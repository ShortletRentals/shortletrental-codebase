<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\OfferAccommodation;

class BlogCategory extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'blog_category';
    // protected $fillable = [
    //     'blog_id',
    //     'name',
    //     'status',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('blog_category.*');

        $orderby = $orderby ? $orderby : 'blog_category.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('blog_category.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('blog_category.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('blog_category.name', 'LIKE', '%' . $search . '%');
                // $query->orWhere('offers.percentage', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        // dd($response);
        return $response;
    }
}
