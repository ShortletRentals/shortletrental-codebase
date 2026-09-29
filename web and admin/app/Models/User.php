<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'country_code',
        'mobile',
        'image',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'user_type',
        'is_super_host',
        'role',
        'status',
        'street_number',
        'address',
        'country_id',
        'province_id',
        'city_id',
        'postal_code',
        'latitude',
        'longitude',
        'landmark',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            if ($this->image_type == 'url') {
                return $value;
            } else {
                return fileUrl('s3',$value,'user/');

            }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getDocumentImageAttribute($value)
    {
        if ($value) {
            // if ($this->image_type == 'url') {
            //     return $value;
            // } else {
                return fileUrl('s3',$value,'user/');
             //   return url('uploads/user/' . $value);
            // }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getIdCardImageAttribute($value)
    {
        if ($value) {
            // if ($this->image_type == 'url') {
            //     return $value;
            // } else {
                return fileUrl('s3',$value,'user/');
               // return url('uploads/user/' . $value);
            // }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getModelUser($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date,$user_type) {
        // $q = $this->select('users.*')->whereIn('user_type', [1]);
        $q = $this->select('users.*');
        $q->whereNotNull('name');
        $q->whereIn('user_type', [$user_type]);

        $orderby = $orderby ? $orderby : 'users.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('users.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('users.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->orWhere('users.name', 'LIKE', '%' . $search . '%')->orWhere('users.surname', 'LIKE', '%' . $search . '%');
                $query->orWhere('users.email', 'LIKE', '%' . $search . '%')
                // ->orWhereRaw(DB::raw("CONCAT('users.name', 'users.surname')"), 'LIKE', '%' . $search . '%')
               // ->orWhereRaw("CONCAT(users.name,' ', users.surname) LIKE ?", ['%'.$search.'%'])
                // $query->whereRaw("CONCAT(users.name,' ', users.surname) LIKE ?", ['%'.$search.'%']);
                ->orWhereRaw("concat(name, ' ', surname) like '%" .$search. "%' ")
                ->orWhere('users.mobile', 'LIKE', '%' . $search . '%')
                ->orWhere('users.unique_id', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
	
	public function devices()
    {
        return $this->hasMany('App\Models\UserDevice','user_id');
	}
}
