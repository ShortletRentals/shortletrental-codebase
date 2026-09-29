<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;
use App\Models\Booking;
use App\Models\Building;
use Carbon\Carbon;


class BecomeAHost extends Model
{
  protected $table = 'properties';
  // protected $fillable = [
  //   'booking_id', 'guest_id', 'host_id', 'stage', 'rental', 'from_date', 'to_date', 'host_amount', 'admin_amount', 'booking_status', 'status',
  // ];

  protected $hidden = [
    'updated_at', 'deleted_at',
  ];

  public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date=null,$end_date=null) {
    $q = $this->select('properties.*','property_address.city_id','property_address.area', 'users.id as user_id', 'users.name as user_name','users.country_code','users.mobile','users.email')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('users','users.id','=','properties.host')->where('properties.created_by','Host')/*->where('properties.host_property_status','!=','Accept')*/;

    // $q = $this->select('bookings.*','properties.code','properties.title','properties.category','property_address.city_id','properties.building','properties.status')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');
    if(isset($orderby) && $orderby == 'stay'){
      $orderby = 'bookings.from_date';
    }elseif(isset($orderby) && $orderby == 'book'){
      $orderby = 'property.title';
    }else{
      $orderby = $orderby ? $orderby : 'properties.created_at';
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
      $q->whereBetween('properties.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);;
    }
        
    if ($search && !empty($search)) {
      // dd($search);
      $q->where(function($query) use ($search) {
        $query->where('properties.title', 'LIKE', '%' . $search . '%');
        $query->orWhere('users.name', 'LIKE', '%' . $search . '%');
      });
    }
    $response = $q->orderBy($orderby, $order);
    return $response;
  }
}
