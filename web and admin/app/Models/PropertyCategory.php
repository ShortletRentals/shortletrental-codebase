<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PropertyCategory extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'property_category';

    protected $fillable = [
        'property_id',
        'category_id',
    ];

    public function getCategoryData()
    {
        return $this->hasOne(Category::class,'id', 'category_id');
    }

}
