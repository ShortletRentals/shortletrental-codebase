<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Area extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $fillable = [
        'country_id',
        'province_id',
        'city_id',
        'name',
        'image',
        'status',
    ];
    protected $tab  ='areas'; 

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        // dd($orderby, $order);
        $q = $this->select('areas.*','countries.id as country_id','countries.name as country_name','provinces.id as province_id','provinces.name as province_name','cities.id as city_id','cities.name as city_name')->leftjoin('countries','countries.id','=','areas.country_id')->leftjoin('provinces','provinces.id','=','areas.province_id')->leftjoin('cities','cities.id','=','areas.city_id');

        $orderby = $orderby ? $orderby : 'areas.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('areas.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('areas.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
                $q->whereDate('areas.created_at',$start_date);    
            }
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('areas.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('countries.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('provinces.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('cities.name', 'LIKE', '%' . $search . '%');
            });
        }
        if(isset($orderby) && $orderby == 'province_name'){
            $response = $q->orderBy('province_name', $order);
        }else if(isset($orderby) && $orderby == 'province_name'){
            $response = $q->orderBy($orderby, $order);
        }else if(isset($orderby) && $orderby == 'city_name'){
            $response = $q->orderBy($orderby, $order);
        }else{
            $response = $q->orderBy($orderby, $order);
        }
        
        return $response;
    }
    

}
