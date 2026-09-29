<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyExtraService extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_extra_services';

    protected $fillable = [
        'property_id',
        'service_id',
    ];

    public function getServiceData()
    {
        return $this->hasOne(ExtraService::class,'id', 'service_id');
    }

}
