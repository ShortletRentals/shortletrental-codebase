<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Property;
use App\Models\Category;
use App\Models\Building;
use App\Models\PropertyAddress;
use App\Models\ExtraService;
use App\Models\AccommodationType;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Models\PropertyImage;
use App\Models\PropertyAmenity;
use App\Models\Amenity;
use App\Models\Country;
use App\Models\Booking;
use App\Models\BookingCheckIn;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GuestCheckInController extends Controller
{
    protected  $page = 'home';
    protected  $lang = 'Home';
    protected  $limit = 5;
    protected  $table;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $recordData[''] = '';
        $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData];
        return view('web.guest_check_in.login',$data);
    }

    public function checkin_login_form(request $request)
    {
        // dd($request->all());
        $booking_id = $request->booking_id;
        $last_name = $request->last_name;
        $bookings = Booking::where(['booking_id'=>$booking_id, 'personal_last_name'=>$last_name])->first();
        // dd($bookings);
        if(isset($bookings) && $bookings != null){
            $response['status'] = true;
            $response['booking_token'] = $bookings->booking_token;
        }else{
            $response['status'] = false;
            $response['message'] = 'Invalid login details.';
        }
        return $response;
    }

    public function guest_checkin_first($booking_token = null)
    {
        if(isset($booking_token) && $booking_token != null){
            $bookings = Booking::where(['booking_token'=>$booking_token])->first();
            if(isset($bookings) && $bookings != null){
                $recordData = Booking::select('bookings.*','properties.title','properties.image','property_address.city_id','cities.name as city_name')->where(['bookings.booking_token'=>$booking_token])->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->leftjoin('cities','cities.id','=','property_address.city_id')->first();
                // dd($recordData);
                $organise_data = BookingCheckIn::where('booking_id',$bookings->id)->first();
                $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData, 'organise_data'=>$organise_data];
                return view('web.guest_check_in.check_in_first',$data);
            }else{
                $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
                return view('web.guest_check_in.login',$data);
            }
        }else{
            $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
            return view('web.guest_check_in.login',$data);
        }
    }

    public function guest_checkin_personal($booking_token = null)
    {
        if(isset($booking_token) && $booking_token != null){
            $bookings = Booking::where(['booking_token'=>$booking_token])->first();
            if(isset($bookings) && $bookings != null){
                $recordData = Booking::select('bookings.*','properties.title','properties.image','property_address.city_id','cities.name as city_name')->where(['bookings.booking_token'=>$booking_token])->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->leftjoin('cities','cities.id','=','property_address.city_id')->first();
                // dd($recordData);
                $organise_data = BookingCheckIn::where('booking_id',$bookings->id)->first();
                $country_data = Country::where('status',1)->get();
                $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData, 'organise_data'=>$organise_data, 'country_data'=>$country_data];
                return view('web.guest_check_in.guest_checkin_personal',$data);
            }else{
                $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
                return view('web.guest_check_in.login',$data);
            }
        }else{
            $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
            return view('web.guest_check_in.login',$data);
        }
    }

    public function guest_checkin_documents($booking_token = null)
    {
        if(isset($booking_token) && $booking_token != null){
            $bookings = Booking::where(['booking_token'=>$booking_token])->first();
            if(isset($bookings) && $bookings != null){
                $recordData = Booking::select('bookings.*','properties.title','properties.image','property_address.city_id','cities.name as city_name')->where(['bookings.booking_token'=>$booking_token])->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->leftjoin('cities','cities.id','=','property_address.city_id')->first();
                // dd($recordData);
                $organise_data = BookingCheckIn::where('booking_id',$bookings->id)->first();
                $country_data = Country::where('status',1)->get();
                $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData, 'organise_data'=>$organise_data, 'country_data'=>$country_data];
                return view('web.guest_check_in.guest_checkin_documents',$data);
            }else{
                $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
                return view('web.guest_check_in.login',$data);
            }
        }else{
            $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
            return view('web.guest_check_in.login',$data);
        }
    }

    public function guest_checkin_upload($booking_token = null)
    {
        if(isset($booking_token) && $booking_token != null){
            $bookings = Booking::where(['booking_token'=>$booking_token])->first();
            if(isset($bookings) && $bookings != null){
                $recordData = Booking::select('bookings.*','properties.title','properties.image','property_address.city_id','cities.name as city_name')->where(['bookings.booking_token'=>$booking_token])->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->leftjoin('cities','cities.id','=','property_address.city_id')->first();
                // dd($recordData);
                $organise_data = BookingCheckIn::where('booking_id',$bookings->id)->first();
                $country_data = Country::where('status',1)->get();
                $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData, 'organise_data'=>$organise_data, 'country_data'=>$country_data];
                return view('web.guest_check_in.guest_checkin_upload',$data);
            }else{
                $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
                return view('web.guest_check_in.login',$data);
            }
        }else{
            $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
            return view('web.guest_check_in.login',$data);
        }
    }

    public function guest_checkin_no_camera($booking_token = null)
    {
        if(isset($booking_token) && $booking_token != null){
            $bookings = Booking::where(['booking_token'=>$booking_token])->first();
            if(isset($bookings) && $bookings != null){
                $recordData = Booking::select('bookings.*','properties.title','properties.image','property_address.city_id','cities.name as city_name')->where(['bookings.booking_token'=>$booking_token])->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id')->leftjoin('cities','cities.id','=','property_address.city_id')->first();
                // dd($recordData);
                $organise_data = BookingCheckIn::where('booking_id',$bookings->id)->first();
                $country_phone = Country::orderBy('phonecode','asc')->get();
                $country = Country::orderBy('name','asc')->get();
                $data = ['title'=>$this->lang,'page'=>'Guest Check In','data'=>$recordData, 'organise_data'=>$organise_data, 'country_phone'=>$country_phone, 'country'=>$country];
                return view('web.guest_check_in.guest_checkin_no_camera',$data);
            }else{
                $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
                return view('web.guest_check_in.login',$data);
            }
        }else{
            $data = ['title'=>$this->lang,'page'=>'Guest Check In'];
            return view('web.guest_check_in.login',$data);
        }
    }

    public function check_in_complete(Request $request){
        $booking_token = $request->booking_token;
        $booking_check_in = BookingCheckIn::where('booking_token',$booking_token)->first();
        // dd($booking_check_in);
        $booking_id = Booking::where('booking_token',$booking_token)->pluck('id')->first();
        $file = $request->file('document_image');
        if(isset($booking_check_in) && $booking_check_in != null){
            if (isset($file)) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'property/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
           

                if(BookingCheckIn::where('booking_id',$booking_id)->update([
                    'booking_token' => $request->booking_token,
                    'nationality' => $request->nationality,
                    // 'dob' => $request->dob??null,
                    'document_type' => $request->document_type,
                    // 'upload_type' => $request->upload_type,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_code' => $request->country_code,
                    'phone_number' => $request->phone_number,
                    'country_id' => $request->country_id,
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                    'document_image' => $result['file']
                ])){
                    $response['status'] = true;
                    $response['message'] = 'Data saved successfully.';
                }else{
                    $response['status'] = false;
                }
            }else{
                if(BookingCheckIn::where('booking_id',$booking_id)->update([
                    'booking_token' => $request->booking_token,
                    'nationality' => $request->nationality,
                    // 'dob' => $request->dob??null,
                    'document_type' => $request->document_type,
                    // 'upload_type' => $request->upload_type,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_code' => $request->country_code,
                    'phone_number' => $request->phone_number,
                    'country_id' => $request->country_id,
                    'province_id' => $request->province_id,
                    'city_id' => $request->city_id,
                ])){
                    $response['status'] = true;
                    $response['message'] = 'Data saved successfully.';
                }else{
                    $response['status'] = false;
                }
            }
        }else{
            if (isset($file)) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'property/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
           

                if (isset($file)) {
                    $data = new BookingCheckIn();
                    $data->booking_id = $booking_id;
                    $data->booking_token = $request->booking_token;
                    $data->nationality = $request->nationality;
                    // $data->dob = $request->dob;
                    $data->document_type = $request->document_type;
                    // $data->upload_type = $request->upload_type;
                    $data->first_name = $request->first_name;
                    $data->last_name = $request->last_name;
                    $data->email = $request->email;
                    $data->country_code = $request->country_code;
                    $data->phone_number = $request->phone_number;
                    $data->country_id = $request->country_id;
                    $data->province_id = $request->province_id;
                    $data->city_id = $request->city_id;
                    $data->document_image = $result['file'];

                    if($data->save()){
                        $response['status'] = true;
                        $response['message'] = 'Data saved successfully.';
                    }else{
                        $response['status'] = false;
                        // $response['message'] = 'Data saved successfully.';
                    }
                }else{
                    $response['status'] = false;
                    // $response['message'] = 'Data saved successfully.';
                }
            }else{
                if(isset($arrival_at_the_property) && !empty($departure_at_the_property)){
                    $data = new BookingCheckIn();
                    $data->booking_id = $booking_id;
                    $data->booking_token = $request->booking_token;
                    $data->nationality = $request->nationality;
                    // $data->dob = $request->dob;
                    $data->document_type = $request->document_type;
                    // $data->upload_type = $request->upload_type;
                    $data->first_name = $request->first_name;
                    $data->last_name = $request->last_name;
                    $data->email = $request->email;
                    $data->country_code = $request->country_code;
                    $data->phone_number = $request->phone_number;
                    $data->country_id = $request->country_id;
                    $data->province_id = $request->province_id;
                    $data->city_id = $request->city_id;

                    if($data->save()){
                        $response['status'] = true;
                        $response['message'] = 'Data saved successfully.';
                    }else{
                        $response['status'] = false;
                        // $response['message'] = 'Data saved successfully.';
                    }
                }else{
                    $response['status'] = false;
                    // $response['message'] = 'Data saved successfully.';
                }
            }
        }
        return $response;
    }

    public function organise_your_trip(Request $request){
        // dd($request->all());
        $arrival_at_the_property = $request->arrival_at_the_property;
        $departure_at_the_property = $request->departure_at_the_property;
        $booking_id = $request->booking_id;
        $check_booking = BookingCheckIn::where('booking_id',$booking_id)->first();
        if(isset($check_booking) && $check_booking != null){
            if(BookingCheckIn::where('booking_id',$booking_id)->update([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'arrival_at_the_property' => $request->arrival_at_the_property,
                'departure_at_the_property' => $request->departure_at_the_property,
                'arrival_travelling_by' => $request->arrival_travelling_by??null,
                'arrival_travelling_from' => $request->arrival_travelling_from??null,
                'arrival_at' => $request->arrival_at??null,
                'arrival_travel_company' => $request->arrival_travel_company??null,
                'arrival_number' => $request->arrival_number??null,
                'arrival_time' => $request->arrival_time??null,
                'departure_travelling_by' => $request->departure_travelling_by??null,
                'departure_travelling_from' => $request->departure_travelling_from??null,
                'departure_at' => $request->departure_at??null,
                'departure_travel_company' => $request->departure_travel_company??null,
                'departure_number' => $request->departure_number??null,
                'departure_time' => $request->departure_time??null
            ])){
                $response['status'] = true;
                $response['message'] = 'Data saved successfully.';
            }else{
                $response['status'] = false;
            }
        }else{
            if(isset($arrival_at_the_property) && !empty($departure_at_the_property)){
                $data = new BookingCheckIn();
                $data->booking_id = $booking_id;
                $data->from_date = $request->from_date;
                $data->to_date = $request->to_date;
                $data->arrival_at_the_property = $request->arrival_at_the_property;
                $data->departure_at_the_property = $request->departure_at_the_property;
    
                $data->arrival_travelling_by = $request->arrival_travelling_by??null;
                $data->arrival_travelling_from = $request->arrival_travelling_from??null;
                $data->arrival_at = $request->arrival_at??null;
                $data->arrival_travel_company = $request->arrival_travel_company??null;
                $data->arrival_number = $request->arrival_number??null;
                $data->arrival_time = $request->arrival_time??null;
                $data->departure_travelling_by = $request->departure_travelling_by??null;
                $data->departure_travelling_from = $request->departure_travelling_from??null;
                $data->departure_at = $request->departure_at??null;
                $data->departure_travel_company = $request->departure_travel_company??null;
                $data->departure_number = $request->departure_number??null;
                $data->departure_time = $request->departure_time??null;
                if($data->save()){
                    $response['status'] = true;
                    $response['message'] = 'Data saved successfully.';
                }else{
                    $response['status'] = false;
                    // $response['message'] = 'Data saved successfully.';
                }
            }else{
                $response['status'] = false;
                // $response['message'] = 'Data saved successfully.';
            }
        }
        return $response;
    }

    public function getFilteredProperties(request $request)
    {
        // dd($request->all());
        $properties = Property::where(['status'=>1])->with('getPropertyAddress','getPropertyBedroom','getPropertyImages');

        if (isset($request->list_type) && $request->list_type == 'featured') {
            $properties = $properties->where(['featured'=>'Yes']);
        }

        if (isset($request->property_name) && $request->property_name) {
            $properties->where(function($query) use ($search) {
                $query->where('properties.title', 'LIKE', '%' . $request->property_name . '%');
            });
        }

        if (isset($request->accommodation_type) && $request->accommodation_type) {
            $accommodation_type = AccommodationType::where(['id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('type',$accommodation_type->name);
            }
        }

        if (isset($request->category_type) && $request->category_type) {
            $categoryIds = explode(",",$request->category_type);
            $properties = $properties->whereIn('category',$categoryIds);
        }

        if (isset($request->amenity_type) && $request->amenity_type) {
            $amenityIds = explode(",",$request->amenity_type);
            $propertyIds = PropertyAmenity::where(['amenities_id'=>$amenityIds])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('id',$propertyIds);
        }

        if (isset($request->price_sort) && $request->price_sort) {
            $properties = $properties->orderBy('price', $request->price_sort);
        }
        $recordData['properties'] = $properties->get();
        // dd($recordData['properties']->toArray());
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.filteredProperties',$data);
    }

    public function property_detail(request $request, $id)
    {
        $recordData['propertyData'] = Property::select('properties.*','users.name as host_name')->leftjoin('users','users.id','=','properties.host')->where(['properties.id'=>$id])->with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding','getExtraService.getServiceData')->first();
        // dd($recordData['propertyData']);

        $recordData['propertyImages'] = PropertyImage::where(['property_id'=>$id])->orderBy('id','desc')->get();
        $amenity_id = PropertyAmenity::where(['property_id'=>$id])->pluck('amenities_id')->toArray();
        // dd(count($amenity_id));
        if(count($amenity_id) > 0){
            $recordData['propertyAmenities'] = Amenity::whereIn('id',$amenity_id)->where('status',1)->get();
        }else{
            $recordData['propertyAmenities'] = [];
        }
        // dd($recordData['propertyAmenities']);

        $recordData['category'] = Category::where(['status'=>1])->get();
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.property-detail',$data);
    }

    public function getCity(request $request, $province_id, $city_id='')
    {
        if ($province_id) {
            $province = City::where(['province_id'=>$province_id])->get();
            $data['records'] = $province;
            $data['province_id'] = $province_id;

        } else {
            $data['records'] = array();
            $data['province_id'] = '';
        }
        $data['city_id'] = $city_id;
        return view('web.show_cities',$data);
    }

    public function my_account(Request $request)
    {
        $parms = array();
        $result = ApiCurlMethod('user-profile', $parms, 'Bearer', 'GET');
        // dd($result, '--result');
        // update session name and image
        $userData =  Session::get('AuthUserData') ?? null;

        if ($userData) {
            $userData->data->name = $result->data->name;
            $userData->data->image = $result->data->image;
            session()->put('AuthUserData', $userData);
        }
        // update session name and image
        $country_phone = Country::orderBy('phonecode','asc')->get();
        $country = Country::orderBy('name','asc')->get();
        // dd($result);
        $data = ['title' => __('backend.My_Account'), 'data' => $result, 'country_phone'=>$country_phone,'country'=>$country];
        return view('web.my-account', $data);
    }

    public function update_profile(Request $request)
    {
        // dd($request->all());
        $parms = $request->all();
        $result = ApiCurlMethod('update_profile', $parms, 'Bearer', 'POST');
        // dd($result, '--update');
        return response()->json($result, 200);
    }

    public function update_royalty_form(Request $request)
    {
        // dd($request->all());
        $parms = $request->all();
        $result = ApiCurlMethod('update_loyalty', $parms, 'Bearer', 'POST');
        // dd($result, '--update');
        return response()->json($result, 200);
    }

    public function addToWishList(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('addToWishList', $parms, 'Bearer', 'POST');
        return response()->json($result, 200);
    }

    public function my_card(Request $request)
    {
        // $parms = array();
        // $result = ApiCurlMethod('user-profile', $parms, 'Bearer', 'GET');
        // // update session name and image
        // $userData =  Session::get('AuthUserData') ?? null;
        // $userData->data->name = $result->data->name;
        // $userData->data->image = $result->data->image;
        // session()->put('AuthUserData', $userData);
        // update session name and image
        $parms = array();
        $result = ApiCurlMethod('get_store_card', $parms, 'Bearer', 'GET');   
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.my-cards', $data);
    }

    public function edit_card_form(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('update_card', $parms, 'Bearer', 'POST');
        return response()->json($result, 200);
    }

    public function deleteCard(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('deleteCard', $parms, 'Bearer', 'POST');
        return response()->json($result, 200);
    }

    public function defaultCard(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('defaultCard', $parms, 'Bearer', 'POST');
        return response()->json($result, 200);
    }

    public function my_favorites(Request $request)
    {
        $parms = array();
        $result = ApiCurlMethod('my_favorites', $parms, 'Bearer', 'GET');
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.my_favorite', $data);
    }

    public function my_notifications(Request $request)
    {
        $parms = array();
        $result = ApiCurlMethod('notification-list', $parms, 'Bearer', 'POST');
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.my_notifications', $data);
    }

    public function my_bookings(Request $request)
    {
        $parms = array();
        $result = ApiCurlMethod('getbookings', $parms, 'Bearer', 'GET');
        // dd($result);
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.my_bookings', $data);
    }

    public function filtered_bookings(Request $request)
    {
        $parms = $request->all();
        $url = 'getbookings?status='.$parms['status'];
        $result = ApiCurlMethod($url, $parms, 'Bearer', 'GET');
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.filtered_bookings', $data);
    }

    public function cancelBooking(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('cancelBooking', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }

    public function readAllNotification(Request $request) {
        $parms = $request->all();
        $result = ApiCurlMethod('read-notification', $parms, 'Bearer');
        return response()->json($result);
    }

    public function clearAllNotification(Request $request) {
        // dd('clearAllNotification', $request->all());
        $parms = $request->all();
        $result = ApiCurlMethod('notification-remove', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }

    public function store_card(Request $request)
    {
        $input =  $request->all();
        $validator = Validator::make($input, [
            'card_holder_name' => 'required',
            'card_number' => 'required',
            'month' => 'required|min:2',
            'year' => 'required|min:4',
            'cvv' => 'required|min:3',
        ]);
    
        // dd($_COOKIE);
        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $result = ApiCurlMethod('store-card', $input, 'Bearer', 'POST');
            return response()->json($result, 200);
        }
    }

    public function addToCart(Request $request)
    {
        $input =  $request->all();
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_days' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
            'adultCount' => 'required',
            'childCount' => 'required',
            'infantCount' => 'required',
            'petCount' => 'required',
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $result = ApiCurlMethod('add-cart', $input, 'Bearer', 'POST');
            return response()->json($result);
        }
    }

    public function checkout(Request $request) {
        $parms = array();
        $result = ApiCurlMethod('get-cart', $parms, 'Bearer', 'GET');
        $countryData = Country::where('status', 1)->get();
        $data = ['title' => __('backend.Checkout'), 'data' => $result, 'countryData' => $countryData];
        return view('web.booking', $data);
    }

    public function checkout_form(Request $request) {

        $parms = $request->all();
        $result = ApiCurlMethod('checkout', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }

    public function checkCouponCode(Request $request) {
        $parms = $request->all();
        $result = ApiCurlMethod('checkCouponCode', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }

    public function update_password_form(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('change-password', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }
}
