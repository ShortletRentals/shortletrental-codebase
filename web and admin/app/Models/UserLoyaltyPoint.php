<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UserLoyaltyPoint extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $fillable = [
        'user_id',
        'order_id',
        'points',
        'title',
        'description',
        'second_royalty_amount',
        'status',
    ];
    protected $tab = 'user_loyalty_points';
}
