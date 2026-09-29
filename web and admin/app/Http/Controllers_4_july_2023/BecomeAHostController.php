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
use App\Models\Country;
use App\Models\Property;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\EmailTemplateLang;
use App\Models\BecomeAHost;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class BecomeAHostController extends Controller
{
    protected  $page = 'become_a_host';
    protected  $lang = 'Become A Host';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new BecomeAHost();
        $this->Models_sev = new Country;  
        $this->Models_pro = new Province;  
        $this->sortableColumns = [
            0 => 'name',
            1 => 'mobile',
            2 => 'email',
            3 => 'title',
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
        // dd($request->all());
        Gate::authorize('Host-section');
        
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
            // $user_type = 6;
            $sortableColumns = $this->sortableColumns;
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date)->Where('is_become_host',1);
            // dd($querydata->get());
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
                $row['name'] = isset($value->user_name)? $value->user_name:'N/A';
                $row['mobile'] = '+'.$value->country_code.'-'.$value->mobile ?? 'N/A';
                $row['email'] = isset($value->email)? $value->email:'N/A';
                $row['title'] = isset($value->title)? $value->title:'N/A';
              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                // if(isset($value) && $value->host_property_status == 'None'){
                //     $status = '<a href="javascript:void(0)" data-id="'.$value->id.'" title="Accepted Account" data-status="Accept" class="change_status">
                //                 <span class="label label-rounded label-success"><i class="bx bx-check" aria-hidden="true"></i></span>
                //             </a>
                //             <a href="javascript:void(0)" data-id="'.$value->id.'" title="Rejected Account" data-status="Reject" class="change_status">
                //                 <span class="label label-rounded label-danger"><i class="bx bx-x" aria-hidden="true"></i></span>
                //             </a>';
                // }else if($value->host_property_status == 'Reject'){
                //     $status = '<p>Reject</p>';
                // }else{
                //     $status = '<p>Accept</p>';
                // }
                // $row['status'] = $status;
                if($value->host_property_status == 'Accept'){
                    $book_type = 'checked';
                }else{
                    $book_type = '';
                }
                $row['status'] = '<div class="form-check-danger form-check form-switch"><input class="form-check-input flexSwitchCheckCheckedDanger" type="checkbox" id="'.$value->id.'" '.$book_type.'></div>';
                // $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();

                // $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-user_id="'.$value->user_id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                if (!auth()->user()->can('Host-delete')) {
                    $delete = '';
                }
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
              
                $row['actions']=createAction($view.$delete);
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

    public function status(Request $request)
    {
        $input = $request->all();
        // $id = $request->id;
        // $status = $request->status;
        $id = $input['id'];
        $status = $input['value'];
        // dd($id);
        try {
            $data = $this->Models->findOrFail($id);
            // dd($data);
            if($status == 'Accept'){
                $data->host_property_status = 'Accept';

                $owner_permissions = ['41','42','43','44','49','50','51','52','53','54','55','56','57','58','59','60','69','70','71','72','81','82','83','84'];
                foreach ($owner_permissions as $key => $value) {
                    // dd(User::where('id',$data->host)->first());
                    $user = User::where('id',$data->host)->first();
                    // $user = User::findOrFail($data->host);
                    // dd($user);
                    if(isset($user) && !empty($user)){
                        $user->givePermissionTo($value);
                    }
                }
                // $data->host_property_status = 'Accept';
                // $data->user_type = 5;
                // $data->status = 1;
            }else{
                $data->host_property_status = 'Reject';
            }
            // $data->status = $request->value;
            $data->save();
            return ['status'=>1,'type'=>'success','message'=>'Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function frontend()
    {
        Gate::authorize('Host-section');
        $user = User::where('user_type', '5')->get();
        $roles = Role::all();
        $data= ['title'=>$this->lang,'page'=>$this->page,'roles'=>$roles]; 
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
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country = $this->Models_sev->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $country_all = Country::select('*')->orderBy('id','asc')->get();
        // $country = $this->Models_sev->orderBy('phonecode','asc')->get();
        // $country_all = Country::select('*')->orderBy('id','asc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'country_phone'=>$country_phone,'country'=>$country, 'country_all'=>$country_all,'province'=>$province];
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
            'title'          => 'required',
            'name'          => 'required|max:190',
            'surname'          => 'required|max:190',
            'email'         => 'required|email|unique:users|max:50|regex:/(.+)@(.+)\.(.+)/i',
            'country_code'  => 'required',
            'mobile' => 'required|numeric|unique:users|digits_between:8,15|regex:/^([1-9][0-9\s\-\+\(\)]*)$/', 
        ]; 
        $message = [
            'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            // $secure_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
            $data = new User;

            if(isset($input['dob']) && $input['dob'] != null){
                $dob = date('y/m/d', strtotime($input['dob']));
            }else{
                $dob = null;
            }
            if ($request->file('image')) {
     
                $file = $request->file('image');
            
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
               
            }
            if ($request->file('document_image')) {
                $document_file = $request->file('document_image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                $data->document_image = $document_result['file'];
               
            }
            $unique_id = random_int(10000000, 99999999);
            $data->unique_id = $unique_id;
            $data->title = $input['title'];
            $data->name = $input['name'];
            $data->surname = $input['surname'];
            $data->remarks = $input['remarks']??null;
            $data->email = $input['email'];
            $data->mobile = $input['mobile'];
            $data->country_code = $input['country_code'];
            $data->is_super_host = $input['is_super_host'] ?? null;
            $data->is_chat_disabled_for_host = $input['is_chat_disabled_for_host'] ?? null;
            $data->secondary_email = $input['secondary_email']??null;
            $data->second_country_code = $input['second_country_code']??null;
            $data->second_mobile = $input['second_mobile']??null;
            $data->document_number = $input['document_number']??null;
            $data->password = bcrypt($request->password);
            $data->dob = $dob;

            $data->street = $input['street']??null;
            $data->street_number = $input['street_number']??null;
            $data->number = $input['number']??null;
            $data->postal_code = $input['postal_code']??null;
            // $data->country = $input['country'];
            $data->country_id = $input['country_id']??null;
            $data->province_id = $input['province_id']??null;
            $data->city_id = $input['city_id']??null;
            $data->status = 1;
            $data->user_type = 5;
            $data->role = 'Host';
            $data->save();
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function store_old(Request $request)
    {
        Gate::authorize('Host-create');
        // validate
        $input = $request->all();
        $mesasge = [
            'name.required' => "Name field is required.",
            'email.required' => "Email field is required.",
            'email.email' => "Please enter valid email.",
            'email.unique' => "Email is already taken.",
            'country_code.required' => "Country code field is required.",
            // 'gender.required' => __("backend.gender_required"),
            'mobile.required' => "Mobile number field is required.",
            'mobile.digits_between' => "Mobile number must be in between 7 to 15 digits only.",
            'image.size'  =>  "Image is too large.",
        ];
        $this->validate($request, [
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => 'required|email|unique:users,email',
            'country_code' => 'required',
            // 'gender' => 'required',
            'mobile' => 'required|digits_between:7,15|unique:users,mobile',

        ], $mesasge);

        $fail = false;
        if (!$fail) {
            try {

                $data = new User;
                if ($request->file('image')) {
                    $file = $request->file('image');
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    $data->image = $result['file'];
                    
                }
                $unique_id = random_int(10000000, 99999999);
                $data->unique_id = $unique_id;
                $data->name = $input['name'];
                $data->email = $input['email'];
                $data->mobile = $input['mobile'];
                $data->country_code = $input['country_code'];
                $data->is_super_host = $input['is_super_host'] ?? null;
                $data->is_chat_disabled_for_host = $input['is_chat_disabled_for_host'] ?? null;
                $data->status = 1;
                $data->type = 4;
                $data->role = 'Customer';
                $data->save();
                // set permissions
                    /*$restro_permissions = ['148', '149', '150', '151', '163', '164', '165', '166'];
                    foreach ($restro_permissions as $key => $value) {
                        $user = User::findOrFail($data->id);
                        $user->givePermissionTo($value);
                    }*/
                // end permissions
                // forgot password start
                $record = $data;
                $token = Str::random(60);
                $email =  $record->email;
                $user = ModelsPasswordReset::where('email', $email)->first();
                if (!isset($user)) {
                    $user = new ModelsPasswordReset;
                    $user->email = $email;
                    $user->token = $token;
                    $user->save();
                } else {
                    ModelsPasswordReset::where('email', $email)->update(['token' => $token]);
                }
                $user = ModelsPasswordReset::where('email', $email)->first();

                // send email start
                $email = EmailTemplateLang::where('email_id', 3)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                $description = $email->description;
                $description = str_replace("[NAME]", $facility_owner->name, $description);
                $name = $email->name;
                $name = str_replace("[NAME]", $facility_owner->name, $name);
                $record = (object)[];
                $record->description = $description;
                $record->footer = $email->footer;
                $record->name = $name;
                $record->subject = $email->subject;
                $record->user_email = $user->email;
                $record->user_token = $user->token;

                Mail::send('emails.welcome', compact('record'), function ($message) use ($facility_owner, $email) {
                    $message->to($facility_owner->email, config('app.name'))->subject($email->subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });
                // send email end
                // forgot password end
                $result['message'] = 'Record added successfully.';
                $result['status'] = 1;
                return response()->json($result);
            } catch (Exception $e) {
                $result['message'] = 'Something went wrong';
                $result['status'] = 0;
                return response()->json($result);
            }
        } else {
            $result['message'] = 'Something went wrong';
            $result['status'] = 0;
            return response()->json($result);
        }
        return response()->json($result);
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
        $property = Property::where('id',$id)->first();
        $data = User::where('id',$property->host)->first();
        // dd($data);
        // $data = $this->Models->where('properties.id',$id)->first();
        // $bookings = Booking::select('bookings.*','properties.title')->where(['bookings.host_id'=>$id, 'bookings.status'=>1])->leftjoin('properties','properties.id','=','bookings.property_id')->get();
        // $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->where(['properties.host'=>$id])->get();
        $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->where(['properties.host'=>$property->host])->get();
        // dd($accommodations);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'accommodations'=>$accommodations];
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
        // $country = $this->Models_sev->orderBy('phonecode','asc')->get();
        // $country_all = Country::select('*')->orderBy('id','asc')->get();
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country_all = Country::select('*')->orderBy('id','asc')->get();
        
        $country = $this->Models_sev->orderBy('name','asc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'country_phone'=>$country_phone,'country'=>$country,'country_all'=>$country_all];
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

    public function update_old(Request $request)
    {     
        $data = $this->Models->find($request->id);
        $input = $request->all();
        $message = [];       
        $validation = [
            'name'     => 'required|max:190',
            'email' => 'required|max:190|email|unique:users,email, '. $data->id . ',id',
            'country_code'  => 'required',
            'mobile_number'=>'required|numeric|digits_between:8,15|regex:/^([1-9][0-9\s\-\+\(\)]*)$/|unique:users,mobile_number, '. $data->id . ',id,country_code,'.str_replace('+','',$input['country_code']),
        ];  
        // dd($input, $data);     
        $this->validate($request,$validation, $message);

        try{
            $user = $this->Models->where('id',$input['id'])->first();
            $input['country_code']=str_replace('+','',$input['country_code']);
            unset($input['_token']);
            $data->update($input);
            return Redirect::route('admin.host.index')->with('success',"Record updated successfully.");

        } catch (Exception $e) {
            return Redirect::Back()->with('error',__('backend.something_went_wrong'));
        }
    }
    public function update(Request $request)
    {
        Gate::authorize('Host-edit');
        // validate
        $id = $request->id;
        $mesasge = [
            'name.required' => __("backend.name_required"),
            'email.required' => __("backend.email_required"),
            'email.email' => __("backend.email_email"),
            'email.unique' => __("backend.email_unique"),
            'country_code.required' => __("backend.country_code_required"),
            // 'gender.required' => __("backend.gender_required"),
            'mobile.required' => __("backend.mobile_required"),
            'mobile.digits_between' => __("backend.mobile_digits_between"),
            'image.size'  =>  __("backend.image_size"),

        ];
        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:190',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => 'required|email|unique:users,email,' . $id,
            'country_code' => 'required',
            // 'gender' => 'required',
            'mobile' => 'required|digits_between:7,15|unique:users,mobile,' . $id,
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            // dd($input);
            $file = $request->file('image');
            $document_file = $request->file('document_image');
            try {
                if(isset($input['dob']) && $input['dob'] != null){
                    $dob = date('y/m/d', strtotime($input['dob']));
                }else{
                    $dob = null;
                }
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);


                    if(isset($document_file)){

                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                    

                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }
                    }
                } else {
                    if(isset($document_file)){

                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);


                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }
                    }
                }
                return Redirect::route('admin.host.index')->with('success',"Record updated successfully.");
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
        // dd($id);
        if(User::findOrFail($id)->first()){
          //  Property::where('host',$id)->delete();
          $user =   User::findOrFail($id);
          $user->is_become_host = 0;
          $user->save();


            $response['status'] = true;
            $response['message'] = 'Become A Host delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Owner does not delete. ';
        }
        return $response;
        // return Courts::findOrFail($id)->delete();
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
