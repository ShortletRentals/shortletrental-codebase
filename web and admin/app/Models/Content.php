<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth, App;
use App\Models\ContentLang;

class Content extends Model
{
  // protected $table  ='contents';  
  protected $fillable = [
    'name', 'description', 'status',
  ];

  protected $hidden = [
    'updated_at',
  ];

  public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
    // dd($user_type);
    if($user_type == 5){
      $slug = ['about-us','term-of-use','privacy-policy'];
      $q = $this->select('contents.*')->whereIn('slug',$slug);
    }else{
      $q = $this->select('contents.*');
    }

    $orderby = $orderby ? $orderby : 'contents.created_at';
    $order = $order ? $order : 'desc';
    if(isset($status) && in_array($status,[0,1,2]))
    {
      $q->where('contents.status',$status);
    }

    if(isset($start_date) && isset($end_date))
    {
      $q->whereBetween('contents.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
    }
    
    if ($search && !empty($search)) {
      $q->where(function($query) use ($search) {
        $query->where('contents.name', 'LIKE', '%' . $search . '%');
      });
    }
    $response = $q->orderBy($orderby, $order);
    return $response;
  }
}
