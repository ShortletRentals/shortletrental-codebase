<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\Notification;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Country;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    protected  $page = 'notification';
    protected  $lang = 'Notification';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new AdminNotification();
        $this->sortableColumns = [
            0 => 'notification_for',
            1 => 'title',
            2 => 'message',
            3 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Notification-section');
        
        if ($request->wantsJson()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];
            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $status = $request['status'] ?? null;
            $start_date = $request['start_date'] ?? null ;
            $end_date = $request['end_date'] ?? null; 
            $sortableColumns = $this->sortableColumns;            
            // dd($search);
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date);
            $totaldata = $querydata->count();
            $response = $querydata;
          
            $response = $response->offset($start)
                ->limit($limit)
                ->get();

            if (!$response) {
                $data = [];
                $paging = [];
            } else {
                $data = $response;
                $paging = $response;
            }
            
            $datas = array();
            $i = 1;
            foreach ($data as $value) {
                $row['id'] = $i;
                $row['notification_for'] = isset($value->notification_for)? $value->notification_for:'';
                $row['title'] = isset($value->title)? $value->title:'N/A';
                $row['message'] = isset($value->message)? $value->message:'N/A';
                // $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
              
                
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Delete Notification" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                if (!auth()->user()->can('Notification-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Notification-delete')) {
                    $delete = '';
                }
                $row['actions']=createAction($delete);
                // $row['actions']=createAction($edit.$view);
                $datas[] = $row;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($totaldata),
                "recordsTotal" => intval($totaldata),
                "data" => $datas
            ];
            return $return;
        }
        if(isset($request->user_type)){
            $user_type = $request->user_type;

        }else{
            $user_type = null;
        }
        if(isset($request->status)){
            $status = $request->status;

        }else{
            $status = null;
        }
        
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'status'=>$status];
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function frontend()
    {
        Gate::authorize('Notification-section');
        $user = User::where('user_type', '2')->get();
        $roles = Role::all();
        $data= ['title'=>$this->lang,'page'=>$this->page,'roles'=>$roles]; 
        return view('admin.'.$this->page.'.listing', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Notification-create');
        $subadmin_users = User::select('id','name','surname','email')->where(['status'=>1,'user_type'=>2])->get();
        $influencer_partner_users = User::select('id','name','surname','email')->where(['status'=>1,'user_type'=>3])->get();
        $customer_users = User::select('id','name','surname','email')->where(['status'=>1,'user_type'=>4])->get();
        $host_owner_users = User::select('id','name','surname','email')->where(['status'=>1,'user_type'=>5])->get();
        // dd($subadmin_users);
        $data= ['title'=>$this->lang,'page'=>$this->page, 'subadmin_users'=>$subadmin_users, 'influencer_partner_users'=>$influencer_partner_users, 'customer_users'=>$customer_users, 'host_owner_users'=>$host_owner_users];
        return view('admin.'.$this->page.'.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    { 
        Gate::authorize('Notification-create');
        $input = $request->all();
        $message = [];
        $validation = [
            'title'          => 'required|max:190',
            // 'message'          => 'required',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new AdminNotification;

            
            $data->notification_for = $input['notification_for']??null;
            $data->title = $input['title'];
            $data->message = $input['message'];
            $data->status = 1;
            
            if($data->save()){
                if($input['notification_for'] == 'Sub-Admin'){
                    if(isset($input['all_subadmin_users']) && $input['all_subadmin_users'] == 'Yes'){
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>2])->get();
                    }else{
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>2])->whereIn('id',$input['subadmin_users'])->get();
                    }
                }else if($input['notification_for'] == 'Influencer'){
                    if(isset($input['all_influencer_users']) && $input['all_influencer_users'] == 'Yes'){
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>3])->get();
                    }else{
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>3])->whereIn('id',$input['influencer_users'])->get();
                    }
                }else if($input['notification_for'] == 'Customer'){
                    if(isset($input['all_customer_users']) && $input['all_customer_users'] == 'Yes'){
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>4])->get();
                    }else{
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>4])->whereIn('id',$input['customer_users'])->get();
                    }
                }else if($input['notification_for'] == 'Host'){
                    if(isset($input['all_host_users']) && $input['all_host_users'] == 'Yes'){
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>5])->get();
                    }else{
                        $users = User::select('user_type','id', 'name', 'email')->where(['status'=>1, 'user_type'=>5])->whereIn('id',$input['host_users'])->get();
                    }
                }else{
                    $users = User::where(['status'=>1])->get();
                }
                if (isset($users) && count($users) > 0) {

                    /*if (is_object($users)) {
                        $users = $users->toArray();
                    }
                    if (is_array($users)) {
                        $chunks = array_chunk($users, 10);

                        foreach ($chunks as $chunk) {
                            foreach ($chunk as $user) {
                                $viewPage = 'emails.notification';
                                $subject = 'Notification';
                                $description = $input['message'];
                                $record = (object)[
                                    'name' => $input['title'],
                                    'footer' => '',
                                    'username' => $user['name'],
                                    'subject' => $input['title'],
                                    'description' => $description,
                                    'user_email' => $user['email'],
                                ];

                                if (filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                                    Mail::send($viewPage, compact('record'), function ($message) use ($user, $subject) {
                                        $message->to($user['email'], config('app.name'))->subject($subject);
                                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                    });
                                }

                                $notificationData = new Notification;
                                $notificationData->user_type = $user['user_type'];
                                $notificationData->notification_type = 1;
                                $notificationData->notification_for = $input['notification_for'];
                                $notificationData->title = $input['title'];
                                $notificationData->message = $input['message'];
                                $notificationData->user_id = $user['id'];
                                
                                $notificationData->save();
                                send_notification(1, $user['id'], $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                                usleep(500000); 
                            }
                        }
                    }else{
                        foreach($users as $user){
                            $viewPage = 'emails.notification';
                            $subject = 'Notification';
                            $description = $input['message'];
                            $record = (object)[];
                            $record->name = $input['title'];
                            $record->footer = '';
                            $record->username = $user->name;
                            $record->subject = $input['title'];
                            $record->description = $description;
                            $record->user_email = $user->email;

                            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                Mail::send($viewPage, compact('record'), function ($message) use ($user, $subject) {
                                    $message->to($user->email, config('app.name'))->subject($subject);
                                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                });
                            }


                            $notificationData = new Notification;
                            $notificationData->user_type = $user->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = $input['notification_for'];
                            $notificationData->title = $input['title'];
                            $notificationData->message = $input['message'];
                            $notificationData->user_id = $user->id;
                            
                            $notificationData->save();
                            send_notification(1, $user->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                           
                        }
                    }*/
                    if (!empty($users)) {
                        $notiInsert = [];
                        foreach($users as $k=>$user){
                            if ($k>5) {
                                $notiInsert[]=array(
                                    "user_type" => $user->user_type,
                                    "notification_type" => '1',
                                    "notification_for" => $input['notification_for'],
                                    "title" => $input['title'],
                                    "message" => $input['message'],
                                    "user_id" => $user->id,
                                    "user_name" => $user->name,
                                    "email" => $user->email,
                                );
                            }else{
                                $viewPage = 'emails.notification';
                                $subject = 'Notification';
                                $description = $input['message'];
                                $record = (object)[];
                                $record->name = $input['title'];
                                $record->footer = '';
                                $record->username = $user->name;
                                $record->subject = $input['title'];
                                $record->description = $description;
                                $record->user_email = $user->email;

                                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                    Mail::send($viewPage, compact('record'), function ($message) use ($user, $subject) {
                                        $message->to($user->email, config('app.name'))->subject($subject);
                                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                    });
                                }

                                $notificationData = new Notification;
                                $notificationData->user_type = $user->user_type;
                                $notificationData->notification_type = 1;
                                $notificationData->notification_for = $input['notification_for'];
                                $notificationData->title = $input['title'];
                                $notificationData->message = $input['message'];
                                $notificationData->user_id = $user->id;
                                
                                $notificationData->save();
                                send_notification(1, $user->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                            }
                            
                        }

                        if(!empty($notiInsert))
                        {
                            DB::table('notification_pendings')->insert($notiInsert);
                        }
                    }

                    /*$batchSize = 20; 

                    foreach ($users->chunk($batchSize) as $userBatch) {
                        foreach ($userBatch as $user) {
                            $viewPage = 'emails.notification';
                            $subject = 'Notification';
                            $description = $input['message'];
                            $record = (object)[];
                            $record->name = $input['title'];
                            $record->footer = '';
                            $record->username = $user->name;
                            $record->subject = $input['title'];
                            $record->description = $description;
                            $record->user_email = $user->email;

                            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                                Mail::send($viewPage, compact('record'), function ($message) use ($user, $subject) {
                                    $message->to($user->email, config('app.name'))->subject($subject);
                                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                });
                            }


                            $notificationData = new Notification;
                            $notificationData->user_type = $user->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = $input['notification_for'];
                            $notificationData->title = $input['title'];
                            $notificationData->message = $input['message'];
                            $notificationData->user_id = $user->id;
                            
                            $notificationData->save();
                            send_notification(1, $user->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }
                        sleep(5); 
                    }*/

                    
                }
            }
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Notification mail is not being sent because you are exceeding the SMTP rate limit provided by the SMTP server');
           // return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $data = $this->Models->where('id',$id)->first();
        // dd($data);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
        return view('admin.'.$this->page.'.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        Gate::authorize('Notification-edit');
        $id = $request->id;
        $data = $this->Models->find($id);       
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
        // dd($data);
        return view('admin.'.$this->page.'.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        Gate::authorize('Notification-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    $data = AdminNotification::where('id', $id)->update(['name' => $input['name'], 'image' => $result['file']]);
                } else {
                    $data = AdminNotification::where('id', $id)->update(['name' => $input['name']]);
                }
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('Notification-delete');
        if(AdminNotification::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Notification delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Notification does not delete. ';
        }
        return $response;
    }

    public function status(Request $request)
    {
        $id = $request->id;
        try {
            $data = $this->Models->findOrFail($id);
            $data->status = $request->value;
            $data->save();
            return ['status'=>1,'type'=>'success','message'=>'Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function changeStatus($id, $status)
    {
        $details = User::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = User::findOrFail($id);
            if ($User->update($inp)) {
                if ($status == 'active') {
                    $result['message'] = __("backend.Facility_Owner_status_success");
                    $result['status'] = 1;
                } else {
                    $result['message'] = __("backend.Facility_Owner_status_deactivate");
                    $result['status'] = 1;
                }
            } else {
                $result['message'] = __("backend.Facility_Owner_status_can`t_updated");
                $result['status'] = 0;
            }
        } else {
            $result['message'] = __("backend.Invaild_user");
            $result['status'] = 0;
        }
        return response()->json($result);
    }

    public function readNotification($id) {
    	$notificationData = Notification::find($id);
    	$inp = ['is_read' => 1];

        if(!empty($notificationData)) {

            if ($notificationData->update($inp)) {

                $result['message'] = 'Notification read successfully';
                $result['status'] = 1;

            }else{
                $result['message'] = 'Something went wrong!!';
                $result['status'] = 0;
            }
        }else{
            $result['message'] = 'Invaild notification id!!';
            $result['status'] = 0;
        }
        return response()->json($result);
    }

    public function deleteNotification($id) {
    	$notificationData = Notification::find($id);

        if(!empty($notificationData)) {
            if (Notification::where('id', $id)->delete()) {
                $result['message'] = 'Notification delete successfully';
                $result['status'] = 1;
            }else{
                $result['message'] = 'Something went wrong!!';
                $result['status'] = 0;
            }
        }else{
            $result['message'] = 'Invaild notification id!!';
            $result['status'] = 0;
        }
        return response()->json($result);
    }

    public function readAllNotification(Request $request) {
    	if ($request->userId) {
	    	Notification::where('user_id', $request->userId)->update(['is_read'=>1]);
	    	// PanelNotifications::where('status', 1)->delete();
	    	$result['message'] = 'Notification deleted successfully';
	        $result['status'] = 1;

    	} else{
            $result['message'] = 'Something went wrong!!';
            $result['status'] = 0;
        }
        return response()->json($result);
    }

    public function clearAllNotification(Request $request) {
    	if ($request->userId) {
	    	Notification::where('user_id', $request->userId)->delete();
	    	// PanelNotifications::where('status', 1)->delete();
	    	$result['message'] = 'Notification deleted successfully';
	        $result['status'] = 1;

    	} else{
            $result['message'] = 'Something went wrong!!';
            $result['status'] = 0;
        }
        return response()->json($result);
    }
}
