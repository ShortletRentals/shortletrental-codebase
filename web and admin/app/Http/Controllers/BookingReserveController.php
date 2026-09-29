<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PropertyReserveRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Property;
use App\Models\Country;
use App\Models\EmailTemplateLang;
use App\Models\BookingCheckIn;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\import;
use App\Exports\BulkBookingExport;
use App\Models\Notification;

class BookingReserveController extends Controller
{
    protected  $page = 'booking_reserve';
    protected  $lang = 'Reserve Bookings';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new PropertyReserveRequest();
        $this->sortableColumns = [
            0 => 'booking_id',
            1 => 'book',
            // 2 => 'unique_id',
            2 => 'guest_name',
            3 => 'host_name',
            4 => 'total_amount',
            // 6 => 'admin_amount',
            // 7 => 'host_amount',
            5 => 'stay',
            6 => 'booking_status',
            // 10 => 'booking_type',
            7 => 'created_at',
        ];
        $this->sortableColumnsHost = [
            0 => 'booking_id',
            1 => 'book',
            2 => 'host_name',
            3 => 'total_amount',
            4 => 'stay',
            5 => 'total_days',
            6 => 'booking_status',
            7 => 'created_at',
            8 => 'booking_from',
            // 8 => 'host_amount',
        ];
        $this->sortableColumnsInfluencer = [
            0 => 'book',
            1 => 'customer_id',
            2 => 'unique_id',
            3 => 'total_amount',
            4 => 'stay',
            5 => 'booking_type',
            6 => 'created_at',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        // dd($request->wantsJson());
         Gate::authorize('Booking-Reserve-section');

        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        if($user_type == 3){
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $incoming_booking_confirm = $request['incoming_booking_confirm'] ?? null; 
                $sortableColumns = $this->sortableColumns;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking_type,$start_date,$end_date,$user_type,$incoming_booking_confirm);
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
                    // dd($value->guest_id);
                    if($value->guest_id){
                        $customer_id = User::where('id',$value->guest_id)->pluck('unique_id')->first();
                    }else{
                        $customer_id = User::where('id',$value->guest_id)->pluck('unique_id')->first();
                    }
                    $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                    if(isset($hostData)){
                        $host_name = $hostData->name;
                        $host_mobile = $hostData->mobile;
                    }else{
                        $host_name = '';
                        $host_mobile = '';
                    }
                    // dd($value->property_id);
                    $property = Property::where(['id' => $value->property_id])->pluck('title')->first();
                    if(isset($property)){
                        $property_name = $property;
                    }else{
                        $property_name = '';
                    }
                    if(isset($value) && !empty($value->from_date)){
                        $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                        $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                        // dd($from_date, $to_date);
                        $stay_date = $from_date.' - '.$to_date;
                    }else{
                        $stay_date = '';
                    }
                    // dd($stay_date);
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['book'] = $property_name;
                    $row['customer_id'] = isset($customer_id)? $customer_id:'N/A';
                    $row['unique_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    // $row['total_amount'] = $value->total_amount + $value->optional_service_amount - $value->discount_amount;
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                    $row['stay'] = $stay_date;
                    $row['booking_status'] = isset($value->booking_status)? $value->booking_status:'N/A';
                    $row['booking_type'] = isset($value->booking_type)? $value->booking_type:'N/A';
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                    $edit = '';
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                    $check_in_data = BookingCheckIn::where('booking_id',$value->id)->first();
                    if(isset($check_in_data) && !empty($check_in_data)){
                        $check = '<a href="'.customeRoute($this->page.'.check_in_show',['id'=>$value->id]).'" class="ms-3"><i class="bx bx-check" ></i></a>';
                        $row['actions']=createAction($edit.$view.$check);
                    }else{
                        $row['actions']=createAction($edit.$view);
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
            if(isset($request->booking_status)){
                $booking_status = $request->booking_status;
            }else{
                $booking_status = null;
            }
            if(isset($request->booking_type)){
                $booking_type = $request->booking_type;
            }else{
                $booking_type = null;
            }
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type];
            // dd($data);
            return view('admin.'.$this->page.'.influencer_listing',$data);
        }elseif($user_type == 5){
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $incoming_booking_confirm = $request['incoming_booking_confirm'] ?? null; 
                $sortableColumns = $this->sortableColumnsHost;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking_type,$start_date,$end_date,$user_type,$incoming_booking_confirm);
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
                    
                    $extraservice = '';
                        if(isset($value->selected_options))
                        {
                            $extraservice1 = json_decode($value->selected_options);
                            foreach($extraservice1 as $v1)
                            {


                                $extraservice =  $extraservice.$v1->name.',';
                            }
                        }
                        
                //                    $exta_service = json_encode($value->selected_options);


                   
                    $guestData = User::where(['user_type'=>4, 'id' => $value->guest_id])->first();
                    if(isset($guestData)){
                        $guest_name = $guestData->name.' ('.'+'.$guestData->country_code.' '.$guestData->mobile.')';
                        $unique_id = $guestData->unique_id;
                        $guest_mobile = $guestData->mobile;
                    }else{
                        $guest_name = '';
                        $unique_id = '';
                        $guest_mobile = '';
                    }
                    $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                    // dd($hostData);
                    if(isset($hostData)){
                        $host_name = $hostData->name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
                        $host_mobile = $hostData->mobile;
                    }else{
                        $host_name = '';
                        $host_mobile = '';
                    }
                    // dd($value->property_id);
                    $property = Property::where(['id' => $value->property_id])->pluck('title')->first();
                    if(isset($property)){
                        $property_name = $property;
                    }else{
                        $property_name = '';
                    }
                    if(isset($value) && !empty($value->from_date)){
                        $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                        $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                        // dd($from_date, $to_date);
                        $stay_date = $from_date.' - '.$to_date;
                    }else{
                        $stay_date = '';
                    }
                    // dd($stay_date);
                    if(isset($value->personal_last_name) && !empty($value->personal_last_name)){
                        $guest_name = $value->personal_first_name.' '.$value->personal_last_name;
                    }else{
                        $guest_name = isset($value->personal_first_name)? $value->personal_first_name:'N/A';
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['book'] = $property_name;
                    $row['guest_name'] = $guest_name;
                    $row['host_name'] = $host_name;
                    $row['unique_id'] = $unique_id;
                    $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                    $row['total_days'] = isset($value->total_days)? $value->total_days:'-';
                    // $row['total_amount'] = isset($value->total_amount)? $value->total_amount:'N/A';
                    $row['total_amount'] = $value->host_amount +  $value->optional_service_amount; // - $value->discount_amount;
                    $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'-';
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['stay'] = $stay_date;

                    if($value->book_type == 'Approve'){
                        $row['booking_status'] = 'Approve';
                    }else{
                        $row['booking_status'] = bookingStatus($value->book_type, $value->id,['Reserve'=>'Reserve','Approve'=>'Approve','Deny'=>'Deny'],'booking_reserve_status',$this->page.'.bookingStatus')->toHtml();
                    }

                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
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
            if(isset($request->booking_type)){
                $booking_type = $request->booking_type;
            }else{
                $booking_type = null;
            }
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type];
            // dd($data);
            return view('admin.'.$this->page.'.listing_host',$data);
        }else{
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $incoming_booking_confirm = $request['incoming_booking_confirm'] ?? null; 
                $sortableColumns = $this->sortableColumns;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                // dd($start_date,$end_date);
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking_type,$start_date,$end_date,$user_type,$incoming_booking_confirm);
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
                    if($incoming_booking_confirm=='confirm')
                    {
                        $value->confirmed_read = 1;
                    }
                    $value->save();

                    $guestData = User::where(['user_type'=>4, 'id' => $value->guest_id])->first();
                    if(isset($guestData)){
                        $guest_name = $guestData->name.' ('.'+'.$guestData->country_code.' '.$guestData->mobile.')';
                        $unique_id = $guestData->unique_id;
                        $guest_mobile = $guestData->mobile;
                    }else{
                        $guest_name = '';
                        $unique_id = '';
                        $guest_mobile = '';
                    }
                    $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                    // dd($hostData);
                    if(isset($hostData)){
                        $host_name = $hostData->name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
                        $host_mobile = $hostData->mobile;
                    }else{
                        $host_name = '';
                        $host_mobile = '';
                    }
                    // dd($value->property_id);
                    $property = Property::where(['id' => $value->property_id])->pluck('title')->first();
                    if(isset($property)){
                        $property_name = $property;
                    }else{
                        $property_name = '';
                    }
                    if(isset($value) && !empty($value->from_date)){
                        $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                        $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                        // dd($from_date, $to_date);
                        $stay_date = $from_date.' - '.$to_date;
                    }else{
                        $stay_date = '';
                    }
                    // dd($stay_date);
                    if(isset($value->personal_last_name) && !empty($value->personal_last_name)){
                        $guest_name = $value->personal_first_name.' '.$value->personal_last_name;
                    }else{
                        $guest_name = isset($value->personal_first_name)? $value->personal_first_name:'N/A';
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['book'] = $property_name;
                    $row['guest_name'] = $guest_name;
                    $row['host_name'] = $host_name;
                    $row['unique_id'] = $unique_id;
                    // $row['total_amount'] = isset($value->total_amount)? $value->total_amount:'N/A';
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount - $value->discount_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'-';
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['stay'] = $stay_date;

                    if($value->book_type == 'Approve'){
                        $row['booking_status'] = 'Approve';
                    }else{
                        $row['booking_status'] = bookingStatus($value->book_type, $value->id,['Reserve'=>'Reserve','Approve'=>'Approve','Deny'=>'Deny'],'booking_reserve_status',$this->page.'.bookingStatus')->toHtml();
                    }

                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                    $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Delete Booking" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                    if (!auth()->user()->can('Booking-Reserve-delete')) {
                        $delete = '';
                    }
                    $row['actions']=createAction($view.$delete);
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
            if(isset($request->booking_type)){
                $booking_type = $request->booking_type;
            }else{
                $booking_type = null;
            }
            if(isset($request->incoming_booking_confirm)){
                $incoming_booking_confirm = $request->incoming_booking_confirm;
            }else{
                $incoming_booking_confirm = null;
            }
            if(isset($request->from_date)){
                // dd($request->from_date);
                if($request->from_date == 'last_7_days'){
                    $last_7_day_date = \Carbon\Carbon::today()->subDays(7);
                    $from_date = date('Y-m-d',strtotime($last_7_day_date));
                    $to_date = date('Y-m-d');
                }else if($request->from_date == 'last_30_days'){
                    $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
                    $from_date = date('Y-m-d',strtotime($last_30_day_date));
                    $to_date = date('Y-m-d');
                }else if($request->from_date == 'today'){
                    $from_date = date('Y-m-d');
                    $to_date = date('Y-m-d');
                }else{
                    $from_date = $request->from_date;
                    $to_date = date('Y-m-d');
                }
            }else{
                $from_date = null;
                $to_date = null;
            }
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type, 'incoming_booking_confirm'=>$incoming_booking_confirm,'from_date'=>$from_date,'to_date'=>$to_date];
            // dd($data);
            return view('admin.'.$this->page.'.listing',$data);
        }
    }

    public function exportBookings(Request $request)
    {
        // dd('inn');
        $input = $request->all();
        // dd($request->all());
        // dd($input['file_type']);
        $file_type = $input['file_type'];

        // $path = url('/public').'/';
        // $path = public_path('/uploads').'/';
        $path = storage_path('app').'/';
        if($file_type == 'Excel'){
            $fileName = time().'_bookings.xls';
        }else{
            $fileName = time().'_bookings.csv';
        }
        Excel::store(new BulkWarehouseExport($request), $fileName);
        // File::move(storage_path('app/'.$fileName), public_path($fileName));

        $path1 =  public_path('storage') .'/'. 'booking/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path1 . $newFolder;
        // dd($folderPath);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true);
        }
        File::move(storage_path('app/'.$fileName), $folderPath.$fileName);

        $result['status'] = 1;
        $result['url'] = url('public/storage/booking/'.$newFolder.$fileName);
        // $result['url'] = $path.$fileName;
        // dd($result);
        return response()->json($result);
    }

    public function frontend()
    {
        Gate::authorize('Booking-section');
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
        Gate::authorize('Booking-create');
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
            $data = new PropertyReserveRequest;

            
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
        $guestData = User::where(['id' => $data->guest_id])->first();
        if(isset($guestData)){
            $data['guest_name'] = $guestData->name;
            $data['guest_email_address'] = $guestData->email;
            $data['guest_mobile'] = $guestData->mobile;
            $data['guest_second_mobile'] = $guestData->second_mobile;
            $data['is_guest'] = $guestData->is_guest;


        }else{
            $data['guest_name'] = '';
            $data['guest_email_address'] = '';
            $data['guest_mobile'] = '';
            $data['guest_second_mobile'] = '';
            $data['is_guest'] = 0;
        }
        $hostData = User::where(['user_type'=>5, 'id' => $data->host_id])->first();
        if(isset($hostData)){
            $data['host_name'] = $hostData->name;
            $data['host_email'] = $hostData->email;
            $data['host_country_code'] = $hostData->country_code;
            $data['host_mobile'] = $hostData->mobile;
            $data['host_second_mobile'] = $hostData->second_mobile;
            $data['host_is_guest'] = $hostData->is_guest;
        }else{
            $data['host_name'] = '';
            $data['host_email'] = '';
            $data['host_country_code'] = '';
            $data['host_mobile'] = '';
            $data['host_second_mobile'] = '';
            $data['host_is_guest'] =0;
        }
        // dd($data);
        $propertyData = Property::where(['id' => $data->property_id])->with('getExtraService.getServiceData')->first();
        if(isset($propertyData)){
            $data['property_details'] = $propertyData;
        }else{
            $data['property_details'] = '';
        }
        // dd(json_decode($data->selected_options));
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data];
        return view('admin.'.$this->page.'.show',$data);
    }

    public function check_in_show(Request $request)
    {
        $id = $request->id;
        // dd($id);
        $check_in_data = BookingCheckIn::where('booking_id',$id)->first();

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
        $propertyData = Property::where(['id' => $data->property_id])->first();
        if(isset($propertyData)){
            $data['property_details'] = $propertyData;
        }else{
            $data['property_details'] = '';
        }
        // dd($data);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'check_in_data'=>$check_in_data];
        return view('admin.'.$this->page.'.check_in_show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        Gate::authorize('Booking-edit');
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
        Gate::authorize('Booking-edit');
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
               
                    $data = PropertyReserveRequest::where('id', $id)->update(['name' => $input['name'], 'description' => $description]);
                } else {
                    $data = PropertyReserveRequest::where('id', $id)->update(['name' => $input['name']]);
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
        Gate::authorize('Booking-delete');
        if(PropertyReserveRequest::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Booking reserve delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Booking does not delete. ';
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

    public function bookingStatus(Request $request)
    {
  
        $id = $request->id;
        // dd($id);
        // $userId = auth()->id();
        $userId = $id;
        try {
            $data = $this->Models->findOrFail($id);
            $data->book_type = $request->value;
            $userdata = User::where('id',$data->guest_id)->first();
            $hostdata = User::where('id',$data->host_id)->first();
            // dd($data);
            if (isset($userdata->id) && isset($hostdata->id)) {
                if($data->save()){
                    // dd($data->booking_from);
                    if(isset($data->booking_from) && $data->booking_from == 'Mobile'){
                        $userId = PropertyReserveRequest::where('id',$userId)->pluck('guest_id')->first();
                  
                        
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Booking Reservation';
                            $notificationData->title = 'Booking Reservation';
                            $notificationData->message = 'Your booking reservation request has been approved. Please payment your booking amount.';
                            $notificationData->user_id = $userId;
                            $notificationData->property_id = $data->property_id;
                             $notificationData->order_id = $data->id;
                            
                            $notificationData->save();
                        if(isset($userdata) && $userdata->notification_on_off == 'On'){
                            send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }
                    }else{

                       // dd('tst');

                        $booking_data = $data; 
                        $record = (object)[];
                        // send email start
                        if($data->book_type=='Approve')
                        {
                          //  dd($userdata);

                            // host
                            if (isset($hostdata->name)) {
                                $email = EmailTemplateLang::where('email_id', 31)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                                $viewPage = 'emails.booking_confimed_host';
                                $subject = $email->subject;
                                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);
                                $description = $email->description;
                                $description = str_replace("[NAME]", ucfirst($hostdata->name).' '.$hostdata->surname, $description);
                                $description = str_replace("[Number]",$data->total_days, $description);
                                $record->name = $email->name;
                                $record->footer = $email->footer;
                                $record->username = $hostdata->name;
                                $record->property_name = $booking_data->getProperty->title;
                                $record->subject = $subject;
                                $record->description = $description;
                                $record->user_email = $hostdata->email;
                                $record->cardData = $booking_data;
                                $record->check_in_url = url('guest-area/checkin/search');


                                Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                                    $message->to($hostdata->email, config('app.name'))->subject($subject);
                                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                });
                            }
                            
                            
                            // guest 
                            if (isset($userdata->name)) {
                                $email = EmailTemplateLang::where('email_id', 15)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                                $viewPage = 'emails.reserve_booking';
                                $subject = $email->subject;
                                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);
                                $checkout = '<br><br><b><a href="'.url('reserve-booking-checkout/'.$data->booking_id).'" style="border: 1px dashed #000; padding: 5px;margin: 22px;background: #F1592A;color: #fff;font-size: unset;font-weight: 800;"> Click Here to Make Payment </a></b><br><br>';
                                $description = $email->description;
                                $description = str_replace("[NAME]", ucfirst($userdata->name).' '.$userdata->surname, $description);
                                $description = str_replace("[TOTAL_AMOUNT]!", $data->total_amount + $data->optional_service_amount - $data->discount_amount.' NGN', $description);
                                $description = str_replace("[CHECKOUT]!", $checkout, $description);
                                $record->description = $description;
                                $record->name = $email->name;
                                $record->username = $userdata->name;
                                $record->property_name = $booking_data->getProperty->title;
                                $record->subject = $subject;
                                $record->description = $description;
                                $record->user_email = $userdata->email;
                                $record->cardData = $booking_data;
                                $record->check_in_url = url('guest-area/checkin/search');
                                
                                Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                                    $message->to($userdata->email, config('app.name'))->subject($subject);
                                    $message->from('customersupport@shortletrentals.com', config('app.name'));
                                });
                            }
                        }
                        if($data->book_type=='Deny')
                        {
                            //host

                            /*$email = EmailTemplateLang::where('email_id', 32)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                            $viewPage = 'emails.booking_confimed_host';
                            $subject = $email->subject;
                            $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);
                            
                            $description = $email->description;
                            $description = str_replace("[NAME]", ucfirst($hostdata->name).' '.$hostdata->surname, $description);
                            $description = str_replace("[Number]", $data->total_days, $description);
                            $record->name = $email->name;
                            $record->username = $hostdata->name;
                            $record->subject = $subject;
                            $record->property_name = $booking_data->getProperty->title;
                            $record->user_email = $hostdata->email;
                            $record->cardData = $booking_data;
                            $record->check_in_url = url('guest-area/checkin/search');
                            $record->description = $description;
                            Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                                $message->to($hostdata->email, config('app.name'))->subject($subject);
                                $message->from('customersupport@shortletrenrals.com', config('app.name'));
                            });*/

                            //Guest

                            /**/

                             // Guest 
                            $viewPage = 'emails.booking_cancel';
                            $email = EmailTemplateLang::where('email_id', 24)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                        
                            $subject = $email->subject;  
                            $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                            $description = $email->description;
                            $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                            $description = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title,  $description);
                            $record->description = str_replace("[Booking_Id]",$booking_data->booking_id, $description);
                            $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
                            $checkout = '<br><br><b><a href="'.url('booking-rating-form/'.$data->booking_id).'" style="border: 1px dashed #000; padding: 5px;margin: 22px;background: #F1592A;color: #fff;font-size: unset;font-weight: 800;"> Rate your stay</a></b><br><br>';
                            $description = str_replace("[Rate_your_stay]", $checkout, $description);
                            $record->description = $description;    
                            $record->name = $email->name;
                            $record->footer = $email->footer;
                            $record->name = $email->name;
                            $record->username = $userdata->name.' '.$userdata->surname;
                            $record->property_name = $data->getProperty->title;
                            $record->subject = $subject;
                            $record->user_email = $userdata->email;
                            $record->cardData = $data;
                            $record->check_in_url = url('guest-area/checkin/search');

                            Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                                $message->to($userdata->email, config('app.name'))->subject($subject);
                                $message->from('customersupport@shortletrenrals.com', config('app.name'));
                            });
                            // Host
                            $email = EmailTemplateLang::where('email_id', 27)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                            $viewPage = 'emails.reserve_booking_deny';
                            $subject = $email->subject;
                            $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);
                            $description = $email->description;
                            $description = str_replace("[NAME]", ucfirst($hostdata->name).' '.$hostdata->surname, $description);
                            $checkout = '<br><br><b><a href="'.url('/').'" style="border: 1px dashed #000; padding: 5px;margin: 22px;background: #F1592A;color: #fff;font-size: unset;font-weight: 800;">Click Here </a></b><br><br>';
                            $description = str_replace("[Click_Here]", $checkout, $description);
                            $record->description = $description;
                            $record->username = $hostdata->name;
                            $record->subject = $subject;
                            $record->property_name = $booking_data->getProperty->title;
                            $record->user_email = $hostdata->email;
                            $record->cardData = $booking_data;
                            $record->check_in_url = url('guest-area/checkin/search');
                             
                            Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                                $message->to($hostdata->email, config('app.name'))->subject($subject);
                                $message->from('customersupport@shortletrenrals.com', config('app.name'));
                            });

                        }
                    }
                }
            }
            return ['status'=>1,'type'=>'success','message'=>'Booking reserve Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function bookingType(Request $request)
    {
        $id = $request->id;
        try {
            $data = $this->Models->findOrFail($id);
            $data->booking_type = $request->value;
            if($data->save()){
                if (isset($userId)) {
                    $userdata = User::where('id',$userId)->first();
                    $notificationData = new Notification;
                    $notificationData->user_type = $userdata->user_type;
                    $notificationData->notification_type = 1;
                    $notificationData->notification_for = 'Booking';
                    $notificationData->title = 'Booking '.str_replace('-',' ',$request->value).'.';
                    $notificationData->message = 'Your booking has been '.str_replace('-',' ',$request->value).' - '.$data->booking_id;
                    $notificationData->user_id = $userId;
                    $notificationData->property_id = $data->property_id;
                    $notificationData->order_id = $data->id;
                    
                    $notificationData->save();
                    send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                }
            }
            return ['status'=>1,'type'=>'success','message'=>'Booking Type update Successfully.'];
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
