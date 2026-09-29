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
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class BlogCategoryController extends Controller
{
    protected  $page = 'blog_category';
    protected  $lang = 'Blog Category';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new BlogCategory();
        $this->sortableColumns = [
            0 => 'name',
            1 => 'status',
            2 => 'created_at',
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
                $blogCategory = BlogCategory::where('id',$value->blog_category)->pluck('name')->first();
                $row['id'] = $i;
                $row['name'] = isset($value->name)? $value->name:'N/A';
              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Delete Blog Category" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                // $permission = permissionAction('permissions.user_permissions',['id'=>$value->id]);
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
        $data= ['title'=>$this->lang,'page'=>$this->page];
        return view('admin.'.$this->page.'.listing', $data);
    }

    public function edit_frontend($id)
    {
        Gate::authorize('Category-edit');
        $data['category'] = BlogCategory::where('status',1)->get();
        return view('admin.'.$this->page.'.edit', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Category-create');
        $category = BlogCategory::where('status',1)->get();

        $data= ['title'=>$this->lang,'page'=>$this->page,'category'=>$category];
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
        Gate::authorize('Category-create');
        $input = $request->all();
        // dd($input);
        //sent mail to payment user
        $message = [];
        $validation = [
            'name' => 'required|max:190|unique:blog_category',
        ]; 
        $message = [
            // 'mobile.digits_between' =>'The mobile number must be between 8 to 15 digits.',
        ];
            
        $this->validate($request,$validation,$message);
        try{
            // $secure_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
            $data = new BlogCategory;

            $data->name = $input['name'];
            $data->save();
            
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
        } catch (Exception $e) {
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
        $category = BlogCategory::where('id',$data->blog_category)->pluck('name')->first();
        // dd($accommodations);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'category'=>$category];
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
        $category = BlogCategory::where('id',$data->blog_category)->pluck('name')->first();

        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'category'=>$category];
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
        $mesasge = [
        ];
        $this->validate($request, [
            // 'image' => 'required',
            'name' => 'required|max:190|unique:blog_category,name,' . $id,
            // 'blog_category' => 'required|max:190|unique:blogs',
        ], $mesasge);

        $input = $request->all();
        $fail = false;
        if (!$fail) {
            try {
                $data = BlogCategory::where('id', $id)->update(['name' => $input['name'] ]);
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
        Gate::authorize('Category-delete');
        if(BlogCategory::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Blog category delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Blog category does not delete. ';
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
