<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyBedding extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_bedding';

    protected $fillable = [
        'property_id','bed_linen','bed_linen_change','bed_Change_frequency','washing_machine','dryer','iron','television','no_of_television','fans','satellite_tv','radio','dvd_player','satellite_tv_language','mosquito_netting','electronic_mosquito_repellents','internet_access','network_name','password','safe','mini_bar','key_code_number'
    ];

    public $timestamps = false;
}
