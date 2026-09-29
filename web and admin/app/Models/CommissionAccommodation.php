<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CommissionAccommodation;

class CommissionAccommodation extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commission_id',
        'property_id',
        'property_name',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    // public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
    //     $q = $this->select('commission.*');

    //     $orderby = $orderby ? $orderby : 'commission.created_at';
    //     $order = $order ? $order : 'desc';
    //     if(isset($status) && in_array($status,[0,1,2]))
    //     {
    //         $q->where('commission.status',$status);
    //     }

    //     if(isset($start_date) && isset($end_date))
    //     {
    //         $q->whereBetween('commission.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
    //     }
       
    //     if ($search && !empty($search)) {
    //         $q->where(function($query) use ($search) {
    //             $query->where('commission.host_id', 'LIKE', '%' . $search . '%');
    //             $query->orWhere('commission.commission', 'LIKE', '%' . $search . '%');
    //         });
    //     }
    //     $response = $q->orderBy($orderby, $order);
    //     return $response;
    // }
}
