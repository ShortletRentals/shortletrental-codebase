<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartSplitBills;
use Auth;
use Illuminate\Support\Facades\App;

class AdminNotification extends Model
{   
    protected $table = 'admin_notification';

    protected $fillable = [
        'notification_type','title','message','user_id','is_read','status','created_at','notification_for'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function getTitleAttribute($value)
    {
      $locale = App::getLocale();
      $data =  AdminNotificationLang::select('title')->where(['admin_notification_id' => $this->id, 'lang' => $locale])->first();
      return $data->title ?? $value;
    }
    public function getMessageAttribute($value)
    {
      $locale = App::getLocale();
      $data =  AdminNotificationLang::select('message')->where(['admin_notification_id' => $this->id, 'lang' => $locale])->first();
      return $data->message ?? $value;
    }

    public function getModel($search=null, $orderby=null, $order=null,$status=null,$start_date,$end_date) {
        $q = $this->select('admin_notification.*');

        $orderby = $orderby ? $orderby : 'admin_notification.created_at';
        $order = $order ? $order : 'desc';
        if(isset($status) && in_array($status,[0,1,2]))
        {
            $q->where('admin_notification.status',$status);
        }

        if(isset($start_date) && isset($end_date))
        {
            $q->whereBetween('admin_notification.created_at',[$start_date.' 00:00:01',$end_date.' 23:59:59']);
        }
       
        if ($search && !empty($search)) {
            $q->where(function($query) use ($search) {
                $query->where('admin_notification.title', 'LIKE', '%' . $search . '%');
                $query->orWhere('admin_notification.message', 'LIKE', '%' . $search . '%');
                $query->orWhere('admin_notification.notification_for', 'LIKE', '%' . $search . '%');
            });
        }
        $response = $q->orderBy($orderby, $order);
        return $response;
    }
   
}
