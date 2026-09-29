<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Discount;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Rate;
use App\Models\Country;
use App\Models\Property;
use App\Models\EmailTemplateLang;
use App\Models\DiscountProperty;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class RateController extends Controller
{
    protected  $page = 'rate';
    protected  $lang = 'Rate';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Rate();
        // $this->Models_cat = new Category();
        $this->sortableColumns = [
            0 => 'title',
            1 => 'price',
            2 => 'start_date',
            3 => 'end_date',
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
                $categoryDetail = Category::where('id', $value->category_id)->first();
                $row['id'] = $i;
                $row['title'] = isset($value->all_properties) && $value->all_properties == 'Yes' ? 'All' : $value->title;
                $row['price'] = isset($value->price) && !empty($value->price) ? $value->price : '0';
                $row['start_date'] = isset($value->start_date) && !empty($value->start_date) ? $value->start_date : '-';
                $row['end_date'] = isset($value->end_date) && !empty($value->end_date) ? $value->end_date : '-';
                
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
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
      
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'status'=>$status];
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function frontend()
    {
        Gate::authorize('Rate-section');
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
        Gate::authorize('Rate-create');
        $properties = Property::where('status',1)->get();
        $data = [];
        $data= ['title'=>$this->lang,'page'=>$this->page, 'properties'=>$properties, 'data'=>$data];
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
        if(isset($input['all_properties']) && $input['all_properties'] == 'Yes' ){
            $message = [];
            $validation = [];
        }else{
            $message = [
                'property_id.required' => 'Select minimum one accommodation.',
            ];
            $validation = [
                'property_id' => 'required',
            ];
        }
        $this->validate($request,$validation,$message);       
        try{
            // dd($input);
            $data = new Rate;
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes' ){
                // dd('iff');
                $data->price = $input['price'];
                $data->start_date = $input['start_date'];
                $data->end_date = $input['end_date'];
                $data->all_properties = 'Yes';
            }else{
                // dd('else');
                $data->property_id = $input['property_id'];
                $data->price = $input['price'];
                $data->start_date = $input['start_date'];
                $data->end_date = $input['end_date'];
                $data->all_properties = 'No';
            }
            $data->save();
            $return['status'] = true;
            $return['message'] = 'Accommodation Rate saved successfully.';
            
        } catch (Exception $e) {
            // dd($e);
            $return['status'] = false;
            $return['message'] = 'Accommodation does not saved.';
        }
        return $return;
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
        $data = Rate::select('rates.*','properties.title')->leftjoin('properties','properties.id','=','rates.property_id')->where('rates.id',$id)->first();
        
        // dd($data);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
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
        Gate::authorize('Rate-edit');
        $id = $request->id;
        $data = $this->Models->find($id);
        $properties = Property::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'properties'=>$properties];
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

    public function update(Request $request){
        $input = $request->all();
        $id = $input['rate_id'];
        if(isset($id) && !empty($id)){
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes' ){
                $message = [];
                $validation = [];
            }else{
                $message = [
                    'property_id.required' => 'Select minimum one accommodation.',
                ];
                $validation = [
                    'property_id' => 'required',
                ];
            }
            $this->validate($request,$validation,$message);       
            try{
                // dd($input);
                if(isset($input['all_properties']) && $input['all_properties'] == 'Yes' ){
                    // dd('iff');
                    $data = array(
                        'price' => $input['price'],
                        'start_date' => $input['start_date'],
                        'end_date' => $input['end_date'],
                        'all_properties' => 'Yes',
                        'property_id' => null
                    );
                }else{
                    // dd('else');
                    $data = array(
                        'property_id' => $input['property_id'],
                        'price' => $input['price'],
                        'start_date' => $input['start_date'],
                        'end_date' => $input['end_date'],
                        'all_properties' => 'No'
                    );
                }
                // dd($data, $id);
                Rate::where('id',$id)->update($data);
                $return['status'] = true;
                $return['message'] = 'Accommodation Rate update successfully.';
                
            } catch (Exception $e) {
                // dd($e);
                $return['status'] = false;
                $return['message'] = 'Accommodation does not saved.';
            }
        }else{
            $return['status'] = false;
            $return['message'] = 'Invalid rate accommodation.';
        }
        return $return;
    }

    public function update_old(Request $request)
    {
        Gate::authorize('Rate-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'code'          => 'required|max:190',
            'percentage'    => 'required',
            'category_id'   => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'total_use'     => 'required',
            'total_single_use' => 'required',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                    $all_properties = 'Yes';
                    DiscountProperty::where('discount_id',$id)->delete();
                }else{
                    $all_properties = 'No';
                }
                if (isset($file)) {
              
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'discount/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    $data = Discount::where('id', $id)->update(['code' => $input['code'],'percentage' => $input['percentage'],'category_id' => $input['category_id'],'start_date' => $input['start_date'],'end_date' => $input['end_date'],'total_use' => $input['total_use'],'total_single_use' => $input['total_single_use'],'all_properties'=>$all_properties,'influencer'=>$influencer, 'image' => $result['file'] ]);
                } else {
                    $data = Discount::where('id', $id)->update(['code' => $input['code'],'percentage' => $input['percentage'],'category_id' => $input['category_id'],'start_date' => $input['start_date'],'end_date' => $input['end_date'],'total_use' => $input['total_use'],'total_single_use' => $input['total_single_use'],'all_properties'=>$all_properties,'influencer'=>$influencer ]);
                }
                if(isset($input['property_id'])){
                    if(!isset($input['all_properties'])){
                        DiscountProperty::where('discount_id',$id)->delete();
                        foreach($input['property_id'] as $property){
                            $property_name = Property::where('id',$property)->pluck('title')->first();
                            $data_service = new DiscountProperty;
                            $data_service->discount_id = $id;
                            $data_service->property_id = $property;
                            $data_service->property_name = $property_name;
                            $data_service->save();
                        }
                    }
                }else{
                    DiscountProperty::where('discount_id',$id)->delete();
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
        // Gate::authorize('Rate-delete');
        if(Rate::findOrFail($id)->delete()){
            // DiscountProperty::where('discount_id',$id)->delete();
            $response['status'] = true;
            $response['message'] = 'Rate Accommodation delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Rate Accommodation does not delete. ';
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
