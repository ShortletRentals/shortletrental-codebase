<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Rating extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $tab  ='ratings'; 

    public function getProperty()
    {
        return $this->hasOne(Property::class,'id', 'property_id');
    }

    public function getUser()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }
}
