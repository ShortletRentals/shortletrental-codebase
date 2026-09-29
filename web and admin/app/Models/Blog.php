<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\OfferAccommodation;

class Blog extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'blogs';
    // protected $fillable = [
    //     'blog_category',
    //     'image',
    //     'title',
    //     'slug',
    //     'description',
    //     'status',
    // ];

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
                return fileUrl('s3',$value,'blog/');
            }
        } else {
            return url('images/default_user.png');
        }
    }

    public function getBlogCategoryData()
    {
        return $this->hasOne(BlogCategory::class,'id', 'blog_category');
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('blogs.*','blog_category.name')->leftjoin('blog_category','blog_category.id','=','blogs.blog_category');

        $orderby = $orderby ? $orderby : 'blogs.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('blogs.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('blogs.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('blogs.title', 'LIKE', '%' . $search . '%');
                $query->orWhere('blog_category.name', 'LIKE', '%' . $search . '%');
                // $query->orWhere('offers.percentage', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        // dd($response);
        return $response;
    }
}
