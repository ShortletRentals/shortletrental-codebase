<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Wishlist extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $tab  ='wishlists'; 

    public function getProperty()
    {
        return $this->hasOne(Property::class,'id', 'product_id');
    }
}
