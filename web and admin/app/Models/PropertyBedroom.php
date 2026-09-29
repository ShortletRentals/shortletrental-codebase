<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyBedroom extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_bedrooms';

    protected $fillable = [
        'property_id','bedrooms','no_of_king_size_beds','no_of_qween_size_beds','communal_zones'
    ];

    public $timestamps = false;
}
