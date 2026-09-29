<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingsCommission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Building;
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
use File;
use App\Exports\BulkBookingCommissionExport;

class BookingsCommissionController extends Controller
{
    protected  $page = 'bookings_commission';
    protected  $lang = 'Booking and commission per portal';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new BookingsCommission();
        $this->sortableColumns = [
            0 => 'booking_id',
            1 => 'from_date',
            2 => 'to_date',
            3 => 'building',
            4 => 'accommodation',
            5 => 'customer',
            6 => 'guest',
            7 => 'influencer',
            8 => 'influencer_code',
            9 => 'rental_without_tax',
            10 => 'rental_with_tax',
            11 => 'admin_commission',
            12 => 'influencer_commission',
            13 => 'caution_fee',
            14 => 'total_amount',
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
            // $booking_status = $request['booking_status'] ?? null;
            $date_of = $request['date_of'] ?? null ;
            $start_date = $request['start_date'] ?? null ;
            $end_date = $request['end_date'] ?? null; 
            $accommodation = $request['accommodation'] ?? null; 
            $search_building = $request['search_building'] ?? null; 
            $booking_type = $request['booking_type'] ?? null;
            $booking_status = $request['booking_status'] ?? null;
            $search_influencer = $request['search_influencer'] ?? null;
            $search_customer = $request['search_customer'] ?? null;
            $search_host = $request['search_host'] ?? null;
            $filter_by = $request['filter_by'] ?? null;
            $filter_by_date = $request['filter_by_date'] ?? null;
            $sortableColumns = $this->sortableColumns;
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$accommodation,$search_building,$booking_status,$booking_type,$date_of,$start_date,$end_date,$user_type,$search_influencer,$search_customer,$search_host,$filter_by,$filter_by_date);
            // dd($querydata->get());
            // $querydata = $this->Models->getModel($search, 'created_at', $order,$booking_status,$start_date,$end_date);
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
                $customerData = User::where(['user_type'=>4, 'id' => $value->guest_id])->first();
                if(isset($customerData)){
                    $customer = $customerData->name;
                    $unique_id = $customerData->unique_id;
                    $guest_mobile = $customerData->mobile;
                }else{
                    $customer = '';
                    $unique_id = '';
                    $guest_mobile = '';
                }
                if(isset($value->guest_first_name) && !empty($value->guest_first_name)){
                    $guest_name = isset($value->guest_first_name) && !empty($value->guest_first_name) ? $value->guest_first_name.' '.$value->guest_last_name : '';
                }else{
                    $guest_name = $customer;
                }
                $hostData = User::where(['user_type'=>5, 'id' => $value->host_id])->first();
                if(isset($hostData)){
                    $host_name = $hostData->name;
                    $host_mobile = $hostData->mobile;
                }else{
                    $host_name = '-';
                    $host_mobile = '';
                }
                if(isset($value->influencer_id) && !empty($value->influencer_id)){
                    $influencerData = User::where(['user_type'=>3, 'id' => $value->influencer_id])->first();
                    if(isset($influencerData)){
                        $influencer_name = $influencerData->name;
                        $influencer_code = $influencerData->unique_id;
                    }else{
                        $influencer_name = '-';
                        $influencer_code = '-';
                    }
                }else{
                    $influencer_name = '-';
                    $influencer_code = '-';
                }
                // dd($value->property_id);
                $property = Property::where(['id' => $value->property_id])->first();
                if(isset($property)){
                    $property_name = isset($property->title) && !empty($property->title) ? $property->title : '';
                    if(isset($property->building)){
                        $buildingData = Building::where('id',$property->building)->first();
                        if(isset($buildingData) && !empty($buildingData)){
                            $building = isset($buildingData->name) && !empty($buildingData->name) ? $buildingData->name : '';
                        }else{
                            $building = '-';
                        }
                    }else{
                        $building = '-';
                    }
                }else{
                    $property_name = '-';
                    $building = '-';
                }
                if(isset($value) && !empty($value->from_date)){
                    $from_date = isset($value) && !empty($value->from_date) ? date('d M Y', strtotime($value->from_date)) :'';
                    $to_date = isset($value) && !empty($value->to_date) ? date('d M Y', strtotime($value->to_date)) :'';
                    $stay_date = $from_date.' - '.$to_date;
                }else{
                    $stay_date = '';
                }
                // dd($stay_date);
                $row['id'] = $i;
                $view = viewAction('booking.show',['id'=>$value->id]);
                $row['booking_id'] = '<a href="'.url('admin/booking/show?id='.$value->id).'" target="_blank">'.$value->booking_id.'</a>';
                // $row['booking_id'] = isset($value->booking_id) && !empty($value->booking_id) ? $value->booking_id : '-';
                $row['from_date'] = isset($value->from_date) && !empty($value->from_date) ? date('d/m/Y', strtotime($value->from_date)) : '-';
                $row['to_date'] = isset($value->to_date) && !empty($value->to_date) ? date('d/m/Y', strtotime($value->to_date)) : '-';
                $row['building'] = $building;
                $row['accommodation'] = $property_name;
                $row['customer'] = $customer;
                $row['guest'] = $guest_name;
                $row['influencer'] = $influencer_name;
                $row['influencer_code'] = $influencer_code;
                if(isset($value->tax_amount) && !empty($value->tax_amount)){
                    $rental_without_tax = $value->host_amount - $value->tax_amount;
                }else{
                    $rental_without_tax = $value->host_amount;
                }
                $row['rental_without_tax'] = $rental_without_tax;
                $row['rental_with_tax'] = isset($value->host_amount) && !empty($value->host_amount) ? $value->host_amount : '-';;
                $row['admin_commission'] = isset($value->admin_amount) && !empty($value->admin_amount) ? $value->admin_amount : '-';;
                $row['influencer_commission'] = isset($value->influencer_amount) && !empty($value->influencer_amount) ? $value->influencer_amount : '-';;
                $row['caution_fee'] = isset($value->security_deposite) && !empty($value->security_deposite) ? $value->security_deposite : '-';;
                $row['total_amount'] = $value->total_amount + $value->optional_service_amount;
                
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
        if(isset($request->date_of)){
            $date_of = $request->date_of;
        }else{
            $date_of = null;
        }
        if(isset($request->accommodation)){
            $accommodation = $request->accommodation;
        }else{
            $accommodation = null;
        }
        if(isset($request->search_building)){
            $search_building = $request->search_building;
        }else{
            $search_building = null;
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
        if(isset($request->filter_by)){
            $filter_by = $request->filter_by;
        }else{
            $filter_by = null;
        }
        $all_influencers = User::select('id','name','email')->where(['status'=>1,'user_type'=>3])->get();
        $all_customers = User::select('id','name','email')->where(['status'=>1,'user_type'=>4])->get();
        $all_host = User::select('id','name','email')->where(['status'=>1,'user_type'=>5])->get();
        $all_buildings = Building::select('id','name')->where(['status'=>1])->get();
        // dd($booking_status, $booking_type);
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'date_of'=>$date_of,'accommodation'=>$accommodation,'search_building'=>$search_building,'booking_status'=>$booking_status,'booking_type'=>$booking_type,'all_buildings'=>$all_buildings,'all_influencers'=>$all_influencers,'all_customers'=>$all_customers,'all_host'=>$all_host];
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
        Excel::store(new BulkBookingCommissionExport($request), $fileName);
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
        dd('inn');
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
        dd('inn');
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
        //
        Gate::authorize('Booking-delete');
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
