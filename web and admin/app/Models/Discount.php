<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\DiscountProperty;

class Discount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'percentage',
        'category_id',
        'start_date',
        'end_date',
        'total_use',
        'total_single_use',
        'status',
    ];

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
                return fileUrl('s3',$value);
            }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {

        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        if(isset($user_type) && $user_type == 3){
            $q = $this->select('discounts.*')->where('influencer',auth()->id());
        }else if(isset($user_type) && $user_type == 5){
            $properties_ids = Property::where('host',auth()->id() )->pluck('id')->toArray();
            $yes_discount_ids = $this->select('discounts.*')->where('all_properties','Yes')->pluck('id')->toArray();
            $no_discount_ids = DiscountProperty::whereIn('property_id',$properties_ids)->pluck('discount_id')->toArray();
            $discount_ids = array_merge($yes_discount_ids, $no_discount_ids);
            $q = $this->select('discounts.*')->whereIn('id',$discount_ids);
        }else{
            $q = $this->select('discounts.*');
        }
        // dd($q->get());
        $orderby = $orderby ? $orderby : 'discounts.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('discounts.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('discounts.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('discounts.code', 'LIKE', '%' . $search . '%');
                // $query->orWhere('discounts.percentage', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
