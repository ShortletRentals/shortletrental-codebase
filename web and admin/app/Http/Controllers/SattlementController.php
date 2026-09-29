<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transection;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\PropertyCategory;
use App\Models\Category;
use App\Models\Sattlement;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\import;
use App\Exports\BulkTransectionExport;
use File;

class SattlementController extends Controller
{
    protected  $page = 'sattlement';
    protected  $lang = 'Settlement';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Sattlement();
        $this->sortableColumns = [
            0 => 'booking_id',
            1 => 'reference',
            2 => 'guest_id',
            3 => 'host_id',
            4 => 'total_amount',
            5 => 'admin_amount',
            6 => 'type',
            7 => 'accommodation_ype',
            8 => 'no_of_adult_guest',
            9 => 'address',
            10 => 'coupon_code',
            11 => 'status',
            12 => 'sattlement',
            13 => 'to_date',
        ];
        $this->sortableColumnsForHost = [
           /* 0 => 'booking_id',
            1 => 'code',
            2 => 'type',
            3 => 'host_amount',
            4 =>'optional_service_amount',
            5 => 'total_days',
            6 => 'no_of_adult_guest',
            7 => 'location',
            8 => 'coupon_code',
            9 => 'sattlement_date',
            10 => 'created_at',*/

            0 => 'booking_id',
            1 => 'reference',
            2 => 'guest_id',
            3 => 'host_id',
            4 => 'total_amount',
            5 => 'admin_amount',
            6 => 'type',
            7 => 'accommodation_ype',
            8 => 'no_of_adult_guest',
            9 => 'address',
            10 => 'coupon_code',
            11 => 'status',
            12 => 'sattlement',
            13 => 'to_date',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        Gate::authorize('Transaction-section');
        
        $user_type = User::where('id', auth()->id() )->pluck('user_type')->first();

        if($user_type == 5){
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $status = $request['status'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null;
                $end_date = $request['end_date'] ?? null;
                $sortableColumns = $this->sortableColumnsForHost;

                $search_country = $request['search_country'] ?? null; 
                $search_province = $request['search_province'] ?? null; 
                $search_city = $request['search_city'] ?? null; 
                $search_area = $request['search_area'] ?? null; 
                $search_type_list = $request['search_type_list'] ?? null; 
                $search_category = $request['search_category'] ?? null; 
                $host = $request['host'] ?? null; 
                // dd($host);
                // dd($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date);
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$start_date,$end_date,$search_country,$search_province,$search_city,$search_area,$search_type_list,$search_category,$host);
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
                $extraservice = '';
                foreach ($data as $value) {
                    if(isset($value->selected_options))
                    {
                        $extraservice1 = json_decode($value->selected_options);
                        foreach($extraservice1 as $v1)
                        {
                            $extraservice =  $extraservice.$v1->name.',';
                        }
                    }

                    // dd($value);
                    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                    $guestData = User::where('id',$value->guest_id)->first();
                    $hostData = User::where('id',$value->host_id)->first();
                    $propertyData = Property::where('id',$value->property_id)->first();
                    // echo '<br>'; print_r($value->property_id);
                    $propertyCategory = PropertyCategory::with('getCategoryData')->where('property_id',$value->property_id)->get();
                    $category = '';
                    if(isset($propertyCategory) && count($propertyCategory) > 0){
                        if(count($propertyCategory) > 1){
                            foreach($propertyCategory as $cat){
                                $categoryData = Category::where('id',$cat->category_id)->first();
                                $category .= $categoryData->name.',';
                            }
                            $category = rtrim($category, ',');
                        }else{
                            if(isset($propertyCategory->category_id) && !empty($propertyCategory->category_id)){
                                $categoryData = Category::where('id',$propertyCategory->category_id)->first();
                                $category = $categoryData->name;
                            }
                        }
                    }
                    // dd($category);
                    $propertyAddress = PropertyAddress::where('property_id',$value->property_id)->first();
                    if(isset($propertyAddress->city_id) && !empty($propertyAddress->city_id)){
                        $cityName = City::where('id',$propertyAddress->city_id)->pluck('name')->first();
                    }else{
                        $cityName = '';
                    }
                    if(isset($propertyAddress->area) && !empty($propertyAddress->area)){
                        $areaName = Area::where('id',$propertyAddress->area)->pluck('name')->first();
                    }else{
                        $areaName = '';
                    }
                    if(isset($cityName) && !empty($areaName)){
                        $location = $cityName.'('.$areaName.')';
                    }else{
                        $location = $cityName;
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['code'] = isset($value->code)? $value->code:'N/A';
                    $row['reference'] = isset($value->reference)? $value->reference:'N/A';
                    // dd($hostData->name.' - '.$hostData->country_code.' - '.$hostData->mobile);
                    if(isset($guestData) && !empty($guestData)){
                        $row['guestData'] = $guestData->name.' ('.'+'.$guestData->country_code.' - '.$guestData->mobile.')';
                    }else{
                        $row['guestData'] = 'N/A';
                    }
                    if(isset($hostData) && !empty($hostData)){
                        $row['hostData'] = $hostData->name.' ('.'+'.$hostData->country_code.' - '.$hostData->mobile.')';
                    }else{
                        $row['hostData'] = 'N/A';
                    }
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'0';
                    $row['type'] = isset($propertyData->type) ? str_replace('-',' ',$propertyData->type):'N/A';
                    $row['category'] = isset($category) ? $category : 'N/A';
                    $row['total_days'] = isset($value->total_days) && !empty($value->total_days) ? $value->total_days : '';
                    $row['no_of_adult_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest + $value->no_of_pet;
                    $row['location'] = $location;
                    if(isset($value->discount_amount) && !empty($value->discount_amount)){
                        $row['coupon_code'] = $value->coupon_code. ' - '.$value->discount_amount;
                    }else{
                        $row['coupon_code'] = isset($value->coupon_code)? $value->coupon_code:'-';
                    }
                    if($value->sattlement == 'Done'){
                        $row['sattlement'] = 'Done';
                    }else{
                        if($user_type == 1){
                            $row['sattlement'] = sattlementStatus($value->sattlement, $value->id,['Done'=>'Done','Not_done'=>'Not done'],'sattlement_status',$this->page.'.sattlementStatus')->toHtml();
                        }else{
                            $row['sattlement'] = 'Not Done';
                        }
                    }
                    // $row['sattlement'] = isset($value->sattlement)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['status'] = isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['sattlement_date'] = date('d M Y', strtotime($value->sattlement_date));
                
                    $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                    // $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                
                    $row['actions']=createAction($view);
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
            if(isset($request->booking_status)){
                $booking_status = $request->booking_status;
            }else{
                $booking_status = null;
            }
            if(isset($request->search_city)){
                $search_city = $request->search_city;
            }else{
                $search_city = null;
            }
            if(isset($request->search_area)){
                $search_area = $request->search_area;
            }else{
                $search_area = null;
            }
            if(isset($request->search_type_list)){
                $search_type_list = $request->search_type_list;
            }else{
                $search_type_list = null;
            }
            $all_countries = Country::select('id','name')->where(['status'=>1])->get();
            $all_province = Province::select('id','name')->where(['status'=>1])->get();
            $all_cities = City::select('id','name')->where(['status'=>1])->get();
            $all_area = Area::select('id','name')->where(['status'=>1])->get();
            $all_categories = Category::select('id','name')->where(['status'=>1])->get();
            $host_users = User::select('id','name')->where(['status'=>1,'user_type'=>5])->get();
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status, 'search_city'=>$search_city,'search_area'=>$search_area,'all_countries'=>$all_countries,'all_province'=>$all_province,'all_cities'=>$all_cities,'all_area'=>$all_area,'all_categories'=>$all_categories, 'host_users'=>$host_users];
            return view('admin.'.$this->page.'.listing_host',$data);
        }else{
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $status = $request['status'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null;
                $end_date = $request['end_date'] ?? null;
                $sortableColumns = $this->sortableColumns;

                $search_country = $request['search_country'] ?? null; 
                $search_province = $request['search_province'] ?? null; 
                $search_city = $request['search_city'] ?? null; 
                $search_area = $request['search_area'] ?? null; 
                $search_type_list = $request['search_type_list'] ?? null; 
                $search_category = $request['search_category'] ?? null; 
                $host = $request['host'] ?? null; 
                // dd($host);
                // dd($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date);
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$start_date,$end_date,$search_country,$search_province,$search_city,$search_area,$search_type_list,$search_category,$host);
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
                    // dd($value->coupon_code);
                    $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                    $guestData = User::where('id',$value->guest_id)->first();
                    $hostData = User::where('id',$value->host_id)->first();
                    $propertyData = Property::where('id',$value->property_id)->first();
                    // echo '<br>'; print_r($value->property_id);
                    $propertyCategory = PropertyCategory::with('getCategoryData')->where('property_id',$value->property_id)->get();
                    $category = '';
                    if(isset($propertyCategory) && count($propertyCategory) > 0){
                        if(count($propertyCategory) > 1){
                            foreach($propertyCategory as $cat){
                                $categoryData = Category::where('id',$cat->category_id)->first();
                                $category .= $categoryData->name.',';
                            }
                            $category = rtrim($category, ',');
                        }else{
                            if(isset($propertyCategory->category_id) && !empty($propertyCategory->category_id)){
                                $categoryData = Category::where('id',$propertyCategory->category_id)->first();
                                $category = $categoryData->name;
                            }
                        }
                    }
                    // dd($category);
                    $propertyAddress = PropertyAddress::where('property_id',$value->property_id)->first();
                    if(isset($propertyAddress->city_id) && !empty($propertyAddress->city_id)){
                        $cityName = City::where('id',$propertyAddress->city_id)->pluck('name')->first();
                    }else{
                        $cityName = '';
                    }
                    if(isset($propertyAddress->area) && !empty($propertyAddress->area)){
                        $areaName = Area::where('id',$propertyAddress->area)->pluck('name')->first();
                    }else{
                        $areaName = '';
                    }
                    if(isset($cityName) && !empty($areaName)){
                        $location = $cityName.'('.$areaName.')';
                    }else{
                        $location = $cityName;
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['reference'] = isset($value->reference)? $value->reference:'N/A';
                    // dd($hostData->name.' - '.$hostData->country_code.' - '.$hostData->mobile);
                    if(isset($guestData) && !empty($guestData)){
                        $row['guestData'] = $guestData->name.' ('.'+'.$guestData->country_code.' - '.$guestData->mobile.')';
                    }else{
                        $row['guestData'] = 'N/A';
                    }
                    if(isset($hostData) && !empty($hostData)){
                        $row['hostData'] = $hostData->name.' ('.'+'.$hostData->country_code.' - '.$hostData->mobile.')';
                    }else{
                        $row['hostData'] = 'N/A';
                    }
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'0';
                    $row['type'] = isset($propertyData->type) ? str_replace('-',' ',$propertyData->type):'N/A';
                    $row['category'] = isset($category) ? $category : 'N/A';
                    $row['no_of_adult_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest + $value->no_of_pet;
                    $row['location'] = $location;
                    if(isset($value->discount_amount) && !empty($value->discount_amount)){
                        $row['coupon_code'] = $value->coupon_code. ' - '.$value->discount_amount;
                    }else{
                        $row['coupon_code'] = isset($value->coupon_code)? $value->coupon_code:'-';
                    }
                    if($value->sattlement == 'Done'){
                        $row['sattlement'] = 'Done';
                    }else{
                        if($user_type == 1){
                            $row['sattlement'] = sattlementStatus($value->sattlement, $value->id,['Done'=>'Done','Not_done'=>'Not done'],'sattlement_status',$this->page.'.sattlementStatus')->toHtml();
                        }else{
                            $row['sattlement'] = 'Not Done';
                        }
                    }
                    // $row['sattlement'] = isset($value->sattlement)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['status'] = isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A';             
                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                
                    // $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                
                    $row['actions']=createAction($view);
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
            if(isset($request->booking_status)){
                $booking_status = $request->booking_status;
            }else{
                $booking_status = null;
            }
            if(isset($request->search_city)){
                $search_city = $request->search_city;
            }else{
                $search_city = null;
            }
            if(isset($request->search_area)){
                $search_area = $request->search_area;
            }else{
                $search_area = null;
            }
            if(isset($request->search_type_list)){
                $search_type_list = $request->search_type_list;
            }else{
                $search_type_list = null;
            }
            $all_countries = Country::select('id','name')->where(['status'=>1])->get();
            $all_province = Province::select('id','name')->where(['status'=>1])->get();
            $all_cities = City::select('id','name')->where(['status'=>1])->get();
            $all_area = Area::select('id','name')->where(['status'=>1])->get();
            $all_categories = Category::select('id','name')->where(['status'=>1])->get();
            $host_users = User::select('id','name')->where(['status'=>1,'user_type'=>5])->get();
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status, 'search_city'=>$search_city,'search_area'=>$search_area,'all_countries'=>$all_countries,'all_province'=>$all_province,'all_cities'=>$all_cities,'all_area'=>$all_area,'all_categories'=>$all_categories, 'host_users'=>$host_users];
            return view('admin.'.$this->page.'.listing',$data);
        }
    }

    public function export(Request $request)
    {
        // dd('inn');
        $input = $request->all();
        // dd($request->all());
        $file_type = $input['file_type'];

        // $path = url('/public').'/';
        // $path = public_path('/uploads').'/';
        $path = storage_path('app').'/';
        if($file_type == 'Excel'){
            $fileName = time().'_sattlement.xls';
        }else{
            $fileName = time().'_sattlement.csv';
        }
        Excel::store(new BulkTransectionExport($request), $fileName);
        // File::move(storage_path('app/'.$fileName), public_path($fileName));

        $path1 =  public_path('storage') .'/'. 'sattlement/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path1 . $newFolder;
        // dd($folderPath);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true);
        }
        File::move(storage_path('app/'.$fileName), $folderPath.$fileName);

        $result['status'] = 1;
        $result['url'] = url('public/storage/sattlement/'.$newFolder.$fileName);
        // $result['url'] = $path.$fileName;
        return response()->json($result);
    }

    public function frontend()
    {
        Gate::authorize('Transaction-section');
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
        Gate::authorize('Transaction-create');
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
            'name'          => 'required|max:190',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Transection;

         
            $data->name = $input['name'];
            $data->description = $input['description'];
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
         $guestData = User::where(['user_type'=>4, 'id' => $data->guest_id])->first();
         if(isset($guestData)){
             $data['guest_name'] = $guestData->name;
             $data['guest_email'] = $guestData->email;
             $data['guest_mobile'] = $guestData->mobile;
             $data['guest_second_mobile'] = $guestData->second_mobile;
         }else{
             $data['guest_name'] = '';
             $data['guest_email'] = '';
             $data['guest_mobile'] = '';
             $data['guest_second_mobile'] = '';
         }
         $hostData = User::where(['user_type'=>5, 'id' => $data->host_id])->first();
         if(isset($hostData)){
             $data['host_name'] = $hostData->name;
             $data['host_email'] = $hostData->email;
             $data['host_country_code'] = $hostData->country_code;
             $data['host_mobile'] = $hostData->mobile;
             $data['host_second_mobile'] = $hostData->second_mobile;
         }else{
             $data['host_name'] = '';
             $data['host_email'] = '';
             $data['host_country_code'] = '';
             $data['host_mobile'] = '';
             $data['host_second_mobile'] = '';
         }
         // dd($data);
         $propertyData = Property::where(['id' => $data->property_id])->with('getExtraService.getServiceData')->first();
         if(isset($propertyData)){
             $data['property_details'] = $propertyData;
         }else{
             $data['property_details'] = '';
         }
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
        Gate::authorize('Transaction-edit');
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
        Gate::authorize('Transaction-edit');
        // validate
        $id = $request->id;
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            $description = $request->file('description');
            try {
                if (isset($description)) {
                  
                    $data = Transection::where('id', $id)->update(['name' => $input['name'], 'description' => $description]);
                } else {
                    $data = Transection::where('id', $id)->update(['name' => $input['name']]);
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
        Gate::authorize('Transaction-delete');
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

    public function sattlementStatus(Request $request)
    {
        $id = $request->id;
        // dd($id);
        $userId = auth()->id();
        // $userId = $id;
        try {
            $data = $this->Models->findOrFail($id);
            $data->sattlement = $request->value;
            // dd($data);
            if($data->save()){
                // dd($data->booking_from);
                // if(isset($data->booking_from) && $data->booking_from == 'Mobile'){
                //     $userId = PropertyReserveRequest::where('id',$userId)->pluck('guest_id')->first();
                //     $userdata = User::where('id',$data->guest_id)->first();

                //     $notificationData = new Notification;
                //     $notificationData->user_type = $userdata->user_type;
                //     $notificationData->notification_type = 1;
                //     $notificationData->notification_for = 'Booking Reservation';
                //     $notificationData->title = 'Booking Reservation';
                //     $notificationData->message = 'Your booking reservation request has been approved. Please payment your booking amount.';
                //     $notificationData->user_id = $userId;
                //     $notificationData->property_id = $data->property_id;
                //     // $notificationData->order_id = $data->id;
                    
                //     $notificationData->save();
                //     send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                // }else{
                //     // send email start
                //     $email = EmailTemplateLang::where('email_id', 15)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                //     $subject = $email->subject;
                //     $record = (object)[];
    
                //     $checkout = '<a href="'.url('reserve-booking-checkout/'.$data->booking_id).'">Checkout</a>';
                //     $description = $email->description;
                //     $description = str_replace("[TOTAL_AMOUNT]!", $data->total_amount.' NGN', $description);
                //     $description = str_replace("[CHECKOUT]!", $checkout, $description);
    
                //     $record->description = $description;
                //     $record->name = $email->name;
                //     $record->footer = $email->footer;
                //     $record->subject = $subject;
                //     $record->cardData = $data;
                    
                //     Mail::send('emails.reserve_booking', compact('record'), function ($message) use ($data, $subject) {
                //         $message->to($data->personal_email, config('app.name'))->subject($subject);
                //         $message->from('customersupport@shortletrenrals.com', config('app.name'));
                //     });
                //     // send email end
                // }
            }
            return ['status'=>1,'type'=>'success','message'=>'Booking reserve Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }
}
