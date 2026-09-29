<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
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

class CategoryController extends Controller
{
    protected  $page = 'category';
    protected  $lang = 'Category';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Category();
        $this->sortableColumns = [
            0 => 'image',
            1 => 'type',
            2 => 'name',
            3 => 'status',
            4 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Category-section');
        
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
                $row['type'] = isset($value->type)? str_replace('_',' ',$value->type):'N/A';
                $row['name'] = isset($value->name)? $value->name:'N/A';
                $row['image'] = "<img style='background-color: black;' src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
                if (!auth()->user()->can('Category-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Category-delete')) {
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
        Gate::authorize('Category-section');
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
        Gate::authorize('Category-create');
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
            'type' => 'required',
            'name' => 'required|max:190|unique:categories',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Category;

            if ($request->file('image')) {
                $file = $request->file('image');
       
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'category/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->type = $input['type'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
            
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    public function categoryStore(Request $request)
    { 
        $input = $request->all();
        // dd($input);
        $message = [];
        $validation = [
            'type' => 'required',
            'name' => 'required|max:190|unique:categories',
        ];
        $this->validate($request,$validation,$message);       
        try{
            $data = new Category;
            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'category/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->type = $input['type'];
            $data->name = $input['name'];
            $data->status = 1;
            $data->save();
            // dd($data);
            $records = Category::where(['status'=>1, 'type'=>$input['type']])->get();
            
            $data['records'] = $records;
            $data['category_id'] = [];

            $result['status'] = true;
            $result['message'] = view('admin.'.$this->page.'.show_category',$data)->toHtml();
            return $result;
            // return view('admin.'.$this->page.'.show_category',$data);
        } catch (Exception $e) {
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
        Gate::authorize('Category-edit');
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
        Gate::authorize('Category-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'type' => 'required',
            'name' => 'required|max:255|unique:categories,name, '. $id .',id',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            // dd($file);
            try {
                if (isset($file)) {
                    // dd($file);
              
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'category/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    $data = Category::where('id', $id)->update(['type' => $input['type'], 'name' => $input['name'], 'image' => $result['file']]);
                } else {
                    $data = Category::where('id', $id)->update(['type' => $input['type'], 'name' => $input['name']]);
                }
                return Redirect::route('admin.'.$this->page.'.index')->with('success',"Record updated successfully.");
            } catch (Exception $e) {
                return Redirect::Back()->with('error',"Something went wrong.");
            }
        } else {
            return Redirect::Back()->with('error',"Something went wrong.");
        }
    }

    public function show_category($type_val, $category = '') {
        if ($type_val) {
            $province = Category::where(['type'=>$type_val])->get();
            $data['records'] = $province;
            $data['type_val'] = $type_val;

        } else {
            $data['records'] = array();
            $data['type_val'] = '';
        }
        //   dd($category);
        if(!empty($category)){
            $data['category_id'] = json_decode($category);
        }else{
            $data['category_id'] = [];
        }
      return view('admin.'.$this->page.'.show_category',$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('Category-delete');
        if(Category::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Category delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Category does not delete. ';
        }
        return $response;
        // return Category::findOrFail($id)->delete();
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
        $details = Category::find($id);
        if (!empty($details)) {
            $inp = ['status' => $status];
            $User = Category::findOrFail($id);
            if ($User->update($inp)) {
                $result['message'] = __("Status updated successfully.");
                $result['status'] = 1;
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
