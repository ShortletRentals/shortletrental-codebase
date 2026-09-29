<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\Subscription;
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
use File;
use App\Http\Controllers\import;
use App\Exports\BulkSubscribeUsersExport;

class SubscribeUsersController extends Controller
{
    protected  $page = 'subscribe_users';
    protected  $lang = 'Subscribe Users';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Subscription();
        $this->sortableColumns = [
            0 => 'type',
            1 => 'email',
            2 => 'created_at',
            // 2 => 'message',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Subscribe-section');
        
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
                $row['type'] = isset($value->type)? $value->type:'';
                $row['email'] = isset($value->email)? $value->email:'N/A';           
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                /*$edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
              
                $row['actions']=createAction($edit.$view);*/
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

    public function exportSubscribeUsers(Request $request)
    {
        // dd('inn');
        $input = $request->all();
        // dd($request->all());
        $file_type = $input['file_type'];

        // $path = url('/public').'/';
        // $path = public_path('/uploads').'/';
        $path = storage_path('app').'/';
        if($file_type == 'Excel'){
            $fileName = time().'_users.xls';
        }else{
            $fileName = time().'_users.csv';
        }
        Excel::store(new BulkSubscribeUsersExport($request), $fileName);
        // File::move(storage_path('app/'.$fileName), public_path($fileName));

        $path1 =  public_path('storage') .'/'. 'users/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path1 . $newFolder;
        // dd($folderPath);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true);
        }
        File::move(storage_path('app/'.$fileName), $folderPath.$fileName);

        $result['status'] = 1;
        $result['url'] = url('public/storage/users/'.$newFolder.$fileName);
        // $result['url'] = $path.$fileName;
        return response()->json($result);
    }

    public function frontend()
    {
        Gate::authorize('Content-section');
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
        Gate::authorize('Content-create');
        $data= ['title'=>$this->lang,'page'=>$this->page];        
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
        $input = $request->all();
        //sent mail to payment user
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
            $data->save();
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
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
        Gate::authorize('Content-edit');
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
        Gate::authorize('Content-edit');
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
                    $folderPath	=	'notification/'.$newFolder; 
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
        //
        Gate::authorize('Content-delete');
        return Courts::findOrFail($id)->delete();
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
}
