<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Content;
use App\Models\ContactUs;
use App\Models\Subscription;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\Blog;
use App\Models\BlogCategory;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Models\AdminSettings;
use App\Models\Offer;
use App\Models\Country;

class StaticPageController extends Controller
{
    protected  $page = 'static_page';
    protected  $lang = 'Static Page';
    protected  $table;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact_us(request $request)
    {
        // dd(session()->all());
        // session()->flush();
        //Gate::authorize('subadmin-section');      
        $settingData = AdminSettings::get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'settingData'=>$settingData];
        return view('web.contact-us',$data);
    }

    public function save_contact_us(Request $request)
    {
        $mesasge = [
            // 'first_name.required' => __("backend.name_required"),
            // 'email.required' => __("backend.email_required"),
            // 'email.email' => __("backend.email_email"),
            // 'country_code.required' => __("backend.country_code_required"),
            // 'mobile.required' => __("backend.mobile_required"),
            // 'mobile.digits_between' => __("backend.mobile_digits_between"),
        ];
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'city' => 'required',
            'state' => 'required',
            // 'country_code' => 'required',
            'mobile_number' => 'required|digits_between:8,12',
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            try {

                $data = new ContactUs();
                $data->name = $input['first_name'].' '.$input['last_name'];
                $data->first_name = $input['first_name'];
                $data->last_name = $input['last_name'];
                $data->email = $input['email'];
                $data->mobile = $input['mobile_number'];
                $data->city = $input['city'];
                $data->state = $input['state'];
                $data->country_code = $input['country_code'] ?? null;
                $data->message = $input['message'] ?? null;
                $data->status = 1;
                $data->save();

                $result['message'] = 'Request submitted successfully.';
                $result['status'] = 1;
                return response()->json($result);

            } catch (Exception $e) {
                $result['message'] = 'Something went wrong.';
                $result['status'] = 0;
                return response()->json($result);
            }
        } else {
            $result['message'] = 'Something went wrong.';
            $result['status'] = 0;
            return response()->json($result);
        }
        return $this->jsonResponse();
    }

    public function subscription(Request $request)
    {
        $mesasge = [
            // 'email.required' => __("backend.email_required"),
            // 'email.email' => __("backend.email_email"),
        ];
        $this->validate($request, [
            'email' => 'required|email',
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            try {

                $checkAlreadyEmail = Subscription::where('email', $input['email'])->first();

                if (!$checkAlreadyEmail) {
                    $data = new Subscription();
                    $data->email = $input['email'];
                    $data->save();
                }


                $result['message'] = 'Request submitted successfully.';
                $result['status'] = 1;
                return response()->json($result);

            } catch (Exception $e) {
                $result['message'] = 'Something went wrong.';
                $result['status'] = 0;
                return response()->json($result);
            }
        } else {
            $result['message'] = 'Something went wrong.';
            $result['status'] = 0;
            return response()->json($result);
        }
        return $this->jsonResponse();
    }

    public function about_us(request $request)
    {
        //Gate::authorize('subadmin-section');      
        $data= ['title'=>$this->lang,'page'=>$this->page];
        return view('web.about-us',$data);
    }

    public function privacy_policy(request $request)
    {
        $recordData = Content::where('slug', 'privacy-policy')->first();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.privacy_policy',$data);
    }

    public function cancellation_policy(request $request)
    {
        $recordData = Content::where('slug', 'cancellation-policy')->first();    
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.cancellation_policy',$data);
    }

    public function how_it_works(request $request)
    {
        $recordData = Content::where('slug', 'how-it-works')->first();    
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.how_it_works',$data);
    }

    public function term_of_use(request $request)
    {
        $recordData = Content::where('slug', 'term-of-use')->first();    
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.term_of_use',$data);
    }

    public function host_policy(request $request)
    {
        $recordData = Content::where('slug', 'host-policy')->first(); 
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.host_policy',$data);
    }

    public function cookies_policy(request $request)
    {
        $recordData = Content::where('slug', 'cookies-policy')->first();    
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return view('web.cookies_policy',$data);
    }

    public function thank_you(request $request)
    {  

        $data= ['title'=>'Thank You','page'=>'thank_you','data'=>$request->all()];
        return view('web.thank_you',$data);
    }

    public function help_center(request $request)
    {
        //Gate::authorize('subadmin-section');      
        $allBlogs = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where('blogs.status',1)->get();
        // dd($allBlogs);
        $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
        $category = BlogCategory::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page, 'allBlogs'=>$allBlogs, 'recentBlogs'=>$recentBlogs, 'category'=>$category];
        return view('web.helpcenter',$data);
    }

    public function help_center_detail(request $request, $slug = null)
    {
        // dd($slug);
        //Gate::authorize('subadmin-section');      
        $blogDetail = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where('blogs.slug',$slug)->first();
        // dd($blogDetail);
        $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
        $category = BlogCategory::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page, 'blogDetail'=>$blogDetail, 'recentBlogs'=>$recentBlogs, 'category'=>$category];
        return view('web.helpcenter-detail',$data);
    }

    public function offer_list(request $request)
    {
        // dd($id);
        //Gate::authorize('subadmin-section');
        $offers = Offer::where(['status'=>1])->with('getDiscountData', 'getOfferAccommodation.getProperty')->get();
        if(isset($offers) && !empty($offers)){
            $data= ['title'=>$this->lang,'page'=>$this->page, 'offers'=>$offers];
            return view('web.offer-list',$data);
        }else{
            redirect(route('web.home'));
        }
    }

    public function help_center_category(request $request, $id = null)
    {
        //Gate::authorize('subadmin-section');
        $allBlogs = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where(['blogs.status'=>1, 'blogs.blog_category'=>$id])->get();
        // dd($blogDetail);
        $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
        $category = BlogCategory::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page, 'allBlogs'=>$allBlogs, 'recentBlogs'=>$recentBlogs, 'category'=>$category];
        return view('web.helpcenter-category',$data);
    }

    public function searchBlog(request $request)
    {
        // dd($request->all());
        $input = $request->all();
        $search_value = $input['search_value'];
        //Gate::authorize('subadmin-section');
        // dd($search_value);
        $allBlogs = Blog::select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category')->where(['blogs.status'=>1])->where('blogs.title', 'LIKE', '%' . $search_value . '%')->get();
        // dd($allBlogs);
        $recentBlogs = Blog::where('status',1)->orderBy('id','desc')->take(5)->get();
        $category = BlogCategory::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page, 'allBlogs'=>$allBlogs, 'recentBlogs'=>$recentBlogs, 'category'=>$category];
        return view('web.helpcenter-search',$data);
    }

    public function show_province($country_id, $province_id = '') {
        if ($country_id) {
          $province = Province::where(['country_id'=>$country_id])->get();
          $data['records'] = $province;
          $data['country_id'] = $country_id;
  
        } else {
          $data['records'] = array();
          $data['country_id'] = '';
        }
        $data['province_id'] = $province_id;
        return view('web.show_province',$data);
    }

    public function show_province_partner($country_id, $province_id = '') {
        if ($country_id) {
          $province = Province::where(['country_id'=>$country_id])->get();
          $data['records'] = $province;
          $data['country_id'] = $country_id;
  
        } else {
          $data['records'] = array();
          $data['country_id'] = '';
        }
        $data['province_id'] = $province_id;
        return view('web.show_province_partner',$data);
    }

    public function show_city($country_id, $province_id, $city_id = '') {
        // dd($country_id, $province_id, $city_id);
        if ($province_id) {
            $province = City::where(['country_id'=>$country_id,'province_id'=>$province_id])->get();
            $data['records'] = $province;
            $data['country_id'] = $country_id;
            $data['province_id'] = $province_id;

        } else {
            $data['records'] = array();
            $data['country_id'] = '';
            $data['province_id'] = '';
        }
        $data['city_id'] = $city_id;
        return view('web.show_city',$data);
    }

    public function show_city_partner($country_id, $province_id, $city_id = '') {
        // dd($country_id, $province_id, $city_id);
        if ($province_id) {
            $province = City::where(['country_id'=>$country_id,'province_id'=>$province_id])->get();
            $data['records'] = $province;
            $data['country_id'] = $country_id;
            $data['province_id'] = $province_id;

        } else {
            $data['records'] = array();
            $data['country_id'] = '';
            $data['province_id'] = '';
        }
        $data['city_id'] = $city_id;
        return view('web.show_city_partner',$data);
    }

    public function show_area($country_id, $province_id, $city_id, $area_id = '') {
        if ($city_id) {
            $province = Area::where(['country_id'=>$country_id,'province_id'=>$province_id,'city_id'=>$city_id])->get();
            $data['records'] = $province;
            $data['country_id'] = $country_id;
            $data['province_id'] = $province_id;
            $data['city_id'] = $city_id;
        } else {
            $data['records'] = array();
            $data['country_id'] = '';
            $data['province_id'] = '';
            $data['city_id'] = '';
        }
        $data['area_id'] = $area_id;
        return view('web.show_area_partner',$data);
    }

    public function show_area_partner($country_id, $province_id, $city_id, $area_id = '') {
        if ($city_id) {
            $province = Area::where(['country_id'=>$country_id,'province_id'=>$province_id,'city_id'=>$city_id])->get();
            $data['records'] = $province;
            $data['country_id'] = $country_id;
            $data['province_id'] = $province_id;
            $data['city_id'] = $city_id;
        } else {
            $data['records'] = array();
            $data['country_id'] = '';
            $data['province_id'] = '';
            $data['city_id'] = '';
        }
        $data['area_id'] = $area_id;
        return view('web.show_area_partner',$data);
    }

    public function get_property_price(Request $request) {
        // dd($request->all());
        $country = $request->country;
        $province = $request->province;
        $city = $request->city;
        $area = $request->area;
        // dd($country, $province, $city, $area);
        if(isset($area) && $area != 'undefined'){
            $price = Property::select(\DB::raw("MIN(price) AS min, MAX(price) AS max"))->leftjoin('property_address','property_address.property_id','=','properties.id')->where(['property_address.country_id'=>$country, 'property_address.province_id'=>$province, 'property_address.city_id'=>$city, 'property_address.area'=>$area])->first();
            if(isset($price) && !empty($price)){
                $min_price = $price->min;
                $max_price = $price->max;
            }else{
                $min_price = '';
                $max_price = '';
            }
        }else{
            $price = Property::select(\DB::raw("MIN(price) AS min, MAX(price) AS max"))->leftjoin('property_address','property_address.property_id','=','properties.id')->where(['property_address.country_id'=>$country, 'property_address.province_id'=>$province, 'property_address.city_id'=>$city])->first();
            if(isset($price) && !empty($price)){
                $min_price = $price->min;
                $max_price = $price->max;
            }else{
                $min_price = '';
                $max_price = '';
            }
        }
        $data['status'] = true;
        $data['min_price'] = $min_price;
        $data['max_price'] = $max_price;
        return $data;
    }

    public function getHashes(Request $request)
    {

        $txnid = $request->txnid;
        $amount  = $request->amount;
        $productinfo = $request->amount;
        $firstname = $request->firstname;
        $email = $request->email;
        $user_credentials  = $request->user_credentials;
        $udf1 = $request->udf1;
        $udf2 = $request->udf2;
        $udf3 = $request->udf3;
        $udf4 = $request->udf4;
        $udf5 = $request->udf5;
        $offerKey = $request->offerKey;
        $cardBin = $request->cardBin;



        // $firstname, $email can be "", i.e empty string if needed. Same should be sent to PayU server (in request params) also.
        $key = 'b9f762c20e9f807c9a2069300e7e2dc5d3abf2951350918ecb50ae7acc8875da';               
        $salt = 'e73a20e4b5b62740a25b8b379db3fdc56878cd940b209f6804e1d1da6d5e38be';
           

        $payhash_str = $key . '|' . $this->checkNull($txnid) . '|' .$this->checkNull($amount)  . '|' .$this->checkNull($productinfo)  . '|' . $this->checkNull($firstname) . '|' . $this->checkNull($email) . '|' . $this->checkNull($udf1) . '|' . $this->checkNull($udf2) . '|' . $this->checkNull($udf3) . '|' . $this->checkNull($udf4) . '|' . $this->checkNull($udf5) . '||||||' . $salt;
        $paymentHash = strtolower(hash('sha512', $payhash_str));
        $arr['payment_hash'] = $paymentHash;

        $cmnNameMerchantCodes = 'get_merchant_ibibo_codes';
        $merchantCodesHash_str = $key . '|' . $cmnNameMerchantCodes . '|default|' . $salt ;
        $merchantCodesHash = strtolower(hash('sha512', $merchantCodesHash_str));
        $arr['get_merchant_ibibo_codes_hash'] = $merchantCodesHash;

        $cmnMobileSdk = 'vas_for_mobile_sdk';
        $mobileSdk_str = $key . '|' . $cmnMobileSdk . '|default|' . $salt;
        $mobileSdk = strtolower(hash('sha512', $mobileSdk_str));
        $arr['vas_for_mobile_sdk_hash'] = $mobileSdk;

        // added code for EMI hash
        $cmnEmiAmountAccordingToInterest= 'getEmiAmountAccordingToInterest';
        $emi_str = $key . '|' . $cmnEmiAmountAccordingToInterest . '|'.$this->checkNull($amount).'|' . $salt;
        $mobileEmiString = strtolower(hash('sha512', $emi_str));
        $arr['emi_hash'] = $mobileEmiString;


        $cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
        $detailsForMobileSdk_str1 = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . $salt ;
        $detailsForMobileSdk1 = strtolower(hash('sha512', $detailsForMobileSdk_str1));
        $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk1;

        //used for verifying payment(optional)
        $cmnVerifyPayment = 'verify_payment';
        $verifyPayment_str = $key . '|' . $cmnVerifyPayment . '|'.$txnid .'|' . $salt;
        $verifyPayment = strtolower(hash('sha512', $verifyPayment_str));
        $arr['verify_payment_hash'] = $verifyPayment;

        if($user_credentials != NULL && $user_credentials != '')
        {
                $cmnNameDeleteCard = 'delete_user_card';
                $deleteHash_str = $key  . '|' . $cmnNameDeleteCard . '|' . $user_credentials . '|' . $salt ;
                $deleteHash = strtolower(hash('sha512', $deleteHash_str));
                $arr['delete_user_card_hash'] = $deleteHash;

                $cmnNameGetUserCard = 'get_user_cards';
                $getUserCardHash_str = $key  . '|' . $cmnNameGetUserCard . '|' . $user_credentials . '|' . $salt ;
                $getUserCardHash = strtolower(hash('sha512', $getUserCardHash_str));
                $arr['get_user_cards_hash'] = $getUserCardHash;

                $cmnNameEditUserCard = 'edit_user_card';
                $editUserCardHash_str = $key  . '|' . $cmnNameEditUserCard . '|' . $user_credentials . '|' . $salt ;
                $editUserCardHash = strtolower(hash('sha512', $editUserCardHash_str));
                $arr['edit_user_card_hash'] = $editUserCardHash;

                $cmnNameSaveUserCard = 'save_user_card';
                $saveUserCardHash_str = $key  . '|' . $cmnNameSaveUserCard . '|' . $user_credentials . '|' . $salt ;
                $saveUserCardHash = strtolower(hash('sha512', $saveUserCardHash_str));
                $arr['save_user_card_hash'] = $saveUserCardHash;

                $cmnPaymentRelatedDetailsForMobileSdk = 'payment_related_details_for_mobile_sdk';
                $detailsForMobileSdk_str = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk . '|' . $user_credentials . '|' . $salt ;
                $detailsForMobileSdk = strtolower(hash('sha512', $detailsForMobileSdk_str));
                $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk;
        }


        // if($udf3!=NULL && !empty($udf3)){
            $cmnSend_Sms='send_sms';
            $sendsms_str=$key . '|' . $cmnSend_Sms . '|' . $udf3 . '|' . $salt;
            $send_sms = strtolower(hash('sha512',$sendsms_str));
            $arr['send_sms_hash']=$send_sms;
        // }


        if ($offerKey!=NULL && !empty($offerKey)) {
            $cmnCheckOfferStatus = 'check_offer_status';
            $checkOfferStatus_str = $key  . '|' . $cmnCheckOfferStatus . '|' . $offerKey . '|' . $salt ;
            $checkOfferStatus = strtolower(hash('sha512', $checkOfferStatus_str));
            $arr['check_offer_status_hash']=$checkOfferStatus;
        }
        
        if ($cardBin!=NULL && !empty($cardBin)) {
            $cmnCheckIsDomestic = 'check_isDomestic';
            $checkIsDomestic_str = $key  . '|' . $cmnCheckIsDomestic . '|' . $cardBin . '|' . $salt ;
            $checkIsDomestic = strtolower(hash('sha512', $checkIsDomestic_str));
            $arr['check_isDomestic_hash']=$checkIsDomestic;
        }
        return json_encode($arr);
    }

    public function checkNull($value) 
    {
        if ($value == null) {
           return '';
        } else {
            return $value;
        }
    }
}
