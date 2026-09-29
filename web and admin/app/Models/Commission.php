<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CommissionAccommodation;

class Commission extends Authenticatable
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
        'is_expire_mail_sent',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function getCommissionAccommodation()
    {
        return $this->hasMany(CommissionAccommodation::class,'property_id', 'id');
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('commissions.*','users.name')->leftjoin('users','users.id','=','commissions.host_id')->leftjoin('properties','properties.id','=','commissions.host_id');
        // dd($orderby, $order);
        if($orderby == 'host'){
            $orderby = 'users.name';
            $order = $order ? $order : 'desc';
        }else if($orderby == 'validity'){
            $orderby = 'commissions.start_date';
            $order = $order ? $order : 'desc';
        }else{
            $orderby = $orderby ? $orderby : 'commissions.created_at';
            $order = $order ? $order : 'desc';
        }
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('commissions.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('commissions.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                if($search != 'a' && $search != 'al' && $search != 'all' ){
                    $query->where('commissions.host_id', 'LIKE', '%' . $search . '%');
                    $query->orWhere('commissions.percentage', 'LIKE', '%' . $search . '%');   
                    $query->orWhere('users.name', 'LIKE', '%' . $search . '%');   
                }else{
                    $query->where('commissions.all_properties', 'Yes');
                    $query->orWhere('commissions.all_hosts', 'Yes');   
                }
            });
        }
        $response = $q->orderBy($orderby, $order);
        // dd($response);
        return $response;
    }

    public function getCommissionAccommodationData()
    {
        return $this->hasMany(CommissionAccommodation::class,'commission_id', 'id');
    }
}
