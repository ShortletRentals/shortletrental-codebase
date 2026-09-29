<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class CronLiveData extends Model
{    
    protected $hidden = [
        'updated_at', 'deleted_at',
    ];
    protected $fillable = [];
    protected $tab  ='cron_live_datas';

}
