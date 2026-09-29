<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Commission;
use App\Models\CommissionAccommodation;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
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

class CommissionController extends Controller
{
    protected  $page = 'commission';
    protected  $lang = 'Contracts';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Commission();
        $this->sortableColumns = [
            0 => 'host',
            1 => 'accomodation',
            2 => 'percentage',
            3 => 'validity',
            // 3 => 'start_date',
            // 4 => 'end_date',
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
        Gate::authorize('Discount-section');
        
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
                /*if(isset($value) && $value->all_properties == 'Yes'){
                    $all_accommodation = Property::select('title')->get();
                    $accommodation = "";
                    foreach ($all_accommodation as $ac_value) {
                        $accommodation != "" && $accommodation .= ", ";
                        $accommodation .= $ac_value->title;
                    }
                }else*/if(isset($value) && $value->all_properties == 'No'){
                    $CommissionAccommodation = CommissionAccommodation::where('commission_id',$value->id)->pluck('property_id')->toArray();
                    $all_accommodation = Property::select('title')->whereIn('id', $CommissionAccommodation)->get();
                    $accommodation = "";
                    foreach ($all_accommodation as $ac_value) {
                        $accommodation != "" && $accommodation .= ", ";
                        $accommodation .= $ac_value->title;
                    }                    
                }else{
                    $accommodation = 'All';
                }
                // dd($accommodation);
                $row['id'] = $i;
                if(isset($value) && $value->all_hosts == 'Yes'){
                    $host = 'All';
                }else{
                    $host = User::where(['id'=>$value->host_id, 'user_type'=>5])->pluck('name')->first();
                }
                $row['host'] = $host;
                // $row['host'] = User::where(['id'=>$value->host_id, 'user_type'=>5])->pluck('name')->first();
                $row['accomodation'] = $accommodation;
                $row['percentage'] = $value->percentage;
                $row['validity'] = date('d M Y', strtotime($value->start_date)) .' - '.date('d M Y', strtotime($value->end_date));
                // $row['start_date'] = date('d M Y', strtotime($value->start_date));
                // $row['end_date'] = date('d M Y', strtotime($value->end_date));
                // $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
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
        Gate::authorize('Discount-section');
        $user = User::where(['user_type'=> '5','status'=>1])->get();
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
        Gate::authorize('Discount-create');
        $user = User::where(['user_type'=> '5','status'=>1])->get();
        $properties = Property::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'user'=>$user, 'properties'=>$properties];
        return view('admin.'.$this->page.'.create',$data);
    }

    public function getAccomodationByHost($host_id)
    {
        Gate::authorize('Discount-create');    
        $properties = Property::where(['status'=>1, 'host'=>$host_id])->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'properties'=>$properties];
        return view('admin.'.$this->page.'.accomodation_list',$data);
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
            // 'code'          => 'required|max:190',
            'percentage'    => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Commission;

            $data->host_id = $input['host_id'];
            $data->percentage = $input['percentage'];
            $data->start_date = $input['start_date'];
            $data->end_date = $input['end_date'];
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                $data->all_properties = 'Yes';
            }else{
                $data->all_properties = 'No';
            }
            if(isset($input['all_hosts']) && $input['all_hosts'] == 'Yes'){
                $data->all_hosts = 'Yes';
            }else{
                $data->all_hosts = 'No';
            }
            $data->status = 1;
            if($data->save()){
                if(isset($input['property_id']) && !empty($input['property_id'])){
                    foreach($input['property_id'] as $property){
                        $property_name = Property::where('id',$property)->pluck('title')->first();
                        $data_service = new CommissionAccommodation;
                        $data_service->commission_id = $data->id;
                        $data_service->property_id = $property;
                        $data_service->property_name = $property_name;
                        $data_service->save();
                    }
                }
            }
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
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
        $selected_commission_properties = CommissionAccommodation::where('commission_id',$id)->pluck('property_id')->toArray();
        if(isset($selected_commission_properties) && count($selected_commission_properties) > 0){
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->whereIn('properties.id',$selected_commission_properties)->get();
        }else{
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->get();
        }
        // dd($data);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'accommodations'=>$accommodations];
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
        Gate::authorize('Discount-edit');
        $id = $request->id;
        $data = $this->Models->find($id);
        $user = User::where(['user_type'=> '5','status'=>1])->get();
        $properties = Property::where('status',1)->get();
        $selected_commission_properties = CommissionAccommodation::where('commission_id',$id)->pluck('property_id')->toArray();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'user'=>$user,'properties'=>$properties,'selected_commission_properties'=>$selected_commission_properties];
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
        Gate::authorize('Discount-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'percentage'    => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
        ]);

        $input = $request->all();
        // dd($input);
        $fail = false;

        if (!$fail) {
            try {
                if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                    $all_properties = 'Yes';
                    CommissionAccommodation::where('commission_id',$id)->delete();
                }else{
                    $all_properties = 'No';
                }
                if(isset($input['all_hosts']) && $input['all_hosts'] == 'Yes'){
                    $all_hosts = 'Yes';
                    $host_id = Null;
                }else{
                    $all_hosts = 'No';
                    $host_id = $input['host_id'];
                }
                $data = Commission::where('id', $id)->update(['host_id' => $host_id,'percentage' => $input['percentage'],'start_date' => $input['start_date'],'end_date' => $input['end_date'],'all_properties'=>$all_properties,'all_hosts'=>$all_hosts ]);
                if(isset($input['property_id'])){
                    if(!isset($input['all_properties'])){
                        CommissionAccommodation::where('commission_id',$id)->delete();
                        foreach($input['property_id'] as $property){
                            $property_name = Property::where('id',$property)->pluck('title')->first();
                            $data_service = new CommissionAccommodation;
                            $data_service->commission_id = $id;
                            $data_service->property_id = $property;
                            $data_service->property_name = $property_name;
                            $data_service->save();
                        }
                    }
                }else{
                    CommissionAccommodation::where('commission_id',$id)->delete();
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
        Gate::authorize('Discount-delete');
        if(Commission::findOrFail($id)->delete()){
            CommissionAccommodation::where('commission_id',$id)->delete();
            $response['status'] = true;
            $response['message'] = 'Discount delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Discount does not delete. ';
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
        $details = Commission::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = Commission::findOrFail($id);
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
