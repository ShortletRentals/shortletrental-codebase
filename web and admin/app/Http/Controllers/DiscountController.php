<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Discount;
use App\Models\CustomerDiscount;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Property;
use App\Models\EmailTemplateLang;
use App\Models\DiscountProperty;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\import;
use App\Exports\BulkDiscountExport;
use File,Auth;

class DiscountController extends Controller
{
    protected  $page = 'discount';
    protected  $lang = 'Discount';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Discount();
        $this->Models_cat = new Category();
        $this->sortableColumns = [
            0 => 'code',
            1 => 'percentage',
            2 => 'category_name',
            3 => 'vilidity',
            4 => 'total_customer_use',
            5 => 'total_use',
            6 => 'total_single_use',
            7 => 'status',
            8 => 'created_at',
        ];

        $this->CustomerDiscountModels = new CustomerDiscount();
        $this->sortableColumns1 = [
            0 => 's_no',
            1 => 'customer_name',
            2 => 'coupon_code',
            3 => 'discount_amount',
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
        Gate::authorize('Discount-section');
        
        if ($request->wantsJson()) {
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
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
                $categoryDetail = Category::where('id', $value->category_id)->first();
                $total_customer_use = Booking::where(['coupon_code'=>$value->code])->count();
                $row['id'] = $i;
                // $row['code'] = isset($value->code)? $value->code:'N/A';
                $row['code'] = '<a href="'.url('admin/discount/discount_customer_view/'.$value->id).'" style="text-decoration: underline">'.$value->code.'</a>';
                if($user_type == 3){
                    $row['code'] = '<a href="'.url('admin/discount/discount_customer_view/'.$value->id).'" style="text-decoration: underline">'.$value->code.'</a>';
                }
                $row['percentage'] = isset($value->percentage)? $value->percentage:'N/A';

                if ($categoryDetail) {
                    $row['category_name'] = isset($value->all_categories) && $value->all_categories == 'No' ? $categoryDetail->name : 'All';
                } else {
                    $row['category_name'] = isset($value->all_categories) && $value->all_categories == 'No' ? 'N/A' : 'All';
                }
                // $row['category_name'] = isset($categoryDetail)? $categoryDetail->name:'N/A';
                $validity = '';
                if(isset($value->start_date) && $value->end_date != Null){
                    $validity = date('d M Y', strtotime($value->start_date)) .' - '.date('d M Y', strtotime($value->end_date)); ;
                }else{
                    $validity = date('d M Y', strtotime($value->start_date));
                }
                $row['validity'] = $validity;
                // $row['start_date'] = date('d M Y', strtotime($value->start_date));
                // $row['end_date'] = date('d M Y', strtotime($value->end_date));
                $row['total_use'] = isset($value->total_use)? $value->total_use:'N/A';
                $row['total_customer_use'] = isset($total_customer_use) ? $total_customer_use:'-';
                $row['total_single_use'] = isset($value->total_single_use)? $value->total_single_use:'N/A';
                // $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                if(Auth::user()->user_type != 3)
                {
                    $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
                }
                else
                {
                    $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                }

                if (!auth()->user()->can('Discount-edit')) {
                    $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                }
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                if($user_type == 3){
                    $view = '<a href="'.url('admin/discount/discount_customer_view/'.$value->id).'" ><span class="bx bx-show"></a>';
                }
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';

                if (!auth()->user()->can('Discount-edit')) {
                    $edit = '';
                }
                if (!auth()->user()->can('Discount-delete')) {
                    $delete = '';
                }
                if(Auth::user()->user_type != 3)
                {
                    $row['actions']=createAction($edit.$view.$delete);
                }
                else{
                    $row['actions']=createAction('-');
                }



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

    public function discount_customer_view(request $request, $id = null)
    {
        // dd($id);
        Gate::authorize('Discount-section');
        
        if ($request->wantsJson()) {
            // dd($id);
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];
            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $status = $request['status'] ?? null;
            $start_date = $request['start_date'] ?? null ;
            $end_date = $request['end_date'] ?? null; 
            $sortableColumns = $this->sortableColumns1;
            $querydata = $this->CustomerDiscountModels->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date,$id);
            // dd($querydata->get(), '--querydata');
            $totaldata = $querydata->count();
            // dd($totaldata);
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
                $row['s_no'] = $i;
                $row['customer_name'] = isset($value->personal_last_name)? $value->personal_first_name.' '.$value->personal_last_name:'N/A';
                $row['coupon_code'] = isset($value->coupon_code)? $value->coupon_code:'N/A';
                $row['discount_amount'] = isset($value->discount_amount)? $value->discount_amount:'N/A';
                $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                // $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
                // if (!auth()->user()->can('Discount-edit')) {
                //     $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                // }

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
      
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'status'=>$status,'id'=>$id];
        return view('admin.'.$this->page.'.discount_customer_view',$data);
    }

    public function discount_customer_export(request $request)
    {
        // dd('inn');
        $input = $request->all();
        // dd($request->all());
        $file_type = $input['file_type'];

        // $path = url('/public').'/';
        // $path = public_path('/uploads').'/';
        $path = storage_path('app').'/';
        if($file_type == 'Excel'){
            $fileName = time().'_discount.xls';
        }else{
            $fileName = time().'_discount.csv';
        }
        Excel::store(new BulkDiscountExport($request), $fileName);
        // File::move(storage_path('app/'.$fileName), public_path($fileName));

        $path1 =  public_path('storage') .'/'. 'discount/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path1 . $newFolder;
        // dd($folderPath);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true);
        }
        File::move(storage_path('app/'.$fileName), $folderPath.$fileName);

        $result['status'] = 1;
        $result['url'] = url('public/storage/discount/'.$newFolder.$fileName);
        // $result['url'] = $path.$fileName;
        return response()->json($result);
    }

    public function frontend()
    {
        Gate::authorize('Discount-section');
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
        Gate::authorize('Discount-create');
        $category = $this->Models_cat->where('status',1)->get();
        $properties = Property::where('status',1)->get();
        $influencer_list = User::where(['user_type'=>3, 'status'=>1])->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'category'=>$category, 'properties'=>$properties, 'influencer_list'=>$influencer_list];
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
            'code'          => 'required|max:190',
            'percentage'    => 'required',
            // 'category_id'   => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'total_use'     => 'required',
            'total_single_use' => 'required',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Discount;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'discount/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            $data->code = $input['code'];
            $data->percentage = $input['percentage'];
            $data->start_date = $input['start_date'];
            $data->end_date = $input['end_date'];
            $data->total_use = $input['total_use'];
            $data->total_single_use = $input['total_single_use'];
            $data->influencer = $input['influencer'];
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                $data->all_properties = 'Yes';
            }else{
                $data->all_properties = 'No';
            }
            if(isset($input['all_categories']) && $input['all_categories'] == 'Yes'){
                $data->all_categories = 'Yes';
                $data->category_id = Null;
            }else{
                $data->all_categories = 'No';
                $data->category_id = $input['category_id'];
            }
            $data->status = 1;
            if($data->save()){
                if(isset($input['property_id']) && !empty($input['property_id'])){
                    foreach($input['property_id'] as $property){
                        $property_name = Property::where('id',$property)->pluck('title')->first();
                        $data_service = new DiscountProperty;
                        $data_service->discount_id = $data->id;
                        $data_service->property_id = $property;
                        $data_service->property_name = $property_name;
                        $data_service->save();
                    }
                }
            }
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
        $selected_discount_properties = DiscountProperty::where('discount_id',$id)->pluck('property_id')->toArray();
        if(isset($selected_discount_properties) && count($selected_discount_properties) > 0){
            // $properties = Property::whereIn('id',$selected_discount_properties)->get();
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->whereIn('properties.id',$selected_discount_properties)->get();
        }else{
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->get();
        }
        // dd($accommodations);
        // dd($selected_discount_properties);
        // dd($data);
        $data = ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'accommodations'=>$accommodations];
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
        Gate::authorize('Discount-edit');
        $id = $request->id;
        $data = $this->Models->find($id);
        $category = $this->Models_cat->where('status',1)->get();
        $properties = Property::where('status',1)->get();
        $selected_discount_properties = DiscountProperty::where('discount_id',$id)->pluck('property_id')->toArray();
        $influencer_list = User::where(['user_type'=>3, 'status'=>1])->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'category'=>$category,'properties'=>$properties,'selected_discount_properties'=>$selected_discount_properties, 'influencer_list'=>$influencer_list];
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
        Gate::authorize('Discount-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'code'          => 'required|max:190',
            'percentage'    => 'required',
            // 'category_id'   => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'total_use'     => 'required',
            'total_single_use' => 'required',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $file = $request->file('image');
            try {
                if(isset($input['all_categories']) && $input['all_categories'] == 'Yes'){
                    $all_categories = 'Yes';
                    $category = Null;
                }else{
                    $all_categories = 'No';
                    $category = $input['category_id'];
                }
                if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                    $all_properties = 'Yes';
                    DiscountProperty::where('discount_id',$id)->delete();
                }else{
                    $all_properties = 'No';
                }
                if (isset($file)) {
              
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'discount/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    $data = Discount::where('id', $id)->update(['code' => $input['code'],'percentage' => $input['percentage'],'all_categories' => $all_categories,'category_id' => $category,'start_date' => $input['start_date'],'end_date' => $input['end_date'],'total_use' => $input['total_use'],'total_single_use' => $input['total_single_use'],'all_properties'=>$all_properties,'influencer'=>$input['influencer']??null, 'image' => $result['file'] ]);
                } else {
                    // dd($request->all());
                    $data = Discount::where('id', $id)->update(['code' => $input['code'],'percentage' => $input['percentage'],'all_categories' => $all_categories,'category_id' => $category,'start_date' => $input['start_date'],'end_date' => $input['end_date'],'total_use' => $input['total_use'],'total_single_use' => $input['total_single_use'],'all_properties'=>$all_properties,'influencer'=>$input['influencer']??null ]);
                }
                if(isset($input['property_id'])){
                    if(!isset($input['all_properties'])){
                        DiscountProperty::where('discount_id',$id)->delete();
                        foreach($input['property_id'] as $property){
                            $property_name = Property::where('id',$property)->pluck('title')->first();
                            $data_service = new DiscountProperty;
                            $data_service->discount_id = $id;
                            $data_service->property_id = $property;
                            $data_service->property_name = $property_name;
                            $data_service->save();
                        }
                    }
                }else{
                    DiscountProperty::where('discount_id',$id)->delete();
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
        Gate::authorize('Discount-delete');
        if(Discount::findOrFail($id)->delete()){
            DiscountProperty::where('discount_id',$id)->delete();
            $response['status'] = true;
            $response['message'] = 'Discount delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Discount does not delete. ';
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
