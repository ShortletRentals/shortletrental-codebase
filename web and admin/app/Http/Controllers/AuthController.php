<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
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
use App\Models\PropertyReserveRequest;
use App\Models\BookingSearch;
use App\Models\PropertyBlockDate;
use App\Models\Country;
use App\Models\Building;
use App\Models\Rating;
use App\Models\BookingCheckIn;
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
use App\Models\Rate;
use App\Models\Transection;
use App\Models\Sattlement;
use App\Models\Discount;
use App\Models\OfferAccommodation;
use App\Models\PropertyTag;
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
use DateTime;
use App\Exports\BulkWarehouseExport;
use Log;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getCityArea','otpVerify','getHomeData','getHomeData1', 'sendOtp', 'login', 'host_login','mobileSocialLogin','HostLogin', 'signUp','becomeahost_signUp','customer_signUp', 'forgot_password', 'resetPassword', 'setPassword', 'socialLogin','propertyList','propertyDetails','propertyDetailsBookingDates','getOffer','OfferDetail','update_profile_host','getAllCountry','getAllCategory','getAllProvince','getAllProvince1','getAllCity','getAllArea','getAllServices','getAllAmenities','getPropertyMinMaxPrice','partner_signup','schedule_appointment','getAllTypes','becomeahost_add_property','becomeahost_user_details','becomeahost_user_update','getContactDetails','updateContactDetails','getAllBlogs','getBlogDetails','updatePassword','getStaticData','addProvince','addCity','addArea','becomeahost_login','admin_Accomodation_list','becomeahost_logout','becomeahost_userdetail','becomeahost_changePassword','getAllDiscount','getAllAccomodation', 'downloadFile']]);
    }
    public function downloadFile($filename)
    {
        // Retrieve the file from the storage directory
        $file = Storage::disk('public')->get($filename);

        // Set the appropriate headers for file download
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Return the file as a response
        return response()->download(storage_path('app/public/' . $filename), $filename, $headers);
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

   
    public function update_property(Request $request)
    {
        $input = $request->all();
        $this->requestdata = $input;

        $message = [
            'user_id.required' => 'User id is required',
            'property_id.required' => 'Property id is required',

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
            
        ], $message);

        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            // Assuming Property model has been defined
            $property = Property::where(['id' => $input['property_id']]);

            if (!$property) {
                $response['status'] = false;
                $response['message'] = 'Property not found.';
            } else {
                // Update the property fields based on the input data
                $property->title = $input['title'];
                $property->type = $input['type'];
                $property->price = $input['price'];
                // Update other fields as needed

                // Save the changes to the property
                $property->save();

                // Update other related tables/models if needed

                // Update PropertyAddress
                $propertyAddress = PropertyAddress::where('property_id', $input['property_id'])->first();
                if ($propertyAddress) {
                    $propertyAddress->address = $input['address'] ?? null;
                    $propertyAddress->country_id = $input['country_id'];
                    $propertyAddress->province_id = $input['province_id'];
                    $propertyAddress->city_id = $input['city_id'] ?? null;
                    // Update other fields as needed
                    $propertyAddress->save();
                }

                // Update PropertyCategory
                PropertyCategory::where('property_id', $input['property_id'])->delete();
                if (isset($input['category']) && !empty($input['category'])) {
                    $category = $input['category'];
                    $category1 = explode(",", $category);
                    foreach ($category1 as $cat) {
                        $data_category = new PropertyCategory;
                        $data_category->property_id = $input['property_id'];
                        $data_category->category_id = $cat;
                        $data_category->save();
                    }
                }

                // Update other related tables/models as needed

                $response['status'] = true;
                $response['message'] = 'Accommodation updated successfully.';
            }
        }

        return response()->json($response);
    }

    public function becomeahost_userdetail(Request $request)
    {
        $input =  $request->all();
        // dd($input);
        $this->requestdata = $input;
        $message = [
            'id.required' => 'User id is required',
        ];
        $validator = Validator::make($input, [
            'id'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $response = $this->errorValidation($validator);
        } else {
            $userData = User::where(['id'=>$input['id'], 'user_type' => 5])->first();
            // dd($userData);
            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            if(isset($userData) && !empty($userData)){
                $response['status'] = true;
                $response['data'] = $userData;
                $response['permission'] = $permission;
                 $response['notification'] =Notification::where(['user_id' => $userData, 'is_read' => 0])->count();
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
                        $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                    $response['status'] = true;
                    $response['token'] = $token;
                    $response['data'] = new UserResource($user1);
                    $response['permission'] = $permission;
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
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                $response['status'] = true;
                $response['message'] = 'User updated successfully.';
                $response['permission'] = $permission;
                $response['notification'] =Notification::where(['user_id' => $user, 'is_read' => 0])->count();
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


    public function becomeahostBooking(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }
                $q = Booking::select('bookings.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');

                $q->where('host_id',auth()->id());
                
                if(isset($request->orderby) && $request->orderby == 'stay'){
                    $orderby = 'bookings.from_date';
                }elseif(isset($request->orderby) && $request->orderby == 'book'){
                    $orderby = 'properties.title';
                }else{
                    $orderby = $request->orderby ? $request->orderby : 'bookings.created_at';
                }
                $orderby = $orderby;
                $order = $request->order ? $request->order : 'desc';
                if(isset($request->booking_type) && in_array($request->booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid','Pending-payment']))
                {
                    if($request->booking_type == 'Pending-payment'){
                      $q->where('bookings.booking_type','!=','Confirmed')->where('bookings.booking_type','!=','Paid');
                    }else{
                      $q->where('bookings.booking_type',$request->booking_type);
                    }
                }
                if(isset($request->booking_status) && in_array($request->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
                {
                    $q->where('bookings.booking_status',$request->booking_status);
                }
                if(isset($request->booking) && !empty($request->booking)){
                    if($request->booking == 'Website'){
                      $q->where('bookings.booking_from',$request->booking);
                    }else if($request->booking == 'Mobile'){
                      $q->where('bookings.booking_from',$request->booking);
                    }else if($request->booking == 'Guest_bookings'){
                      $guest_ids = User::where(['status'=>1, 'is_guest'=>1])->pluck('id')->toArray();
                      $q->whereIn('guest_id',$guest_ids);
                    }else if($request->booking == 'Customer_bookings'){
                      $customer_ids = User::where(['status'=>1, 'is_guest'=>0])->pluck('id')->toArray();
                      $q->whereIn('guest_id',$customer_ids);
                    }
                }
                $date_of = 'created_at';
                if(isset($request->start_date) && isset($request->end_date) && !empty($request->start_date) && !empty($request->end_date))
                {
                    $q->whereDate('bookings.'.$date_of,'>=',$request->start_date)->whereDate('bookings.'.$date_of,'<=',$request->end_date);
                }else{
                    if (isset($request->start_date) && !empty($request->start_date)) {
                        $q->whereDate('bookings.'.$date_of,'>=',$request->start_date);
                    }else{
                        if (isset($request->end_date) && !empty($request->end_date)) {
                            $q->whereDate('bookings.'.$date_of,'<=',$request->end_date);
                        }
                    }
                }
                

                if(isset($request->booking_id) && !empty($request->booking_id))
                {
                    $q->where('bookings.booking_id',$request->booking_id);
                }

                if(isset($request->guest_id) && !empty($request->guest_id))
                {
                    $q->where('bookings.guest_id',$request->guest_id);
                }
                if ($request->search && !empty($request->search)) {
                    $search = $request->search;
                    $q->where(function($query) use ($search) {
                        $query->where('properties.title', 'LIKE', '%' . $search . '%');                
                    });
                }
                
                $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
                if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                    foreach ($results as $value) {
                        $extraservice = '';
                            if(isset($value->selected_options))
                            {
                                $extraservice1 = json_decode($value->selected_options);
                                foreach($extraservice1 as $v1)
                                {


                                    $extraservice =  $extraservice.$v1->name.',';
                                }
                            }

                        $property = Property::with('getExtraService.getServiceData')->where(['id' => $value->property_id])->first();
                  

                        if(isset($property)){
                         
                            


                        $property_name = $property->title;
                        }else{
                            $property_name = '';
                        }
                        if(isset($value) && !empty($value->from_date)){
                            $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                            $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                            $stay_date = $from_date.' - '.$to_date;
                        }else{
                            $stay_date = '';
                        }
                        $row['id'] = $value->id;
                        $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                        $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                        $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                        $row['total_days'] = isset($value->total_days)? $value->total_days:'-';
                        $row['stay'] = $stay_date;

                        if (auth()->user()->can('Booking-edit')) 
                        {
                            $row['booking_status'] = bookingStatus($value->booking_status, $value->id,['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'],'booking_status',$this->page.'.bookingStatus')->toHtml();
                            $row['booking_type'] = bookingType($value->booking_type, $value->id,['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'],'booking_type',$this->page.'.bookingType')->toHtml();
                        }
                        else
                        {
                            $row['booking_status'] = str_replace('-',' ',$value->booking_status);
                            $row['booking_type'] = str_replace('-',' ',$value->booking_type);
                        }

                        $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                        $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                        $row['book'] = $property_name;
                        if ($extraservice == '') {
                            $row['extra_service'] = '';
                        }else{
                            $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                        }
                        
                        $check_in_data = BookingCheckIn::where('booking_id',$value->id)->first();
                        if(!isset($check_in_data))
                        {
                            $row['booking_check_in'] = 'No';
                        }
                        else{
                            if($check_in_data->is_approved == 1)
                            {
                                $row['booking_check_in']    = 'Approve';
                            }
                            else if($check_in_data->is_approved == 0){
                                $row['booking_check_in']    = 'Pending';
                            }

                            else{
                                $row['booking_check_in']    = 'Reject';
                            }


                        }
                        $datas[] = $row;
                        $i++;
                        unset($u);
                    }
                     $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostBookingShow(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'id.required' => 'Booking Id',
            ];
            $validator = Validator::make($input, [
                'id'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                
                $q = Booking::select('bookings.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');

                //$q->where('host_id',auth()->id());
                
                
                $results = $q->where('bookings.id', $request->id)->first();
                if(isset($results->property_id)){
                    $propertyData = Property::where(['id' => $results->property_id])->first();
                    if(isset($propertyData)){
                        $results['property_details'] = $propertyData;
                    }else{
                        $results['property_details'] = '';
                    }

                 $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                    

                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostBookingReserve(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }
                $q = PropertyReserveRequest::select('property_reserve_requests.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->leftjoin('properties','properties.id','=','property_reserve_requests.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');

                $q->where('host_id',auth()->id());
                
                if(isset($request->orderby) && $request->orderby == 'stay'){
                    $orderby = 'property_reserve_requests.from_date';
                }elseif(isset($request->orderby) && $request->orderby == 'book'){
                    $orderby = 'properties.title';
                }else{
                    $orderby = $request->orderby ? $request->orderby : 'property_reserve_requests.created_at';
                }
                $orderby = $orderby;
                $order = $request->order ? $request->order : 'desc';
                if(isset($request->booking_type) && in_array($request->booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid','Pending-payment']))
                {
                    if($request->booking_type == 'Pending-payment'){
                      $q->where('property_reserve_requests.booking_type','!=','Confirmed')->where('property_reserve_requests.booking_type','!=','Paid');
                    }else{
                      $q->where('property_reserve_requests.booking_type',$request->booking_type);
                    }
                }
                if(isset($request->booking_status) && in_array($request->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
                {
                    $q->where('property_reserve_requests.booking_status',$request->booking_status);
                }
                if(isset($request->booking) && !empty($request->booking)){
                    if($request->booking == 'Website'){
                      $q->where('property_reserve_requests.booking_from',$request->booking);
                    }else if($request->booking == 'Mobile'){
                      $q->where('property_reserve_requests.booking_from',$request->booking);
                    }else if($request->booking == 'Guest_bookings'){
                      $guest_ids = User::where(['status'=>1, 'is_guest'=>1])->pluck('id')->toArray();
                      $q->whereIn('guest_id',$guest_ids);
                    }else if($request->booking == 'Customer_bookings'){
                      $customer_ids = User::where(['status'=>1, 'is_guest'=>0])->pluck('id')->toArray();
                      $q->whereIn('guest_id',$customer_ids);
                    }
                }
                $date_of = 'created_at';
                if(isset($request->start_date) && isset($request->end_date) && !empty($request->start_date) && !empty($request->end_date))
                {
                    $q->whereDate('property_reserve_requests.'.$date_of,'>=',$request->start_date)->whereDate('property_reserve_requests.'.$date_of,'<=',$request->end_date);
                }else{
                    if (isset($request->start_date) && !empty($request->start_date)) {
                        $q->whereDate('property_reserve_requests.'.$date_of,'>=',$request->start_date);
                    }else{
                        if (isset($request->end_date) && !empty($request->end_date)) {
                            $q->whereDate('property_reserve_requests.'.$date_of,'<=',$request->end_date);
                        }
                    }
                }
                

                if(isset($request->booking_id) && !empty($request->booking_id))
                {
                    $q->where('property_reserve_requests.booking_id',$request->booking_id);
                }

                if(isset($request->guest_id) && !empty($request->guest_id))
                {
                    $q->where('property_reserve_requests.guest_id',$request->guest_id);
                }
                if ($request->search && !empty($request->search)) {
                    $search = $request->search;
                    $q->where(function($query) use ($search) {
                        $query->where('properties.title', 'LIKE', '%' . $search . '%');                
                    });
                }
                
                $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
                if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                    foreach ($results as $value) {
                        
                        $extraservice = '';
                            if(isset($value->selected_options))
                            {
                                $extraservice1 = json_decode($value->selected_options);
                                foreach($extraservice1 as $v1)
                                {


                                    $extraservice =  $extraservice.$v1->name.',';
                                }
                            }
                            


                       
                        $guestData = User::where(['user_type'=>4, 'id' => $value->guest_id])->first();
                        if(isset($guestData)){
                            $guest_name = $guestData->name.' ('.'+'.$guestData->country_code.' '.$guestData->mobile.')';
                            $unique_id = $guestData->unique_id;
                            $guest_mobile = $guestData->mobile;
                        }else{
                            $guest_name = '';
                            $unique_id = '';
                            $guest_mobile = '';
                        }
                        $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                        // dd($hostData);
                        if(isset($hostData)){
                            $host_name = $hostData->name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
                            $host_mobile = $hostData->mobile;
                        }else{
                            $host_name = '';
                            $host_mobile = '';
                        }
                        // dd($value->property_id);
                        $property = Property::where(['id' => $value->property_id])->pluck('title')->first();
                        if(isset($property)){
                            $property_name = $property;
                        }else{
                            $property_name = '';
                        }
                        if(isset($value) && !empty($value->from_date)){
                            $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                            $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                            // dd($from_date, $to_date);
                            $stay_date = $from_date.' - '.$to_date;
                        }else{
                            $stay_date = '';
                        }
                        // dd($stay_date);
                        if(isset($value->personal_last_name) && !empty($value->personal_last_name)){
                            $guest_name = $value->personal_first_name.' '.$value->personal_last_name;
                        }else{
                            $guest_name = isset($value->personal_first_name)? $value->personal_first_name:'N/A';
                        }
                        $row['id'] = $value->id;
                        $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                        $row['book'] = $property_name;
                        $row['guest_name'] = $guest_name;
                        $row['host_name'] = $host_name;
                        $row['unique_id'] = $unique_id;
                        $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                        $row['total_days'] = isset($value->total_days)? $value->total_days:'-';
                        $row['total_amount'] = $value->host_amount +  $value->optional_service_amount; 
                        $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                        $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'-';
                        $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                        $row['stay'] = $stay_date;
                        $row['booking_status'] = $value->book_type;
                        // if($value->book_type == 'Approve'){
                        //     $row['booking_status'] = $value->book_type;
                        //     $row['booking_status'] = 'Approve';
                        // }else{
                        //     $row['booking_status'] = 'Deny';
                        // }

                        $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                        $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                        
                        $datas[] = $row;
                        $i++;
                        unset($u);
                    }

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostBookingReserveShow(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'id.required' => 'Property Reserve Request Id',
            ];
            $validator = Validator::make($input, [
                'id'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                
                $q = PropertyReserveRequest::select('property_reserve_requests.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->leftjoin('properties','properties.id','=','property_reserve_requests.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');

                //$q->where('host_id',auth()->id());
                
                
                $results = $q->where('property_reserve_requests.id', $request->id)->first();
                if(isset($results->property_id)){
                    $propertyData = Property::where(['id' => $results->property_id])->first();
                    if(isset($propertyData)){
                        $results['property_details'] = $propertyData;
                    }else{
                        $results['property_details'] = '';
                    }

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Property Reserve Request not found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostBookingSearch(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }
                $q = BookingSearch::select('properties.*','property_address.city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id');

                $q->where('host',auth()->id());
                if(isset($request->orderby) && $request->orderby == 'stay'){
                    $orderby = 'bookings.from_date';
                }elseif(isset($request->orderby) && $request->orderby == 'book'){
                    $orderby = 'properties.title';
                }else{
                    $orderby = $request->orderby ? $request->orderby : 'bookings.created_at';
                }
                $orderby = $orderby;
                $order = $request->order ? $request->order : 'desc';
                if(isset($request->status) )
                { 
                    $q->where('properties.status',$request->status);
                }
                if(isset($request->start_date) && !empty($request->start_date) && isset($request->end_date) && !empty($request->end_date))
                {
                    $alreadybookingFrom = Booking::where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('from_date', [$request->start_date, $request->end_date])->pluck('property_id')->toArray();
                    $bookings = Booking::where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('to_date', [$request->start_date, $request->end_date])->pluck('property_id')->toArray();
                    $q->whereNotIn('properties.id',$bookings)->whereNotIn('properties.id',$alreadybookingFrom);
                }else{
                      if(isset($request->start_date) && $request->end_date == null){
                        $bookings = Booking::whereDate('from_date',$request->start_date)->pluck('property_id')->toArray();
                        if(isset($bookings) && !empty($bookings)){
                            $q->whereNotIn('properties.id',$bookings);
                        }
                      }else{
                           if(isset($request->end_date) && $request->start_date == null){
                                $bookings = Booking::whereDate('from_date',$request->end_date)->pluck('property_id')->toArray();
                                if(isset($bookings) && !empty($bookings)){
                                    $q->whereNotIn('properties.id',$bookings);
                                }
                            } 
                      }
                }
                if(isset($request->search_city) && !empty($request->search_city))
                {
                    $q->where('property_address.city_id',$request->search_city);
                }
                if(isset($request->search_area) && !empty($request->search_area))
                {
                    $q->where('property_address.area',$request->search_area);
                }
                if(isset($request->search_accommodation) && !empty($request->search_accommodation))
                {
                    $q->where('properties.title', 'LIKE', '%' . $request->search_accommodation . '%');
                }
                if(isset($request->search_building) && !empty($request->search_building))
                {
                    $building_ids = Building::Where('name', 'LIKE', '%' . $request->search_building . '%')->pluck('id')->toArray();
                    $q->whereIn('properties.building',$building_ids);
                }
                if(isset($request->max_guest_capacity) && !empty($request->max_guest_capacity))
                {
                    $q->where('properties.max_guest','>=',$request->max_guest_capacity);
                }
                if(isset($request->search_bedroom) && !empty($request->search_bedroom))
                {
                    $q->where('property_bedrooms.no_of_bedrooms',$request->search_bedroom);
                }
                if(isset($request->search_category) && !empty($request->search_category))
                {
                    $q->where('properties.category',$request->search_category);
                }
                if(isset($request->search_type_list) && !empty($request->search_type_list))
                {
                    $q->orWhere('properties.type',$request->search_type_list);
                }
                
                if ($request->search && !empty($request->search)) {
                    $search = $request->search;
                    $q->where(function($query) use ($search) {
                        $query->where('properties.title', 'LIKE', '%' . $search . '%')
                              ->orWhere('properties.price', 'LIKE', '%' . $search . '%');
                    });
                }
                $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
                if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                    foreach ($results as $value) {
                        $addressDetails = PropertyAddress::where('property_id',$value->id)->first();
                        $city_id = isset($addressDetails) && !empty($addressDetails->city_id) ? $addressDetails->city_id : '';
                        $area = isset($addressDetails) && !empty($addressDetails->area) ? $addressDetails->area : '';
                        if(isset($city_id) && !empty($city_id)){
                            $city_name = City::where('id',$city_id)->pluck('name')->first();
                        }else{
                            $city_name = '';
                        }
                        if(isset($area) && !empty($area)){
                            $area_name = Area::where('id',$area)->pluck('name')->first();
                        }else{
                            $area_name = '';
                        }
                        $user_host = User::where('id',$value->host)->first();
                        $host_name = isset($user_host) ? $user_host->name : '';
                        $row['id'] = $value->id;
                        $row['title'] = isset($value->title)? $value->title:'N/A';
                        $new_address = isset($city_name) && !empty($area_name) ? $city_name.' ('.$area_name.')' : '';
                        $row['address'] = isset($new_address) && !empty($new_address) ? $new_address : $city_name;
                        $row['host'] = isset($host_name)? $host_name:'N/A';
                        $row['image'] = $value->image;              
                        $row['created_at'] = date('d M Y', strtotime($value->created_at));
                        $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                        $row['price'] = isset($value->price) && $value->price ? $value->price : 'N/A';
                       
                        $datas[] = $row;
                        $i++;
                        unset($u);
                    }

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking Search found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }
    public function becomeahostAllCityList(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $results = City::select('id','name')->where(['status'=>1])->get();
            if(isset($results) && !empty($results)){
                $response['status'] = true;
                $response['data'] = $results;
            }else{
                $response['status'] = false;
                $response['message'] = 'City Not found.';
            }
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostAllAreaList(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $results = Area::select('id','name')->where(['status'=>1])->get();
            if(isset($results) && !empty($results)){
                $response['status'] = true;
                $response['data'] = $results;
            }else{
                $response['status'] = false;
                $response['message'] = 'Area Not found.';
            }
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostAllCategoryList(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $results = Category::select('id','name')->where(['status'=>1])->get();
            if(isset($results) && !empty($results)){
                $response['status'] = true;
                $response['data'] = $results;
            }else{
                $response['status'] = false;
                $response['message'] = 'Category Not found.';
            }
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }


    public function becomeahostBookingSearchShow(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'id.required' => 'Property Id',
            ];
            $validator = Validator::make($input, [
                'id'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                $id = $request->id;
                $data = Property::with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding')->where('id',$id)->first();
                if(isset($data) && !empty($data->host)){
                    $host_name = User::where(['id'=>$data->host])->pluck('name')->first();
                    $building_name = Building::where(['id'=>$data->building])->pluck('name')->first();
                    $data->host_name = $host_name;
                    $data->building_name = $building_name;
                    $data->propertyImages = PropertyImage::where('property_id',$id)->get();
                    $data->ratings = Rating::where(['property_id'=>$id])->with('getUser')->get();

                    $response['status'] = true;
                    $response['data'] = $data;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahostStatusUpdate(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'id.required' => 'Id',
                'model_name.required' => 'Model',
                'key_name.required' => 'Key Name',
                'value_name.required' => 'Value Name',
            ];
            $validator = Validator::make($input, [
                'id'   => 'required|numeric',
                'model_name'   => 'required',
                'key_name'   => 'required',
                'value_name'   => 'required',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if ($request->key_name == 'position') {
                    if($property = Property::where('id',$request->id)->first())
                    {
                        if ($old_property = Property::where('position',$request->value_name)->first()) {
                            $old_property->position = $property->position;
                            $old_property->save(); 
                        }
                        Property::where('id',$request->id)->update(['position'=>$request->value_name]);
                    }else{
                        Property::where('id',$request->id)->update(['position'=>$request->value_name]);
                       
                    }
                }else{
                    $modelName = $request->model_name;
                    $keyName = $request->key_name;
                    $keyValue = $request->value_name;
                    $updateData = [$keyName => (string)$keyValue];

                    $model = app("App\\Models\\$modelName");
                    $record = $model->where('id', $request->id)->update($updateData);
                }
                
                $response['status'] = true;
                $response['data'] = 'Record updated successfully';
                /*if ($record) {
                    $record->update($updateData);
                    $response['status'] = true;
                    $response['data'] = 'Record updated successfully';
                } else {
                     $response['status'] = false;
                    $response['data'] = 'Record not found.';
                }*/
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
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

     public function getAllState(){        
        $categoryData = Province::all();

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
        $categoryData = ExtraService::where('status',1)->where('is_defalt', 'Yes')->get();

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
            $categoryData = Category::where('status',1)->where('type',$typename)->get();
            if (!empty($categoryData) && count($categoryData) > 0) {
                // code...
            }else{
                $categoryData = Category::where('status',1)->get();
            }
        }else{
            $categoryData = Category::where('status',1)->get();
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

        /*$property = Property::select('properties.*')
        ->where(['properties.status'=>1,'properties.featured'=>'No','properties.luxury'=>'No','properties.rare'=>'No'])
        ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb');*/


        if (isset($getAddressCountryIds) && !empty($getAddressCountryIds)) {
            /*$featuredPropertyWithPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->where('position', '<=', 15)->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit(15)->get();

            $remainFeaturedProperty = 15 - count($featuredPropertyWithPos);

            $featuredPropertyWithoutPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit($remainFeaturedProperty)->get();*/

            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });
            $recordData['rareProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.rare'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            // dd($recordData['featuredProperty']);
            $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))->groupBy('bookings.property_id')->orderBy('total_count', 'asc')->pluck('bookings.property_id')->toArray();

            $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
            if(!empty($getMostBookedIds))
            {
                //$property->whereNotIn('properties.host', $superHostIds);
                $recordData['superHostProperty'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->limit(10)->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")")->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });
            }
            else
            {
                $recordData['superHostProperty'] = Property::select('properties.*')
                ->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])
                ->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')
                ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
                ->limit(10)
                ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });
            }
            $recordData['rareProperty_book_now'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->limit(10)->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

        } else {
            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            $recordData['rareProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.rare'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

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
                ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
                ->limit(10)
                ->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")")
                ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });                
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
                ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')    
                ->limit(10)               
                ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });


            }
            $recordData['rareProperty_book_now'] = Property::select('properties.*')->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyCity','getPropertyBedroom','getPropertyImages','getUserWeb')->limit(10)->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });


        
        }
        //$recordData['count_all_properties'] = $property->count();

        //$recordData['property'] = $property->orderBy('id','desc')->limit(15 )->get();

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

        if(array_key_exists("category",$serachData) &&  isset($serachData['category']) && !empty($serachData['category'])){
            if (strpos($serachData['category'], ',')) { 
                $category = explode (",", $serachData['category']); 
                $category_ids = PropertyCategory::WhereIn("category_id",$category)->pluck('property_id')->toArray();
            }else{
			    $category_ids = PropertyCategory::Where("category_id",$serachData['category'])->pluck('property_id')->toArray();
            }
            $propertyList->whereIn('properties.id',$category_ids);
        }
        if(array_key_exists("services",$serachData) &&  isset($serachData['services']) && !empty($serachData['services'])){
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
        
        if(array_key_exists("type",$serachData) &&  isset($serachData['type']) &&   !empty($serachData['type'])){
			$type = $serachData['type'];
            if ($type != 'All') {
                $propertyList->Where("properties.type",'like','%'.$type.'%');
            }
			
        }
        if(array_key_exists("no_of_bedrooms",$serachData) &&  isset($serachData['no_of_bedrooms']) && !empty($serachData['no_of_bedrooms'])){
			$bedrooms = $serachData['no_of_bedrooms'];
			$propertyList->Where("property_bedrooms.no_of_bedrooms",$bedrooms);
        }
        if(array_key_exists("bathroom",$serachData)  &&  isset($serachData['bathroom']) && !empty($serachData['bathroom'])){
			$bathroom = $serachData['bathroom'];
			$propertyList->Where("property_bathrooms.bathroom_with_bathtub",$bathroom);
        }
        if(array_key_exists("code",$serachData) &&  isset($serachData['code']) &&  !empty($serachData['code'])){
			$code = $serachData['code'];
			$propertyList->Where("properties.code",$code);
        }
        if(array_key_exists("checkIn",$serachData) &&  isset($serachData['checkIn'])  && !empty($serachData['checkOut'])){
            $start_date = date('Y-m-d', strtotime($serachData['checkIn']));
            $end_date = date('Y-m-d', strtotime($serachData['checkOut']));
            $alreadybooking = Booking::whereDate('bookings.from_date', '>=', $start_date)->whereDate('bookings.to_date', '<=', $end_date)
            ->pluck('property_id')->toArray();
            $alreadyBlockedDate = PropertyBlockDate::whereBetween('block_date', [$start_date, $end_date])->pluck('property_id')->toArray();
            $book_properties_ids = array_unique(array_merge($alreadybooking, $alreadyBlockedDate));
            // dd($book_properties_ids);
            $propertyList->whereNotIn('properties.id',$book_properties_ids);
        }
        if(array_key_exists("building",$serachData) &&  isset($serachData['building'])  && !empty($serachData['building'])){
			$building = $serachData['building'];
			$propertyList->Where("properties.building",$building);
        }
        if(array_key_exists("max_guest",$serachData) &&  isset($serachData['max_guest']) && !empty($serachData['max_guest'])){
			$max_guest = $serachData['max_guest'];
			$propertyList->Where("properties.max_guest",'>=',$max_guest);
        }
        if(array_key_exists("is_super_host",$serachData) &&  isset($serachData['is_super_host']) && !empty($serachData['is_super_host'])){
            if($serachData['is_super_host'] == 'Yes'){
                $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
                $propertyList->whereIn('host', $superHostIds);
                // $propertyList->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")");
            }
        }
        if(array_key_exists("country",$serachData) &&  isset($serachData['country']) && !empty($serachData['country'])){
			$country = $serachData['country'];
            $country_id = Country::where('name','like','%'.$country.'%')->pluck('id')->toArray();

            $propertyList = $propertyList->where(function($query) use ($country_id){
                $query->whereIn('property_address.country_id', $country_id);
                // $query->whereIn('property_address.city_id', $city_id);
            });
			// $propertyList->WhereIn("property_address.city_id",$city_id);
        }
        if(array_key_exists("province",$serachData) &&  isset($serachData['province']) && !empty($serachData['province'])){
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
        if(array_key_exists("province_id",$serachData) &&  isset($serachData['province_id']) && !empty($serachData['province_id'])){
            $province = $serachData['province_id'];
            $propertyList = $propertyList->where(function($query) use ($province){
                $query->where('property_address.province_id', $province);
            });
        }
        if(array_key_exists("city",$serachData) &&  isset($serachData['city']) && !empty($serachData['city'])){
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
        if(array_key_exists("city_id",$serachData) &&  isset($serachData['city_id']) && !empty($serachData['city_id'])){
            $city_id = $serachData['city_id'];
            $propertyList = $propertyList->where(function($query) use ($city_id){
                $query->where('property_address.city_id', $city_id);
            });
        }
        if (isset($serachData['featured']) &&  isset($serachData['featured']) &&  !empty($serachData['featured']) ) {
            if (isset($serachData['featured']) && $serachData['featured'] == 'Yes') {
                $propertyList->where('properties.featured','Yes');
            }else{
                $propertyList->where(['properties.featured'=>'No']);
            }
        }
        if (isset($serachData['luxury']) &&  isset($serachData['luxury']) && !empty($serachData['luxury']) ) {
            if (isset($serachData['luxury']) && $serachData['luxury'] == 'Yes') {
                $propertyList->where('properties.luxury','Yes');
            }else{
                $propertyList->where(['properties.luxury'=>'No']);
            }
        }

        if (isset($serachData['rare'])  &&  isset($serachData['rare']) && !empty($serachData['rare']) ) {
            if (isset($serachData['rare']) && $serachData['rare'] == 'Yes') {
                $propertyList->where('properties.rare','Yes');
            }else{
                $propertyList->where(['properties.rare'=>'No']);
            }
        }

        if(array_key_exists("review",$serachData) &&  isset($serachData['review']) && !empty($serachData['review'])){
			$review = $serachData['review'];
            $propertyList->Where("properties.avg_rating",$review);
        }

      
        
           if(array_key_exists("rating",$serachData)  && isset($serachData['rating']) && !empty($serachData['rating'])){

            if($serachData['rating']==5)
            {
                $propertyList->where('avg_rating','>',4)->where('avg_rating','<=',5);
            }
            if($serachData['rating']==4)
            {
                $propertyList->where('avg_rating','>',3)->where('avg_rating','<=',4);
            }
            if($serachData['rating']==3)
            {
                $propertyList->where('avg_rating','>',2)->where('avg_rating','<=',3);
            }
            if($serachData['rating']==2)
            {
                $propertyList->where('avg_rating','>',1)->where('avg_rating','<=',2);
            }
            if($serachData['rating']==1)
            {
                $propertyList->where('avg_rating','>',0)->where('avg_rating','<=',1);
            }
            if($serachData['rating']==0)
            {
                $propertyList->where('avg_rating','<=',0)->orWhere('avg_rating',null);
            }
        }

        if(array_key_exists("search_text",$serachData)){
			$search = $serachData['search_text'];
			$propertyList->Where("properties.title",'like','%'.$search.'%');
        }
        if ($limit != 'All') {
            if ($is_pagination == 1) {
                $propertyDetail = $propertyList->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->paginate($limit)->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });
            } else {
                $propertyList->limit($limit);
                $propertyDetail = $propertyList->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->get()->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });
            }
        } else {
            $propertyDetail = $propertyList->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });
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
        //dd($input, 'input');
        $serachData = $request->all();
        $limit = 15;
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

             $range = 12;
        $start_range = (($range*1)-12);
        $end_range = $range*1;

        $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));
        $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
        $allDates = [];

        $alreadybooking = Booking::where('property_id', $serachData['propertyid'])->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
            ->pluck('from_date','to_date')->toArray();

        $alreadyBlockedDate = PropertyBlockDate::where('property_id', $serachData['propertyid'])->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();

        $mo = [];
        for($i=0; $i<12;$i++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
            $getThisMonth = date('m',strtotime($start_date_new));

            if ($alreadybooking) {
                foreach ($alreadybooking as $key => $value) {
                    $getstarMonth = date('m',strtotime($value));
                    $getendMonth = date('m',strtotime($key));
                    if(($getstarMonth == $getendMonth) && ($getThisMonth == $getstarMonth))
                    {
                        $period = CarbonPeriod::create($value, $key);
                        foreach ($period as $date) {
                            $mo[$date->format('Y-m-d')] = $date->format('Y-m-d');
                        }
                    }
                    else
                    {
                        $period = CarbonPeriod::create($value, $key);
                        foreach ($period as $date) {
                            if($getThisMonth == $date->format('m'))
                            {
                                $mo[$date->format('Y-m-d')] = $date->format('Y-m-d');
                            }
                        }
                    }
                }
                // $mo = array_unique($mo);
            }

            if ($alreadyBlockedDate) {
                foreach ($alreadyBlockedDate as $k => $v) {
                    $getBlockstarMonth = date('m',strtotime($v));
                    $getblockEndMonth = date('m',strtotime($k));
                    $date = date('Y-m-d', strtotime($v));

                    if (($getBlockstarMonth) && ($getThisMonth == $getBlockstarMonth)) {
                        $mo[$date] = $date;
                    } else {
                        if ($getThisMonth == $getBlockstarMonth) {
                            $mo[$date] = $date;
                        }
                    }
                }
                // $mo[] = array_unique($mo);
            }
            // $mo[] = $allDates;
        }


            $propertyList = Property::select('properties.*','users.name as host_name')->where(['properties.status'=>1])->with('getHostDetails','getPropertyAddress.getPropertyCity','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyCountry','getPropertyCategory.getCategoryData','getAmenities.getAmenityData','getExtraService.getServiceData','getPropertyBedroom','getProPropertyBathroom','getPropertyBedding','getPropertyKitchen','getPropertyImages','getPropertyRating.getUser')->leftjoin('users','users.id','=','properties.host')->where('properties.id',$serachData['propertyid'])->first();
            if(isset($propertyList) && !empty($propertyList)){
                $propertyList->loyalty_points = $final_loyalty_points;
                $propertyList->points_amount = $points_amount;
                
                $propertyList->blockDates = json_encode($mo);
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
                        /* if ($locale == 'en') {
                            $langType = 'N';
                        } else {
                          $langType = 'LNG';
                        }
                         $username = 'Tahadiyaat';
                         $sender_id = 'TAHADIYATAE';
                         $password = 'Tahadiyaat01$';

                         $curlUrlNew = "http://api.smscountry.com/SMSCwebservice_bulk.aspx?mobilenumber=" . $input['country_code'] . $input['mobile'] . "&message=" . urlencode($message) . "&User=" . $username . "&passwd=" . $password . "&sid=" . $sender_id . "&mtype=" . $langType . "&DR=Y";
                        // dd($curlUrlNew);

                         $curl = curl_init();
                         curl_setopt_array($curl, array(
                             CURLOPT_URL => $curlUrlNew,
                             CURLOPT_RETURNTRANSFER => true,
                             CURLOPT_ENCODING => "",
                             CURLOPT_MAXREDIRS => 10,
                             CURLOPT_TIMEOUT => 30,
                             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                             CURLOPT_CUSTOMREQUEST => "GET",
                             CURLOPT_HTTPHEADER => array(
                                 "cache-control: no-cache",
                                 "postman-token: 7e81e559-a81b-d18c-a629-908156cda911"
                             ),
                         ));
                         $response = curl_exec($curl);
                         $err = curl_error($curl);
                         // echo "<pre>";print_r($response);
                         // echo "<pre>";print_r($err);die;
                         curl_close($curl); */
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
                        $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                /*$check = Appointment::where(['schedule_time'=>$input['schedule_time'], 'appointment_date'=>$appointment_date, 'province_id' => $input['province_id'] ])->first();*/
                $check = Appointment::where(['schedule_time'=>$input['schedule_time'], 'appointment_date'=>$appointment_date])->first();

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
                        $email = EmailTemplateLang::where('email_id', 39)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                        $subject = $email->subject;
                        $subject = str_replace("[NAME]", $input['full_name'], $email->subject);
                        $description = $email->description;
                        $description = str_replace("[NAME]", $input['full_name'].' '.$input['surname'] , $description);
                        $description = str_replace("[DATE]", $input['appointment_date'], $description);
                        $description = str_replace("[TIME]", $input['schedule_time'], $description);
                        $description = str_replace("[LOCATION]", $input['address'], $description);
                
                
                        $register_detail=(object)[];
                        $register_detail->name = str_replace("[NAME]", $input['full_name'], $email->name);
                        $register_detail->subject = $subject;
                        $register_detail->description = $description;
                        $register_detail->footer = isset($email->footer) ? $email->footer : 'Copyright Â© 2022 Shortlet. All rights reserved.';
                        $user = $input;
                
                        Mail::send('emails.register', compact('register_detail'), function($message)use($user, $email, $subject) {
                            $message->to($user['email'], config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com',config('app.name'));
                        });
               

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
                        $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                $q->where(['country_code' => $input['country_code'], 'mobile' => $input['mobile'], 'user_type' => 4 ]);
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
                        $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                    $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();
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
                            $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                                $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                                $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                                $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                                $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                $user->image_type = 'url';
                $user->password = Hash::make($input['social_id']);
                $user->image = $input['image']??null;
                $user->save();
                if ($user->status == 1 && empty($user->deleted_at)) {
                    $credentials = ['email' => $input['email'], 'password' => $input['social_id']];
                    if (Hash::check($input['social_id'], $user->password)) {
                        if (!$token = JWTAuth::attempt($credentials)) {
                            $response['status'] = false;
                            $response['message'] = __("api.invalid_user_login");
                            return response()->json($response, 200);
                        }
                        if (isset($input['device_token'])) {
                            $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
                $user->image_type = 'url';
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
                            $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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
        //dd($request->all());
        $user = JWTAuth::user();
        // dd($user);
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

        if(isset($requset->email)){
             $message = [
            'email.required' => 'Email is required.',
        ];
        $validator = Validator::make($input, [ 
            'email'   => 'required',
        ],$message);
        if ($validator->fails()) 
        {
            $errors     =   $validator->errors();
            $response['status'] = 0;
            $response['message'] = $errors;
            return response()->json($response, 200);
        }
        else{

             $otps = $this->generateRandomString('N',4);
            $message = "Your OTP Code is ".$otps;
            $message = str_replace(' ','%20', $message);
              
            if($user = User::select('users.id','users.name','users.surname','users.country_code','users.mobile','users.email')->where(['email'=>$input['email']])->first())
            {
                //dd($user);
                $otp = UserOtp::where(['country_code'=>$user->country_code,'mobile'=>$user->mobile])->first();

                if(!isset($otp))
                {
                    $otp = new UserOtp; 
                    $otp->country_code = $user->country_code;
                    $otp->mobile = $user->mobile;                        
                }
                $otp->otp = $otps;
                $otp->save(); 

                $user['name'] = $user->name;
                $user['email'] = $user->email;
                
                $this->setOtpMail($user,$otps);

                $response['status'] = 1;
                $response['message'] = 'OTP sent successfully.';
                $response['data'] = ['otp' => $otps,
                                     'user' => $user,
                                    ];
                                                  
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
        else{
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
                  
                if($user = User::where(['country_code'=>$input['country_code'],'mobile'=>$input['mobile']])->first())
                {
                    //dd($user);
                    $otp = UserOtp::where(['country_code'=>$input['country_code'],'mobile'=>$input['mobile']])->first();

                    if(!isset($otp))
                    {
                        $otp = new UserOtp; 
                        $otp->country_code = $input['country_code'];
                        $otp->mobile = $input['mobile'];                        
                    }
                    $otp->otp = $otps;
                    $otp->save(); 

                    $user['name'] = $user->name;
                    $user['email'] = $user->email;
                    
                    $this->setOtpMail($user,$otps);

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


     //apis for Admin 


   

    public function admin_userProfile()
    {
        $userId = JWTAuth::user();
        // dd($userId);
      
        $user = User::where('id',$userId->id)->first();     
        if ($user) {

            $data =  new UserResource($user);
            // dd($user->id);
            // $debit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Debit'])->sum('points');
            // if(isset($debit_loyalty_points) && !empty($debit_loyalty_points) ){
            //     $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
            //     $total_loyalty_points = $credit_loyalty_points - $debit_loyalty_points;
            // }else{
            //     $credit_loyalty_points = UserLoyaltyPoint::where(['user_id'=>$user->id, 'type'=>'Credit'])->sum('points');
            //     $total_loyalty_points = $credit_loyalty_points;
            // }
            // $loyalty_points = UserLoyaltyPoint::where('user_id',$user->id)->orderBy('id','desc')->get();
            // dd($data);
            $response['status'] = true;
            $response['data'] = $data;

            // $response['loyalty_points'] = $loyalty_points;
            // $response['total_loyalty_points'] = $total_loyalty_points;
            return response()->json($response, 200);
        } else {
            $response['status'] = false;
            $response['message'] = __("api.something_worng");
            return response()->json($response, 200);
        }
        return response()->json();
    }

     public function admin_updateProfile(Request $request)
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
            // 'gender'   => 'required',
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
                $folderPath =   'user/'.$newFolder; 
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

    public function becomeahost_changePassword(Request $request)
    {
        // $user = JWTAuth::user();
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'id.required' => __("api.id_required"),
            'old_password.required' => __("api.old_password_required"),
            'new_password.required' => __("api.new_password_required"),
            'confirm_password.required' => __("api.confirm_password_required"),
            'new_password.min' => __("api.new_password_min"),
            'confirm_password.min' => __("api.confirm_password_min"),
            'old_password.min' => __("api.old_password_min"),
        ];
        $validator = Validator::make($input, [
            'id'   => 'required',
            'old_password'   => 'required',
            'new_password'   => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {

            $user = User::where(['id'=>$input['id'], 'user_type' => 5])->first();
            // dd($user);
            if (isset($user)) {

                if (Hash::check($input['old_password'], $user->password)) {
                    $user->password = Hash::make($input['new_password']);

                    if ($user->save()) {
                        $updatedUser = User::find($user->id);
                        $response['status'] = true;
                        $response['message'] = __("api.password_change_success");
                        $response['user'] = [
                            'name' => $updatedUser->name,
                            'email' => $updatedUser->email,
                            'image' => $updatedUser->image,
                
                        ];
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

    

    //becomeahost apis

    public function becomeahost_login(Request $request)
    {
        // dd($request->all());
        $this->code = 200;
        $input =  $request->all();
        $this->requestdata = $input;
        $message = [
            'email.required' => __("api.email_required"),
            'password.required' => __("api.password_required"),
        ];
        $validator = Validator::make($input, [
            // 'country_code'   => 'required',
            // 'mobile' => 'required|min:7|max:15',
            // 'password'   => 'required',
        ], $message);
        if ($validator->fails()) {
            $this->errorValidation($validator);
        } else {
            
                $user = User::where(['email' => $input['email'] ])
                ->where(function ($query) {
                    $query->where('user_type','=',5);
                })->first();

                if (isset($user)) {
                    if ($user->status == 1 && empty($user->deleted_at)) {
                        $credentials = ['email' => $input['email'], 'password' => $input['password']];
                        if (Hash::check($input['password'], $user->password)) {
                            if (!$token = JWTAuth::attempt($credentials)) {
                                // dd($token);
                                $response['status'] = false;
                                $response['message'] = __("api.invalid_user_login");
                                return response()->json($response, 200);
                            }
                            if (isset($input['device_token'])) {
                                $data = UserDevice::where(['user_id' => $user->id])->where('device_type', $input['device_type'])->first();

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

                            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                            $response['status'] = true;
                            $response['token'] = $token;
                            $response['data'] = new UserResource($user);
                            $response['permission'] = $permission;
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

    public function becomeahost_userdetails(Request $request)
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
            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            if(isset($userData) && !empty($userData)){
                $response['status'] = true;
                $response['data'] = $userData;
                $response['permission'] = $permission;


            }else{
                $response['status'] = false;
                $response['message'] = 'User not found.';
            }
            return response()->json($response, 200);
        }
        // return $this->jsonResponse();
        return response()->json($response, 200);
    }

    public function becomeahost_logout(Request $request)
    {
         $input =  $request->all();
        $this->requestdata = $input;
        
        $user = User::where(['id'=>$input['user_id'], 'user_type' => 5])->first();
        // dd($user);
        
        $user->logout();

        $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

        $response['status'] = true;
        $response['message'] = __("api.user_logout_message");
        $response['permission'] = $permission;
        return response()->json($response, 200);
    }

     public function becomeahost_update_user(Request $request)
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
                    $folderPath =   'user/'.$newFolder; 
                    $result =  fileUploads('s3',$document_file,$folderPath,false);

                    User::where(['id' => $input['user_id'], 'user_type' => 5])->update(['document_image' => $result['file']]);
                }
                if(isset($file)){
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath =   'user/'.$newFolder; 
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

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'User updated successfully.';
                $response['permission'] = $permission;

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


   

    public function becomeahosttransactionShow(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }  
             // $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    
      $q = Transection::select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('host_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
  
            if(isset($request->orderby) && $request->orderby == 'stay'){
              $orderby = 'bookings.from_date';
            }elseif(isset($request->orderby) && $request->orderby == 'book'){
              $orderby = 'property.title';
            }else{
              // dd($orderby);
              $orderby = $request->orderby ? $request->orderby : 'bookings.created_at';
            }
            $orderby = $orderby;
            $order = $request->order ? $request->order : 'desc';

            if(isset($request->booking_status) && in_array($request->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
            {
              $q->where('bookings.booking_status',$request->booking_status);
            }

           
            if(isset($request->host) && !empty($request->host))
            {
              if (!in_array("All", $request->host)) {
                $q->whereIn('properties.host',$request->host);
              }
            }
            $date_of = 'created_at';
            if(isset($date_of) && isset($date_of)){

              if(isset($request->start_date) && isset($request->end_date))
              {
                $q->whereDate('bookings.'.$date_of,'>=',$request->start_date)->whereDate('bookings.'.$date_of,'<=',$request->end_date);
              }
            }
            else{
              if(isset($request->start_date) && isset($request->end_date))
              {
                $q->whereBetween('bookings.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
              }else{
                if(isset($request->start_date) && $request->end_date == null){
                  $q->whereDate('bookings.created_at',$request->start_date);    
                }
              }
            }

            if(isset($request->search_type_list) && !empty($request->search_type_list))
            {
              if (!in_array("All", $request->search_type_list)) {
                $q->whereIn('properties.type',$request->search_type_list);
              }
            }
            if(isset($request->search_category))
            {
              if (!in_array("All", $request->search_category)) {
                $getPropertyByCat = PropertyCategory::whereIn('category_id', $request->search_category)->groupBy('property_id')->pluck('property_id')->toArray();
                $q->whereIn('properties.id',$getPropertyByCat);
              }
            }

            if(isset($request->search_country) && !empty($request->search_country))
            { 
              $q->where('property_address.country_id',$request->search_country);
            }
            if(isset($request->search_province))
            {
              $q->where('property_address.province_id',$request->search_province);
            }
            if(isset($request->search_city) && !empty($request->search_city))
            { 
              $q->where('property_address.city_id',$request->search_city);
            }
            if(isset($request->search_area))
            {
              $q->where('property_address.area',$request->search_area);
            }
            
            if ($request->search && !empty($request->search)) {
              // dd($search);
              $q->where(function($query) use ($search) {
                $query->where('bookings.booking_id', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.email', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.mobile', 'LIKE', '%' . $search . '%');
              });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
                        
                
                // $results = $q->where('bookings.id', $request->id)->first();
               if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;

               foreach ($results as $value) {

                    $extraservice = '';
                    if(isset($value->selected_options))
                    {
                        $extraservice1 = json_decode($value->selected_options);
                        foreach($extraservice1 as $v1)
                        {
                            $extraservice =  $extraservice.$v1->name.',';
                        }
                    }
                    // dd($value->coupon_code);
                    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                    $guestData = User::where('id',$value->guest_id)->first();
                    $hostData = User::where('id',$value->host_id)->first();
                    $propertyData = Property::where('id',$value->property_id)->first();
                    // echo '<br>'; print_r($value->property_id);
                    $propertyCategory = PropertyCategory::with('getCategoryData')->where('property_id',$value->property_id)->get();
                    $category = '';
                    if(isset($propertyCategory) && count($propertyCategory) > 0){
                        if(count($propertyCategory) > 1){
                            foreach($propertyCategory as $cat){
                                $categoryData = Category::where('id',$cat->category_id)->first();
                                $category .= $categoryData->name.',';
                            }
                            $category = rtrim($category, ',');
                        }else{
                            if(isset($propertyCategory->category_id) && !empty($propertyCategory->category_id)){
                                $categoryData = Category::where('id',$propertyCategory->category_id)->first();
                                $category = $categoryData->name;
                            }
                        }
                    }
                    // dd($category);
                    $propertyAddress = PropertyAddress::where('property_id',$value->property_id)->first();
                    if(isset($propertyAddress->city_id) && !empty($propertyAddress->city_id)){
                        $cityName = City::where('id',$propertyAddress->city_id)->pluck('name')->first();
                    }else{
                        $cityName = '';
                    }
                    if(isset($propertyAddress->area) && !empty($propertyAddress->area)){
                        $areaName = Area::where('id',$propertyAddress->area)->pluck('name')->first();
                    }else{
                        $areaName = '';
                    }
                    if(isset($cityName) && !empty($areaName)){
                        $location = $cityName.'('.$areaName.')';
                    }else{
                        $location = $cityName;
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['reference'] = isset($value->reference)? $value->reference:'N/A';
                    // dd($hostData->name.' - '.$hostData->country_code.' - '.$hostData->mobile);
                    if(isset($guestData) && !empty($guestData)){
                        $row['guestData'] = $guestData->name.' ('.'+'.$guestData->country_code.' - '.$guestData->mobile.')';
                    }else{
                        $row['guestData'] = 'N/A';
                    }
                    if(isset($hostData) && !empty($hostData)){
                        $row['hostData'] = $hostData->name.' ('.'+'.$hostData->country_code.' - '.$hostData->mobile.')';
                    }else{
                        $row['hostData'] = 'N/A';
                    }
                    $row['host_amount'] = $value->host_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'0';
                    $row['type'] = isset($propertyData->type) ? str_replace('-',' ',$propertyData->type):'N/A';
                    $row['category'] = isset($category) ? $category : 'N/A';
                    $row['no_of_adult_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest + $value->no_of_pet;
                    $row['location'] = $location;
                    if(isset($value->discount_amount) && !empty($value->discount_amount)){
                        $row['coupon_code'] = $value->coupon_code. ' - '.$value->discount_amount;
                    }else{
                        $row['coupon_code'] = isset($value->coupon_code)? $value->coupon_code:'-';
                    }
                    if($value->sattlement == 'Done'){
                        $row['sattlement'] = 'Done';
                    }else{
                        if($user_type == 1){
                            $row['sattlement'] = sattlementStatus($value->sattlement, $value->id,['Done'=>'Done','Not_done'=>'Not done'],'sattlement_status',$this->page.'.sattlementStatus')->toHtml();
                        }else{
                            $row['sattlement'] = 'Not Done';
                        }
                    }
                    // $row['sattlement'] = isset($value->sattlement)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['status'] = isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));

                    $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Transection search found.';
                }
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
        }

    public function becomeahostsattlementShow(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            } else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }  
             // $user_type = User::where('id',auth()->id())->pluck('user_type')->first();

              $q = Sattlement::select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','properties.code','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('host_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->where('bookings.sattlement','Done');
             $q->where('host',auth()->id());
            // dd($orderby);
            if(isset($request->orderby) && $request->orderby == 'stay'){
              $orderby = 'bookings.from_date';
            }elseif(isset($request->orderby) && $request->orderby == 'book'){
              $orderby = 'property.title';
            }else{
              $orderby = 'bookings.to_date';
            }
            $orderby = $orderby;
            $order = $request->order ? $request->order : 'desc';
            // if(isset($status) && in_array($status,[0,1,2]))
            // if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
            // {
            //   $q->where('bookings.booking_type',$booking_type);
            // }
            if(isset($request->booking_status) && in_array($request->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
            {
              $q->where('bookings.booking_status',$request->booking_status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
              $q->whereBetween('bookings.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }else{
              if(isset($request->start_date) && $request->end_date == null){
                $q->whereDate('bookings.created_at',$request->start_date);    
              }
            }
            if(isset($request->host) && !empty($request->host))
            {
              if (!in_array("All", $request->host)) {
                $q->whereIn('properties.host',$request->host);
              }
            }

            if(isset($request->search_type_list) && !empty($request->search_type_list))
            {
              if (!in_array("All", $request->search_type_list)) {
                $q->whereIn('properties.type',$request->search_type_list);
              }
            }
            if(isset($request->search_category))
            {
              if (!in_array("All", $request->search_category)) {
                $getPropertyByCat = PropertyCategory::whereIn('category_id', $request->search_category)->groupBy('property_id')->pluck('property_id')->toArray();
                $q->whereIn('properties.id',$getPropertyByCat);
              }
            }

            if(isset($request->search_country) && !empty($request->search_country))
            { 
              $q->where('property_address.country_id',$request->search_country);
            }
            if(isset($request->search_province))
            {
              $q->where('property_address.province_id',$request->search_province);
            }
            if(isset($request->search_city) && !empty($request->search_city))
            { 
              $q->where('property_address.city_id',$request->search_city);
            }
            if(isset($request->search_area))
            {
              $q->where('property_address.area',$request->search_area);
            }
            
            if ($request->search && !empty($request->search)) {
              // dd($search);
              $q->where(function($query) use ($search) {
                $query->where('bookings.booking_id', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.email', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.mobile', 'LIKE', '%' . $search . '%');
                // $query->where('bookings.booking_type', 'LIKE', '%' . $search . '%');
                // $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
              });
            }
            // $response = $q->orderBy($orderby, $order);
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
                        
                
                // $results = $q->where('bookings.id', $request->id)->first();
               if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                    $extraservice = '';
                 foreach ($results as $value) {
                    if(isset($value->selected_options))
                    {
                        $extraservice1 = json_decode($value->selected_options);
                        foreach($extraservice1 as $v1)
                        {
                            $extraservice =  $extraservice.$v1->name.',';
                        }
                    }

                    // dd($value);
                    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                    $guestData = User::where('id',$value->guest_id)->first();
                    $hostData = User::where('id',$value->host_id)->first();
                    $propertyData = Property::where('id',$value->property_id)->first();
                    // echo '<br>'; print_r($value->property_id);
                    $propertyCategory = PropertyCategory::with('getCategoryData')->where('property_id',$value->property_id)->get();
                    $category = '';
                    if(isset($propertyCategory) && count($propertyCategory) > 0){
                        if(count($propertyCategory) > 1){
                            foreach($propertyCategory as $cat){
                                $categoryData = Category::where('id',$cat->category_id)->first();
                                $category .= $categoryData->name.',';
                            }
                            $category = rtrim($category, ',');
                        }else{
                            if(isset($propertyCategory->category_id) && !empty($propertyCategory->category_id)){
                                $categoryData = Category::where('id',$propertyCategory->category_id)->first();
                                $category = $categoryData->name;
                            }
                        }
                    }
                    // dd($category);
                    $propertyAddress = PropertyAddress::where('property_id',$value->property_id)->first();
                    if(isset($propertyAddress->city_id) && !empty($propertyAddress->city_id)){
                        $cityName = City::where('id',$propertyAddress->city_id)->pluck('name')->first();
                    }else{
                        $cityName = '';
                    }
                    if(isset($propertyAddress->area) && !empty($propertyAddress->area)){
                        $areaName = Area::where('id',$propertyAddress->area)->pluck('name')->first();
                    }else{
                        $areaName = '';
                    }
                    if(isset($cityName) && !empty($areaName)){
                        $location = $cityName.'('.$areaName.')';
                    }else{
                        $location = $cityName;
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['code'] = isset($value->code)? $value->code:'N/A';
                    $row['reference'] = isset($value->reference)? $value->reference:'N/A';
                    // dd($hostData->name.' - '.$hostData->country_code.' - '.$hostData->mobile);
                    if(isset($guestData) && !empty($guestData)){
                        $row['guestData'] = $guestData->name.' ('.'+'.$guestData->country_code.' - '.$guestData->mobile.')';
                    }else{
                        $row['guestData'] = 'N/A';
                    }
                    if(isset($hostData) && !empty($hostData)){
                        $row['hostData'] = $hostData->name.' ('.'+'.$hostData->country_code.' - '.$hostData->mobile.')';
                    }else{
                        $row['hostData'] = 'N/A';
                    }
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'0';
                    $row['type'] = isset($propertyData->type) ? str_replace('-',' ',$propertyData->type):'N/A';
                    $row['category'] = isset($category) ? $category : 'N/A';
                    $row['total_days'] = isset($value->total_days) && !empty($value->total_days) ? $value->total_days : '';
                    $row['no_of_adult_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest + $value->no_of_pet;
                    $row['location'] = $location;
                    if(isset($value->discount_amount) && !empty($value->discount_amount)){
                        $row['coupon_code'] = $value->coupon_code. ' - '.$value->discount_amount;
                    }else{
                        $row['coupon_code'] = isset($value->coupon_code)? $value->coupon_code:'-';
                    }
                    if($value->sattlement == 'Done'){
                        $row['sattlement'] = 'Done';
                    }else{
                        if($user_type == 1){
                            $row['sattlement'] = sattlementStatus($value->sattlement, $value->id,['Done'=>'Done','Not_done'=>'Not done'],'sattlement_status',$this->page.'.sattlementStatus')->toHtml();
                        }else{
                            $row['sattlement'] = 'Not Done';
                        }
                    }
                    // $row['sattlement'] = isset($value->sattlement)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['status'] = isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['sattlement_date'] = date('d M Y', strtotime($value->sattlement_date));
                
                    $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                    // $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                   
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking search found.';
                }
                return response()->json($response, 200);
            }

             
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function getAllDiscount(){        
        $categoryData = Discount::where('status',1)->get();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

    public function getAllAccomodation(){        
        $categoryData = Property::select('properties.title')->where('status',1)->get();

        if (!$categoryData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);
        } else {
            $response['status'] = true;
            $response['data'] = $categoryData;
            return response()->json($response, 200);
        }
    }

     public function becomeahostAlloffersList(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = Offer::select('offers.*','discounts.code')->leftjoin('discounts','discounts.id','=','offers.discount');

            $orderby = $request->orderby ? $request->orderby : 'offers.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('offers.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('offers.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }
           
            if ($request->search && !empty($request->search)) {
                $q->where(function($query) use ($search) {
                    $query->where('discounts.code', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();

            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    if(isset($value) && $value->all_properties == 'No'){
                        $offerAccommodation = OfferAccommodation::where('offer_id',$value->id)->pluck('property_id')->toArray();
                        $all_accommodation = Property::select('title')->whereIn('id', $offerAccommodation)->get();
                        $accommodation = "";
                        foreach ($all_accommodation as $ac_value) {
                            $accommodation != "" && $accommodation .= ", ";
                            $accommodation .= $ac_value->title;
                        }                    
                    }else{
                        $accommodation = 'All';
                    }
                    if(isset($value) && $value->discount != Null){
                        $discount_code = Discount::select('code')->where('id', $value->discount)->pluck('code')->first();
                    }else{
                        $discount_code = '-';
                    }
                    $row['id'] = $value->id;
                    $row['accomodation'] = $accommodation;
                    $row['discount'] = $discount_code;
                    $row['image'] = $value->image;              
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                     $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';


                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahost_addOffer(Request $request){

        $input = $request->all();
        // $userData = auth()->user();
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            // 'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $data = new Offer();
            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath =   'offer/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                
                $data->image = $result['file'];
            }
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                $data->all_properties = 'Yes';
            }else{
                $data->all_properties = 'No';
            }
            $data->discount = $input['discount'];
            $data->title = $input['title'] ?? null;
            $data->description = $input['description'] ?? null;
            $data->status = 1;
            if($data->save()){
                if(isset($input['property_id']) && !empty($input['property_id'])){
                    
                    foreach($input['property_id'] as $property){
                        $property_name = Property::where('id',$property)->pluck('title')->first();
                        $data_service = new OfferAccommodation;
                        $data_service->offer_id = $data->id;
                        $data_service->property_id = $property;
                        $data_service->property_name = $property_name;
                        $data_service->save();
                    }
                }
            }

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Offer added successfully.';
                $response['permission'] = $permission;
                 $response['notification'] =Notification::where(['user_id' => $userId, 'is_read' => 0])->count();
                return response()->json($response, 200);
            
        }
    }

    public function becomeahost_edit_view_Offer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = Offer::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Offer not found.';
                    return response()->json($response, 200);
                }
                $properties = Property::where('status',1)->get();
                $selected_offer_properties = OfferAccommodation::where('offer_id',$id)->pluck('property_id')->toArray();
                $discount = Discount::where('status',1)->get();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = [
                'offer' => $offer,
                'properties' => $properties,
                'selected_offer_properties' => $selected_offer_properties,
                'discount' => $discount,

                ];
                 $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
    public function becomeahost_editOffer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = Offer::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Offer not found.';
                    return response()->json($response, 200);
                }

                $offer->title = $input['title'] ?? null;
                $offer->description = $input['description'] ?? null;
                $offer->discount = $input['discount'];
                $offer->all_properties = isset($input['all_properties']) && $input['all_properties'] == 'Yes' ? 'Yes' : 'No';
                
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $newFolder = strtoupper(date('M') . date('Y'));
                    $folderPath = 'offer/' . $newFolder;
                    $result = fileUploads('s3', $file, $folderPath, false);
                    $offer->image = $result['file'];
                }

                $offer->save();

                if (isset($input['property_id']) && !isset($input['all_properties'])) {
                    OfferAccommodation::where('offer_id', $id)->delete();
                    foreach ($input['property_id'] as $property) {
                        $property_name = Property::where('id', $property)->pluck('title')->first();
                        $data_service = new OfferAccommodation;
                        $data_service->offer_id = $id;
                        $data_service->property_id = $property;
                        $data_service->property_name = $property_name;
                        $data_service->save();
                    }
                } else {
                    OfferAccommodation::where('offer_id', $id)->delete();
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Offer updated successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_deleteOffer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = Offer::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Offer not found.';
                    return response()->json($response, 200);
                }

                $offer->delete();

                OfferAccommodation::where('offer_id', $id)->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Offer deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewOffer(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                
                $offer = Offer::where('id',$id)->first();

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Offer not found.';
                    return response()->json($response, 200);
                }

                $selected_offer_properties = OfferAccommodation::where('offer_id',$id)->pluck('property_id')->toArray();
                if(isset($selected_offer_properties) && count($selected_offer_properties) > 0){
                    $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->whereIn('properties.id',$selected_offer_properties)->get();
                }else{
                    $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->get();
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Offer view successfully.';
                $response['data'] =  [
                'offer' => $offer,
                'accommodations' => $accommodations,
                ];
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_listBuilding(Request $request){

       try {
            $input =  $request->all();
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = Building::select('building.*');

            $orderby = $request->orderby ? $request->orderby : 'building.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('building.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('building.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }
           
            if ($request->search && !empty($request->search)) {
                 $q->where(function($query) use ($search) {
                    $query->where('building.name', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();

            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                 foreach ($results as $value) {
                    $row['id'] = $value->id;
                    $row['name'] = isset($value->name)? $value->name.' '.$value->surname:'N/A';
                  
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Building Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahost_addBuilding(Request $request){

        $input = $request->all();
        // $userData = auth()->user();
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
             'name' => 'required|max:190|unique:building',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
             $data = new Building;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath =   'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Building added successfully.';
                $response['permission'] = $permission;
                $response['notification'] =Notification::where(['user_id' => $userId, 'is_read' => 0])->count();
                return response()->json($response, 200);
            
        }
    }

    public function becomeahost_edit_view_Building(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = Building::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Building not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $offer;
                $response['data'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
    public function becomeahost_editBuilding(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $building = Building::find($id);
    
                if (!$building) {
                    $response['status'] = false;
                    $response['message'] = 'Building not found.';
                    return response()->json($response, 200);
                }

                $building->name = $input['name'] ?? null;
                $building->status = 1;

                        
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $newFolder = strtoupper(date('M') . date('Y'));
                    $folderPath = 'amenity/' . $newFolder;
                    $result = fileUploads('s3', $file, $folderPath, false);
                    $building->image = $result['file'];
                }

                $building->save();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Building updated successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_deleteBuilding(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $building = Building::find($id);

                if (!$building) {
                    $response['status'] = false;
                    $response['message'] = 'Building not found.';
                    return response()->json($response, 200);
                }

                $building->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Building deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewBuilding(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = Building::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'Building not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Building view successfully.';
                $response['data'] = $offer;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_listExtraService(Request $request){

       try {
            $input =  $request->all();
            $userData = User::where(['user_type' => 5])->first();
            $userId =  $userData->id;

            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = ExtraService::select('extra_services.*');

            $orderby = $request->orderby ? $request->orderby : 'extra_services.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('extra_services.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('extra_services.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }
           
            if ($request->search && !empty($request->search)) {
                 $q->where(function($query) use ($search) {
                    $query->where('extra_services.name', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    $row['id'] = $value->id;
                    $row['name'] = isset($value->name)? $value->name:'N/A';
                    $row['price'] = isset($value->price)? $value->price:'N/A';
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    $row['is_defalt'] = isset($value->is_defalt) && $value->is_defalt == 1 ? 'Yes' : 'No';
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                    $response['notification'] =Notification::where(['user_id' => $userId, 'is_read' => 0])->count();
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Extra Service Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahost_addExtraService(Request $request){

        $input = $request->all();
        // $userData = auth()->user();
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
             'name' => 'required|max:190|unique:extra_services',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
             $data = new ExtraService;
            $data->name = $input['name'];
            $data->price = $input['price']??null;
            $data->user_id = Auth::user()->id;
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Extra Service added successfully.';
                $response['permission'] = $permission;
                 $response['notification'] =Notification::where(['user_id' => $userId, 'is_read' => 0])->count();
                return response()->json($response, 200);
            
        }
    }

    public function becomeahost_edit_view_ExtraService(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = ExtraService::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Extra Service not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
     public function becomeahost_editExtraService(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = ExtraService::find($id);
    
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Extra Service not found.';
                    return response()->json($response, 200);
                }

                $data->name = $input['name'] ?? null;
                $data->price = $input['price'] ?? null;;
                $data->save();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Extra Service updated successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_deleteExtraService(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = ExtraService::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Extra Service not found.';
                    return response()->json($response, 200);
                }

                $data->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Extra Service deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewExtraService(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $offer = ExtraService::find($id);

                if (!$offer) {
                    $response['status'] = false;
                    $response['message'] = 'ExtraService not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'ExtraService view successfully.';
                $response['data'] = $offer;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


     public function becomeahost_listContent(Request $request){

       try {
            $input =  $request->all();
            $this->requestdata = $input;
             $message = [
                // 'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                // 'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {

            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    
            if($user_type == 5){
              $slug = ['about-us','term-of-use','privacy-policy'];
              $q = Content::select('contents.*')->whereIn('slug',$slug);
            }else{
              $q = Content::select('contents.*');
            }

            $orderby = $request->orderby ? $request->orderby : 'contents.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('contents.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('contents.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }
           
            if ($request->search && !empty($request->search)) {
                 $q->where(function($query) use ($search) {
                    $query->where('contents.name', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->get();
            

            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    $row['id'] = $value->id;
                    $row['name'] = isset($value->name)? $value->name:'N/A';
                    if(isset($value->description) && str_word_count($value->description) > 100){
                        $description = substr(strip_tags($value->description), 0, 100) . '...';
                    }else{
                        $description = strip_tags($value->description);
                    }
                    $row['description'] = isset($description) ? $description :'N/A';
                    // $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                   
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Extra Service Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahost_viewContent(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Content::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Content not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Content view successfully.';
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
   

    public function becomeahost_listCountry(Request $request){

         try {
            $input =  $request->all();
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = Country::select('countries.*');

            $orderby = $request->orderby ? $request->orderby : 'countries.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('countries.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('countries.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }
           
            if ($request->search && !empty($request->search)) {
                 $q->where(function($query) use ($search) {
                    $query->where('countries.name', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    $row['id'] = $value->id;
                    $row['sortname'] = isset($value->sortname)? $value->sortname:'N/A';
                    $row['name'] = isset($value->name)? $value->name:'N/A';
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Country Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

     public function becomeahost_addCountry(Request $request){

        $input = $request->all();
    
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

            $data = new Country;
            $data->sortname = $input['sortname'];
            $data->name = $input['name'];
            $data->phonecode = str_replace("+","",$input['phonecode']);
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            $response['status'] = true;
            $response['message'] = 'Country added successfully.';
            $response['permission'] = $permission;
            return response()->json($response, 200);
    }  

    public function becomeahost_edit_view_Country(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Country::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Country not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
     public function becomeahost_updateCountry(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Country::find($id);
    
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Country not found.';
                    return response()->json($response, 200);
                }

                 if(strpos($input['phonecode'], "+") !== false){
                // echo "Found!";
                $phonecode = str_replace("+","",$input['phonecode']);
                $phonecode = $phonecode;
                }else{
                    $phonecode = $input['phonecode'];
                }

                $data->sortname = $input['sortname'] ?? null;
                $data->name = $input['name'] ?? null;
                $data->phonecode = $phonecode ?? null;;
                $data->save();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Country updated successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_deleteCountry(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Country::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Country not found.';
                    return response()->json($response, 200);
                }

                $data->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Country deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewCountry(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Country::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Country not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Country view successfully.';
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_listProvince(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = Province::select('provinces.*', 'countries.name as country_name')->join('countries', 'provinces.country_id', '=', 'countries.id');
            // dd($q);

            $orderby = $request->orderby ? $request->orderby : 'provinces.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('provinces.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('provinces.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }else{
                if(isset($request->start_date) && $request->end_date == null){
                    $q->whereDate('provinces.created_at',$start_date);    
                }
            }
           
            if ($request->search && !empty($request->search)) {
                $q->where(function($query) use ($search) {
                    $query->where('countries.name', 'LIKE', '%' . $request->search . '%');
                    $query->orWhere('provinces.name', 'LIKE', '%' . $request->search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*20)->take(20)->get();
            // dd($results);
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    // $row['id'] = $value->id;
                    $countryDetail = Country::where('id',$value->country_id)->first();
                    $row['id'] = $value->id;
                    $row['country_name'] = isset($countryDetail->name)? $countryDetail->name:'N/A';
                    $row['name'] = isset($value->name)? $value->name:'N/A';
                    $row['image'] = $value->image;  
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                // dd($datas);
                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Province Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

    public function becomeahost_addProvince(Request $request){

        $input = $request->all();
    
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

            $data = new Province;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath =   'province/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->country_id = $input['country_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            $response['status'] = true;
            $response['message'] = 'Province added successfully.';
            $response['permission'] = $permission;
            return response()->json($response, 200);
    }  

    public function becomeahost_edit_view_Province(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Province::find($id);
                $country = Country::orderBy('name','asc')->get();
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Province not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
     public function becomeahost_updateProvince(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'country_id' => 'required',
            'name' => 'required|max:255|unique:provinces,name, '. $id .',id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {

            $fail = false;

            if (!$fail) {
                $file = $request->file('image');
                try {
                    if (isset($file)) {
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath =   'province/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);
                        
                        $data = Province::where('id', $id)->update(['country_id' => $input['country_id'],'name' => $input['name'], 'image' => $result['file']]);
                    } else {
                        $data = Province::where('id', $id)->update(['country_id' => $input['country_id'],'name' => $input['name']]);
                    }

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();


                    $response['status'] = true;
                    $response['message'] = 'Province updated successfully.';
                    $response['permission'] = $permission;
                    return response()->json($response, 200);
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong.';
                    return response()->json($response, 200);
                }
            } else {
                return Redirect::Back()->with('error',"Something went wrong.");
            }

        }
    }


    public function becomeahost_deleteProvince(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Province::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Province not found.';
                    return response()->json($response, 200);
                }

                $data->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Province deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewProvince(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Province::find($id);
                $province_name = Country::where('id',$data->country_id)->pluck('name')->first();
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Province not found.';
                    return response()->json($response, 200);
                }
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Province view successfully.';
                $response['data'] = [
                                'data' => $data,
                                'country_name' => $province_name,
                            ];
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


     public function becomeahost_listCity(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = City::select('cities.*', 'countries.name as country_name', 'provinces.name as province_name')->join('countries', 'cities.country_id', '=', 'countries.id')->join('provinces', 'cities.province_id', '=', 'provinces.id');

            $orderby = $request->orderby ? $request->orderby : 'cities.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('cities.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('cities.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }else{
                if(isset($request->start_date) && $request->end_date == null){
                    $q->whereDate('cities.created_at',$start_date);    
                }
            }
           
            if ($request->search && !empty($request->search)) {
                $q->where(function($query) use ($search) {
                    $query->where('cities.name', 'LIKE', '%' . $request->search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*20)->take(20)->get();
            // dd($results);
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    // $row['id'] = $value->id;
                    $countryDetail = Country::where('id',$value->country_id)->first();
                    $provinceDetail = Province::where('id',$value->province_id)->first();
                    $row['id'] = $value->id;
                    $row['country_name'] = isset($countryDetail->name)? $countryDetail->name:'N/A';
                    $row['province_name'] = isset($provinceDetail->name)? $provinceDetail->name:'N/A';
                    $row['name'] = isset($value->name)? $value->name:'N/A';
                    // $row['image'] = $value->image;  
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                // dd($datas);
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'City Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

    public function becomeahost_addCity(Request $request){

        $input = $request->all();
    
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

            $data = new City;
            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            $response['status'] = true;
            $response['message'] = 'City added successfully.';
            $response['permission'] = $permission;
            return response()->json($response, 200);
    }  

    public function becomeahost_edit_view_City(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = City::find($id);
                $country = Country::orderBy('name','asc')->get();
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'City not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
    public function becomeahost_updateCity(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'country_id' => 'required',
            'province_id'=> 'required',
            'name' => 'required|max:255|unique:cities,name, '. $id .',id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {

            $fail = false;

            if (!$fail) {
                $file = $request->file('image');
                try {
                    if (isset($file)) {
                        
                       $data = City::where('id', $id)->update(['country_id' => $input['country_id'],'province_id' => $input['province_id'],'name' => $input['name']]);
                    } else {
                        $data = City::where('id', $id)->update(['country_id' => $input['country_id'],'province_id' => $input['province_id'],'name' => $input['name']]);
                    }

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['message'] = 'City updated successfully.';
                    $response['permission'] = $permission;
                    return response()->json($response, 200);
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong.';
                    return response()->json($response, 200);
                }
            } else {
                return Redirect::Back()->with('error',"Something went wrong.");
            }

        }
    }


    public function becomeahost_deleteCity(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = City::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'City not found.';
                    return response()->json($response, 200);
                }

                $data->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'City deleted successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewCity(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = City::find($id);
                $country = Country::where('id',$data->country_id)->pluck('name')->first();
                $province = Province::where('id',$data->province_id)->pluck('name')->first();

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'City not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'City view successfully.';
                $response['data'] =  [
                                'data' => $data,
                                'country_name' => $country,
                                'province_name' => $province,
                            ];
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

       public function becomeahost_listArea(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            $q = Area::select('areas.*','countries.id as country_id','countries.name as country_name','provinces.id as province_id','provinces.name as province_name','cities.id as city_id','cities.name as city_name')->leftjoin('countries','countries.id','=','areas.country_id')->leftjoin('provinces','provinces.id','=','areas.province_id')->leftjoin('cities','cities.id','=','areas.city_id');

            $orderby = $request->orderby ? $request->orderby : 'areas.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('areas.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('areas.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }else{
                if(isset($request->start_date) && $request->end_date == null){
                    $q->whereDate('areas.created_at',$start_date);    
                }
            }
           
            if ($request->search && !empty($request->search)) {
                $q->where(function($query) use ($search) {
                    $query->where('areas.name', 'LIKE', '%' . $request->search . '%');
                    $query->orWhere('countries.name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('provinces.name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('cities.name', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*20)->take(20)->get();
            // dd($results);
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                foreach ($results as $value) {
                    // $row['id'] = $value->id;
                    $countryDetail = Country::where('id',$value->country_id)->first();
                    $provinceDetail = Province::where('id',$value->province_id)->first();
                    $cityDetail = City::where('id',$value->city_id)->first();
                    $row['id'] = $value->id;
                    $row['country_name'] = isset($countryDetail->name)? $countryDetail->name:'N/A';
                    $row['province_name'] = isset($provinceDetail->name)? $provinceDetail->name:'N/A';
                    $row['city_name'] = isset($cityDetail->name)? $cityDetail->name:'N/A';
                    $row['name'] = isset($value->name)? $value->name:'N/A';             
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                    
                    $datas[] = $row;
                    $i++;
                    unset($u);
                }
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                // dd($datas);
                    $response['status'] = true;
                    $response['data'] = $datas;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Area Search found.';
                }
             

            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

    public function becomeahost_addArea(Request $request){

        $input = $request->all();
    
        $userData = User::where(['user_type' => 5])->first();
        $userId =  $userData->id;

            $data = new Area;
            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->city_id = $input['city_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

            $response['status'] = true;
            $response['message'] = 'Area added successfully.';
            $response['permission'] = $permission;
            return response()->json($response, 200);
    }  

    public function becomeahost_edit_view_Area(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Area::find($id);
                $country = Country::orderBy('name','asc')->get();
                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Area not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }
    public function becomeahost_updateArea(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'country_id' => 'required',
            'province_id'=> 'required',
            'city_id'    => 'required',
            'name'       => 'required|max:190|unique:areas,name, '. $id .',id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {

            $fail = false;

            if (!$fail) {
                
                try {
                        
                    $data = Area::where('id', $id)->update(['country_id' => $input['country_id'],'province_id' => $input['province_id'],'city_id' => $input['city_id'],'name' => $input['name']]);

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    $response['status'] = true;
                    $response['message'] = 'Area updated successfully.';
                    $response['permission'] = $permission;
                    return response()->json($response, 200);
                } catch (Exception $e) {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong.';
                    return response()->json($response, 200);
                }
            } else {
                return Redirect::Back()->with('error',"Something went wrong.");
            }

        }
    }


    public function becomeahost_deleteArea(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Area::find($id);

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Area not found.';
                    return response()->json($response, 200);
                }

                $data->delete();

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Area deleted successfully.';
                $response['permission'] = $permission;

                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_viewArea(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data = Area::find($id);

                $country = Country::where('id',$data->country_id)->pluck('name')->first();
                $province = Province::where('id',$data->province_id)->pluck('name')->first();
                $city = City::where('id',$data->city_id)->pluck('name')->first();

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Area not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Area view successfully.';
                $response['data'] = [
                                'data'=>$data, 
                                'country'=>$country, 
                                'province'=>$province, 
                                'city'=>$city,
                            ];
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_listAccomodation(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

            // $userData = User::where(['user_type' => 5])->get();
            // $userId =  $userData->id;
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                // dd($user_type);
            $q = Property::select('properties.*','property_address.property_id as main_property_id','property_address.city_id as main_city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id')->with('getPropertyAddress','getProPropertyBathroom','getPropertyBedding','getPropertyBedroom','getPropertyKitchen','getPropertyHouserule');

            $orderby = $request->orderby ? $request->orderby : 'properties.created_at';
            $order = $request->order ? $request->order : 'desc';
            if(isset($request->status) && in_array($request->status,[0,1,2]))
            {
                $q->where('properties.status',$request->status);
            }

            if(isset($request->start_date) && isset($request->end_date))
            {
                $q->whereBetween('properties.created_at',[$request->start_date.' 00:00:01',$request->end_date.' 23:59:59']);
            }else{
                if(isset($request->start_date) && $request->end_date == null){
                    $q->whereDate('properties.created_at',$start_date);    
                }
            }
            $q->where('properties.host',auth()->id());
    
            if(isset($request->search_type_list) && !empty($request->search_type_list))
            {
                $q->where('properties.type',$request->search_type_list);
            }

            if(isset($request->search_book_type) && !empty($request->search_book_type))
            {
                $q->where('properties.book_type',$request->search_book_type);
            }

            if(isset($request->search_city) && !empty($request->search_city))
            { 
                $q->where('property_address.city_id',$request->search_city);
            }
            if(isset($request->search_area))
            {
              $q->where('property_address.area',$request->search_area);
            }
            if(isset($request->max_guest_capacity))
            {
                $q->where('properties.max_guest','<=',$request->max_guest_capacity);
                // $q->where('properties.max_guest','>=',$max_guest_capacity);
            }
            if(isset($request->search_bedroom))
            {
                if ($request->search_bedroom != 'All') {
                    $q->where('property_bedrooms.no_of_bedrooms',$request->search_bedroom);
                }
            }
            if(isset($request->search_category))
            {
                $getPropertyByCat = PropertyCategory::where('category_id', $request->search_category)->groupBy('property_id')->pluck('property_id')->toArray();
                $q->whereIn('properties.id',$getPropertyByCat);
            }
            if(isset($request->price))
            {
                if( strpos($request->price, ',') !== false ) {
                    $price1 = str_replace(',', '', $request->price);
                    $q->where('properties.price',$price1);
                }else{
                    $q->where('properties.price',$request->price);
                }
            }
           
            if ($request->search && !empty($request->search)) {
                $q->where(function($query) use ($search) {
                    $query->where('properties.title', 'LIKE', '%' . $search . '%');
                    $query->orWhere('properties.code', 'LIKE', '%' . $search . '%');
                });
            }
            $results = $q->orderBy($orderby, $order)->offset($page_no*50)->take(50)->get();
            // dd($results);
            
            if(isset($results) && !empty($results)){
                    $datas = array();
                    $i = 1;
                    foreach ($results as $value) {

                        $propertyPrice = DB::table('properties')->where(['id'=>$value->id])->pluck('price')->first();
                        $bathrooms = 'N/A';
                        $beds = 'N/A';

                        if (isset($value->getProPropertyBathroom[0])) {
                            $bathrooms = $value->getProPropertyBathroom[0]->bathroom_with_bathtub + $value->getProPropertyBathroom[0]->bathroom_with_shower + $value->getProPropertyBathroom[0]->toilets;
                        }

                        if (isset($value->getPropertyBedroom[0])) {
                            $beds = $value->getPropertyBedroom[0]->no_of_bunk_bed + $value->getPropertyBedroom[0]->no_of_double_bed + $value->getPropertyBedroom[0]->no_of_double_sofa_bed + $value->getPropertyBedroom[0]->no_of_extra_bed + $value->getPropertyBedroom[0]->no_of_kingsize_bed + $value->getPropertyBedroom[0]->no_of_qweensize_bed + $value->getPropertyBedroom[0]->no_of_single_bed + $value->getPropertyBedroom[0]->no_of_single_sofa_bed;
                        }
                        $addressDetails = PropertyAddress::where('property_id',$value->id)->first();
                        // dd($addressDetails);
                        $city_id = isset($addressDetails) && !empty($addressDetails->city_id) ? $addressDetails->city_id : '';
                        $area = isset($addressDetails) && !empty($addressDetails->area) ? $addressDetails->area : '';
                        if(isset($city_id) && !empty($city_id)){
                            $city_name = City::where('id',$city_id)->pluck('name')->first();
                        }else{
                            $city_name = '';
                        }
                        if(isset($area) && !empty($area)){
                            $area_name = Area::where('id',$area)->pluck('name')->first();
                        }else{
                            $area_name = '';
                        }
                        $user_host = User::where('id',$value->host)->first();
                        $host_name = isset($user_host) ? $user_host->name : '';
                        $row['id'] = $value->id;
                        $row['code'] = $value->code;
                        // $row['title'] = isset($value->title)? $value->title:'N/A';
                        $row['title'] = $value->title;
                        $row['featured'] = $value->featured;
                        $row['free_cancellation'] = $value->free_cancellation;
                        $row['price'] = isset($propertyPrice)? number_format($propertyPrice, 2):'N/A';
                        $row['occupants'] = isset($value->max_guest)? $value->max_guest:'N/A';
                        $row['bedrooms'] = isset($value->no_of_bedrooms)? $value->no_of_bedrooms:'N/A';
                        $row['beds'] = $beds;
                        $row['bathrooms'] = $bathrooms;
                        $new_address = isset($city_name) && !empty($area_name) ? $city_name.' ('.$area_name.')' : '';
                        $row['address'] = isset($new_address) && !empty($new_address) ? $new_address : $city_name;
                        $row['host'] = isset($host_name)? $host_name:'N/A';
                        $row['image'] = $value->image;
                        if($value->book_type == 'Reserve'){
                            $book_type = 'checked';
                        }else{
                            $book_type = '';
                        
                        }
                        // if ($user_type == 5) {
                        
                            $row['book_type'] = ucwords(str_replace('_',' ',$value->book_type)); 
                        // }
                        // else {
                        //     $row['book_type'] = '<div class="form-check-danger form-check form-switch"><input class="form-check-input flexSwitchCheckCheckedDanger" type="checkbox" id="'.$value->id.'" '.$book_type.'></div>';
                        // }


                        
                        // $row['Position'] = '<select class="form-control changePosition" data-id="'.$value->id.'">';
                        
                        // $row['Position'] .= '<option value="0">Select Position</option>';

                        // for($i=1; $i<=$total_property; $i++)
                        // {

                        //     $selected = '';
                        //     if($i == $value->position)
                        //     {
                        //         $selected = 'Selected';
                        //     }

                        //     $row['Position'] .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                        // }
                        
                        $row['position'] =$value->position ;

                        $row['created_at'] = date('d M Y', strtotime($value->created_at));

                        // if ($user_type == 5) {
                        //     $row['status'] = $value->status == 1 ? 'Active' : 'Inactive';
                        // } else {
                            $row['status'] =  isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                        
                       
                        $datas[] = $row;
                        $i++;
                        unset($u);
                    }
                    $total_property = Property::count();
                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                    // dd($datas);
                        $response['status'] = true;
                        $response['data'] = [
                                            'data' => $datas,
                                            'position' => $total_property,
                                            ];
                        $response['permission']= $permission;
                    }else{
                        $response['status'] = false;
                        $response['message'] = 'Property Search found.';
                    }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }  

    public function becomeahost_view_Accomodation(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
        
                $data = Property::with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding')->where('id',$id)->first();

                if(isset($data) && !empty($data->host)){
                    $host_name = User::where(['id'=>$data->host])->pluck('name')->first();
                }else{
                    $host_name = '';
                }
                if(isset($data) && !empty($data->building)){
                    $building_name = Building::where(['id'=>$data->building])->pluck('name')->first();
                }else{
                    $building_name = '';
                }
                $propertyImages = PropertyImage::where('property_id',$id)->get();
                $ratings = Rating::where(['property_id'=>$id])->with('getUser')->get();


                $addressDetails = PropertyAddress::where('property_id',$data->id)->first();
                $country_id = isset($addressDetails) && !empty($addressDetails->country_id) ? $addressDetails->country_id : '';
                $province_id = isset($addressDetails) && !empty($addressDetails->province_id) ? $addressDetails->province_id : '';
                $city_id = isset($addressDetails) && !empty($addressDetails->city_id) ? $addressDetails->city_id : '';
                $area = isset($addressDetails) && !empty($addressDetails->area) ? $addressDetails->area : '';
                if(isset($country_id) && !empty($country_id)){
                    $country_name = Country::where('id',$country_id)->pluck('name')->first();
                }else{
                    $country_name = '';
                }
                if(isset($province_id) && !empty($province_id)){
                    $province_name = Province::where('id',$province_id)->pluck('name')->first();
                }else{
                    $province_name = '';
                }
                if(isset($city_id) && !empty($city_id)){
                    $city_name = City::where('id',$city_id)->pluck('name')->first();
                }else{
                    $city_name = '';
                }
                if(isset($area) && !empty($area)){
                    $area_name = Area::where('id',$area)->pluck('name')->first();
                }else{
                    $area_name = '';
                }
                $amenities = PropertyAmenity::where('property_id',$data->id)->pluck('amenities_id')->toArray();
                // dd($amenities);
                if(isset($amenities) && count($amenities) > 0){
                    $amenity_details = Amenity::whereIn('id',$amenities)->pluck('name')->toArray();
                    if(isset($amenity_details) && count($amenity_details) > 0){
                        $amenity_name = implode (', ', $amenity_details);
                    }
                }else{
                    $amenity_name = '-';
                }
                $services = PropertyExtraService::where('property_id',$data->id)->pluck('service_id')->toArray();
                // dd($services);
                if(isset($services) && count($services) > 0){
                    $services_details = ExtraService::whereIn('id',$services)->pluck('name')->toArray();
                    if(isset($services_details) && count($services_details) > 0){
                        $service_name = implode (', ', $services_details);
                    }
                }else{
                    $service_name = '-';
                }
                $house_rules_details = PropertyHouserule::where('property_id',$data->id)->pluck('name')->toArray();
                // dd($house_rules_details);
                if(isset($house_rules_details) && count($house_rules_details) > 0){
                    $house_rules = implode (', ', $house_rules_details);
                }else{
                    $house_rules = '-';
                }
                $category = PropertyCategory::where('property_id',$data->id)->pluck('category_id')->toArray();
                // dd($category);
                if(isset($category) && count($category) > 0){
                    $category_details = Category::whereIn('id',$category)->pluck('name')->toArray();
                    if(isset($category_details) && count($category_details) > 0){
                        $category_name = implode (', ', $category_details);
                    }
                }else{
                    $category_name = '-';
                }


                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Property not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Property view successfully.';
                $response['data'] =  [
                            'data'=>$data, 
                            'host_name'=>$host_name, 
                            'building_name'=>$building_name,
                            'propertyImages'=>$propertyImages,
                            'ratings'=>$ratings,
                            'country_name'=>$country_name,
                            'province_name'=>$province_name,
                            'city_name'=>$city_name,
                            'area_name'=>$area_name,
                            'amenity_name'=>$amenity_name,
                            'service_name'=>$service_name,
                            'house_rules'=>$house_rules,
                            'category_name'=>$category_name,
                        ];
                $response['permission'] = $permission;

                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


    public function becomeahost_AccomodationDuplicate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                if ($p = Property::where('id',$id)->first()) {
                    $input = DB::table('properties')->where('id',$id )->first();
                    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                    $old_property_id = Property::where('id',$id)->pluck('id')->first();
                    $last_property_id = Property::orderBy('id','desc')->pluck('id')->first();

                    $position = $last_property_id + 1;
                    $data = new Property;
                    if(isset($input->image) && $input->image ){
                        $data->image =$input->image;
                    }
                    $total_property = Property::count();
                    $data->position = $total_property + 1;
                    $code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
                    $data->code = $code;
                    $data->title = $input->title;
                    $data->type = $input->type;
                    $data->host = $input->host ?? auth()->id();
                    $data->featured = $input->featured ?? null;
                    $data->free_cancellation = $input->free_cancellation ?? null;
                    $data->additional_notes = $input->additional_notes ?? null;
                    $data->description = $input->description ?? null;
                    $data->booking_condition = $input->booking_condition ?? null;
                    $data->cancellation_policy = $input->cancellation_policy ?? null;
                    $data->note = $input->note ?? null;
                    $data->max_guest = $input->max_guest;
                    $data->price = $input->price;
                    $data->tax = $input->tax ?? null;
                    $data->building = $input->building ?? null;
                    $data->minimum_no_of_nights = $input->minimum_no_of_nights ?? null;
                    $data->security_deposit_amount = $input->security_deposit_amount ?? null;
                    $data->video_url = $input->video_url ?? null;
                    $data->book_type = $input->book_type ?? null;
                    $data->cctv = $input->cctv ?? null;
                    $data->cctv_locations = $input->cctv_locations ?? null;
                    $data->location_of_television = $input->location_of_television ?? null;
                    $data->pets_allow = $input->pets_allow ?? null;
                    $data->response_time = $input->response_time ?? null;
                    $data->party_rate_commission = $input->party_rate_commission ?? null;
                    $data->standout_amenities = $input->standout_amenities ?? null;
                    $data->allow_a_day_booking = $input->allow_a_day_booking ?? null;
                    $data->created_by = $input->created_by ?? null;
                    $data->check_in_from_time = $input->check_in_from_time ?? null;
                    $data->check_in_to_time = $input->check_in_to_time ?? null;
                    $data->check_out_time = $input->check_out_time ?? null;
                    $data->apartment_responsible = $input->apartment_responsible ?? null;
                    $data->other_responsibility = $input->other_responsibility ?? null;
                    $data->estate_located = $input->estate_located ?? null;
                    $data->estate_name = $input->estate_name ?? null;
                    $data->landmark = $input->landmark ?? null;
                    $data->tarred_located = $input->tarred_located ?? null;
                    $data->tarred_road = $input->tarred_road ?? null;
                    $data->home_support = $input->home_support ?? null;
                    $data->people_allowed_parties = $input->people_allowed_parties ?? null;

                    $data->swimming_pool = $input->swimming_pool;
                    $data->pool_opening_period = $input->pool_opening_period;
                    $data->pool_closing_period = $input->pool_closing_period;
                    $data->heated_swimming_pool = $input->heated_swimming_pool;
                    $data->heated_pool_opening_period = $input->heated_pool_opening_period;
                    $data->heated_pool_closing_period = $input->heated_pool_closing_period;

                    $data->address = $input->address;
                    $data->latitude = $input->latitude;
                    $data->longitude = $input->longitude;
                    
                    $data->status = $input->status;
                    $data->clean_status = $input->clean_status ?? null;
                    $data->host_property_status = 'Accept';
                    $data->position = $position;

                    $data->building = $input->building ?? null;
                    $data->featured = $input->featured ?? null;
                    $data->luxury = $input->luxury ?? null;
                    $data->rare = $input->rare ?? null;
                    
                    if($user_type == 5){
                        $data->created_by = 'Host';
                    }else{
                        $data->created_by = 'Admin';
                    }

                    if($data->save()){
                        $property_id = $data->id;
                        $propertyCategory = PropertyCategory::where('property_id',$old_property_id)->pluck('category_id')->toArray();
                        if(isset($propertyCategory) && !empty($propertyCategory)){
                            foreach($propertyCategory as $cat){
                                $data_category = new PropertyCategory;
                                $data_category->property_id = $property_id;
                                $data_category->category_id = $cat;
                                $data_category->save();
                            }
                        }
                        $propertyAddress = PropertyAddress::where('property_id',$old_property_id)->first();
                        if(isset($propertyAddress) && !empty($propertyAddress)){                    
                            $data_address = new PropertyAddress;
                            $data_address->property_id = $property_id;
                            $data_address->address = $propertyAddress['address'];
                            $data_address->country_id = $propertyAddress['country_id'];
                            $data_address->province_id = $propertyAddress['province_id'];
                            $data_address->city_id = $propertyAddress['city_id'];
                            $data_address->area = $propertyAddress['area'];
                            $data_address->postal_code = $propertyAddress['postal_code']??null;

                            $data_address->street_name = $propertyAddress['street_name'];
                            $data_address->street_type = $propertyAddress['street_type']??null;
                            $data_address->street_number = $propertyAddress['street_number']??null;
                            $data_address->house_number = $propertyAddress['house_number'];
                            $data_address->floor = $propertyAddress['floor'];
                            $data_address->staircase = $propertyAddress['staircase']??null;
                            $data_address->elevator = $propertyAddress['elevator']??0;
                            $data_address->apartment_door_no = $propertyAddress['apartment_door_no'];
                            $data_address->save();
                        }
                        $propertyBedroom = PropertyBedroom::where('property_id',$old_property_id)->first();
                        if(isset($propertyBedroom) && !empty($propertyBedroom)){
                            $data_bedrooms = new PropertyBedroom;
                            $data_bedrooms->property_id = $property_id;
                            $data_bedrooms->no_of_bedrooms = $propertyBedroom['no_of_bedrooms'];
                            $data_bedrooms->communal_zones = $propertyBedroom['communal_zones'];
                            $data_bedrooms->no_of_bunk_bed = $propertyBedroom['no_of_bunk_bed'];
                            $data_bedrooms->no_of_double_bed = $propertyBedroom['no_of_double_bed'];
                            $data_bedrooms->no_of_double_sofa_bed = $propertyBedroom['no_of_double_sofa_bed'];
                            $data_bedrooms->no_of_extra_bed = $propertyBedroom['no_of_extra_bed'];
                            $data_bedrooms->no_of_kingsize_bed = $propertyBedroom['no_of_kingsize_bed'];
                            $data_bedrooms->no_of_qweensize_bed = $propertyBedroom['no_of_qweensize_bed'];
                            $data_bedrooms->no_of_single_bed = $propertyBedroom['no_of_single_bed'];
                            $data_bedrooms->no_of_single_sofa_bed = $propertyBedroom['no_of_single_sofa_bed'];
                            $data_bedrooms->save();
                        }
                        $propertyBathroom = PropertyBathroom::where('property_id',$old_property_id)->first();
                        if(isset($propertyBathroom) && !empty($propertyBathroom)){
                            $data_bathrooms = new PropertyBathroom;
                            $data_bathrooms->property_id = $property_id;
                            $data_bathrooms->bathroom_with_bathtub = $propertyBathroom['bathroom_with_bathtub'];
                            $data_bathrooms->bathroom_with_shower = $propertyBathroom['bathroom_with_shower'];
                            $data_bathrooms->toilets = $propertyBathroom['toilets'];
                            $data_bathrooms->sauna = $propertyBathroom['sauna']??0;
                            $data_bathrooms->jacuzzi = $propertyBathroom['jacuzzi']??0;
                            $data_bathrooms->hair_dryer = $propertyBathroom['hair_dryer']??0;
                            $data_bathrooms->towels = $propertyBathroom['towels'];
                            $data_bathrooms->towel_change = $propertyBathroom['towel_change']??0;
                            $data_bathrooms->towel_change_frequency = $propertyBathroom['towel_change_frequency'];
                            $data_bathrooms->save();
                        }
                        $propertyKitchen = PropertyKitchen::where('property_id',$old_property_id)->first();
                        if(isset($propertyKitchen) && !empty($propertyKitchen)){
                            $data_kitchen = new PropertyKitchen;
                            $data_kitchen->property_id = $property_id;
                            $data_kitchen->no_of_kitchens = $propertyKitchen['no_of_kitchens'];
                            $data_kitchen->kitchen_type = $propertyKitchen['kitchen_type'];
                            $data_kitchen->kitchen_category = $propertyKitchen['kitchen_category'];
                            $data_kitchen->kitchen_amenities = $propertyKitchen['kitchen_amenities'];
                            $data_kitchen->save();
                        }
                        $propertyBedding = PropertyBedding::where('property_id',$old_property_id)->first();
                        if(isset($propertyBedding) && !empty($propertyBedding)){
                            $data_bedding = new PropertyBedding;
                            $data_bedding->property_id = $property_id;
                            $data_bedding->bed_linen = $propertyBedding['bed_linen'];
                            $data_bedding->bed_linen_change = $propertyBedding['bed_linen_change']??0;
                            $data_bedding->bed_Change_frequency = $propertyBedding['bed_Change_frequency'];
                            $data_bedding->washing_machine = $propertyBedding['washing_machine']??0;
                            $data_bedding->dryer = $propertyBedding['dryer']??0;
                            $data_bedding->iron = $propertyBedding['iron']??0;
                            $data_bedding->television = $propertyBedding['television']??0;
                            $data_bedding->no_of_television = $propertyBedding['no_of_television'];
                            $data_bedding->fans = $propertyBedding['fans'];
                            $data_bedding->satellite_tv = $propertyBedding['satellite_tv']??0;
                            $data_bedding->radio = $propertyBedding['radio']??0;
                            $data_bedding->dvd_player = $propertyBedding['dvd_player']??0;
                            $data_bedding->satellite_tv_language = $propertyBedding['satellite_tv_language'];
                            $data_bedding->mosquito_netting = $propertyBedding['mosquito_netting']??0;
                            $data_bedding->electronic_mosquito_repellents = $propertyBedding['electronic_mosquito_repellents']??0;
                            $data_bedding->internet_access = $propertyBedding['internet_access'];
                            $data_bedding->network_name = $propertyBedding['network_name'];
                            $data_bedding->password = $propertyBedding['password'];
                            $data_bedding->safe = $propertyBedding['safe']??0;
                            $data_bedding->mini_bar = $propertyBedding['mini_bar']??0;
                            $data_bedding->key_code_number = $propertyBedding['key_code_number'];
                            $data_bedding->save();
                        }
                        $propertyAmenity = PropertyAmenity::where('property_id',$old_property_id)->get();
                        if(isset($propertyAmenity) && !empty($propertyAmenity)){
                            foreach($propertyAmenity as $amenity){
                                $data_emenity = new PropertyAmenity;
                                $data_emenity->property_id = $property_id;
                                $data_emenity->amenities_id = $amenity->amenities_id;
                                $data_emenity->save();
                            }
                        }
                        $propertyExtraService = PropertyExtraService::where('property_id',$old_property_id)->get();
                        if(isset($propertyExtraService) && !empty($propertyExtraService)){
                            foreach($propertyExtraService as $service){
                                $data_service = new PropertyExtraService;
                                $data_service->property_id = $property_id;
                                $data_service->service_id = $service->service_id;
                                $data_service->save();
                            }
                        }
                        $propertyHouserule = PropertyHouserule::where('property_id',$old_property_id)->get();
                        if(isset($propertyHouserule) && !empty($propertyHouserule)){
                            foreach($propertyHouserule as $house){
                                $data_house = new PropertyHouserule;
                                $data_house->property_id = $property_id;
                                $data_house->name = $house->name;
                                $data_house->save();
                            }
                        }
                        $propertyImages = PropertyImage::where('property_id',$old_property_id)->get();
                        if(isset($propertyImages) && !empty($propertyImages)){
                            foreach($propertyImages as $image1){
                                $image2 = explode("property/",$image1->image);
            
                                $data_image = new PropertyImage;
                                $data_image->property_id = $property_id;
                                $data_image->image = $image2[1];
                                $data_image->image_type = 'IMAGE';
                                $data_image->save();
                                $data_image->position =    $data_image->id;
                                $data_image->save();
                            }
                        }
                    }
                    $response['status'] = true;
                    $response['message'] = 'Duplicate Accommodation created Successfully.';
                    return response()->json($response, 200);
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Invalid property id.';
                    return response()->json($response, 200);
                }
               
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_delete_Accomodation(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;

                if(property::findOrFail($id)->delete()){
                    PropertyAddress::where('property_id',$id)->delete();
                    PropertyBathroom::where('property_id',$id)->delete();
                    PropertyBedding::where('property_id',$id)->delete();
                    PropertyBedroom::where('property_id',$id)->delete();
                    PropertyKitchen::where('property_id',$id)->delete();
                    PropertyImage::where('property_id',$id)->delete();
                    PropertyExtraService::where('property_id',$id)->delete();
                    PropertyHouserule::where('property_id',$id)->delete();
                    Rating::where('property_id',$id)->delete();
                    PropertyAmenity::where('property_id',$id)->delete();
                    PropertyTag::where('property_id',$id)->delete();


                    $response['status'] = true;
                    $response['message'] = 'Accommodation delete successfully. ';
                     return response()->json($response, 200);
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Accommodation does not delete. ';
                     return response()->json($response, 200);
                }
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }

    
    }

    public function becomeahost_AddMoreImage_Accomodation(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data['property'] = Property::find($id);

                if ($request->file('addMoremultipalImage')) {
                    $i = 1;
                    foreach ($request->file("addMoremultipalImage") as $key => $file) {
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath =   'property/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);
                        // dd($result);
                        $modelProductImages = new PropertyImage();
                        $modelProductImages->image = $result['file'];
                        $modelProductImages->image_type = $result['type'];

                        $i++;
                        $modelProductImages->property_id = $data['property']->id;
                        $modelProductImages->save();
                        $modelProductImages->position =    $modelProductImages->id;
                        // dd($modelProductImages);
                        $modelProductImages->save();
                    }
                    $data['propertyImage'] = PropertyImage::select('image','id','image_type')->where('property_id',$data['property']->id)->get();

                    if($data['property']->id){
                        $response['message'] = 'Property Images added successfully';
                        $response['status'] = true;
                        $response['data'] = $data;
                        return response()->json($response, 200);

                        
                    }else{
                        $response['message'] = 'Property can`t be Added!!';
                        $response['status'] = false;
                        return response()->json($response, 200);
                    } 
                }else{
                    $response['message'] = 'At least One Image is  required!!';
                    $response['status'] = 0;
                    return response()->json($response, 200);
                }
            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_imageView_Accomodation(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                $data['property'] = Property::findOrFail($id);
                $data['propertyImage'] = PropertyImage::select('image','id','image_type','property_id','position')->where('property_id',$data['property']->id)->orderBy('position','asc')->get();

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Property not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

    public function becomeahost_imagesDelete_Accomodation(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {

                $ids = explode(',',$request->id);
                PropertyImage::whereIn('id', $ids)->delete();
                
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Property Images deleted successfully';
                $response['permission'] = $permission;
                return response()->json($response, 200);
        

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }



    public function becomeahost_propertyAdd(Request $request)
    {
        $input = $request->all();
        Log::info(['input' => $input]);
        $validator = Validator::make($request->all(), [
             'user_id'   => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                if ($userData = User::where(['id' => $input['user_id'], 'user_type' => 5])->first()) {
                    if (isset($request->property) && !empty($request->property)) {
                        $properties = $request->property;
                        $arrayDatas = json_decode($properties, true);
                        if (!empty($arrayDatas)) {
                            foreach ($arrayDatas as $val) {
                                    $data = new Property;

                                    if ($request->hasFile('image')) {
                                        $file = $request->file('image');
                                        $newFolder = strtoupper(date('M') . date('Y'));
                                        $folderPath = 'property/' . $newFolder;
                                        $result = fileUploads('s3', $file, $folderPath, false);
                                        $data->image = $result['file'];
                                    }

                                    $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                                    $commission = AdminSettings::pluck('commission')->first();

                                    if ($commission) {
                                        $price1 = $val['price'] * $commission / 100;
                                        $updated_price = $price1 + $val['price'];

                                        if (isset($val['tax']) && $val['tax']) {
                                            $price1 = $updated_price * $val['tax'] / 100;
                                            $updated_price += $price1;
                                        }
                                    } else {
                                        $updated_price = $val['price'];

                                        if (isset($val['tax']) && $val['tax']) {
                                            $price1 = $val['price'] * $val['tax'] / 100;
                                            $updated_price += $price1;
                                        }
                                    }

                                    $data->code = $code;
                                    $data->title = $val['title'] ?? '0';
                                    $data->type = $val['type'] ?? '0';
                                    $data->host = $val['host'] ?? auth()->id();
                                    $data->additional_notes = $val['additional_notes'] ?? '0';
                                    $data->description = $val['description'] ?? '0';
                                    $data->booking_condition = $val['booking_condition'] ?? '0';
                                    $data->cancellation_policy = $val['cancellation_policy'] ?? '0';
                                    $data->note = $val['note'] ?? '0';
                                    $data->max_guest = $val['max_guest'] ?? '0';
                                    $data->price = $val['price'] ?? '0';
                                    $data->updated_price = $updated_price;
                                    $data->tax = $val['tax'] ?? '0';
                                    $data->building = $val['building'] ?? null;
                                    if ($val['featured'] != '') {
                                        $data->featured = $val['featured'];
                                    }else{
                                        $data->featured = 'No';
                                    }
                                    
                                    $data->luxury = $val['luxury'] ?? null;
                                    $data->rare = $val['rare'] ?? null;
                                    $data->minimum_no_of_nights = $val['minimum_no_of_nights'] ?? '0';
                                    $data->security_deposit_amount = $val['security_deposit_amount'] ?? '0';
                                    $data->video_url = $val['video_url'] ?? null;
                                    $data->book_type = $val['book_type'] ?? '0';
                                    $data->swimming_pool = $val['swimming_pool'] ?? '0';
                                    $data->pool_opening_period = $val['pool_opening_period'] ?? '';
                                    $data->pool_closing_period = $val['pool_closing_period'] ?? '';
                                    $data->heated_swimming_pool = $val['heated_swimming_pool'] ?? '';
                                    $data->heated_pool_opening_period = $val['heated_pool_opening_period'] ?? '';
                                    $data->heated_pool_closing_period = $val['heated_pool_closing_period'] ?? '';
                                    $data->check_in_from_time = $val['check_in_from_time'] ?? '0';
                                    $data->check_in_to_time = $val['check_in_to_time'] ?? '0';
                                    $data->check_out_time = $val['check_out_time'] ?? '0';
                                    $data->refund = $val['refund'] ?? '0';
                                    $data->pets_allow = $val['pets_allow'] ?? '0';
                                    $data->cctv = $val['cctv'] ?? '0';
                                    $data->cctv_locations = $val['cctv_locations'] ?? '0';
                                    $data->response_time = $val['response_time'] ?? '0';
                                    $data->location_of_television = $val['location_of_television'] ?? '0';
                                    $data->standout_amenities = $val['standout_amenities'] ?? '0';
                                    $data->address = $val['address'] ?? '0';
                                    $data->latitude = $val['latitude'] ?? '0';
                                    $data->longitude = $val['longitude'] ?? '0';
                                    $data->status = $val['status'] ?? '0';
                                    $total_property = Property::count();
                                    $data->position = $total_property + 1;
                                    $data->clean_status = $val['clean_status'] ?? '0';
                                    $data->host_property_status = 'Accept';
                                    $data->position = $val['position'] ?? $data->id;
                                
                                    if ($data->save()) {
                                        if (!empty($val['category'])) {
                                            foreach ($val['category'] as $cat) {
                                                $data_category = new PropertyCategory;
                                                $data_category->property_id = $data->id;
                                                $data_category->category_id = $cat;
                                                $data_category->save();
                                            }
                                        }

                                        if (!empty($val['city_id'])) {
                                            $data_address = new PropertyAddress;

                                            $area_name = Area::where('id', $val['area'])->pluck('name')->first();
                                            $city_name = City::where('id', $val['city_id'])->pluck('name')->first();
                                            $province_name = Province::where('id', $val['province_id'])->pluck('name')->first();
                                            $country_name = Country::where('id', $val['country_id'])->pluck('name')->first();
                                            $address = $area_name ?? '' . ', ' . $city_name . ', ' . $province_name . ', ' . $country_name;

                                            $data_address->property_id = $data->id;
                                            $data_address->address = $address;
                                            $data_address->country_id = $val['country_id'] ?? '0';
                                            $data_address->province_id = $val['province_id'] ?? '0';
                                            $data_address->city_id = $val['city_id'] ?? '0';
                                            $data_address->area = $val['area'] ?? '0';
                                            $data_address->postal_code = $val['postal_code'] ?? '0';
                                            $data_address->street_name = $val['street_name'] ?? '0';
                                            $data_address->street_type = $val['street_type'] ?? '0';
                                            $data_address->street_number = $val['street_number'] ?? '0';
                                            $data_address->house_number = $val['house_number'] ?? '0';
                                            $data_address->floor = $val['floor'] ?? '0';
                                            $data_address->staircase = $val['staircase'] ?? '0';
                                            $data_address->elevator = $val['elevator'] ?? '0';
                                            $data_address->apartment_door_no = $val['apartment_door_no'] ?? '0';
                                            $data_address->save();
                                        }

                                        if (isset($val['no_of_bedrooms'])) {
                                            $data_bedrooms = new PropertyBedroom;
                                            $data_bedrooms->property_id = $data->id;
                                            $data_bedrooms->no_of_bedrooms = $val['no_of_bedrooms'] ?? '0';
                                            // $data_bedrooms->communal_zones = isset($val['communal_zones']) ? implode(',', $val['communal_zones']) : null;
                                            $data_bedrooms->communal_zones = is_array($val['communal_zones']) ? implode(',', $val['communal_zones']) : null;

                                            $data_bedrooms->no_of_bunk_bed = $val['no_of_bunk_bed'] ?? '0';
                                            $data_bedrooms->no_of_double_bed = $val['no_of_double_bed'] ?? '0';
                                            $data_bedrooms->no_of_double_sofa_bed = $val['no_of_double_sofa_bed'] ?? '0';
                                            $data_bedrooms->no_of_extra_bed = $val['no_of_extra_bed'] ?? '0';
                                            $data_bedrooms->no_of_kingsize_bed = $val['no_of_kingsize_bed'] ?? '0';
                                            $data_bedrooms->no_of_qweensize_bed = $val['no_of_qweensize_bed'] ?? '0';
                                            $data_bedrooms->no_of_single_bed = $val['no_of_single_bed'] ?? '0';
                                            $data_bedrooms->no_of_single_sofa_bed = $val['no_of_single_sofa_bed'] ?? '0';
                                            $data_bedrooms->save();
                                        }

                                        $data_bathrooms = new PropertyBathroom;
                                        $data_bathrooms->property_id = $data->id;
                                        $data_bathrooms->bathroom_with_bathtub = $val['bathroom_with_bathtub'] ?? '0';
                                        $data_bathrooms->bathroom_with_shower = $val['bathroom_with_shower'] ?? '0';
                                        $data_bathrooms->toilets = $val['toilets'] ?? '0';
                                        $data_bathrooms->sauna = $val['sauna'] ?? '0';
                                        $data_bathrooms->jacuzzi = $val['jacuzzi'] ?? '0';
                                        $data_bathrooms->hair_dryer = $val['hair_dryer'] ?? '0';
                                        $data_bathrooms->towels = $val['towels'] ?? '0';
                                        $data_bathrooms->towel_change = $val['towel_change'] ?? '0';
                                        $data_bathrooms->towel_change_frequency = $val['towel_change_frequency'] ?? '0';
                                        $data_bathrooms->save();

                                        if (isset($val['no_of_kitchens'])) {
                                            $data_kitchen = new PropertyKitchen;
                                            $data_kitchen->property_id = $data->id;
                                            $data_kitchen->no_of_kitchens = $val['no_of_kitchens'] ?? '0';
                                            $data_kitchen->kitchen_type = $val['kitchen_type'] ?? '0';
                                            $data_kitchen->kitchen_category = $val['kitchen_category'] ?? '0';
                                            $data_kitchen->kitchen_amenities = is_array($val['kitchen_amenities']) ? implode(',', $val['kitchen_amenities']) : null;

                                            $data_kitchen->save();
                                        }

                                        $data_bedding = new PropertyBedding;
                                        $data_bedding->property_id = $data->id;
                                        $data_bedding->bed_linen = $val['bed_linen'] ?? '0';
                                        $data_bedding->bed_linen_change = $val['bed_linen_change'] ?? '0';
                                        $data_bedding->bed_Change_frequency = $val['bed_Change_frequency'] ?? '0';
                                        $data_bedding->washing_machine = $val['washing_machine'] ?? '0';
                                        $data_bedding->dryer = $val['dryer'] ?? '0';
                                        $data_bedding->iron = $val['iron'] ?? '0';
                                        $data_bedding->television = $val['television'] ?? '0';
                                        $data_bedding->no_of_television = $val['no_of_television'] ?? '0';
                                        $data_bedding->fans = $val['fans'] ?? '0';
                                        $data_bedding->satellite_tv = $val['satellite_tv'] ?? '0';
                                        $data_bedding->satellite_tv_language = is_array($val['satellite_tv_language']) ? implode(',', $val['satellite_tv_language']) : null;

                                        $data_bedding->mosquito_netting = $val['mosquito_netting'] ?? '0';
                                        $data_bedding->electronic_mosquito_repellents = $val['electronic_mosquito_repellents'] ?? '0';
                                        $data_bedding->internet_access = $val['internet_access'] ?? '0';
                                        $data_bedding->network_name = $val['network_name'] ?? '0';
                                        $data_bedding->password = $val['password'] ?? '0';
                                        $data_bedding->safe = $val['safe'] ?? '0';
                                        $data_bedding->mini_bar = $val['mini_bar'] ?? '0';
                                        $data_bedding->key_code_number = $val['key_code_number'] ?? '0';
                                        $data_bedding->save();

                                        foreach ($val['property_amenities'] as $amenity) {
                                            // dd($amenity);
                                            $data_emenity = new PropertyAmenity;
                                            $data_emenity->property_id = $data->id;
                                            $data_emenity->amenities_id = $amenity;
                                            $data_emenity->save();
                                        }

                                        if (!empty($val['property_extra_services'])) {
                                            foreach ($val['property_extra_services'] as $service) {
                                                $data_service = new PropertyExtraService;
                                                $data_service->property_id = $data->id;
                                                $data_service->service_id = $service;
                                                $data_service->save();
                                            }
                                        }

                                        if (!empty($val['house_rule'])) {
                                            foreach ($val['house_rule'] as $house) {
                                                $data_house = new PropertyHouserule;
                                                $data_house->property_id = $data->id;
                                                $data_house->name = $house;
                                                $data_house->save();
                                            }
                                        }

                                    }
                            }
                        }
                        $response['status'] = true;
                        $response['message'] = 'Property added successfully';
                    }else{
                        $response['status'] = false;
                        $response['message'] = 'Please send record';
                    }
                    
                }else{
                    $response['status'] = false;
                    $response['message'] = 'User not found';
                }
                
            } catch (\Exception $e) {
                Log::info(['e' => $e]);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
            }
        } 
        $response['permission'] = Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
        return response()->json($response, 200); 

    }

    public function becomeahost_propertyUpdate(Request $request)
    {
        $input = $request->all(); 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        }else {

            try {
                 $userData = User::where(['id' => auth()->id(), 'user_type' => 5])->first();
                 // User::where('id',auth()->id(), 'user_type', 5)->first();
                
                  if ($userData) {
                    if ($request->has('property')) {
                        $properties = $request->property;

                         $arrayDatas = json_decode($properties, true);
                        if (!empty($arrayDatas)) {
                            foreach ($arrayDatas as $val) {
                                    
                                    $id = $request->id;
                                    $data = Property::where('id',$id)->first();
                                    if ($request->hasFile('image')) {
                                        $file = $request->file('image');
                                        $newFolder = strtoupper(date('M') . date('Y'));
                                        $folderPath = 'property/' . $newFolder;
                                        $result = fileUploads('s3', $file, $folderPath, false);
                                        $data->image = $result['file'];
                                    }

                                    $code = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
                                    $commission = AdminSettings::pluck('commission')->first();

                                    if ($commission) {
                                        $price1 = $val['price'] * $commission / 100;
                                        $updated_price = $price1 + $val['price'];

                                        if (isset($val['tax']) && $val['tax']) {
                                            $price1 = $updated_price * $val['tax'] / 100;
                                            $updated_price += $price1;
                                        }
                                    } else {
                                        $updated_price = $val['price'];

                                        if (isset($val['tax']) && $val['tax']) {
                                            $price1 = $val['price'] * $val['tax'] / 100;
                                            $updated_price += $price1;
                                        }
                                    }

                                    $data->code = $code;
                                    $data->title = $val['title'] ?? $data->title;
                                    $data->type = $val['type'] ?? $data->type;
                                    $data->host = $val['host'] ?? auth()->id();
                                    $data->additional_notes = $val['additional_notes'] ?? $data->additional_notes;
                                    $data->description = $val['description'] ?? $data->description;
                                    $data->booking_condition = $val['booking_condition'] ?? $data->booking_condition;
                                    $data->cancellation_policy = $val['cancellation_policy'] ?? $data->cancellation_policy;
                                    $data->note = $val['note'] ?? $data->note;
                                    $data->max_guest = $val['max_guest'] ?? $data->max_guest;
                                    $data->price = $val['price'] ?? $data->price;
                                    $data->updated_price = $updated_price;
                                    $data->tax = $val['tax'] ?? $data->tax;
                                    $data->building = $val['building'] ?? $data->building;
                                    $data->featured = $val['featured'] ?? $data->featured;
                                    $data->luxury = $val['luxury'] ?? $data->luxury;
                                    $data->rare = $val['rare'] ?? $data->rare;
                                    $data->minimum_no_of_nights = $val['minimum_no_of_nights'] ?? $data->minimum_no_of_nights;
                                    $data->security_deposit_amount = $val['security_deposit_amount'] ?? $data->security_deposit_amount;
                                    $data->video_url = $val['video_url'] ?? $data->video_url;
                                    $data->book_type = $val['book_type'] ?? $data->book_type;
                                    $data->swimming_pool = $val['swimming_pool'] ?? $data->swimming_pool;
                                    $data->pool_opening_period = $val['pool_opening_period'] ?? $data->pool_opening_period;
                                    $data->pool_closing_period = $val['pool_closing_period'] ?? $data->pool_closing_period;
                                    $data->heated_swimming_pool = $val['heated_swimming_pool'] ?? $data->heated_swimming_pool;
                                    $data->heated_pool_opening_period = $val['heated_pool_opening_period'] ?? $data->heated_pool_opening_period;
                                    $data->heated_pool_closing_period = $val['heated_pool_closing_period'] ?? $data->heated_pool_closing_period;
                                    $data->check_in_from_time = $val['check_in_from_time'] ?? $data->check_in_from_time;
                                    $data->check_in_to_time = $val['check_in_to_time'] ?? $data->check_in_to_time;
                                    $data->check_out_time = $val['check_out_time'] ?? $data->check_out_time;
                                    $data->refund = $val['refund'] ?? $data->refund;
                                    $data->pets_allow = $val['pets_allow'] ?? $data->pets_allow;
                                    $data->cctv = $val['cctv'] ?? $data->cctv;
                                    $data->cctv_locations = $val['cctv_locations'] ?? $data->cctv_locations;
                                    $data->response_time = $val['response_time'] ?? $data->response_time;
                                    $data->location_of_television = $val['location_of_television'] ?? $data->location_of_television;
                                    $data->standout_amenities = $val['standout_amenities'] ?? $data->standout_amenities;
                                    $data->address = $val['address'] ?? $data->address;
                                    $data->latitude = $val['latitude'] ?? $data->latitude;
                                    $data->longitude = $val['longitude'] ?? $data->longitude;
                                    $data->status = $val['status'] ?? $data->status;
                                    $total_property = Property::count();
                                    $data->position = $total_property + 1;
                                    $data->clean_status = $val['clean_status'] ?? $data->clean_status;
                                    $data->host_property_status = 'Accept';
                                    $data->position = $val['position'] ?? $id;
                                    // dd($data);
                                    if ($data->save()) {
                                        if (!empty($val['category'])) {
                                            PropertyCategory::where('property_id',$id)->delete();
                                            foreach ($val['category'] as $cat) {
                                                $data_category = new PropertyCategory;
                                                $data_category->property_id = $id;
                                                $data_category->category_id = $cat;
                                                $data_category->save();
                                            }
                                        }

                                        if (isset($val['city_id']) && !empty($val['city_id'])) {
                                             PropertyAddress::where('property_id',$id)->delete();
                                            $data_address = new PropertyAddress;

                                            $area_name = Area::where('id', $val['area'])->pluck('name')->first();
                                            $city_name = City::where('id', $val['city_id'])->pluck('name')->first();
                                            $province_name = Province::where('id', $val['province_id'])->pluck('name')->first();
                                            $country_name = Country::where('id', $val['country_id'])->pluck('name')->first();
                                            $address = $area_name ?? '' . ', ' . $city_name . ', ' . $province_name . ', ' . $country_name;

                                            $data_address->property_id = $id;
                                            $data_address->address = $address;
                                            $data_address->country_id = $val['country_id'] ?? $data_address->country_id;
                                            $data_address->province_id = $val['province_id'] ?? $data_address->province_id;
                                            $data_address->city_id = $val['city_id'] ?? $data_address->city_id;
                                            $data_address->area = $val['area'] ?? $data_address->area;
                                            $data_address->postal_code = $val['postal_code'] ?? $data_address->postal_code;
                                            $data_address->street_name = $val['street_name'] ?? $data_address->street_name;
                                            $data_address->street_type = $val['street_type'] ?? $data_address->street_type;
                                            $data_address->street_number = $val['street_number'] ?? $data_address->street_number;
                                            $data_address->house_number = $val['house_number'] ?? $data_address->house_number;
                                            $data_address->floor = $val['floor'] ?? $data_address->floor;
                                            $data_address->staircase = $val['staircase'] ?? $data_address->staircase;
                                            $data_address->elevator = $val['elevator'] ?? $data_address->elevator;
                                            $data_address->apartment_door_no = $val['apartment_door_no'] ?? $data_address->apartment_door_no;
                                            $data_address->save();
                                        }

                                        if (isset($val['no_of_bedrooms'])) {
                                            PropertyBedroom::where('property_id',$id)->delete();
                                            $data_bedrooms = new PropertyBedroom;
                                            $data_bedrooms->property_id = $id;
                                            $data_bedrooms->no_of_bedrooms = $val['no_of_bedrooms'] ?? $data_bedrooms->no_of_bedrooms;
                                            // $data_bedrooms->communal_zones = isset($val['communal_zones']) ? implode(',', $val['communal_zones']) : null;
                                            $data_bedrooms->communal_zones = is_array($val['communal_zones']) ? implode(',', $val['communal_zones']) : $data_bedrooms->communal_zones;

                                            $data_bedrooms->no_of_bunk_bed = $val['no_of_bunk_bed'] ?? $data_bedrooms->no_of_bunk_bed;
                                            $data_bedrooms->no_of_double_bed = $val['no_of_double_bed'] ?? $data_bedrooms->no_of_double_bed;
                                            $data_bedrooms->no_of_double_sofa_bed = $val['no_of_double_sofa_bed'] ?? $data_bedrooms->no_of_double_sofa_bed;
                                            $data_bedrooms->no_of_extra_bed = $val['no_of_extra_bed'] ?? $data_bedrooms->no_of_extra_bed;
                                            $data_bedrooms->no_of_kingsize_bed = $val['no_of_kingsize_bed'] ?? $data_bedrooms->no_of_kingsize_bed;
                                            $data_bedrooms->no_of_qweensize_bed = $val['no_of_qweensize_bed'] ?? $data_bedrooms->no_of_qweensize_bed;
                                            $data_bedrooms->no_of_single_bed = $val['no_of_single_bed'] ?? $data_bedrooms->no_of_single_bed;
                                            $data_bedrooms->no_of_single_sofa_bed = $val['no_of_single_sofa_bed'] ?? $data_bedrooms->no_of_single_sofa_bed;
                                            $data_bedrooms->save();
                                        }

                                        $data_bathrooms = new PropertyBathroom;
                                        PropertyBathroom::where('property_id',$id)->delete();
                                        $data_bathrooms->property_id = $id;
                                        $data_bathrooms->bathroom_with_bathtub = $val['bathroom_with_bathtub'] ?? $data_bathrooms->bathroom_with_bathtub;
                                        $data_bathrooms->bathroom_with_shower = $val['bathroom_with_shower'] ?? $data_bathrooms->bathroom_with_shower;
                                        $data_bathrooms->toilets = $val['toilets'] ?? $data_bathrooms->toilets;
                                        $data_bathrooms->sauna = $val['sauna'] ?? $data_bathrooms->sauna;
                                        $data_bathrooms->jacuzzi = $val['jacuzzi'] ?? $data_bathrooms->jacuzzi;
                                        $data_bathrooms->hair_dryer = $val['hair_dryer'] ?? $data_bathrooms->hair_dryer;
                                        $data_bathrooms->towels = $val['towels'] ?? $data_bathrooms->towels;
                                        $data_bathrooms->towel_change = $val['towel_change'] ?? $data_bathrooms->towel_change;
                                        $data_bathrooms->towel_change_frequency = $val['towel_change_frequency'] ?? $data_bathrooms->towel_change_frequency;
                                        $data_bathrooms->save();

                                        if (isset($val['no_of_kitchens'])) {
                                            PropertyKitchen::where('property_id',$id)->delete();
                                            $data_kitchen = new PropertyKitchen;
                                            $data_kitchen->property_id = $id;
                                            $data_kitchen->no_of_kitchens = $val['no_of_kitchens'] ?? $data_kitchen->no_of_kitchens;
                                            $data_kitchen->kitchen_type = $val['kitchen_type'] ?? $data_kitchen->kitchen_type;
                                            $data_kitchen->kitchen_category = $val['kitchen_category'] ?? $data_kitchen->kitchen_category;
                                            // $data_kitchen->kitchen_amenities = isset($val['kitchen_amenities']) ? implode(',', $val['kitchen_amenities']) : null;
                                            $data_kitchen->kitchen_amenities = is_array($val['kitchen_amenities']) ? implode(',', $val['kitchen_amenities']) : $data_kitchen->kitchen_amenities;

                                            $data_kitchen->save();
                                        }
                                        PropertyBedding::where('property_id',$id)->delete();
                                        $data_bedding = new PropertyBedding;
                                        $data_bedding->property_id = $id;
                                        $data_bedding->bed_linen = $val['bed_linen'] ?? $data_bedding->bed_linen;
                                        $data_bedding->bed_linen_change = $val['bed_linen_change'] ?? $data_bedding->bed_linen_change;
                                        $data_bedding->bed_Change_frequency = $val['bed_Change_frequency'] ?? $data_bedding->bed_Change_frequency;
                                        $data_bedding->washing_machine = $val['washing_machine'] ?? $data_bedding->washing_machine;
                                        $data_bedding->dryer = $val['dryer'] ?? $data_bedding->dryer;
                                        $data_bedding->iron = $val['iron'] ?? $data_bedding->iron;
                                        $data_bedding->television = $val['television'] ?? $data_bedding->television;
                                        $data_bedding->no_of_television = $val['no_of_television'] ?? $data_bedding->no_of_television;
                                        $data_bedding->fans = $val['fans'] ?? $data_bedding->fans;
                                        $data_bedding->satellite_tv = $val['satellite_tv'] ?? $data_bedding->satellite_tv;
                                        // $data_bedding->satellite_tv_language = isset($val['satellite_tv_language']) ? implode(',', $val['satellite_tv_language']) : null;
                                        $data_bedding->satellite_tv_language = is_array($val['satellite_tv_language']) ? implode(',', $val['satellite_tv_language']) : $data_bedding->satellite_tv_language;

                                        $data_bedding->mosquito_netting = $val['mosquito_netting'] ?? $data_bedding->mosquito_netting;
                                        $data_bedding->electronic_mosquito_repellents = $val['electronic_mosquito_repellents'] ?? $data_bedding->electronic_mosquito_repellents;
                                        $data_bedding->internet_access = $val['internet_access'] ?? $data_bedding->internet_access;
                                        $data_bedding->network_name = $val['network_name'] ?? $data_bedding->network_name;
                                        $data_bedding->password = $val['password'] ?? $data_bedding->password;
                                        $data_bedding->safe = $val['safe'] ?? $data_bedding->safe;
                                        $data_bedding->mini_bar = $val['mini_bar'] ?? $data_bedding->mini_bar;
                                        $data_bedding->key_code_number = $val['key_code_number'] ?? $data_bedding->key_code_number;
                                        $data_bedding->save();

                                        foreach ($val['property_amenities'] as $amenity) {
                                            // dd($amenity);
                                            $data_emenity = new PropertyAmenity;
                                            $data_emenity->property_id = $id;
                                            $data_emenity->amenities_id = $amenity;
                                            $data_emenity->save();
                                        }

                                        if (!empty($val['property_extra_services'])) {
                                            foreach ($val['property_extra_services'] as $service) {
                                                $data_service = new PropertyExtraService;
                                                $data_service->property_id = $id;
                                                $data_service->service_id = $service;
                                                $data_service->save();

                                            }
                                        }

                                        if (!empty($val['house_rule'])) {
                                            foreach ($val['house_rule'] as $house) {
                                                $data_house = new PropertyHouserule;
                                                $data_house->property_id = $id;
                                                $data_house->name = $house;
                                                $data_house->save();
                                            }
                                        }

                                    }
                                
                            }
                        }

                         // $jsonString = json_encode($arrayDatas); 
                        // else {
                        //     $response['status'] = false;
                        //     $response['message'] = 'User not found.';
                        //     return response()->json($response, 200);
                        // }
                    }
                }
                
                $response['status'] = true;
                $response['message'] = 'Property update successfully';
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
        $response['permission'] = Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
        return response()->json($response, 200); 
    }


    public function becomeahost_view_calendar(Request $request) {

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;

               $data = [
                    'id'=>$input['id'],
                ];

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        } 
    }



    public function becomeahost_load_calendar(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;

                $range = 6;
                $page = $input['page'] ?? 1;
                $start_range = (($range*$page)-6);
                $end_range = $range*$page;

                $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));;
               // dd($start_date);
                $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
                $allDates = [];

                $alreadybooking = Booking::where('property_id', $input['id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
                    ->pluck('from_date','to_date')->toArray();

                //$alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', 1)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();


                $mo = [];
                for($i=0; $i<6;$i++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$i.'month',strtotime($start_date)));

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
                                    $allDates[] = $date->format('d');
                                }
                            }
                            else
                            {

                                $period = CarbonPeriod::create($value, $key);
                                foreach ($period as $date) {
                                    if($getThisMonth == $date->format('m'))
                                    {
                                        $allDates[] = $date->format('d');
                                    }

                                }

                            }

                        }
                        $allDates = array_unique($allDates);

                    }

                    $mo[$i] = implode(',', $allDates);
                }



                $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', 1)->where('added_by_sub', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();


                $admin_month = [];
                for($i=0; $i<6;$i++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$i.'month',strtotime($start_date)));

                    $getThisMonth = date('m',strtotime($start_date_new));
                    $allDates = array();


                   
                    
                    if ($alreadyBlockedDate) {

                        foreach ($alreadyBlockedDate as $k => $v) {
                            $getBlockstarMonth = date('m',strtotime($v));
                            $getblockEndMonth = date('m',strtotime($k));
                            $date = date('d', strtotime($v));

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

                    $admin_month[$i] = implode(',', $allDates);
                }




                $host_month = [];
                $alreadyHostBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', '!=', 1)->where('added_by_sub', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
                for($j=0; $j<6;$j++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

                    $getThisMonth = date('m',strtotime($start_date_new));
                    $allDates = array();

                    if ($alreadyHostBlockedDate) {

                        foreach ($alreadyHostBlockedDate as $k => $v) {
                            $getBlockstarMonth = date('m',strtotime($v));
                            $getblockEndMonth = date('m',strtotime($k));
                            $date = date('d', strtotime($v));

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

                    $host_month[$j] = implode(',', $allDates);
                }

                $sub_month = [];
                $alreadySubBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by_sub', '!=', 0)->where('added_by', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
                for($j=0; $j<6;$j++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

                    $getThisMonth = date('m',strtotime($start_date_new));
                    $allDates = array();

                    if ($alreadySubBlockedDate) {

                        foreach ($alreadySubBlockedDate as $k => $v) {
                            $getBlockstarMonth = date('m',strtotime($v));
                            $getblockEndMonth = date('m',strtotime($k));
                            $date = date('d', strtotime($v));

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

                    $sub_month[$j] = implode(',', $allDates);
                }

                $start_date1 = explode('-',$start_date);
                $end_date1 = explode('-',$end_date);

                    $data = [
                        // 'start_date'=> $start_date,
                        // 'start_day'=>$start_date1[2],
                        // 'start_month'=>$start_date1[1],
                        // 'start_year'=>$start_date1[0],
                        // 'end_date'=>$end_date,
                        // 'end_day'=>$end_date1[2],
                        // 'end_month'=>$end_date1[1],
                        // 'end_year'=>$end_date1[0],          
                        // 'monthWiseData'=>$mo,
                        // 'hostMonthWiseData'=>$host_month,
                        // 'adminMonthWiseData'=>$admin_month,
                        // 'subAdminMonthWiseData'=>$sub_month, 
                        // 'id'=>$input['id'],
                        'alreadySubBlockedDate'=> $alreadySubBlockedDate,
                    ];

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = $data;
                $response['permission'] = $permission;
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        } 
    }


    public function becomeahost_blockDateRange(Request $request){

         $message = 'Date is blocked successfully.';
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {

                $id = $request->id;
                // dd($id);
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                $startDate = new DateTime($from_date);
                $endDate = new DateTime($to_date);

            // Iterate over the date range
            $i = 0;
            $j = 0;
            for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
                $i++;
                $checDate =  $date->format('Y-m-d');
                if (!Booking::where('property_id', $id)->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '<=', $checDate)->whereDate('to_date', '>=', $checDate)->first()) {
                    $j++;
                    if ($checkExist = PropertyBlockDate::where('property_id', $id)->whereDate('block_date', $checDate)->first()) {

                        $checkExist->delete();
                        $response['status'] = true;
                        $message = 'This date is now unblocked.';
                        

                    } else {
                        
                        $insertData = new PropertyBlockDate;
                        $insertData->property_id = $id;
                        $insertData->block_date = $checDate;
                        $insertData->added_by = 0;
                        $insertData->added_by_sub = auth()->id();
                        $insertData->save();
                    } 
                }

            }

                $range = 6;
                $page = $input['page'] ?? 1;
                $start_range = (($range*$page)-6);
                $end_range = $range*$page;

              $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));;
               // dd($start_date);
                $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));

             $alreadySubBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by_sub', '!=', 0)->where('added_by', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
                for($j=0; $j<6;$j++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

                    $getThisMonth = date('m',strtotime($start_date_new));
                    $allDates = array();

                    if ($alreadySubBlockedDate) {

                        foreach ($alreadySubBlockedDate as $k => $v) {
                            $getBlockstarMonth = date('m',strtotime($v));
                            $getblockEndMonth = date('m',strtotime($k));
                            $date = date('d', strtotime($v));

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

                    $sub_month[$j] = implode(',', $allDates);
                }

            $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();                
    
                $response['status'] = true;
                $response['message'] = $message;
                $response['data'] = $alreadySubBlockedDate;
                $response['permission'] = $permission;
                return response()->json($response, 200);
           

                

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }  
    
    }
       
    public function becomeahost_unBlockDateRange(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {

                $id = $request->id;
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                $startDate = new DateTime($from_date);
                $endDate = new DateTime($to_date);

            // Iterate over the date range
            $i = 0;
            $j = 0;
            for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
                $i++;
                $checDate =  $date->format('Y-m-d');
                if (!Booking::where('property_id', $id)->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '<=', $checDate)->whereDate('to_date', '>=', $checDate)->first()) {
                    $j++;
                    PropertyBlockDate::where('property_id', $id)->whereDate('block_date', $checDate)->delete();

                }

            }

                $range = 6;
                $page = $input['page'] ?? 1;
                $start_range = (($range*$page)-6);
                $end_range = $range*$page;

              $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));;
               // dd($start_date);
                $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));

             $alreadySubBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by_sub', '!=', 0)->where('added_by', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
                for($j=0; $j<6;$j++)
                {
                    $allDates = [];
                    $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
                    $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
                    $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

                    $getThisMonth = date('m',strtotime($start_date_new));
                    $allDates = array();

                    if ($alreadySubBlockedDate) {

                        foreach ($alreadySubBlockedDate as $k => $v) {
                            $getBlockstarMonth = date('m',strtotime($v));
                            $getblockEndMonth = date('m',strtotime($k));
                            $date = date('d', strtotime($v));

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

                    $sub_month[$j] = implode(',', $allDates);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
            // if ($i == $j) {
                $response['status'] = true;
                $response['message'] = 'Date is Unblocked successfully.';
                $response['data'] = $alreadySubBlockedDate;
                $response['permissions'] = $permission;
                return response()->json($response, 200);
            // }else{
            //     $response['status'] = true;
            //     $response['message'] = 'Some Date is not Unblocked because property book already.';
            //     return response()->json($response, 200);
            // }

                

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }  
    
    }
    public function becomeahost_typeWiseCategorylist(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
                'type.required' => 'Type',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
                'type'   => 'required',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

                $q = Category::select('id', 'name')->where(['type'=>$request->type]);

                
                $results = $q->orderBy('name', 'asc')->offset($page_no*20)->take(20)->get();
                // dd($results);
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                
                if(isset($results) && !empty($results)){
                     
                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'City Search found.';
                }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

    public function becomeahost_typelist(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }
                // $results = [
                //     "name"=>"",
                //     "name"=>"Aparthotel",
                //     "name"=>"Apartment",
                //     "name"=>"Boat",
                //     "name"=>"Bungalow",
                //     "name"=>"Chalet",
                //     "name"=>"Cottage",
                //     "name"=>"Country house",
                //     "name"=>"Farm stay",
                //     "name"=>"Garage",
                //     "name"=>"House",
                //     "name"=>"Mobile home",
                //     "name"=>"Rent by room",
                //     "name"=>"Residence",
                //     "name"=>"Studio",
                //     "name"=>"Townhouse",
                //     "name"=>"Trullo",
                //     "name"=>"Villa"
                // ];
                 $results = [
                [
                    "name" => "Aparthotel",
                ],
                [
                    "name" => "Apartment",
                ],
                [
                    "name" => "Boat",
                ],
                [
                    "name" => "Bungalow",
                ],
                [
                    "name" => "Chalet",
                ],
                [
                    "name" => "Cottage",
                ],
                [
                    "name" => "Country house",
                ],
                [
                    "name" => "Farm stay",
                ],
                [
                    "name" => "Garage",
                ],
                [
                    "name" => "House",
                ],
                [
                    "name" => "Mobile home",
                ],
                [
                    "name" => "Rent by room",
                ],
                [
                    "name" => "Residence",
                ],
                [
                    "name" => "Studio",
                ],
                [
                    "name" => "Townhouse",
                ],
                [
                    "name" => "Trullo",
                ],
                [
                    "name" => "Villa",
                ]
            ];
                
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                if(isset($results) && !empty($results)){
                     
                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Type Search Not found.';
                }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

     public function becomeahost_countryWiseProvincelist(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
                'country_id.required' => 'Country',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
                'country_id'   => 'required',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

                $q = Province::select('id', 'name')->where(['country_id'=>$request->country_id]);

                // dd($q);
                
                $results = $q->orderBy('name', 'asc')->offset($page_no*20)->take(20)->get();
                // dd($results);
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                
                if(isset($results) && !empty($results)){
                     
                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'City Search found.';
                }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

     public function becomeahost_ProvinceWiseCitylist(Request $request){

        try {
            $input =  $request->all();
            // dd($input);
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
                'province_id.required' => 'Province',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
                'province_id'   => 'required',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

                $q = City::select('id', 'name')->where(['province_id'=>$request->province_id]);

                // dd($q);
                
                $results = $q->orderBy('name', 'asc')->offset($page_no*20)->take(20)->get();
                // dd($results);
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                
                if(isset($results) && !empty($results)){
                     
                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'City Search found.';
                }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 

    public function becomeahost_CityWiseArealist(Request $request){

        try {
            $input =  $request->all();
        
            $this->requestdata = $input;
             $message = [
                'page.required' => 'Page No',
                'city_id.required' => 'City',
            ];
            $validator = Validator::make($input, [
                'page'   => 'required|numeric',
                'city_id'   => 'required',
            ], $message);
            if ($validator->fails()) {
                $response = $this->errorValidation($validator);
            }else {
                if (isset($request->page) && !empty($request->page)) {
                    $page_no = $request->page - 1;
                }else{
                    $page_no = 0;
                }

                $q = Area::select('id', 'name')->where(['city_id'=>$request->city_id]);
                
                $results = $q->orderBy('name', 'asc')->offset($page_no*20)->take(20)->get();
                // dd($results);
                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
                
                if(isset($results) && !empty($results)){
                     
                    $response['status'] = true;
                    $response['data'] = $results;
                    $response['permission'] = $permission;
                }else{
                    $response['status'] = false;
                    $response['message'] = 'City Search found.';
                }
                 

                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    } 
    public function becomeahost_edit_view_Accomodation(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;
                // $data = ExtraService::find($id);

                 $data = Property::with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding','getPropertyHouserule')->find($id);
                // dd($data);
                $amenities = Amenity::where('status',1)->get();
                $extra_services = ExtraService::where('user_id',Auth::user()->id)->where('status',1)->get();
                // ->where(function($q){            
                //     $q->where('user_id',Auth::user()->id);
                //     if(Auth::user()->user_type==5)
                //     {
                //         $q->orWhere('user_id',5)->orWhere('user_id',0);            
                //     }
                // })->get();
                $selected_amenities = PropertyAmenity::where('property_id',$id)->pluck('amenities_id')->toArray();
                $selected_extra_services = PropertyExtraService::where('property_id',$id)->pluck('service_id')->toArray();
                $selected_category = PropertyCategory::where('property_id',$id)->pluck('category_id')->toArray();
                $host_users = User::where('id',Auth::user()->id)->where(['status'=>1,'user_type'=>5])->get();
                $all_categories = Category::where('status',1)->get();
                $country = Country::orderBy('position','desc')->get();
                $province = Province::orderBy('name','asc')->get();
                $buildings = Building::where(['status'=>1])->get();
                $tags = PropertyTag::where('status',1)->get();
                $selected_tags = [];
                $property_count = Property::count();

                if (!$data) {
                    $response['status'] = false;
                    $response['message'] = 'Property not found.';
                    return response()->json($response, 200);
                }

                $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['data'] = [
                                'data'=>$data,
                                'amenities'=>$amenities, 
                                'selected_amenities' => $selected_amenities,
                                 'host_users'=>$host_users,
                                 'all_categories'=>$all_categories,
                                 'country'=>$country,
                                 'province'=>$province,
                                 'tags'=>$tags,
                                 'selected_tags'=>$selected_tags, 
                                 'buildings'=>$buildings, 
                                 'extra_services'=>$extra_services, 
                                 'selected_extra_services' => $selected_extra_services, 
                                 'selected_category'=>$selected_category, 
                                 'property_count'=>$property_count,
                                 
                             ];
                $response['permissions'] = $permission;
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }


     public function becomeahost_user_permissions(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $id = $request->id;

                $user = User::findOrFail($id); 
               
                $data['permissions']=Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
               
                $login_user_data = auth()->user();
                $json = json_encode(array('userid'=>$id));

                $response['status'] = true;
                $response['data'] = $data;
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }  
    
    }

    public function becomeahost_addCategory(Request $request){

        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'name' => 'required|max:190|unique:categories',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $userData = User::where(['user_type' => 5])->first();
                $userId =  $userData->id;

                    $data = new Category;

                    if ($request->file('image')) {
                        $file = $request->file('image');
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath =   'category/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);
                        $data->image = $result['file'];
                    }
                    $data->type = $input['type'];
                    $data->name = $input['name'];
                    $data->status = 1;
                    $data->save();

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Category added successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }  
    }

    public function becomeahost_addAmenity(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
        
            'name' => 'required|max:190|unique:amenities',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try {
                $userData = User::where(['user_type' => 5])->first();
                $userId =  $userData->id;

                    $data = new Amenity;

                    if ($request->file('image')) {
                        $file = $request->file('image');
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath =   'amenity/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);
                        $data->image = $result['file'];
                    }
                    $data->name = $input['name'];
                    $data->status = 1;
                    $data->save();

                    $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();

                $response['status'] = true;
                $response['message'] = 'Amenity added successfully.';
                $response['permission'] = $permission;
                return response()->json($response, 200);

            } catch (\Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }  
    }

    public function becomeahost_ExtraService_List(Request $request)
    {
        try {
            $input =  $request->all();
            $this->requestdata = $input;
            $userData = User::where(['user_type' => 5])->first();
            
            $userId =  $userData->id;

            $results = ExtraService::select('id','name')->where(['status'=>1])->where('user_id',Auth::user()->id)->get();

            if(isset($results) && !empty($results)){
                $response['status'] = true;
                $response['data'] = $results;
            }else{
                $response['status'] = false;
                $response['message'] = 'ExtraService Not found.';
            }
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = 'Somthing went wrong.';
        }
        return response()->json($response, 200);
    }

    public function becomeahost_exportProperties(Request $request)
    {

        $permission =Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->pluck('name')->toArray();
        $response['permission'] = $permission;
        $input = $request->all();
        $validator = Validator::make($request->all(), [
        
            'file_type' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            try{
                $input = $request->all();
                
                $file_type = $input['file_type'];

                // $path = url('/public').'/';
                $path = public_path('/uploads').'/';
                // $path = storage_path('app').'/';
                if($file_type == 'Excel'){
                    $fileName = time().'_properties.xls';
                }else{
                    $fileName = time().'_properties.csv';
                }

                Excel::store(new BulkWarehouseExport($request), 'app/public/' . $fileName);

                $fileUrl = asset('storage/' . $fileName);
            
                $response['status'] = true;
                $response['message'] = 'Successfully export file.';
                $response['data'] = url('download').'/'.$fileName;
                
                return response()->json($response, 200);

            } catch (Exception $e) {
                dd($e);
                $response['status'] = false;
                $response['message'] = 'Something went wrong.';
                return response()->json($response, 200);
            }
        }
    }

}
