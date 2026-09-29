<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Null_;
use App\Models\Country;
use App\Models\BankData;
use DB,Redirect;
use File;

class SettingController extends Controller
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function frontend()
    {
        $userData = User::where('id',auth()->id() )->first();
        // dd($userData->user_type);
        $login_user_data = auth()->user();
        $data = array();
        // $data['country']=Country::select('phonecode','name','id')->get();
        if($userData->user_type == 5 || $userData->user_type == 3){
            $id = auth()->id();
            $data = USER::find($id);
            $bank_data_exist = BankData::where('user_id',$id)->first();
            if(isset($bank_data_exist) && !empty($bank_data_exist)){
                $bank_data = $bank_data_exist;
            }else{
                $bank_data = '';
            }
            $country_phone = Country::orderBy('phonecode','asc')->get();
            $country_all = Country::select('*')->orderBy('position','desc')->get();
            
            $country = Country::orderBy('position','desc')->get();
            $data= ['data'=>$data, 'country_phone'=>$country_phone,'country'=>$country,'country_all'=>$country_all, 'bank_data'=>$bank_data];

            return view('admin.host_settings', $data);
        }/*else if($userData->user_type == 3){
            $id = auth()->id();
            $data = USER::find($id);
            $bank_data_exist = BankData::where('user_id',$id)->first();
            if(isset($bank_data_exist) && !empty($bank_data_exist)){
                $bank_data = $bank_data_exist;
            }else{
                $bank_data = '';
            }
            $country_phone = Country::orderBy('phonecode','asc')->get();
            $country_all = Country::select('*')->orderBy('position','desc')->get();
            
            $country = Country::orderBy('position','desc')->get();
            $data= ['data'=>$data, 'country_phone'=>$country_phone,'country'=>$country,'country_all'=>$country_all, 'bank_data'=>$bank_data];

            return view('admin.host_settings', $data);
        }*/else{
            return view('admin.settings', $data);
        }
    }

    public function host_profile_update(Request $request)
    {
        // validate
        $id = $request->id;
        $mesasge = [
            'name.required' => __("backend.name_required"),
            'email.required' => __("backend.email_required"),
            'email.email' => __("backend.email_email"),
            'email.unique' => __("backend.email_unique"),
            'country_code.required' => __("backend.country_code_required"),
            'mobile.required' => __("backend.mobile_required"),
            'mobile.digits_between' => __("backend.mobile_digits_between"),
            'image.size'  =>  __("backend.image_size"),

        ];
        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:190',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], $mesasge);

        $input = $request->all();
        // dd($input);
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            $document_file = $request->file('document_image');
            $id_card_file = $request->file('id_card_image');

            $user_old = DB::table('users')->where('id', $id)->first();

            if ($id_card_file) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $id_card_result =  fileUploads('s3',$id_card_file,$folderPath,false);
                $id_card_image = $id_card_result['file'];
            } else {
                $id_card_image = $user_old->id_card_image;
            }
            try {
                if(isset($input['dob']) && $input['dob'] != null){
                    $dob = date('Y-m-d', strtotime($input['dob']));
                }else{
                    $dob = null;
                }
                // dd($dob);
                if (isset($file)) {
                
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    if(isset($document_file)){
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'document_image' => $document_result['file'], 'id_card_image' => $id_card_image ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'document_image' => $document_result['file'], 'id_card_image' => $id_card_image ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'id_card_image' => $id_card_image ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result['file'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'id_card_image' => $id_card_image ]);
                        }
                    }
                } else {
                    if(isset($document_file)){
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);

                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'document_image' => $document_result['file'], 'id_card_image' => $id_card_image ]);
                        }else{
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'document_image' => $document_result['file'], 'id_card_image' => $id_card_image ]);
                        }
                    }else{
                        if(isset($input['password']) && !empty($input['password'])){
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'],'password'=>bcrypt($input['password']), 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'id_card_image' => $id_card_image ]);
                        }else{
                            // dd($input, 'inn');
                            $data = User::where('id', $id)->update(['title' => $input['title'], 'name' => $input['name'], 'surname' => $input['surname'], 'remarks' => $input['remarks'], 'is_super_host' => $input['is_super_host'], 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host'], 'street' => $input['street'], 'street_number' => $input['street_number'], 'number' => $input['number'], 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'secondary_email' => $input['secondary_email'], 'second_country_code' => $input['second_country_code'], 'second_mobile' => $input['second_mobile'], 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status']??null,'document_number' => $input['document_number'], 'id_card_image' => $id_card_image ]);
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
                return Redirect::route('admin.profile')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }

    public function settings()
    {
        $login_user_data = auth()->user();
        $settingData = AdminSettings::all();
        // dd($settingData);
        $data['settingData'] = $settingData;
        $country = Country::select('*')->orderBy('phonecode','asc')->get();
        $country_all = Country::select('*')->orderBy('id','asc')->get();
        $data['country'] = $country;
        $data['country_all'] = $country_all;
        return view('admin.admin_setting', $data);
    }

    public function update_settings(Request $request, $id) {
        Gate::authorize('Setting-section');
        $mesasge = [
            // 'new_password.regex' => 'Password should be valid.',
        ];
        $this->validate($request, [   
            'commission' => 'required',
            // 'loyalty_point' => 'required',
            'title' => 'required',
            'email' => 'required',
            'country_code' => 'required',
            'mobile' => 'required',
            'country' => 'required',
        ],$mesasge);

        try{
            // dd($request->all());
            // dd($request->country_code);
            $inp=[
                'commission'=>$request->commission,
                // 'loyalty_point'=>$request->loyalty_point,
                'loyalty_point_amount'=>$request->loyalty_point_amount??null,
                'loyalty_percentage'=>$request->loyalty_percentage??null,
                'royalty_point_equal_to'=>$request->royalty_point_equal_to??null,
                'second_royalty_amount'=>$request->second_royalty_amount??null,
                'title'=>$request->title,
                'email'=>$request->email,
                'country_code'=>$request->country_code,
                'mobile'=>$request->mobile,
                'facebook_url'=>$request->facebook_url??'',
                'twitter_url'=>$request->twitter_url??'',
                'instagram_url'=>$request->instagram_url??'',
                'whatsup_url'=>$request->whatsup_url??'',
                'country'=>$request->country,
            ];

            $record = AdminSettings::findOrFail($id);

            if ($record->update($inp)) {
                $message = 'Admin Settings has been updated successfully';
                return Redirect::route('admin.admin-settings.index')->with('success',$message);

            } else {
                $message = 'Admin Settings Can`t updated';
                return Redirect::back()->with('error',$message);
            }

        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.dashboard.index','','error',$e);                       
        }
    }

    public function sendVerificationLink(Request $request){
        $this->validate($request, [   
            'current_password' => 'required|min:6|max:20',        
            'email'=>'required|email|max:255|unique:users,email',    
        ]);
        if (!(Hash::check($request->current_password, Auth::user()->password))) {
            $result=array(
                'status'=>false,
                'message'=>'Your current password does not matches with the password you provided. Please try again.'
            );
        }
        else{
            $token=$this->createNewToken();
            $user = Auth::user();
            $user->new_email = $request->email; 
            $user->new_email_token = $token;
            if($user->save()){
                if($this->_sendEmail($request->email,$token)){
                    $result=array(
                        'status'=>true,
                        'message'=>'Email varification link has been successfully send.'
                    );
                  }
                  else{
                    $result=array(
                        'status'=>false,
                        'message'=>'Error to email send.'
                    );
                  }   
            }
            else{
                $result=array(
                    'status'=>false,
                    'message'=>'Error Occured.'
                );
            }
           }
        return response()->json($result);
    }


    public function saveProfile(Request $request) {
        $user = Auth::user();
        $this->validate($request, [   
            'name' => 'required',
            'email' => 'string|email|unique:users,email, '. $user->id .',id',    
            // 'last_name'=>'required',  
            // 'mobile'=>'required',  
            // 'country_code'=>'required',  
            'image' => 'image|mimes:jpeg,png,jpg,gif,svgmax:5120',
        ],[
			'image.size'  => 'the file size is less than 5MB',
        ]);

        
        $user->name = $request->name; 
        $user->email = $request->email; 
        // $user->last_name = $request->last_name;
        // $user->name = $request->first_name.' '.$request->last_name;
        
        // $user->country_code = $request->country_code;
        // $user->mobile = $request->mobile;
        if ($request->file('image')) {
            $file = $request->file('image');
            
            $newFolder  = strtoupper(date('M') . date('Y'));
            $folderPath	=	'user/'.$newFolder; 
            $result =  fileUploads('s3',$file,$folderPath,false);
            $user->image = $result['file'];
        }
        if($user->save()){
            $result=array(
                'status'=>true,
                'message'=>'Profile updated successfully!'
            );
        }
        else{
            $result=array(
                'status'=>false,
                'message'=>'Error Occured.'
            );
        }  
        return response()->json($result);
    }


    public function emailUpdate(Request $request,$userid,$token){
        $user = User::findOrFail($userid);
        if ($user->new_email_token==$token && $userid==Auth::user()->id) {
            $user->email = $user->new_email;
            $user->new_email = Null;
            $user->new_email_token = Null;
            if($user->save()){
                $result=array(
                    'status'=>true,
                    'message'=>'Email has been successfully updated.'
                );
                $request->session()->flash('status', $result['message']);
                return redirect('settings');
            }
        }
        else{
            return abort(403,'Invalid or expired Link');
        }

    }

    public function change_password(Request $request){      
        $data = User::find(Auth::User()->id);
        $data= ['title'=>'Change Password','data'=>$data];
        return view('admin.change_password',$data); 
    }
    
    public function changePassword(Request $request) {
        $mesasge = [
            // 'new_password.regex' => 'Password should be valid.',
        ];
        $this->validate($request, [   
            'old_password' => 'required|min:6|max:20',        
            // 'new_password' => 'required|min:6|max:20|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|same:password',
        ],$mesasge);

        try{

            if (!(Hash::check($request->old_password, Auth::user()->password))) {
                $message = 'Your current password does not matches with the password you provided. Please try again.';
                return Redirect::back()->with('error',$message);
                // return Redirect::route('admin.change_password')->with('error',$message);
            }
            else{
                $user = Auth::user();
                $user->password = bcrypt($request->password);

                if ($user->save()) {
                    $message = "Password changed successfully !";
                }
                return Redirect::route('admin.change_password')->with('success',$message);
            }
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.dashboard.index','','error',$e);                       
        }
    }

    public function _sendEmail($email,$token)
    {
        $details = [
            'new_email'=>$email,
            'token'=>$token,
            'user'=>Auth::user()
         ];
        //  dd($details);
        try {
            //  Mail::to($email)->send(new \App\Mail\ChangeEmailVarification($objEmail));
            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('emails.new_email_verify', $details, function($message) use ($details)
            {
                $message
                    ->to($details['new_email'])
                    ->subject('Verify your new email address');
            });
             return true; 
        } catch(\Exception $e){
            dd($e->getMessage());
        }
    }

     /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return hash_hmac('sha256',Str::random(40),Auth::user()->password);
    }

}

