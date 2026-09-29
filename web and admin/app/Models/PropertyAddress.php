<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyAddress extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_address';

    protected $fillable = [
        'property_id','country','state','province','city','area','postal_code','building_no','floor','apartment_no'
    ];

    public $timestamps = false;

    public function getPropertyCity()
    {
        return $this->hasOne(City::class,'id', 'city_id');
    }

    public function getPropertyArea()
    {
        return $this->hasOne(Area::class,'id', 'area');
    }

    public function getPropertyProvince()
    {
        return $this->hasOne(Province::class,'id', 'province_id');
    }

    public function getPropertyCountry()
    {
        return $this->hasOne(Country::class,'id', 'country_id');
    }
}
