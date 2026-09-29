<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserDevice;
use App\Models\Category;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\PropertyCategory;
use App\Models\AdminSettings;
use App\Models\Property;
use App\Models\Offer;
use App\Models\BankData;
use App\Models\Booking;
use App\Models\PropertyBlockDate;
use App\Models\Country;
use App\Models\ExtraService;
use App\Models\Amenity;
use App\Models\UserLoyaltyPoint;
use App\Models\EmailTemplateLang;
use App\Models\Notification;
use App\Models\Appointment;
use App\Models\PropertyImage;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\PropertyAmenity;
use App\Models\PropertyExtraService;
use App\Models\AccommodationType;
use App\Models\PropertyHouserule;
use App\Models\ContactUs;
use App\Models\Blog;
use App\Models\Content;
use App\Models\BlogCategory;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session, Auth, DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getCityArea','otpVerify','getHomeData','getHomeData1', 'sendOtp', 'login', 'host_login','mobileSocialLogin','HostLogin', 'signUp','becomeahost_signUp','customer_signUp', 'forgot_password', 'resetPassword', 'setPassword', 'socialLogin','propertyList','propertyDetails','propertyDetailsBookingDates','getOffer','OfferDetail','update_profile_host','getAllCountry','getAllCategory','getAllProvince','getAllProvince1','getAllCity','getAllArea','getAllServices','getAllAmenities','getPropertyMinMaxPrice','partner_signup','schedule_appointment','getAllTypes','becomeahost_add_property','becomeahost_user_details','becomeahost_user_update','getContactDetails','updateContactDetails','getAllBlogs','getBlogDetails','updatePassword','getStaticData','addProvince','addCity','addArea']]);
    }

    public function notificationOnOff(Request $request){      
        $user = JWTAuth::user();  
        $user_id = $user->id;

        $input =  $request->all();
        $message = [
            // 'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'on_off'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            if(array_key_exists('on_off',$input) && !empty($input['on_off'])){
                if(User::where('id',$user_id)->update(['notification_on_off'=>$input['on_off']])){
                    $response['status'] = true;
                    $response['message'] = 'Notification updated successfully.';
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Notification not updated.';
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'User not found.';
            }
        }
        return response()->json($response, 200);
    }

    public function getContactDetails(){        
        $categoryData = AdminSettings::first();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getStaticData(Request $request){
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            // 'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'slug' => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            // dd($input['blog_id']);
            if(isset($input['slug']) && !empty($input['slug']) ){
                $contents = Content::select('contents.*')->where('contents.slug',$input['slug'])->first();
                // dd($allBlogs);
                $data= ['contents'=>$contents];
    
                if(isset($contents) && !empty($contents)){
                    $result['data'] = $data;
                    $result['status'] = true;
                }else{
                    $result['status'] = false;
                    $result['message'] = 'Blogs not found';
                }
            }else{
                $result['status'] = false;
                $result['message'] = 'Slug is required';
            }
            return response()->json($result);
        }
    }

    public function getAllBlogs(Request $request){
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            // 'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            // 'first_name' => 'required|max:255',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            $allBlogs1 = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where('blogs.status',1);
            if(array_key_exists('search',$input) && !empty($input['search'])){
                $allBlogs1->Where("blogs.title",'like','%'.$input['search'].'%');
            }
            if(array_key_exists('category',$input) && !empty($input['category'])){
                $allBlogs1->Where("blogs.blog_category",$input['category']);
            }
            $allBlogs = $allBlogs1->get();
            // dd($allBlogs);
            $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
            $category = BlogCategory::where('status',1)->get();
            $data= ['allBlogs'=>$allBlogs, 'recentBlogs'=>$recentBlogs, 'category'=>$category];

            if(isset($allBlogs) && count($allBlogs) > 0){
                $result['data'] = $data;
                $result['status'] = true;
            }else{
                $result['status'] = false;
                $result['message'] = 'Blogs not found';
            }
            return response()->json($result);
        }
    }

    public function getBlogDetails(Request $request){
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            // 'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'blog_id' => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            // dd($input['blog_id']);
            $allBlogs1 = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where('blogs.id',$input['blog_id'])->first();
            // dd($allBlogs);
            $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
            $category = BlogCategory::where('status',1)->get();
            $data= ['blogDetail'=>$allBlogs1, 'recentBlogs'=>$recentBlogs, 'category'=>$category];

            if(isset($allBlogs1) && !empty($allBlogs1)){
                $result['data'] = $data;
                $result['status'] = true;
            }else{
                $result['status'] = false;
                $result['message'] = 'Blogs not found';
            }
            return response()->json($result);
        }
    }

    public function updateContactDetails(Request $request){
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            // 'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'city' => 'required',
            'province' => 'required',
            // 'country_code' => 'required',
            'mobile_number' => 'required|digits_between:8,12',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            try {
                $data = new ContactUs();
                $data->name = $input['first_name'].' '.$input['last_name'];
                $data->first_name = $input['first_name'];
                $data->last_name = $input['last_name'];
                $data->email = $input['email'];
                $data->mobile = $input['mobile_number'];
                $data->city = $input['city'];
                $data->state = $input['province'];
                $data->country_code = $input['country_code'] ?? null;
                $data->message = $input['message'] ?? null;
                $data->status = 1;
                $data->save();

                $result['message'] = 'Your details received successfully. We will contact you soon.';
                $result['status'] = true;
                return response()->json($result);

            } catch (Exception $e) {
                $result['message'] = 'Something went wrong.';
                $result['status'] = false;
                return response()->json($result);
            }
        }
    }

    public function becomeahost_add_property(Request $request)
    {
        // $user = JWTAuth::user();
        // dd($request->all());
        // $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'user_id'   => 'required',
            'title'   => 'required',
            'price'   => 'required',
            'address'   => 'required',
            'latitude'   => 'required',
            'longitude'   => 'required',
            'country_id'   => 'required',
            'province_id'   => 'required',
            'city_id'   => 'required',
            // 'email'   => 'email|unique:users,email,' . $user->id,
            // 'dob'   => 'date_format:Y-m-d|before:today',
            // 'gender'   => 'required',
            // 'marital_status'   => 'required',
            // 'image'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            DB::table('json')->insert([
                'json' => json_encode($input),
            ]);
           // dd($request->file());

            $userData = User::where('id',$input['user_id'])->first();
            // dd('inn',$userData->id);
            if(isset($userData) && !empty($userData)){
                $check_property = Property::where(['host'=>$input['user_id'], 'title'=>$input['title']])->first();
                if(isset($check_property) && !empty($check_property)){
                    $response['status'] = false;
                    $response['message'] = 'Accommodation already added.';
                }else{
                    $data = new Property;
                    // dd($request->file("image"));
                    if($request->file('image')){
                        foreach ($request->file("image") as $key => $file) {
                            // dd($key);
                            if($key == 0){
                                $newFolder  = strtoupper(date('M') . date('Y'));
                                $folderPath	=	'property/'.$newFolder; 
                                $result =  fileUploads('s3',$file,$folderPath,false);
                                $data->image = $result['file'];
                            }else{
                                break;
                            }
                        }
                    }
                    // $code = $this->generateRandomString();
                    $code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
                    $data->code = $code;
                    // $data->reference = $input['reference']??'';
                    $data->title = $input['title'];
                    $data->type = $input['type'];
                    // $data->category = $input['category'];
                    $data->host = $userData->id;
                    $data->featured = $input['featured']??'Yes';
                    $data->free_cancellation = $input['free_cancellation']??'Yes';
                    $data->description = $input['description']??null;
                    // $data->max_guest = $input['max_guest']??null;
                    $data->price = $input['price'];
                    // $data->tax = $input['tax']??null;
                    // $data->building = $input['building']??null;
                    // $data->minimum_no_of_nights = $input['minimum_no_of_nights']??null;
                    // $data->security_deposit_amount = $input['security_deposit_amount']??null;
            
                    $data->address = $input['address'];
                    $data->latitude = $input['latitude'];
                    $data->longitude = $input['longitude'];
                    

                    $data->max_guest = $input['max_guest']??null;
                    $data->pets_allow = $input['pets_allow']??null;
                    $data->minimum_no_of_nights = $input['minimum_no_of_nights']??null;
                    $data->cctv = $input['cctv']??null;
                    $data->cctv_locations = $input['cctv_locations']??null;
                    $data->location_of_television = $input['location_of_television']??null;
                    $data->response_time = $input['response_time']??null;
                    $data->party_rate_commission = $input['party_rate_commission']??null;
                    $data->standout_amenities = $input['standout_amenities']??null;
                    $data->allow_a_day_booking = $input['allow_a_day_booking']??null;
                    
                    $data->status = 0;
                    $data->clean_status = 0;
                    $data->created_by = 'Host';
                    $data->host_property_status = 'None';

                    if($data->save()){
                        $property_id = $data->id;
                     
                        if($request->has("image")){
                        
                            foreach ($request->file("image") as $key1 => $file1) {
                             
                                // dd($key);
                                if($request->file('image')){
                                    if($key1 > 0){

                                        $newFolder  = strtoupper(date('M') . date('Y'));
                                        $folderPath	=	'property/'.$newFolder; 
                                        $result =  fileUploads('s3',$file,$folderPath,false);                                 

                                        $modelProductImages = new PropertyImage(); 
                                        $modelProductImages->property_id = $property_id;
                                        $modelProductImages->image = $result['file'];
                                        $modelProductImages->image_type = $result['type'];
                                        $modelProductImages->save();
                                    }
                                }
                            }
                        }
                        if(isset($input['category']) && !empty($input['category'])){
                            // dd($input['category']);
                            $category = $input['category'];
                            $category1 = explode (",", $category);
                            // dd($category);
                            foreach($category1 as $cat){
                                $data_category = new PropertyCategory;
                                $data_category->property_id = $property_id;
                                $data_category->category_id = $cat;
                                $data_category->save();
                            }
                        }
                        if(isset($input['country_id']) && !empty($input['country_id'])){
                            $data_address = new PropertyAddress;
    
                            $data_address->property_id = $property_id;
                            $data_address->address = $input['address']??null;
                            $data_address->country_id = $input['country_id'];
                            $data_address->province_id = $input['province_id'];
                            $data_address->city_id = $input['city_id']??null;
                            $data_address->area = $input['area']??null;
                            $data_address->postal_code = $input['postal_code']??null;
    
                            $data_address->street_name = $input['street_name']??null;
                            $data_address->street_type = $input['street_type']??null;
                            $data_address->street_number = $input['street_number']??null;
                            $data_address->house_number = $input['house_number']??null;
                            $data_address->floor = $input['floor']??0;
                            $data_address->staircase = $input['staircase']??null;
                            $data_address->elevator = $input['elevator']??null;
                            $data_address->apartment_door_no = $input['apartment_door_no']??null;
                            $data_address->save();
                        }
                        if(isset($input['bedrooms'])){
                            $data_bedrooms = new PropertyBedroom;
                            $data_bedrooms->property_id = $property_id;
                            $data_bedrooms->no_of_bedrooms = $input['bedrooms'];
                            $data_bedrooms->save();
                        }
                        
                        $data_bathrooms = new PropertyBathroom;
                        $data_bathrooms->property_id = $property_id;
                        $data_bathrooms->bathroom_with_bathtub = $input['bathrooms'];
                        
                        $data_bathrooms->towel_change = $input['towel_change']??0;
                        $data_bathrooms->save();
    
                        $data_kitchen = new PropertyKitchen;
                        $data_kitchen->property_id = $property_id;
                        $data_kitchen->no_of_kitchens = $input['kitchens'];
                        $data_kitchen->save();
    
                        $data_bedding = new PropertyBedding;
                        $data_bedding->property_id = $property_id;
                        $data_bedding->bed_linen = $input['beds'];
                        $data_bedding->no_of_television = $input['no_of_television']??0;
                        $data_bedding->network_name = $input['wifi_username']??null;
                        $data_bedding->password = $input['wifi_password']??null;
                        $data_bedding->save();
    
                        if(isset($input['amenity']) && !empty($input['amenity'])){
                            $amenity1 = $input['amenity'];
                            $all_amenity = explode(',', $amenity1);
                            foreach($all_amenity as $amenity){
                                $data_emenity = new PropertyAmenity;
                                $data_emenity->property_id = $property_id;
                                $data_emenity->amenities_id = $amenity;
                                $data_emenity->save();
                            }
                        }
                        if(isset($input['extra_services']) && !empty($input['extra_services'])){
                            $extra_services1 = $input['extra_services'];
                            $extra_services = explode(',', $extra_services1);
                            foreach($extra_services as $service){
                                $data_service = new PropertyExtraService;
                                $data_service->property_id = $property_id;
                                $data_service->service_id = $service;
                                $data_service->save();
                            }
                            // dd($extra_services);
                        }
                        if(isset($input['extra_services']) && !empty($input['extra_services'])){
                            $data_service = new PropertyHouserule;
                            $data_service->property_id = $property_id;
                            $data_service->name = $input['house_rule'];
                            $data_service->save();
                        }
                        $result['status'] = true;
                        $result['message'] = 'Accommodation added successfully. Admin will approve shortly.';
                        return response()->json($result);
                    }
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'User not found.';
            }
            return response()->json($response, 200);
        }
        // return $this->jsonResponse();
        return response()->json($response, 200);
    }

    public function becomeahost_user_details(Request $request)
    {
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'user_id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'user_id'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            $userData = User::where(['id'=>$input['user_id'], 'user_type' => 5])->first();
            // dd('inn',$userData->id);
            if(isset($userData) && !empty($userData)){
                $response['status'] = true;
                $response['data'] = $userData;
            }else{
                $response['status'] = false;
                $response['message'] = 'User not found.';
            }
            return response()->json($response, 200);
        }
        // return $this->jsonResponse();
        return response()->json($response, 200);
    }

    public function becomeahost_signUp(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];

        $validator = Validator::make($input, [
            'fullname'   => 'required',
            'email'   => 'required|unique:users,email',
            'country_code'   => 'required',
            'mobile'   => 'required|min:7|max:15|unique:users,mobile',
            'password'   => 'required',
        ], $message);

        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
            $errors   = json_decode(json_encode($response));
             foreach($errors as $key => $err ){  
                $response1['status'] = false;
                $response1['message'] =$err;
                return response()->json($response1, 200); 
            }
        } else {
            // $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'user_type' => 5])
            ->where(function ($query) {
                $query->where('user_type','=',5);
            })->first();
            if (!$user) {
                $user = new User;

                // image upload
                if ($request->file('business_registration_image')) {
                    $file = $request->file('business_registration_image');

                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    $user->business_registration_image = $result['file'];
                    $user->image_type = 'local';
                }
                $hash = Hash::make($input['password']);
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->title = $input['title'];
                $user->name = $input['fullname'];
                $user->surname = $input['surname'];
                $user->country_code = $input['country_code'];
                $user->mobile = $input['mobile'];
                $user->password = $hash;
                $user->user_new_token = base64_encode($input['password']);
                $user->email = $input['email'];
                $user->gender = $input['gender'];

                $user->hear_about_us = $input['hear_about_us']??null;
                $user->country_id = $input['country_id']??null;
                $user->province_id = $input['province_id']??null;
                $user->city_id = $input['city_id']??null;
                $user->area = $input['area']??null;
                $user->landmark = $input['landmark']??null;
                $user->address = $input['address']??null;
                $user->hosting_type = $input['hosting_type']??null;
                $user->business_name = $input['business_name']??null;
                // $user->business_registration_image = $input['business_registration_image']??null;
                $user->status = 1;
                $user->user_type = 5;

                if ($user->save()) {

                    $notificationData = new Notification;
                    $notificationData->user_type = $user->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Host-Registration';
                    $notificationData->title = 'Host Registration';
                    $notificationData->message = ucwords($input['fullname'].' '.$input['surname']).' host account has been created.';
                    $notificationData->user_id = 1;
                    $notificationData->save();

                    $user1 = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
                    ->where(function ($query) {
                        $query->where('user_type','=',5);
                    })->first();
                    $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
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
                    $response['status'] = true;
                    $response['token'] = $token;
                    $response['data'] = new UserResource($user1);;
                    $response['message'] = 'User register successfully.';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Error Occured.';
                    return response()->json($response, 200);
                }
            } else {
                // $this->message  = __("api.mobile_number_already_exsits");
                // $this->status   = false;
                $response['status'] = false;
                $response['message'] = __("api.mobile_number_already_exsits");
                return response()->json($response, 200);
            }
        }
        // return $this->jsonResponse();
        return response()->json($response, 200);
    }

    public function becomeahost_user_update(Request $request)
    {
        // $this->code = 200;
        $input =  $request->all();
        // $this->requestdata = $input;
        if(array_key_exists('user_id',$input) && !empty($input['user_id'])){
            $user = User::where(['id' => $input['user_id'], 'user_type' => 5])->first();
        }else{
            $response['status'] = false;
            $response['message'] = 'User id is required';
            return response()->json($response, 200);
        }
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];
        $validator = Validator::make($input, [
            'user_id'   => 'required',
            // 'email'   => 'email|unique:users,email,' . $user->id,
            // 'mobile'   => 'min:7|max:15|unique:users,mobile,'.$user->id,
        ], $message);

        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
            // return $this->jsonResponse();
            return response()->json($response, 200);
        } else {
            // $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            $document_file = $request->file('document_image');
            $file = $request->file('image');
            if(isset($input['dob']) && $input['dob'] != null){
                $dob = date('y-m-d', strtotime($input['dob']));
            }else{
                $dob = null;
            }
            $user = User::where(['id' => $input['user_id'], 'user_type' => 5])->first();
            if (isset($user) && !empty($user)) {
                if(isset($document_file)){
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$document_file,$folderPath,false);

                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['document_image' => $result['file']]);
                }
                if(isset($file)){
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['image' => $result['file']]);
                }
                if(array_key_exists('title',$input) && !empty($input['title'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['title'=>$input['title']]);
                }
                if(array_key_exists('fullname',$input) && !empty($input['fullname'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['name'=>$input['fullname']]);
                }
                if(array_key_exists('surname',$input) && !empty($input['surname'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['surname'=>$input['surname']]);
                }
                if(array_key_exists('email',$input) && !empty($input['email'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['email'=>$input['email']]);
                }
                if(array_key_exists('mobile',$input) && !empty($input['mobile'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['country_code'=>$input['country_code']??'234', 'mobile'=>$input['mobile']]);
                }
                if(array_key_exists('dob',$input) && !empty($input['dob'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['dob'=>$dob]);
                }
                if(array_key_exists('gender',$input) && !empty($input['gender'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['gender'=>$input['gender']]);
                }
                if(array_key_exists('marital_status',$input) && !empty($input['marital_status'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['marital_status'=>$input['marital_status']]);
                }
                if(array_key_exists('remarks',$input) && !empty($input['remarks'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['remarks'=>$input['remarks']]);
                }
                if(array_key_exists('secondary_email',$input) && !empty($input['secondary_email'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['secondary_email'=>$input['secondary_email']]);
                }
                if(array_key_exists('second_mobile',$input) && !empty($input['second_mobile'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['second_country_code'=>$input['second_country_code']??'234', 'second_mobile'=>$input['second_mobile']]);
                }
                if(array_key_exists('country_id',$input) && !empty($input['country_id'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['country_id'=>$input['country_id'] ]);
                }
                if(array_key_exists('province_id',$input) && !empty($input['province_id'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['province_id'=>$input['province_id'] ]);
                }
                if(array_key_exists('city_id',$input) && !empty($input['city_id'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['city_id'=>$input['city_id'] ]);
                }
                if(array_key_exists('street',$input) && !empty($input['street'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['street'=>$input['street'] ]);
                }
                if(array_key_exists('street_number',$input) && !empty($input['street_number'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['street_number'=>$input['street_number'] ]);
                }
                if(array_key_exists('number',$input) && !empty($input['number'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['number'=>$input['number'] ]);
                }
                if(array_key_exists('postal_code',$input) && !empty($input['postal_code'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['postal_code'=>$input['postal_code'] ]);
                }
                if(array_key_exists('document_number',$input) && !empty($input['document_number'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['document_number'=>$input['document_number'] ]);
                }
                
                if(isset($input['method_of_payment']) && !empty($input['method_of_payment'])){
                    BankData::where('user_id',$input['user_id'])->delete();
                    $bank_data = new BankData;
                    $bank_data->user_id = $input['user_id'];
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
                if(array_key_exists('information_correct_or_not',$input) && !empty($input['information_correct_or_not'])){
                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['information_correct_or_not'=>$input['information_correct_or_not'] ]);
                }
                // if(array_key_exists('second_mobile',$input) && !empty($input['second_mobile'])){
                //     User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['secondary_email'=>$input['second_country_code']??'234', 'second_mobile'=>$input['second_mobile']]);
                // }
                $response['status'] = true;
                $response['message'] = 'User updated successfully.';
            } else {
                // $this->message  = __("api.mobile_number_already_exsits");
                // $this->status   = false;
                $response['status'] = false;
                $response['message'] = 'User not found.';
                return response()->json($response, 200);
            }
        }
        // return $this->jsonResponse();
        return response()->json($response, 200);
    }

    public function getAllTypes(){        
        $categoryData = AccommodationType::all();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getAllCountry(){        
        $categoryData = Country::all();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getAllServices(){        
        $categoryData = ExtraService::all();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getAllAmenities(){        
        $categoryData = Amenity::all();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getAllCategory(Request $request){
        $input = $request->all();
        // dd($input['type']);
        if(array_key_exists("type",$input) && !empty($input['type'])){
            $typename1 = AccommodationType::where('id',$input['type'])->pluck('name')->first();
            $typename = str_replace(' ','_',$typename1);
            $categoryData = Category::where('type',$typename)->get();
        }else{
            $categoryData = Category::all();
        }
        if (empty($categoryData)) {
            $response['status'] = false;
            $response['message'] = 'Category not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getPropertyMinMaxPrice(Request $request){
        $country = $request->country_id;
        $province = $request->province_id;
        $city = $request->city_id;
        $area = $request->area_id;
        // dd($country, $province, $city, $area);
        if(isset($city) && $city != null){
            if(isset($area) && $area != null){
                $price = Property::select(\DB::raw("MIN(price) AS min, MAX(price) AS max"))->leftjoin('property_address','property_address.property_id','=','properties.id')->where(['property_address.country_id'=>$country, 'property_address.province_id'=>$province, 'property_address.city_id'=>$city, 'property_address.area'=>$area])->first();
                if(isset($price) && !empty($price)){
                    $min_price = $price->min;
                    $max_price = $price->max;
                }else{
                    $min_price = '';
                    $max_price = '';
                }
            }elseif(isset($city) && $city != null){
                $price = Property::select(\DB::raw("MIN(price) AS min, MAX(price) AS max"))->leftjoin('property_address','property_address.property_id','=','properties.id')->where(['property_address.country_id'=>$country, 'property_address.province_id'=>$province, 'property_address.city_id'=>$city])->first();
                if(isset($price) && !empty($price)){
                    $min_price = $price->min;
                    $max_price = $price->max;
                }else{
                    $min_price = '';
                    $max_price = '';
                }
            }
        }else{
            $price = Property::select(\DB::raw("MIN(price) AS min, MAX(price) AS max"))->leftjoin('property_address','property_address.property_id','=','properties.id')->where(['property_address.country_id'=>$country, 'property_address.province_id'=>$province])->first();
            if(isset($price) && !empty($price)){
                $min_price = $price->min;
                $max_price = $price->max;
            }else{
                $min_price = '';
                $max_price = '';
            }
        }

        if (!$price) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['min_price'] = $min_price;
            $response['max_price'] = $max_price;
            return response()->json($response, 200);
        }
    }

    public function addProvince(Request $request)
    {
        $input = $request->all();

        $categoryData = Province::where('country_id',$input['country_id'])->where('name',$request->name)->first();
        if(!isset($categoryData))
        {
            $categoryData = new Province;
            $categoryData->country_id = $input['country_id'];
            $categoryData->name = $request->name;
            $categoryData->save();

            $response['status'] = true;
            $response['message'] = 'Province added successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
        else
        {
            $response['status'] = false;
            $response['message'] = 'Province already exist.';
            return response()->json($response, 200);
        }
    }

    public function addCity(Request $request)
    {
        $input = $request->all();

        $categoryData = City::where(['country_id'=>$input['country_id'], 'province_id'=>$input['province_id']])->where('name',$request->name)->first();
        if(!isset($categoryData))
            {
            $categoryData = new City;
            $categoryData->country_id = $input['country_id'];    
            $categoryData->province_id = $input['province_id'];
            $categoryData->name = $request->name;
            $categoryData->save();

            $response['status'] = true;
            $response['data'] = $categoryData;
            $response['message'] = 'City added successfully.';
            return response()->json($response, 200);
        }
        else
        {
            $response['status'] = false;
            $response['message'] = 'City already exist.';
            return response()->json($response, 200);
        }
    }

    public function addArea(Request $request)
    {
        $input = $request->all();

        $categoryData = Area::where(['country_id'=>$input['country_id'], 'province_id'=>$input['province_id'], 'city_id'=>$input['city_id'] ])->where('name',$request->name)->first();
        if(!isset($categoryData))       
        {
            $categoryData = new Area        ;
            $categoryData->country_id = $input['country_id'];    
            $categoryData->province_id = $input['province_id'];             
            $categoryData->city_id = $input['city_id'];
            $categoryData->name = $request->name;
            $categoryData->save();

            $response['status'] = true;
            $response['message'] = 'Area added successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
        else
        {
            $response['status'] = false;
            $response['message'] = 'Area already exist.';
            return response()->json($response, 200);
        }
    }


    public function getAllProvince1(Request $request){
        $input = $request->all();

        $propertyCountries = PropertyAddress::select('property_address.province_id', DB::raw('count(province_id) as total') )
            ->join('properties', 'properties.id', '=', 'property_address.property_id')->join('provinces', 'provinces.id', '=', 'property_address.province_id')
            ->whereNotNull('provinces.id')
            ->where('properties.status', 1)
            ->groupBy('province_id')->get();
            // dd($propertyCountries);
            if (count($propertyCountries)) {
                foreach ($propertyCountries as $key => $value) {
                    $provinceData = Province::where('id', $value->province_id)->first();
                    // if(isset($provinceData) && !empty($provinceData)){
                        $value->name = $provinceData->name ?? '';
                    // }
                }
            }

        // dd($input['type']);
        /*if(array_key_exists("country_id",$input) && !empty($input['country_id'])){
            
            
            $categoryData = Province::where('country_id',$input['country_id'])->get();
        }else{
            $categoryData = Province::all();
        }*/

        if (!$propertyCountries) {
            $response['status'] = $propertyCountries;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $propertyCountries  ;
            return response()->json($response, 200);
        }
    }


    public function getAllProvince(Request $request){
        $input = $request->all();

        if(array_key_exists("country_id",$input) && !empty($input['country_id'])){
            
            
            $categoryData = Province::where('country_id',$input['country_id'])->get();
        }else{
            $categoryData = Province::all();
        }

        if (!$categoryData) {
            $response['status'] = $categoryData;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData  ;
            return response()->json($response, 200);
        }
    }

    
    public function getAllCity(Request $request){
        $input = $request->all();
        // dd($input['type']);
        if(array_key_exists("country_id",$input) && !empty($input['province_id'])){
            $categoryData = City::where(['country_id'=>$input['country_id'], 'province_id'=>$input['province_id']])->get();
        }elseif(array_key_exists("province_id",$input) && !empty($input['province_id'])){
            $categoryData = City::where(['province_id'=>$input['province_id']])->get();
        }else{
            $categoryData = City::all();
        }
        // dd($categoryData);
        if (count($categoryData) ) {
            // dd($categoryData, 'iff');
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        } else {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        }
    }

    public function getCityArea(Request $request){

        
       
        if (isset($request->province_id)) {
            $province_id = $request->province_id;
            $cityRecord = Area::select('areas.*', 'cities.name as city_name')->join('cities', 'cities.id', '=', 'areas.city_id')->where(['areas.province_id'=>$province_id])->get();

        } else {
            $cityRecord = Area::select('areas.*', 'cities.name as city_name')->join('cities', 'cities.id', '=', 'areas.city_id')->get();
        }
        if (!$cityRecord) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $cityRecord;
            return response()->json($response, 200);
        }
    }

    public function getAllArea(Request $request){
        $input = $request->all();
        // dd($input['type']);
        if(array_key_exists("country_id",$input) && array_key_exists("province_id",$input) && array_key_exists("city_id",$input) ){
            $categoryData = Area::where(['country_id'=>$input['country_id'], 'province_id'=>$input['province_id'], 'city_id'=>$input['city_id'] ])->get();
        }else{
            $categoryData = Area::all();
        }

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            // $response['message'] = 'Data found successfully.';
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function update_profile_host(Request $request){
        // dd($request->user_id);
        $serachData = $request->all();
        // $userData = $request->user_id;
        $userId = $request->user_id;
        // dd($userId);
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'surname' => 'required',
            'email' => 'required|:users,email',
            'country_id' => 'required',
            // 'province_id' => 'required',
            // 'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $checkCard = User::where(['id'=>$userId])->first();

            if ($checkCard) {
                $file = $request->file('image');
                // dd($file);
                $document_file = $request->file('document_image');
                if(isset($input['dob']) && $input['dob'] != null){
                    $dob = date('y-m-d', strtotime($input['dob']));
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
                        
                        $data = User::where('id', $userId)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname']??null, 'remarks' => $input['remarks']??null, 'street' => $input['street']??null, 'street_number' => $input['street_number']??null, 'number' => $input['number']??null, 'postal_code' => $input['postal_code']??null, 'country_id' => $input['country_id'], 'province_id' => $input['province_id']??null, 'city_id' => $input['city_id']??null, 'image' => $result['file'], 'secondary_email' => $input['secondary_email']??null, 'second_country_code' => $input['second_country_code']??null, 'second_mobile' => $input['second_mobile']??null, 'dob' => $dob,'document_number' => $input['document_number']??null,'gender' => $input['gender'],'marital_status' => $input['marital_status'], 'document_image' => $document_result['file'], 'email_verified_at'=>date('Y-m-d H:i:s'),'information_correct_or_not' => $input['information_correct_or_not']??null ]);
                    }else{
                        $data = User::where('id', $userId)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname']??null, 'remarks' => $input['remarks']??null, 'street' => $input['street']??null, 'street_number' => $input['street_number']??null, 'number' => $input['number']??null, 'postal_code' => $input['postal_code']??null, 'country_id' => $input['country_id']??null, 'province_id' => $input['province_id']??null, 'city_id' => $input['city_id']??null, 'image' => $result['file'], 'secondary_email' => $input['secondary_email']??null, 'second_country_code' => $input['second_country_code']??null, 'second_mobile' => $input['second_mobile']??null, 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status'],'document_number' => $input['document_number']??null, 'email_verified_at'=>date('Y-m-d H:i:s'),'information_correct_or_not' => $input['information_correct_or_not']??null ]);
                    }
                } else {
                    if(isset($document_file)){
                   

                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                        

                        
                        $data = User::where('id', $userId)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname']??null, 'remarks' => $input['remarks']??null, 'street' => $input['street']??null, 'street_number' => $input['street_number']??null, 'number' => $input['number']??null, 'postal_code' => $input['postal_code']??null, 'country_id' => $input['country_id'], 'province_id' => $input['province_id']??null, 'city_id' => $input['city_id']??null, 'secondary_email' => $input['secondary_email']??null, 'second_country_code' => $input['second_country_code']??null, 'second_mobile' => $input['second_mobile']??null, 'dob' => $dob,'document_number' => $input['document_number']??null,'gender' => $input['gender'],'marital_status' => $input['marital_status'], 'document_image' => $document_result['file'], 'email_verified_at'=>date('Y-m-d H:i:s'),'information_correct_or_not' => $input['information_correct_or_not']??null ]);
                    }else{
                        $data = User::where('id', $userId)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname']??null, 'remarks' => $input['remarks']??null, 'street' => $input['street']??null, 'street_number' => $input['street_number']??null, 'number' => $input['number']??null, 'postal_code' => $input['postal_code']??null, 'country_id' => $input['country_id'], 'province_id' => $input['province_id']??null, 'city_id' => $input['city_id']??null, 'secondary_email' => $input['secondary_email']??null, 'second_country_code' => $input['second_country_code']??null, 'second_mobile' => $input['second_mobile']??null, 'dob' => $dob,'document_number' => $input['document_number']??null,'gender' => $input['gender'],'marital_status' => $input['marital_status'], 'email_verified_at'=>date('Y-m-d H:i:s'),'information_correct_or_not' => $input['information_correct_or_not']??null ]);
                    }
                }
                $bank_data_exist = BankData::where('user_id',$userId)->first();
                if(isset($bank_data_exist) && !empty($bank_data_exist)){
                    BankData::where('user_id', $userId)->update(['method_of_payment' => $input['method_of_payment']??null,  'account_holder_name' => $input['account_holder_name']??null, 'account_number' => $input['account_number']??null, 'iban' => $input['iban']??null, 'vat_number' => $input['vat_number']??null, 'fiscal_code' => $input['fiscal_code']??null, 'route' => $input['route']??null, 'retention' => $input['retention']??null, 'ledger_account' => $input['ledger_account']??null, 'bic_swift' => $input['bic_swift']??null, 'tax' => $input['tax']??null, 'bank_name' => $input['bank_name']??null, 'cnae_code' => $input['cnae_code']??null ]);
                }else{
                    if(isset($input['method_of_payment']) && !empty($input['method_of_payment'])){
                        $bank_data = new BankData;
                        $bank_data->user_id = $userId;
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
                }
                $owner_permissions = ['41','42','43','44','49','50','51','52','86','87','88'];
                foreach ($owner_permissions as $key => $value) {
                    $user = User::findOrFail($userId);
                    $user->givePermissionTo($value);
                }
                // send email start
                /*$email = EmailTemplateLang::where('email_id', 14)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                $subject = $email->subject;
                // $subject = str_replace("[NAME]", $input['name'], $email->subject);
                // $messsage = $message;
                $user = User::where('id',$userId)->first();
                $password = base64_decode($user->user_new_token);
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
                });*/

                $userdata = User::where('id',$user->id)->first();
                    $viewPage = 'emails.other_template';
                    $record = (object)[];
                    $email = EmailTemplateLang::where('email_id', 14)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                 
                    $subject = $email->subject;  
                
                   
                    $description = $email->description;
                    $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                    $url = '<a href="'.url('/admin/login').'" target="_blank">Click Here</a>';
                    $description = str_replace("[URL]", $url, $description);
                    $description = str_replace("[USERNAME]", $request->email, $description);
                    $description = str_replace("[PASSWORD]", base64_decode($userdata->user_new_token), $description);
                 

                    $record->description = $description;    
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $userdata->name.' '.$userdata->surname;
                   
                    $record->subject = $subject;
                    $record->user_email = $userdata->email;
                 
                    $record->check_in_url = url('guest-area/checkin/search');

                   // echo view($viewPage, compact('record'));
        
                    Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                        $message->to($userdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });

                $notificationData = new Notification;
                $notificationData->user_type = 1;
                $notificationData->notification_type = 1;
                $notificationData->notification_for = 'Become_a_host_register';
                $notificationData->title = 'Become A Host Sign-up';
                $notificationData->message = 'New Become A Host registered.';
                $notificationData->user_id = 1;
                $notificationData->save();
                send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                
                $response['status'] = true;
                $response['message'] = 'for creating your Personal profile and accommodation profile- A specialist will contact you within 24/48 hours. The specialist will review your information and request for more information if needed. once successfully reviewed and approved by specialist, You will be required to schedule an in -person inspection to verify the current state of your accomodation ';
                // $response['message'] = 'Become a host created successfully. Admin will send mail shortly!';
                return response()->json($response, 200);
            } else {
                $response['status'] = false;
                $response['message'] = 'Profile not updated.';
                return response()->json($response, 200);
            }
        }
    }

    public function getHomeData1(Request $request){

      //  $data = Cache::remember('homepage', 600, function () {

        //$location = $this->location();
        //$countryId = getCountryIdByLatLong($location['latitude'], $location['longitude']);

        /*if ($countryId == '252') {
            
        } else {
            $countryId = explode(',', $countryId);
        }*/

        $countryId = explode(',', '252,160');




        $getAddressCountryIds = PropertyAddress::whereIn('country_id', $countryId)->pluck('property_id')->toArray();


        // dd($countryId);

        $property = Property::select('properties.*')
        ->where(['properties.status'=>1,'properties.featured'=>'No','properties.luxury'=>'No','properties.rare'=>'No'])
        ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb');


        if (isset($getAddressCountryIds) && !empty($getAddressCountryIds)) {
            /*$featuredPropertyWithPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->where('position', '<=', 15)->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit(15)->get();

            $remainFeaturedProperty = 15 - count($featuredPropertyWithPos);

            $featuredPropertyWithoutPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit($remainFeaturedProperty)->get();*/

            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();

            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();
            $recordData['rareProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.rare'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();

            // dd($recordData['featuredProperty']);
            $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))->groupBy('bookings.property_id')->orderBy('total_count', 'asc')->pluck('bookings.property_id')->toArray();

            $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
            if(!empty($getMostBookedIds))
            {
                //$property->whereNotIn('properties.host', $superHostIds);
                $recordData['superHostProperty'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->limit(15)->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")")->get();
            }
            else
            {
                $recordData['superHostProperty'] = Property::select('properties.*')
                ->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])
                ->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')
                ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->limit(15)
                ->orderBy("properties.position","desc")
                ->get();
            }
            $recordData['rareProperty_book_now'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->limit(15)->get();

        } else {
            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();

            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();

            $recordData['rareProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.rare'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy('properties.position','asc')
            ->limit(15)
            ->get();

            $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))
            ->groupBy('bookings.property_id')
            ->orderBy('total_count', 'asc')
            ->pluck('bookings.property_id')->toArray();

            $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
            if(!empty($getMostBookedIds))
            {                
                //$property->whereNotIn('properties.host', $superHostIds);
                $recordData['superHostProperty'] = Property::select('properties.*')
                ->where(['properties.status'=>1])
                ->whereIn('properties.host', $superHostIds)
                ->join('users','users.id','=','properties.host')
                ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->limit(15)
                ->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")")
                ->get();                
            }
            else
            {
                $recordData['superHostProperty'] = Null;
               // $property->whereNotIn('properties.host', $superHostIds);
                $recordData['superHostProperty'] = Property::select('properties.*')
                ->where(['properties.status'=>1])
                ->whereIn('properties.host', $superHostIds)
                ->join('users','users.id','=','properties.host')
                ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->orderBy("properties.position","desc")    
                ->limit(15)               
                ->get();


            }
            $recordData['rareProperty_book_now'] = Property::select('properties.*')->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->limit(15)->get();


        
        }
        $recordData['count_all_properties'] = $property->count();

        $recordData['property'] = $property->orderBy('id','desc')->limit(15 )->get();

       // $recordData['building'] = Building::where(['status'=>1])->limit(15  )->get();
        $recordData['offers'] = Offer::where(['status'=>1])->with('getDiscountData', 'getOfferAccommodation.getProperty')->limit(6)->get();
        $recordData['limited_category'] = Category::where(['status'=>1])->limit(6)->get();
        $recordData['category'] = Category::where(['status'=>1])->get();
        $recordData['province'] = Province::where(['status'=>1, 'is_website_show'=>1])->orderBy('position', 'asc')->get();
        $recordData['accommodation_types'] = AccommodationType::where(['status'=>1, 'is_home_page_show'=>1])->orderBy('shown_order', 'asc')->get();


     //   return ['data'=>$recordData];
   // });
    $response['status'] = true;
    $response['data'] = $recordData;
    return response()->json($response, 200);



    }
    public function getHomeData(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        // dd($input, 'input');
        $serachData = $request->all();
        $limit = 10;
        $radius = 10;
        $is_pagination = 1;
        $is_featured = 1;

        $validator = Validator::make($request->all(), [
            // 'longitude' => 'required',
            // 'latitude' => 'required',
            // 'main_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $userData = auth()->user();
            if(isset($userData) && $userData != null){
                $userId =  $userData->id;
            }else{
                $userId =  '';
            }

            $getCategory = $this->getCategory($serachData, $limit, $is_pagination);
            $getProperty = $this->getProperty($serachData, $limit, $is_pagination, $userId);

            if (count($getCategory)) {
                $data['category'] = $getCategory;
            } else {
                $data['category'] = [];
            }

            if (count($getProperty)) {
                $data['properties'] = $getProperty;
            } else {
                $data['properties'] = [];
            }

            $response['status'] = true;
            $response['data'] = $data;
            return response()->json($response, 200);
        }
    }

    public function getProperty($serachData,$limit,$is_pagination = 0,$userId=0) {
        $propertyList = Property::select('properties.*','property_address.country_id','property_address.province_id','property_address.city_id','property_bathrooms.bathroom_with_bathtub','property_bedrooms.no_of_bedrooms')->where(['status'=>1])->with('getPropertyCategory','getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getExtraService','getUserWeb')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bathrooms','property_bathrooms.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id');

        if(array_key_exists("category",$serachData) && !empty($serachData['category'])){
            if (strpos($serachData['category'], ',')) { 
                $category = explode (",", $serachData['category']); 
                $category_ids = PropertyCategory::WhereIn("category_id",$category)->pluck('property_id')->toArray();
            }else{
			    $category_ids = PropertyCategory::Where("category_id",$serachData['category'])->pluck('property_id')->toArray();
            }
            $propertyList->whereIn('properties.id',$category_ids);
        }
        if(array_key_exists("services",$serachData) && !empty($serachData['services'])){
            if (strpos($serachData['services'], ',')) { 
                $services = explode (",", $serachData['services']); 
                $service_ids = PropertyExtraService::WhereIn("service_id",$category)->pluck('property_id')->toArray();
            }else{
			    $service_ids = PropertyExtraService::Where("service_id",$serachData['services'])->pluck('property_id')->toArray();
            }
            $propertyList->whereIn('properties.id',$service_ids);
        }
        if(isset($userId) && !empty($userId)){
            $propertyList->Where("properties.host",$userId);
        }
        if(array_key_exists("type",$serachData) && !empty($serachData['type'])){
			$type = $serachData['type'];
			$propertyList->Where("properties.type",'like','%'.$type.'%');
        }
        if(array_key_exists("no_of_bedrooms",$serachData) && !empty($serachData['no_of_bedrooms'])){
			$bedrooms = $serachData['no_of_bedrooms'];
			$propertyList->Where("property_bedrooms.no_of_bedrooms",$bedrooms);
        }
        if(array_key_exists("bathroom",$serachData) && !empty($serachData['bathroom'])){
			$bathroom = $serachData['bathroom'];
			$propertyList->Where("property_bathrooms.bathroom_with_bathtub",$bathroom);
        }
        if(array_key_exists("code",$serachData) && !empty($serachData['code'])){
			$code = $serachData['code'];
			$propertyList->Where("properties.code",$code);
        }
        if(array_key_exists("checkIn",$serachData) && !empty($serachData['checkOut'])){
            $start_date = date('Y-m-d', strtotime($serachData['checkIn']));
            $end_date = date('Y-m-d', strtotime($serachData['checkOut']));
            $alreadybooking = Booking::whereDate('bookings.from_date', '>=', $start_date)->whereDate('bookings.to_date', '<=', $end_date)
            ->pluck('property_id')->toArray();
            $alreadyBlockedDate = PropertyBlockDate::whereBetween('block_date', [$start_date, $end_date])->pluck('property_id')->toArray();
            $book_properties_ids = array_unique(array_merge($alreadybooking, $alreadyBlockedDate));
            // dd($book_properties_ids);
            $propertyList->whereNotIn('properties.id',$book_properties_ids);
        }
        if(array_key_exists("building",$serachData) && !empty($serachData['building'])){
			$building = $serachData['building'];
			$propertyList->Where("properties.building",$building);
        }
        if(array_key_exists("max_guest",$serachData) && !empty($serachData['max_guest'])){
			$max_guest = $serachData['max_guest'];
			$propertyList->Where("properties.max_guest",'<=',$max_guest);
        }
        if(array_key_exists("is_super_host",$serachData) && !empty($serachData['is_super_host'])){
            if($serachData['is_super_host'] == 'Yes'){
                $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
                $propertyList->whereIn('host', $superHostIds);
                // $propertyList->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")");
            }
        }
        if(array_key_exists("country",$serachData) && !empty($serachData['country'])){
			$country = $serachData['country'];
            $country_id = Country::where('name','like','%'.$country.'%')->pluck('id')->toArray();

            $propertyList = $propertyList->where(function($query) use ($country_id){
                $query->whereIn('property_address.country_id', $country_id);
                // $query->whereIn('property_address.city_id', $city_id);
            });
			// $propertyList->WhereIn("property_address.city_id",$city_id);
        }
        if(array_key_exists("province",$serachData) && !empty($serachData['province'])){
			$province = $serachData['province'];
            $province_id = Province::where('name','like','%'.$province.'%')->pluck('id')->toArray();

            // $propertyList = $propertyList->with(array('city' => function ($query) use ($city_id) {
            //     $query->where('property_address.city_id',$city_id);
            // }))

            $propertyList = $propertyList->where(function($query) use ($province_id){
                $query->whereIn('property_address.province_id', $province_id);
                // $query->whereIn('property_address.city_id', $city_id);
            });
			// $propertyList->WhereIn("property_address.city_id",$city_id);
        }
        if(array_key_exists("province_id",$serachData) && !empty($serachData['province_id'])){
            $province = $serachData['province_id'];
            $propertyList = $propertyList->where(function($query) use ($province){
                $query->where('property_address.province_id', $province);
            });
        }
        if(array_key_exists("city",$serachData) && !empty($serachData['city'])){
			$city = $serachData['city'];
            $city_id = City::where('name','like','%'.$city.'%')->pluck('id')->toArray();

            // $propertyList = $propertyList->with(array('city' => function ($query) use ($city_id) {
            //     $query->where('property_address.city_id',$city_id);
            // }))

            $propertyList = $propertyList->where(function($query) use ($city_id){
                $query->whereIn('property_address.city_id', $city_id);
                // $query->whereIn('property_address.city_id', $city_id);
            });
			// $propertyList->WhereIn("property_address.city_id",$city_id);
        }
        if(array_key_exists("city_id",$serachData) && !empty($serachData['city_id'])){
            $city_id = $serachData['city_id'];
            $propertyList = $propertyList->where(function($query) use ($city_id){
                $query->where('property_address.city_id', $city_id);
            });
        }
        if (isset($serachData['featured']) && !empty($serachData['featured']) ) {
            if (isset($serachData['featured']) && $serachData['featured'] == 'Yes') {
                $propertyList->where('properties.featured','Yes');
            }else{
                $propertyList->where(['properties.featured'=>'No']);
            }
        }
        if(array_key_exists("review",$serachData) && !empty($serachData['review'])){
			$review = $serachData['review'];
            $propertyList->Where("properties.avg_rating",$review);
        }
        if(array_key_exists("search_text",$serachData)){
			$search = $serachData['search_text'];
			$propertyList->Where("properties.title",'like','%'.$search.'%');
        }
        if ($limit != 'All') {
            if ($is_pagination == 1) {
                $propertyDetail = $propertyList->paginate($limit);
            } else {
                $propertyList->limit($limit);
                $propertyDetail = $propertyList->get();
            }
        } else {
            $propertyDetail = $propertyList->get();
        }
        return $propertyDetail;
    }

    public function getCategory($serachData,$limit,$is_pagination = 0) {
        $categoryList = Category::select('id','type','name','image')->where(['status'=>1]);

        if(array_key_exists("search_text",$serachData)){
			$search = $serachData['search_text'];
			$categoryList->Where("categories.name",'like','%'.$search.'%');
        }
        if ($limit != 'All') {
            if ($is_pagination == 1) {
                $categoryDetail = $categoryList->paginate($limit);
            } else {
                $categoryList->limit($limit);
                $categoryDetail = $categoryList->get();
            }
        } else {
            $categoryDetail = $categoryList->get();
        }
        // if($limit != 'All'){
        //     $categoryList->limit($limit);
        // }
        // $categoryDetail= $categoryList->get();
        return $categoryDetail;
    }

    public function propertyList(Request $request)
    {
        $response = array();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        // dd($input, 'input');
        $serachData = $request->all();
        $limit = 10;
        $radius = 10;
        $is_pagination = 1;

        $userData = auth()->user();
            if(isset($userData) && $userData != null){
                $userId =  $userData->id;
            }else{
                $userId =  '';
            }
            
     

        $getProperty = $this->getProperty($serachData, $limit, $is_pagination, $userId);

        if (count($getProperty)) {
            $response['status'] = true;
            $response['data'] = $getProperty;
        } else {
            $response['status'] = false;
            $response['data'] = [];
        }
        return response()->json($response, 200);


    }

    public function propertyDetails(Request $request){
        $this->code = 200;
        $serachData = $request->all();

        $validator = Validator::make($request->all(), [
            'propertyid' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {

            if (auth('api')->check()) {
                $userId = Auth::guard('api')->user()->id;

            } else {
                $userId =  '';
            }

            $admin_data = AdminSettings::first();
            $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';

            if(isset($loyalty_percentage) && !empty($loyalty_percentage) && !empty($userId)) {
                $total_debit = UserLoyaltyPoint::where(['user_id'=>$userId, 'type'=>'Debit'])->sum('points');
                $total_credit = UserLoyaltyPoint::where(['user_id'=>$userId, 'type'=>'Credit'])->sum('points');

                if (isset($total_debit) && !empty($total_debit)) {
                    $loyalty_points = $total_credit - $total_debit;

                    if ($loyalty_points > $admin_data->royalty_point_equal_to) {
                        $points_amount1 = floor($loyalty_points / $admin_data->royalty_point_equal_to);
                        $points_amount = $points_amount1 * $admin_data->second_royalty_amount;
                        $final_loyalty_points = $points_amount1 * $admin_data->royalty_point_equal_to;

                    } else {
                        $points_amount = '';
                    }

                } else {
                    $loyalty_points = $total_credit;

                    if ($loyalty_points > $admin_data->royalty_point_equal_to) {
                        $points_amount1 = floor($loyalty_points / $admin_data->royalty_point_equal_to);
                        $points_amount = $points_amount1 * $admin_data->second_royalty_amount;
                        $final_loyalty_points = $points_amount1 * $admin_data->royalty_point_equal_to;

                    } else {
                        $final_loyalty_points = '';
                        $points_amount = '';
                    }
                }

            } else {
                $final_loyalty_points = '';
                $points_amount = '';
            }

            $propertyList = Property::select('properties.*','users.name as host_name')->where(['properties.status'=>1])->with('getHostDetails','getPropertyAddress.getPropertyCity','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyCountry','getPropertyCategory.getCategoryData','getAmenities.getAmenityData','getExtraService.getServiceData','getPropertyBedroom','getProPropertyBathroom','getPropertyBedding','getPropertyKitchen','getPropertyImages','getPropertyRating.getUser')->leftjoin('users','users.id','=','properties.host')->where('properties.id',$serachData['propertyid'])->first();
            if(isset($propertyList) && !empty($propertyList)){
                $propertyList->loyalty_points = $final_loyalty_points;
                $propertyList->points_amount = $points_amount;
    
                return $propertyList;
            }else{
                $response['status'] = false;
                $response['message'] = 'Accommodation not found.';
                return response()->json($response, 200);
            }
        }
    }

    public function propertyDetailsBookingDates(Request $request){
        $this->code = 200;
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'propertyid' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            if (auth('api')->check()) {
                $userId = Auth::guard('api')->user()->id;
            } else {
                $userId =  '';
            }
            if(isset($input['page']) && !empty($input['page']) ){
                $page = $input['page'];
            }else{
                $page = 1;
            }
            $range = 6;
            $start_range = (($range*$page)-6);
            $end_range = $range*$page;

            $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));;
            $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
            $allDates = [];

            $alreadybooking = Booking::where('property_id', $input['propertyid'])->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
                ->pluck('from_date','to_date')->toArray();

            $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['propertyid'])->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();

            $mo = [];
            for($i=0; $i<6;$i++)
            {
                $allDates = [];
                $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
                $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
                $getThisMonth = date('m',strtotime($start_date_new));
                $allDates = array();

                if ($alreadybooking) {
                    foreach ($alreadybooking as $key => $value) {
                        $getstarMonth = date('m',strtotime($value));
                        $getendMonth = date('m',strtotime($key));
                        if(($getstarMonth == $getendMonth) && ($getThisMonth == $getstarMonth))
                        {
                            $period = CarbonPeriod::create($value, $key);
                            foreach ($period as $date) {
                                $allDates[] = $date->format('Y-m-d');
                            }
                        }
                        else
                        {
                            $period = CarbonPeriod::create($value, $key);
                            foreach ($period as $date) {
                                if($getThisMonth == $date->format('m'))
                                {
                                    $allDates[] = $date->format('Y-m-d');
                                }
                            }
                        }
                    }
                    $allDates = array_unique($allDates);
                }

                if ($alreadyBlockedDate) {
                    foreach ($alreadyBlockedDate as $k => $v) {
                        $getBlockstarMonth = date('m',strtotime($v));
                        $getblockEndMonth = date('m',strtotime($k));
                        $date = date('Y-m-d', strtotime($v));

                        if (($getBlockstarMonth) && ($getThisMonth == $getBlockstarMonth)) {
                            $allDates[] = $date;
                        } else {
                            if ($getThisMonth == $getBlockstarMonth) {
                                $allDates[] = $date;
                            }
                        }
                    }
                    $allDates = array_unique($allDates);
                }
                $mo[$i] = implode(',', $allDates);
            }

            $start_date1 = explode('-',$start_date);
            $end_date1 = explode('-',$end_date);

            $data = [
                'start_date'=> $start_date,
                'start_day'=>$start_date1[2],
                'start_month'=>$start_date1[1],
                'start_year'=>$start_date1[0],
                'end_date'=>$end_date,
                'end_day'=>$end_date1[2],
                'end_month'=>$end_date1[1],
                'end_year'=>$end_date1[0],          
                'monthWiseData'=>$mo,          
            ];

            $response['status'] = true;
            $response['data'] = $data;
            $response['message'] = 'Record found.';

            return response()->json($response, 200);
        }
    }

    public function getOffer(){
        
        $propertyList = Offer::select('offers.*','offer_accommodations.property_id','discounts.code')->leftjoin('offer_accommodations','offer_accommodations.offer_id','=','offers.id')->leftjoin('discounts','discounts.id','=','offers.discount')->get();

        if(isset($propertyList) && count($propertyList) > 0){
            $response['status'] = true;
            $response['data'] = $propertyList;
        }else{
            $response['status'] = false;
            $response['message'] = 'Offer not found.';
        }
        return response()->json($response, 200);
        // return $propertyList;
    }

    public function OfferDetail(Request $request){
        $input = $request->all();

        $message = [
        ];
        $validator = Validator::make($input, [
            'offer_id'   => 'required',
        ], $message);

        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $this->errorValidation($validator);

            return response()->json($response, 200);
        } else {
            $propertyList = Offer::select('offers.*','offer_accommodations.property_id','discounts.code')->leftjoin('offer_accommodations','offer_accommodations.offer_id','=','offers.id')->leftjoin('discounts','discounts.id','=','offers.discount')->where('offers.id',$input['offer_id'])->first();

            if(isset($propertyList) && !empty($propertyList) > 0){
                $response['status'] = true;
                $response['data'] = $propertyList;
            }else{
                $response['status'] = false;
                $response['message'] = 'Offer not found.';
            }
            return response()->json($response, 200);
        }
        // return $propertyList;
    }

    public function sendOtp(Request $request)
    {
        // dd($request->all());
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;

      
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];

        $validator = Validator::make($input, [
            'type'   => 'required'
        ], $message);

        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            $locale = App::getLocale();

            if ($request->type == 'register') {
                $validator = Validator::make($input, [
                    'country_code'   => 'required',
                    'mobile' => 'required|min:7|max:15',
                    'email' => 'required|unique:users,email',
                ], $message);
                if ($validator->fails()) {
                    $this->errorValidation($validator);
                } else {
                    // if(array_key_exists('email',$input) && !empty($input['email'])){
                        // $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'email' => $input['email']])->first();
                    // }else{
                        $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
                    // }
                    if (!$user) {
                        // create otp 
                        $otps = $this->generateRandomString('N', 4);
                        $message = __("api.sendOtpMessage") . $otps;
                        $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
                        if (!isset($otp)) {
                            $otp = new UserOtp;
                            $otp->country_code = $input['country_code'];
                            $otp->mobile = $input['mobile'];
                        }
                        $otp->otp = $otps;
                        $otp->save();
                        $user['name'] = $input['full_name'];
                        $user['email'] = $input['email'];
                        
                        $this->setOtpMail($user,$otps);

                        /*Send SMSCountry*/
                        // if ($locale == 'en') {
                        //     $langType = 'N';
                        // } else {
                        //     $langType = 'LNG';
                        // }
                        // $username = 'Tahadiyaat';
                        // $sender_id = 'TAHADIYATAE';
                        // $password = 'Tahadiyaat01$';

                        // $curlUrlNew = "http://api.smscountry.com/SMSCwebservice_bulk.aspx?mobilenumber=" . $input['country_code'] . $input['mobile'] . "&message=" . urlencode($message) . "&User=" . $username . "&passwd=" . $password . "&sid=" . $sender_id . "&mtype=" . $langType . "&DR=Y";
                        // // dd($curlUrlNew);

                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => $curlUrlNew,
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => "",
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 30,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => "GET",
                        //     CURLOPT_HTTPHEADER => array(
                        //         "cache-control: no-cache",
                        //         "postman-token: 7e81e559-a81b-d18c-a629-908156cda911"
                        //     ),
                        // ));
                        // $response = curl_exec($curl);
                        // $err = curl_error($curl);
                        // // echo "<pre>";print_r($curl);die;
                        // curl_close($curl);
                        /*Send SMSCountry*/
                        // return response
                        $this->message  = __("api.sendOtpSuccess");
                        $this->status   = true;
                        $this->data = $otps;
                    } else {
                        $this->message  = __("api.mobile_number_already_exsits");
                        $this->status   = false;
                    }
                    $this->code     = 200;
                }
            } elseif ($request->type == 'resend') {
                // dd('resend');
                $message = [
                    'mobile.required' => __("api.mobile_required"),
                    'mobile.min' => __("api.mobile_min"),
                    'mobile.max' => __("api.mobile_max"),
                ];
                $validator = Validator::make($input, [
                    'country_code'   => 'required',
                    'mobile' => 'required|min:7|max:15',
                ], $message);
                if ($validator->fails()) {
                    $this->errorValidation($validator);
                } else {
                    
                
                    // create otp 
                    $otps = $this->generateRandomString('N', 4);
                    $message = __("api.sendOtpMessage") . $otps;
                    $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();

                    if (!isset($otp)) {
                        $otp = new UserOtp;
                        $otp->country_code = $input['country_code'];
                        $otp->mobile = $input['mobile'];
                    }
                    $user['name'] = $input['full_name'];
                    $user['email'] = $input['email'];
                    $otp->otp = $otps;
                    $otp->save();

                    $user['name'] = $input['full_name'];
                    $user['email'] = $input['email'];


                    $this->setOtpMail($user,$otps);
                    /*Send SMSCountry*/
                    // if ($locale == 'en') {
                    //     $langType = 'N';
                    // } else {
                    //     $langType = 'LNG';
                    // }
                    // $username = 'Tahadiyaat';
                    // $sender_id = 'TAHADIYATAE';
                    // $password = 'Tahadiyaat01$';

                    // $curlUrlNew = "http://api.smscountry.com/SMSCwebservice_bulk.aspx?mobilenumber=" . $input['country_code'] . $input['mobile'] . "&message=" . urlencode($message) . "&User=" . $username . "&passwd=" . $password . "&sid=" . $sender_id . "&mtype=" . $langType . "&DR=Y";
                    // // dd($curlUrlNew);

                    // $curl = curl_init();
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => $curlUrlNew,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => "",
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 30,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => "GET",
                    //     CURLOPT_HTTPHEADER => array(
                    //         "cache-control: no-cache",
                    //         "postman-token: 7e81e559-a81b-d18c-a629-908156cda911"
                    //     ),
                    // ));
                    // $response = curl_exec($curl);
                    // $err = curl_error($curl);
                    // // echo "<pre>";print_r($curl);die;
                    // curl_close($curl);
                    /*Send SMSCountry*/
                    // return response
                    $this->message  = __("api.sendOtpSuccess");
                    $this->status   = true;
                    $this->data = $otps;
                    $this->code     = 200;
                }
            } elseif ($request->type == 'forgot') {
                $message = [
                    'mobile.required' => __("api.mobile_required"),
                    'mobile.min' => __("api.mobile_min"),
                    'mobile.max' => __("api.mobile_max"),
                    'mobile.exists' => __("api.mobile_exists"),
                ];
                $validator = Validator::make($input, [
                    'country_code'   => 'required',
                    'mobile' => 'required|min:8|max:15',
                ], $message);
                if ($validator->fails()) {
                    $this->errorValidation($validator);
                } else {
                    $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'] ])->first();
                    
                    if ($user) {
                        // create otp 
                        $otps = $this->generateRandomString('N', 4);
                        $message = __("api.sendOtpMessage") . $otps;
                        $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();

                        if (!isset($otp)) {
                            $otp = new UserOtp;
                            $otp->country_code = $input['country_code'];
                            $otp->mobile = $input['mobile'];
                        }
                        $otp->otp = $otps;
                        $otp->save();
                        $user['name'] = $user->name;
                        $user['email'] = $user->email;

                        $this->setOtpMail($user,$otps);
                        /*Send SMSCountry*/
                        // if ($locale == 'en') {
                        //     $langType = 'N';
                        // } else {
                        //     $langType = 'LNG';
                        // }
                        // $username = 'Tahadiyaat';
                        // $sender_id = 'TAHADIYATAE';
                        // $password = 'Tahadiyaat01$';

                        // $curlUrlNew = "http://api.smscountry.com/SMSCwebservice_bulk.aspx?mobilenumber=" . $input['country_code'] . $input['mobile'] . "&message=" . urlencode($message) . "&User=" . $username . "&passwd=" . $password . "&sid=" . $sender_id . "&mtype=" . $langType . "&DR=Y";
                        // // dd($curlUrlNew);

                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => $curlUrlNew,
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => "",
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 30,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => "GET",
                        //     CURLOPT_HTTPHEADER => array(
                        //         "cache-control: no-cache",
                        //         "postman-token: 7e81e559-a81b-d18c-a629-908156cda911"
                        //     ),
                        // ));
                        // $response = curl_exec($curl);
                        // $err = curl_error($curl);
                        // // echo "<pre>";print_r($curl);die;
                        // curl_close($curl);
                        /*Send SMSCountry*/
                        // return response
                        $this->message  = __("api.sendOtpSuccess");
                        $this->status   = true;
                        $this->data = $otps;
                        $this->code     = 200;
                    } else {
                        $this->message  = __("api.mobile_exists");
                        $this->status   = false;
                    }
                }
            } else {
                // return response
                $this->message  = __("api.not_valid_type");
                $this->status   = false;
                $this->code     = 200;
            }
        }
        return $this->jsonResponse();
    }

    public function setOtpMail($user,$otp){
       
        $data = ['user'=>$user,'otp'=>$otp];
        Mail::send('emails.otp',$data, function($message)use($user) {
            $subject = 'Otp Verification';
            $message->to($user['email'], config('app.name'))->subject($subject);
            $message->from('customersupport@shortletrenrals.com',config('app.name'));
        });
    }

    public function signUp(Request $request)
    {
        // dd($request->all());
        $this->code = 200;  
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];

        $validator = Validator::make($input, [
            'full_name'   => 'required',
            'email'   => 'required|unique:users,email',
            'country_code'   => 'required',
            'mobile'   => 'required|min:7|max:15',
            'password'   => 'required',
        ], $message);

        if ($validator->fails()) {
            // $this->errorValidation($validator);
            $response['status'] = 0;
            $response['message'] = $this->errorValidation($validator);

        } else {
            $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            
            if (!$user) {

                $user = new User;
                $hash = Hash::make($input['password']);
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->country_code = $input['country_code'];
                $user->mobile = $input['mobile'];
                $user->password = $hash;
                $user->name = $input['full_name'];
                $user->email = $input['email'];
                $user->status = 1;
                $user->user_type = 4;

                if ($user->save()) {

                    // create otp 
                    // $otps = $this->generateRandomString('N', 4);
                    // $message = __("api.sendOtpMessage") . $otps;
                    // $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
                    // if (!isset($otp)) {
                    //     $otp = new UserOtp;
                    //     $otp->country_code = $input['country_code'];
                    //     $otp->mobile = $input['mobile'];
                    // }
                    // $otp->otp = $otps;
                    // $otp->save();

                    // dd($otp);
                    // /*Send SMSCountry*/
                    // if ($locale == 'en') {
                    //     $langType = 'N';
                    // } else {
                    //     $langType = 'LNG';
                    // }
                    // $username = 'Tahadiyaat';
                    // $sender_id = 'TAHADIYATAE';
                    // $password = 'Tahadiyaat01$';

                    // $curlUrlNew = "http://api.smscountry.com/SMSCwebservice_bulk.aspx?mobilenumber=" . $input['country_code'] . $input['mobile'] . "&message=" . urlencode($message) . "&User=" . $username . "&passwd=" . $password . "&sid=" . $sender_id . "&mtype=" . $langType . "&DR=Y";
                    // // dd($curlUrlNew);

                    // $curl = curl_init();
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => $curlUrlNew,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => "",
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 30,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => "GET",
                    //     CURLOPT_HTTPHEADER => array(
                    //         "cache-control: no-cache",
                    //         "postman-token: 7e81e559-a81b-d18c-a629-908156cda911"
                    //     ),
                    // ));
                    // $response = curl_exec($curl);
                    // $err = curl_error($curl);
                    // // echo "<pre>";print_r($curl);die;
                    // curl_close($curl);
                    // /*Send SMSCountry*/
                    // return response
                    // $this->message  = __("api.sendOtpSuccess");
                    // $this->status   = true;

                    // $response['status'] = 1;
                    // $response['message'] = __("api.sendOtpSuccess");
                    // $response['data'] = ['country_code'=>$otp->country_code, 'mobile'=>$otp->mobile, 'otp'=>$otp->otp];

                    $email = EmailTemplateLang::where('email_id', 1)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[NAME]", $input['full_name'], $email->subject);
                    // $messsage = $message;
                    $description = $email->description;
                    $description = str_replace("[NAME]", $input['full_name'], $email->description);

                    $register_detail=(object)[];
                    $register_detail->name = str_replace("[NAME]", $input['full_name'], $email->name);
                    $register_detail->subject = $subject;
                    $register_detail->description = $description;
                    $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
                    $user = User::where('id',$user->id)->first();


                    Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                        $message->to($user->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com',config('app.name'));
                    });

                    $userdata = User::where('id',$user->id)->first();
                    $notificationData = new Notification;
                    $notificationData->user_type = $userdata->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Registration';
                    $notificationData->title = 'Welcome';
                    $notificationData->message = 'Welcome to Shortlet Rental.';
                    $notificationData->user_id = $userdata->id;
                    $notificationData->save();
                    send_notification(1, $userdata->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                    $user1 = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
                    ->where(function ($query) {
                        $query->where('user_type','=',4);
                    })->first();
                    $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
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
                    $response['status'] = true;
                    $response['token'] = $token;
                    $response['data'] = new UserResource($user1);;
                    $response['message'] = 'User register successfully.';
                    return response()->json($response, 200);
                    // dd($response);
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Error Occured.';
                    return response()->json($response, 200);
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'User already available.';
                return response()->json($response, 200);
                // $this->message  = __("api.mobile_number_already_exsits");
                // $this->status   = false;
            }
        }
        return response()->json($response, 200);
        // return $this->jsonResponse($response);
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
            if(isset($input['schedule_time']) && !empty($input['appointment_date']) && !empty($input['province_id'])){
                $appointment_date = date('Y-m-d', strtotime(str_replace("/","-",$input['appointment_date'])));
                $check = Appointment::where(['schedule_time'=>$input['schedule_time'], 'appointment_date'=>$appointment_date, 'province_id' => $input['province_id'] ])->first();

                if(empty($check)){
                    $data = new Appointment();
                    $data->title = $input['title'];
                    $data->full_name = $input['full_name'];
                    $data->surname = $input['surname'];
                    $data->email = $input['email'];
                    $data->country_code = $input['country_code'] ?? null;
                    $data->mobile = $input['mobile'] ?? null;
                    $data->appointment_date = $appointment_date;
                    $data->schedule_time = $input['schedule_time'] ?? null;
                    $data->province_id = $input['province_id'] ?? null;
                    $data->address = $input['address'] ?? null;
                    $data->landmark = $input['landmark'] ?? null;
                    $data->onsite_inspection = $input['onsite_inspection'] ?? null;
                    $data->contact_person_name = $input['contact_person_name'] ?? null;
                    $data->contact_person_country_code = $input['contact_person_country_code'] ?? null;
                    $data->contact_person_number = $input['contact_person_number'] ?? null;
        
                    if ($data->save()) {
                        // $email = EmailTemplateLang::where('email_id', 1)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                        // $subject = $email->subject;
                        // $subject = str_replace("[NAME]", $input['full_name'], $email->subject);
                        // // $messsage = $message;
                        // $description = $email->description;
                        // $description = str_replace("[NAME]", $input['full_name'], $email->description);
    
                        // $register_detail=(object)[];
                        // $register_detail->name = str_replace("[NAME]", $input['full_name'], $email->name);
                        // $register_detail->subject = $subject;
                        // $register_detail->description = $description;
                        // $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
                        // $user = User::where('id',$user->id)->first();
    
                        // Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                        //     $message->to($user->email, config('app.name'))->subject($subject);
                        //     $message->from('customersupport@shortletrenrals.com',config('app.name'));
                        // });

                        $response['status'] = true;
                        $response['message'] = 'Your appointment is booked, we will contact you soon.';
                    } else {
                        $response['status'] = false;
                        $response['message'] = 'Something went wrong, Please try again.';
                    }
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Appointment already exist. Please choose another date and time.';
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Please select all mandatory fields.';
            }
            return response()->json($response, 200);
        }
    }

    public function partner_signup(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];
        $validator = Validator::make($input, [
            'fullname'   => 'required',
            'email'   => 'required|unique:users,email',
            'country_code'   => 'required',
            'mobile'   => 'required|min:7|max:15',
            'password'   => 'required',
        ], $message);

        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'user_type' => 3])
            ->where(function ($query) {
                $query->where('user_type','=',3);
            })->first();
            if (!$user) {

                $user = new User;
                $hash = Hash::make($input['password']);
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->title = $input['title'];
                $user->name = $input['fullname'];
                $user->surname = $input['surname'];
                $user->country_code = $input['country_code'];
                $user->mobile = $input['mobile'];
                $user->password = $hash;
                $user->email = $input['email'];

                $user->hear_about_us = $input['hear_about_us']??null;
                $user->country_id = $input['country_id']??null;
                $user->province_id = $input['province_id']??null;
                $user->city_id = $input['city_id']??null;
                $user->area = $input['area']??null;
                $user->landmark = $input['landmark']??null;
                $user->address = $input['address']??null;
                $user->status = 1;
                $user->user_type = 3;

                if ($user->save()) {

                    $permissions = ['45','46','47','48','49','50','51','52','53','54','55','56','86','87','88'];
                    foreach ($permissions as $key => $value) {
                        $user_new = User::findOrFail($user->id);
                        $user_new->givePermissionTo($value);
                    }

               
                    $userdata = User::where('id',$user->id)->first();
                    $viewPage = 'emails.other_template';
                    $record = (object)[];
                    $email = EmailTemplateLang::where('email_id', 37)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                 
                    $subject = $email->subject;  
                
                   
                    $description = $email->description;
                    $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                    $url = '<a href="'.url('/admin/login').'" target="_blank">Click Here</a>';
                    $description = str_replace("[URL]", $url, $description);
                    $description = str_replace("[USERNAME]", $request->email, $description);
                    $description = str_replace("[PASSWORD]", $request->password, $description);
                 

                    $record->description = $description;    
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $userdata->name.' '.$userdata->surname;
                   
                    $record->subject = $subject;
                    $record->user_email = $userdata->email;
                 
                    $record->check_in_url = url('guest-area/checkin/search');

                   // echo view($viewPage, compact('record'));
        
                    Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                        $message->to($userdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });

                    $notificationData = new Notification;
                    $notificationData->user_type = $userdata->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Partner-Registration';
                    $notificationData->title = 'Partner Registration';
                    $notificationData->message = ucwords($input['fullname'].' '.$input['surname']).' partner account has been created.';
                    $notificationData->user_id = 1;
                    $notificationData->save();





                    $user1 = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
                    ->where(function ($query) {
                        $query->where('user_type','=',3);
                    })->first();
                    $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
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
                    $response['status'] = true;
                    $response['token'] = $token;
                    $response['data'] = new UserResource($user1);;
                    $response['message'] = 'Thank you for registering to become an Affiliate partner, a specialist will contact you within 24 hours for the next steps.';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Error Occured.';
                    return response()->json($response, 200);
                }
            } else {
                $this->message  = __("api.mobile_number_already_exsits");
                $this->status   = false;
            }
        }
        return $this->jsonResponse();
    }

    public function customer_signUp(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];

        $validator = Validator::make($input, [
            'fullname'   => 'required',
            'email'   => 'required',
            'country_code'   => 'required',
            'mobile'   => 'required|min:7|max:15',
            'password'   => 'required',
        ], $message);

        if ($validator->fails()) {
            $this->errorValidation($validator);

        } else {
            // $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            $user = User::where(function($q)use($input){
                $q->where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'user_type' => 4]);
                $q->orWhere('email',$input['email']);
            })
            ->where(function ($query) {
                $query->where('user_type','=',4);
            })->first();






            // dd($user);
            if (!$user) {

                $user = new User;
                $hash = Hash::make($input['password']);
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->name = $input['fullname'];
                $user->surname = $input['surname'] ?? null;
                $user->country_code = $input['country_code'];
                $user->mobile = $input['mobile'];
                $user->password = $hash;
                $user->email = $input['email'];
                $user->status = 1;
                $user->user_type = 4;

                if ($user->save()) {
                    $email = EmailTemplateLang::where('email_id', 1)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[NAME]", $input['fullname'], $email->subject);
                    // $messsage = $message;
                    $description = $email->description;
                    $description = str_replace("[NAME]", $input['fullname'], $email->description);

                    $register_detail=(object)[];
                    $register_detail->name = str_replace("[NAME]", $input['fullname'], $email->name);
                    $register_detail->subject = $subject;
                    $register_detail->description = $description;
                    $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
                    $user = User::where('id',$user->id)->first();

                    Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                        $message->to($user->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com',config('app.name'));
                    });

                    $userdata = User::where('id',$user->id)->first();
                    $notificationData = new Notification;
                    $notificationData->user_type = $userdata->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Registration';
                    $notificationData->title = 'Welcome';
                    $notificationData->message = 'Welcome to Shortlet Rental.';
                    $notificationData->user_id = $userdata->id;
                    $notificationData->save();
                    send_notification(1, $userdata->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                    $notificationData = new Notification;
                    $notificationData->user_type = 1;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'guest_register';
                    $notificationData->title = 'Guest Sign-up';
                    $notificationData->message = 'New Guest has been registered.';
                    $notificationData->user_id = 1;
                    $notificationData->save();
                    send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                    $user1 = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
                    ->where(function ($query) {
                        $query->where('user_type','=',4);
                    })->first();
                    $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
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
                    $response['status'] = true;
                    $response['token'] = $token;
                    $response['data'] = new UserResource($user1);;
                    $response['message'] = 'User register successfully.';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 0;
                    $response['message'] = 'Error Occured.';
                    return response()->json($response, 200);
                }
            } else {
                $hash = Hash::make($input['password']);

                $user->password = $hash;
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->country_code = $input['country_code'];
                $user->mobile = $input['mobile'];
                $user->save();
                $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
                

                $token = JWTAuth::attempt($credentials);
                $response['status'] = true;
                $response['token'] = $token;
                $response['data'] = new UserResource($user);;
                $response['message'] = 'User register successfully.';
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function otpVerify(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'country_code.required' => __("api.country_code_required"),
            'otp.required' => __("api.otp_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];
        $validator = Validator::make($input, [
            'country_code'   => 'required',
            'mobile' => 'required|min:7|max:15',
            'otp' => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
           
            $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();

            if (isset($otp) && ($input['otp'] == $otp->otp  || $input['otp'] == '1234')) {
                $response['status'] = true;
                $response['message'] = __("api.otp_verify_success");
                return response()->json($response, 200);
            } else {
                $response['status'] = false;
                $response['message'] = __("api.otp_invalid_message");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function setPassword(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.unique' => __("api.mobile_unique"),
            'country_code.required' => __("api.country_code_required"),
            'otp.required' => __("api.otp_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
            'password.required' => __("api.password_required"),
        ];
        $validator = Validator::make($input, [
            'country_code'   => 'required',
            'mobile' => 'required|min:7|max:15',
            'password'   => 'required|min:6',
            'otp' => 'required',

        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {

            $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            if (isset($otp) && ($input['otp'] == $otp->otp  || $input['otp'] == '1234')) {
                $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
                if (!isset($user)) {
                    $user = new User;
                    $unique_id = random_int(10000000, 99999999);
                    $user->unique_id = $unique_id;
                    $user->country_code = $input['country_code'];
                    $user->mobile = $input['mobile'];
                    $user->status = 1;
                    $user->type = 3;
                    $user->password = Hash::make($request->input('password'));
                    $user->save();
                } else {
                    $this->message  = __("api.mobile_number_already_exsits");
                    $this->status   = false;
                }
                $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];

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
                // send notification start
                $user_id =  $user->id;
                if (isset($user_id)) {
                    $locale = App::getLocale();
                    // App::SetLocale('ar');
                    $notification_title = 'notification_create_user_title';
                    $notification_message = 'notification_create_user_message';
                    send_notification_add($user_id, $user_type = 3, $notification_type = 3, $notification_for = 'create_user', $order_id = $user_id, $title = $notification_title, $message = $notification_message);
                    App::SetLocale($locale);
                    $notification_title = __('api.notification_create_user_title');
                    $notification_message = __('api.notification_create_user_message');
                    send_notification(1, $user_id, $notification_title, array('title' => $notification_title, 'message' => $notification_message, 'type' => 'create_user', 'key' => 'create_user'));
                    // panal notification start
                    $playerChanellId = 'pubnub_onboarding_channel_player_' . $user_id;
                    send_admin_notification($message = $notification_message, $title = $notification_title, $channel_name = $playerChanellId);

                    $locale = App::getLocale();
                    $admin_notification_title = 'admin_notification_create_user_title';
                    $admin_notification_message = 'admin_notification_create_user_message';
                    $login_user_data = auth()->user();
                    $adminChanellId = 'pubnub_onboarding_channel_admin_1';
                    add_admin_notification($user_type = 0, $notification_type = 0, $notification_for = 'create_user', $title = $admin_notification_title, $message = $admin_notification_message, $user_id = 1, $order_id = $user_id);
                    App::SetLocale($locale);
                    $admin_notification_title = __('backend.admin_notification_create_user_title');
                    $admin_notification_message = __('backend.admin_notification_create_user_message');
                    send_admin_notification($message = $admin_notification_message, $title = $admin_notification_title, $channel_name = $adminChanellId);
                    //panal notification end
                }
                // send notification end

                $response['status'] = true;
                $response['token'] = $token;
                $response['data'] = new UserResource($user);
                $response['message'] = __("api.login_successfully");
                return response()->json($response, 200);
                // return response
                // $response['status'] = true;
                // $response['message'] = __("api.set_password_message");
                // return response()->json($response, 200);

            } else {
                $response['status'] = false;
                $response['message'] = __("api.otp_invalid_message");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function resetPassword(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'mobile.unique' => __("api.mobile_unique"),
            'country_code.required' => __("api.country_code_required"),
            'otp.required' => __("api.otp_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
            'password.required' => __("api.password_required"),
        ];
        $validator = Validator::make($input, [
            'country_code'   => 'required',
            'mobile' => 'required|min:7|max:15',
            'password'   => 'required|min:6',
            'otp' => 'required',

        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {

            $otp = UserOtp::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();

            if (isset($otp) && ($input['otp'] == $otp->otp  || $input['otp'] == '1234')) {
                $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
                if (!isset($user)) {
                    $user = new User;
                    $unique_id = random_int(10000000, 99999999);
                    $user->unique_id = $unique_id;
                    $user->country_code = $input['country_code'];
                    $user->mobile = $input['mobile'];
                }
                $user->password = Hash::make($request->input('password'));
                $user->save();
                // return response
                $this->message  = __("api.reset_password_message");
                $this->status   = true;
                $this->code     = 200;
            } else {
                $response['status'] = false;
                $response['message'] = __("api.otp_invalid_message");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function updatePassword(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'old_password.required' => __("api.old_password_required"),
            'new_password.required' => __("api.new_password_required"),
            'new_password.min' => __("api.new_password_min"),
            'old_password.min' => __("api.old_password_min"),
        ];
        $validator = Validator::make($input, [
            // 'old_password'   => 'required',
            'new_password'   => 'required|min:6',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            if(User::where(['country_code'=>$input['country_code'], 'mobile'=>$input['mobile']])->update(['password'=>Hash::make($input['new_password']) ])){
                $response['status'] = true;
                $response['message'] = 'Password update successfully.';
                return response()->json($response, 200);                    
            }else{
                $response['status'] = false;
                $response['message'] = __("api.something_worng");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }
    public function HostLogin(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        // dd($input);
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'country_code.required' => __("api.country_code_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
            'password.required' => __("api.password_required"),
            'password.min' => __("api.password_min"),
        ];
        $validator = Validator::make($input, [
            'country_code'   => 'required',
            'mobile' => 'required|min:7|max:15',
            'password'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            // dd($input['user_type']);
            $user_type = isset($input) && !empty($input['user_type']) ? (int)$input['user_type'] : 5;
            // dd($user_type);
            $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
            ->where(function ($query) {
                $query->where('user_type','=',5);
            })->first();
            
            if (isset($user)) {
                if ($user->status == 1 && empty($user->deleted_at)) {
                    $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
                    if (Hash::check($input['password'], $user->password)) {
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
                        return response()->json($response, 200);
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
                $response['status'] = false;
                $response['message'] = __("api.invalid_user_login");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }
    public function login(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'country_code.required' => __("api.country_code_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
            'password.required' => __("api.password_required"),
            'password.min' => __("api.password_min"),
        ];
        $validator = Validator::make($input, [
            // 'country_code'   => 'required',
            // 'mobile' => 'required|min:7|max:15',
            // 'password'   => 'required',
            'type'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            if ($request->type == 'Mobile') {
                $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->where('user_type',4)->first();
                if (isset($user)) {
                    if ($user->status == 1 && empty($user->deleted_at)) {
                        $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
                        if (Hash::check($input['password'], $user->password)) {
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

                                // $userdata = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'] ])->first();
                                // $notificationData = new Notification;
                                // $notificationData->user_type = $userdata->user_type;
                                // $notificationData->notification_type = 1;
                                // $notificationData->notification_for = 'Test';
                                // $notificationData->title = 'Test Notification.';
                                // $notificationData->message = 'Test notification for testing.';
                                // $notificationData->user_id = $userdata->id;
                                // $notificationData->save();
                                // send_notification(1, $userdata->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                                
                            }
                            $user->is_guest = 0;
                            $user->save();  
                            //Notification send
                            // send_notification(1, $user->id, 'Welcome Back', array('title'=>'Welcome Back','message'=>'You are successfully login.','type'=>'Login','key'=>'Login'));
                            //End Notification send
                            $response['status'] = true;
                            $response['token'] = $token;
                            $response['data'] = new UserResource($user);
                            $response['message'] = __("api.login_successfully");
                            return response()->json($response, 200);
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
                    $response['status'] = false;
                    $response['message'] = "Invalid mobile number.";
                    // $response['message'] = __("api.invalid_user_login");
                    return response()->json($response, 200);
                }
            } elseif ($request->type == 'Email') {
                $user = User::where(['email' => $input['email'] ])
                ->where(function ($query) {
                    $query->where('user_type','=',4);
                })->first();

                if (isset($user)) {
                    if ($user->status == 1 && empty($user->deleted_at)) {
                        $credentials = ['email' => $input['email'], 'password' => $input['password']];
                        if (Hash::check($input['password'], $user->password)) {
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
                            return response()->json($response, 200);
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
                    $response['status'] = false;
                    $response['message'] = __("api.invalid_user_login");
                    return response()->json($response, 200);
                }
            } else {
                // return response
                $this->message  = "Type is not valid.";
                $this->status   = false;
                $this->code     = 200;
            }
        }
        return $this->jsonResponse();
    }
    public function host_login(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'country_code.required' => __("api.country_code_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
            'password.required' => __("api.password_required"),
            'password.min' => __("api.password_min"),
        ];
        $validator = Validator::make($input, [
            // 'country_code'   => 'required',
            // 'mobile' => 'required|min:7|max:15',
            // 'password'   => 'required',
            'type'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            if ($request->type == 'Mobile') {
                $user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])
                ->where(function ($query) {
                    $query->where('user_type','=',5);
                    // $query->OrWhere('user_type','=',3);
                })->first();

                if (isset($user)) {
                    if ($user->status == 1 && empty($user->deleted_at)) {
                        $credentials = ['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'password' => $input['password']];
                        if (Hash::check($input['password'], $user->password)) {
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
                            return response()->json($response, 200);
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
                    $response['status'] = false;
                    $response['message'] = "Invalid mobile number.";
                    // $response['message'] = __("api.invalid_user_login");
                    return response()->json($response, 200);
                }
            } elseif ($request->type == 'Email') {
                $user = User::where(['email' => $input['email'] ])
                ->where(function ($query) {
                    $query->where('user_type','=',5);
                })->first();

                if (isset($user)) {
                    if ($user->status == 1 && empty($user->deleted_at)) {
                        $credentials = ['email' => $input['email'], 'password' => $input['password']];
                        if (Hash::check($input['password'], $user->password)) {
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
                            return response()->json($response, 200);
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
                    $response['status'] = false;
                    $response['message'] = __("api.invalid_user_login");
                    return response()->json($response, 200);
                }
            } else {
                // return response
                $this->message  = "Type is not valid.";
                $this->status   = false;
                $this->code     = 200;
            }
        }
        return $this->jsonResponse();
    }
    public function mobileSocialLogin(Request $request)
    {
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'password.required' => __("api.password_required"),
            'password.min' => __("api.password_min"),
        ];
        $validator = Validator::make($input, [
            'social_type'   => 'required',
            'email'   => 'required',
            'social_id'   => 'required',
            'name'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            
            $user = User::where(['email' => $input['email'] ])
            ->where(function ($query) {
                $query->where('user_type','=',4);
            })->first();
            // dd($user);
            if (isset($user)) {
                if ($user->status == 1 && empty($user->deleted_at)) {
                    $credentials = ['email' => $input['email'], 'password' => $input['social_id']];
                    if (Hash::check($input['social_id'], $user->password)) {
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
                        // Session::forget('is_guest');
                        // Session::put('AuthUserData', (object)json_decode($data));
                        // return redirect()->route('web.home');
                        return response()->json($response, 200);
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
                $unique_id = random_int(10000000, 99999999);
                $user = New User;
                $user->unique_id = $unique_id;
                $user->social_type = $input['social_type'];
                $user->social_id = $input['social_id'];
                $user->name = $input['name']??null;
                $user->email = $input['email'];
                $user->image = $input['image']??null;
                $user->password = Hash::make($input['social_id']);
                $user->image_type = 'url';
                $user->user_type = 4;
                $user->social_json = json_encode($input);
                $user->status = 1;
                if($user->save()){
                    $credentials = ['email' => $input['email'], 'password' => $input['social_id']];
                    if (Hash::check($input['social_id'], $user->password)) {
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
                        return response()->json($response, 200);
                        // $data =  json_encode($response);
                        // Session::forget('is_guest');
                        // Session::put('AuthUserData', (object)json_decode($data));
                        // return redirect()->route('web.home');
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
        }
        return $this->jsonResponse();
    }
    public function updateProfile(Request $request)
    {
        $user = JWTAuth::user();
        // dd($user->id);
        // $user = Auth::user();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'full_name.required' => __("api.first_name_required"),
            'last_name.required' => __("api.last_name_required"),
            'email.required' => __("api.email_required"),
            'gender.required' => __("api.gender_required"),
            'image.required' => __("api.image_required"),
            'email.unique' => __("api.email_unique"),
            'dob.required' => __("api.dob_required"),
            'dob.date_format' => __("api.dob_date_format"),
            'dob.before' => __("api.dob_before"),

        ];
        $validator = Validator::make($input, [
            'full_name'   => 'required',
            'email'   => 'email|unique:users,email,' . $user->id,
            // 'email'   => 'email',
            'dob'   => 'date_format:Y-m-d|before:today',
            'gender'   => 'required',
            'marital_status'   => 'required',
            // 'image'   => 'required',

        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            $user_old_data = User::where('id',$user->id)->first();
            DB::table('json')->insert([
                'json' => json_encode($input),
            ]);
            // image upload
            if ($request->file('image')) {
                $file = $request->file('image');
            
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'user/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $user->image = $result['file'];
                $user->image_type = 'local';
                
            }
            $user->name = $request->input('full_name') ?? $user_old_data->name;
            $user->email = $request->input('email') ?? $user_old_data->email;
            $user->gender = $request->input('gender') ?? $user_old_data->gender;
            $user->dob = $request->input('dob') ?? $user_old_data->dob;
            $user->marital_status = $request->input('marital_status') ?? $user_old_data->marital_status;
            $user->country_id = $request->input('country_id') ?? $user_old_data->country_id;
            $user->province_id = $request->input('province_id') ?? $user_old_data->province_id;
            $user->city_id = $request->input('city_id') ?? $user_old_data->city_id;
            $user->street = $request->input('street') ?? $user_old_data->street;
            $user->address = $request->input('address') ?? $user_old_data->address  ;
            $user->street_number = $request->input('street_number') ?? $user_old_data->street_number;
            $user->postal_code = $request->input('postal_code') ?? $user_old_data->postal_code;


            if ($user->update()) {
                $userData = User::where(['id' => $user->id])->first();
                $data =  new UserResource($userData);
                $response['status'] = true;
                $response['message'] = __("api.profile_update_message");
                $response['data'] = $data;
            } else {
                $response['status'] = false;
                $response['message'] = __("api.something_worng");
            }
            return response()->json($response, 200);
        }
        return $this->jsonResponse();
    }
    public function updateProfileMobile(Request $request)
    {
        $user = JWTAuth::user();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'mobile.required' => __("api.mobile_required"),
            'country_code.required' => __("api.country_code_required"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),
        ];
        $validator = Validator::make($input, [
            'country_code'   => 'required',
            'mobile' => 'required|min:7|max:15',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            $is_user = User::where(['country_code' => $input['country_code'], 'mobile' => $input['mobile']])->first();
            if (!isset($is_user)) {
                $user->country_code = $request->input('country_code');
                $user->mobile = $request->input('mobile');
                if ($user->update()) {
                    $userData = User::where(['id' => $user->id])->first();
                    $data =  new UserResource($userData);
                    $response['status'] = true;
                    $response['message'] = __("api.mobile_update_message");
                    $response['data'] = $data;
                } else {
                    $response['status'] = false;
                    $response['message'] = __("api.something_worng");
                }
            } else {
                $response['message']  = __("api.mobile_number_already_exsits");
                $response['status']  = false;
            }
            return response()->json($response, 200);
        }
        return $this->jsonResponse();
    }

    public function userProfile()
    {
        $user = JWTAuth::user();
      
        $uesr = User::where('id',$user->id)->first();     
        if ($user) {

            $data =  new UserResource($user);
            // dd($user->id);
            $debit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Debit'])->sum('points');
            if(isset($debit_loyalty_points) && !empty($debit_loyalty_points) ){
                $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
                $total_loyalty_points = $credit_loyalty_points - $debit_loyalty_points;
            }else{
                $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
                $total_loyalty_points = $credit_loyalty_points;
            }
            $loyalty_points = UserLoyaltyPoint::where('user_id',$user->id)->orderBy('id','desc')->get();
            // dd($data);
            $response['status'] = true;
            $response['data'] = $data;
            $response['loyalty_points'] = $loyalty_points;
            $response['total_loyalty_points'] = $total_loyalty_points;
            return response()->json($response, 200);
        } else {
            $response['status'] = false;
            $response['message'] = __("api.something_worng");
            return response()->json($response, 200);
        }
        return response()->json();
    }

    public function royalty_points()
    {
        $user = JWTAuth::user();
        if ($user) {

            $data =  new UserResource($user);
            // dd($user->id);
            $debit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Debit'])->sum('points');
            if(isset($debit_loyalty_points) && !empty($debit_loyalty_points) ){
                $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
                $total_loyalty_points = $credit_loyalty_points - $debit_loyalty_points;
            }else{
                $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
                $total_loyalty_points = $credit_loyalty_points;
            }
            $loyalty_points = UserLoyaltyPoint::where('user_id',$user->id)->get();
            // dd($data);
            $data1['loyalty_points'] = $loyalty_points;
            $data1['total_loyalty_points'] = $total_loyalty_points;
            // dd($data);
            $response['status'] = true;
            $response['data'] = $data1;
            return response()->json($response, 200);
        } else {
            $response['status'] = false;
            $response['message'] = __("api.something_worng");
            return response()->json($response, 200);
        }
        return response()->json();
    }

    public function changePassword(Request $request)
    {
        $user = JWTAuth::user();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'old_password.required' => __("api.old_password_required"),
            'new_password.required' => __("api.new_password_required"),
            'new_password.min' => __("api.new_password_min"),
            'old_password.min' => __("api.old_password_min"),
        ];
        $validator = Validator::make($input, [
            'old_password'   => 'required',
            'new_password'   => 'required|min:6',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {

            $user = JWTAuth::user();
            if (isset($user)) {

                if (Hash::check($input['old_password'], $user->password)) {
                    $user->password = Hash::make($input['new_password']);

                    if ($user->save()) {

                        $response['status'] = true;
                        $response['message'] = __("api.password_change_success");
                        return response()->json($response, 200);
                    } else {
                        $response['status'] = false;
                        $response['message'] = __("api.something_worng");
                        return response()->json($response, 200);
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = __("api.old_password_incorrect");
                    return response()->json($response, 200);
                }
            } else {
                $response['status'] = false;
                $response['message'] = __("api.invalid_user");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function forgetUpdatePassword(Request $request)
    {
        $user = JWTAuth::user();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'old_password.required' => __("api.old_password_required"),
            'new_password.required' => __("api.new_password_required"),
            'new_password.min' => __("api.new_password_min"),
            'old_password.min' => __("api.old_password_min"),
        ];
        $validator = Validator::make($input, [
            // 'old_password'   => 'required',
            'new_password'   => 'required|min:6',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {

            $user = JWTAuth::user();
            if (isset($user)) {

                if (Hash::check($input['old_password'], $user->password)) {
                    $user->password = Hash::make($input['new_password']);

                    if ($user->save()) {

                        $response['status'] = true;
                        $response['message'] = __("api.password_change_success");
                        return response()->json($response, 200);
                    } else {
                        $response['status'] = false;
                        $response['message'] = __("api.something_worng");
                        return response()->json($response, 200);
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = __("api.old_password_incorrect");
                    return response()->json($response, 200);
                }
            } else {
                $response['status'] = false;
                $response['message'] = __("api.invalid_user");
                return response()->json($response, 200);
            }
        }
        return $this->jsonResponse();
    }

    public function socialLogin(Request $request)
    {
        // dd('socialLogin',$request->all());

        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $user = User::where(['social_type' => $input['social_type'], 'social_id' => $input['social_id']])->first();
        // dd($user);
        $message = [
            'social_type.required' => __("api.social_type_required"),
            'social_id.required' => __("api.social_id_required"),
            // 'first_name.required' => __("api.first_name_required"),
            // 'last_name.required' => __("api.last_name_required"),
            'email.unique' => __("api.email_unique"),
            'mobile.min' => __("api.mobile_min"),
            'mobile.max' => __("api.mobile_max"),

        ];
        if ($user !== null) {
            $validator = Validator::make($input, [
                'social_type'   => 'required',
                'social_id' => 'required',
                // 'first_name'   => 'required',
                // 'last_name'   => 'required',
                'email'   => 'unique:users,email,' . $user->id,
                'mobile' => 'min:7|max:15',
            ], $message);
        } else {
            $validator = Validator::make($input, [
                'social_type'   => 'required',
                'social_id' => 'required',
                // 'first_name'   => 'required',
                // 'last_name'   => 'required',
                'email'   => 'unique:users,email,',
                'mobile' => 'min:7|max:15',
            ], $message);
        }

        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            // dd($input);
            $user = User::where(['social_type' => $input['social_type'], 'social_id' => $input['social_id']])->first();
            if (!isset($user)) {
                $user = new User;
                $unique_id = random_int(10000000, 99999999);
                $user->unique_id = $unique_id;
                $user->social_type = $input['social_type'];
                $user->social_id = $input['social_id'];
                $user->password = Hash::make($input['social_id']);
                $user->first_name = $request->first_name ? $input['first_name'] : null;
                $user->last_name = $request->last_name ? $input['last_name'] : null;
                $user->name = $request->first_name ? $input['first_name'] . ' ' . $input['last_name'] : '';
                $user->email = $request->email ? $input['email'] : null;
                $user->image = $request->image ? $input['image'] : null;
                $user->mobile = $request->mobile ? $input['mobile'] : null;
                $user->image_type = 'url';
                $user->status = 1;
                $user->type = 3;

                $user->save();
            }
            $credentials = ['social_id' => $input['social_id'], 'password' => $input['social_id']];

            if (!$token = JWTAuth::attempt($credentials)) {
                $response['status'] = false;
                $response['message'] = __("api.invalid_user_login");
                return response()->json($response, 200);
            }

            $response['status'] = true;
            $response['token'] = $token;
            $response['data'] = new UserResource($user);
            $response['message'] = __("api.login_successfully");
            return response()->json($response, 200);
        }
        return $this->jsonResponse();
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = JWTAuth::user();
        // $data = UserDevice::where(['user_id' => $user->id])->first();
       
        // if (isset($data)) {
        //     $data->device_type = null;
        //     $data->device_token = null;
        //     $data->save();
        // }
        auth()->logout();
        $response['status'] = true;
        $response['message'] = __("api.user_logout_message");
        return response()->json($response, 200);
    }

    public function generateRandomString($type = null, $length = 6)
    {
        if ($type == 'N') {
            $string = '0123456789';
        } else if ($type == 'A') {
            $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        return substr(str_shuffle(str_repeat($string, ceil($length / strlen($string)))), 1, $length);
    }

    public function forgot_password(Request $requset)
    {
        $input =  $requset->all();
        $message = [
            'mobile.required' => 'Mobile Number is required.',
            'mobile.min' => 'The Mobile number must be at least 7 characters',
            'mobile.max' => 'The Mobile number may not be greater than 15 characters.'
        ];
        $validator = Validator::make($input, [ 
            'country_code'   => 'required',
            'mobile'=> 'required|min:7|max:15',
        ],$message);
        if ($validator->fails()) 
        {
            $errors     =   $validator->errors();
            $response['status'] = 0;
            $response['message'] = $errors;
            return response()->json($response, 200);
        }
        else
        {
            $otps = $this->generateRandomString('N',4);
            $message = "Your OTP Code is ".$otps;
            $message = str_replace(' ','%20', $message);
              
            $user = User::where(['country_code'=>$input['country_code'],'mobile'=>$input['mobile']])->first();
            if(isset($user))
            {
                $otp = UserOtp::where(['country_code'=>$input['country_code'],'mobile'=>$input['mobile']])->first();

                if(!isset($otp))
                {
                    $otp = new UserOtp; 
                    $otp->country_code = $input['country_code'];
                    $otp->mobile = $input['mobile'];                        
                }
                $otp->otp = $otps;
                $otp->save(); 

                $response['status'] = 1;
                $response['message'] = 'OTP sent successfully.';
                $response['data'] = $otps;
                return response()->json($response, 200);
            }
            else
            {
                $response['status'] = 0;
                $response['message'] = 'User not found.';
                return response()->json($response, 200);
            }
                      
        }
    }
}
