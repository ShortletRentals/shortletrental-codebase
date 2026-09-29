<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;
Use \Carbon\Carbon;

class Appointment extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $fillable = [
        'title',
        'full_name',
        'surname',
        'email',
        'country_code',
        'mobile',
        'appointment_date',
        'status',
    ];
    protected $tab  ='appointments'; 

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        // dd($orderby, $order);
        $q = $this->select('appointments.*');

        if($orderby == 'name'){
            $orderby = 'appointments.full_name';
            $order = $order ? $order : 'desc';
        }else{
            $orderby = $orderby ? $orderby : 'appointments.created_at';
            $order = $order ? $order : 'desc';
        }


        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('appointments.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
                $q->whereDate('appointments.created_at',$start_date);    
            }
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                // $query->where('appointments.full_name', 'LIKE', '%' . $search . '%');
                $query->orWhereRaw("CONCAT(appointments.full_name,' ', appointments.surname) LIKE ?", ['%'.$search.'%']);
                $query->orWhere('appointments.email', 'LIKE', '%' . $search . '%');
                $query->orWhere('appointments.mobile', 'LIKE', '%' . $search . '%');
                $query->orWhere('appointments.address', 'LIKE', '%' . $search . '%');
            });
        }




        if(isset($status) && in_array($status,['Upcoming','Expire']))
        {
            if ($status == 'Upcoming') {
                $q->where('appointment_date', '>=', date('Y-m-d'));
            }else{
                $q->where('appointment_date', '<', date('Y-m-d'));
                $order = 'desc';
            }
        }else{
            $q->where('appointment_date', '>=', date('Y-m-d'));
        }
        if (isset($orderby) && $orderby) {
            $currentDate = Carbon::now();
                $response = $q->orderBy($orderby, $order); 
        }
        
        return $response;
    }
    

}
