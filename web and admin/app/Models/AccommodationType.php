<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class AccommodationType extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $fillable = [
        'name',
        'image',
        'status',
    ];
    protected $tab  ='accommodation_types';

    public function getImageAttribute($value)
    {
        if ($value) {
            if ($this->image_type == 'url') {
                return $value;
            } else {
                return fileUrl('s3',$value,'property/');
            }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('accommodation_types.*');

        $orderby = $orderby ? $orderby : 'accommodation_types.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('accommodation_types.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('accommodation_types.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('accommodation_types.name', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
