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
use App\Models\BankData;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

use App\Imports\BulkImport;

class HostController extends Controller
{
    protected  $page = 'host';
    protected  $lang = 'Owner';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new User();
        $this->Models_sev = new Country;  
        $this->Models_pro = new Province;  
        $this->sortableColumns = [
            0 => 'name',
            1 => 'mobile',
            2 => 'email',
            3 => 'role',
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
            $user_type = 5; 
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
                if(isset($value->country_code) && !empty($value->country_code)){
                    $row['mobile'] = '+'.$value->country_code.'-'.$value->mobile ?? 'N/A';
                }else{
                    $row['mobile'] = $value->mobile ?? 'N/A';
                }
                $row['email'] = isset($value->email)? $value->email:'N/A';
                $row['role'] = isset($value->role)? $value->role:'N/A';
              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();

                if($value->is_super_host == 'Yes'){
                    $is_super_host = 'checked';
                }else{
                    $is_super_host = '';
                }
                $row['is_super_host'] = '<div class="form-check-danger form-check form-switch"><input class="form-check-input superHostChange" type="checkbox" id="'.$value->id.'" '.$is_super_host.'></div>';
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                $permission = permissionAction('permissions.user_permissions',['id'=>$value->id]);
              
                if (!auth()->user()->can('Host-edit')) {
                    $edit = '';
                    $permission = '';
                }
                if (!auth()->user()->can('Host-delete')) {
                    $delete = '';
                }
                $row['actions']=createAction($edit.$view.$delete.$permission);
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
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $country_all = Country::select('*')->orderBy('position','desc')->get();
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
                $result =  fileUploads('s3',$file,$folderPath,false);
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
            $data->gender = $input['gender'];

            $data->street = $input['street']??null;
            $data->street_number = $input['street_number']??null;
            // $data->number = $input['number']??null;
            $data->postal_code = $input['postal_code']??null;
            // $data->country = $input['country'];
            $data->country_id = $input['country_id']??null;
            $data->province_id = $input['province_id']??null;
            $data->city_id = $input['city_id']??null;
            $data->area = $input['area']??null;
            $data->status = 1;
            $data->user_type = 5;
            $data->role = 'Host';
            $data->email_verified_at = date('Y-m-d h:i:s');
            $data->information_correct_or_not = $input['information_correct_or_not'];
            $data->save();

            if(isset($input['method_of_payment']) && !empty($input['method_of_payment'])){
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
                $bank_data->invoicing_type = $input['invoicing_type']??null;
                $bank_data->retention = $input['retention']??null;
                $bank_data->ledger_account = $input['ledger_account']??null;
                $bank_data->bic_swift = $input['bic_swift']??null;
                $bank_data->tax = $input['tax']??null;
                $bank_data->bank_name = $input['bank_name']??null;
                $bank_data->cnae_code = $input['cnae_code']??null;
                $bank_data->save();
            }

            $owner_permissions = ['41','42','43','44','49','50','51','52','53','54','55','56','57','58','59','60','69','70','71','72','81','82','83','84','86','87','88'];
            foreach ($owner_permissions as $key => $value) {
                $user = User::findOrFail($data->id);
                $user->givePermissionTo($value);
            }
            // dd('inn');
            // send email start
            $email = EmailTemplateLang::where('email_id', 14)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
            $subject = $email->subject;
            // $subject = str_replace("[NAME]", $input['name'], $email->subject);
            // $messsage = $message;
            $url = '<a href="'.url('/admin/login').'" target="_blank">Click Here</a>';
            $description = $email->description;
            $description = str_replace("[NAME]", $input['name'], $description);
            $description = str_replace("[URL]", $url, $description);
            $description = str_replace("[USERNAME]", $request->email, $description);
            $description = str_replace("[PASSWORD]", $request->password, $description);

            $register_detail=(object)[];
            // $register_detail->name = str_replace("[NAME]", $input['name'], $email->name);
            $register_detail->name = $input['name'];
            $register_detail->subject = $subject;
            $register_detail->description = $description;
            $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
            $user = User::where('id',$data->id)->first();

            Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                $message->to($user->email, config('app.name'))->subject($subject);
                $message->from('customersupport@shortletrenrals.com',config('app.name'));
            });
            // dd($this->page);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function importData(Request $request) 
    {
        $input = $request->all();
        // dd($input);
        // $langData = Language::pluck('lang')->toArray();
        $login_user_data = auth()->user();
        $added_by = $login_user_data->id;
        // $imageZipName = [];
        // $imageUploadedNames = [];
        // $taxQARData = mainKPApis('tax-detail',['currency_code'=>'QAR'], 'Register');
        // $qarToDollarDifference = 1;

        // if (isset($taxQARData) && $taxQARData->status == 1) {
        //     $qarToDollarDifference = $taxQARData->data->difference_amount;
        // }

        if (!empty($input['file']) ) {
            $imgRslt = file_upload($request->file('file'), 'user');
            // dd($imgRslt);
            if ($imgRslt[0] == true) {
                $excelData = (new BulkImport)->toArray(public_path($imgRslt[1]))[0];

                if (!empty($excelData)) {
                    $n = 8;
                    // $brand_id = $input['brand_id'];
                    // $restaurant_id = ($input['main_category_id'] == '5') ? '1' : '2';

                    foreach ($excelData as $key => $product) {
                        
                        // dd($country_code, $mobile, $product['e_mail']);
                        $user = User::where(['unique_id'=>$product['owner_id'] ])->first();
                        // $user = User::where(['country_code'=>$country_code,'mobile'=>$mobile,'email'=>$product['e_mail'] ])->first();
                        // echo '<pre>'; print_r($user);
                        if(empty($user)){
                            // dd('iff');

                            $mobile_telephone = $product['mobile_telephone'];
                            $mobile_telephone = preg_replace("/[^0-9]/", "", $mobile_telephone );
                            if(isset($mobile_telephone) && !empty($mobile_telephone)){

                                if (str_contains($mobile_telephone, '(')) { 
                                    $mobile_telephone = $this->removeSpecialCharacters($mobile_telephone);
                                    if (str_contains($mobile_telephone, '+')) { 
        
                                        $mobile_telephone1 = preg_split('/\s+/', $mobile_telephone);
                                        // dd($mobile_telephone1, 'iff');
                                        $country_code = $this->removePlus($mobile_telephone1[0]);
                                        if(isset($mobile_telephone1[3]) && $mobile_telephone1[3] ){
                                            $mobile = $mobile_telephone1[1] . $mobile_telephone1[2] . $mobile_telephone1[3];
                                        }else{
                                            if(isset($mobile_telephone1[2]) && $mobile_telephone1[2] ){
                                                $mobile = $mobile_telephone1[1] . $mobile_telephone1[2];
                                            }else{
                                                $mobile = $mobile_telephone1[1];
                                            }
                                        }
                                    }else{
                                        $country_code = Null;
                                        $mobile = $mobile_telephone;
                                    }
                                }else{
                                    if (str_contains($mobile_telephone, '+')) { 
        
                                        $mobile_telephone1 = preg_split('/\s+/', $mobile_telephone);
                                        // dd($mobile_telephone1, 'else');
                                        if(isset($mobile_telephone1[1]) && $mobile_telephone1[1]) {
                                            $country_code = $this->removePlus($mobile_telephone1[0]);
                                            if(isset($mobile_telephone1[3]) && $mobile_telephone1[3] ){
                                                $mobile = $mobile_telephone1[1] . $mobile_telephone1[2] . $mobile_telephone1[3];
                                            }else{
                                                if(isset($mobile_telephone1[2]) && $mobile_telephone1[2] ){
                                                    $mobile = $mobile_telephone1[1] . $mobile_telephone1[2];
                                                }else{
                                                    $mobile = $mobile_telephone1[1];
                                                }
                                            }
                                        } else {
                                            $mobile_telephone2 = $this->removePlus($mobile_telephone1[0]);
                                            // dd($mobile_telephone2);
                                            $mobile = substr( $mobile_telephone2, -10 );
                                            $country_code = str_replace($mobile, '', $mobile_telephone2);
                                            // $country_code = str_replace($mobile_telephone2,$mobile);
                                            // dd($country_code);
                                        }
                                        
                                    }else{
                                        $country_code = Null;
                                        $mobile = $mobile_telephone;
                                    }
                                }
                            }
                            $second_mobile_telephone = $product['contact_telephone'];
                            $second_mobile_telephone = preg_replace("/[^0-9]/", "", $second_mobile_telephone );
                            if(isset($second_mobile_telephone) && !empty($second_mobile_telephone)){

                                if (str_contains($second_mobile_telephone, '(')) { 
                                    $second_mobile_telephone = $this->removeSpecialCharacters($second_mobile_telephone);
                                    if (str_contains($second_mobile_telephone, '+')) { 
        
                                        $second_mobile_telephone1 = preg_split('/\s+/', $second_mobile_telephone);
                                        // dd($second_mobile_telephone1, 'iff');
                                        $second_country_code = $this->removePlus($second_mobile_telephone1[0]);
                                        if(isset($second_mobile_telephone1[3]) && $second_mobile_telephone1[3] ){
                                            $second_mobile = $second_mobile_telephone1[1] . $second_mobile_telephone1[2] . $second_mobile_telephone1[3];
                                        }else{
                                            if(isset($second_mobile_telephone1[2]) && $second_mobile_telephone1[2] ){
                                                $second_mobile = $second_mobile_telephone1[1] . $second_mobile_telephone1[2];
                                            }else{
                                                $second_mobile = $second_mobile_telephone1[1];
                                            }
                                        }
                                    }else{
                                        $second_country_code = Null;
                                        $second_mobile = $second_mobile_telephone;
                                    }
                                }else{
                                    if (str_contains($second_mobile_telephone, '+')) { 
        
                                        $second_mobile_telephone1 = preg_split('/\s+/', $second_mobile_telephone);
                                        // dd($second_mobile_telephone1, 'else');
                                        if(isset($second_mobile_telephone1[1]) && $second_mobile_telephone1[1]) {
                                            $second_country_code = $this->removePlus($second_mobile_telephone1[0]);
                                            if(isset($second_mobile_telephone1[3]) && $second_mobile_telephone1[3]){
                                                $second_mobile = $second_mobile_telephone1[1] . $second_mobile_telephone1[2] . $second_mobile_telephone1[3];
                                            }else{
                                                if(isset($second_mobile_telephone1[2]) && $second_mobile_telephone1[2]){
                                                    $second_mobile = $second_mobile_telephone1[1] . $second_mobile_telephone1[2];
                                                }else{
                                                    $second_mobile = $second_mobile_telephone1[1];
                                                }
                                            }
                                        } else {
                                            $second_mobile_telephone2 = $this->removePlus($second_mobile_telephone1[0]);
                                            // dd($second_mobile_telephone2);
                                            $second_mobile = substr( $second_mobile_telephone2, -10 );
                                            $second_country_code = str_replace($second_mobile, '', $second_mobile_telephone2);
                                            // $second_country_code = str_replace($second_mobile_telephone2,$second_mobile);
                                            // dd($second_country_code);
                                        }
                                        
                                    }else{
                                        $second_country_code = Null;
                                        $second_mobile = $second_mobile_telephone;
                                    }
                                }
                            }
                            $country = $product['country'];
                            if(isset($country) && !empty($country)){
                                $check_country_id = Country::where('name','LIKE','%'.$country.'%')->pluck('id')->first();
                                if(isset($check_country_id) && !empty($check_country_id)){
                                    $country_id = $check_country_id;
                                    // $data->country_id = $check_country_id;
                                }else{
                                    $add_country = new Country;
                                    $add_country->name = $country;
                                    if($add_country->save()){
                                        $country_id = $add_country->id;
                                        // $data->country_id = $add_country->id;
                                    }
                                }
                            }
                            $province = $product['province'];
                            if(isset($province) && !empty($province)){
                                $check_province_id = Province::where('name','LIKE','%'.$province.'%')->where('country_id',$country_id)->pluck('id')->first();
                                if(isset($check_province_id) && !empty($check_province_id)){
                                    $province_id = $check_province_id;
                                    // $data->province_id = $check_province_id;
                                }else{
                                    $add_province = new Province;
                                    $add_province->country_id = $country_id;
                                    $add_province->is_website_show = 1;
                                    $add_province->name = $province;
                                    if($add_province->save()){
                                        $province_id = $add_province->id;
                                        // $data->province_id = $add_province->id;
                                    }
                                }
                            }else{
                                $check_province_id = Province::where('name','LIKE','%None%')->where('country_id',$country_id)->pluck('id')->first();
                                if(isset($check_province_id) && !empty($check_province_id)){
                                    $province_id = $check_province_id;
                                    // $data->province_id = $check_province_id;
                                }else{
                                    $add_province = new Province;
                                    $add_province->country_id = $country_id;
                                    $add_province->is_website_show = 1;
                                    $add_province->name = 'None';
                                    if($add_province->save()){
                                        $province_id = $add_province->id;
                                        // $data->province_id = $add_province->id;
                                    }
                                }
                            }
                            $city = $product['city'];
                            if(isset($city) && !empty($city)){
                                $check_city_id = City::where('name','LIKE','%'.$city.'%')->where(['country_id'=>$country_id, 'province_id'=>$province_id])->pluck('id')->first();
                                if(isset($check_city_id) && !empty($check_city_id)){
                                    $city_id = $check_city_id;
                                    // $data->city_id = $check_city_id;
                                }else{
                                    $add_city = new City;
                                    $add_city->country_id = $country_id;
                                    $add_city->province_id = $province_id;
                                    $add_city->name = $city;
                                    if($add_city->save()){
                                        $city_id = $add_city->id;
                                        // $data->city_id = $add_city->id;
                                    }
                                }
                            }
                            $area = $product['area'];
                            if(isset($area) && !empty($area)){
                                $check_area_id = Area::where('name','LIKE','%'.$area.'%')->where(['country_id'=>$country_id, 'province_id'=>$province_id, 'city_id'=>$city_id])->pluck('id')->first();
                                if(isset($check_area_id) && !empty($check_area_id)){
                                    $area_id = $check_area_id;
                                }else{
                                    $add_area = new Area;
                                    $add_area->country_id = $country_id;
                                    $add_area->province_id = $province_id;
                                    $add_area->city_id = $city_id;
                                    $add_area->name = $area;
                                    if($add_area->save()){
                                        $area_id = $add_area->id;
                                    }
                                }
                            }

                            $data = new User;
                            // $unique_id = random_int(10000000, 99999999);
                            // $data->unique_id = $unique_id;
                            $data->unique_id = $product['owner_id'];
                            // $data->title = $product['title'];
                            $data->name = $product['name'];
                            if(isset($product['second_last_name']) && !empty($product['second_last_name'])){
                                $data->surname = $product['second_last_name'] .' '. $product['surname'];
                            }else{
                                $data->surname = $product['surname'];
                            }
                            $data->remarks = $product['comments']??null;
                            $data->email = $product['e_mail'];
                            if(isset($mobile) && !empty($mobile)){
                                $data->mobile = $mobile;
                                $data->country_code = $country_code;
                            }else{
                                if(isset($second_mobile) && !empty($second_mobile)){
                                    $data->mobile = $second_mobile;
                                    $data->country_code = $second_country_code;
                                }
                            }
                            // $data->is_super_host = $product['is_super_host'] ?? null;
                            // $data->is_chat_disabled_for_host = $product['is_chat_disabled_for_host'] ?? null;
                            $data->secondary_email = $product['alternative_email']??null;
                            $data->second_country_code = $second_country_code??null;
                            $data->second_mobile = $second_mobile??null;
                            $data->type_of_documentation = $product['type_of_documentation']??null;
                            $data->document_number = $product['document_number']??null;
                            $data->password = bcrypt($product['owner_id']);
                            // $data->dob = $dob;
                            // $data->gender = $product['gender'];
                
                            $data->street = $product['street']??null;
                            // $data->street_number = $product['street_number']??null;
                            // $data->number = $product['number']??null;
                            $data->postal_code = $product['postal_code']??null;
                            $data->country_id = $country_id??null;
                            $data->province_id = $province_id??null;
                            $data->city_id = $city_id??null;
                            $data->area_id = $area_id??null;
                            $data->status = 1;
                            $data->user_type = 5;
                            $data->role = 'Host';
                            $data->email_verified_at = date('Y-m-d h:i:s');
                            if($data->save()){
                                if(isset($product['account_nunber']) && !empty($product['account_nunber'])){

                                    $bank_data = new BankData;
                                    $bank_data->user_id = $data->id;
                                    $bank_data->method_of_payment = $product['method_of_payment']??'Bank_transfer';
                                    $bank_data->account_holder = 1;
                                    $bank_data->account_holder_name = $product['account_holder']??null;
                                    $bank_data->account_number = $product['account_nunber']??null;
                                    $bank_data->iban = $product['iban']??null;
                                    // $bank_data->vat_number = $product['vat_number']??null;
                                    $bank_data->fiscal_code = $product['fiscal_code']??null;
                                    // $bank_data->route = $product['route']??null;
                                    // $bank_data->retention = $product['retention']??null;
                                    $bank_data->ledger_account = $product['ledger_account']??null;
                                    $bank_data->bic_swift = $product['bic_swift']??null;
                                    // $bank_data->tax = $product['tax']??null;
                                    $bank_data->bank_name = $product['bank_name']??null;
                                    // $bank_data->cnae_code = $product['cnae_code']??null;
                                    $bank_data->save();
                                }
                            }
                        }/*else{
                            // dd('else');
                            $result['message'] = 'User already created.';
                            $result['status'] = 0;
                            return response()->json($result);
                        }*/
                    }
                    // dd('out');
                    $result['message'] = 'Owners list imported successfully';
                    $result['status'] = 1;
                    return response()->json($result);
                }
            }
        } else {
            $result['message'] = 'Please fill all mandatory field.';
            $result['status'] = 0;
            return response()->json($result);
            // return back();
        }
        /*Excel::import(new BulkImport,request()->file('file'));
        return back();*/
    }

    function removeSpecialCharacters($string) {
        $string = str_replace('(', ' ', $string); // Replaces all spaces with hyphens. 
        $string = str_replace(')','-', '', $string); // Replaces all spaces with hyphens. 
        return $string; // Removes special chars.
    }

    function removePlus($str){
        $res = str_ireplace( array( '+'), '', $str);
        return $res;
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
        $data = $this->Models->where('users.id',$id)->first();
        // $bookings = Booking::select('bookings.*','properties.title')->where(['bookings.host_id'=>$id, 'bookings.status'=>1])->leftjoin('properties','properties.id','=','bookings.property_id')->get();
        $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->where(['properties.host'=>$id])->get();
        // dd($accommodations);
        $bank_data = BankData::where('user_id',$id)->first();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'accommodations'=>$accommodations, 'bank_data'=>$bank_data];
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
        $bank_data_exist = BankData::where('user_id',$id)->first();
        if(isset($bank_data_exist) && !empty($bank_data_exist)){
            $bank_data = $bank_data_exist;
        }else{
            $bank_data = '';
        }
        // $country = $this->Models_sev->orderBy('phonecode','asc')->get();
        // $country_all = Country::select('*')->orderBy('id','asc')->get();
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country_all = Country::select('*')->orderBy('position','desc')->get();
        
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'country_phone'=>$country_phone,'country'=>$country,'country_all'=>$country_all, 'bank_data'=>$bank_data];
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
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'] ]);
                        }
                    }
                } else {
                    if(isset($document_file)){
                      
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);

                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'], 'document_image' => $document_result['file'] ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'] ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'area' => $input['area'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'information_correct_or_not' => $input['information_correct_or_not'],'document_number' => $input['document_number'] ]);
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
                    $bank_data->invoicing_type = $input['invoicing_type']??null;
                    $bank_data->ledger_account = $input['ledger_account']??null;
                    $bank_data->bic_swift = $input['bic_swift']??null;
                    $bank_data->tax = $input['tax']??null;
                    $bank_data->bank_name = $input['bank_name']??null;
                    $bank_data->cnae_code = $input['cnae_code']??null;
                    $bank_data->save();
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
        if(User::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Owner delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Owner does not delete. ';
        }
        return $response;
        // return Courts::findOrFail($id)->delete();
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

    public function change_superhost(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $status = $input['value'];

        $details = User::find($id);

        if (!empty($details)) {
            $inp = ['is_super_host' => $status];
            $User = User::findOrFail($id);

            if ($User->update($inp)) {
                if ($status == 'Yes') {
                    $result['message'] = 'Super host added successfully.';
                    $result['status'] = 1;
                } else {
                    $result['message'] = 'Super host removed successfully.';
                    $result['status'] = 0;
                }
            } else {
                $result['message'] = 'Something went wrong.';
                $result['status'] = 0;
            }
        } else {
            $result['message'] = __("backend.Invaild_user");
            $result['status'] = 0;
        }
        return response()->json($result);
    }
}
