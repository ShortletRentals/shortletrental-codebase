<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\Property;
use App\Models\Category;
use App\Models\Building;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\PropertyHouserule;
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
use App\Models\PropertyCategory;
use App\Models\PropertyAmenity;
use App\Models\PropertyExtraService;
use App\Models\Amenity;
use App\Models\Country;
use App\Models\BankData;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BecomeAHostController extends Controller
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


    public function become_a_host()
    {
        // $userData =  Session::get('AuthUserData') ?? null; 
        return view('web.become-a-host');
    }

    public function become_a_host_type()
    {
        $recordData = array();
        $data = ['title'=>$this->lang,'page'=>'homepage','data'=>$recordData];
        return view('web.become_a_host.type_first',$data);
    }

    public function host_category($type = null)
    {
        // dd($type);
        $all_categories = Category::where(['status'=>1, 'type'=>$type])->get();
        $data = ['title'=>$this->lang,'page'=>'homepage','all_categories'=>$all_categories];
        return view('web.become_a_host.host_category',$data);
    }

    public function host_address()
    {
        // dd('inn');
        // dd(Session::get('host_type'));
        $country = Country::orderBy('name','asc')->get();
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address','country'=>$country];
        return view('web.become_a_host.address',$data);
    }

    public function host_guest()
    {
        // $country = Country::orderBy('name','asc')->get();
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address'];
        return view('web.become_a_host.guest',$data);
    }

    public function host_amenity()
    {
        $amenities = Amenity::where('status',1)->get();
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address', 'amenities'=>$amenities];
        return view('web.become_a_host.amenity',$data);
    }

    public function host_max_guest()
    {
        $data = ['title'=>$this->lang,'page'=>'Become A Host Other'];
        $data['buildings'] = Building::where(['status'=>1])->get();
        return view('web.become_a_host.max_guest',$data);
    }

    public function host_title()
    {
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address'];
        return view('web.become_a_host.title',$data);
    }

    public function host_description()
    {
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address'];
        return view('web.become_a_host.description',$data);
    }

    public function host_extra_services()
    {
        $extra_services = ExtraService::where('status',1)->get();
        $data = ['title'=>$this->lang,'page'=>'Become A Host Address', 'extra_services'=>$extra_services];
        return view('web.become_a_host.extra_services',$data);
    }

    public function host_price()
    {
        $data = ['title'=>$this->lang,'page'=>'Become A Host Price'];
        return view('web.become_a_host.price',$data);
    }

    public function host_images()
    {
        $data = ['title'=>$this->lang,'page'=>'Become A Host Price'];
        return view('web.become_a_host.host_images',$data);
    }

    public function host_submit_form(Request $request)
    {
        $message = [];
        $validation = [
            // 'title' => 'required|max:190',
            // 'featured' => 'required',
            // 'free_cancellation' => 'required',
            // 'position' => 'required|unique:properties,position',
        ];
            
        $this->validate($request,$validation,$message);   
        try{
            $userData =  Session::get('AuthUserData') ?? null;
            // dd($userData);
            if(isset($userData) && !empty($userData->data->id)){
                $input = $request->all();
                // dd($input);
                $data = new Property;
                foreach ($request->file("image") as $key => $file) {
                    // dd($key);
                    if($key == 0){
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'property/'.$newFolder; 
                        $result =  fileUploads('s3',$file,$folderPath,false);
                        $data->image = $result['file'];
                    }
                }
                // $code = $this->generateRandomString();
                $code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
                $data->code = $code;
                // $data->reference = $input['reference']??'';
                $data->title = $input['title'];
                $data->type = $input['type'];
                // $data->category = $input['category'];
                $data->host = $userData->data->id;
                $data->featured = $input['featured']??'Yes';
                $data->free_cancellation = $input['free_cancellation']??'Yes';
                $data->description = $input['additional_notes']??'Yes';
                $data->note = $input['note']??'';
                $data->price = $input['price'];
                $data->tax = $input['tax']??null;
                $data->building = $input['building']??null;
                $data->minimum_no_of_nights = $input['minimum_no_of_nights']??null;
                $data->security_deposit_amount = $input['security_deposit_amount']??null;

                $data->max_guest = $input['max_guest']??null;
                $data->cctv = $input['cctv']??null;
                $data->cctv_locations = $input['cctv_locations']??null;
                $data->location_of_television = $input['location_of_television']??null;
                $data->pets_allow = $input['pets_allow']??null;
                $data->response_time = $input['response_time']??null;
                $data->party_rate_commission = $input['party_rate_commission']??null;
                $data->standout_amenities = $input['standout_amenities']??null;
                $data->apartment_responsible = $input['apartment_responsible']??null;
                $data->other_responsibility = $input['other_responsibility']??null;
                $data->estate_located = $input['estate_located']??null;
                $data->estate_name = $input['estate_name']??null;
                $data->landmark = $input['landmark']??null;
                $data->tarred_located = $input['tarred_located']??null;
                // $data->tarred_road = $input['tarred_road']??null;
                $data->home_support = $input['home_support']??null;
                $data->people_allowed_parties = $input['people_allowed_parties']??null;
                // $data->building = $input['building']??null;
                // $data->allow_a_day_booking = $input['allow_a_day_booking']??null;
        
                $data->address = $input['address'];
                $data->latitude = $input['latitude'];
                $data->longitude = $input['longitude'];
                
                $data->status = 0;
                $data->clean_status = 0;
                $data->created_by = 'Host';
                $data->host_property_status = 'None';
                if($data->save()){
                    $property_id = $data->id;
                    $i = 1;
                    foreach ($request->file("image") as $key1 => $file1) {
                        // dd($key);
                        if($key1 > 0){
                            

                            $modelProductImages = new PropertyImage();
                            $extension  = $file1->getClientOriginalExtension();
                            $newFolder  = strtoupper(date('M') . date('Y')) . '/';
                            $folderPath	=	public_path().'/uploads/property/'.$newFolder; 
                            if (!File::exists($folderPath)) {
                                File::makeDirectory($folderPath, $mode = 0777, true);
                            }
                            $productImageName = time() . $i . '-property.' . $extension;
                            $image = $newFolder . $productImageName;
                            if ($file1->move($folderPath, $productImageName)) {
                                $modelProductImages->image = $image;
                                $modelProductImages->image_type = 'IMAGE';
                            }
                            $i++;
                            $modelProductImages->property_id = $property_id;
                            $modelProductImages->save();
                        }
                    }
                    // dd('end');
                    if(isset($input['category']) && !empty($input['category'])){
                        // dd($input['category']);
                        $category = ltrim($input['category'],',');
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
                    $data_bathrooms->bathroom_with_shower = $input['bathrooms_shower'];                     
                    $data_bathrooms->towel_change = $input['towel_change']??0;
                    $data_bathrooms->save();

                    $data_kitchen = new PropertyKitchen;
                    $data_kitchen->property_id = $property_id;
                    $data_kitchen->no_of_kitchens = $input['kitchens'];
                    $data_kitchen->save();

                    $data_bedding = new PropertyBedding;
                    $data_bedding->property_id = $property_id;
                    $data_bedding->bed_linen = $input['beds'];
                    $data_bedding->no_of_television = $input['no_of_television']??null;
                    $data_bedding->network_name = $input['wifi_username']??null;
                    $data_bedding->password = $input['wifi_password']??null;
                    $data_bedding->save();

                    if(isset($input['amenity']) && !empty($input['amenity'])){
                        $amenity1 = ltrim($input['amenity'],',');
                        $all_amenity = explode(',', $amenity1);
                        foreach($all_amenity as $amenity){
                            $data_emenity = new PropertyAmenity;
                            $data_emenity->property_id = $property_id;
                            $data_emenity->amenities_id = $amenity;
                            $data_emenity->save();
                        }
                    }
                    if(isset($input['extra_services']) && !empty($input['extra_services'])){
                        $extra_services1 = ltrim($input['extra_services'],',');
                        $extra_services = explode(',', $extra_services1);
                        foreach($extra_services as $service){
                            $data_service = new PropertyExtraService;
                            $data_service->property_id = $property_id;
                            $data_service->service_id = $service;
                            $data_service->save();
                        }
                        // dd($extra_services);
                    }
                    if(isset($input['house_rule']) && !empty($input['house_rule'])){
                        $data_service = new PropertyHouserule;
                        $data_service->property_id = $property_id;
                        $data_service->name = $input['house_rule'];
                        $data_service->save();
                        // dd($extra_services);
                    }
                    $result['status'] = true;
                    $result['message'] = 'Accommodation added successfully. Admin will approve shortly.';
                    return response()->json($result);
                }
            }
        } catch (Exception $e) {
       
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);
            $result['status'] = false;
            $result['message'] = $e;
            return response()->json($result);
        }
        // return response()->json($result);
    }

    public function host_profile(){
        $country_phone = Country::orderBy('phonecode','asc')->get();
        $country = Country::orderBy('name','asc')->get();
        $userData =  Session::get('AuthUserData') ?? null;
        // dd($userData->data);
        $user_data = $userData->data;
        // dd($user_data);
        $bank_data_exist = BankData::where('user_id',$user_data->id)->first();
        if(isset($bank_data_exist) && !empty($bank_data_exist)){
            $bank_data = $bank_data_exist;
        }else{
            $bank_data = '';
        }
        // dd($bank_data);
        $data = ['title'=>$this->lang,'page'=>'Become A Host Profile','country_phone'=>$country_phone, 'country'=>$country, 'user_data'=>$user_data, 'bank_data'=>$bank_data];
        return view('web.become_a_host.host_profile',$data);
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function store_become_a_host_type(Request $request)
    {
        $input =  $request->all();
        $validator = Validator::make($input, [
            'type' => 'required',
        ]);
    
        // dd($_COOKIE);
        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            // dd($input);
            if(isset($input['type']) && !empty($input['type'] )){
                $array = ['type'=>$input['type']];
                if(Session::put('become_a_host', $array)){
                    $result['status'] = true;
                    $result['message'] = 'Type add successfully.';
                    return response()->json($result, 200);
                }
            }
            // return response()->json($result, 200);
        }
    }

    public function getLatLongByCountry(request $request){
        $input = $request->all();
        $address = $input['address'];
        if(isset($address) && !empty($address)){
            $latlong = getLatLongByCountry($address);
            // dd($latlong);
            if(isset($latlong) && !empty($latlong)){
                // dd($latlong);
                return $latlong;
            }
        }
        return '';
    }

    public function provinceStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'country_id'          => 'required',
            // 'province_id'          => 'required',
            'name'          => 'required|max:190|unique:cities',
        ];
        try{
            $this->validate($request,$validation,$message);
            $data = new Province;

            $data->country_id = $input['country_id'];
            // $data->province_id = $input['province_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            $result['status'] = true;
            $result['message'] = 'Record added successfully.';
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }

    public function cityStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'country_id'          => 'required',
            'province_id'          => 'required',
            'name'          => 'required|max:190|unique:cities',
        ];
        try{
            $this->validate($request,$validation,$message);
            $data = new City;

            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            $result['status'] = true;
            $result['message'] = 'Record added successfully.';
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }

    public function areaStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'country_id'          => 'required',
            'province_id'          => 'required',
            'city_id'          => 'required',
            'name'          => 'required|max:190|unique:areas',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Area;
            $data->country_id = $input['country_id'];
            $data->province_id = $input['province_id'];
            $data->city_id = $input['city_id'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            $result['status'] = true;
            $result['message'] = 'Record added successfully.';
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }

    public function buildingStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name' => 'required|max:190|unique:building',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Building;
            // dd($request->file('image'));
            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            $result['status'] = true;
            $result['message'] = 'Record added successfully.';
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }
}
