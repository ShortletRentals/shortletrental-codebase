<?php
namespace App\Exports;
use App\Models\Orders;
use App\Models\Property;
use App\Models\Transection;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\User;
use App\Models\PropertyAmenity;
use App\Models\PropertyCategory;
use App\Models\Category;
use App\Models\PropertyExtraService;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\Amenity;
use App\Models\ExtraService;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkTransectionExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        $this->file_type = $request->file_type;
        $this->booking_status = $request->booking_status??null;
        $this->start_date = $request->start_date??null;
        $this->end_date = $request->end_date??null;
        $this->host = $request->host??null;
        $this->search_type_list = $request->search_type_list??null;
        $this->search_category = $request->search_category??null;
        $this->search_country = $request->search_country??null;
        $this->search_province = $request->search_province??null;
        $this->search_city = $request->search_city??null;
        $this->search_area = $request->search_area??null;
        // $this->is_product_selected = $_GET['is_product_selected'] ?? null;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Guest Name & Number',
            'Host Name & Number',
            'Total Amount',
            'Extra Service Amount(NGN)',
            'Platform Fees',
            'Accommodation Type',
            'Category',
            '# of Guests & Children',
            'Location',
            'Discount code & Amount',
            'Status',
            'Added Date',
        ];
    }
    public function query()
    {
        // dd(auth()->id());
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        // $q = Transection::select('bookings.*');
        if(isset($user_type) && $user_type == 5){
            $q = Transection::select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('host_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
        }else if(isset($user_type) && $user_type == 3){
            $q = Transection::select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('influencer_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
        }else{
            $q = Transection::select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
        }
        // if(isset($orderby) && $orderby == 'stay'){
        //     $orderby = 'bookings.from_date';
        // }elseif(isset($orderby) && $orderby == 'book'){
        //     $orderby = 'property.title';
        // }else{
            // $orderby = $orderby ? $orderby : 'bookings.created_at';
            $orderby = 'bookings.created_at';
        // }
        // $orderby = $orderby;
        $order = 'desc';
        // $order = $order ? $order : 'desc';
        // if(isset($status) && in_array($status,[0,1,2]))
        // if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
        // {
        //   $q->where('bookings.booking_type',$booking_type);
        // }
        // dd($this->booking_status);
        if(isset($this->booking_status) && in_array($this->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
        {
            // dd($this->booking_status);
            $q->where('bookings.booking_status',$this->booking_status);
        }

        if(isset($this->start_date) && isset($this->end_date))
        {
            $q->whereBetween('bookings.created_at',[$this->start_date.' 00:00:01',$this->end_date.' 23:59:59']);
        }else{
            if(isset($this->start_date) && $this->end_date == null){
                $q->whereDate('bookings.created_at',$this->start_date);    
            }
        }
        if(isset($this->host) && !empty($this->host))
        {
          if (!in_array("All", $this->host)) {
            $q->whereIn('properties.host',$this->host);
          }
        }

        if(isset($this->search_type_list) && !empty($this->search_type_list))
        {
          if (!in_array("All", $this->search_type_list)) {
            $q->whereIn('properties.type',$this->search_type_list);
          }
        }
        if(isset($this->search_category))
        {
          if (!in_array("All", $this->search_category)) {
            $getPropertyByCat = PropertyCategory::whereIn('category_id', $this->search_category)->groupBy('property_id')->pluck('property_id')->toArray();
            $q->whereIn('properties.id',$getPropertyByCat);
          }
        }

        if(isset($this->search_country) && !empty($this->search_country))
        { 
          $q->where('property_address.country_id',$this->search_country);
        }
        if(isset($this->search_province))
        {
          $q->where('property_address.province_id',$this->search_province);
        }
        if(isset($this->search_city) && !empty($this->search_city))
        { 
          $q->where('property_address.city_id',$this->search_city);
        }
        if(isset($this->search_area))
        {
          $q->where('property_address.area',$this->search_area);
        }
        
        // if ($search && !empty($search)) {
        //     // dd($search);
        //     $q->where(function($query) use ($search) {
        //         $query->where('bookings.booking_type', 'LIKE', '%' . $search . '%');
        //         $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
        //     });
        // }
        $response = $q->orderBy($orderby, $order);
        // dd($response->get());
        return $response;
    }
    public function map($value): array
    {
        // echo '<pre>'; print_r($value); exit;

        $extraservice = '';
        if(isset($value->selected_options))
        {
            $extraservice1 = json_decode($value->selected_options);
            foreach($extraservice1 as $v1)
            {
                $extraservice =  $extraservice.$v1->name.',';
            }
        }


        $customerData = User::where(['id' => $value->guest_id])->first();
        if(isset($customerData)){
            if($customerData->is_guest==1)
            {
                $customer = $value->personal_first_name.' '.    $value->personal_laset_name;
                $unique_id = $value->guest_id;
                $guest_mobile = $value->personal_phone_number;
    
            }
            else{
                $customer = $customerData->name;
                $unique_id = $customerData->unique_id;
                $guest_mobile = $customerData->mobile;
    
            }
        }else{
            $customer = '';
            $unique_id = '';
            $guest_mobile = '';
        }
        if(isset($value->guest_first_name) && !empty($value->guest_first_name)){
            $guest_name = isset($value->guest_first_name) && !empty($value->guest_first_name) ? $value->guest_first_name.' '.$value->guest_last_name : '';
            //$guest_name = $guest_name.' ('.'+'.$customerData->country_code.' '.$customerData->mobile.')';
        }else{
            $guest_name = $customer. '('.$guest_mobile.')';
        }
        $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
        if(isset($hostData)){
            $host_name = $hostData->name;
            $host_name = $host_name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
            $host_mobile = $hostData->mobile;
        }else{
            $host_name = '-';
            $host_mobile = '';
        }
        $property = Property::where(['id' => $value->property_id])->first();
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
        if(isset($value->discount_amount) && !empty($value->discount_amount)){
            $discount = $value->coupon_code. ' - '.$value->discount_amount;
        }else{
            $discount = isset($value->coupon_code)? $value->coupon_code:'-';
        }
        // dd($value);
        return [
            $value->booking_id,
            $guest_name,
            $host_name,
            isset($value->total_amount) && !empty($value->total_amount) ? $value->total_amount : '',
            $extraservice.' \ ('.$value->optional_service_amount.')',
            isset($value->admin_amount) && !empty($value->admin_amount) ? $value->admin_amount : '',
            isset($property) && !empty($property->type) ? str_replace('_',' ',$property->type) : '',
            isset($category) && !empty($category) ? $category : '',
            $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest + $value->no_of_pet,
            $location,
            $discount,
            isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A',
            date('d M Y h:i', strtotime($value->created_at))
            // date('d M Y', strtotime($value->created_at)),
        ];
    }

}
