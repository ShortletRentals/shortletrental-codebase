<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
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

class CountryController extends Controller
{
    protected  $page = 'country';
    protected  $lang = 'Country';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Country();
        $this->sortableColumns = [
            0 => 'sortname',
            1 => 'name',
            2 => 'status',
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
        Gate::authorize('Country-section');
        
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
                $row['sortname'] = isset($value->sortname)? $value->sortname:'N/A';
                $row['name'] = isset($value->name)? $value->name:'N/A';
                $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
                if (!auth()->user()->can('Country-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Country-delete')) {
                    $delete = '';
                }
                $row['actions']=createAction($edit.$view.$delete);
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
        Gate::authorize('Country-section');
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
        Gate::authorize('Country-create');
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
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'sortname'          => 'required|max:190|unique:countries',
            'name'          => 'required|max:190|unique:countries',
            'phonecode'          => 'required|max:190|unique:countries',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Country;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'country/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
               
            }
            $data->sortname = $input['sortname'];
            $data->name = $input['name'];

            // if(strpos($input['phonecode'], "+") !== false){
            //     // echo "Found!";
            //     $phonecode = str_replace("+","",$input['phonecode']);
            //     $data->phonecode = $phonecode;
            // }else{
            //     $data->phonecode = $input['phonecode'];
            // }
            $data->phonecode = str_replace("+","",$input['phonecode']);

            // if(strpos($input['phonecode'], "+") !== false){
            //     // echo "Found!";
            //     $phonecode = str_replace("+","",$input['phonecode']);
            //     $data->phonecode = $phonecode;
            // }else{
            //     $data->phonecode = $input['phonecode'];
            // }
            // $data->phonecode = $input['phonecode'];

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
        Gate::authorize('Country-edit');
        $id = $request->id;
        $data = $this->Models->find($id);
        $data->phonecode = '+'.$data->phonecode;
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
        Gate::authorize('Country-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'sortname' => 'required|max:255|unique:countries,sortname, '. $id .',id',
            'name' => 'required|max:255|unique:countries,name, '. $id .',id',
            'phonecode' => 'required|max:255|unique:countries,phonecode, '. $id .',id',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');

            if(strpos($input['phonecode'], "+") !== false){
                // echo "Found!";
                $phonecode = str_replace("+","",$input['phonecode']);
                $phonecode = $phonecode;
            }else{
                $phonecode = $input['phonecode'];
            }
            try {
                if (isset($file)) {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'country/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    $data = Country::where('id', $id)->update(['sortname' => $input['sortname'],'name' => $input['name'],'phonecode' => $phonecode, 'image' => $result['file']]);
                } else {
                    $data = Country::where('id', $id)->update(['sortname' => $input['sortname'],'name' => $input['name'],'phonecode' => $phonecode]);
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
        Gate::authorize('Country-delete');
        if(Country::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Country delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Country does not delete. ';
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
}
