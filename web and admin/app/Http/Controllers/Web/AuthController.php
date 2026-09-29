<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use App\Models\CourtBooking;
use App\Models\CronJob;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

		// changeRestaurantStatus();
	}
    //google callback
    public function redirectToGoogle()
    {
      return Socialite::driver('google')->redirect();
    }
	
    public function handleGoogleCallback(){
      try {
		$social_user = Socialite::driver('google')->user();
        // dd($social_user, 'google');
        // $finduser = Member::where('social_id', $user->id)->first();

        $user = User::where(['email' => $social_user->email ])
		->where(function ($query) {
			$query->where('user_type','=',4);
		})->first();
		// dd($user);
		if (isset($user)) {
			if ($user->status == 1 && empty($user->deleted_at)) {
				$credentials = ['email' => $social_user->email, 'password' => $social_user->id];
				if (Hash::check($social_user->id, $user->password)) {
					if (!$token = JWTAuth::attempt($credentials)) {
						$response['status'] = false;
						$response['message'] = __("api.invalid_user_login");
						return response()->json($response, 200);
					}
					if (isset($input['device_token'])) {
						$data = UserDevice::where(['user_id' => $user->id])->first();

						if (!isset($data)) {
							$data = new UserDevice;
							$data->user_id = $user->id;
							$data->device_type = $input['device_type'];
							$data->device_token = $input['device_token'];
							$data->save();
						}
						$data->device_type = $input['device_type'];
						$data->device_token = $input['device_token'];
						$data->save();
					}
					//Notification send
					// send_notification(1, $user->id, 'Welcome Back', array('title'=>'Welcome Back','message'=>'You are successfully login.','type'=>'Login','key'=>'Login'));
					//End Notification send
					$response['status'] = true;
					$response['token'] = $token;
					$response['data'] = new UserResource($user);
					$response['message'] = __("api.login_successfully");
					$data =  json_encode($response);
					// redirect(route('web.home'));
					Session::forget('is_guest');
					Session::put('AuthUserData', (object)json_decode($data));
					return redirect()->route('web.home');
				} else {
					$response['status'] = false;
					$response['message'] = __("api.incorrect_password_message");
					return response()->json($response, 200);
				}
			} else {
				$response['status'] = false;
				$response['message'] = __("api.user_deactive_or_deleted_message");
				return response()->json($response, 200);
			}
		} else {
			// dd($social_user, $social_user->email);
			$unique_id = random_int(10000000, 99999999);
			$user = New User;
			$user->unique_id = $unique_id;
			$user->social_type = 'google';
			$user->social_id = $social_user->id;
			$user->name = $social_user->name;
			$user->email = $social_user->email;
			$user->image = $social_user->avatar;
			$user->password = Hash::make($social_user->id);
			$user->image_type = 'url';
			$user->user_type = 4;
			$user->social_json = json_encode($social_user);
			$user->status = 1;
			if($user->save()){
				$credentials = ['email' => $social_user->email, 'password' => $social_user->id];
				if (Hash::check($social_user->id, $user->password)) {
					if (!$token = JWTAuth::attempt($credentials)) {
						$response['status'] = false;
						$response['message'] = __("api.invalid_user_login");
						return response()->json($response, 200);
					}
					if (isset($input['device_token'])) {
						$data = UserDevice::where(['user_id' => $user->id])->first();

						if (!isset($data)) {
							$data = new UserDevice;
							$data->user_id = $user->id;
							$data->device_type = $input['device_type'];
							$data->device_token = $input['device_token'];
							$data->save();
						}
						$data->device_type = $input['device_type'];
						$data->device_token = $input['device_token'];
						$data->save();
					}
					//Notification send
					// send_notification(1, $user->id, 'Welcome Back', array('title'=>'Welcome Back','message'=>'You are successfully login.','type'=>'Login','key'=>'Login'));
					//End Notification send
					$response['status'] = true;
					$response['token'] = $token;
					$response['data'] = new UserResource($user);
					$response['message'] = __("api.login_successfully");
					$data =  json_encode($response);
					// return response()->json($response, 200);
					// redirect(route('web.home'));
					Session::forget('is_guest');
					Session::put('AuthUserData', (object)json_decode($data));
					return redirect()->route('web.home');
				} else {
					$response['status'] = false;
					$response['message'] = __("api.incorrect_password_message");
					return response()->json($response, 200);
				}
			}

			$response['status'] = false;
			$response['message'] = __("api.invalid_user_login");
			return response()->json($response, 200);
		}
        
      } catch (Exception $e) {
        dd($e->getMessage());
      }
      //return redirect()->intended(route('home'));
    }

    //google callback
    public function redirectToFacebook()
    {
		return Socialite::driver('facebook')->redirect();
    }
	
    public function handleFacebookCallback(){
		try {
		  $social_user = Socialite::driver('facebook')->user();
		//   dd($social_user);
		  // $finduser = Member::where('social_id', $user->id)->first();
  
		  $user = User::where(['email' => $social_user->email ])
		  ->where(function ($query) {
			  $query->where('user_type','=',4);
		  })->first();
		  // dd($user);
		  if (isset($user)) {
			  if ($user->status == 1 && empty($user->deleted_at)) {
				  $credentials = ['email' => $social_user->email, 'password' => $social_user->id];
				  if (Hash::check($social_user->id, $user->password)) {
					  if (!$token = JWTAuth::attempt($credentials)) {
						  $response['status'] = false;
						  $response['message'] = __("api.invalid_user_login");
						  return response()->json($response, 200);
					  }
					  if (isset($input['device_token'])) {
						  $data = UserDevice::where(['user_id' => $user->id])->first();
  
						  if (!isset($data)) {
							  $data = new UserDevice;
							  $data->user_id = $user->id;
							  $data->device_type = $input['device_type'];
							  $data->device_token = $input['device_token'];
							  $data->save();
						  }
						  $data->device_type = $input['device_type'];
						  $data->device_token = $input['device_token'];
						  $data->save();
					  }
					  //Notification send
					  // send_notification(1, $user->id, 'Welcome Back', array('title'=>'Welcome Back','message'=>'You are successfully login.','type'=>'Login','key'=>'Login'));
					  //End Notification send
					  $response['status'] = true;
					  $response['token'] = $token;
					  $response['data'] = new UserResource($user);
					  $response['message'] = __("api.login_successfully");
					  $data =  json_encode($response);
					  // redirect(route('web.home'));
					  Session::forget('is_guest');
					  Session::put('AuthUserData', (object)json_decode($data));
					  return redirect()->route('web.home');
				  } else {
					  $response['status'] = false;
					  $response['message'] = __("api.incorrect_password_message");
					  return response()->json($response, 200);
				  }
			  } else {
				  $response['status'] = false;
				  $response['message'] = __("api.user_deactive_or_deleted_message");
				  return response()->json($response, 200);
			  }
		  } else {
			  // dd($social_user, $social_user->email);
			  $unique_id = random_int(10000000, 99999999);
			  $user = New User;
			  $user->unique_id = $unique_id;
			  $user->social_type = 'facebook';
			  $user->social_id = $social_user->id;
			  $user->name = $social_user->name;
			  $user->email = $social_user->email;
			  $user->image = $social_user->avatar;
			  $user->password = Hash::make($social_user->id);
			  $user->image_type = 'url';
			  $user->user_type = 4;
			  $user->social_json = json_encode($social_user);
			  $user->status = 1;
			  if($user->save()){
				  $credentials = ['email' => $social_user->email, 'password' => $social_user->id];
				  if (Hash::check($social_user->id, $user->password)) {
					  if (!$token = JWTAuth::attempt($credentials)) {
						  $response['status'] = false;
						  $response['message'] = __("api.invalid_user_login");
						  return response()->json($response, 200);
					  }
					  if (isset($input['device_token'])) {
						  $data = UserDevice::where(['user_id' => $user->id])->first();
  
						  if (!isset($data)) {
							  $data = new UserDevice;
							  $data->user_id = $user->id;
							  $data->device_type = $input['device_type'];
							  $data->device_token = $input['device_token'];
							  $data->save();
						  }
						  $data->device_type = $input['device_type'];
						  $data->device_token = $input['device_token'];
						  $data->save();
					  }
					  //Notification send
					  // send_notification(1, $user->id, 'Welcome Back', array('title'=>'Welcome Back','message'=>'You are successfully login.','type'=>'Login','key'=>'Login'));
					  //End Notification send
					  $response['status'] = true;
					  $response['token'] = $token;
					  $response['data'] = new UserResource($user);
					  $response['message'] = __("api.login_successfully");
					  $data =  json_encode($response);
					  // return response()->json($response, 200);
					  // redirect(route('web.home'));
					  Session::forget('is_guest');
					  Session::put('AuthUserData', (object)json_decode($data));
					  return redirect()->route('web.home');
				  } else {
					  $response['status'] = false;
					  $response['message'] = __("api.incorrect_password_message");
					  return response()->json($response, 200);
				  }
			  }
  
			  $response['status'] = false;
			  $response['message'] = __("api.invalid_user_login");
			  return response()->json($response, 200);
		  }
		  
		} catch (Exception $e) {
		  dd($e->getMessage());
		}
      //return redirect()->intended(route('home'));
    }

    public function handleGoogleCallback_old(){
      try {
        $user = Socialite::driver('google')->user();
        dd($user);
        $finduser = Member::where('social_id', $user->id)->first();

        $is_user_active = true;
        $resend_email_flag = false;
        $message = '';

        if ($finduser) {
            switch ($finduser->status) {
                case 0:
                    $message = 'Your account has been disabled. Please contact Admin in case of any concerns.';
                    $is_user_active = false;
                    break;
                case 5: //account deleted..
                    $finduser->update([
                      'status' => 1
                    ]);

                    $is_user_active = true;
                    // ************************* //
                    // Send Thankyou email to User
                    // ************************* //
                    $name = $finduser->first_name.' '.$finduser->last_name;

                    $email = $finduser->email;
                    
                    $email_template = EmailTemplate::where('type','user_welcome')->first();
                    $subject = $email_template['subject'];
                    $content = $email_template['content'];
                    $search = array("{{name}}","{{app_name}}");
                    $replace = array(ucwords($name),env('APP_NAME'));
                    $content  = str_replace($search,$replace,$content);
                    $social_links = GeneralSetting::find(1);
                    
                    sendEmail($email, $subject, $content, '', '', 'en','',$social_links);
                    
                    break;
            }
            if($is_user_active == false)
            {
              Auth::guard('user')->logout();
              return redirect()->back()->withErrors(['error' => $message]);
            }
            $finduser->update([
                'last_login' => Carbon::now('UTC')->timestamp,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
            ]);
        }
        else{

            if ( $user->email ) {
              $finduser = Member::where( 'email', $user->email )->first();
              if (!$finduser) {

                  $plan = Plan::where(['id' => 1, 'status' => 1])->first(); // Trial Plan
                  $finduser = Member::create([
                      'first_name' => $user->user['given_name'],
                      'last_name' => $user->user['family_name'],
                      'email' => $user->email,
                      'social_id' => $user->id,
                      'social_type' => 'google',
                      'firebase_token' => NULL,
                      'device_id' => NULL,
                      'email_verified_at' => now(),
                      'status' => 3,
                      'last_login' => Carbon::now('UTC')->timestamp,
                      'ip_address' => $_SERVER['REMOTE_ADDR'],
                  ]);
                  // ************************* //
                  // Send Thankyou email to User
                  // ************************* //
                  $name = $finduser->first_name.' '.$finduser->last_name;

                  $email = $finduser->email;
                  
                  $email_template = EmailTemplate::where('type','user_welcome')->first();
                  //$email_template = transformEmailTemplateModel($email_template,$lang);
                  $subject = $email_template['subject'];
                  $content = $email_template['content'];
                  $search = array("{{name}}","{{app_name}}");
                  $replace = array(ucwords($name),env('APP_NAME'));
                  $content  = str_replace($search,$replace,$content);
                  $social_links = GeneralSetting::find(1);
                  
                  sendEmail($email, $subject, $content, '', '', 'en','',$social_links);
                  // ************************* //
                  //    Send email to admin
                  // ************************* //
                  $email_template = EmailTemplate::where('type','new_user_registered')->first();
                  $admin_email = $social_links['email'];
                  $subject = $email_template['subject'];
                  $content = $email_template['content'];
                  $search = array("{{name}}","{{app_name}}");
                  $replace = array(ucwords($name),env('APP_NAME'));
                  $content  = str_replace($search,$replace,$content);
                  
                  sendEmail($admin_email, $subject, $content, '', '', 'en','',$social_links);

              }else{
                  $finduser->update([
                      'last_login' => Carbon::now('UTC')->timestamp,
                      'ip_address' => $_SERVER['REMOTE_ADDR'],
                      'firebase_token' => NULL,
                      'device_id' => NULL,
                      'social_id' => $user->id,
                      'email_verified_at' => now(),
                      'social_type' => 'google'
                  ]);
              }
            }else{
              return response()->json(['status' => 0, 'message' => array('message' => ['Sorry for inconvenient, Something went wrong !!! ']) , 'success_data' => NULL ], 200, ['Content-Type' => 'application/json']);
            }
        }
        session(['user' => $finduser]);
        //$token =  Auth::login($finduser);
        $token =  Auth::guard('user')->login($finduser);
        
        if ( !$token )
        {
          \Illuminate\Support\Facades\Session::flash('flash_danger', 'Something went wrong try again please !!');
          return redirect('login');
        }

        //$finduser = Member::where(['email' => $user->email ])->first();
        if (auth()->user()->is_approved != 1 && auth()->user()->status == 4) {
          die('okaa');
          return redirect()->intended(route('user.members'));  
        }elseif(auth()->user()->is_approved != 1 && auth()->user()->status != 4){
          return redirect()->intended(route('user.edit-profile'));
        }
        
      } catch (Exception $e) {
        dd($e->getMessage());
      }
      //return redirect()->intended(route('home'));
    }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function webLoginSocial(Request $request)
	{
		// dd('inn');
		$input =  $request->all();
		$validator = Validator::make($input, [
			'mobile' => 'required',
			'password' => 'required|string|min:6',
		]);
	
		// dd($_COOKIE);
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			// dd($input['country_code']);
			$input['country_code'] = isset($input['country_code']) && !empty($input['country_code']) ? $input['country_code'] : '91';
			$data = ApiCurlMethod('login', $input, 'Normal');

			if ($data->status == true) {
				Session::forget('is_guest');
				Session::put('AuthUserData', $data);

				if (isset($input["remember_me"])) {
					$hour = time() + 3600 * 24 * 30;
					setcookie('web_mobile', $input['mobile'], $hour);
					setcookie('web_password', $input['password'], $hour);
					setcookie('web_country_code', $input['country_code'], $hour);
					setcookie('web_remember_me', $input['remember_me'], $hour);

				} else {
					setcookie("web_mobile","");
					setcookie("web_password","");
					setcookie("web_country_code","");
					setcookie("web_remember_me","");
				}
				return response()->json($data, 200);

			} else {
				return response()->json($data, 200);
			}
		}
	}

	public function webLogin(Request $request)
	{
		Session::forget('AuthUserData');
		$input =  $request->all();
		$validator = Validator::make($input, [
			'mobile' => 'required',
			'password' => 'required|string|min:6',
		]);
	
		// dd($_COOKIE);
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			// dd($input['country_code']);
			$input['country_code'] = isset($input['country_code']) && !empty($input['country_code']) ? $input['country_code'] : '91';
			$data = ApiCurlMethod('login', $input, 'Normal');
			if ($data->status == true) {
				Session::forget('is_guest');
				Session::put('AuthUserData', $data);

				if (isset($input["remember_me"])) {
					$hour = time() + 3600 * 24 * 30;
					setcookie('web_mobile', $input['mobile'], $hour);
					setcookie('web_password', $input['password'], $hour);
					setcookie('web_country_code', $input['country_code'], $hour);
					setcookie('web_remember_me', $input['remember_me'], $hour);

				} else {
					setcookie("web_mobile","");
					setcookie("web_password","");
					setcookie("web_country_code","");
					setcookie("web_remember_me","");
				}
				return response()->json($data, 200);

			} else {
				return response()->json($data, 200);
			}
		}
	}

	public function HostWebLogin(Request $request)
	{
		$input =  $request->all();
		$validator = Validator::make($input, [
			'mobile' => 'required',
			'password' => 'required|string|min:6',
		]);
	
		// dd($_COOKIE);
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			// dd($input['user_type'], 'input');
			$data = ApiCurlMethod('HostLogin', $input, 'Normal');

			if ($data->status == true) {
				Session::put('AuthUserData', $data);

				if (isset($input["remember_me"])) {
					$hour = time() + 3600 * 24 * 30;
					setcookie('web_mobile', $input['mobile'], $hour);
					setcookie('web_password', $input['password'], $hour);
					setcookie('web_country_code', $input['country_code'], $hour);
					setcookie('web_remember_me', $input['remember_me'], $hour);
				} else {
					setcookie("web_mobile","");
					setcookie("web_password","");
					setcookie("web_country_code","");
					setcookie("web_remember_me","");
				}
				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
		}
	}

	public function webSignup(Request $request)
	{
		$input =  $request->all();
		$validator = Validator::make($input, [
			'title' => 'required',
			'full_name' => 'required',
			'email' => 'required|unique:users,email',
			'country_code' => 'required',
			'mobile' => 'required',
			'password' => 'required|string|min:6',
		]);
	
		// dd($_COOKIE);
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			// dd(Session::get('AuthUserData'));
			$input['type'] = 'register';
			$data = ApiCurlMethod('send-otp', $input, 'Normal');
		
			// $data = ApiCurlMethod('check_becomeahost_signUp', $input, 'Normal');
			// dd('inn');
			// dd($data, '--signup');
			$user_mobile = User::where(['country_code'=>$input['country_code'],'mobile'=>$input['mobile']])->first();
			// dd($user_mobile);
			if(isset($user_mobile) && !empty($user_mobile)){
				$data->status = false;
				$data->message = 'The mobile has already been taken. Please use another number.';
			}else{
				//dd('test');
				if ($data->status == true) {
					// dd($input['business_registration_image']);
					$file = $request->file('business_registration_image');
					if (isset($file)) {
						
						$newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);

						Session::put('dummy_user', ['title'=> $input['title'], 'name'=>$input['full_name'],'surname'=>$input['surname']??null,'gender'=>$input['gender']??null,'email'=>$input['email'],'country_code'=>$input['country_code'],'mobile'=>$input['mobile'],'password'=>$input['password'],'hear_about_us'=>$input['hear_about_us']??null,'country_id'=>$input['country_id']??null,'province_id'=>$input['province_id']??null,'city_id'=>$input['city_id']??null,'area'=>$input['area']??null,'landmark'=>$input['landmark']??null,'address'=>$input['address']??null,'hosting_type'=>$input['hosting_type']??null,'business_name'=>$input['business_name']??null,'business_registration_image'=>$result['file'] ] );
					}else{
						Session::put('dummy_user', ['title'=> $input['title'], 'name'=>$input['full_name'],'surname'=>$input['surname']??null,'gender'=>$input['gender']??null,'email'=>$input['email'],'country_code'=>$input['country_code'],'mobile'=>$input['mobile'],'password'=>$input['password'],'hear_about_us'=>$input['hear_about_us']??null,'country_id'=>$input['country_id']??null,'province_id'=>$input['province_id']??null,'city_id'=>$input['city_id']??null,'area'=>$input['area']??null,'landmark'=>$input['landmark']??null,'address'=>$input['address']??null,'hosting_type'=>$input['hosting_type']??null,'business_name'=>$input['business_name']??null ] );
					}
					// dd(Session::get('dummy_user'));
					$data->country_code = $input['country_code'];
					$data->mobile = $input['mobile'];
				} 
			}
			return response()->json($data);
		}
	}

	public function varifyOtp(Request $request)
	{
		$input =  $request->all();
		// dd($input);
        $message = [
            'otp_one.required' => "otp is required",
            'otp_two.required' => "otp is required",
            'otp_three.required' => "otp is required",
            'otp_four.required' => "otp is required",
        ];
		$validator = Validator::make($input, [
			'otp_one' => 'required',
			'otp_two' => 'required',
			'otp_three' => 'required',
			'otp_four' => 'required',
		]);
	
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			$otp_one = $input['otp_one'];
			$otp_two = $input['otp_two'];
			$otp_three = $input['otp_three'];
			$otp_four = $input['otp_four'];
			// dd(Session::get('dummy_user'));
			if(Session::get('dummy_user')){
				// dd(session('dummy_user'));
				$country_code = session('dummy_user')['country_code'];
				$gender = session('dummy_user')['gender'];
				$mobile = session('dummy_user')['mobile'];
				$title = session('dummy_user')['title'];
				$name = session('dummy_user')['name'];
				$surname = session('dummy_user')['surname'];
				$email = session('dummy_user')['email'];
				$password = session('dummy_user')['password'];

				$country_id = session('dummy_user')['country_id']??null;
				$province_id = session('dummy_user')['province_id']??null;
				$city_id = session('dummy_user')['city_id']??null;
				$area = session('dummy_user')['area']??null;
				$landmark = session('dummy_user')['landmark']??null;
				$address = session('dummy_user')['address']??null;
				$hosting_type = session('dummy_user')['hosting_type']??null;
				$business_name = session('dummy_user')['business_name']??null;
				$business_registration_image = session('dummy_user')['business_registration_image']??null;
				$hear_about_us = session('dummy_user')['hear_about_us']??null;

			
			
				if(isset($otp_one) && isset($otp_two) && isset($otp_three) && isset($otp_four) ){
					
					// $otp1 = implode('',$otp);
					$otp1 = $otp_one.$otp_two.$otp_three.$otp_four;
					$otp2 = ['otp'=>$otp1,'country_code'=>$country_code,'mobile'=>$mobile];
					
					$otp_data = ApiCurlMethod('verify-otp', $otp2, 'Normal');
					// dd($otp_data);
					if($otp_data->status == true){
						// dd(session('dummy_user'));
						$register_data = ['title'=>$title, 'fullname'=>$name, 'surname'=>$surname, 'gender'=>$gender, 'email'=>$email, 'country_code'=>$country_code, 'mobile'=>$mobile, 'password'=>$password, 'country_id'=>$country_id, 'province_id'=>$province_id, 'city_id'=>$city_id, 'area'=>$area, 'landmark'=>$landmark, 'address'=>$address, 'hosting_type'=>$hosting_type, 'business_name'=>$business_name, 'business_registration_image'=>$business_registration_image, 'hear_about_us'=>$hear_about_us];
						$data1 = ApiCurlMethod('becomeahost_signUp', $register_data, 'Normal');
						// dd($data1);
						if ($data1->status == true) {
							$register_data1 = $data1;
							// $register_data1 = ['data'=>['title'=>$title, 'fullname'=>$name, 'surname'=>$surname, 'email'=>$email, 'country_code'=>$country_code, 'mobile'=>$mobile, 'password'=>$password]];
							// dd($register_data1);
							Session::put('AuthUserData', $register_data1);
							session()->forget('dummy_user');

							return response()->json($data1, 200);
						} else {
							// dd('else');
							return response()->json($data1);
						}
						return response()->json($data1);
					}else{
						return response()->json($otp_data);
					}
					// return response()->json($data);
				}
			} else {
				return response()->json($data);
			}
		}
	}

	public function webPartnerSignup(Request $request)
	{
		$input =  $request->all();
		$validator = Validator::make($input, [
			'title' => 'required',
			'fullname' => 'required',
			'email' => 'required|unique:users,email',
			'country_code' => 'required',
			'mobile' => 'required|unique:users,mobile',
			'password' => 'required|string|min:6',
		]);
	
		// dd($_COOKIE);
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			// dd(Session::get('AuthUserData'));
			$input['type'] = 'register';
			$data = ApiCurlMethod('partner-signup', $input, 'Normal');
			
			return response()->json($data);
		}
	}

	public function webCustomerSignup(Request $request)
	{
		$input =  $request->all();
		$validator = Validator::make($input, [
			'fullname' => 'required',
			'email' => 'required',
			'country_code' => 'required',
			'mobile' => 'required',
			'password' => 'required|string|min:6',
		]);
	
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors->toArray();
			// dd($response);
			return response()->json($response, 200);
		} else {
			$data1 = ApiCurlMethod('customer_signUp', $input, 'Normal');
			
			if ($data1->status == true) {
				$register_data1 = $data1;
				Session::put('AuthUserData', $register_data1);
				session()->forget('dummy_user');
				session()->forget('is_guest');
				return response()->json($data1, 200);
			} else {
				return response()->json($data1);
			}
		}
	}

	public function webForgetPassword(Request $request)
	{
		$input =  $request->all();
		$validator = Validator::make($input, [
			// 'fullname' => 'required',
			// 'email' => 'required|unique:users,email',
			'country_code' => 'required',
			'mobile' => 'required',
			// 'password' => 'required|string|min:6',
		]);
	
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors->toArray();
			// dd($response);
			return response()->json($response, 200);

		} else {
			$input['type'] = 'forgot';
			// dd('resend_otp', $input, $userData);

			$data = ApiCurlMethod('send-otp', $input, 'Normal');
			// dd($data);
			if ($data->status == true) {
				// $register_data = $data;
				// Session::put('AuthUserData', $register_data);
				// session()->forget('dummy_user');
				return response()->json($data, 200);

			} else {
				// dd('else');
				return response()->json($data);
			}
		}
	}

	public function resend_otp_form()
	{
		if(Session::get('dummy_user')){
		
			$country_code = session('dummy_user')['country_code'];
			$mobile = session('dummy_user')['mobile'];
			$email = session('dummy_user')['email'];
			$name = session('dummy_user')['name'];
			

			if(isset($country_code) && !empty($mobile)){
				// $otp1 = implode('',$otp);
				$otp2 = ['type'=>'resend','country_code'=>$country_code,'mobile'=>$mobile,'email'=>$email,'full_name'=>$name];

				$data = ApiCurlMethod('send-otp', $otp2, 'Normal');
				return response()->json($data, 200);
			}
		} else {
			return response()->json($data, 200);
		}
	}

	public function forgetVarifyOtp(Request $request)
	{
		$input =  $request->all();
		// dd($input);
		$message = [
			'otp_one.required' => "otp is required",
			'otp_two.required' => "otp is required",
			'otp_three.required' => "otp is required",
			'otp_four.required' => "otp is required",
		];
		$validator = Validator::make($input, [
			'otp_one' => 'required',
			'otp_two' => 'required',
			'otp_three' => 'required',
			'otp_four' => 'required',
		]);
	
		if ($validator->fails()) {
			$errors     =   $validator->errors();
			$response['status'] = false;
			$response['error'] = $errors;
			return response()->json($response, 200);
		} else {
			$otp_one = $input['otp_one'];
			$otp_two = $input['otp_two'];
			$otp_three = $input['otp_three'];
			$otp_four = $input['otp_four'];
			// dd(Session::get('dummy_user'));
			if(isset($otp_one) && !empty($otp_two) && !empty($otp_three) && !empty($otp_four) ){
				// dd($input);
				// $otp1 = implode('',$otp);
				$otp1 = $otp_one.$otp_two.$otp_three.$otp_four;
				$otp2 = ['otp'=>$otp1,'country_code'=>$input['country_code'],'mobile'=>$input['mobile']];

				$otp_data = ApiCurlMethod('verify-otp', $otp2, 'Normal');
				// dd($otp_data);
				// if($otp_data->status == true){
				// 	// dd(session('dummy_user'));
				// 	$register_data = ['title'=>$title, 'fullname'=>$name, 'surname'=>$surname, 'email'=>$email, 'country_code'=>$country_code, 'mobile'=>$mobile, 'password'=>$password, 'country_id'=>$country_id, 'province_id'=>$province_id, 'city_id'=>$city_id, 'area'=>$area, 'landmark'=>$landmark, 'address'=>$address, 'hosting_type'=>$hosting_type, 'business_name'=>$business_name, 'business_registration_image'=>$business_registration_image, 'hear_about_us'=>$hear_about_us];
				// 	$data1 = ApiCurlMethod('becomeahost_signUp', $register_data, 'Normal');
				// 	// dd($data1);
				// 	if ($data1->status == true) {
				// 		$register_data1 = $data1;
				// 		// $register_data1 = ['data'=>['title'=>$title, 'fullname'=>$name, 'surname'=>$surname, 'email'=>$email, 'country_code'=>$country_code, 'mobile'=>$mobile, 'password'=>$password]];
				// 		// dd($register_data1);
				// 		Session::put('AuthUserData', $register_data1);
				// 		session()->forget('dummy_user');

				// 		return response()->json($data1, 200);
				// 	} else {
				// 		// dd('else');
				// 		return response()->json($data1);
				// 	}
				// 	return response()->json($data1);
				// }else{
				// 	return response()->json($data);
				// }
				return response()->json($otp_data);
			}else{
				$data['status'] = false;
				$data['message'] = 'Please endter otp.';

				return response()->json($data);
			}
		}
	}
	
	public function send_otp(Request $request)
	{
		// dd('dddddd',$request->all());
		session()->forget('userData');
		$input =  $request->all();
		$data = ApiCurlMethod('send-otp', $input, 'Normal');
		if ($data->status == true) {
			Session::put('userData', $input);
			return response()->json($data, 200);
		} else {
			return response()->json($data, 200);
		}
	}
	public function resend_otp(Request $request)
	{
		$userData = Session::get('userData') ?? null;

		$input =  $request->all();
		$userData['type'] = 'resend';
		// dd('resend_otp', $input, $userData);

		$data = ApiCurlMethod('send-otp', $userData, 'Normal');
		if ($data->status == true) {
			Session::put('userData', $userData);
			$data->status = 1;
			return response()->json($data, 200);
		} else {
			return response()->json($data, 200);
		}
	}
	public function verify_otp(Request $request)
	{
		$userData = Session::get('userData') ?? null;
		if ($userData != null) {
			$input =  $request->all();
			$input['country_code'] = $userData['country_code'];
			$input['mobile'] = $userData['mobile'];
			// dd($input);
			$data = ApiCurlMethod('verify-otp', $input, 'Normal');
			if ($data->status == true) {
				Session::put('userDataWithOtp', $input);
				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
		} else {
			$data['message'] = __('api.invalid_user');
			return response()->json($data, 200);
		}
	}

	public function set_password(Request $request)
	{
		$userData = Session::get('userDataWithOtp') ?? null;
		// dd('set_password', $userData, $request->all());
		if ($userData != null) {
			$input =  $request->all();
			$input['country_code'] = $userData['country_code'];
			$input['mobile'] = $userData['mobile'];
			$input['otp'] = $userData['otp'];
			// dd($input);
			$data = ApiCurlMethod('set-password', $input, 'Normal');
			if ($data->status == true) {
				Session::put('AuthUserData', $data);
				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
		} else {
			$data['message'] = __('api.invalid_user');
			return response()->json($data, 200);
		}
	}

	public function reset_password(Request $request)
	{
		$userData = Session::get('userDataWithOtp') ?? null;
		// dd('set_password', $userData, $request->all());
		if ($userData != null) {
			$input =  $request->all();
			$input['country_code'] = $userData['country_code'];
			$input['mobile'] = $userData['mobile'];
			$input['otp'] = $userData['otp'];
			// dd($input);
			$data = ApiCurlMethod('reset-password', $input, 'Normal');
			if ($data->status == true) {
				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
		} else {
			$data['message'] = __('api.invalid_user');
			return response()->json($data, 200);
		}
	}

	public function web_logout()
	{
		// dd('ddd');
		session()->forget('AuthUserData');
		$AuthUserData = Session::get('AuthUserData') ?? null;
		if ($AuthUserData == null) {
			return redirect()->route('web.home')->with('success', 'User logout successfully');
		} else {
			return redirect()->Back()->with('success', 'Something went wrong');
		}
	}

	public function changeOrderAuto()
	{
		$today_date = date('Y-m-d');
		$booking = CourtBooking::where('order_status', '!=', 'Completed')->where('order_status', '!=', 'Cancelled')->whereDate('booking_date', '<', $today_date)->get();

		foreach ($booking as $key => $value) {
			if($value->booking_type == 'normal') {

				if ($value->payment_type == 'cash' && $value->payment_received_status == 'Received') {
					$value->update(['order_status' => 'Completed']);

				} else {

					if ($value->payment_type == 'online') {
						$value->update(['order_status' => 'Completed']);

					} else {
						$value->update(['order_status' => 'Cancelled']);
					}
				}
			}else{
				if ($value->payment_type == 'cash' && $value->payment_received_status == 'Received' && $value->joiner_payment_status == 'Received') {
					$value->update(['order_status' => 'Completed']);
				}else{
					if ($value->payment_type == 'online' && $value->joiner_payment_status == 'Received') {
						$value->update(['order_status' => 'Completed']);

					} else {
						$value->update(['order_status' => 'Cancelled']);
					}
				}
			}
		}
		$data['value'] = json_encode($booking);
		// dd($data, $booking);
		$cron_job = CronJob::create($data);
		return 'change status';
	}

    public function schedule_appointment(Request $request)
    {
        $input =  $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'full_name' => 'required',
            'email' => 'required',
            'country_code' => 'required',
            'mobile' => 'required',
            'appointment_date' => 'required',
            'schedule_time' => 'required',
            'province_id' => 'required',
            'address' => 'required',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['error'] = $errors;
            return response()->json($response, 200);

        } else {
			$data = ApiCurlMethod('schedule_appointment', $input, 'Normal');
			dd($data);
			if ($data->status == true) {
				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
            return response()->json($response, 200);
        }
    }
}
