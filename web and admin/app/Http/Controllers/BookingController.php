<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\UserLoyaltyPoint;
use App\Models\Property;
use App\Models\Country;
use App\Models\EmailTemplateLang;
use App\Models\BookingCheckIn;
use App\Models\Rating;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\import;
use App\Exports\BulkBookingExport;
use App\Models\Notification;
use Carbon\Carbon;
use DateTime;


class BookingController extends Controller
{
    protected  $page = 'booking';
    protected  $lang = 'Booking';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Booking();
        $this->sortableColumns = [
            0 => 'booking_id',
            1 => 'book',
            2 => 'unique_id',
            3 => 'guest_name',
            4 => 'host_name',
            5 => 'total_amount',
            6 => 'admin_amount',
            7 => 'host_amount',
            8 => 'optional_service_amount',
            9 => 'stay',
            10 => 'booking_status',
            11 => 'booking_type',
            12 => 'created_at',
            13 => 'booking_from',
        ];
        $this->sortableColumnsHost = [
            0 => 'booking_id',
            1 => 'stay',
            2 => 'booking_status',
            3 => 'booking_type',
            4 => 'created_at',
            5 => 'booking_from',
            6 => 'no_of_guest',
            7 => 'total_days',
            8 => 'book',
            9 => 'optional_service_amount',
            10 => 'host_amount',
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
        Gate::authorize('Booking-section');
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        
        if($user_type == 3){
        
            if ($request->wantsJson()) {
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking = $request['booking'] ?? null;
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $booking_id = $request['booking_id'] ?? null; 
                $date_of = $request['date_of'] ?? null ;
                $guest_id = ''; 
                $sortableColumns = $this->sortableColumnsInfluencer;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                $incoming_booking_confirm = '';
                $from_date = '';
                $pending_actions = '';
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status, $booking,$booking_type,$start_date,$end_date,$user_type, $incoming_booking_confirm,$from_date,$pending_actions,$booking_id,$guest_id, $date_of);
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
                    $property = Property::where(['id' => $value->property_id])->first();
                    if(isset($property)){
                        $property_name = $property->title;
                        $reference_no = $property->code;
                    }else{
                        $property_name = '';
                        $reference_no = ''; 
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
                    $row['reference_no'] = $reference_no;
                    $row['total_days'] = $value->total_days;
                    
                    $row['customer_id'] = isset($customer_id)? $customer_id:'N/A';
                    $row['unique_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                    // $row['total_amount'] = $value->total_amount + $value->optional_service_amount - $value->discount_amount;
                    $row['total_amount'] = $value->total_booking_amount-$value->security_deposite;
                    $row['stay'] = $stay_date;
                    $row['booking_status'] = isset($value->booking_status)? $value->booking_status:'N/A';
                    $row['booking_type'] = isset($value->booking_type)? $value->booking_type:'N/A';
                    $row['created_at'] = date('d M Y', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                    $edit = '';
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                    $check_in_data = BookingCheckIn::where('booking_id',$value->id)->first();
                    if(!isset($check_in_data))
                    {
                        $row['booking_check_in'] = 'No';
                    }
                    else{
                        if($check_in_data->is_approved == 1)
                        {
                            $row['booking_check_in']    = 'Approve';
                        }
                        else if($check_in_data->is_approved == 0){
                            $row['booking_check_in']    = 'Pending';
                        }

                        else{
                            $row['booking_check_in']    = 'Reject';
                        }


                    }
                    if(isset($check_in_data) && !empty($check_in_data)){
                        $check = '<a href="'.customeRoute($this->page.'.check_in_show',['id'=>$value->id]).'" class="ms-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m10 15.586l-3.293-3.293l-1.414 1.414L10 18.414l9.707-9.707l-1.414-1.414z"/></svg></a>';
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
            if(isset($request->date_of)){
                $date_of = $request->date_of;
            }else{
                $date_of = null;
            }
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'date_of'=>$date_of,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type];
            // dd($data);
            return view('admin.'.$this->page.'.influencer_listing',$data);
        }else if($user_type == 5){




            if ($request->wantsJson()) {
            
                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $booking = $request['booking'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $incoming_booking_confirm = $request['incoming_booking_confirm'] ?? null; 
                $from_date = $request['from_date'] ?? null; 
                $pending_actions = $request['pending_actions'] ?? null; 
                $booking_id = $request['booking_id'] ?? null; 
                $guest_id = ''; 
                $sortableColumns = $this->sortableColumnsHost;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                $date_of = $request['date_of'] ?? null ;
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking,$booking_type,$start_date,$end_date,$user_type, $incoming_booking_confirm,$from_date,$pending_actions,$booking_id,$guest_id, $date_of);
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
                    // $guestData = User::where(['id' => $value->guest_id])->first();
                    // if(isset($guestData)){
                    //     if(isset($guestData->mobile) && !empty($guestData->mobile)){
                    //         $guest_name = $guestData->name.' ('.'+'.$guestData->country_code.' '.$guestData->mobile.')';
                    //     }else{
                    //         $guest_name = $guestData->name;
                    //     }
                    //     $unique_id = $guestData->unique_id;
                    //     $guest_mobile = $guestData->mobile;
                    // }else{
                    //     $guest_name = '';
                    //     $unique_id = '';
                    //     $guest_mobile = '';
                    // }
                    // $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                    // if(isset($hostData)){
                    //     if(isset($hostData->mobile) && !empty($hostData->mobile)){
                    //         $host_name = $hostData->name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
                    //     }else{
                    //         $host_name = $hostData->name;
                    //     }
                    //     $host_mobile = $hostData->mobile;
                    // }else{
                    //     $host_name = '';
                    //     $host_mobile = '';
                    // }
                    $extraservice = '';
                        if(isset($value->selected_options))
                        {
                            $extraservice1 = json_decode($value->selected_options);
                            foreach($extraservice1 as $v1)
                            {


                                $extraservice =  $extraservice.$v1->name.',';
                            }
                        }

                    $property = Property::with('getExtraService.getServiceData')->where(['id' => $value->property_id])->first();
              

                    if(isset($property)){
                     
                        


                        $property_name = $property->title;
                    }else{
                        $property_name = '';
                    }
                    if(isset($value) && !empty($value->from_date)){
                        $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                        $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                        $stay_date = $from_date.' - '.$to_date;
                    }else{
                        $stay_date = '';
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                    $row['total_days'] = isset($value->total_days)? $value->total_days:'-';
                    $row['stay'] = $stay_date;

                    if (auth()->user()->can('Booking-edit')) 
                    {
                        $row['booking_status'] = bookingStatus($value->booking_status, $value->id,['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'],'booking_status',$this->page.'.bookingStatus')->toHtml();
                        $row['booking_type'] = bookingType($value->booking_type, $value->id,['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'],'booking_type',$this->page.'.bookingType')->toHtml();
                    }
                    else
                    {
                        $row['booking_status'] = str_replace('-',' ',$value->booking_status);
                        $row['booking_type'] = str_replace('-',' ',$value->booking_status);
                    }

                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                    $row['book'] = $property_name;
                    $row['extra_service'] = $extraservice.'<br/>('.$value->optional_service_amount.')';
                    $edit = '';
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                    $check_in_data = BookingCheckIn::where('booking_id',$value->id)->first();
                    if(!isset($check_in_data))
                    {
                        $row['booking_check_in'] = 'No';
                    }
                    else{
                        if($check_in_data->is_approved == 1)
                        {
                            $row['booking_check_in']    = 'Approve';
                        }
                        else if($check_in_data->is_approved == 0){
                            $row['booking_check_in']    = 'Pending';
                        }

                        else{
                            $row['booking_check_in']    = 'Reject';
                        }


                    }

                    if(isset($check_in_data) && !empty($check_in_data)){
                        $check = '<a href="'.customeRoute($this->page.'.check_in_show',['id'=>$value->id]).'" class="ms-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m10 15.586l-3.293-3.293l-1.414 1.414L10 18.414l9.707-9.707l-1.414-1.414z"/></svg></a>';
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
            if(isset($request->booking)){
                $booking = $request->booking;
            }else{
                $booking = null;
            }
            // dd($request->incoming_booking_confirm);
            if(isset($request->incoming_booking_confirm)){
                $incoming_booking_confirm = $request->incoming_booking_confirm;
            }else{
                $incoming_booking_confirm = null;
            }
            // dd($request->from_date);
            if(isset($request->from_date)){
                $from_date = $request->from_date;
            }else{
                $from_date = null;
            }
            if(isset($request->pending_actions)){
                $pending_actions = $request->pending_actions;
            }else{
                $pending_actions = null;
            }
            if(isset($request->date_of)){
                $date_of = $request->date_of;
            }else{
                $date_of = null;
            }
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'date_of'=>$date_of,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type, 'booking'=>$booking, 'incoming_booking_confirm'=>$incoming_booking_confirm, 'from_date'=>$from_date,'pending_actions'=>$pending_actions,'startDate'];
            // dd($data);
            return view('admin.'.$this->page.'.listing_host',$data);
        }else{
            /*
            $from_date =Null;
            $to_date = Null;
            $booking_type = Null;
            if($request->upcoming_booking=='upcoming' && $request->days=='next_7_days')
            {
                $from_date = Carbon::now()->format('Y-m-d');
                $to_date = Carbon::now()->addDays(7)->format('Y-m-d');
                $e = Carbon::now()->addDays(30)->format('Y-m-d');
                $booking_type = $request->upcoming_booking;
                
            }
            if($request->upcoming_booking=='upcoming' && $request->days=='next_30_days')
            {
               // $startDate = Carbon::now()->format('Y-m-d');
              
                $booking_type = $request->upcoming_booking;
            }

            */
            // dd($request->booking);
            if ($request->wantsJson()) {

                $limit = $request->input('length');
                $start = $request->input('start');
                $search = $request['search']['value'];
                $orderby = $request['order']['0']['column'];
                $order = $orderby != "" ? $request['order']['0']['dir'] : "";
                $draw = $request['draw'];
                $booking_type = $request['booking_type'] ?? null;
                $booking_status = $request['booking_status'] ?? null;
                $booking = $request['booking'] ?? null;
                $start_date = $request['start_date'] ?? null ;
                $end_date = $request['end_date'] ?? null; 
                $incoming_booking_confirm = $request['incoming_booking_confirm'] ?? null; 
                $from_date = $request['from_date'] ?? null; 
                $pending_actions = $request['pending_actions'] ?? null; 
                $booking_id = $request['booking_id'] ?? null; 
                $guest_id = $request['guest_id'] ?? null; 
                $sortableColumns = $this->sortableColumns;
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                $date_of = $request['date_of'] ?? null ;
                $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking,$booking_type,$start_date,$end_date,$user_type, $incoming_booking_confirm,$from_date,$pending_actions,$booking_id,$guest_id, $date_of);

                if(isset($request->upcoming_booking) && $request->upcoming_booking == 'upcoming')
                {
                    $from_date = Carbon::now()->format('Y-m-d');
                    if($request->days == 'next_7_days')
                    {                        
                        $to_date = Carbon::now()->addDays(7)->format('Y-m-d');                      
                    }
                    if($request->days == 'next_30_days')
                    {
                        $to_date = Carbon::now()->addDays(30)->format('Y-m-d');
                    }
                    $querydata->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->whereBetween('bookings.from_date', [$from_date, $to_date]);
                }
                if(isset($request->upcoming_booking) && $request->upcoming_booking == 'current')
                {
                    $querydata->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->whereRaw(\DB::raw('CURDATE() between bookings.from_date and bookings.to_date'));
                }
                if(isset($request->upcoming_booking) && $request->upcoming_booking == 'Cancelled-Booking')
                {
                    $querydata->where('bookings.booking_status', $request->upcoming_booking);
                }
                if(isset($request->upcoming_booking) && $request->upcoming_booking == 'Confirmed-by-Host')
                {
                    $querydata->where('bookings.booking_status', $request->upcoming_booking);
                }
                if(isset($request->upcoming_booking) && $request->upcoming_booking == 'Pending-payment')
                {
                    $querydata->where('bookings.booking_status', 'Not-confirmed-by-Host');
                    $querydata->whereIn('booking_type',['Pre-booking']);
                }

               

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

           
                    
                    if($incoming_booking_confirm=='instant')
                    {
                        $value->instant_read = 1;
                    }
                    if($incoming_booking_confirm=='assigned')
                    {
                        $value->assigned_read = 1;
                    }
                    if($incoming_booking_confirm=='request')
                    {
                        $value->requests_read = 1;
                    }
                    $value->save();
                    $guestData = User::where(['id' => $value->guest_id])->first();
                    if(isset($guestData)){
                        if(isset($guestData->mobile) && !empty($guestData->mobile)){
                            $guest_name = $guestData->name.' ('.'+'.$guestData->country_code.' '.$guestData->mobile.')';
                        }else{
                            $guest_name = $guestData->name;
                        }
                        $unique_id = $guestData->unique_id;
                        $guest_mobile = $guestData->mobile;
                    }
                    else
                    {
                        $guest_name = '';
                        $unique_id = '';
                        $guest_mobile = '';
                    }
                    $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                    if(isset($hostData)){
                        if(isset($hostData->mobile) && !empty($hostData->mobile)){
                            $host_name = $hostData->name.' ('.'+'.$hostData->country_code.' '.$hostData->mobile.')';
                        }else{
                            $host_name = $hostData->name;
                        }
                        $host_mobile = $hostData->mobile;
                    }else{
                        $host_name = '';
                        $host_mobile = '';
                    }
                    $property = Property::where(['id' => $value->property_id])->pluck('title')->first();
                    if(isset($property)){
                        $property_name = $property;
                    }else{
                        $property_name = '';
                    }
                    if(isset($value) && !empty($value->from_date)){
                        $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                        $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                        $stay_date = $from_date.' - '.$to_date;
                    }else{
                        $stay_date = '';
                    }
                    $row['id'] = $i;
                    $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                    $row['book'] = $property_name;
                    // $row['guest_name'] = $guest_name;
                    if(isset($value->personal_first_name) && !empty($value->personal_last_name)){
                        $row['guest_name'] = $value->personal_first_name.' '.$value->personal_last_name.' ('.$value->personal_phone_number.')';
                    }else{
                        $row['guest_name'] = '-';
                    }
                    $row['host_name'] = $host_name;
                    $row['unique_id'] = $unique_id;
                    $row['no_of_guest'] = $value->no_of_adult_guest + $value->no_of_children_guest + $value->no_of_babies_guest;
                    $row['total_amount'] = $value->total_amount + $value->optional_service_amount - $value->discount_amount;
                    $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'-';
                    $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                    $row['extra_service'] = isset($value->optional_service_amount) ? $value->optional_service_amount:'-';
                    $row['stay'] = $stay_date;

                    if (auth()->user()->can('Booking-edit')) 
                    {
                        $row['booking_status'] = bookingStatus($value->booking_status, $value->id,['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'],'booking_status',$this->page.'.bookingStatus')->toHtml();
                        $row['booking_type'] = bookingType($value->booking_type, $value->id,['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'],'booking_type',$this->page.'.bookingType')->toHtml();
                    }
                    else
                    {
                        $row['booking_status'] = str_replace('-',' ',$value->booking_status);
                        $row['booking_type'] = str_replace('-',' ',$value->booking_status);
                    }
//                    $row['booking_status'] = bookingStatus($value->booking_status, $value->id,['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'],'booking_status',$this->page.'.bookingStatus')->toHtml();
//                    $row['booking_type'] = bookingType($value->booking_type, $value->id,['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'],'booking_type',$this->page.'.bookingType')->toHtml();
                    $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
                    $row['booking_from'] = isset($value->booking_from)? $value->booking_from:'N/A';
                    $edit = '';
                    $view = viewAction($this->page.'.show',['id'=>$value->id]);
                    $check_in_data = BookingCheckIn::where('booking_id',$value->id)->first();
                    if(!isset($check_in_data))
                    {
                        $row['booking_check_in'] = 'No';
                    }
                    else{
                        if($check_in_data->is_approved == 1)
                        {
                            $row['booking_check_in']    = 'Approve';
                        }
                        else if($check_in_data->is_approved == 0){
                            $row['booking_check_in']    = 'Pending';
                        }

                        else{
                            $row['booking_check_in']    = 'Reject';
                        }


                    }
                    

                    $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Delete Booking" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                    if (!auth()->user()->can('Booking-delete')) {
                        $delete = '';
                    }


                    if(isset($check_in_data) && !empty($check_in_data)){
                        $check = '<a href="'.customeRoute($this->page.'.check_in_show',['id'=>$value->id]).'" class="ms-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m10 15.586l-3.293-3.293l-1.414 1.414L10 18.414l9.707-9.707l-1.414-1.414z"/></svg></a>';
                        $row['actions']=createAction($edit.$view.$delete.$check);
                    }else{
                        $row['actions']=createAction($edit.$view.$delete);
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
            if(isset($request->booking)){
                $booking = $request->booking;
            }else{
                $booking = null;
            }
            // dd($request->incoming_booking_confirm);
            if(isset($request->incoming_booking_confirm)){
                $incoming_booking_confirm = $request->incoming_booking_confirm;
            }else{
                $incoming_booking_confirm = null;
            }
            // dd($request->from_date);
            if(isset($request->from_date)){
                $from_date = $request->from_date;
            }
            else{
                $from_date = null;
            }
            if(isset($request->pending_actions)){
                $pending_actions = $request->pending_actions;
            }else{
                $pending_actions = null;
            }
            if(isset($request->pending_actions)){
                $pending_actions = $request->pending_actions;
            }else{
                $pending_actions = null;
            }
            if(isset($request->date_of)){
                $date_of = $request->date_of;
            }else{
                $date_of = null;
            }
            $guest_users = User::select('id','name','surname','country_code','mobile')->where(['status'=>1,'user_type'=>4])->get();
        
            $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'date_of'=>$date_of,'booking_status'=>$booking_status,'booking_type'=>$booking_type, 'booking'=>$booking, 'incoming_booking_confirm'=>$incoming_booking_confirm, 'from_date'=>$from_date, 'pending_actions'=>$pending_actions, 'guest_users'=>$guest_users,'upcoming_booking'=>$request->upcoming_booking ?? null,'days'=>$request->days];
            return view('admin.'.$this->page.'.listing',$data);
        }
    }

    public function indexWithGet(request $request, $booking_status)
    {
        // dd($request->wantsJson());
        Gate::authorize('Booking-section');
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
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
            $sortableColumns = $this->sortableColumns;
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$booking_status,$booking_type,$start_date,$end_date,$user_type);
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
                $guestData = User::where(['id' => $value->guest_id])->first();
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
                $row['id'] = $i;
                $row['booking_id'] = isset($value->booking_id)? $value->booking_id:'N/A';
                $row['book'] = $property_name;
                $row['guest_name'] = $guest_name;
                $row['host_name'] = $host_name;
                $row['unique_id'] = $unique_id;
                // $row['total_amount'] = isset($value->total_amount)? $value->total_amount:'N/A';
                $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                $row['admin_amount'] = isset($value->admin_amount)? $value->admin_amount:'-';
                $row['host_amount'] = isset($value->host_amount)? $value->host_amount:'-';
                $row['stay'] = $stay_date;
                // $row['booking_status'] = '<select class="form-control booking_status" name="booking_status"><option value="Not-confirmed-by-Host" ';
                // $row['booking_status'] .= if($value->booking_status == "Not-confirmed-by-Host") { echo "selected"; }
                // $row['booking_status'] .= '>Not confirmed by Host</option><option value="Confirmed-by-Host">Confirmed by Host</option><option value="Ongoing-Booking">Ongoing Booking</option><option value="Completed-Booking">Completed Booking</option><option value="Cancelled-Booking">Cancelled Booking</option></select>';

                $row['booking_status'] = bookingStatus($value->booking_status, $value->id,['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'],'booking_status',$this->page.'.bookingStatus')->toHtml();
                // $row['booking_status'] = isset($value->booking_status)? str_replace('-',' ',$value->booking_status):'N/A';

                $row['booking_type'] = bookingType($value->booking_type, $value->id,['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'],'booking_type',$this->page.'.bookingType')->toHtml();
                // $row['booking_type'] = isset($value->booking_type)? $value->booking_type:'N/A';
                $row['created_at'] = date('d M Y h:i', strtotime($value->created_at));
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
    
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'booking_status'=>$booking_status,'booking_type'=>$booking_type];
        // dd($data);
        return view('admin.'.$this->page.'.listing',$data);
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
            $data = new Booking;

         
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
            $data['guest_id'] = $guestData->unique_id;
            $data['guest_name'] = $guestData->name;
            $data['guest_email_address'] = $guestData->email;
            $data['guest_mobile'] = $guestData->mobile;
            $data['guest_second_mobile'] = $guestData->second_mobile;
            $data['is_guest'] = $guestData->is_guest;
        }else{
            $data['guest_id'] = '';
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
            $data['host_is_guest'] = 0;
        }
        // dd($data);
        $propertyData = Property::where(['id' => $data->property_id])->first();
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
        $guestData = User::where(['id' => $data->guest_id])->first();
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
                   
                    $data = Booking::where('id', $id)->update(['name' => $input['name'], 'description' => $description]);
                } else {
                    $data = Booking::where('id', $id)->update(['name' => $input['name']]);
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
        Gate::authorize('Booking-delete');
        if(Booking::findOrFail($id)->delete()){
            $response['status'] = true;
            $response['message'] = 'Booking delete successfully. ';
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
        // $userId = $id;
     
        $email_id = 0;
        $record = (object)[];
        $data = $this->Models->findOrFail($id);
        $userId = $data->guest_id;
        $hostId = $data->host_id;
        $userdata = User::where('id',$userId)->first();
        $booking_data = $data;

     
        if($request->value == 'Confirmed-by-Host'  && $data->booking_type=='Paid')
        {
            
            if ($userdata = User::where('id',$userId)->first()) {
                $viewPage = 'emails.booking_confirm';
                
                $email = EmailTemplateLang::where('email_id', 12)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
    
                $subject = $email->subject;  
            
                $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                $description = $email->description;
                $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
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
            }
            if ($hostdata = User::where('id',$hostId)->first()) {
                $viewPage = 'emails.booking_confimed_host';
                $userdata = User::where('id',$userId)->first();
                $email = EmailTemplateLang::where('email_id', 29)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
    
                $subject = $email->subject;  
                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);   
                $description = $email->description;
                $description = str_replace("[PROPERTY_NAME]",$data->getProperty->title, $description);  
                $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                $description = str_replace("[GUEST_NAME]",$userdata->name.' '.$userdata->surname, $description);
                $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
    
    
                $record->description = $description;
    
                $record->footer = $email->footer;
                $record->name = $email->name;
                $record->username = $hostdata->name.' '.$hostdata->surname;
                $record->property_name = $data->getProperty->title;
                $record->subject = $subject;
                $record->user_email = $hostdata->email;
                $record->cardData = $data;
                $record->check_in_url = url('guest-area/checkin/search');

                Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                    $message->to($hostdata->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });
            }    
                
                
        }


        if($request->value == 'Completed-Booking')
        {
            // Guest 
            if ($userdata = User::where('id',$userId)->first()) {
                $viewPage = 'emails.booking_confirmed_paid';
                
                $email = EmailTemplateLang::where('email_id', 23)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                $subject = $email->subject;  
            
                $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                $description = $email->description;
                $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                $description = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title,  $description);
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
            }

            // Host
            if ($hostdata = User::where('id',$hostId)->first()) {
                $userdata = User::where('id',$userId)->first();
                $viewPage = 'emails.booking_confimed_host';
                
                $email = EmailTemplateLang::where('email_id', 33)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                $subject = $email->subject;  
                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);   
                $description = $email->description;
                $description = str_replace("[PROPERTY_NAME]",$data->getProperty->title, $description);  
                $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                $description = str_replace("[GUEST_NAME]",$userdata->name.' '.$userdata->surname, $description);
                $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);


                $record->description = $description;

                $record->footer = $email->footer;
                $record->name = $email->name;
                $record->username = $hostdata->name.' '.$hostdata->surname;
                $record->property_name = $data->getProperty->title;
                $record->subject = $subject;
                $record->user_email = $hostdata->email;
                $record->cardData = $data;
                $record->check_in_url = url('guest-area/checkin/search');
                
                Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                    $message->to($hostdata->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });
            }


        }


        if($request->value == 'Ongoing-Booking')
        {

        
            // Guest 
            if ($userdata = User::where('id',$userId)->first()) {
                $viewPage = 'emails.booking_confirmed_paid';
            
                $email = EmailTemplateLang::where('email_id', 22)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                $subject = $email->subject;  
            
                $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                $description = $email->description;
                $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                $description = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title,  $description);
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
            }
            
        }
        //cancelBooking
        if($request->value == 'Cancelled-Booking')
        {
            $bookingData = $booking_data;
            $property = Property::where('id',$bookingData->property_id)->first();
            $userInfo = User::where('id',$bookingData->guest_id)->first();
            if ($bookingData) {
                $from_date = $bookingData->from_date;
                $current_date = Carbon::now()->format('Y-m-d h:i:s');

                $datetime1 = new DateTime($from_date);
                $datetime2 = new DateTime($current_date);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
   
                if($property->refund == 'YES')
                {
                    if(isset($days) && $days >= 14){
                        $total_amount = $bookingData->total_amount;

                        if(isset($bookingData->access_code))
                        {
                            $url = "https://api.paystack.co/refund";
                            $fields = [                                
                                'transaction' => $bookingData->access_code,
                                'amount' => $total_amount,
                            ];
                            $fields_string = http_build_query($fields);
                            //open connection
                            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';

                            $ch = curl_init();                            
                            //set the url, number of POST vars, POST data
                            curl_setopt($ch,CURLOPT_URL, $url);
                            curl_setopt($ch,CURLOPT_POST, true);
                            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Authorization: Bearer ".$live_key,
                                "Cache-Control: no-cache",
                            ));                            
                            //So that curl_exec returns the contents of the cURL; rather than echoing it
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                                    
                            $result = curl_exec($ch);
                            // echo $result;
                            $result1_decode = json_decode($result);

                            if ($result1_decode->status == true) {
                                $authorization_url = $result1_decode->data->authorization_url;
                                $reference = $result1_decode->data->reference;
                                $access_code = $result1_decode->data->access_code;
                            } else {
                                $authorization_url = '';
                                $reference = '';
                                $access_code = '';
                            }
                            /*Payment Api*/
                        }

                        $bookingData->refund_amount = $total_amount;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();
                    }else if($days < 14 && $days > 3 ){
                        $total_amount = $bookingData->total_amount / 2;
                        if(isset($bookingData->access_code))
                        {
                            $url = "https://api.paystack.co/refund";
                            $fields = [                                
                                'transaction' => $bookingData->access_code,
                                'amount' => $total_amount,
                            ];
                            $fields_string = http_build_query($fields);
                            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
                            
                            //open connection
                            $ch = curl_init();                            
                            //set the url, number of POST vars, POST data
                            curl_setopt($ch,CURLOPT_URL, $url);
                            curl_setopt($ch,CURLOPT_POST, true);
                            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Authorization: Bearer ".$live_key,
                                "Cache-Control: no-cache",
                            ));                            
                            //So that curl_exec returns the contents of the cURL; rather than echoing it
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                                    
                            $result = curl_exec($ch);
                            // echo $result;
                            $result1_decode = json_decode($result);

                            if ($result1_decode->status == true) {
                                $authorization_url = $result1_decode->data->authorization_url;
                                $reference = $result1_decode->data->reference;
                                $access_code = $result1_decode->data->access_code;
                            } else {
                                $authorization_url = '';
                                $reference = '';
                                $access_code = '';
                            }
                        }
                        /*Payment Api*/

                        $bookingData->refund_amount = $total_amount;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();
                    }else if($days < 3 ){
                        $bookingData->refund_amount = 0;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();                  
                    }
                    else{
                        $bookingData->refund_amount = 0;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();  
                    }
                }
                else{
                    $bookingData->refund_amount = 0;
                    $bookingData->booking_status = 'Cancelled-Booking';
                    $bookingData->save();  
                }
            }

            // Guest 
            if ($userdata = User::where('id',$userId)->first()) {
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
            }
            // Host
            if ($hostdata = User::where('id',$hostId)->first()) {
                $userdata = User::where('id',$userId)->first();
                $viewPage = 'emails.booking_confimed_host';

                $email = EmailTemplateLang::where('email_id', 34)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                $subject = $email->subject;  
                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);   
                $description = $email->description;
                $description = str_replace("[PROPERTY_NAME]",$data->getProperty->title, $description);  
                $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                $description = str_replace("[GUEST_NAME]",$userdata->name.' '.$userdata->surname, $description);
                $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);


                $record->description = $description;

                $record->footer = $email->footer;
                $record->name = $email->name;
                $record->username = $hostdata->name.' '.$hostdata->surname;
                $record->property_name = $data->getProperty->title;
                $record->subject = $subject;
                $record->user_email = $hostdata->email;
                $record->cardData = $data;
                $record->check_in_url = url('guest-area/checkin/search');

                Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                    $message->to($hostdata->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });
            }
        }



        
      
        try {
            $data = $this->Models->findOrFail($id);
            $data->booking_status = $request->value;
            if($data->save()){
                $userId = $data->guest_id;
                if($request->value != 'Completed-Booking'){
                    if (isset($userId)) {
                        if ($userdata = User::where('id',$userId)->first()) {
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Booking';
                            $notificationData->title = 'Booking '.str_replace('-',' ',$request->value).'.';
                            // $notificationData->title = str_replace('-',' ',$request->value);
                            $notificationData->message = 'Your booking has been '.$request->value.' - '.$data->booking_id;
                            $notificationData->user_id = $userId;
                            $notificationData->property_id = $data->property_id;
                            $notificationData->order_id = $data->id;
                            
                            $notificationData->save();
                            send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }
                    }
                }
                if($request->value == 'Completed-Booking'){
                    $new_loyalty_amount = isset($data) && !empty($data->new_loyalty_amount) ? $data->new_loyalty_amount : '';

                    if (isset($new_loyalty_amount) && !empty($new_loyalty_amount)) {
                        // $loyalty_point = $data->total_booking_amount * $loyalty_percentage / 100;
                        // $loyalty_point_round = intval(round( $loyalty_point ));
                        $loyalty_point_round = $new_loyalty_amount;

                        // dd($userId, $booking_data->id, $loyalty_point_round);
                        $loyalty = new UserLoyaltyPoint;
                        $loyalty->user_id = $userId;
                        $loyalty->order_id = $data->id;
                        $loyalty->points = $loyalty_point_round;
                        $loyalty->type = 'Credit';
                        $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                        $loyalty->save();
                    }else{
                        $loyalty_point_round = '';
                    }
                    if (isset($userId)) {
                        if ($userdata = User::where('id',$userId)->first()) {
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Booking';
                            // $notificationData->title = 'Booking '.str_replace('-',' ',$request->value).'.';
                            // $notificationData->message = 'Your booking has been '.$request->value.' - '.$data->booking_id.' and You have got '.$loyalty_point_round.' Loyalty points.';
                            $notificationData->title = str_replace('-',' ',$request->value);
                            $notificationData->message = 'Your booking has been Completed - '.$data->booking_id.' and You have got '.$loyalty_point_round.' Loyalty points.';
                            $notificationData->user_id = $userId;
                            $notificationData->property_id = $data->property_id;
                            $notificationData->order_id = $data->id;
                            
                            $notificationData->save();
                            send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }



                    }

                    if ($userdata = User::where('id',$userId)->first()) {
                        $notificationData = new Notification;
                        $notificationData->user_type = $userdata->user_type;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Booking';
                        $notificationData->title = 'Booking Review';
                        $notificationData->message = 'Please rate on your booking - '.$data->booking_id;
                        $notificationData->user_id = $userId;
                        $notificationData->property_id = $data->property_id;
                        $notificationData->order_id = $data->id;
                        
                        $notificationData->save();
                        send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                    }

                }
            }
            return ['status'=>1,'type'=>'success','message'=>'Booking Status update Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function bookingType(Request $request)
    {
        $id = $request->id;
        try {
            $data = $this->Models->with('getProperty.getPropertyAddress.getPropertyCity')->findOrFail($id);
            $data->booking_type = $request->value;

            $email_id = 0;
            $record = (object)[];
            $userId = $data->guest_id;
            $hostId = $data->host_id;

            $booking_data =  $data;
         
            if(in_array($request->value,['Paid','Confirmed']))
            {
                $userdata = User::where('id',$userId)->first();
                $viewPage = 'emails.booking_confirm';
                
                $email = EmailTemplateLang::where('email_id', 12)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
    
                $subject = $email->subject;  
            
                $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                $description = $email->description;
                $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
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

                $hostdata = User::where('id',$hostId)->first();
                $viewPage = 'emails.booking_confimed_host';
                $userdata = User::where('id',$userId)->first();
                $email = EmailTemplateLang::where('email_id', 29)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
    
                $subject = $email->subject;  
                $subject = str_replace("[PROPERTY_NAME]", $data->getProperty->title, $subject);   
                $description = $email->description;
                $description = str_replace("[PROPERTY_NAME]",$data->getProperty->title, $description);  
                $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                $description = str_replace("[GUEST_NAME]",$userdata->name.' '.$userdata->surname, $description);
                $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
    
    
                $record->description = $description;
    
                $record->footer = $email->footer;
                $record->name = $email->name;
                $record->username = $hostdata->name.' '.$hostdata->surname;
                $record->property_name = $data->getProperty->title;
                $record->subject = $subject;
                $record->user_email = $hostdata->email;
                $record->cardData = $data;
                $record->check_in_url = url('guest-area/checkin/search');

                Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                    $message->to($hostdata->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });


            }



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

    public function check_in_action(Request $request)
    {
        $id = $request->id;
        $check_in_data = BookingCheckIn::where('id',$id)->first();
        if($request->status=='approve')
        {
            $check_in_data->is_approved = 1;
            $check_in_data->save();
            return Redirect::Back()->with('success',"check In requiest is approved");
        }
        else
        {
            $check_in_data->is_approved  = 2;
            $check_in_data->save();
            return Redirect::Back()->with('success',"check In requiest is reject");
        }
    }

    public function exportProperties(Request $request)
    {
        try{
            $input = $request->all();
            // dd($request->all());
            $file_type = $input['file_type'];

            // $path = url('/public').'/';
            // $path = public_path('/uploads').'/';
            $path = storage_path('app').'/';
            if($file_type == 'Excel'){
                $fileName = time().'_booking_properties.xls';
            }else{
                $fileName = time().'_booking_properties.csv';
            }
            Excel::store(new BulkBookingExport($request), $fileName);
     
            $result['status'] = 1;
          
            $result['url'] = url('public/'.$fileName);
        } catch (Exception $e) {
             dd($e);
            $result['status'] = false;
            $result['message'] = 'Something went wrong.';
        }
        return response()->json($result);
    }


}
