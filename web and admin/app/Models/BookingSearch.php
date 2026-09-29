<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;
use App\Models\Booking;
use App\Models\Building;
use Carbon\Carbon;


class BookingSearch extends Model
{
  protected $table = 'properties';
  protected $fillable = [
    'booking_id', 'guest_id', 'host_id', 'stage', 'rental', 'from_date', 'to_date', 'host_amount', 'admin_amount', 'booking_status', 'status',
  ];

  protected $hidden = [
    'updated_at', 'deleted_at',
  ];

  public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date=null,$end_date=null,$search_city=null,$search_area=null,$search_accommodation=null,$search_building=null,$max_guest_capacity=null,$search_bedroom=null,$search_category=null,$search_type_list=null,$user_type=null) {
    $q = $this->select('properties.*','property_address.city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id');

    if($user_type == 5){
      $q->where('host',auth()->id());
    }
    if(isset($orderby) && $orderby == 'stay'){
      $orderby = 'bookings.from_date';
    }elseif(isset($orderby) && $orderby == 'book'){
      $orderby = 'property.title';
    }else{
      $orderby = $orderby ? $orderby : 'bookings.created_at';
    }
    $orderby = $orderby;
    $order = $order ? $order : 'desc';
    // if(isset($status) && in_array($status,[0,1,2]))
    // if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
    // {
    //   $q->where('bookings.booking_type',$booking_type);
    // }
    if(isset($status) )
    { 
      $q->where('properties.status',$status);
    }
    if(isset($start_date) && !empty($end_date))
    {
      // dd($start_date, $end_date);
      // $bookings = Booking::where(['from_date'=>$start_date, 'to_date'=>$end_date])->pluck('property_id')->toArray();
      //$bookings = Booking::whereDate('from_date',$start_date)->whereDate('to_date',$end_date)->pluck('property_id')->toArray();
      // dd($bookings);
      // $bookings = Booking::whereBetween('created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59'])->pluck('property_id')->toArray();

      $alreadybookingFrom = Booking::where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('from_date', [$start_date, $end_date])->pluck('property_id')->toArray();
      $bookings = Booking::where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('to_date', [$start_date, $end_date])->pluck('property_id')->toArray();
      $q->whereNotIn('properties.id',$bookings)->whereNotIn('properties.id',$alreadybookingFrom);
      // $q->whereDate('bookings.from_date',$start_date);
      // $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
    }else{
      if(isset($start_date) && $end_date == null){
        $bookings = Booking::whereDate('from_date',$start_date)->pluck('property_id')->toArray();
        if(isset($bookings) && !empty($bookings)){
          $q->whereNotIn('properties.id',$bookings);
        }
      }
      // dd($bookings);
    }
    // if(isset($end_date))
    // {
    //   $q->whereDate('bookings.to_date',$end_date);
    //   // $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
    // }
    if(isset($search_city))
    {
      $q->where('property_address.city_id',$search_city);
    }
    if(isset($search_area))
    {
      $q->where('property_address.area',$search_area);
    }
    if(isset($search_accommodation))
    {
      $q->where('properties.title', 'LIKE', '%' . $search_accommodation . '%');
    }
    if(isset($search_building))
    {
      $building_ids = Building::Where('name', 'LIKE', '%' . $search_building . '%')->pluck('id')->toArray();
      $q->whereIn('properties.building',$building_ids);
    }
    if(isset($max_guest_capacity))
    {
      $q->where('properties.max_guest','>=',$max_guest_capacity);
    }
    if(isset($search_bedroom))
    {
      $q->where('property_bedrooms.no_of_bedrooms',$search_bedroom);
    }
    if(isset($search_category))
    {
      $q->where('properties.category',$search_category);
    }
    if(isset($search_type_list))
    {
      // dd($search_type_list);
      $q->orWhere('properties.type',$search_type_list);
    }
    
    if ($search && !empty($search)) {
      // dd($search);
      $q->where(function($query) use ($search) {
        // dd($search);
        $query->where('properties.title', 'LIKE', '%' . $search . '%')
              ->orWhere('properties.price', 'LIKE', '%' . $search . '%');
        // $query->where('bookings.booking_type', 'LIKE', '%' . $search . '%');
        // $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
      });
    }
    $response = $q->orderBy($orderby, $order);
    return $response;
  }
}
