<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\OfferAccommodation;

class Offer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'host_id',
        'percentage',
        'start_date',
        'end_date',
        'status',
    ];
     
     protected $appends = ['discount_code'];

    public function getDiscountCodeAttribute($value)
    {
        return  Discount::where('id',$this->discount)->first();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function getImageAttribute($value)
    {
        if ($value) {
            if ($this->image_type == 'url') {
                return $value;
            } else {
                return fileUrl('s3',$value,'offer/');
            }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getOfferAccommodation()
    {
        return $this->hasMany(OfferAccommodation::class,'property_id', 'id');
    }

    public function getDiscountData()
    {
        return $this->hasOne(Discount::class,'id', 'discount');
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('offers.*','discounts.code')->leftjoin('discounts','discounts.id','=','offers.discount');

        $orderby = $orderby ? $orderby : 'offers.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('offers.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('offers.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('discounts.code', 'LIKE', '%' . $search . '%');
                // $query->orWhere('offers.percentage', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        // dd($response);
        return $response;
    }
}
