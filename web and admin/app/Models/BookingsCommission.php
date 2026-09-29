<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;
use Carbon\Carbon;

class BookingsCommission extends Model
{
  /*protected $fillable = [
    'booking_id', 'guest_id', 'host_id', 'stage', 'rental', 'from_date', 'to_date', 'host_amount', 'admin_amount', 'booking_status', 'status',
  ];*/

  protected $table = 'bookings';
  protected $tab = 'bookings';

  protected $hidden = [
    'updated_at', 'deleted_at',
  ];

  public function getProperty()
  {
      return $this->hasOne(Property::class,'id', 'property_id');
  }

  public function getModel($search=null, $orderby=null, $order=null,$accommodation=null,$search_building=null,$booking_status=null,$booking_type=null,$date_of=null,$start_date,$end_date,$user_type,$search_influencer = null,$search_customer = null,$search_host = null,$filter_by = null,$filter_by_date = null) {
    // dd($search_building);
    // $user_type = User::where('id',auth()->id())->pluck('user_type')->first();

    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    if(isset($user_type) && $user_type == 5){
      $q = $this->select('bookings.*','properties.title','properties.building')->leftjoin('properties','properties.id','bookings.property_id')->where('bookings.host_id',auth()->id());
    }else if(isset($user_type) && $user_type == 3){
      $q = $this->select('bookings.*','properties.title','properties.building')->leftjoin('properties','properties.id','bookings.property_id')->where('bookings.influencer_id',auth()->id());
    }else{
      $q = $this->select('bookings.*','properties.title','properties.building')->leftjoin('properties','properties.id','bookings.property_id');
    }
    // dd($q);
    // if($user_type == 5){
    //   $q->where('host_id',auth()->id());
    // }
    // dd($orderby, $order);
    if(isset($orderby) && $orderby == 'accommodation'){
      $orderby = 'properties.title';
    }elseif(isset($orderby) && $orderby == 'book'){
      $orderby = 'property.title';
    }else{
      $orderby = $orderby ? $orderby : 'bookings.created_at';
    }
    $orderby = $orderby;
    $order = $order ? $order : 'desc';
    // if(isset($status) && in_array($status,[0,1,2]))
    if(isset($search_building) && !empty($search_building))
    {
      $q->where('properties.building',$search_building);
    }
    if(isset($search_influencer) && !empty($search_influencer))
    {
      $q->where('bookings.influencer_id',$search_influencer);
    }
    if(isset($search_customer) && !empty($search_customer))
    {
      $q->where('bookings.guest_id',$search_customer);
    }
    if(isset($search_host) && !empty($search_host))
    {
      $q->where('bookings.host_id',$search_host);
    }
    if(isset($accommodation) && !empty($accommodation))
    {
      $q->where('properties.title', 'LIKE', '%' . $accommodation . '%');
    }
    if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
    {
      $q->where('bookings.booking_type',$booking_type);
    }
    if(isset($booking_status) && in_array($booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
    {
      $q->where('bookings.booking_status',$booking_status);
    }

    if(isset($date_of) && isset($date_of)){
      if(isset($start_date) && isset($end_date))
      {
        $q->whereDate('bookings.'.$date_of,'>=',$start_date)->whereDate('bookings.'.$date_of,'<=',$end_date);
        // $q->whereBetween('bookings.'.$date_of,[$start_date.' 00:00:01',$end_date.' 23:59:59']);
      }
    }

    if(isset($filter_by_date) && !empty($filter_by_date))
    {
      // dd($filter_by_date);
      if($filter_by == 'year'){
        $last_date = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $filter_by_date ) ) . "-12 month" ) );
        $q->whereDate('bookings.from_date','>=',$last_date)->whereDate('bookings.from_date','<=',$filter_by_date);
        // $q->whereBetween('bookings.from_date',[Carbon::now()->subMonth(12), Carbon::now()]);
      }else if($filter_by == 'month'){
        $last_date = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $filter_by_date ) ) . "-1 month" ) );
        $q->whereDate('bookings.from_date','>=',$last_date)->whereDate('bookings.from_date','<=',$filter_by_date);
        // $q->whereBetween('bookings.from_date',[Carbon::now()->subMonth(1), Carbon::now()]);
      }else{
        $q->where('from_date', '=', $filter_by_date.' 00:00:00');
        // $q->where('from_date', '=', date('Y-m-d').' 00:00:00');
      }
    }
    
    if ($search && !empty($search)) {
      // dd($search);
      $q->where(function($query) use ($search) {
        $query->where('properties.title', 'LIKE', '%' . $search . '%');
        // $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
      });
    }
    $response = $q->orderBy($orderby, $order);
    return $response;
  }
}
