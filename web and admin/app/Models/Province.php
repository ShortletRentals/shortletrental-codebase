<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Province extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $fillable = [
        'country_id',
        'name',
        'image',
        'status',
    ];
    protected $tab  ='provinces'; 

    public function getImageAttribute($value)
    {
        if ($value) {
            if ($this->image_type == 'url') {
                return $value;
            } else {
                return fileUrl('s3',$value,'province/');
            }
        } else {
            return url('images/no-image.png');
        }
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('provinces.*', 'countries.name as country_name')->join('countries', 'provinces.country_id', '=', 'countries.id');

        $orderby = $orderby ? $orderby : 'provinces.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('provinces.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('provinces.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
                $q->whereDate('provinces.created_at',$start_date);    
            }
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('countries.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('provinces.name', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
    

}
