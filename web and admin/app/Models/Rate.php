<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Rate extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $tab  ='rates'; 
    // protected $fillable = [
    //     'property_id','price','start_date','end_date','all_properties','status','created_at'
    // ];

    // public function getProperty()
    // {
    //     return $this->hasOne(Property::class,'id', 'property_id');
    // }

    // public function getUser()
    // {
    //     return $this->hasOne(User::class,'id', 'user_id');
    // }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('rates.*','properties.title','properties.code')->leftjoin('properties','properties.id','=','rates.property_id');

        $orderby = $orderby ? $orderby : 'rates.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('rates.status',$status);
        }
        // if (Auth::user()->id != '1') {
        //     $q->where('rates.user_id',Auth::user()->id);
        // }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('rates.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
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
