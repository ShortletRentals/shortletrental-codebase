<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;
use App\Models\Discount;

class CustomerDiscount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'code',
    //     'percentage',
    //     'category_id',
    //     'start_date',
    //     'end_date',
    //     'total_use',
    //     'total_single_use',
    //     'status',
    // ];
    protected $table = ['bookings'];
    protected $tab = ['bookings'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /*public function getImageAttribute($value)
    {
        if ($value) {
            if ($this->image_type == 'url') {
                return $value;
            } else {
                return url('uploads/category/' . $value);
            }
        } else {
            return url('images/default_user.png');
        }
    }*/

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date,$id) {
        // dd(auth()->id());
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        $discountData = Discount::where('id',$id)->first();
        // dd($discountData);
        if(isset($user_type) && $user_type == 3){
            $q = DB::table('bookings')->where(['influencer_id'=>auth()->id(), 'coupon_code'=>$discountData->code ]);
        }else{
            $q = DB::table('bookings')->where(['coupon_code'=>$discountData->code ]);
        }
        
        $orderby = $orderby ? $orderby : 'bookings.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('bookings.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('bookings.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
              $q->whereDate('bookings.created_at',$start_date);    
            }
          }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('bookings.coupon_code', 'LIKE', '%' . $search . '%');
                $query->orWhere('bookings.personal_first_name', 'LIKE', '%' . $search . '%');
                $query->orWhere('bookings.personal_last_name', 'LIKE', '%' . $search . '%');
                $query->orWhere('bookings.discount_amount', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        // dd($response->get());
        return $response;
    }
}
