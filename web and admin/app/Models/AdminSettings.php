<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class AdminSettings extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $fillable = [
        'commission',
        'loyalty_point',
        'loyalty_point_amount',
        'loyalty_percentage',
        'royalty_point_equal_to',
        'second_royalty_amount',
        'title',
        'email',
        'country_code',
        'mobile',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'whatsup_url',
        'country',
    ];
    protected $tab = 'admin_settings';
}
