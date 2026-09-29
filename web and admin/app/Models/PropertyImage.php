<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyImage extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'property_id',
        'image',
        'image_type',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {

            if ($this->image_type == 'url') {
                return $value;
            } else {
                return fileUrl('s3',$value,'property/');
            }

        } else {
            return url('images/default_user.png');
        }
    }

}
