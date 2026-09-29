<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Facades\App;

class EmailTemplate extends Model
{    
    protected $table  ='email_templates';    
    protected $fillable = [
        'name','slug','subject','description','status'
    ];

    protected $hidden = [
        'updated_at', 'deleted_at',
    ];

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('email_templates.*');

        $orderby = $orderby ? $orderby : 'email_templates.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('email_templates.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('email_templates.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('email_templates.name', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
