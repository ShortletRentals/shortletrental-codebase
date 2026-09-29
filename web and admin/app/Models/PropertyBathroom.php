<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyBathroom extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_bathrooms';

    protected $fillable = [
        'property_id','bathroom_with_bathtub','bathroom_with_shower','toilets','sauna','jacuzzi','hair_dryer','towels','towel_change','towel_change_frequency'
    ];

    public $timestamps = false;
}
