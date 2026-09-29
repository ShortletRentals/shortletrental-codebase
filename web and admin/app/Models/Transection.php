<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;

class Transection extends Model
{
  // protected $fillable = [
  //   'name', 'description', 'status',
  // ];

  protected $table = 'bookings';

  protected $hidden = [
    'updated_at', 'deleted_at',
  ];

  public function getProperty()
  {
    return $this->hasOne(Property::class,'id', 'property_id');
  }

  public function getModel($search=null, $orderby=null, $order=null,$booking_status=null,$start_date=null,$end_date=null,$search_country=null,$search_province=null,$search_city=null,$search_area=null,$search_type_list=null,$search_category=null,$host=null,$date_of=null) {
    // $q = $this->select('bookings.*');
    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    if(isset($user_type) && $user_type == 5){
      $q = $this->select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('host_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
    }else if(isset($user_type) && $user_type == 3){
      $q = $this->select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->where('influencer_id',auth()->id())->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
    }else{
      $q = $this->select('bookings.*','users.name','users.email','users.mobile','properties.id as property_main_id','properties.type','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area')->leftjoin('users','users.id','=','bookings.guest_id')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','bookings.property_id');
    }
    // dd($orderby);
    if(isset($orderby) && $orderby == 'stay'){
      $orderby = 'bookings.from_date';
    }elseif(isset($orderby) && $orderby == 'book'){
      $orderby = 'property.title';
    }else{
      // dd($orderby);
      $orderby = $orderby ? $orderby : 'bookings.created_at';
    }
    $orderby = $orderby;
    $order = $order ? $order : 'desc';
    // if(isset($status) && in_array($status,[0,1,2]))
    // if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
    // {
    //   $q->where('bookings.booking_type',$booking_type);
    // }
    if(isset($booking_status) && in_array($booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
    {
      $q->where('bookings.booking_status',$booking_status);
    }

   
    if(isset($host) && !empty($host))
    {
      if (!in_array("All", $host)) {
        $q->whereIn('properties.host',$host);
      }
    }
    if(isset($date_of) && isset($date_of)){

      if(isset($start_date) && isset($end_date))
      {
        $q->whereDate('bookings.'.$date_of,'>=',$start_date)->whereDate('bookings.'.$date_of,'<=',$end_date);
        // $q->whereBetween('bookings.'.$date_of,[$start_date.' 00:00:01',$end_date.' 23:59:59']);
      }
    }
    else{
      if(isset($start_date) && isset($end_date))
      {
        $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
      }else{
        if(isset($start_date) && $end_date == null){
          $q->whereDate('bookings.created_at',$start_date);    
        }
      }
    }

    if(isset($search_type_list) && !empty($search_type_list))
    {
      if (!in_array("All", $search_type_list)) {
        $q->whereIn('properties.type',$search_type_list);
      }
    }
    if(isset($search_category))
    {
      if (!in_array("All", $search_category)) {
        $getPropertyByCat = PropertyCategory::whereIn('category_id', $search_category)->groupBy('property_id')->pluck('property_id')->toArray();
        $q->whereIn('properties.id',$getPropertyByCat);
      }
    }

    if(isset($search_country) && !empty($search_country))
    { 
      $q->where('property_address.country_id',$search_country);
    }
    if(isset($search_province))
    {
      $q->where('property_address.province_id',$search_province);
    }
    if(isset($search_city) && !empty($search_city))
    { 
      $q->where('property_address.city_id',$search_city);
    }
    if(isset($search_area))
    {
      $q->where('property_address.area',$search_area);
    }
    
    if ($search && !empty($search)) {
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
    $response = $q->orderBy($orderby, $order);
    // dd($response->get());
    return $response;
  }
}
