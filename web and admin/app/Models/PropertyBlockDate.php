<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class PropertyBlockDate extends Model
{    
    protected $hidden = [
        'updated_at',
    ];
    protected $tab = 'property_block_dates';   

}
