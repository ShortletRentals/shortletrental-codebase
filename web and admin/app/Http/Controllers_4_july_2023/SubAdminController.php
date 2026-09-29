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
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class SubAdminController extends Controller
{
    protected  $page = 'subadmin';
    protected  $lang = 'Subadmin';
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
            // 3 => 'role',
            3 => 'status',
            4 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Subadmin-section');
        
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
            $user_type = 2; 
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
                $row['role'] = isset($value->role)? $value->role:'N/A';
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
                $permission = permissionAction('permissions.user_permissions',['id'=>$value->id]);

                if (!auth()->user()->can('Subadmin-edit')) {
                    $edit = '';
                    $permission = '';
                }
                if (!auth()->user()->can('Subadmin-delete')) {
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
        Gate::authorize('Subadmin-section');
        $user = User::where('user_type', '2')->get();
        $roles = Role::all();
        $data= ['title'=>$this->lang,'page'=>$this->page,'roles'=>$roles]; 
        return view('admin.subadmin.listing', $data);
    }

    public function edit_frontend($id)
    {
        Gate::authorize('Subadmin-edit');
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
        Gate::authorize('Subadmin-create');
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country = $this->Models_sev->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $country_all = Country::select('*')->orderBy('id','asc')->get();
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

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];

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
            $data->password = bcrypt($request->password);

            $data->street = $input['street']??null;
            $data->street_number = $input['street_number']??null;
            $data->number = $input['number']??null;
            $data->postal_code = $input['postal_code']??null;
            // $data->country = $input['country'];
            $data->country_id = $input['country_id']??null;
            $data->province_id = $input['province_id']??null;
            $data->city_id = $input['city_id']??null;
            $data->status = 1;
            $data->user_type = 2;
            $data->role = 'Subadmin';
            if($data->save()){
                $subadmin_permissions = ['5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','63','64','65','66','67','68','69','70','71','72','73','74','75','81','82','83','84','85','86','87','88','77'];
                foreach ($subadmin_permissions as $key => $value) {
                    $user = User::findOrFail($data->id);
                    $user->givePermissionTo($value);
                }
                // send email start
                $email = EmailTemplateLang::where('email_id', 16)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                $subject = $email->subject;
                // $subject = str_replace("[NAME]", $input['name'], $email->subject);
                // $messsage = $message;
                $user = User::where('id',$data->id)->first();
                $password = $request->password;
                $url = '<a href="'.url('/admin/login').'" target="_blank">Click Here</a>';
                $description = $email->description;
                $description = str_replace("[NAME]", $input['name'], $description);
                $description = str_replace("[URL]", $url, $description);
                $description = str_replace("[USERNAME]", $request->email, $description);
                $description = str_replace("[PASSWORD]", $password, $description);
    
                $register_detail=(object)[];
                // $register_detail->name = str_replace("[NAME]", $input['name'], $email->name);
                $register_detail->name = $input['name'];
                $register_detail->subject = $subject;
                $register_detail->description = $description;
                $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
    
                Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                    $message->to($user->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com',config('app.name'));
                }); 
                return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully.');
            }else{
                return Redirect::route('admin.'.$this->page.'.index')->with('error','Record not added.');
            }
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function store_old(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name'          => 'required|max:190',
            'password'          => 'required|min:6|max:190',
            'confirm_password'          => 'required|min:6|max:190',
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
            $data->password = Hash::make($input['password']);
            $data->status = 1;
            $data->user_type = 2;
            $data->role = 'Subadmin';
            $data->save();
            // dd($data);
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
        $data = $this->Models->where('users.id',$id)->first();
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
        Gate::authorize('Subadmin-edit');
        $id = $request->id;
        $data = $this->Models->find($id);       
        $country_phone = $this->Models_sev->orderBy('phonecode','asc')->get();
        $country_all = Country::select('*')->orderBy('id','asc')->get();
        
        $country = $this->Models_sev->orderBy('name','asc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'country_phone'=>$country_phone, 'country_all'=>$country_all,'country'=>$country,'province'=>$province];
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
        Gate::authorize('Influencer-edit');
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
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    
                    
                    if(isset($input['password']) && !empty($input['password'])){
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']) ]);
                    }else{
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'] ]);
                    }
                } else {
                    if(isset($input['password']) && !empty($input['password'])){
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']) ]);
                    }else{
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'] ]);
                    }
                }
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }
     
    public function update_old(Request $request)
    {
        Gate::authorize('Subadmin-edit');
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
            // 'password'          => 'required|min:6|max:190',
            // 'confirm_password'          => 'required|min:6|max:190',
        ];
        $this->validate($request, [
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'country_code' => 'required',
            // 'gender' => 'required',
            'mobile' => 'required|digits_between:7,15|unique:users,mobile,' . $id,
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    if(isset($input['password'])){
                        $data = User::where('id', $id)->update(['name' => $input['name'], 'email' => $input['email'], 'password' => Hash::make($input['password']), 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'image' => $result['file']]);
                    }else{
                        $data = User::where('id', $id)->update(['name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'image' => $result['file']]);
                    }
                } else {
                    if(isset($input['password'])){
                        $data = User::where('id', $id)->update(['name' => $input['name'], 'email' => $input['email'], 'password' => Hash::make($input['password']), 'mobile' => $input['mobile'], 'country_code' => $input['country_code']]);
                    }else{
                        $data = User::where('id', $id)->update(['name' => $input['name'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code']]);
                    }
                }
                return Redirect::route('admin.subadmin.index')->with('success',"Record updated successfully.");
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
        Gate::authorize('Subadmin-delete');
        if(User::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Sub-admin delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Sub-admin does not delete. ';
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
