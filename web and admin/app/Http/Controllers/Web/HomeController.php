<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Notification;
use App\Models\Area;
use App\Models\Property;
use App\Models\Category;
use App\Models\Building;
use App\Models\Rating;
use App\Models\Offer;
use App\Models\Booking;
use App\Models\Province;
use App\Models\Appointment;
use App\Models\PropertyAddress;
use App\Models\PropertyReserveRequest;
use App\Models\ExtraService;
use App\Models\AccommodationType;
use App\Models\EmailTemplateLang;


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
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyBlockDate;
use App\Models\Amenity;
use App\Models\Country;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use DateTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use SoapClient;
use Illuminate\Support\Facades\Cache;
use App\Models\Rate;
use App\Models\AdminSettings;
use App\Models\Discount;
use Helper;
use App\Models\Commission;
use App\Models\CommissionAccommodation;
class HomeController extends Controller
{
    protected  $page = 'home';
    protected  $lang = 'Home';
    protected  $limit = 10;
    protected  $table;

    public function __construct() {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function location()
    {
        $latitude = ($_COOKIE && isset($_COOKIE['lat'])) ? $_COOKIE['lat'] : '25.3548';
        $longitude = ($_COOKIE && isset($_COOKIE['long'])) ? $_COOKIE['long'] : '51.1839';
        return array('latitude' => $latitude, 'longitude' => $longitude);
    }

    public function become_a_partner()
    {
        // $userData =  Session::get('AuthUserData') ?? null; 
        return view('web.become-a-partner');
    }

    public function index(request $request)
    {

        //DB::statement('SET @num := 0;');
        //DB::table('properties')->update(['position' => DB::raw('@num := @num + 1')]);
        //DB::table('properties')->update(['position' => DB::raw('position + 1')]);
        // try{
        //     $client = new SoapClient('http://ws.avantio.com/soap/vrmsInputServices.php?wsdl');
        //     $credentials = array(
        //         "Language" => "EN",
        //         // "UserName" => "delivery@inventcolabs.com",
        //         // "Password" => "Sandeep@123"

        //         "UserName" => "jiten@inventcolabs.com",
        //         "Password" => "Adeyinka@3212"
        //     );
        //     $criteria = array();
        //     $request = array(
        //         "Credentials" => $credentials
        //     );
        //     $result = $client->GetAccommodationCodes($request);
        //     print_r($result);
        // }
        //     catch(SoapFault $e){
        //     echo $e;
        // }
        // die('here');


       // $data = Cache::remember('homepage', 600, function () {

        $location = $this->location();
        $countryId = getCountryIdByLatLong($location['latitude'], $location['longitude']);

        if ($countryId == '252') {
            $countryId = explode(',', '252,160');

        } else {
            $countryId = explode(',', $countryId);
        }



        $getAddressCountryIds = PropertyAddress::whereIn('country_id', $countryId)->pluck('property_id')->toArray();


        // dd($countryId);

        $property = Property::select('properties.*')
        ->where(['properties.status'=>1,'properties.featured'=>'No','properties.luxury'=>'No','properties.rare'=>'No'])
        ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb');


        if (isset($getAddressCountryIds) && !empty($getAddressCountryIds)) {

            /*$featuredPropertyWithPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->where('position', '<=', 15)->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit(10)->get();

            $remainFeaturedProperty = 15 - count($featuredPropertyWithPos);

            $featuredPropertyWithoutPos = Property::select('id','title','type','host','building','price','tax','price_with_taxes','security_deposit_amount','featured','image','max_guest','avg_rating','total_rating','book_type','position','status','created_at')->whereIn('id', $getAddressCountryIds)->where(['featured'=>'Yes', 'status'=>1])->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')->orderBy('position','asc')->limit($remainFeaturedProperty)->get();*/

            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            //->orderBy('properties.position','asc')
            //->orderByRaw('CONVERT(properties.position, SIGNED) desc')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });


            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->whereIn('properties.id', $getAddressCountryIds)
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
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
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            //dd($recordData['featuredProperty']);
            $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))->groupBy('bookings.property_id')->orderBy('total_count', 'asc')->pluck('bookings.property_id')->toArray();

            $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
            if(!empty($getMostBookedIds))
            {
                //$property->whereNotIn('properties.host', $superHostIds);
                $recordData['superHostProperty'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')->limit(10)->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")")->get()->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });
            }
            else
            {
                $recordData['superHostProperty'] = Property::select('properties.*')
                ->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1])
                ->whereIn('properties.host', $superHostIds)->join('users','users.id','=','properties.host')
                ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->limit(10)
                ->orderBy("properties.position","desc")
                ->get()->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });
            }
            $recordData['rareProperty_book_now'] = Property::select('properties.*')->whereIn('properties.id', $getAddressCountryIds)->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')->limit($this->limit)->get()->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });

        } else {
            $recordData['featuredProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at')
            ->where(['properties.featured'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

           


            $recordData['luxuryProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.luxury'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
            ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
            ->limit(10)
            ->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            $recordData['rareProperty'] = Property::select('properties.id','properties.title','properties.type','properties.host','properties.building','properties.price','properties.tax','properties.price_with_taxes','properties.security_deposit_amount','properties.featured','properties.image','properties.max_guest','properties.no_of_bedrooms','properties.avg_rating','properties.total_rating','properties.book_type','properties.position','properties.status','properties.created_at','properties.luxury','properties.rare')
            ->where(['properties.rare'=>'Yes', 'properties.status'=>1])
            ->join('users','users.id','=','properties.host')
            ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
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
                ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->limit(10)
                ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')
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
                ->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')
                ->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')  
                ->limit(10)               
                ->get()->map(function ($query) {
                    $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                    return $query;
                });


            }

            $recordData['rareProperty_book_now'] = Property::select('properties.*')->where(['properties.status'=>1, 'properties.book_type'=>'Book_now'])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb')->limit($this->limit)->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });


        
        }
        //dd($recordData['featuredProperty']);

        $recordData['count_all_properties'] = $property->count();

        $recordData['property'] = $property->orderBy('position','asc')->limit($this->limit)->get();

        $recordData['building'] = Building::where(['status'=>1])->limit($this->limit)->get();
        $recordData['offers'] = Offer::where(['status'=>1])->with('getDiscountData', 'getOfferAccommodation.getProperty')->limit(6)->get();
       // $recordData['limited_category'] = Category::where(['status'=>1])->limit(6)->get();
        $recordData['category'] = Category::where(['status'=>1])->get();
        $recordData['province'] = Province::where(['status'=>1, 'is_website_show'=>1])->orderBy('position', 'asc')->get();
        $recordData['accommodation_types'] = AccommodationType::where(['status'=>1, 'is_home_page_show'=>1])->orderBy('shown_order', 'asc')->get();


        //return ['title'=>$this->lang,'page'=>'homepage','data'=>$recordData];
    //});
        $data = ['data' => $recordData];
    
        return view('web.home',$data);
    }

    public function payment()
    {
        $url = "https://api.paystack.co/transaction/initialize";
        // dd($url);
        $fields = [
          'email' => "lalit.kumawat@inventcolab.com",
          'amount' => "20000",
        ];
      
        $fields_string = http_build_query($fields);
        
        $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
        $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
      
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".$live_key,
          "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        // echo $result;
        $result1_decode = json_decode($result);
       // dd($result1_decode);

        if ($result1_decode->status == true) {
            $reference = $result1_decode->data->reference;
            // dd($reference);

            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$live_key,
                "Cache-Control: no-cache",
            ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
        
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                // echo $response;
                $response_json = json_decode($response);
                $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';

                $curl_list = curl_init();
                curl_setopt_array($curl_list, array(
                  CURLOPT_URL => "https://api.paystack.co/transaction",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$live_key,
                    "Cache-Control: no-cache",
                  ),
                ));
                $response_list = curl_exec($curl_list);
                $err = curl_error($curl_list);
                curl_close($curl_list);

                if ($err) {
                  echo "cURL Error #:" . $err;

                } else {
                  echo '<pre>'; print_r(json_decode($response_list)); die;
                  echo $response_list;
                }
            }

            /*Here the verification api*/

                // $curl = curl_init();
                // curl_setopt_array($curl, array(
                //     CURLOPT_URL => "https://api.paystack.co/transaction/verify/:reference",
                //     CURLOPT_RETURNTRANSFER => true,
                //     CURLOPT_ENCODING => "",
                //     CURLOPT_MAXREDIRS => 10,
                //     CURLOPT_TIMEOUT => 30,
                //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     CURLOPT_CUSTOMREQUEST => "GET",
                //     CURLOPT_HTTPHEADER => array(
                //       "Authorization: Bearer sk_test_a8aa9ef2cf3f3aa01d82cb634a0b83c9748b9ee0",
                //       "Cache-Control: no-cache",
                //     ),
                // ));
                // $response = curl_exec($curl);
                // $err = curl_error($curl);
                // curl_close($curl);

                // if ($err) {
                //     echo "cURL Error #:" . $err;

                // } else {
                //     echo $response;
                // }
            /*End verification api*/
        }
    }

    public function properties(request $request)
    {
        // dd($request->all());
        $location = $this->location();
        $countryId = getCountryIdByLatLong($location['latitude'], $location['longitude']);
        $properties = Property::select('properties.*')->where(['properties.status'=>1])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getUserWeb');
        // $properties = $properties->with(array('getPropertyImagesWeb' => function ($query) use ($request) {
        //     $query->limit(2);
        //     // $query->where('products.buy_one_get_one', 0);
        // }));
        // dd($properties->first()->getPropertyBedroom[0]->no_of_bedrooms);
        if ($location) {
            $properties = $properties->selectRaw("( 6371 * acos( cos( radians(" . $location['latitude'] . ") ) *cos( radians(properties .latitude) ) * cos( radians(properties .longitude) - radians(" . $location['longitude'] . ") ) +  sin( radians(" . $location['latitude'] . ") ) * sin( radians(properties .latitude) ) ) )  AS distance")/*->orderBy('distance', 'asc')*/;
        }
        $list_type = '';
        if (isset($request->list_type) && $request->list_type == 'featured') {
            $properties = $properties->where(['properties.featured'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'superhost') {
            
            $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))
            ->groupBy('bookings.property_id')
            ->orderBy('total_count', 'asc')->pluck('bookings.property_id')->toArray();
           
            $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
            if(!empty($getMostBookedIds))
            {            
                $properties->where(['properties.status'=>1])->whereIn('properties.host', $superHostIds)
                ->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")");
            }

            $list_type = $request->list_type;
        }
        if (isset($request->list_type) && $request->list_type == 'luxury') {
            $properties = $properties->where(['properties.luxury'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'rare') {
            $properties = $properties->where(['properties.rare'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'property') {
            $properties = $properties->where(['properties.rare'=>'No','properties.luxury'=>'No','properties.featured'=>'No']);
            $list_type = $request->list_type;
        }

        if(isset($request->rating))
        {
            if($request->rating==5)
            {
                $properties->where('avg_rating','>',4)->where('avg_rating','<=',5);
            }
            if($request->rating==4)
            {
                $properties->where('avg_rating','>',3)->where('avg_rating','<=',4);
            }
            if($request->rating==3)
            {
                $properties->where('avg_rating','>',2)->where('avg_rating','<=',3);
            }
            if($request->rating==2)
            {
                $properties->where('avg_rating','>',1)->where('avg_rating','<=',2);
            }
            if($request->rating==1)
            {
                $properties->where('avg_rating','>',0)->where('avg_rating','<=',1);
            }
            if($request->rating==0)
            {
             $properties->where('avg_rating','<=',0)->orWhere('avg_rating',null);
            }
        }

        if (isset($request->category_id) && $request->category_id) {
            $getPropertyByCat = PropertyCategory::where('category_id', $request->category_id)->groupBy('property_id')->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id', $getPropertyByCat);
        }

        if (isset($request->accommodation_type) && $request->accommodation_type) {
            $accommodation_type = AccommodationType::where(['id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('properties.type',$accommodation_type->name);
            }
        }

        if (isset($request->province_id) && $request->province_id && $request->province_id != 'all') {
            $propertyIds = PropertyAddress::where(['province_id'=>$request->province_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->city_id) && $request->city_id) {
            $propertyIds = PropertyAddress::where(['city_id'=>$request->city_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->area_id) && $request->area_id) {
            $propertyIds = PropertyAddress::where(['area'=>$request->area_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }
        if(isset($request->checkIn) && $request->checkOut){
            $start_date = date('Y-m-d', strtotime($request->checkIn));
            $end_date = date('Y-m-d', strtotime($request->checkOut));
            $alreadybooking = Booking::whereDate('bookings.from_date', '>=', $start_date)->whereDate('bookings.to_date', '<=', $end_date)
            ->pluck('property_id')->toArray();
            $alreadyBlockedDate = PropertyBlockDate::whereBetween('block_date', [$start_date, $end_date])->pluck('property_id')->toArray();
            $book_properties_ids = array_unique(array_merge($alreadybooking, $alreadyBlockedDate));
            $properties = $properties->whereNotIn('properties.id',$book_properties_ids);
        }
        if(isset($request->no_of_bedrooms) && $request->no_of_bedrooms){
            if($request->no_of_bedrooms != 0){
                $propertyIds = PropertyBedroom::where('no_of_bedrooms',$request->no_of_bedrooms)->pluck('property_id')->toArray();
              //  dd($propertyIds);
                // $propertyIds = PropertyBedroom::where('no_of_bedrooms','>=',$request->no_of_bedrooms)->pluck('property_id')->toArray();
                $properties = $properties->whereIn('properties.id',$propertyIds);
            }
        }
        if(isset($request->adultCount) || $request->childCount || $request->infantCount){
            if($request->adultCount != 0){
                $max_guest = $request->adultCount + $request->childCount + $request->infantCount;
                $properties = $properties->Where('properties.max_guest','>=',$max_guest);
            }
        }
        // $recordData['properties'] = $properties->get();
        $pagination = 20;
        $count_all_properties = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance','asc')->get()->count();
        // dd($count_properties);
        $recordData['properties'] = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance','asc')->take($pagination)->get()->map(function ($query) {
            $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
            return $query;
        });
        // dd($count_all_properties, $recordData['properties']);
        $total_properties = count($recordData['properties']) / $pagination;
        // dd($count_all_properties,count($recordData['properties']), $total_properties);
        // dd($recordData['properties']);
        $recordData['category'] = Category::where(['status'=>1])->get();
        $recordData['accommodation_type'] = AccommodationType::where(['status'=>1])->get();
        $recordData['amenities'] = Amenity::where(['status'=>1])->get();
        if(isset($result['response']) && $result['response'] == true){
            $recordData['userData'] = $result['response']->data;
        }else{
            $recordData['userData'] = '';
        }
        // dd($recordData);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData, 'count_all_properties'=>$count_all_properties, 'total_properties'=>$total_properties, 'list_type'=>$list_type,'request'=> $request->all()];
        return view('web.properties',$data);
    }

    public function properties_map(request $request)
    {
        $location = $this->location();
        $countryId = getCountryIdByLatLong($location['latitude'], $location['longitude']);
        $properties = Property::select('properties.*')->where(['properties.status'=>1])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getUserWeb');
        // $properties = $properties->with(array('getPropertyImagesWeb' => function ($query) use ($request) {
        //     $query->limit(2);
        //     // $query->where('products.buy_one_get_one', 0);
        // }));

        if ($location) {
            $properties = $properties->selectRaw("( 6371 * acos( cos( radians(" . $location['latitude'] . ") ) *cos( radians(properties .latitude) ) * cos( radians(properties .longitude) - radians(" . $location['longitude'] . ") ) +  sin( radians(" . $location['latitude'] . ") ) * sin( radians(properties .latitude) ) ) )  AS distance")->orderBy('distance', 'asc');
        }

        if (isset($request->list_type) && $request->list_type == 'featured') {
            $properties = $properties->where(['properties.featured'=>'Yes']);
            $list_type = $request->list_type;
        }else{
            $list_type = '';
        }

        if (isset($request->category_id) && $request->category_id) {
            $getPropertyByCat = PropertyCategory::where('category_id', $request->category_id)->groupBy('property_id')->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id', $getPropertyByCat);
        }

        if (isset($request->accommodation_type) && $request->accommodation_type) {
            $accommodation_type = AccommodationType::where(['properties.id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('properties.type',$accommodation_type->name);
            }
        }

        if (isset($request->province_id) && $request->province_id && $request->province_id != 'all') {
            $propertyIds = PropertyAddress::where(['properties.province_id'=>$request->province_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('id',$propertyIds);
        }

        if (isset($request->city_id) && $request->city_id) {
            $propertyIds = PropertyAddress::where(['city_id'=>$request->city_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->area_id) && $request->area_id) {
            $propertyIds = PropertyAddress::where(['area'=>$request->area_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }
        // $recordData['properties'] = $properties->get();
        $pagination = 15;
        $count_all_properties = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance','asc')->get()->count();
        // dd($count_properties);
        $recordData['properties'] = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance','asc')->take($pagination)->get()->map(function ($query) {
            $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
            return $query;
        });
        // dd($count_all_properties, $recordData['properties']);
        $total_properties = count($recordData['properties']) / $pagination;
        // dd($count_all_properties,count($recordData['properties']), $total_properties);
        // dd($recordData['properties']);
        $recordData['category'] = Category::where(['status'=>1])->get();
        $recordData['accommodation_type'] = AccommodationType::where(['status'=>1])->get();
        $recordData['amenities'] = Amenity::where(['status'=>1])->get();
        if(isset($result['response']) && $result['response'] == true){
            $recordData['userData'] = $result['response']->data;
        }else{
            $recordData['userData'] = '';
        }

        $initialMarkers = [];

        /*$initialMarkers = [
            [
                'position' => [
                    'lat' => 28.625485,
                    'lng' => 79.821091
                ],
                'label' => [ 'color' => 'white', 'text' => 'P1' ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => 28.625293,
                    'lng' => 79.817926
                ],
                'label' => [ 'color' => 'white', 'text' => 'P2' ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => 28.625182,
                    'lng' => 79.81464
                ],
                'label' => [ 'color' => 'white', 'text' => 'P3' ],
                'draggable' => false
            ]
        ];*/

        foreach ($recordData['properties'] as $key => $value) {

            if (count($value->getPropertyBedroom)) {
                $totalBed = $value->getPropertyBedroom[0]->no_of_bedrooms;

            } else {
                $totalBed = 0;
            }
            $address = '';

            if (isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity) {
                $address = $value->getPropertyAddress[0]->getPropertyCity->name;
            }
            $url = url("/property-detail")."/".$value->id;

            if ($value->latitude && $value->longitude) {
                $html = '<div id="multiHouseContainer">
                    <div style="cursor: pointer">
                        <a href="'.$url.'" target="_blank">
                            <div class="wrapper-multihouse-marker">
                                <div class="multihouse-marker-left">
                                    <img src="'.$value->image.'">
                                </div>
                                <div class="multihouse-marker-right">
                                    <div>
                                        <div class="info_alojamiento">
                                            <span>
                                              <img src="'.url("public/assets/web/img/shortlet/account-user-white.svg").'" alt="">'.$value->max_guest.'</span>
                                            <span>
                                              <img src="'.url("public/assets/web/img/shortlet/bed-white.svg").'" alt="">'.$totalBed.'</span>
                                        </div>
                                        <div class="map-cabecera">
                                            <span class="name">'.ucfirst($value->title).'</span>
                                            <div>
                                              <span class="type">
                                                <span class="tagSubCabecera pobl">'.$address.' - </span>
                                                <span class="tagSubCabecera tipo">'.$value->type.'</span>
                                              </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="map-table">
                                        <div class="map-column">
                                            <label class="text_desde">from</label>
                                            <label class="precio_result">NGN'.$value->price.'</label>
                                            <label class="res_post">/night</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>';

                $initialMarkers[$key]['position']['lat'] = (float)$value->latitude;
                $initialMarkers[$key]['position']['lng'] = (float)$value->longitude;
                // $initialMarkers[$key]['label'] = [ 'color' => 'white', 'text' => 'P3' ];
                $initialMarkers[$key]['icon'] = url('public/assets/web/img/logo-icon.png');
                $initialMarkers[$key]['draggable'] = false;
                $initialMarkers[$key]['html'] = $html;
            }
        }


        /*$html = `<div id="multiHouseContainer">
                    <div style="cursor: pointer" onclick="window.open('https://www.shortletrentals.com/rentals/studio-lagos-comfy-studio-apartment-with-convertible-snooker-and-tennis-oregun-ikeja-406307.html', '_self');">
                        <div class="wrapper-multihouse-marker">
                            <div class="multihouse-marker-left">
                                <img src="https://www.shortletrentals.com/rentals/fotos/2/166732197444064673c34f02049cbae3252833b480/big1667321975e0c63aaac4bc5ff77db910b6ef42aac0.jpg">
                            </div>
                            <div class="multihouse-marker-right">
                                <div>
                                    <div class="info_alojamiento">
                                        <span>
                                          <i class="fa fa-user" aria-hidden="true"></i>2 </span>
                                        <span>
                                          <i class="fa fa-file-text-o" aria-hidden="true"></i>1 </span>
                                    </div>
                                    <div class="map-cabecera">
                                        <span class="name">Comfy studio apartment with convertible snooker and tennis | oregun ikeja</span>
                                        <div>
                                          <span class="type">
                                            <span class="tagSubCabecera pobl">Lagos - </span>
                                            <span class="tagSubCabecera tipo">Studio</span>
                                          </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="map-table">
                                    <div class="map-column">
                                        <label class="text_desde">from</label>
                                        <label class="precio_result">NGN30,000</label>
                                        <label class="res_post">/night</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;*/
        // dd($initialMarkers);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData, 'location'=>$location, 'count_all_properties'=>$count_all_properties, 'total_properties'=>$total_properties, 'list_type'=>$list_type, 'initialMarkers'=>$initialMarkers];
        return view('web.properties_map',$data);
    }

    public function getFilteredProperties(request $request)
    {
       // dd($request->all());
        $location = $this->location();
        $properties = Property::select('properties.*')->where(['properties.status'=>1])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getUserWeb');

        if ($location) {
            $properties = $properties->selectRaw("( 6371 * acos( cos( radians(" . $location['latitude'] . ") ) *cos( radians(properties.latitude) ) * cos( radians(properties.longitude) - radians(" . $location['longitude'] . ") ) +  sin( radians(" . $location['latitude'] . ") ) * sin( radians(properties.latitude) ) ) )  AS distance");
        }

        if (isset($request->list_type) && $request->list_type == 'featured' && $request->featured != 'undefined') {
            if (isset($request->featured) && $request->featured && $request->featured != 'undefined') {
                $properties = $properties->where('properties.featured',$request->featured);
            }else{
                $properties = $properties->where(['properties.featured'=>'Yes']);
            }
        }else{
            if (isset($request->featured) && $request->featured && $request->featured != 'undefined') {
                $properties = $properties->where('properties.featured', $request->featured);
            }
        }

        if (isset($request->star) && $request->star && $request->star != 'undefined') {
            $properties = $properties->where('properties.avg_rating', '>=', $request->star);
        }

        if (isset($request->property_name) && $request->property_name) {
            $properties->where(function($query) use ($request) {
                $query->where('properties.title', 'LIKE', '%' . $request->property_name . '%');
                $query->orWhere('properties.code', 'LIKE', '%' . $request->property_name . '%');
            });
        }

        if (isset($request->accommodation_type) && !empty($request->accommodation_type)) {
            $accommodation_type = AccommodationType::where(['id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('properties.type',$accommodation_type->name);
            }
        }

        if (isset($request->category_type) && !empty($request->category_type)) {
            $categoryIds = explode(",",$request->category_type);
            $getPropertyByCat = PropertyCategory::whereIn('category_id', $categoryIds)->groupBy('property_id')->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id', $getPropertyByCat);
        }

        if (isset($request->amenity_type) && !empty($request->amenity_type)) {
            $amenityIds = explode(",",$request->amenity_type);
            $propertyIds = PropertyAmenity::where(['amenities_id'=>$amenityIds])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->bedroom) && !empty($request->bedroom) && $request->bedroom != 'undefined') {
            if ($request->bedroom >= 5) {
                $propertyIds = PropertyBedroom::where('no_of_bedrooms', '>=', $request->bedroom)->pluck('property_id')->toArray();
            } else {
                $propertyIds = PropertyBedroom::where('no_of_bedrooms', '=', $request->bedroom)->pluck('property_id')->toArray();
            }
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->bathroom) && !empty($request->bathroom) && $request->bathroom != 'undefined') {
            if ($request->bathroom >= 5) {
                $propertyIds = PropertyBathroom::where('toilets', '>=', $request->bathroom)->pluck('property_id')->toArray();
            } else {
                $propertyIds = PropertyBathroom::where('toilets', '=', $request->bathroom)->pluck('property_id')->toArray();
            }
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->price_sort) && !empty($request->price_sort)) {
            if ($request->price_sort == 'name_asc' || $request->price_sort == 'name_desc') {
                $properties = $properties->orderBy('title', str_replace('name_', '', $request->price_sort));
            }
            if ($request->price_sort == 'max_guest') { 
                $properties = $properties->orderBy('properties.max_guest', 'asc');
            }
            if ($request->price_sort == 'asc') {
               // dd($request->price_sort);
                //$properties = $properties->orderBy('properties.price', 'asc');

                 /*$direction = 'asc';
    
                $properties->orderByRaw("price {$direction}");*/
            }
            if ($request->price_sort == 'desc') {
                //$properties = $properties->orderBy('properties.price', 'desc');

                /*$direction = 'desc';
    
                $properties->orderByRaw("price {$direction}");*/
            }
        }else{
            $properties = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance', 'asc');
        }


        if (isset($request->list_type) && $request->list_type == 'featured') {
            $properties = $properties->where(['properties.featured'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'superhost') {
            if (empty($request->price_sort)) {
                $getMostBookedIds = Booking::select(\DB::raw("COUNT(bookings.id) AS total_count, bookings.property_id"))
                ->groupBy('bookings.property_id')
                ->orderBy('total_count', 'asc')->pluck('bookings.property_id')->toArray();
               
                $superHostIds = User::where('is_super_host', 'Yes')->pluck('id')->toArray();
                if(!empty($getMostBookedIds))
                {            
                    $properties->where(['properties.status'=>1])->whereIn('properties.host', $superHostIds)
                    ->orderByRaw("field(properties.id,".implode(',',$getMostBookedIds).")");
                }
            }

            $list_type = $request->list_type;
        }
        if (isset($request->list_type) && $request->list_type == 'luxury') {
            $properties = $properties->where(['properties.luxury'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'rare') {
            $properties = $properties->where(['properties.rare'=>'Yes']);
            $list_type = $request->list_type;
        }

        if (isset($request->list_type) && $request->list_type == 'property') {
            $properties = $properties->where(['properties.rare'=>'No','properties.luxury'=>'No','properties.featured'=>'No']);
            $list_type = $request->list_type;
        }

        if(isset($request->rating) && !empty($request->rating))
        {
            if($request->rating==5)
            {
                $properties->where('avg_rating','>',4)->where('avg_rating','<=',5);
            }
            if($request->rating==4)
            {
                $properties->where('avg_rating','>',3)->where('avg_rating','<=',4);
            }
            if($request->rating==3)
            {
                $properties->where('avg_rating','>',2)->where('avg_rating','<=',3);
            }
            if($request->rating==2)
            {
                $properties->where('avg_rating','>',1)->where('avg_rating','<=',2);
            }
            if($request->rating==1)
            {
                $properties->where('avg_rating','>',0)->where('avg_rating','<=',1);
            }
            if($request->rating==0)
            {
                $properties->where('avg_rating','<=',0)->orWhere('avg_rating',null);
            }
        }

        if (isset($request->category_id) && !empty($request->category_id)) {
            $getPropertyByCat = PropertyCategory::where('category_id', $request->category_id)->groupBy('property_id')->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id', $getPropertyByCat);
        }

        if (isset($request->accommodation_type) && !empty($request->accommodation_type)) {
            $accommodation_type = AccommodationType::where(['id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('properties.type',$accommodation_type->name);
            }
        }

        if (isset($request->province_id) && !empty($request->province_id) && $request->province_id != 'all') {
            $propertyIds = PropertyAddress::where(['province_id'=>$request->province_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->city_id) && !empty($request->city_id)) {
            $propertyIds = PropertyAddress::where(['city_id'=>$request->city_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->area_id) && !empty($request->area_id)) {
            $propertyIds = PropertyAddress::where(['area'=>$request->area_id])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }
        if(isset($request->checkIn) && $request->checkOut){
            $start_date = date('Y-m-d', strtotime($request->checkIn));
            $end_date = date('Y-m-d', strtotime($request->checkOut));
            $alreadybooking = Booking::whereDate('bookings.from_date', '>=', $start_date)->whereDate('bookings.to_date', '<=', $end_date)
            ->pluck('property_id')->toArray();
            $alreadyBlockedDate = PropertyBlockDate::whereBetween('block_date', [$start_date, $end_date])->pluck('property_id')->toArray();
            $book_properties_ids = array_unique(array_merge($alreadybooking, $alreadyBlockedDate));
            $properties = $properties->whereNotIn('properties.id',$book_properties_ids);
        }
        if(isset($request->no_of_bedrooms) && !empty($request->no_of_bedrooms) && $request->no_of_bedrooms != 'undefined'){
            if($request->no_of_bedrooms != 0){
                $propertyIds = PropertyBedroom::where('no_of_bedrooms',$request->no_of_bedrooms)->pluck('property_id')->toArray();
                $properties = $properties->whereIn('properties.id',$propertyIds);
            }
        }
        if(isset($request->adultCount) || $request->childCount || $request->infantCount){
            if($request->adultCount != 0){
                $max_guest = $request->adultCount + $request->childCount + $request->infantCount;
                $properties = $properties->where('properties.max_guest','>=',$max_guest);
            }
        }

        $count = $properties->count();
        $page = $request->last_record_value/20;
        $page_no = $page - 1;
        
        /*$recordData['properties'] = $properties->offset($page_no*20)->take(20)->get()->map(function ($query) {
            $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
            return $query;
        });*/
        if (isset($request->price_sort) && ($request->price_sort == 'asc' || $request->price_sort == 'desc')) {
            // Retrieve properties with pagination but without sorting
            $properties = $properties->get();
            
            // Sort the retrieved properties based on the price attribute
            $properties = $properties->sortBy(function ($property2) use ($request) {
                $price2 = $property2->getPriceAttribute($property2->price);
                $direction = $request->price_sort === 'asc' ? 1 : -1;
                return $price2 * $direction;
            });
        } else {
            // Retrieve properties with pagination and default sorting
            $properties = $properties->offset($page_no * 20)->take(20)->get();
        }

        // Transform the properties by setting relations and other modifications
        $recordData['properties'] = $properties->map(function ($property) {
            $property->setRelation('getPropertyImages', $property->getPropertyImages->take(2));
            return $property;
        });

        
        $exact_count = count($recordData['properties']);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        return ['status'=>true, 'message'=>view('web.filteredProperties',$data)->toHtml(), 'count'=>$count, 'exact_count'=>$exact_count];
    }


    public function getHomeProperties(request $request)
    {
        // dd($request->all());
        $location = $this->location();
        // $properties = Property::select('*')->where(['status'=>1])->with('getPropertyAddress','getPropertyBedroom','getPropertyImages','getUserWeb');
        $properties = Property::select('properties.*')/*->inRandomOrder()*/->where(['properties.status'=>1])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getUserWeb');

        if ($location) {
            $properties = $properties->selectRaw("( 6371 * acos( cos( radians(" . $location['latitude'] . ") ) *cos( radians(properties.latitude) ) * cos( radians(properties.longitude) - radians(" . $location['longitude'] . ") ) +  sin( radians(" . $location['latitude'] . ") ) * sin( radians(properties.latitude) ) ) )  AS distance")/*->orderBy('distance', 'asc')*/;
        }

        if (isset($request->list_type) && $request->list_type == 'featured') {
            if (isset($request->featured) && $request->featured && $request->featured != 'undefined') {
                $properties = $properties->where('properties.featured',$request->featured);
            }else{
                $properties = $properties->where(['properties.featured'=>'Yes']);
            }
        }else{
            if (isset($request->featured) && $request->featured && $request->featured != 'undefined') {
                $properties = $properties->where('properties.featured',$request->featured);
            }
        }

        if (isset($request->star) && $request->star) {
            $properties = $properties->where('properties.avg_rating', '>=', $request->star);
        }

        if (isset($request->property_name) && $request->property_name) {
            $properties->where(function($query) use ($request) {
                $query->where('properties.title', 'LIKE', '%' . $request->property_name . '%');
                $query->orWhere('properties.code', 'LIKE', '%' . $request->property_name . '%');
            });
        }

        if (isset($request->accommodation_type) && $request->accommodation_type) {
            $accommodation_type = AccommodationType::where(['id'=>$request->accommodation_type])->first();

            if ($request->accommodation_type != 'all') {
                $properties = $properties->where('properties.type',$accommodation_type->name);
            }
        }

        if (isset($request->category_type) && $request->category_type) {
            $categoryIds = explode(",",$request->category_type);
            $getPropertyByCat = PropertyCategory::whereIn('category_id', $categoryIds)->groupBy('property_id')->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id', $getPropertyByCat);
            // $properties = $properties->whereIn('category',$categoryIds);
        }

        if (isset($request->amenity_type) && $request->amenity_type) {
            $amenityIds = explode(",",$request->amenity_type);
            $propertyIds = PropertyAmenity::where(['amenities_id'=>$amenityIds])->pluck('property_id')->toArray();
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->bedroom) && $request->bedroom && $request->bedroom != 'undefined') {
            // dd($request->bedroom);
            if ($request->bedroom >= 5) {
                $propertyIds = PropertyBedroom::where('no_of_bedrooms', '>=', $request->bedroom)->pluck('property_id')->toArray();
            } else {
                $propertyIds = PropertyBedroom::where('no_of_bedrooms', '=', $request->bedroom)->pluck('property_id')->toArray();
            }
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->bathroom) && $request->bathroom && $request->bathroom != 'undefined') {
            if ($request->bathroom >= 5) {
                $propertyIds = PropertyBathroom::where('toilets', '>=', $request->bathroom)->pluck('property_id')->toArray();
            } else {
                $propertyIds = PropertyBathroom::where('toilets', '=', $request->bathroom)->pluck('property_id')->toArray();
            }
            $properties = $properties->whereIn('properties.id',$propertyIds);
        }

        if (isset($request->price_sort) && !empty($request->price_sort)) {

            if ($request->price_sort == 'name_asc' || $request->price_sort == 'name_desc') {
                $properties = $properties->orderBy('title', str_replace('name_', '', $request->price_sort));
                // $properties = $properties->orderBy('title', str_replace('name_', '', $request->price_sort));
            }else if ($request->price_sort == 'max_guest') { 
                $properties = $properties->orderBy('properties.max_guest', 'asc');
            }else if ($request->price_sort == 'asc') {
                $properties = $properties->orderBy('properties.price', 'asc');
            } else if ($request->price_sort == 'desc') {
                $properties = $properties->orderBy('properties.price', 'desc');
            }
        }else{
           // dd($request->price_sort);
            $properties = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance', 'asc');
        }
        $count = count($properties->get());
        $recordData['properties'] = $properties->take($request->last_record_value)->get()->map(function ($query) {
            $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
            return $query;
        });
        $exact_count = count($recordData['properties']);
        // dd($count, $recordData['properties']);
        //$recordData['properties'] = $properties->get();
        // dd(count($recordData['properties']));
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
        // return view('web.filteredProperties',$data);
        return ['status'=>true, 'message'=>view('web.filteredProperties',$data)->toHtml(), 'count'=>$count, 'exact_count'=>$exact_count];
    }


    

    public function getFilteredMapProperties(request $request)
    {
        try{ 
            //dd($request->all());
            $location = $this->location();
            $properties = Property::select('properties.*')->where(['properties.status'=>1])->join('users','users.id','=','properties.host')->with('getPropertyAddress','getPropertyBedroom','getUserWeb');

            if ($location) {
                $properties = $properties->selectRaw("( 6371 * acos( cos( radians(" . $location['latitude'] . ") ) *cos( radians(properties.latitude) ) * cos( radians(properties.longitude) - radians(" . $location['longitude'] . ") ) +  sin( radians(" . $location['latitude'] . ") ) * sin( radians(properties.latitude) ) ) )  AS distance")/*->orderBy('distance', 'asc')*/;
            }

            if (isset($request->list_type) && $request->list_type == 'featured') {
                $properties = $properties->where(['featured'=>'Yes']);
            }

            if (isset($request->star) && $request->star) {
                $properties = $properties->where('avg_rating', '>=', $request->star);
            }

            if (isset($request->property_name) && $request->property_name) {
                $properties->where(function($query) use ($request) {
                    $query->where('properties.title', 'LIKE', '%' . $request->property_name . '%');
                    $query->orWhere('properties.code', 'LIKE', '%' . $request->property_name . '%');
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
                $getPropertyByCat = PropertyCategory::whereIn('category_id', $categoryIds)->groupBy('property_id')->pluck('property_id')->toArray();
                $properties = $properties->whereIn('id', $getPropertyByCat);
                // $properties = $properties->whereIn('category',$categoryIds);
            }

            if (isset($request->amenity_type) && $request->amenity_type) {
                $amenityIds = explode(",",$request->amenity_type);
                $propertyIds = PropertyAmenity::where(['amenities_id'=>$amenityIds])->pluck('property_id')->toArray();
                $properties = $properties->whereIn('id',$propertyIds);
            }

            if (isset($request->bedroom) && $request->bedroom && $request->bedroom != 'undefined') {
                // dd($request->bedroom);
                if ($request->bedroom >= 5) {
                    $propertyIds = PropertyBedroom::where('no_of_bedrooms', '>=', $request->bedroom)->pluck('property_id')->toArray();

                } else {
                    $propertyIds = PropertyBedroom::where('no_of_bedrooms', '=', $request->bedroom)->pluck('property_id')->toArray();
                }
                // if ($request->bedroom >=5) {

                // } else {
                //     $propertyIds = PropertyBedroom::where('no_of_bedrooms', $request->bedroom)->pluck('property_id')->toArray();
                // }
                $properties = $properties->whereIn('id',$propertyIds);
            }

            if (isset($request->bathroom) && $request->bathroom && $request->bathroom != 'undefined') {

                if ($request->bathroom >= 5) {
                    $propertyIds = PropertyBathroom::where('toilets', '>=', $request->bathroom)->pluck('property_id')->toArray();

                } else {
                    $propertyIds = PropertyBathroom::where('toilets', '=', $request->bathroom)->pluck('property_id')->toArray();
                }

                $properties = $properties->whereIn('id',$propertyIds);
            }

            if (isset($request->price_sort) && !empty($request->price_sort)) {
                if ($request->price_sort == 'name_asc' || $request->price_sort == 'name_desc') {
                    $properties = $properties->orderBy('title', str_replace('name_', '', $request->price_sort));
                }else if ($request->price_sort == 'max_guest') { 
                    $properties = $properties->orderBy('max_guest', 'asc');
                }else if ($request->price_sort == 'asc') {
                    $properties = $properties->orderBy('properties.price', 'asc');
                } else if ($request->price_sort == 'desc') {
                    $properties = $properties->orderBy('properties.price', 'desc');
                }
            }else{
                $properties = $properties->orderBy(DB::raw('ISNULL(properties.position), properties.position'), 'ASC')->orderBy('distance', 'asc');
            }
            $count = count($properties->get());
            $recordData['properties'] = $properties->take($request->last_record_value)->get()->map(function ($query) {
                $query->setRelation('getPropertyImages', $query->getPropertyImages->take(2));
                return $query;
            });

            $initialMarkers = [];
            if (!empty($recordData['properties']) && count($recordData['properties']) > 0) {
                foreach ($recordData['properties'] as $key => $value) {

                    if (count($value->getPropertyBedroom)) {
                        $totalBed = $value->getPropertyBedroom[0]->no_of_bedrooms;

                    } else {
                        $totalBed = 0;
                    }
                    $address = '';

                    if (isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity) {
                        $address = $value->getPropertyAddress[0]->getPropertyCity->name;
                    }
                    $url = url("/property-detail")."/".$value->id;

                    if ($value->latitude && $value->longitude) {
                        $html = '<div id="multiHouseContainer">
                            <div style="cursor: pointer">
                                <a href="'.$url.'" target="_blank">
                                    <div class="wrapper-multihouse-marker">
                                        <div class="multihouse-marker-left">
                                            <img src="'.$value->image.'">
                                        </div>
                                        <div class="multihouse-marker-right">
                                            <div>
                                                <div class="info_alojamiento">
                                                    <span>
                                                      <img src="'.url("public/assets/web/img/shortlet/account-user-white.svg").'" alt="">'.$value->max_guest.'</span>
                                                    <span>
                                                      <img src="'.url("public/assets/web/img/shortlet/bed-white.svg").'" alt="">'.$totalBed.'</span>
                                                </div>
                                                <div class="map-cabecera">
                                                    <span class="name">'.ucfirst($value->title).'</span>
                                                    <div>
                                                      <span class="type">
                                                        <span class="tagSubCabecera pobl">'.$address.' - </span>
                                                        <span class="tagSubCabecera tipo">'.$value->type.'</span>
                                                      </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="map-table">
                                                <div class="map-column">
                                                    <label class="text_desde">from</label>
                                                    <label class="precio_result">NGN'.$value->price.'</label>
                                                    <label class="res_post">/night</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>';

                        $initialMarkers[$key]['position']['lat'] = (float)$value->latitude;
                        $initialMarkers[$key]['position']['lng'] = (float)$value->longitude;
                        // $initialMarkers[$key]['label'] = [ 'color' => 'white', 'text' => 'P3' ];
                        $initialMarkers[$key]['icon'] = url('public/assets/web/img/logo-icon.png');
                        $initialMarkers[$key]['draggable'] = false;
                        $initialMarkers[$key]['html'] = $html;
                    }
                }
            }
            $exact_count = count($recordData['properties']);
            $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData];
            return ['status'=>true, 'message'=>view('web.filteredProperties',$data)->toHtml(), 'count'=>$count, 'initialMarkers'=>$initialMarkers, 'exact_count'=>$exact_count];
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = "Something went wrong!";
            $response['data'] = null;
            return response()->json($response, 200);
        }
    }

    public function property_detail(request $request, $id)
    {
        /*$alreadybooking = Booking::where('property_id',$id)
                            ->whereBetween('created_at',[(Carbon::now()->subMonth(6)), Carbon::now()])
                            ->pluck('from_date','to_date')->toArray();*/

        $auth_user = Session::get('AuthUserData');


        if (!$auth_user) {  
            //Guest User
            $ipaddress = $_SERVER['REMOTE_ADDR'];
            //check remote address exist

            if ($ipaddress) {
                $data = json_decode(checkGuestLogin($ipaddress));

                if ($data->status == true) {
                    Session::put('is_guest', '1');
                    Session::put('AuthUserData', $data);
                }
            }
            //End
        } else {
            $parms = array();
            $result = ApiAuthValid('user-profile', $parms, 'Bearer', 'GET');

            //dd($result);

            if ($result['httpcode'] == 401) {
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                //check remote address exist

                if ($ipaddress) {
                    $data = json_decode(checkGuestLogin($ipaddress));

                    if ($data->status == true) {
                        Session::put('is_guest', '1');
                        Session::put('AuthUserData', $data);
                    }
                }
            }
            else
            {
                if(isset($result['response']->status) && $result['response']->status == true)
                {
                    if($result['response']->data->is_guest ==1  && $result['response']->data->ip_address==null)
                    {
                       
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                        //check remote address exist
        
                        if ($ipaddress) {
                            $data = json_decode(checkGuestLogin($ipaddress));
        
                            if ($data->status == true) {
                                Session::put('is_guest', '1');
                                Session::put('AuthUserData', $data);
                            }
                            $auth_user = Session::get('AuthUserData');
                        }
                    }
                }
            }
        }
        $recordData['propertyData'] = Property::select('properties.*','users.name as host_name')->leftjoin('users','users.id','=','properties.host')->where(['properties.id'=>$id])->with('getPropertyAddress.getPropertyCity','getPropertyAddress.getPropertyArea','getPropertyAddress.getPropertyProvince','getPropertyAddress.getPropertyCountry','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding','getPropertyHouserule','getExtraService.getServiceData','getUserWeb')->first();
        // dd($recordData['propertyData']->getPropertyAddress);

        $recordData['propertyImages'] = PropertyImage::where(['property_id'=>$id])->orderBy('position','asc')->get();
       // dd($recordData['propertyImages']);
        $amenity_id = PropertyAmenity::where(['property_id'=>$id])->pluck('amenities_id')->toArray();
        // dd(count($amenity_id));
        if(count($amenity_id) > 0){
            $recordData['propertyAmenities'] = Amenity::whereIn('id',$amenity_id)->where('status',1)->get();
        }else{
            $recordData['propertyAmenities'] = [];
        }
        // dd($recordData['propertyAmenities']);

        $recordData['category'] = Category::where(['status'=>1])->get();
        $recordData['ratings'] = Rating::where(['property_id'=>$id])->with('getUser')->get();
        
        if(isset($result['response']->data) && $result['response'] == true){
            $recordData['userData'] = $result['response']->data;
        }else{
            $recordData['userData'] = '';
        }

        $range = 12;
        $start_range = (($range*1)-12);
        $end_range = $range*1;

        $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));
        $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
        $allDates = [];

        $alreadybooking = Booking::where('property_id', $id)->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
            ->pluck('from_date','to_date')->toArray();

        $alreadyBlockedDate = PropertyBlockDate::where('property_id', $id)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
       // dd($alreadybooking);
        $mo = [];
        for($i=0; $i<12;$i++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
            $getThisMonth = date('m',strtotime($start_date_new));

            if ($alreadybooking) {
              //  dd($alreadybooking);
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
        
        $mo = [];
        if (!empty($alreadybooking)) {
            foreach ($alreadybooking as $key => $value) {
                $start_date_for = new DateTime($value);
                $end_date_for = new DateTime($key);
                for ($date = clone $start_date_for; $date < $end_date_for; $date->modify('+1 day')) {
                    $mo[$date->format('Y-m-d')] = $date->format('Y-m-d');
                }
            }
        }

        //dd($newArray);


       // dd($mo);
        /*if (!empty($alreadybooking)) {
            foreach ($alreadybooking as $key => $value) {
                $getendMonth = date('Y-m-d',strtotime($key));
                unset($mo[$getendMonth]);
            }
        }*/
        //
       

        //die;
        if ($recordData['propertyData']) {
            $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$recordData,'id'=>$id,'blockDates'=>json_encode($mo)];
            return view('web.property-detail',$data);
        } else {
            return abort(404);
        }
    }

        public function rateListPricePropertyDetails(Request $request)
        {   

            $startDate =$request->startDate;
            $endDate =$request->endDate;
            $property_id =$request->property_id;
            $total_price =0;

            $checked_commision = false;
            $rateData = '';
            if ($getProperty =  Property::select('price as detail_price', 'host', 'id')->where("id",$property_id)->first()) {
                if ($getRate = Rate::where('status', 1)->where("start_date","<=",$startDate)->where('property_id', $property_id)->where("end_date",">=",$startDate)->orderBy('start_date', 'asc')->first()) {
                    $rateData = $getRate;
                }else{
                    if ($getRate =  Rate::where('status', 1)->where("start_date","<=",$endDate)->where('property_id', $property_id)->where("end_date",">=",$endDate)->orderBy('start_date', 'asc')->first()) {
                        $rateData = $getRate;
                    }else{
                        if ($getRate =  Rate::where('status', 1)->where("start_date",">=",$startDate)->where('property_id', $property_id)->where("end_date","<=",$endDate)->orderBy('start_date', 'asc')->first()) {
                            $rateData = $getRate;
                        }
                    }
                }
                if ($rateData != '') {
                    $startDate = new DateTime($startDate);
                        $endDate = new DateTime($endDate);
                    for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                          $newDate = $date->format('Y-m-d');
                       
                        if ((($newDate) >=($rateData->start_date)) && (($newDate) <= ($rateData->end_date))) {
                         $total_price = $total_price + $rateData->price;
                          
                        }else{
                            $updatePrice= $this->getContractAdminCommision($newDate,$getProperty);
                            $total_price = $total_price + $updatePrice;
                        }
                       
                    }
                }else{
                    if ($getRate = Rate::where('status', 1)->where("start_date","<=",$startDate)->whereNull('property_id')->where("end_date",">=",$startDate)->orderBy('start_date', 'asc')->first()) {
                        $rateData = $getRate;
                    }else{
                        if ($getRate =  Rate::where('status', 1)->where("start_date","<=",$endDate)->whereNull('property_id')->where("end_date",">=",$endDate)->orderBy('start_date', 'desc')->first()) {
                            $rateData = $getRate;
                        }else{
                            if ($getRate =  Rate::where('status', 1)->where("start_date",">=",$startDate)->whereNull('property_id')->where("end_date","<=",$endDate)->orderBy('start_date', 'asc')->first()) {
                                $rateData = $getRate;
                            }
                        }
                    }
                    if ($rateData != '') {
                        $startDate = new DateTime($startDate);
                        $endDate = new DateTime($endDate);
                        for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                            $newDate = $date->format('Y-m-d') ;
                            if ((($newDate) >= ($rateData->start_date)) && (($newDate) <= ($rateData->end_date))) {
                                $total_price = $total_price + $rateData->price;
                            }else{
                                 $updatePrice= $this->getContractAdminCommision($newDate,$getProperty);
                                $total_price = $total_price + $updatePrice;
                            }
                        }
                 
                    }else{
                        $startDate = new DateTime($startDate);
                        $endDate = new DateTime($endDate);
                        for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                            $newDate = $date->format('Y-m-d') ;
                            $updatePrice =  $this->getContractAdminCommision($newDate, $getProperty);
                            $total_price = $total_price + $updatePrice;

                        }
                    }
                }
                 
            }
            return round($total_price, 2);
        }

        public function getContractAdminCommision($newDate,$getProperty){
            try{
                $price = $getProperty->detail_price;
                $commission  = 0;
                $total_price = 0;
                $getCommissionAccommodationData = "";
                if($getCommissionAccommodation = Commission::where('status',1)->where('start_date', '<=',$newDate)->where('end_date', '>=',$newDate)->orderBy('start_date', 'asc')->where('all_properties', 'No')->whereHas('getCommissionAccommodationData' , function ($q) use ($getProperty) {
                        $q->where('property_id', $getProperty->id); 
                    })->first()){
                    $getCommissionAccommodationData = $getCommissionAccommodation;
                }

                if($getCommissionAccommodationData != ''){
                    if ($getCommissionAccommodationData->all_hosts == 'Yes') {
                        $commission = $getCommissionAccommodationData->percentage;
                    }else{
                        if($getProperty->host == $getCommissionAccommodationData->host_id){
                            $commission = $getCommissionAccommodationData->percentage;
                        }
                    } 
                }else{
                    if($getCommissionAccommodation = Commission::where('status',1)->where('start_date', '<=',$newDate)->where('end_date', '>=',$newDate)->orderBy('start_date', 'asc')->where('all_properties', 'Yes')->first()){
                        $getCommissionAccommodationData = $getCommissionAccommodation;
                    }
                    if($getCommissionAccommodationData != ''){
                        if ($getCommissionAccommodationData->all_hosts == 'Yes') {
                            $commission = $getCommissionAccommodationData->percentage;
                        }else{
                            if($getProperty->host == $getCommissionAccommodationData->host_id){
                                $commission = $getCommissionAccommodationData->percentage;
                            }
                        } 
                    }
                }
                if($commission == '0'){
                    $commission = AdminSettings::pluck('commission')->first();
                }

                if($commission > 0){
                    $price1 = 0;
                    $price1 = ($price / 100) * $commission ;
                    $price1 = round($price1, 2);
                    $total_price = $price1 + $price;
                }else{
                    $total_price = $price;
                }
            }catch (Exception $e) {
                //dd($e);
            }
            return $total_price;
        }

        public function rateListPricePropertyDetails09(Request $request)  // 09-10-2023
    {   

       $startDate = $request->startDate;
        $endDate = $request->endDate;
        $property_id =$request->property_id;
        $total_price =0;
        $checked_commision = false;
        $rateData = '';
        if ($getProperty =  Property::select('price as detail_price', 'host', 'id')->where("id",$property_id)->first()) {
            $startDate = new DateTime($startDate);
            $endDate = new DateTime($endDate);
          
            if ($getRate = Rate::where('status', 1)->where("start_date","<=",$startDate)->where('property_id', $property_id)->where("end_date",">=",$startDate)->orderBy('id', 'desc')->first()) {
                $rateData = $getRate;

            }else{
                if ($getRate =  Rate::where('status', 1)->where("start_date","<=",$endDate)->where('property_id', $property_id)->where("end_date",">=",$endDate)->orderBy('id', 'desc')->first()) {

                    $rateData = $getRate;
                }else{
                    if ($getRate =  Rate::where('status', 1)->where("start_date",">=",$startDate)->where('property_id', $property_id)->where("end_date","<=",$endDate)->orderBy('id', 'desc')->first()) {
                        $rateData = $getRate;
                    }
                }
            }
         
            if ($rateData != '') {
               
               for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                     $newDate = $date->format('Y-m-d');

                    if ((($newDate) >= ($rateData->start_date)) && (($newDate) <= ($rateData->end_date))) {
                        $total_price = $total_price + $rateData->price;
                    }else{
                        $total_price = $total_price + $getProperty->detail_price;
                    }
                }

            }else{

                if ($getRate = Rate::where('status', 1)->where("start_date","<=",$startDate)->whereNull('property_id')->where("end_date",">=",$startDate)->orderBy('id', 'desc')->first()) {
                    $rateData = $getRate;

                }else{
                    if ($getRate =  Rate::where('status', 1)->where("start_date","<=",$endDate)->whereNull('property_id')->where("end_date",">=",$endDate)->orderBy('id', 'desc')->first()) {

                        $rateData = $getRate;

                    }else{
                        if ($getRate =  Rate::where('status', 1)->where("start_date",">=",$startDate)->whereNull('property_id')->where("end_date","<=",$endDate)->orderBy('id', 'desc')->first()) {
                            $rateData = $getRate;

                        }
                    }
                }

                if ($rateData != '') {
               
                   for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                         $newDate = $date->format('Y-m-d');

                        if ((($newDate) >= ($rateData->start_date)) && (($newDate) <= ($rateData->end_date))) {
                            $total_price = $total_price + $rateData->price;
                        }else{
                            $total_price = $total_price + $getProperty->detail_price;
                        }
                    }
                }else{

                    for ($date = $startDate; $date < $endDate; $date->modify('+1 day')) {
                        $total_price = $total_price + $getProperty->detail_price;
                    }
                    //dd($total_price);
                }
            }

            $commission = null;
            $getCommissionAccommodationData = '';
           
            //-------------------getCommissionAccommodation-------------
           /*
            if($getCommissionAccommodation = Commission::where('status',1)->whereDate('start_date', '<=',$startDate)->whereDate('end_date', '>=',$startDate)->orderBy('id', 'desc')->first()){
                $getCommissionAccommodationData = $getCommissionAccommodation;
            }
            else{
                 if ($getCommissionAccommodation =  Commission::where('status', 1)->whereDate("start_date","<=",$endDate)->whereDate("end_date",">=",$endDate)->orderBy('id', 'desc')->first()) {

                        $getCommissionAccommodationData = $getCommissionAccommodation;
                    }else{
                        if ($getCommissionAccommodation =  Commission::where('status', 1)->whereDate("start_date",">=",$startDate)->whereDate("end_date","<=",$endDate)->orderBy('id', 'desc')->first()) {
                            $getCommissionAccommodationData = $getCommissionAccommodation;
                        }
                    }
            }*/
            $property_price = $total_price;


             if(Commission::where("status",1)->count()>0) // main
             {
                /*
                $record = Commission::where(['all_properties'=>'Yes', 'all_hosts'=>'Yes','status'=>1])->whereDate('start_date', '<=', $startDate)->whereDate('end_date', '>=', $endDate)->orderBy('id', 'desc')->first();

                if($record){
                    $commission = $record->percentage;
                }else{
                    $record = CommissionAccommodation::select('commissions.percentage')->where(['property_id'=>$property_id])->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')
                    ->whereDate('commissions.start_date', '<=', $startDate)->whereDate('commissions.end_date', '>=', $endDate)
                    ->orderBy('commission_accommodations.id', 'desc')->first();
                    if($record){
                        $commission = $record->percentage;
                    }else{
                    $record = Commission::where(['host_id'=>$getProperty->host,'status'=>1])->whereDate('start_date', '<=', $startDate)->whereDate('end_date', '>=', $endDate)->orderBy('id', 'desc')->first();
                        if ($record) {
                        $commission = $record->percentage;
                      }
                    }
                }*/
                //--------------
              //  dd($request->startDate);
                   $record = CommissionAccommodation::select('commissions.id','commissions.percentage','commissions.status','commissions.start_date')
                  ->where(['property_id'=>$property_id,'commissions.status'=>1])
                  ->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')
                  //->where("commissions.status",1)
                    ->where('commissions.start_date', '<=', $request->startDate)
                    ->where('commissions.end_date', '>=', $request->startDate)
                     ->orderBy("commissions.percentage","asc")
                    ->orderBy('commissions.start_date', 'asc')
                    ->first();
                  
                    if($record)
                    {
                        $record = CommissionAccommodation::select('commissions.id','commissions.percentage','commissions.status','commissions.start_date')
                          ->where(['property_id'=>$property_id,'commissions.status'=>1])
                          ->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')
                          //->where("commissions.status",1)
                            ->whereDate('commissions.start_date', '<=', $request->endDate)
                            ->whereDate('commissions.end_date', '>=', $request->endDate)
                             ->orderBy("commissions.percentage","asc")
                          //  ->orderBy('commissions.start_date', 'asc')
                            ->first();

                            if($record){
                                 $record = CommissionAccommodation::select('commissions.id','commissions.percentage','commissions.status','commissions.start_date')
                                  ->where(['property_id'=>$property_id,'commissions.status'=>1])
                                  ->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')
                                  //->where("commissions.status",1)
                                    ->whereDate('commissions.start_date', '<=', $request->startDate)
                                    ->whereDate('commissions.end_date', '>=', $request->endDate)
                                     ->orderBy("commissions.percentage","asc")
                                  //  ->orderBy('commissions.start_date', 'asc')
                                    ->first();
                                   if($record)
                                   {
                                    $commission = $record->percentage;
                                   }
                            }
                    }
                    else{

                          $record = Commission::where(['status'=>1])
                          ->whereDate('commissions.start_date', '<=', $request->startDate)
                          ->whereDate('commissions.end_date', '>=', $request->endDate)
                          ->orderBy('commissions.percentage', 'asc')->orderBy('commissions.start_date', 'asc')->first();
                        if($record){
                              $commission = $record->percentage;
                        }

                    }

                    //
                    /*///
                  $record = CommissionAccommodation::select('commissions.id','commissions.percentage','commissions.status','commissions.start_date')
                  ->where(['property_id'=>$property_id,'commissions.status'=>1])
                  ->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')
                  //->where("commissions.status",1)
                    ->whereDate('commissions.start_date', '<=', $startDate)
                    ->whereDate('commissions.end_date', '>=', $endDate)
                     ->orderBy("commissions.percentage","asc")
                  //  ->orderBy('commissions.start_date', 'asc')
                    ->first();

                   
                    if($record){
                     $commission = $record->percentage;
                    }else{
                        $record = Commission::where(['host_id'=>$getProperty->host,'status'=>1])->whereDate('start_date', '<=', $startDate)->whereDate('end_date', '>=', $endDate)->orderBy('commissions.percentage', 'asc')->orderBy('commissions.start_date', 'asc')->first();
                        if ($record) {
                            $commission = $record->percentage;
                        }
                        else{
                             $record = Commission::where(['all_properties'=>'Yes', 'all_hosts'=>'Yes','status'=>1])->whereDate('start_date', '<=', $startDate)->whereDate('end_date', '>=', $endDate)->orderBy('commissions.percentage', 'asc')->orderBy('commissions.start_date', 'asc')->first();
                            if($record){
                                $commission = $record->percentage;
                            }
                        }
                    }
                    */
            }
            
            /*
            if(isset($getCommissionAccommodationData) && !empty($getCommissionAccommodationData)){
                $crow = $getCommissionAccommodationData;
              //  foreach($getCommissionAccommodationData as $crow){

                    //Single Host and Single Property
                    if(($crow->all_properties=='No') && ($crow->all_hosts=='No')){

                        if(isset($crow->getCommissionAccommodationData)){
                            foreach($crow->getCommissionAccommodationData as $cdetaul){
                                if(($cdetaul->property_id == $getProperty->id) && ($getProperty->host = $crow->host_id )){
                                    $commission = $crow->percentage;
                                    break;
                                }
                            }
                        }
                    }//End Single Host and Single Property
                    else if(($crow->all_properties=='No') && ($crow->all_hosts=='Yes')){ //Single Property But All Host

                        if(isset($crow->getCommissionAccommodationData)){
                            foreach($crow->getCommissionAccommodationData as $cdetaul){
                                if(($cdetaul->property_id == $getProperty->id)){
                                    $commission = $crow->percentage;
                                    break;
                                }
                            }
                        }
                    }//end //Single Property But All Host
                   else if(($crow->all_properties=='Yes') && ($crow->all_hosts=='No')){ //All Property But No Host
                       if(($crow->host_id == $getProperty->host)){
                             $commission = $crow->percentage;
                        }
                    }//end  //All Property But No Host

                    else if(($crow->all_properties=='Yes') && ($crow->all_hosts=='Yes')){ //All Property But All host
                             $commission = $crow->percentage;
                    }//end  //All Property But All host
              //  }
            }
            */
           // dd($commission);
            /*
            if(!isset($commission))
            {
                 $commission = AdminSettings::pluck('commission')->first();
            
            }*/
            if(!$checked_commision){
                  $commission = AdminSettings::pluck('commission')->first();
            }

            //admin Commision
            
            
                    if(isset($commission) && !empty($commission)){
                        $price1 = $total_price * $commission / 100;

                        $total_price = $price1 + $total_price;
                    // if(isset($getProperty->tax) && !empty($getProperty->tax)){
                    //     $price1 = $total_price * $getProperty->tax / 100;
                    //     $total_price = $price1 + $total_price;
                    // }
                }else{
                    // if(isset($getProperty->tax) && !empty($getProperty->tax)){
                    //     $price1 = $total_price * $getProperty->tax / 100;
                    //     $total_price = $price1 + $total_price;
                    // }
                }

          }
          return $total_price;
        }

    public function rateListPricePropertyDetails1(Request $request){ // old

        if($request->all()){

         $startDate=   $request->startDate; //date("Y-m-d", strtotime($request->startDate));
         $endDate= $request->endDate;// date("Y-m-d", strtotime($request->endDate));
         $property_id = $request->property_id;
         $getProperty =  Property::where("id",$property_id)->first();

        $price = $getProperty->price;

        $rateprice =0;
         // $rateList = Rate::where(['status'=>1, 'property_id'=>$property_id])->where('start_date', '>=', $startDate)->where('end_date',  '<=', $endDate)->orderBy('id', 'desc')->first();

         $rateList = Rate::where(['status'=>1,'property_id'=>$property_id])->where(function ($query) use ($startDate,$endDate) {
                    if(!empty($startDate)){
                        $query->orwhere('start_date', '>=', $startDate);
                    }
                    if(!empty($endDate)){
                        $query->orwhere('end_date', '>=', $endDate);                        
                    }

                })->orderBy('id', 'desc')->first();


             if ($rateList) {
                $rateprice = $rateList->price;
            }else{
                 // $rateList = Rate::where(['status'=>1,'all_properties'=>'Yes'])->where('start_date', '>=', $startDate)->where('end_date',  '<=', $endDate)->orderBy('id', 'desc')->first();
               $rateList = null;
                if(!empty($startDate)){

                 $rateList =   Rate::where(['status'=>1,'all_properties'=>'Yes'])->where(function($q) use($startDate){
                        $q->where("start_date",">=",$startDate);
                    })->first();
                  
                    if(isset($rateList)){
                 $rateList =   Rate::where(['status'=>1,'all_properties'=>'Yes'])->where(function($q) use($startDate){
                        $q->where("start_date",">=",$startDate);
                    })->first();

                    }
                }
                dd($rateList);
                // $rateList = Rate::where(['status'=>1,'all_properties'=>'Yes'])->where(function ($query) use ($startDate,$endDate) {
                //    if(!empty($startDate)){
                //         $query->orwhere('start_date', '>=', $startDate);
                //     }
                //     if(!empty($endDate)){
                //         $query->where('end_date', '>=', $endDate);                        
                //     }

                // })->orderBy('id', 'desc')->first();

                if ($rateList) {
                    $rateprice = $rateList->price;
                }
            }

           $rPrice = $getProperty->getCommissionPriceAttribute($rateprice);
           

            // if(isset($rateList))
            // {
            //    return $price; 
            // }
          // echo $rateprice;

            if(isset($rateList) && isset($rateprice))
            {
                if($rateprice ==$rPrice)
                {
                    $commission = AdminSettings::pluck('commission')->first();
                }
                else{
                    $rateprice =$rPrice;
                    $commission = null;
                }

                if(isset($commission) && !empty($commission)){
                     $price1 = $rateprice * $commission / 100;

                    $rateprice = $price1 + $rateprice;

                    if(isset($getProperty->tax) && !empty($getProperty->tax)){
                        $price1 = $rateprice * $getProperty->tax / 100;
                        $rateprice = $price1 + $rateprice;
                        // dd($price);
                    }
                }else{
                    if(isset($getProperty->tax) && !empty($getProperty->tax)){
                        $price1 = $rateprice * $getProperty->tax / 100;
                        $rateprice = $price1 + $rateprice;
                        // dd($price);
                    }
                }

           $rateprice = ($rateprice) + ($price);
        
           }
           else{
            $rateprice = $price;
           }
     }
      return $rateprice;
    }
    public function property_detail_booking_calender(request $request){
        $input= $request->all();
        $range = 6;
        $start_range = (($range*$input['page'])-6);
        $end_range = $range*$input['page'];

        $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));
        $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
        $allDates = [];

        /*$alreadybooking = Booking::where('property_id', $input['id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
            ->pluck('from_date','to_date')->toArray();*/

        $alreadybooking = Booking::where('property_id', $input['id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
            ->pluck('from_date','to_date')->toArray();


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
                    $key = date('Y-m-d H:i:s', strtotime($key . ' -1 day'));
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
            $getThisMonth = date('m',strtotime($start_date_new));
            $allDates = array();

        

            if ($alreadyBlockedDate) {
                foreach ($alreadyBlockedDate as $k => $v) {
                    $k = date('Y-m-d H:i:s', strtotime($k . ' -1 day'));
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
                    $k = date('Y-m-d H:i:s', strtotime($k . ' -1 day'));
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
                    $k = date('Y-m-d H:i:s', strtotime($k . ' -1 day'));
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
            'start_date'=> $start_date,
            'start_day'=>$start_date1[2],
            'start_month'=>$start_date1[1],
            'start_year'=>$start_date1[0],
            'end_date'=>$end_date,
            'end_day'=>$end_date1[2],
            'end_month'=>$end_date1[1],
            'end_year'=>$end_date1[0],          
            'monthWiseData'=>$mo,  
            'hostMonthWiseData'=>$host_month,
            'adminMonthWiseData'=>$admin_month, 
            'subAdminMonthWiseData'=>$sub_month, 
                   
        ];

       // dd($data);

        
        return view('web.property-detail-booking-calender',$data);
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

    public function getCityArea(request $request, $province_id, $area_id='')
    {
        if ($province_id) {

            if ($province_id != 'all') {
                $cityRecord = Area::select('areas.*', 'cities.name as city_name')->join('cities', 'cities.id', '=', 'areas.city_id')->where(['areas.province_id'=>$province_id])->get();

            } else {
                $cityRecord = Area::select('areas.*', 'cities.name as city_name')->join('cities', 'cities.id', '=', 'areas.city_id')->get();
            }
            $data['records'] = $cityRecord;
            $data['province_id'] = $province_id;

        } else {
            $data['records'] = array();
            $data['province_id'] = '';
        }
        $data['area_id'] = $area_id;
        return view('web.show_cities_areas',$data);
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
        // dd($result->data[0]->get_property->get_property_address[0]);
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
        // dd($result->data[0]->get_property->get_host_details);
        $data = ['title' => __('backend.My_Account'), 'data' => $result];
        return view('web.my_bookings', $data);
    }

    public function my_reservations(Request $request)
    {
        $parms = array();
        $result = ApiCurlMethod('getreservations', $parms, 'Bearer', 'GET');
        // dd($result->data[0]->get_property->get_host_details);
        $data = ['title' => __('backend.My_Reservations'), 'data' => $result];
        return view('web.my_reservations', $data);
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

    public function reBooking(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('reBooking', $parms, 'Bearer', 'POST');
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

    public function share_booking_form(Request $request)
    {
        $input =  $request->all();
        $validator = Validator::make($input, [
            'mobile' => 'required',
            'country_code' => 'required',
        ]);
    
        // dd($_COOKIE);
        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['error'] = $errors;
            return response()->json($response, 200);
        } else {
            // dd($input);
            $booking_id = $input['booking_id'];
            if(isset($booking_id) && !empty($booking_id)){
                $booking = Booking::where('booking_id',$booking_id)->first();
                if(isset($booking) && !empty($booking)){
                    $response['status'] = true;
                    $response['message'] = 'Booking share successfully.';
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Booking id is required.';
            }
            // $result = ApiCurlMethod('store-card', $input, 'Bearer', 'POST');
            return response()->json($response, 200);
        }
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
            $response['error'] = $errors;
            return response()->json($response, 200);
        } else {
            $result = ApiCurlMethod('store-card', $input, 'Bearer', 'POST');
            return response()->json($result, 200);
        }
    }

    public function addToCart(Request $request)
    {
        try{
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
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function addToReserve(Request $request)
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
            $result = ApiCurlMethod('add-reserve', $input, 'Bearer', 'POST');
            return response()->json($result);
        }
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
            $parms = $request->all();
            $result = ApiCurlMethod('schedule_appointment', $parms, 'Bearer', 'POST');
            // dd($result);
            return response()->json($result, 200);
            // return response()->json($response, 200);
        }
    }

    public function checkout(Request $request) {
      
        $parms = array();
        $result = ApiCurlMethod('get-cart', $parms, 'Bearer', 'GET');
        $countryData = Country::where('status', 1)->orderBy('name','asc')->get();
        $data = ['title' => __('backend.Checkout'), 'data' => $result, 'countryData' => $countryData];
        return view('web.booking', $data);
    }

    public function checkout_reserve(Request $request) {
        $parms = array();
        $result = ApiCurlMethod('get-cart', $parms, 'Bearer', 'GET');
        $countryData = Country::where('status', 1)->orderBy('name','asc')->get();
        $data = ['title' => __('backend.Checkout'), 'data' => $result, 'countryData' => $countryData];
        return view('web.booking_reserve', $data);
    }

    public function reserveBookingCheckout($booking_id = null) {
        // dd($booking_id);
        if(isset($booking_id) && !empty($booking_id)){
            $auth_user = Session::get('AuthUserData');
            if (!$auth_user) {
                //Guest User
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                //check remote address exist

                if ($ipaddress) {
                    $data = json_decode(checkGuestLogin($ipaddress));

                    if ($data->status == true) {
                        Session::put('is_guest', '1');
                        Session::put('AuthUserData', $data);
                    }
                }
                //End
            } else {
                $parms = array();
                $result = ApiAuthValid('user-profile', $parms, 'Bearer', 'GET');

                if ($result['httpcode'] == 401) {
                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                    //check remote address exist

                    if ($ipaddress) {
                        $data = json_decode(checkGuestLogin($ipaddress));

                        if ($data->status == true) {
                            Session::put('is_guest', '1');
                            Session::put('AuthUserData', $data);
                        }
                    }
                }
            }
            $result = PropertyReserveRequest::where('booking_id',$booking_id)->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getPropertyAddress.getPropertyCity','getProperty.getPropertyAddress.getPropertyArea','getProperty.getPropertyAddress.getPropertyProvince','getProperty.getPropertyAddress.getPropertyCountry','getProperty.getExtraService.getServiceData')->first();
            // dd($result->getProperty->getPropertyAddress[0]->getPropertyArea);
            if(isset($result) && !empty($result)){
                $parms = array();
                $countryData = Country::where('status', 1)->get();
                $data = ['title' => __('backend.Checkout'), 'data' => $result, 'countryData' => $countryData];
                return view('web.booking_reserve_checkout', $data);
            }else{
                redirect(route('web.home'));
            }
        }else{
            redirect(route('web.home'));
        }
    }

    public function checkout_form(Request $request) {

        $parms = $request->all();

       
      

        if(isset($parms['booking_type']) && $parms['booking_type'] == 'Reserve'){
            $result = ApiCurlMethod('checkout_reserve', $parms, 'Bearer', 'POST');
        }else if(isset($parms['booking_type1']) && $parms['booking_type1'] == 'Reserve'){
            $result = ApiCurlMethod('checkout_from_reserve', $parms, 'Bearer', 'POST');
        }else{
            $result = ApiCurlMethod('checkout', $parms, 'Bearer', 'POST');
        }
        return response()->json($result);
    }

    public function booking_details(Request $request) {
        if ($request->reference) {

            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$request->reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$live_key,
                "Cache-Control: no-cache",
            ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
        
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
              
                // echo $response;
                $response_json = json_decode($response);
                $getBookingData = Booking::with('getProperty.getPropertyAddress.getPropertyCity')->where('reference', $request->reference)->first();
                

                if ($getBookingData) {
                    $getBookingData->payment_json = $response;
                    $getBookingData->booking_status = 'Confirmed-by-Host';
                    $getBookingData->booking_type = 'Paid';
                    $getBookingData->save();

                    $booking_data =  $getBookingData;                
                    $email = EmailTemplateLang::where('email_id', 12)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                    $user = User::where('id',$booking_data->guest_id)->first();
                    $subject = $email->subject;
                
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $record = (object)[];
                    $description = $email->description;
                    $description = str_replace("[NAME]", ucwords($user->name).' '.$user->surname,  $email->description);
                    $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
                    $record->description = $description;    
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->username = $user->name;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $user->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
                
                    Mail::send('emails.booking_confirm', compact('record'), function ($message) use ($user, $subject) {
                        $message->to($user->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });

                    $hostdata = User::where('id',$booking_data->host_id)->first();
                    $viewPage = 'emails.booking_confimed_host';
                    
                    $email = EmailTemplateLang::where('email_id', 29)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
        
                    $subject = $email->subject;  
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);   
                    $description = $email->description;
                    $description = str_replace("[PROPERTY_NAME]",$booking_data->getProperty->title, $description);  
                    $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                    $description = str_replace("[GUEST_NAME]",$user->name.' '.$user->surname, $description);
                    $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
    
                    $record->description = $description;        
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $hostdata->name.' '.$hostdata->surname;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $hostdata->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
                           
                    Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                        $message->to($hostdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });

                }

                $is_guest = Session::get('is_guest');

                if ($is_guest == 1) {
                    // return redirect('/');
                    return redirect('/thank-you?booking_id='.$getBookingData->booking_id);

                } else {
                    // return redirect('/bookings');
                    return redirect('/thank-you?booking_id='.$getBookingData->booking_id    );
                }
            }

        } else {
            die('Something went wrong.');
        }
    }

    public function booking_detail_without_auth($booking_id = null) {
        // dd($booking_id);
        if(isset($booking_id) && !empty($booking_id)){
            $check_booking = Booking::where('booking_id',$booking_id)->first();
            if(isset($check_booking) && !empty($check_booking)){
                // $booking = Booking::where('booking_id',$booking_id)->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData','getProperty.getUser')->first();
                $booking = Booking::where('booking_id',$booking_id)->with('getProperty.getPropertyAddress','getProperty.getHostDetails','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData','getLoyaltyPoints')->first();
                // dd($booking);
                $data = ['title' => 'Booking Detail', 'data' => $booking];
                return view('web.booking_detail', $data);
            }else{
                // redirect(url('/'));
                return redirect('/');
            }
        }else{
            return redirect('/');
        }
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

    public function forget_update_password_form(Request $request)
    {
        $parms = $request->all();
        // dd($parms);
        $result = ApiCurlMethod('update-password', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }

    public function rating_form(Request $request)
    {
        $parms = $request->all();
        $result = ApiCurlMethod('submit-rate', $parms, 'Bearer', 'POST');
        return response()->json($result);
    }
    public function bookingRatingForm($booking_id)
    {

        $booking = Booking::where('booking_id',$booking_id)
        ->with('getProperty.getPropertyAddress','getProperty.getHostDetails','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData','getLoyaltyPoints')
        ->first();
        $data = ['title' => 'Booking Detail', 'data' => $booking];
        return view('web.booking_detail_review', $data);

    }

    public function bookingRatingSubmit(Request $request)
    {
        $input = $request->all();
        $booking = Booking::where('booking_id',$input['booking_id'])
        ->first();
        $booking->is_rating = 1;
        $booking->save();
        if ($totalRate = Rating::select([DB::raw('COUNT(*) AS total_rating'),DB::raw('SUM(rate) AS sum_rate')])->where('property_id', $booking->property_id)->first()) {
            if ($totalRate->total_rating > 0) {
                $avg_rating = $totalRate->sum_rate / $totalRate->total_rating;
            }else{
                $avg_rating = $input['star'];
            }
            
            Property::where('id', $booking->property_id)->update(['avg_rating' => $avg_rating, 'total_rating' => $totalRate->total_rating]);
        }
        

        $rating = new Rating;
        $rating->user_id = $input['user_id'];
        $rating->order_id = $booking->id;
        $rating->property_id =     $input['property_id'];
        $rating->rate = $input['star'];
        $rating->review = $input['review'];
        $rating->service   =    $input['service'];
        $rating->cleanliness =  $input['cleanliness'];
        $rating->accommodation =  $input['accommodation'];
        $rating->location =  $input['location'];
        $rating->valueformoney =  $input['valueformoney'];   
        $rating->comment =   $input['comment'];   
        $rating->improvement = $input['improvement'];
        $rating->save();


        return redirect()->back()->with('success', 'Rating Submit successfully');   
    }

    public function cronJob(request $request)
    {
        
        try{
            $results = Booking::select('bookings.*','properties.title','properties.code','properties.type','users.name as host_name')
                    ->join('properties','properties.id','=','bookings.property_id')
                    ->join('users', 'users.id', '=', 'properties.host')
                    ->orderBy('properties.id', 'DESC')->with('getProperty','getProperty.getPropertyAddress')->where('booking_status', '!=', 'Cancelled-Booking')->where('is_payout_mail', 'No')->where('from_date', '<=', date('Y-m-d H:i:s'))->limit(10)->get();
        
            if (!empty($results)) {
                foreach($results as $key => $booking_data){
                    $record = (object)[];
                    $userId = $booking_data->guest_id;
                    $hostId = $booking_data->host_id;

                    if ($hostdata = User::where('id',$hostId)->first()) {
                        $viewPage = 'emails.booking_payout';
                        $userdata = User::where('id',$userId)->first();
                        $email = EmailTemplateLang::where('email_id', 40)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
            
                        $subject = $email->subject;  
                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);   
                        $description = $email->description;
                        $description = str_replace("[PROPERTY_NAME]",$booking_data->getProperty->title, $description);  
                        $description = str_replace("[AMOUNT]",$booking_data->host_amount, $description);  
                        $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                        $description = str_replace("[GUEST_NAME]",$userdata->name.' '.$userdata->surname, $description);
                        $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
                        $record->description = $description;
                        $record->footer = $email->footer;
                        $record->name = $email->name;
                        $record->username = $hostdata->name.' '.$hostdata->surname;
                        $record->property_name = $booking_data->getProperty->title;
                        $record->subject = $subject;
                        $record->user_email = $hostdata->email;
                        $record->cardData = $booking_data;
                        if (filter_var($hostdata->email, FILTER_VALIDATE_EMAIL)) {
                            Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                                $message->to($hostdata->email, config('app.name'))->subject($subject);
                                $message->from('customersupport@shortletrenrals.com', config('app.name'));
                            });
                            Booking::where('id', $booking_data->id)->update(array('is_payout_mail' => 'Yes'));
                        }
                    } 
                }
                
            }  
        } catch (\Exception $ex) {
            dd($ex);
        }   
    }

    public function cronJobNotification(request $request)
    {
        
        try{
            $results = DB::table('notification_pendings')->limit(10)->get();
        
            if (!empty($results)) {
                foreach($results as $key => $val){
                    $viewPage = 'emails.notification';
                    $subject = 'Notification';
                    $description = $val->message;
                    $record = (object)[];
                    $record->name = $val->title;
                    $record->footer = '';
                    $record->username = $val->user_name;
                    $record->subject = $val->title;
                    $record->description = $description;
                    $record->user_email = $val->email;

                        Mail::send($viewPage, compact('record'), function ($message) use ($val, $subject) {
                            $message->to($val->email, config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });
                    

                    $notificationData = new Notification;
                    $notificationData->user_type = $val->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = $val->notification_for;
                    $notificationData->title = $val->title;
                    $notificationData->message = $val->message;
                    $notificationData->user_id = $val->user_id;
                    
                    $notificationData->save();
                    send_notification(1, $val->user_id, $val->title, array('title'=>$val->title,'message'=>$val->message) );
                    DB::table('notification_pendings')->where('id', $val->id)->delete();
                }
                
            }else{
                DB::table('notification_pendings')->truncate();
            }  
        } catch (\Exception $ex) {
            dd($ex);
        }   
    }



}
