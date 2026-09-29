<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Subscription extends Model
{    
    protected $hidden = [
        'updated_at',
    ];

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('subscriptions.*');

        $orderby = $orderby ? $orderby : 'subscriptions.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('subscriptions.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('subscriptions.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
                $q->whereDate('subscriptions.created_at',$start_date);    
            }
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('subscriptions.email', 'LIKE', '%' . $search . '%');
                $query->orWhere('subscriptions.type', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
