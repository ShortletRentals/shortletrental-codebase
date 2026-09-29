<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;
use Carbon\Carbon;

class Booking extends Model
{
  /*protected $fillable = [
    'booking_id', 'guest_id', 'host_id', 'stage', 'rental', 'from_date', 'to_date', 'host_amount', 'admin_amount', 'booking_status', 'status',
  ];*/

  protected $hidden = [
    'updated_at', 'deleted_at',
  ];

  public function getProperty()
  {
      return $this->hasOne(Property::class,'id', 'property_id');
  }

  public function getLoyaltyPoints()
  {
      return $this->hasMany(UserLoyaltyPoint::class,'order_id', 'id');
  }

  public function getModel($search=null, $orderby=null, $order=null,$booking_status=null,$booking=null,$booking_type=null,$start_date,$end_date,$user_type, $incoming_booking_confirm,$from_date,$pending_actions,$booking_id, $guest_id,$date_of=null) {
    // dd($incoming_booking_confirm,$from_date);
    // dd($date_of);
    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    if($user_type == 3){
      $q = Booking::select('bookings.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->join('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id')->where('bookings.influencer_id',auth()->id());
      if($user_type == 5){
        $q->where('host_id',auth()->id());
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
      if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid','Pending-payment']))
      {
        // dd($booking_type);
        if($booking_type == 'Pending-payment'){
          $q->where('bookings.booking_type','!=','Confirmed')->where('bookings.booking_type','!=','Paid');
        }else{
          $q->where('bookings.booking_type',$booking_type);
        }
      }
      if(isset($booking_status) && in_array($booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
      {
        $q->where('bookings.booking_status',$booking_status);
      }
      if(isset($date_of) && isset($date_of)){

          if(isset($start_date) && isset($end_date))
          {
            $q->whereDate('bookings.'.$date_of,'>=',$start_date)->whereDate('bookings.'.$date_of,'<=',$end_date);
          }else{
              if (isset($start_date) && !empty($start_date)) {
                  $q->whereDate('bookings.'.$date_of,'>=',$start_date);
              }else{
                  if (isset($end_date) && !empty($end_date)) {
                      $q->whereDate('bookings.'.$date_of,'<=',$end_date);
                  }
              }
          }
      }else{
        if(isset($start_date) && isset($end_date))
        {
          $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
          if(isset($start_date) && $end_date == null){
            $q->whereDate('bookings.created_at',$start_date);    
          }
        }
      }
      

      if(isset($booking_id) && !empty($booking_id))
      {
        $q->where('bookings.booking_id',$booking_id);
      }

      if(isset($guest_id) && !empty($guest_id))
      {
        $q->where('bookings.guest_id',$guest_id);
      }
      
      if ($search && !empty($search)) {
        // dd($search);
        $q->where(function($query) use ($search) {
          $query->where('bookings.booking_id', 'LIKE', '%' . $search . '%');
          $query->orWhere('bookings.booking_type', 'LIKE', '%' . $search . '%');
          $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
        });
      }
      $response = $q->orderBy($orderby, $order);
      return $response;
    }else{
      // $q = $this->select('bookings.*');
      $q = Booking::select('bookings.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->leftjoin('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id');
      // dd($q->count());
      if($user_type == 5){
        $q->where('host_id',auth()->id());
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
      if(isset($booking_type) && in_array($booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid','Pending-payment']))
      {
        // $q->where('bookings.booking_type',$booking_type);
        if($booking_type == 'Pending-payment'){
          $q->where('bookings.booking_type','!=','Confirmed')->where('bookings.booking_type','!=','Paid');
        }else{
          $q->where('bookings.booking_type',$booking_type);
        }
      }
      if(isset($booking_status) && in_array($booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
      {
        $q->where('bookings.booking_status',$booking_status);
      }
      if(isset($booking) && !empty($booking)){
        if($booking == 'Website'){
          $q->where('bookings.booking_from',$booking);
        }else if($booking == 'Mobile'){
          $q->where('bookings.booking_from',$booking);
        }else if($booking == 'Guest_bookings'){
          $guest_ids = User::where(['status'=>1, 'is_guest'=>1])->pluck('id')->toArray();
          $q->whereIn('guest_id',$guest_ids);
        }else if($booking == 'Customer_bookings'){
          $customer_ids = User::where(['status'=>1, 'is_guest'=>0])->pluck('id')->toArray();
          $q->whereIn('guest_id',$customer_ids);
        }
      }

      /*if(isset($start_date) && isset($end_date))
      {
        $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
      }else{
        if(isset($start_date) && $end_date == null){
          $q->whereDate('bookings.created_at',$start_date);    
        }
      }*/
      if(isset($date_of) && isset($date_of)){

          if(isset($start_date) && isset($end_date))
          {
            $q->whereDate('bookings.'.$date_of,'>=',$start_date)->whereDate('bookings.'.$date_of,'<=',$end_date);
          }else{
              if (isset($start_date) && !empty($start_date)) {
                  $q->whereDate('bookings.'.$date_of,'>=',$start_date);
              }else{
                  if (isset($end_date) && !empty($end_date)) {
                      $q->whereDate('bookings.'.$date_of,'<=',$end_date);
                  }
              }
          }
      }else{
        if(isset($start_date) && isset($end_date))
        {
          $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
          if(isset($start_date) && $end_date == null){
            $q->whereDate('bookings.created_at',$start_date);    
          }
        }
      }

      if(isset($booking_id) && !empty($booking_id))
      {
        $q->where('bookings.booking_id',$booking_id);
      }

      if(isset($guest_id) && !empty($guest_id))
      {
        $q->where('bookings.guest_id',$guest_id);
      }
      
      if ($search && !empty($search)) {
        // dd($search);
          $q->where(function($query) use ($search) {
          $query->where('properties.title', 'LIKE', '%' . $search . '%');
          // $query->where('bookings.booking_type', 'LIKE', '%' . $search . '%');
          // $query->orWhere('bookings.total_amount', 'LIKE', '%' . $search . '%');
        });
      }
      if(isset($incoming_booking_confirm) && !empty($from_date)){
        if(isset($incoming_booking_confirm) && $incoming_booking_confirm == 'confirm'){
          if($from_date == 'last_7_days'){
            $last_7_day_date = \Carbon\Carbon::today()->subDays(7);
            $q->where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] );
          }else if($from_date == 'last_30_days'){
            $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
            $q->where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_30_day_date, Carbon::now()] );
          }else if($from_date == 'today'){
            $q->where('booking_status','Confirmed-by-Host')->where('from_date', '=', date('Y-m-d').' 00:00:00');
          }
        }else if(isset($incoming_booking_confirm) && $incoming_booking_confirm == 'instant'){
          if($from_date == 'last_7_days'){
            $last_7_day_date = \Carbon\Carbon::today()->subDays(7);
            $q->whereBetween('bookings.created_at', [$last_7_day_date, Carbon::now()] );
          }else if($from_date == 'last_30_days'){
            $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
            $q->whereBetween('bookings.created_at', [$last_30_day_date, Carbon::now()] );
          }else if($from_date == 'today'){
            $q->whereDate('bookings.created_at', '=', date('Y-m-d'));
          }
        }else{
          $q->where('booking_status','Confirmed');
        }
      }
      if(isset($pending_actions) && !empty($from_date)){
        // dd($pending_actions, $from_date);
        if($from_date == 'last_7_days'){
          $last_7_day_date = \Carbon\Carbon::today()->subDays(7);
          $q->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereBetween('bookings.created_at', [$last_7_day_date, Carbon::now()] )->where('booking_check_ins.booking_id',null);
          // $q->where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] );
        }else if($from_date == 'last_30_days'){
          $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
          $q->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereBetween('bookings.created_at', [$last_30_day_date, Carbon::now()] )->where('booking_check_ins.booking_id',null);
        }else if($from_date == 'today'){
          $q->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereDate('bookings.created_at','=', date('Y-m-d') )->where('booking_check_ins.booking_id',null);
        }
      }
      $response = $q->orderBy($orderby, $order);
      return $response;
    }
  }
}
