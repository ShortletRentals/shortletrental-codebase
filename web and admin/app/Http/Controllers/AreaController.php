<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Province;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class AreaController extends Controller
{
    protected  $page = 'area';
    protected  $lang = 'Area';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Area();
        $this->Models_sev = new Country;
        $this->Models_pro = new Province();
        $this->Models_city = new City;
        $this->sortableColumns = [
            0 => 'country_name',
            1 => 'province_name',
            2 => 'city_name',
            3 => 'name',
            4 => 'status',
            5 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Area-section');
        
        if ($request->wantsJson()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];
            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $status = $request['status'] ?? null;
            $start_date = $request['start_date'] ?? null ;
            $end_date = $request['end_date'] ?? null; 
            $sortableColumns = $this->sortableColumns;            
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date);
            $totaldata = $querydata->count();
            $response = $querydata;
          
            $response = $response->offset($start)
                    ->limit($limit)
                    ->get();

            if (!$response) {
                $data = [];
                $paging = [];
            } else {
                $data = $response;
                $paging = $response;
            }
            
            $datas = array();
            $i = 1;
            foreach ($data as $value) {
                $countryDetail = $this->Models_sev->where('id',$value->country_id)->first();
                $provinceDetail = $this->Models_pro->where('id',$value->province_id)->first();
                $cityDetail = $this->Models_city->where('id',$value->city_id)->first();
                $row['id'] = $i;
                $row['country_name'] = isset($countryDetail->name)? $countryDetail->name:'N/A';
                $row['province_name'] = isset($provinceDetail->name)? $provinceDetail->name:'N/A';
                $row['city_name'] = isset($cityDetail->name)? $cityDetail->name:'N/A';
                $row['name'] = isset($value->name)? $value->name:'N/A';
                $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
                if (!auth()->user()->can('Area-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Area-delete')) {
                    $delete = '';
                }
                $row['actions']=createAction($edit.$view.$delete);
                $datas[] = $row;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($totaldata),
                "recordsTotal" => intval($totaldata),
                "data" => $datas
            ];
            return $return;
        }
        if(isset($request->user_type)){
            $user_type = $request->user_type;

        }else{
            $user_type = null;
        }
        if(isset($request->status)){
            $status = $request->status;

        }else{
            $status = null;
        }
      
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'status'=>$status];
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function frontend()
    {
        Gate::authorize('Area-section');
        $user = User::where('user_type', '2')->get();
        $roles = Role::all();
        $data= ['title'=>$this->lang,'page'=>$this->page,'roles'=>$roles]; 
        return view('admin.'.$this->page.'.listing', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Area-create');
        $country = $this->Models_sev->orderBy('name','asc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'country'=>$country,'province'=>$province];        
        return view('admin.'.$this->page.'.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'country_id'          => 'required',
            'province_id'          => 'required',
            'city_id'          => 'required',
            'name'          => 'required|max:190|unique:areas',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Area;

            if ($request->file('image')) {
                    $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'area/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->city_id = $input['city_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function areaStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'country_id'          => 'required',
            'province_id'          => 'required',
            'city_id'          => 'required',
            'name'          => 'required|max:190|unique:areas',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Area;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'area/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->city_id = $input['city_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            // $result['status'] = true;
            // $result['message'] = 'Record added successfully.';
            $allRecords = Area::where(['status'=>1, 'country_id'=>$input['country_id'], 'province_id'=>$input['province_id'], 'city_id'=>$input['city_id'] ])->get();
            
            $data['allRecords'] = $allRecords;
            $result['status'] = true;
            $result['message'] = view('admin.'.$this->page.'.all_area',$data)->toHtml();
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $data = $this->Models->where('id',$id)->first();
        $country = Country::where('id',$data->country_id)->pluck('name')->first();
        $province = Province::where('id',$data->province_id)->pluck('name')->first();
        $city = City::where('id',$data->city_id)->pluck('name')->first();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'country'=>$country, 'province'=>$province, 'city'=>$city];
        return view('admin.'.$this->page.'.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        Gate::authorize('Area-edit');
        $id = $request->id;
        $country = $this->Models_sev->orderBy('name','asc')->get();
        $data = $this->Models->find($id);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'country'=>$country];
        // dd($data);
        return view('admin.'.$this->page.'.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        Gate::authorize('Area-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'country_id'          => 'required',
            'province_id'          => 'required',
            'city_id'          => 'required',
            'name'          => 'required|max:190|unique:areas,name, '. $id .',id',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'area/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                
                    $data = Area::where('id', $id)->update(['country_id' => $input['country_id'],'province_id' => $input['province_id'],'city_id' => $input['city_id'],'name' => $input['name'], 'image' => $result['file']]);
                } else {
                    $data = Area::where('id', $id)->update(['country_id' => $input['country_id'],'province_id' => $input['province_id'],'city_id' => $input['city_id'],'name' => $input['name']]);
                }
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }

    public function show_province($country_id, $province_id = '') {
      if ($country_id) {
        $province = Province::where(['country_id'=>$country_id])->get();
        $data['records'] = $province;
        $data['country_id'] = $country_id;

      } else {
        $data['records'] = array();
        $data['country_id'] = '';
      }
      $data['province_id'] = $province_id;
      return view('admin.'.$this->page.'.show_province',$data);
    }

    public function show_province_new($country_id, $province_id = '') {
      if ($country_id) {
        $province = Province::where(['country_id'=>$country_id])->get();
        $data['records'] = $province;
        $data['country_id'] = $country_id;

      } else {
        $data['records'] = array();
        $data['country_id'] = '';
      }
      $data['province_id'] = $province_id;
      return view('admin.'.$this->page.'.show_province_new',$data);
    }

    public function show_city($country_id, $province_id, $city_id = '') {
      if ($province_id) {
        $province = City::where(['country_id'=>$country_id,'province_id'=>$province_id])->get();
        $data['records'] = $province;
        $data['country_id'] = $country_id;
        $data['province_id'] = $province_id;

      } else {
        $data['records'] = array();
        $data['country_id'] = '';
        $data['province_id'] = '';
      }
      $data['city_id'] = $city_id;
      return view('admin.'.$this->page.'.show_city',$data);
    }

    public function show_area($country_id, $province_id, $city_id, $area_id = '') {
      if ($city_id) {
        $province = Area::where(['country_id'=>$country_id,'province_id'=>$province_id,'city_id'=>$city_id])->get();
        $data['records'] = $province;
        $data['country_id'] = $country_id;
        $data['province_id'] = $province_id;
        $data['city_id'] = $city_id;
      } else {
        $data['records'] = array();
        $data['country_id'] = '';
        $data['province_id'] = '';
        $data['city_id'] = '';
      }
      $data['area_id'] = $area_id;
      return view('admin.'.$this->page.'.show_area',$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('Area-delete');
        if(Area::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Area delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Area does not delete. ';
        }
        return $response;
    }

    public function status(Request $request)
    {
        $id = $request->id;
        try {
            $data = $this->Models->findOrFail($id);
            $data->status = $request->value;
            $data->save();
            return ['status'=>1,'type'=>'success','message'=>'Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function changeStatus($id, $status)
    {
        $details = User::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = User::findOrFail($id);
            if ($User->update($inp)) {
                if ($status == 'active') {
                    $result['message'] = __("backend.Facility_Owner_status_success");
                    $result['status'] = 1;
                } else {
                    $result['message'] = __("backend.Facility_Owner_status_deactivate");
                    $result['status'] = 1;
                }
            } else {
                $result['message'] = __("backend.Facility_Owner_status_can`t_updated");
                $result['status'] = 0;
            }
        } else {
            $result['message'] = __("backend.Invaild_user");
            $result['status'] = 0;
        }
        return response()->json($result);
    }
}
