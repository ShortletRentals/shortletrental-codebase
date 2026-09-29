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
use App\Models\Discount;
use App\Models\Country;
use App\Models\Province;
use App\Models\EmailTemplateLang;
use App\Models\BankData;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class InfluencerController extends Controller
{
    protected  $page = 'influencer';
    protected  $lang = 'Partner';
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
        Gate::authorize('Influencer-section');
        
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
            $user_type = 3; 
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
              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                $permission = permissionAction('permissions.user_permissions',['id'=>$value->id]);
              
                if (!auth()->user()->can('Influencer-edit')) {
                    $edit = '';
                    $permission = '';
                }
                if (!auth()->user()->can('Influencer-delete')) {
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
        Gate::authorize('Influencer-section');
        $user = User::where('user_type', '3')->get();
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
        Gate::authorize('Influencer-create');
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
            // dd($request->file('document_image'), $request->file('id_card_image'));
            if ($request->file('document_image')) {
                $document_file = $request->file('document_image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                $data->document_image = $document_result['file'];

            }

            if ($request->file('id_card_image')) {
                $id_card_file = $request->file('id_card_image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $id_card_result =  fileUploads('s3',$id_card_file,$folderPath,false);
                $data->id_card_image = $id_card_result['file'];

            }
            // dd($data->document_image, $data->id_card_image);
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
            $data->commission = $input['commission']??null;
            $data->document_number = $input['document_number']??null;
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
            $data->user_type = 3;
            $data->role = 'Influencer';
            $data->email_verified_at = date('Y-m-d h:i:s');
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
                $bank_data->retention = $input['retention']??null;
                $bank_data->ledger_account = $input['ledger_account']??null;
                $bank_data->bic_swift = $input['bic_swift']??null;
                $bank_data->tax = $input['tax']??null;
                $bank_data->bank_name = $input['bank_name']??null;
                $bank_data->cnae_code = $input['cnae_code']??null;
                $bank_data->save();
            }

            $permissions = ['45','46','47','48','49','50','51','52','53','54','55','56','86','87','88'];
            foreach ($permissions as $key => $value) {
                $user = User::findOrFail($data->id);
                $user->givePermissionTo($value);
            }
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
        // dd($id);
        $data = $this->Models->where('users.id',$id)->first();
        $bank_data_exist = BankData::where('user_id',$id)->first();
        $all_discounts = Discount::select('discounts.*','categories.name')->leftjoin('categories','categories.id','=','discounts.category_id')->where(['discounts.status'=>1, 'discounts.influencer'=>$id])->get();
        // dd($all_discounts);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'all_discounts'=>$all_discounts,'bank_data'=>$bank_data_exist];
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
        Gate::authorize('Influencer-edit');
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
            $document_file = $request->file('document_image');
            $id_card_file = $request->file('id_card_image');

            $user_old = DB::table('users')->where('id', $id)->first();

            if ($document_file) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                $document_image = $document_result['file'];
            } else {
                $document_image = $user_old->document_image;
            }

            if ($id_card_file) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $id_card_result =  fileUploads('s3',$id_card_file,$folderPath,false);
                $id_card_image = $id_card_result['file'];
            } else {
                $id_card_image = $user_old->id_card_image;
            }

            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    
                    if(isset($input['password']) && !empty($input['password'])){
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'commission' => $input['commission'],'document_number' => $input['document_number'], 'document_image' => $document_image, 'id_card_image' => $id_card_image ]);
                    }else{
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'commission' => $input['commission'],'document_number' => $input['document_number'], 'document_image' => $document_image, 'id_card_image' => $id_card_image ]);
                    }
                } else {
                    if(isset($input['password']) && !empty($input['password'])){
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'commission' => $input['commission'],'document_number' => $input['document_number'], 'document_image' => $document_image, 'id_card_image' => $id_card_image ]);
                    }else{
                        $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'email' => $input['email'], 'mobile' => $input['mobile'], 'country_code' => $input['country_code'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'commission' => $input['commission'],'document_number' => $input['document_number'], 'document_image' => $document_image, 'id_card_image' => $id_card_image ]);
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
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                dd($e);
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
            $response['message'] = 'Partner delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Partner does not delete. ';
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
