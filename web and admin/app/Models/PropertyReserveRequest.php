<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class PropertyReserveRequest extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $tab  ='property_reserve_requests'; 

    public function getProperty()
    {
        return $this->hasOne(Property::class,'id', 'property_id');
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$type=null,$start_date,$end_date,$user_type,$incoming_booking_confirm) {
        // dd($orderby, $order);
        // dd($incoming_booking_confirm);
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        if(isset($user_type) && $user_type == 5){
            $q = $this->select('property_reserve_requests.*')->where('property_reserve_requests.host_id',auth()->id() );
        }else if(isset($user_type) && $user_type == 3){
            $q = $this->select('property_reserve_requests.*')->where('property_reserve_requests.influencer_id',auth()->id() );
        }else{
            $q = $this->select('property_reserve_requests.*');
        }

        $orderby = $orderby ? $orderby : 'property_reserve_requests.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('property_reserve_requests.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('property_reserve_requests.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
        else
        {
            if(isset($start_date) && $end_date == null){
                $q->whereDate('property_reserve_requests.created_at',$start_date);
            }
        }
        // if(isset($incoming_booking_confirm) && !empty($from_date)){
        //     dd($incoming_booking_confirm);
        //   if(isset($incoming_booking_confirm) && $incoming_booking_confirm == 'confirm'){
        //     if($from_date == 'last_7_days'){
        //       $last_7_day_date = \Carbon\Carbon::today()->subDays(7);
        //       $q->whereBetween('from_date', [$last_7_day_date, Carbon::now()] );
        //     }else if($from_date == 'last_30_days'){
        //       $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
        //       $q->whereBetween('from_date', [$last_30_day_date, Carbon::now()] );
        //     }else if($from_date == 'today'){
        //       $q->where('from_date', '=', date('Y-m-d').' 00:00:00');
        //     }
        //   }else{
        //     $q->where('booking_status','Confirmed');
        //   }
        // }
        // dd($q->get());
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                
                $query->where('property_reserve_requests.booking_id', 'LIKE', '%' . $search . '%');
                $query->orWhere('property_reserve_requests.from_date', 'LIKE', '%' . $search . '%');
                $query->orWhere('property_reserve_requests.to_date', 'LIKE', '%' . $search . '%');                
                $query->orWhere('property_reserve_requests.total_amount', 'LIKE', '%' . $search . '%');
                $query->orWhereRaw("concat(property_reserve_requests.personal_first_name, ' ', property_reserve_requests.personal_last_name) like '%$search%' ");
//                $query->orWhereRaw("CONCAT('property_reserve_requests.personal_first_name', " ", 'property_reserve_requests.personal_last_name'), 'LIKE', '%'.$search.'%');
            });
        }
        if(isset($orderby) && $orderby){
            $response = $q->orderBy($orderby, $order);   
        }
        
        return $response;
    }
    

}
