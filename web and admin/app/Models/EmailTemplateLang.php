<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class EmailTemplateLang extends Model
{
    protected $table  ='email_template_lang';    
    protected $fillable = [
        'name','slug','subject','description','email_id'
    ];

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('email_template_lang.*');

        $orderby = $orderby ? $orderby : 'email_template_lang.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('email_template_lang.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('email_template_lang.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }else{
            if(isset($start_date) && $end_date == null){
              $q->whereDate('email_template_lang.created_at',$start_date);    
            }
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('email_template_lang.name', 'LIKE', '%' . $search . '%');
                $query->orWhere('email_template_lang.subject', 'LIKE', '%' . $search . '%');
                $query->orWhere('email_template_lang.description', 'LIKE', '%' . $search . '%');
                // $query->orWhere('email_template_lang.name', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
}
