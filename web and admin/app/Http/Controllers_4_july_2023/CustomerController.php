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
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\EmailTemplateLang;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\BankData;
use App\Models\EmailTemplate;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    protected  $page = 'customer';
    protected  $lang = 'Guest';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new User();
        $this->Models_sev = new Country;  
        $this->Models_pro = new Province;  
        $this->sortableColumns = [
            0 => 'unique_id',
            1 => 'name',
            2 => 'mobile',
            3 => 'email',
            4 => 'status',
            5 => 'updated_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Customer-section');
        
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
            $user_type = 4; 
            $sortableColumns = $this->sortableColumns;            
            $querydata = $this->Models->getModelUser($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date,$user_type);
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
                $row['mobile'] = '+'.$value->country_code.'-'.$value->mobile ?? 'N/A';
                $row['email'] = isset($value->email)? $value->email:'N/A';
                $row['unique_id'] = isset($value->unique_id)? $value->unique_id:'N/A';
                $row['role'] = isset($value->role)? $value->role:'N/A';
                // $row['barcode'] = '<img alt="Barcoded value 1234567890"
                // src="http://bwipjs-api.metafloor.com/?bcid=code128&text='.$value->barcode.'&includetext" style="max-width: 150px !important;max-height: 150px !important;">';
                
                // $row['capacity'] = isset($value->capacity)? $value->capacity:'-';
                // $row['manufacturing_date'] = isset($value->manufacturing_date)? date(' d, M Y', strtotime($value->manufacturing_date)):'-';
                // $row['refurbishing_date'] = isset($value->refurbishing_date)? date(' d, M Y', strtotime($value->refurbishing_date)):'-';
                // $row['unique_number'] = isset($value->unique_number)? $value->unique_number:'-';
                // $row['crate_id'] = isset($value->crate_id)? $value->crate_id:'-';
                // $row['batch_id'] = isset($value->batch_id)? $value->batch_id:'-';
              
                $row['created_at'] = date('d M Y', strtotime($value->updated_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();


                // if($value->status==1) {
                //     $row['status']='<a href="'.route('admin.'.$this->page.'.status',[$value->id,0]).'" class="btn btn-primary btn-primary-light" onclick=\'return confirm("Are you Sure you want to Inactivate this record?")\'>Active</a>';    
                // }else{
                //     $row['status']='<a href="'.route('admin.'.$this->page.'.status',[$value->id,1]).'" class="btn btn-primary btn-primary-light" onclick=\'return confirm("Are you sure you want to Activate this record?")\'>Inactive</a>';
                // }
               
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
                if (!auth()->user()->can('Customer-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Customer-delete')) {
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
        Gate::authorize('Customer-section');
        $user = User::where('user_type', '4')->get();
        $roles = Role::all();
        $data= ['title'=>$this->lang,'page'=>$this->page,'roles'=>$roles]; 
        return view('admin.customer.listing', $data);
    }

    public function edit_frontend($id)
    {
        Gate::authorize('Customer-edit');
        //$data['roles']=Role::all();
        $data['users'] = User::findOrFail($id);
        $data['country_all'] = Country::select('*')->orderBy('id','asc')->get();
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
        Gate::authorize('Customer-create');
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $country_all = Country::select('*')->orderBy('position','desc')->get();
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
            $data->country_code = $input['country_code'];
            $data->mobile = $input['mobile'];
            $data->secondary_email = $input['secondary_email']??null;
            $data->second_country_code = $input['second_country_code']??null;
            $data->second_mobile = $input['second_mobile']??null;
            $data->document_number = $input['document_number']??null;
            $data->password = bcrypt($request->password);
            $data->dob = $dob;

//            $data->street = $input['street']??null;
//            $data->landmark = $input['landmark']??null;
            $data->address = $input['address']??null;
            $data->latitude = $input['latitude']??null;
            $data->longitude = $input['longitude']??null;
          //  $data->number = $input['number']??null;
            $data->postal_code = $input['postal_code']??null;
            // $data->country = $input['country'];
            $data->country_id = $input['country_id']??null;
            $data->province_id = $input['province_id']??null;
            $data->city_id = $input['city_id']??null;
            $data->status = 1;
            $data->user_type = 4;
            $data->role = 'Customer';
            $data->save();

            //Bank data
            if (isset($input['method_of_payment']) && !empty($input['method_of_payment'])){
                $bank_data = new BankData;
                $bank_data->user_id = $data->id;
                $bank_data->method_of_payment = $input['method_of_payment'];
                if(isset($input['account_holder'])){
                    $bank_data->account_holder = $input['account_holder']??null;
                }
                $bank_data->account_holder_name = $input['account_holder_name']??null;
                $bank_data->account_number = $input['account_number']??null;
                $bank_data->iban = $input['iban']??null;
                $bank_data->vat_number = $input['vat_number']??null;
                $bank_data->fiscal_code = $input['fiscal_code']??null;
                $bank_data->route = $input['route']??null;
                $bank_data->retention = $input['retention']??null;
                $bank_data->ledger_account = $input['ledger_account']??null;
                $bank_data->bic_swift = $input['bic_swift']??null;
                $bank_data->tax = $input['tax']??null;
                $bank_data->bank_name = $input['bank_name']??null;
                $bank_data->cnae_code = $input['cnae_code']??null;
                // $bank_data->save();
                if($bank_data->save()){
        			$email = EmailTemplateLang::where('email_id', 1)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
		            $subject = $email->subject;
		            $subject = str_replace("[NAME]", $input['name'], $email->subject);
		            $messsage = $message;
		            $description = $email->description;
		            $description = str_replace("[NAME]", $input['name'], $email->description);

		            $register_detail=(object)[];
		            $register_detail->name = str_replace("[NAME]", $input['name'], $email->name);
		            $register_detail->subject = $subject;
		            $register_detail->description = $description;
		            $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
                    $user = User::where('id',$data->id)->first();

		            Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                        $message->to($user->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com',config('app.name'));
                    });

                    $userdata = User::where('id',$data->id)->first();
                    $notificationData = new Notification;
                    $notificationData->user_type = $userdata->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Registration';
                    $notificationData->title = 'Welcome';
                    $notificationData->message = 'Welcome to Shortlet Rental.';
                    $notificationData->user_id = $userdata->id;
                    $notificationData->save();
                    send_notification(1, $userdata->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                }
            }
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
           // dd($e);
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
        $data = $this->Models->where('users.id',$id)->first();
        $bookings = Booking::select('bookings.*','properties.title')->where(['bookings.guest_id'=>$id, 'bookings.status'=>1])->leftjoin('properties','properties.id','=','bookings.property_id')->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData')->get();
        // dd($bookings, $id);

        $bank_data = BankData::where('user_id',$id)->first();

        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'bookings'=>$bookings, 'bank_data'=>$bank_data];
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
        Gate::authorize('Customer-edit');
        $id = $request->id;
        $data = $this->Models->find($id);       
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country_all = Country::select('*')->orderBy('position','desc')->get();
        $bank_data_exist = BankData::where('user_id',$id)->first();
        if(isset($bank_data_exist) && !empty($bank_data_exist)){
            $bank_data = $bank_data_exist;
        }else{
            $bank_data = '';
        }
        
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'country_phone'=>$country_phone, 'country_all'=>$country_all,'country'=>$country,'province'=>$province, 'bank_data'=>$bank_data];
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
        Gate::authorize('Customer-edit');
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
            'surname'          => 'required|max:190',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => 'required|email|unique:users,email,' . $id,
            'country_code' => 'required',
            // 'gender' => 'required',
            'mobile' => 'required|digits_between:7,15|unique:users,mobile,' . $id,
        ], $mesasge);

        $input = $request->all();
        // dd($input);
        $fail = false;

        if (!$fail) {
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
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }
                    }
                } else {
                    if(isset($document_file)){

                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);

                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'address' => $input['address'],'latitude'=>$input['latitude'],'longitude'=>$input['longitude'],    'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'document_number' => $input['document_number'] ]);
                        }
                    }
                }

                $bank_data = BankData::where('user_id', $id)->first();
                if(isset($bank_data) && !empty($bank_data)){
                    BankData::where('user_id', $id)->update(['method_of_payment' => $input['method_of_payment']??null,  'account_holder_name' => $input['account_holder_name']??null, 'account_number' => $input['account_number']??null, 'iban' => $input['iban']??null, 'vat_number' => $input['vat_number']??null, 'fiscal_code' => $input['fiscal_code']??null, 'route' => $input['route']??null, 'retention' => $input['retention']??null, 'ledger_account' => $input['ledger_account']??null, 'bic_swift' => $input['bic_swift']??null, 'tax' => $input['tax']??null, 'bank_name' => $input['bank_name']??null, 'cnae_code' => $input['cnae_code']??null]);
                }else{
                    // dd($input);
                    $bank_data = new BankData;
                    $bank_data->user_id = $id;
                    $bank_data->method_of_payment = $input['method_of_payment'];
                    if(isset($input['account_holder'])){
                        $bank_data->account_holder = $input['account_holder']??null;
                    }
                    $bank_data->account_holder_name = $input['account_holder_name']??null;
                    $bank_data->account_number = $input['account_number']??null;
                    $bank_data->iban = $input['iban']??null;
                    $bank_data->vat_number = $input['vat_number']??null;
                    $bank_data->fiscal_code = $input['fiscal_code']??null;
                    $bank_data->route = $input['route']??null;
                    $bank_data->retention = $input['retention']??null;
                    $bank_data->ledger_account = $input['ledger_account']??null;
                    $bank_data->bic_swift = $input['bic_swift']??null;
                    $bank_data->tax = $input['tax']??null;
                    $bank_data->bank_name = $input['bank_name']??null;
                    $bank_data->cnae_code = $input['cnae_code']??null;
                    $bank_data->save();
                }
                return Redirect::route('admin.customer.index')->with('success',"Record updated successfully.");
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
        Gate::authorize('Customer-delete');
        if(User::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Customer delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Customer does not delete. ';
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
