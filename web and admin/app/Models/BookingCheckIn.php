<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class BookingCheckIn extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    // protected $fillable = [
    //     'name',
    //     'image',
    //     'status',
    // ];
    protected $tab  ='booking_check_ins';


    public function getDocumentImageAttribute($value)
    {
        if ($value) {
            // if ($this->image_type == 'url') {
            //     return $value;
            // } else {
                return fileUrl('s3',$value,'property/');
            // }
        } else {
            return str_replace('http','https',url('images/default_user.png'));
        }
    }
}
