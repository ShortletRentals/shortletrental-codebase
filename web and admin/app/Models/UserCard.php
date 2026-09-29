<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UserCard extends Model
{    
    protected $hidden = [
        'updated_at'
    ];
    protected $fillable = [
        'user_id',
        'card_holder_name',
        'card_number',
        'month',
        'year',
        'cvv',
        'defalult_card',
    ];
    protected $tab  ='user_cards'; 

    // public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
    //     $q = $this->select('cities.*', 'countries.name as country_name', 'provinces.name as province_name')->join('countries', 'cities.country_id', '=', 'countries.id')->join('provinces', 'cities.province_id', '=', 'provinces.id');

    //     $orderby = $orderby ? $orderby : 'cities.created_at';
    //     $order = $order ? $order : 'desc';
    //     if(isset($status) && in_array($status,[0,1,2]))
    //     {
    //         $q->where('cities.status',$status);
    //     }

    //     if(isset($start_date) && isset($end_date))
    //     {
    //         $q->whereBetween('cities.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
    //     }
       
    //     if ($search && !empty($search)) {
    //         $q->where(function($query) use ($search) {
    //             $query->where('cities.name', 'LIKE', '%' . $search . '%');
    //         });
    //     }
    //     $response = $q->orderBy($orderby, $order);
    //     return $response;
    // }
    

}
