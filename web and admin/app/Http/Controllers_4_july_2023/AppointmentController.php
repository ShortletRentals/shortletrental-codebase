<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Courts;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Building;
use App\Models\Appointment;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class AppointmentController extends Controller
{
    protected  $page = 'appointment';
    protected  $lang = 'Appointment';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Appointment();
        $this->sortableColumns = [
            0 => 'name',
            1 => 'email',
            2 => 'mobile',
            3 => 'appointment_date',
            4 => 'schedule_time',
            5 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        // dd($request->all());
        Gate::authorize('Host-section');
        
        if ($request->wantsJson()) {            
            // dd($request);
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
                $row['name'] = isset($value->full_name)? $value->full_name.' '.$value->surname:'N/A';
                $row['email'] = isset($value->email)? $value->email:'N/A';
                $row['mobile'] = isset($value->mobile) && !empty($value->mobile) ? $value->country_code.' - '.$value->mobile:'N/A';
                $row['appointment_date'] = date('d M Y', strtotime($value->appointment_date)).' - '.$value->schedule_time;
                $row['address'] = isset($value->address)? $value->address:'N/A';
              
                $row['created_at'] = date('d M Y H:i', strtotime($value->created_at));
                // $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '';                
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Cancel Appointment" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
                $row['actions']=createAction($view.$delete);
                // $row['actions']=createAction($view.$delete);
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
      
        $data= ['title'=>$this->lang,'page'=>$this->page,'status'=>$status];
        // dd($data);
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $data = $this->Models->where('id',$id)->first();
        // dd($data);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
        return view('admin.'.$this->page.'.show',$data);
    }

    public function frontend()
    {
        Gate::authorize('Host-section');
        $user = Building::where('status', '1')->get();
        $data= ['title'=>$this->lang,'page'=>$this->page]; 
        return view('admin.host.listing', $data);
    }

    public function edit_frontend($id)
    {
        Gate::authorize('Host-edit');
        //$data['roles']=Role::all();
        $data['users'] = User::findOrFail($id);
        $data['country'] = Country::select('phonecode', 'name', 'id')->get();
        return view('facility_owner.edit', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Host-create');
        $data= ['title'=>$this->lang, 'page'=>$this->page];
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
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name'          => 'required|max:190',
        ]; 
        $message = [
            // 'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            // $secure_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
            $data = new Building;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];

            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function buildingStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name' => 'required|max:190',
        ]; 
        $message = [
            // 'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
        $this->validate($request,$validation,$message);       
        try{
            $data = new Building;
            // dd($request->file('image'));
            if ($request->file('image')) {

                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'amenity/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            // return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            $result['status'] = true;
            $result['message'] = 'Record added successfully.';
        } catch (Exception $e) {
            // return customeRedirect('admin.'.$this->page.'.index','','error',$e);
            $result['status'] = false;
            $result['message'] = 'Records not added.';
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        Gate::authorize('Host-edit');
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
        Gate::authorize('Host-edit');
        // validate
        $id = $request->id;
        $mesasge = [
            // 'name.required' => __("backend.name_required"),
        ];
        $this->validate($request, [
            'name' => 'required',
        ], $mesasge);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'amenity/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    $data = Building::where('id', $id)->update(['name' => $input['name'], 'image' => $result['file']]);
                } else {
                    $data = Building::where('id', $id)->update(['name' => $input['name']]);
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
        Gate::authorize('Host-delete');
        if(Building::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Building delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Building does not delete. ';
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
        $details = Building::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = Building::findOrFail($id);
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

    public function cancel($id)
    {
        $data = $this->Models->where('id',$id)->first();
        $data->is_status = 2;
        $data->save();


        if (filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $email = EmailTemplateLang::where('email_id', 17)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
            $description = $email->description;
            $description = str_replace("[NAME]", $data->full_name.' '. $data->surname , $description);
            $name = $email->name;

            $name = str_replace("[NAME]", $data->full_name.' '. $data->surname , $name);
            $record = (object)[];
            $record->description = $description;
            $record->footer = $email->footer;
            $record->name = $name;
            $record->subject = $email->subject;
            $record->user_email = $data->email;
            // $record->user_token = $user->token;
            Mail::send('emails.comman', compact('record'), function ($message) use ($data, $email) {
                $message->to($data->email, config('app.name'))->subject($email->subject);
                $message->from('customersupport@shortletrenrals.com', config('app.name'));
            });
        }
       
           
        if($this->Models->findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Appointment delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Appointment does not delete. ';
        }
        return response()->json($response);
    }
}
