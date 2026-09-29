<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyKitchen extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_kitchen';

    protected $fillable = [
        'property_id','no_of_kitchens','kitchen_type','kitchen_category','kitchen_amenities'
    ];

    public $timestamps = false;
}

