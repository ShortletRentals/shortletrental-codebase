<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Courts;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Building;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class BuildingController extends Controller
{
    protected  $page = 'building';
    protected  $lang = 'Building';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Building();
        $this->sortableColumns = [
            0 => 'name',
            1 => 'status',
            2 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        // dd($request->all());
        Gate::authorize('Host-section');
        
        if ($request->wantsJson()) {            
            // dd($request);
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
                $row['id'] = $i;
                $row['name'] = isset($value->name)? $value->name.' '.$value->surname:'N/A';
                // $row['barcode'] = '<img alt="Barcoded value 1234567890"
                // src="http://bwipjs-api.metafloor.com/?bcid=code128&text='.$value->barcode.'&includetext" style="max-width: 150px !important;max-height: 150px !important;">';
                
                // $row['capacity'] = isset($value->capacity)? $value->capacity:'-';
                // $row['manufacturing_date'] = isset($value->manufacturing_date)? date(' d, M Y', strtotime($value->manufacturing_date)):'-';
                // $row['refurbishing_date'] = isset($value->refurbishing_date)? date(' d, M Y', strtotime($value->refurbishing_date)):'-';
                // $row['unique_number'] = isset($value->unique_number)? $value->unique_number:'-';
                // $row['crate_id'] = isset($value->crate_id)? $value->crate_id:'-';
                // $row['batch_id'] = isset($value->batch_id)? $value->batch_id:'-';
              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();


                // if($value->status==1) {
                //     $row['status']='<a href="'.route('admin.'.$this->page.'.status',[$value->id,0]).'" class="btn btn-primary btn-primary-light" onclick=\'return confirm("Are you Sure you want to Inactivate this record?")\'>Active</a>';    
                // }else{
                //     $row['status']='<a href="'.route('admin.'.$this->page.'.status',[$value->id,1]).'" class="btn btn-primary btn-primary-light" onclick=\'return confirm("Are you sure you want to Activate this record?")\'>Inactive</a>';
                // }
               
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
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
      
        $data= ['title'=>$this->lang,'page'=>$this->page,'status'=>$status];
        // dd($data);
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function frontend()
    {
        Gate::authorize('Host-section');
        $user = Building::where('status', '1')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page]; 
        return view('admin.host.listing', $data);
    }

    public function edit_frontend($id)
    {
        Gate::authorize('Host-edit');
        //$data['roles']=Role::all();
        $data['users'] = User::findOrFail($id);
        $data['country'] = Country::select('phonecode', 'name', 'id')->get();
        return view('facility_owner.edit', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Host-create');
        $data= ['title'=>$this->lang, 'page'=>$this->page];
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
            'name' => 'required|max:190|unique:building',
        ]; 
        $message = [
            // 'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            // $secure_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
            $data = new Building;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function buildingStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name' => 'required|max:190|unique:building',
        ]; 
        $message = [
            // 'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
        $this->validate($request,$validation,$message);       
        try{
            $data = new Building;
            // dd($request->file('image'));
            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            // return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            // $result['status'] = true;
            // $result['message'] = 'Record added successfully.';
            // $result['data'] = $allBuildings;
            $allRecords = Building::where('status',1)->get();

            $data['allRecords'] = $allRecords;
            return ['status'=>true, 'message'=>view('admin.'.$this->page.'.all_buildings',$data)->toHtml()];
        } catch (Exception $e) {
            // return customeRedirect('admin.'.$this->page.'.index','','error',$e);
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
        $data = $this->Models->where('building.id',$id)->first();
        // dd($data);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
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
        Gate::authorize('Host-edit');
        $id = $request->id;
        $data = $this->Models->find($id);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
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
        Gate::authorize('Host-edit');
        // validate
        $id = $request->id;
        $mesasge = [
            // 'name.required' => __("backend.name_required"),
        ];
        $this->validate($request, [
            // 'name' => 'required',
            'name' => 'required|max:255|unique:building,name, '. $id .',id',
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'amenity/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    
                    $data = Building::where('id', $id)->update(['name' => $input['name'], 'image' => $result['file']]);
                } else {
                    $data = Building::where('id', $id)->update(['name' => $input['name']]);
                }
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('Host-delete');
        if(Building::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Building delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Building does not delete. ';
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
        $details = Building::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = Building::findOrFail($id);
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
