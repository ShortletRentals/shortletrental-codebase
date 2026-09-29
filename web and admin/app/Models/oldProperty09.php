<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PropertyAddress;
use App\Models\PropertyBathroom;
use App\Models\PropertyBedding;
use App\Models\PropertyBedroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyHouserule;
use App\Models\PropertyCategory;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Rate;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;
use App\Models\Commission;
class Property extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'status','note','position'
    ];

    protected $appends = ['is_fav','commission_price'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function getUserWeb()
    {
        // dd('inn');
        return $this->hasOne(User::class,'id', 'host')->select(['id','title','name','surname','country_code','mobile','email','email_verified_at','is_super_host']);
    }

    public function getUser()
    {
        // dd('inn');
        return $this->hasOne(User::class,'id', 'host')->select(['id','title','name','surname','country_code','mobile','email','email_verified_at','is_super_host']);
    }

    public function getIsFavAttribute($value) {

        if (auth('api')->check()) {
            // dd(Auth::guard('api')->user()->id);
            // return 'is_favourite';
            // dd($this->id);
            $userId = Auth::guard('api')->user()->id;
            $checkFav = Wishlist::where(['user_id'=>$userId, 'product_id'=>$this->id])->first();
            // dd($checkFav);

            if ($checkFav) {
                $is_fav = 1;
            }else{
                $is_fav = 0;
            }
        } else {
            $auth_user = Session::get('AuthUserData');
            $is_fav = 0;

            if (isset($auth_user)) {
                $userId = $auth_user->data->id;
                $checkFav = Wishlist::where(['user_id'=>$userId, 'product_id'=>$this->id])->first();

                if ($checkFav) {
                    $is_fav = 1;
                }
            }else {
                $auth_user = Auth::user();
                if(isset($auth_user)){
                    $userId = $auth_user->id;
                    $checkFav = Wishlist::where(['user_id'=>$userId, 'product_id'=>$this->id])->first();
        
                    if ($checkFav) {
                        $is_fav = 1;
                    }
                }
            }
        }
        return $is_fav;
    }

    public function getCommissionPriceAttribute($value) {
      $property_price =$value; //$this->price;
        $commission_price =$value; //$this->price;

        $record = Commission::where(['all_properties'=>'Yes', 'all_hosts'=>'Yes','status'=>1])->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();
       
        if ($record) {
            $percent = $record->percentage;
            $commission_price = (($percent / 100) * $property_price) + $property_price;
          
        } else {
            $record = CommissionAccommodation::select('commissions.percentage')->where(['property_id'=>$this->id])->join('commissions', 'commissions.id', '=', 'commission_accommodations.commission_id')->whereDate('commissions.start_date', '<=', date('Y-m-d'))->whereDate('commissions.end_date', '>=', date('Y-m-d'))->orderBy('commission_accommodations.id', 'desc')->first();

            if ($record) {
                $percent = $record->percentage;
                $commission_price = (($percent / 100) * $property_price) + $property_price;

            } else {
                $record = Commission::where(['host_id'=>$this->host,'status'=>1])->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->orderBy('id', 'desc')->first();

                if ($record) {
                    $percent = $record->percentage;
                    $commission_price = (($percent / 100) * $property_price) + $property_price;

                }
            }
        }
        return $commission_price;
    }

    public function getImageAttribute2($value)
    {
        if ($value) {
            /*if ($this->image_type == 'url') {
                return $value;
            } else {
                return url('uploads/property/' . $value);
            }*/
            return fileUrl('s3',$value,'property/');
        } else {
            return url('images/no-image.png');
        }
    }
    /*
    public function getPriceAttribute($value)
    {
        $price = $value;

        $rateList = Rate::where(['status'=>1, 'property_id'=>$this->id])->whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))->orderBy('id', 'desc')->first();

        if ($rateList) {
            $price = $rateList->price;
        } else {
            $rateList = Rate::where(['status'=>1, 'all_properties'=>'Yes'])->whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))->orderBy('id', 'desc')->first();
            if ($rateList) {
                $price = $rateList->price;
            }
        }
        if(isset($rateList))
        {
           return $price; 
        }

        $price= ($this->getCommissionPriceAttribute($price));
        
        if($price == $value){
            $commission = AdminSettings::pluck('commission')->first();
        }
        else{
            $commission=null;
        }

        if(isset($commission) && !empty($commission)){
            $price1 = $price * $commission / 100;
            $price = $price1 + $price;
            if(isset($this->tax) && !empty($this->tax)){
                $price1 = $price * $this->tax / 100;
                $price = $price1 + $price;
                // dd($price);
            }
        }else{
            if(isset($this->tax) && !empty($this->tax)){
                $price1 = $price * $this->tax / 100;
                $price = $price1 + $price;
                // dd($price);
            }
        }
        return $price;
    }*/

      public function getPriceAttribute($value)
      {
        $price = $value;
          $property_id =$this->id;

        $startDate = date("Y-m-d");
        $endDate = date("Y-m-d");
      

        $rateList = Rate::where(['status'=>1, 'property_id'=>$property_id])->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate)->orderBy('id', 'desc')->first();

        if ($rateList) {
         return  $price = $rateList->price;
        } else {
            $rateList = Rate::where(['status'=>1, 'all_properties'=>'Yes'])->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate)->orderBy('id', 'desc')->first();
            if ($rateList) {
                $price = $rateList->price;
            }
        }
        // if(isset($rateList))
        // {
        //    return $price; 
        // }

         //return ($this->getCommissionPriceAttribute($price));
         
         $commission = null;
        $getCommissionAccommodationData = '';
       
        //-------------------getCommissionAccommodation-------------
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
        }
       


            

       // if($price == $value){
            $commission = AdminSettings::pluck('commission')->first();
        // }
        // else{
        //     $commission=null;
        // }

        if(isset($commission) && !empty($commission)){
            $price1 = $price * $commission / 100;
            $price = $price1 + $price;
            // if(isset($this->tax) && !empty($this->tax)){
            //     $price1 = $price * $this->tax / 100;
            //     $price = $price1 + $price;
            //     // dd($price);
            // }
        }else{
            // if(isset($this->tax) && !empty($this->tax)){
            //     $price1 = $price * $this->tax / 100;
            //     $price = $price1 + $price;
            //     // dd($price);
            // }
        }

        return $price;
    }

    public function getContractAttribute($value)
    {
        if ($value) {
            // if ($this->image_type == 'url') {
            //     return $value;
            // } else {
                // return url('uploads/property/' . $value);
                return url($value);
            // }
        } else {
            return '';
            // return url('images/default_user.png');
        }
    }

    public function getPropertyAddress()
    {
        return $this->hasMany(PropertyAddress::class,'property_id', 'id');
    }

    public function getHostDetails()
    {
        return $this->hasMany(User::class,'id', 'host');
    }

    public function getPropertyCategory()
    {
        return $this->hasMany(PropertyCategory::class,'property_id', 'id');
    }

    public function getProPropertyBathroom()
    {
        return $this->hasMany(PropertyBathroom::class,'property_id', 'id');
    }

    public function getPropertyBedding()
    {
        return $this->hasMany(PropertyBedding::class,'property_id', 'id');
    }

    public function getPropertyBedroom()
    {
        return $this->hasMany(PropertyBedroom::class,'property_id', 'id');
    }

    public function getPropertyKitchen()
    {
        return $this->hasMany(PropertyKitchen::class,'property_id', 'id');
    }

    public function getPropertyImagesWeb()
    {
        return $this->hasMany(PropertyImage::class,'property_id', 'id');
    }

    public function getPropertyImages()
    {
        return $this->hasMany(PropertyImage::class,'property_id', 'id')->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
    }

    public function getExtraService()
    {
        return $this->hasMany(PropertyExtraService::class,'property_id', 'id');
    }

    public function getAmenities()
    {
        return $this->hasMany(PropertyAmenity::class,'property_id', 'id');
    }

    public function getPropertyHouserule()
    {
        return $this->hasMany(PropertyHouserule::class,'property_id', 'id');
    }

    public function getPropertyRating()
    {
        return $this->hasMany(Rating::class,'property_id', 'id');
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date,$host,$search_city,$search_area,$max_guest_capacity,$search_bedroom,$search_category, $price,$search_type_list,$search_book_type) {
        // dd($status);
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        $q = $this->select('properties.*','property_address.property_id as main_property_id','property_address.city_id as main_city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id')->with('getPropertyAddress','getProPropertyBathroom','getPropertyBedding','getPropertyBedroom','getPropertyKitchen','getPropertyHouserule');
        
        if($user_type == 5){
            $q->where('properties.host',auth()->id());
        }else{
            // $q->where('properties.host_property_status','Accept');
        }
        
        $orderby = $orderby ? $orderby : 'properties.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('properties.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('properties.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
                $q->whereDate('properties.created_at',$start_date);
            }
        }

        if(isset($host) && !empty($host))
        {
            $q->where('properties.host',$host);
        }

        if(isset($search_type_list) && !empty($search_type_list))
        {
            $q->where('properties.type',$search_type_list);
        }

        if(isset($search_book_type) && !empty($search_book_type))
        {
            $q->where('properties.book_type',$search_book_type);
        }

        if(isset($search_city) && !empty($search_city))
        { 
            $q->where('property_address.city_id',$search_city);
        }
        if(isset($search_area))
        {
          $q->where('property_address.area',$search_area);
        }
        if(isset($max_guest_capacity))
        {
            $q->where('properties.max_guest','<=',$max_guest_capacity);
            // $q->where('properties.max_guest','>=',$max_guest_capacity);
        }
        if(isset($search_bedroom))
        {
            if ($search_bedroom != 'All') {
                $q->where('property_bedrooms.no_of_bedrooms',$search_bedroom);
            }
        }
        if(isset($search_category))
        {
            $getPropertyByCat = PropertyCategory::where('category_id', $search_category)->groupBy('property_id')->pluck('property_id')->toArray();
            $q->whereIn('properties.id',$getPropertyByCat);
        }
        if(isset($price))
        {
            if( strpos($price, ',') !== false ) {
                $price1 = str_replace(',', '', $price);
                $q->where('properties.price',$price1);
            }else{
                $q->where('properties.price',$price);
            }
        }
        // if(isset($search_type_list))
        // {
        //   $q->where('properties.type',$search_type_list);
        // }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('properties.title', 'LIKE', '%' . $search . '%');
                $query->orWhere('properties.code', 'LIKE', '%' . $search . '%');
            });
        }

        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
