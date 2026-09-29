<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Category;
use App\Models\Province;
use App\Models\Country;
use App\Models\Booking;
use App\Models\City;
use App\Models\Area;
use App\Models\Amenity;
use App\Models\ExtraService;
use App\Models\PropertyImage;
use App\Models\PropertyCategory;
use App\Models\PropertyAmenity;
use App\Models\PropertyExtraService;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\PropertyTag;
use App\Models\PropertyHouserule;
use App\Models\Building;
use App\Models\Rating;
use App\Models\PropertyBlockDate;
use App\Models\EmailTemplateLang;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Storage;
use Response;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception,Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use File;
use App\Http\Controllers\import;
use App\Exports\BulkWarehouseExport;
use DateTime;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PropertyController extends Controller
{
    protected  $page = 'property';
    protected  $lang = 'Accommodations';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Property();

        // $this->Models = new Area();
        $this->Models_sev = new Country;
        $this->Models_pro = new Province();
        $this->Models_city = new City;

        $this->sortableColumns = [
            0 => 'image',
            1 => 'code',
            2 => 'title',
            // 3 => 'featured',
            // 4 => 'free_cancellation',
            3 => 'address',
            4 => 'price',
            5 => 'max_guest',
            6 => 'beds',
            7 => 'no_of_bedrooms',
            8 => 'bathrooms',
            9 => 'host',
            10 => 'status',
            11 => 'book_type',
            13 => 'created_at',
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
        Gate::authorize('Property-section');
        $user_type = '';

        if (auth()->user()) {
            $user_type = auth()->user()->user_type;
        }

        $from_date =Null;
        $to_date = Null;
        if($request->booking_type=='upcoming' && $request->days=='next_7_days')
        {
            $from_date = Carbon::now()->format('Y-m-d');
            $to_date = Carbon::now()->addDays(7)->format('Y-m-d');
            
        }
        if($request->booking_type=='upcoming' && $request->days=='next_30_days')
        {
            $from_date = Carbon::now()->format('Y-m-d');
            $to_date = Carbon::now()->addDays(30)->format('Y-m-d');
        }
        if ($request->wantsJson()) {
            // dd($request->all());
            $limit = $request->input('length');
            $start = $request->input('start');
            $search = $request['search']['value'];
            $orderby = $request['order']['0']['column'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $status = $request['status'] ?? null;
            $start_date = $request['start_date'] ?? null ;
            $end_date = $request['end_date'] ?? null; 
            $search_city = $request['search_city'] ?? null; 
            $search_area = $request['search_area'] ?? null; 
            $search_accommodation = $request['search_accommodation'] ?? null; 
            $search_building = $request['search_building'] ?? null; 
            $max_guest_capacity = $request['max_guest_capacity'] ?? null; 
            $search_bedroom = $request['search_bedroom'] ?? null; 
            $search_category = $request['search_category'] ?? null; 
            $price = $request['price'] ?? null; 
            // dd($price);
            $search_type_list = $request['search_type_list'] ?? null; 
            $search_book_type = $request['search_book_type'] ?? null; 
            $host = $request['host'] ?? null; 
            // $type = $request['type'] ?? null; 
            // $city = $request['city'] ?? null; 
            $sortableColumns = $this->sortableColumns;
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date,$host,$search_city,$search_area,$max_guest_capacity,$search_bedroom,$search_category,$price,$search_type_list,$search_book_type);
            // $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date, $host, $type, $city);
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
            // dd($data);
            foreach ($data as $value) {
                $propertyPrice = DB::table('properties')->where(['id'=>$value->id])->pluck('price')->first();
                $bathrooms = 'N/A';
                $beds = 'N/A';

                if (isset($value->getProPropertyBathroom[0])) {
                    $bathrooms = $value->getProPropertyBathroom[0]->bathroom_with_bathtub + $value->getProPropertyBathroom[0]->bathroom_with_shower + $value->getProPropertyBathroom[0]->toilets;
                }

                if (isset($value->getPropertyBedroom[0])) {
                    $beds = $value->getPropertyBedroom[0]->no_of_bunk_bed + $value->getPropertyBedroom[0]->no_of_double_bed + $value->getPropertyBedroom[0]->no_of_double_sofa_bed + $value->getPropertyBedroom[0]->no_of_extra_bed + $value->getPropertyBedroom[0]->no_of_kingsize_bed + $value->getPropertyBedroom[0]->no_of_qweensize_bed + $value->getPropertyBedroom[0]->no_of_single_bed + $value->getPropertyBedroom[0]->no_of_single_sofa_bed;
                }
                $addressDetails = PropertyAddress::where('property_id',$value->id)->first();
                // dd($addressDetails);
                $city_id = isset($addressDetails) && !empty($addressDetails->city_id) ? $addressDetails->city_id : '';
                $area = isset($addressDetails) && !empty($addressDetails->area) ? $addressDetails->area : '';
                if(isset($city_id) && !empty($city_id)){
                    $city_name = City::where('id',$city_id)->pluck('name')->first();
                }else{
                    $city_name = '';
                }
                if(isset($area) && !empty($area)){
                    $area_name = Area::where('id',$area)->pluck('name')->first();
                }else{
                    $area_name = '';
                }
                $user_host = User::where('id',$value->host)->first();
                $host_name = isset($user_host) ? $user_host->name : '';
                $row['id'] = $i;
                $row['code'] = $value->code;
                // $row['title'] = isset($value->title)? $value->title:'N/A';
                $row['title'] = '<a href="'.url('admin/property/show?id='.$value->id).'" target="_blank">'.$value->title.'</a>';
                $row['featured'] = $value->featured;
                $row['free_cancellation'] = $value->free_cancellation;
                $row['price'] = isset($propertyPrice)? number_format($propertyPrice, 2):'N/A';
                $row['occupants'] = isset($value->max_guest)? $value->max_guest:'N/A';
                $row['bedrooms'] = isset($value->no_of_bedrooms)? $value->no_of_bedrooms:'N/A';
                $row['beds'] = $beds;
                $row['bathrooms'] = $bathrooms;
                $new_address = isset($city_name) && !empty($area_name) ? $city_name.' ('.$area_name.')' : '';
                $row['address'] = isset($new_address) && !empty($new_address) ? $new_address : $city_name;
                $row['host'] = isset($host_name)? $host_name:'N/A';
                $row['image'] = "<a href='".url('admin/property/show?id='.$value->id)."' target='_blank'><img src='$value->image'   width='40' height='40'></a>";
                if($value->book_type == 'Reserve'){
                    $book_type = 'checked';
                }else{
                    $book_type = '';
                
                }
                if ($user_type == 5) {
                
                    $row['book_type'] = ucwords(str_replace('_',' ',$value->book_type)); 
                }
                else {
                    $row['book_type'] = '<div class="form-check-danger form-check form-switch"><input class="form-check-input flexSwitchCheckCheckedDanger" type="checkbox" id="'.$value->id.'" '.$book_type.'></div>';
                }


                $total_property = Property::count();
                $row['Position'] = '<select class="form-control changePosition" data-id="'.$value->id.'">';
                
                $row['Position'] .= '<option value="0">Select Position</option>';

                for($i=1; $i<=$total_property; $i++)
                {

                    $selected = '';
                    if($i == $value->position)
                    {
                        $selected = 'Selected';
                    }

                    $row['Position'] .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                }
                
                $row['Position'] .= '</select>';



                // $row['image'] = "<img src='$value->image'   width='40' height='40'>";
                $row['created_at'] = date('d M Y', strtotime($value->created_at));

                if ($user_type == 5) {
                    $row['status'] = $value->status == 1 ? 'Active' : 'Inactive';
                } else {
                    $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
                }
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                // $delete = deleteAction($this->page.'.delete',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                // $delete = '<a href="'.url('property/delete/short?id='.$value->id).'" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
                $addImage = '<a href="#" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs images_btn ms-3"><span class="bx bx-image"></span></a>';
                
                if (!auth()->user()->can('Property-edit')) {
                    $edit = '';
                    $addImage = '';
                }
                if (!auth()->user()->can('Property-delete')) {
                    $delete = '';
                }
                $calendar = viewCalendar($this->page.'.calendar',['id'=>$value->id]);
                if (!auth()->user()->can('Property-calendar')) {
                    $calendar = '';
                }
              
                $row['actions']=createAction($edit.$view.$delete.$addImage.$calendar);
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

        $host_users = User::select('id','name','surname')->where(['status'=>1,'user_type'=>5])->get();
        
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
        if(isset($request->max_guest_capacity)){
            $max_guest_capacity = $request->max_guest_capacity;
        }else{
            $max_guest_capacity = null;
        }
        if(isset($request->search_children)){
            $search_children = $request->search_children;
        }else{
            $search_children = null;
        }
        if(isset($request->search_type_list)){
            $search_type_list = $request->search_type_list;
        }else{
            $search_type_list = null;
        }
        if(isset($request->search_book_type)){
            $search_book_type = $request->search_book_type;
        }else{
            $search_book_type = null;
        }
        $all_cities = City::select('id','name')->where(['status'=>1])->get();
        $all_area = Area::select('id','name')->where(['status'=>1])->get();
        $all_categories = Category::select('id','name')->where(['status'=>1])->get();
        // dd($host_users);
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'status'=>$status, 'host_users'=>$host_users,'search_city'=>$search_city,'search_area'=>$search_area,'max_guest_capacity'=>$max_guest_capacity,'search_children'=>$search_children,'all_cities'=>$all_cities,'all_area'=>$all_area,'all_categories'=>$all_categories, 'search_book_type' => $search_book_type,'to_date'=>$to_date,'from_date'=>$from_date];
        return view('admin.'.$this->page.'.listing',$data);
    }

    public function change_posiation(Request $request)
    {   
        $posiation = $request->posiation;
        $id = $request->id;


        //dd($posiation,$id);
        $rev = PropertyImage::where('id',$id)->where('position',$posiation)->first();
        $rev1 = PropertyImage::where('id',$posiation)->where('position',$id)->first();
        if(isset($rev) && isset($rev1))
        {
            PropertyImage::where('id',$id)->update(['position'=>$id]);
            PropertyImage::where('id',$posiation)->update(['position'=>$posiation]);
    
        }
        else
        {
            $pos = PropertyImage::where('id',$posiation)->first();
            $pos1 = PropertyImage::where('id',$id)->first();      

            PropertyImage::where('id',$posiation)->update(['position'=>$pos1->position]);
            PropertyImage::where('id',$id)->update(['position'=>$pos->position]);
    
        }

       
        /*dd($old);
        if(isset($old))
        {
            /*PropertyImage::where('id',$id)->update(['position'=>$posiation]);
            $old->position = $old->id;
            $old->save();*/

        /*}
        else{
           
    
        }*/

//        $preProperty1->save();

        
/*
        $preProperty2 = PropertyImage::where('id',$request->propertyid)->where('position',$request->id)->first();
        if(($preProperty2->id-10000)== $request->posiation)
        {
            $preProperty2->id =$request->id;
            PropertyImage::where('id',$request->propertyid)->where('position',$request->id)->update(['id'=>$request->id]);    
        }
        else
        {
            PropertyImage::where('id',$request->propertyid)->where('position',$request->id)->update(['id'=>$request->posiation]);
        }

        $preProperty3 = PropertyImage::where('id',$request->propertyid)->where('position',$request->posiation)->first();  
        if(($preProperty3->id-10000)== $request->id)
        {
            PropertyImage::where('id',$request->propertyid)->where('position',$request->posiation)->update(['id'=>$request->posiation]);
        }
        else
        {
            PropertyImage::where('id',$request->propertyid)->where('position',$request->posiation)->update(['id'=>$request->id]);    
        }*/

       
        return json_encode(array('status'=>true));
    }

    public function duplicate(Request $request){
        // dd($request->all());
        $id = $request->id;
        Gate::authorize('Property-edit');
        try {
            // $input = $this->Models->findOrFail($id);
            $input = DB::table('properties')->where('id',$id )->first();
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $old_property_id = Property::where('id',$id)->pluck('id')->first();
            $last_property_id = Property::orderBy('id','desc')->pluck('id')->first();

            $position = $last_property_id + 1;
            $data = new Property;
            // dd($input->image);
            if(isset($input->image) && $input->image ){
              //  dd($input->image);
              //  $image1 = explode("property/",$input->image);
                $data->image =$input->image;
            }
            // $code = $this->generateRandomString();
            $code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
            $data->code = $code;
            // $data->reference = $input->reference??'';
            $data->title = $input->title;
            $data->type = $input->type;
            // $data->category = $input->category;
            $data->host = $input->host ?? auth()->id();
            $data->featured = $input->featured ?? null;
            $data->free_cancellation = $input->free_cancellation ?? null;
            $data->additional_notes = $input->additional_notes ?? null;
            $data->description = $input->description ?? null;
            $data->booking_condition = $input->booking_condition ?? null;
            $data->cancellation_policy = $input->cancellation_policy ?? null;
            $data->note = $input->note ?? null;
            $data->max_guest = $input->max_guest;
            $data->price = $input->price;
            $data->tax = $input->tax ?? null;
            $data->building = $input->building ?? null;
            $data->minimum_no_of_nights = $input->minimum_no_of_nights ?? null;
            $data->security_deposit_amount = $input->security_deposit_amount ?? null;
            $data->video_url = $input->video_url ?? null;
            $data->book_type = $input->book_type ?? null;
            $data->cctv = $input->cctv ?? null;
            $data->cctv_locations = $input->cctv_locations ?? null;
            $data->location_of_television = $input->location_of_television ?? null;
            $data->pets_allow = $input->pets_allow ?? null;
            $data->response_time = $input->response_time ?? null;
            $data->party_rate_commission = $input->party_rate_commission ?? null;
            $data->standout_amenities = $input->standout_amenities ?? null;
            $data->allow_a_day_booking = $input->allow_a_day_booking ?? null;
            $data->created_by = $input->created_by ?? null;
            $data->check_in_from_time = $input->check_in_from_time ?? null;
            $data->check_in_to_time = $input->check_in_to_time ?? null;
            $data->check_out_time = $input->check_out_time ?? null;
            $data->apartment_responsible = $input->apartment_responsible ?? null;
            $data->other_responsibility = $input->other_responsibility ?? null;
            $data->estate_located = $input->estate_located ?? null;
            $data->estate_name = $input->estate_name ?? null;
            $data->landmark = $input->landmark ?? null;
            $data->tarred_located = $input->tarred_located ?? null;
            $data->tarred_road = $input->tarred_road ?? null;
            $data->home_support = $input->home_support ?? null;
            $data->people_allowed_parties = $input->people_allowed_parties ?? null;

            $data->swimming_pool = $input->swimming_pool;
            $data->pool_opening_period = $input->pool_opening_period;
            $data->pool_closing_period = $input->pool_closing_period;
            $data->heated_swimming_pool = $input->heated_swimming_pool;
            $data->heated_pool_opening_period = $input->heated_pool_opening_period;
            $data->heated_pool_closing_period = $input->heated_pool_closing_period;

            $data->address = $input->address;
            $data->latitude = $input->latitude;
            $data->longitude = $input->longitude;
            
            $data->status = $input->status;
            $data->clean_status = $input->clean_status ?? null;
            $data->host_property_status = 'Accept';
            $data->position = $position;

            $data->building = $input->building ?? null;
            $data->featured = $input->featured ?? null;
            $data->luxury = $input->luxury ?? null;
            $data->rare = $input->rare ?? null;
            
            if($user_type == 5){
                $data->created_by = 'Host';
            }else{
                $data->created_by = 'Admin';
            }

            if($data->save()){
                $property_id = $data->id;
                $propertyCategory = PropertyCategory::where('property_id',$old_property_id)->pluck('category_id')->toArray();
                if(isset($propertyCategory) && !empty($propertyCategory)){
                    foreach($propertyCategory as $cat){
                        $data_category = new PropertyCategory;
                        $data_category->property_id = $property_id;
                        $data_category->category_id = $cat;
                        $data_category->save();
                    }
                }
                $propertyAddress = PropertyAddress::where('property_id',$old_property_id)->first();
                if(isset($propertyAddress) && !empty($propertyAddress)){                    
                    $data_address = new PropertyAddress;
                    $data_address->property_id = $property_id;
                    $data_address->address = $propertyAddress['address'];
                    $data_address->country_id = $propertyAddress['country_id'];
                    $data_address->province_id = $propertyAddress['province_id'];
                    $data_address->city_id = $propertyAddress['city_id'];
                    $data_address->area = $propertyAddress['area'];
                    $data_address->postal_code = $propertyAddress['postal_code']??null;

                    $data_address->street_name = $propertyAddress['street_name'];
                    $data_address->street_type = $propertyAddress['street_type']??null;
                    $data_address->street_number = $propertyAddress['street_number']??null;
                    $data_address->house_number = $propertyAddress['house_number'];
                    $data_address->floor = $propertyAddress['floor'];
                    $data_address->staircase = $propertyAddress['staircase']??null;
                    $data_address->elevator = $propertyAddress['elevator']??0;
                    $data_address->apartment_door_no = $propertyAddress['apartment_door_no'];
                    $data_address->save();
                }
                $propertyBedroom = PropertyBedroom::where('property_id',$old_property_id)->first();
                if(isset($propertyBedroom) && !empty($propertyBedroom)){
                    $data_bedrooms = new PropertyBedroom;
                    $data_bedrooms->property_id = $property_id;
                    $data_bedrooms->no_of_bedrooms = $propertyBedroom['no_of_bedrooms'];
                    $data_bedrooms->communal_zones = $propertyBedroom['communal_zones'];
                    $data_bedrooms->no_of_bunk_bed = $propertyBedroom['no_of_bunk_bed'];
                    $data_bedrooms->no_of_double_bed = $propertyBedroom['no_of_double_bed'];
                    $data_bedrooms->no_of_double_sofa_bed = $propertyBedroom['no_of_double_sofa_bed'];
                    $data_bedrooms->no_of_extra_bed = $propertyBedroom['no_of_extra_bed'];
                    $data_bedrooms->no_of_kingsize_bed = $propertyBedroom['no_of_kingsize_bed'];
                    $data_bedrooms->no_of_qweensize_bed = $propertyBedroom['no_of_qweensize_bed'];
                    $data_bedrooms->no_of_single_bed = $propertyBedroom['no_of_single_bed'];
                    $data_bedrooms->no_of_single_sofa_bed = $propertyBedroom['no_of_single_sofa_bed'];
                    $data_bedrooms->save();
                }
                $propertyBathroom = PropertyBathroom::where('property_id',$old_property_id)->first();
                if(isset($propertyBathroom) && !empty($propertyBathroom)){
                    $data_bathrooms = new PropertyBathroom;
                    $data_bathrooms->property_id = $property_id;
                    $data_bathrooms->bathroom_with_bathtub = $propertyBathroom['bathroom_with_bathtub'];
                    $data_bathrooms->bathroom_with_shower = $propertyBathroom['bathroom_with_shower'];
                    $data_bathrooms->toilets = $propertyBathroom['toilets'];
                    $data_bathrooms->sauna = $propertyBathroom['sauna']??0;
                    $data_bathrooms->jacuzzi = $propertyBathroom['jacuzzi']??0;
                    $data_bathrooms->hair_dryer = $propertyBathroom['hair_dryer']??0;
                    $data_bathrooms->towels = $propertyBathroom['towels'];
                    $data_bathrooms->towel_change = $propertyBathroom['towel_change']??0;
                    $data_bathrooms->towel_change_frequency = $propertyBathroom['towel_change_frequency'];
                    $data_bathrooms->save();
                }
                $propertyKitchen = PropertyKitchen::where('property_id',$old_property_id)->first();
                if(isset($propertyKitchen) && !empty($propertyKitchen)){
                    $data_kitchen = new PropertyKitchen;
                    $data_kitchen->property_id = $property_id;
                    $data_kitchen->no_of_kitchens = $propertyKitchen['no_of_kitchens'];
                    $data_kitchen->kitchen_type = $propertyKitchen['kitchen_type'];
                    $data_kitchen->kitchen_category = $propertyKitchen['kitchen_category'];
                    $data_kitchen->kitchen_amenities = $propertyKitchen['kitchen_amenities'];
                    $data_kitchen->save();
                }
                $propertyBedding = PropertyBedding::where('property_id',$old_property_id)->first();
                if(isset($propertyBedding) && !empty($propertyBedding)){
                    $data_bedding = new PropertyBedding;
                    $data_bedding->property_id = $property_id;
                    $data_bedding->bed_linen = $propertyBedding['bed_linen'];
                    $data_bedding->bed_linen_change = $propertyBedding['bed_linen_change']??0;
                    $data_bedding->bed_Change_frequency = $propertyBedding['bed_Change_frequency'];
                    $data_bedding->washing_machine = $propertyBedding['washing_machine']??0;
                    $data_bedding->dryer = $propertyBedding['dryer']??0;
                    $data_bedding->iron = $propertyBedding['iron']??0;
                    $data_bedding->television = $propertyBedding['television']??0;
                    $data_bedding->no_of_television = $propertyBedding['no_of_television'];
                    $data_bedding->fans = $propertyBedding['fans'];
                    $data_bedding->satellite_tv = $propertyBedding['satellite_tv']??0;
                    $data_bedding->radio = $propertyBedding['radio']??0;
                    $data_bedding->dvd_player = $propertyBedding['dvd_player']??0;
                    $data_bedding->satellite_tv_language = $propertyBedding['satellite_tv_language'];
                    $data_bedding->mosquito_netting = $propertyBedding['mosquito_netting']??0;
                    $data_bedding->electronic_mosquito_repellents = $propertyBedding['electronic_mosquito_repellents']??0;
                    $data_bedding->internet_access = $propertyBedding['internet_access'];
                    $data_bedding->network_name = $propertyBedding['network_name'];
                    $data_bedding->password = $propertyBedding['password'];
                    $data_bedding->safe = $propertyBedding['safe']??0;
                    $data_bedding->mini_bar = $propertyBedding['mini_bar']??0;
                    $data_bedding->key_code_number = $propertyBedding['key_code_number'];
                    $data_bedding->save();
                }
                $propertyAmenity = PropertyAmenity::where('property_id',$old_property_id)->get();
                if(isset($propertyAmenity) && !empty($propertyAmenity)){
                    foreach($propertyAmenity as $amenity){
                        $data_emenity = new PropertyAmenity;
                        $data_emenity->property_id = $property_id;
                        $data_emenity->amenities_id = $amenity->amenities_id;
                        $data_emenity->save();
                    }
                }
                $propertyExtraService = PropertyExtraService::where('property_id',$old_property_id)->get();
                if(isset($propertyExtraService) && !empty($propertyExtraService)){
                    foreach($propertyExtraService as $service){
                        $data_service = new PropertyExtraService;
                        $data_service->property_id = $property_id;
                        $data_service->service_id = $service->service_id;
                        $data_service->save();
                    }
                }
                $propertyHouserule = PropertyHouserule::where('property_id',$old_property_id)->get();
                if(isset($propertyHouserule) && !empty($propertyHouserule)){
                    foreach($propertyHouserule as $house){
                        $data_house = new PropertyHouserule;
                        $data_house->property_id = $property_id;
                        $data_house->name = $house->name;
                        $data_house->save();
                    }
                }
                $propertyImages = PropertyImage::where('property_id',$old_property_id)->get();
                if(isset($propertyImages) && !empty($propertyImages)){
                    foreach($propertyImages as $image1){
                        $image2 = explode("property/",$image1->image);
    
                        $data_image = new PropertyImage;
                        $data_image->property_id = $property_id;
                        $data_image->image = $image2[1];
                        $data_image->image_type = 'IMAGE';
                        $data_image->save();
                        $data_image->position =    $data_image->id;
                        $data_image->save();
                    }
                }
            }
            return ['status'=>1,'type'=>'success','message'=>'Duplicate Accommodation created Successfully.'];
        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function change_book_type(Request $request){
        $input = $request->all();
        // dd($input);
        $id = $input['id'];
        $status = $input['value'];
        $details = Property::find($id);
        if (!empty($details)) {
            $inp = ['book_type' => $status];
            // dd($inp);
            // $User = Property::findOrFail($id);
            // $User = Property::where('id',$id)->update($inp);
            if (Property::where('id',$id)->update($inp)) {
                $result['message'] = __("Accomodation booking type is ").str_replace("_"," ",$status);
                $result['status'] = 1;
            } else {
                $result['message'] = __("Booking type can't update");
                $result['status'] = 0;
            }
        } else {
            $result['message'] = __("backend.Invaild_user");
            $result['status'] = 0;
        }
        return response()->json($result);
    }

    public function getLatLongByCountry(request $request){
        $input = $request->all();
        // dd($input['country_name']);
        $address = $input['address'];
        if(isset($address) && !empty($address)){
            $latlong = getLatLongByCountry($address);
            // dd($latlong);
            if(isset($latlong) && !empty($latlong)){
                // dd($latlong);
                return $latlong;
            }
        }
        return '';
    }

    public function exportProperties(Request $request)
    {
        // dd('inn');
        $input = $request->all();
        // dd($request->all());
        $file_type = $input['file_type'];

        // $path = url('/public').'/';
        // $path = public_path('/uploads').'/';
        $path = storage_path('app').'/';
        if($file_type == 'Excel'){
            $fileName = time().'_properties.xls';
        }else{
            $fileName = time().'_properties.csv';
        }
        Excel::store(new BulkWarehouseExport($request), $fileName);
        // File::move(storage_path('app/'.$fileName), public_path($fileName));

        $path1 =  public_path('storage') .'/'. 'property/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path1 . $newFolder;
        // dd($folderPath);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true);
        }
        File::move(storage_path('app/'.$fileName), $folderPath.$fileName);

        $result['status'] = 1;
        $result['url'] = url('public/storage/property/'.$newFolder.$fileName);
        // $result['url'] = $path.$fileName;
        return response()->json($result);
    }

    public function imageView(Request $request)
    {
        // dd($request->all());
        $id = $request->segment(4);
        // dd($id);
        $data['property'] = Property::findOrFail($id);
        $data['propertyImage'] = PropertyImage::select('image','id','image_type','property_id','position')->where('property_id',$data['property']->id)->orderBy('position','asc')->get();
        // dd($data['propertyImage']);
        // dd($data['productImage'][0]->image);        
        return response()->json($data);;
    }

    public function addMoreImages(Request $request, $id){
        $data['property'] = Property::findOrFail($id);
        if ($request->file('addMoremultipalImage')) {
            $i = 1;

            foreach ($request->file("addMoremultipalImage") as $key => $file) {
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'property/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
             
                $modelProductImages = new PropertyImage();
                $modelProductImages->image = $result['file'];
                $modelProductImages->image_type = $result['type'];


                $i++;
                $modelProductImages->property_id = $data['property']->id;
                $modelProductImages->save();
                $modelProductImages->position =    $modelProductImages->id;
                $modelProductImages->save();
            }
            $data['propertyImage'] = PropertyImage::select('image','id','image_type')->where('property_id',$data['property']->id)->get();
            if($data['property']->id){
                $result['message'] = 'Property Images added successfully';
                $result['status'] = 1;

                $result['data'] = $data;
            }else{
                $result['message'] = 'Property can`t be Added!!';
                $result['status'] = 0;
            } 
        }else{
            $result['message'] = 'At least One Image is  required!!';
            $result['status'] = 0;
        }
        
        return response()->json($result);
    }

    public function propertyImagesDelete(Request $request)
    {
        $id = $request->segment(4);
        $details = PropertyImage::find($id); 
        if(!empty($details)){ 
            if(PropertyImage::findOrFail($id)->delete()){
                $result['message'] = 'Property Image deleted successfully';
                $result['status'] = 1;
            }else{
                $result['message'] = 'Property Image can`t be deleted!!';
                $result['status'] = 0;
            }
        }else{
            $result['message'] = 'Invaild Images!!';
            $result['status'] = 0;
        }
        return response()->json($result);;
    }

    public function frontend($slug = null)
    {
        // dd($slug);
        Gate::authorize('Property-section');
        $user = User::where('user_type', '2')->get();
        $roles = Role::all();
        dd($roles);
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
        Gate::authorize('Property-create');
        $amenities = Amenity::where('status',1)->get();
//        $extra_services = ExtraService::where('status',1)->get();
        $extra_services = ExtraService::where('status',1)->where(function($q){            
            $q->where('user_id',Auth::user()->id);
            if(Auth::user()->user_type==1)
            {
                $q->orWhere('user_id',1)->orWhere('user_id',0);            
            }
        })->get();


        $selected_extra_services = [];
        $host_users = User::where(['status'=>1,'user_type'=>5])->get();
        $buildings = Building::where(['status'=>1])->get();
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $all_categories = Category::where('status',1)->get();
        $tags = PropertyTag::where('status',1)->get();
        $property_count1 = Property::count();
        $property_count = $property_count1 + 1;
        // dd($property_count);
        $selected_tags = [];
        $data= ['title'=>$this->lang,'page'=>$this->page,'amenities'=>$amenities,'host_users'=>$host_users,'all_categories'=>$all_categories,'country'=>$country,'province'=>$province,'tags'=>$tags,'selected_tags'=>$selected_tags, 'buildings'=>$buildings, 'extra_services'=>$extra_services, 'property_count'=>$property_count];
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
        $message = [];
        $validation = [
            'title' => 'required',
            // 'featured' => 'required',
            // 'free_cancellation' => 'required',
            // 'position' => 'required|unique:properties,position',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            // dd($input);
            // dd(auth()->id());
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $data = new Property;

            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'property/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            // $code = $this->generateRandomString();
            $code = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
            $commission = AdminSettings::pluck('commission')->first();
            if(isset($commission) && !empty($commission)){
                $price1 = $input['price'] * $commission / 100;
                $updated_price = $price1 + $input['price'];
                if(isset($input['tax']) && $input['tax'] ){
                    $price1 = $updated_price * $input['tax'] / 100;
                    $updated_price = $price1 + $updated_price;
                }
            }else{
                if(isset($input['tax']) && $input['tax'] ){
                    $price1 = $input['price'] * $input['tax'] / 100;
                    $updated_price = $price1 + $input['price'];
                }
            }
            $data->code = $code;
            $data->title = $input['title'];
            $data->type = $input['type'];
            $data->host = $input['host']??auth()->id();
            // $data->free_cancellation = $input['free_cancellation'];
            $data->additional_notes = $input['additional_notes'];
            $data->description = $input['description'] ?? null;
            $data->booking_condition = $input['booking_condition'] ?? null;
            $data->cancellation_policy = $input['cancellation_policy'] ?? null;
            $data->note = $input['note']??'';
            $data->max_guest = $input['max_guest'];
            $data->price = $input['price'];
            $data->updated_price = $updated_price;
            $data->tax = $input['tax'] ?? null;
            // dd($input);
            if(isset($input['building']) && $input['building'] ){
                $data->building = $input['building'] ?? null;
            }
            if(isset($input['featured']) && $input['featured'] ){
                $data->featured = $input['featured'] ?? null;
            }
            
            if(isset($input['luxury']) && $input['luxury'] ){
                $data->luxury = $input['luxury'] ?? null;
            }

            if(isset($input['rare']) && $input['rare'] ){
                $data->rare = $input['rare'] ?? null;
            }

            $data->minimum_no_of_nights = $input['minimum_no_of_nights'];
            $data->security_deposit_amount = $input['security_deposit_amount'];
            $data->video_url = $input['video_url']??null;
            $data->book_type = $input['book_type']??null;

            $data->swimming_pool = $input['swimming_pool'];
            $data->pool_opening_period = $input['pool_opening_period'];
            $data->pool_closing_period = $input['pool_closing_period'];
            $data->heated_swimming_pool = $input['heated_swimming_pool'];
            $data->heated_pool_opening_period = $input['heated_pool_opening_period'];
            $data->heated_pool_closing_period = $input['heated_pool_closing_period'];
            $data->check_in_from_time = $input['check_in_from_time'];
            $data->check_in_to_time = $input['check_in_to_time'];
            $data->check_out_time = $input['check_out_time'];

            $data->refund = $input['refund'];
            $data->pets_allow = $input['pets_allow'];
            $data->cctv = $input['cctv'];
            $data->cctv_locations = $input['cctv_locations'];
            $data->response_time = $input['response_time']??null;
            $data->location_of_television = $input['location_of_television'];
            $data->standout_amenities = $input['standout_amenities']??null;
            // $data->information_correct_or_not = $input['information_correct_or_not'];

            $data->address = $input['address'];
            $data->latitude = $input['latitude'];
            $data->longitude = $input['longitude'];
            
            $data->status = $input['status']??0;
            if(isset($input['clean_status']) && $input['clean_status'] ){
                $data->clean_status = $input['clean_status'];
            }
            $data->host_property_status = 'Accept';

            
            if(isset($input['position']) && $input['position'] ){
                $data->position = $input['position']??0;
            }
            else{

            }
            if($user_type == 5){
                $data->created_by = 'Host';
            }else{
                $data->created_by = 'Admin';
            }

            if($data->save()){

                if(isset($input['position']) && $input['position'] ){
                    $data->position = $input['position']??0;
                }
                else{
                    $data->position = $data->id;
                    $data->save();
                }


                
                $property_id = $data->id;
                if(isset($input['category']) && !empty($input['category'])){
                    foreach($input['category'] as $cat){
                        $data_category = new PropertyCategory;
                        $data_category->property_id = $property_id;
                        $data_category->category_id = $cat;
                        $data_category->save();
                    }
                }
                if(isset($input['city_id']) && !empty($input['city_id'])){
                    $data_address = new PropertyAddress;

                    $area_name = Area::where('id',$input['area'])->pluck('name')->first();
                    $city_name = City::where('id',$input['city_id'])->pluck('name')->first();
                    $province_name = Province::where('id',$input['province_id'])->pluck('name')->first();
                    $country_name = Country::where('id',$input['country_id'])->pluck('name')->first();
                    $address = $area_name ?? ''.', '. $city_name.', '. $province_name.', '. $country_name;

                    $data_address->property_id = $property_id;
                    $data_address->address = $address;
                    $data_address->country_id = $input['country_id'];
                    $data_address->province_id = $input['province_id'];
                    $data_address->city_id = $input['city_id'];
                    $data_address->area = $input['area'];
                    $data_address->postal_code = $input['postal_code']??null;

                    $data_address->street_name = $input['street_name'];
                    $data_address->street_type = $input['street_type']??null;
                    $data_address->street_number = $input['street_number']??null;
                    $data_address->house_number = $input['house_number'];
                    $data_address->floor = $input['floor'];
                    $data_address->staircase = $input['staircase']??null;
                    $data_address->elevator = $input['elevator']??0;
                    $data_address->apartment_door_no = $input['apartment_door_no'];
                    $data_address->save();
                }
                if(isset($input['no_of_bedrooms'])){
                    $data_bedrooms = new PropertyBedroom;
                    $data_bedrooms->property_id = $property_id;
                    $data_bedrooms->no_of_bedrooms = $input['no_of_bedrooms'];
                    if(isset($input['communal_zones']) && count($input['communal_zones']) > 0){
                        $communal_zones1 = implode(',',$input['communal_zones']);
                        $communal_zones = rtrim($communal_zones1, ',');
                    }else{
                        $communal_zones1 = null;
                        $communal_zones = null;
                    }
                    $data_bedrooms->communal_zones = $communal_zones;
                    $data_bedrooms->no_of_bunk_bed = $input['no_of_bunk_bed'];
                    $data_bedrooms->no_of_double_bed = $input['no_of_double_bed'];
                    $data_bedrooms->no_of_double_sofa_bed = $input['no_of_double_sofa_bed'];
                    $data_bedrooms->no_of_extra_bed = $input['no_of_extra_bed'];
                    $data_bedrooms->no_of_kingsize_bed = $input['no_of_kingsize_bed'];
                    $data_bedrooms->no_of_qweensize_bed = $input['no_of_qweensize_bed'];
                    $data_bedrooms->no_of_single_bed = $input['no_of_single_bed'];
                    $data_bedrooms->no_of_single_sofa_bed = $input['no_of_single_sofa_bed'];
                    $data_bedrooms->save();
                }
                
                $data_bathrooms = new PropertyBathroom;
                $data_bathrooms->property_id = $property_id;
                $data_bathrooms->bathroom_with_bathtub = $input['bathroom_with_bathtub'];
                $data_bathrooms->bathroom_with_shower = $input['bathroom_with_shower'];
                $data_bathrooms->toilets = $input['toilets'];
                $data_bathrooms->sauna = $input['sauna']??0;
                $data_bathrooms->jacuzzi = $input['jacuzzi']??0;
                $data_bathrooms->hair_dryer = $input['hair_dryer']??0;
                $data_bathrooms->towels = $input['towels'];
                $data_bathrooms->towel_change = $input['towel_change']??0;
                $data_bathrooms->towel_change_frequency = $input['towel_change_frequency'];
                $data_bathrooms->save();

                /*$data_kitchen = new PropertyKitchen;
                $data_kitchen->property_id = $property_id;
                $data_kitchen->no_of_kitchens = $input['no_of_kitchens'];
                $data_kitchen->kitchen_type = $input['kitchen_type'];
                $data_kitchen->kitchen_category = $input['kitchen_category'];
                if(isset($input['kitchen_amenities']) && count($input['kitchen_amenities']) > 0){
                    $kitchen_amenities1 = implode(',',$input['kitchen_amenities']);
                    $kitchen_amenities = rtrim($communal_zones1, ',');
                }else{
                    $kitchen_amenities = null;
                }
                $data_kitchen->kitchen_amenities = $kitchen_amenities;
                $data_kitchen->save();
*/
                if(isset($input['no_of_kitchens'])){
                    PropertyKitchen::where('property_id',$property_id)->delete();
                    $data_kitchen = new PropertyKitchen;
                    $data_kitchen->property_id = $property_id;
                    $data_kitchen->no_of_kitchens = $input['no_of_kitchens'];
                    $data_kitchen->kitchen_type = $input['kitchen_type'];
                    $data_kitchen->kitchen_category = $input['kitchen_category'];

                    if(isset($input['kitchen_amenities']) && count($input['kitchen_amenities']) > 0){
                        $kitchen_amenities = implode(',',$input['kitchen_amenities']);
                        // $kitchen_amenities = rtrim($communal_zones1, ',');
                    }else{
                        $kitchen_amenities = null;
                    }
                    // dd($kitchen_amenities);
                    $data_kitchen->kitchen_amenities = $kitchen_amenities;
                    $data_kitchen->save();
                }



                $data_bedding = new PropertyBedding;
                $data_bedding->property_id = $property_id;
                $data_bedding->bed_linen = $input['bed_linen'];
                $data_bedding->bed_linen_change = $input['bed_linen_change']??0;
                $data_bedding->bed_Change_frequency = $input['bed_Change_frequency'];
                $data_bedding->washing_machine = $input['washing_machine']??0;
                $data_bedding->dryer = $input['dryer']??0;
                $data_bedding->iron = $input['iron']??0;
                $data_bedding->television = $input['television']??0;
                $data_bedding->no_of_television = $input['no_of_television'];
                $data_bedding->fans = $input['fans'];
                $data_bedding->satellite_tv = $input['satellite_tv']??0;
                $data_bedding->radio = $input['radio']??0;
                $data_bedding->dvd_player = $input['dvd_player']??0;
                if(isset($input['satellite_tv_language']) && count($input['satellite_tv_language']) > 0){
                    $satellite_tv_language1 = implode(',',$input['satellite_tv_language']);
                    $satellite_tv_language = rtrim($satellite_tv_language1, ',');
                }else{
                    $satellite_tv_language = null;
                }
                $data_bedding->satellite_tv_language = $satellite_tv_language;
                $data_bedding->mosquito_netting = $input['mosquito_netting']??0;
                $data_bedding->electronic_mosquito_repellents = $input['electronic_mosquito_repellents']??0;
                $data_bedding->internet_access = $input['internet_access'];
                $data_bedding->network_name = $input['network_name'];
                $data_bedding->password = $input['password'];
                $data_bedding->safe = $input['safe']??0;
                $data_bedding->mini_bar = $input['mini_bar']??0;
                $data_bedding->key_code_number = $input['key_code_number'];
                $data_bedding->save();

                foreach($input['property_amenities'] as $amenity){
                    $data_emenity = new PropertyAmenity;
                    $data_emenity->property_id = $property_id;
                    $data_emenity->amenities_id = $amenity;
                    $data_emenity->save();
                }
                if(isset($input['property_extra_services']) && !empty($input['property_extra_services'])){
                    foreach($input['property_extra_services'] as $service){
                        $data_service = new PropertyExtraService;
                        $data_service->property_id = $property_id;
                        $data_service->service_id = $service;
                        $data_service->save();
                    }
                }
                if(isset($input['house_rule']) && !empty($input['house_rule'][0])){
                    foreach($input['house_rule'] as $house){
                        $data_house = new PropertyHouserule;
                        $data_house->property_id = $property_id;
                        $data_house->name = $house;
                        $data_house->save();
                    }
                }else{
                   // PropertyHouserule::where('property_id',$id)->delete();
                }
            }
            // dd($data);
            return Redirect::route('admin.'.$this->page.'.index')->with('success','Record added successfully');
        } catch (Exception $e) {
            dd($e);
            return customeRedirect('admin.'.$this->page.'.index','','error',$e);                       
        }
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
        // dd($id);
        $data = $this->Models->with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding')->where('id',$id)->first();
        if(isset($data) && !empty($data->host)){
            $host_name = User::where(['id'=>$data->host])->pluck('name')->first();
        }else{
            $host_name = '';
        }
        if(isset($data) && !empty($data->building)){
            $building_name = Building::where(['id'=>$data->building])->pluck('name')->first();
        }else{
            $building_name = '';
        }
        $propertyImages = PropertyImage::where('property_id',$id)->get();
        // dd($category_name);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'host_name'=>$host_name, 'building_name'=>$building_name,'propertyImages'=>$propertyImages];
        return view('admin.'.$this->page.'.show',$data);
    }

    public function view_calendar(Request $request) {
        $input = $request->all();
        $data = [
            'id'=>$input['id'],
        ];
        return view('admin.'.$this->page.'.calendar',$data);
    }

    public function load_calendar(Request $request)
    {

        Gate::authorize('Property-calendar');
        $input = $request->all();
        $range = 6;
        $page = $input['page'] ?? 1;
        $start_range = (($range*$page)-6);
        $end_range = $range*$page;

        $start_date = date('Y-m-01',strtotime('+'.$start_range.'month',strtotime(date('Y-m-d'))));;
       // dd($start_date);
        $end_date =date('Y-m-t',strtotime('+'.$end_range.'month',strtotime(date('Y-m-d'))));
        $allDates = [];

        $alreadybooking = Booking::where('property_id', $input['id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '>=', $start_date)->whereDate('to_date', '<=', $end_date)
            ->pluck('from_date','to_date')->toArray();

        //$alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', 1)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();


        $mo = [];
        for($i=0; $i<6;$i++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
            $end_block_date =date('Y-m-t',strtotime('+'.$i.'month',strtotime($start_date)));

            $getThisMonth = date('m',strtotime($start_date_new));
            $allDates = array();


            if ($alreadybooking) {

                foreach ($alreadybooking as $key => $value) {
                    $getstarMonth = date('m',strtotime($value));
                    $getendMonth = date('m',strtotime($key));
                    if(($getstarMonth == $getendMonth) && ($getThisMonth == $getstarMonth))
                    {
                        
                        $period = CarbonPeriod::create($value, $key);
                        foreach ($period as $date) {
                            $allDates[] = $date->format('d');
                        }
                    }
                    else
                    {

                        $period = CarbonPeriod::create($value, $key);
                        foreach ($period as $date) {
                            if($getThisMonth == $date->format('m'))
                            {
                                $allDates[] = $date->format('d');
                            }

                        }

                    }

                }
                $allDates = array_unique($allDates);

            }

            $mo[$i] = implode(',', $allDates);
        }



        $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', 1)->where('added_by_sub', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();


        $admin_month = [];
        for($i=0; $i<6;$i++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$i.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$i.'month',strtotime($end_date)));
            $end_block_date =date('Y-m-t',strtotime('+'.$i.'month',strtotime($start_date)));

            $getThisMonth = date('m',strtotime($start_date_new));
            $allDates = array();


           
            
            if ($alreadyBlockedDate) {

                foreach ($alreadyBlockedDate as $k => $v) {
                    $getBlockstarMonth = date('m',strtotime($v));
                    $getblockEndMonth = date('m',strtotime($k));
                    $date = date('d', strtotime($v));

                    if (($getBlockstarMonth) && ($getThisMonth == $getBlockstarMonth)) {
                        $allDates[] = $date;

                    } else {

                        if ($getThisMonth == $getBlockstarMonth) {
                            $allDates[] = $date;
                        }
                    }

                }
                $allDates = array_unique($allDates);

            }

            $admin_month[$i] = implode(',', $allDates);
        }




        $host_month = [];
        $alreadyHostBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by', '!=', 1)->where('added_by_sub', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
        for($j=0; $j<6;$j++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
            $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

            $getThisMonth = date('m',strtotime($start_date_new));
            $allDates = array();

            if ($alreadyHostBlockedDate) {

                foreach ($alreadyHostBlockedDate as $k => $v) {
                    $getBlockstarMonth = date('m',strtotime($v));
                    $getblockEndMonth = date('m',strtotime($k));
                    $date = date('d', strtotime($v));

                    if (($getBlockstarMonth) && ($getThisMonth == $getBlockstarMonth)) {
                        $allDates[] = $date;

                    } else {

                        if ($getThisMonth == $getBlockstarMonth) {
                            $allDates[] = $date;
                        }
                    }

                }
                $allDates = array_unique($allDates);

            }

            $host_month[$j] = implode(',', $allDates);
        }

        $sub_month = [];
        $alreadySubBlockedDate = PropertyBlockDate::where('property_id', $input['id'])->where('added_by_sub', '!=', 0)->where('added_by', 0)->whereBetween('block_date', [$start_date, $end_date])->pluck('block_date')->toArray();
        for($j=0; $j<6;$j++)
        {
            $allDates = [];
            $start_date_new = date('Y-m-01',strtotime('+'.$j.'month',strtotime($start_date)));
            $end_date_new =date('Y-m-t',strtotime('+'.$j.'month',strtotime($end_date)));
            $end_block_date =date('Y-m-t',strtotime('+'.$j.'month',strtotime($start_date)));

            $getThisMonth = date('m',strtotime($start_date_new));
            $allDates = array();

            if ($alreadySubBlockedDate) {

                foreach ($alreadySubBlockedDate as $k => $v) {
                    $getBlockstarMonth = date('m',strtotime($v));
                    $getblockEndMonth = date('m',strtotime($k));
                    $date = date('d', strtotime($v));

                    if (($getBlockstarMonth) && ($getThisMonth == $getBlockstarMonth)) {
                        $allDates[] = $date;

                    } else {

                        if ($getThisMonth == $getBlockstarMonth) {
                            $allDates[] = $date;
                        }
                    }

                }
                $allDates = array_unique($allDates);

            }

            $sub_month[$j] = implode(',', $allDates);
        }

        $start_date1 = explode('-',$start_date);
        $end_date1 = explode('-',$end_date);

        $data = [
            'start_date'=> $start_date,
            'start_day'=>$start_date1[2],
            'start_month'=>$start_date1[1],
            'start_year'=>$start_date1[0],
            'end_date'=>$end_date,
            'end_day'=>$end_date1[2],
            'end_month'=>$end_date1[1],
            'end_year'=>$end_date1[0],          
            'monthWiseData'=>$mo,
            'hostMonthWiseData'=>$host_month,
            'adminMonthWiseData'=>$admin_month,
            'subAdminMonthWiseData'=>$sub_month, 
            'id'=>$input['id'],
        ];

         //dd($data);

        return view('admin.'.$this->page.'.booking-calender',$data);
    }

    public function checkBlockDate(Request $request)
    {
        $id = $request->id;
        $date = date('Y-m-d', strtotime($request->date));

        try {
            $checkExist = PropertyBlockDate::where('property_id', $id)->whereDate('block_date', $date)->first();
            // dd($checkExist);
            // $alreadybooking = Booking::where('property_id', $id)->whereDate('from_date', '<=', $date)->whereDate('to_date', '>=', $date)->first();

            if ($checkExist != null) {
                // dd('inn');
                // if ($checkExist) {
                //     $checkExist->delete();
                //     return ['status'=>0,'type'=>'danger','message'=>'This date is now unblocked.'];

                // } else {
                //     $insertData = new PropertyBlockDate;
                //     $insertData->property_id = $id;
                //     $insertData->block_date = $date;
                    
                //     if ($insertData->save()) {
                //         return ['status'=>1,'type'=>'success','message'=>'Date is blocked successfully.'];

                //     } else {
                        return ['status'=>1,'type'=>'success','message'=>'Are you sure want to un-block '.$date.' date?'];
                    // }
                // }

            } else {
                // return ['status'=>0,'type'=>'danger','message'=>'This date has already booked.'];
                return ['status'=>1,'type'=>'success','message'=>'Are you sure want to block '.$date.' date?'];
            }

        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function blockDate(Request $request)
    {
        $id = $request->id;
        $date = date('Y-m-d', strtotime($request->date));

        try {
            $checkExist = PropertyBlockDate::where('property_id', $id)->whereDate('block_date', $date)->first();
            $alreadybooking = Booking::where('property_id', $id)->where('booking_status', '!=', 'Cancelled-Booking')->whereDate('from_date', '<=', $date)->whereDate('to_date', '>=', $date)->first();

            if (!$alreadybooking) {

                if ($checkExist) {
                    $checkExist->delete();
                    return ['status'=>0,'type'=>'danger','message'=>'This date is now unblocked.'];

                } else {
                    $insertData = new PropertyBlockDate;
                    $insertData->property_id = $id;
                    $insertData->block_date = $date;
                    if(auth()->user()->user_type==2)
                    {
                        $insertData->added_by_sub = auth()->id();
                        $insertData->added_by = 0;
                    }
                    else{
                        $insertData->added_by = auth()->id();
                        $insertData->added_by_sub = 0;
                    }

                    
                    if ($insertData->save()) {
                        return ['status'=>1,'type'=>'success','message'=>'Date is blocked successfully.'];

                    } else {
                        return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
                    }
                }

            } else {
                return ['status'=>0,'type'=>'danger','message'=>'This date has already booked.'];
            }

        } catch (ModelNotFoundException $e) {
            return ['status'=>0,'type'=>'danger','message'=>'Something went wrong.'];
        }
    }

    public function showTags()
    {
        dd('inn');
        $tags = PropertyTag::select('id','tag')->where(['status'=>1])->get()->toArray();
        // $tags = PropertyTag::select('id','tag')->where(['status'=>1])->groupBy('tag')->get()->toArray();
        // dd($tags);
        $result = json_encode($tags);
        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        Gate::authorize('Property-edit');
        $id = $request->id;
        $data = $this->Models->with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding','getPropertyHouserule')->find($id);
        // dd($data);
        $amenities = Amenity::where('status',1)->get();
        $extra_services = ExtraService::where('status',1)->where(function($q){            
            $q->where('user_id',Auth::user()->id);
            if(Auth::user()->user_type==1)
            {
                $q->orWhere('user_id',1)->orWhere('user_id',0);            
            }
        })->get();
        $selected_amenities = PropertyAmenity::where('property_id',$id)->pluck('amenities_id')->toArray();
        $selected_extra_services = PropertyExtraService::where('property_id',$id)->pluck('service_id')->toArray();
        $selected_category = PropertyCategory::where('property_id',$id)->pluck('category_id')->toArray();
        $host_users = User::where(['status'=>1,'user_type'=>5])->get();
        $all_categories = Category::where('status',1)->get();
        $country = $this->Models_sev->orderBy('position','desc')->get();
        $province = $this->Models_pro->orderBy('name','asc')->get();
        $buildings = Building::where(['status'=>1])->get();
        $tags = PropertyTag::where('status',1)->get();
        $selected_tags = [];
        $property_count = Property::count();
        // dd($property_count);
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'amenities'=>$amenities, 'selected_amenities' => $selected_amenities, 'host_users'=>$host_users,'all_categories'=>$all_categories,'country'=>$country,'province'=>$province,'tags'=>$tags,'selected_tags'=>$selected_tags, 'buildings'=>$buildings, 'extra_services'=>$extra_services, 'selected_extra_services' => $selected_extra_services, 'selected_category'=>$selected_category, 'property_count'=>$property_count];
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
        Gate::authorize('Property-edit');
        $id = $request->id;
        $this->validate($request, [
            'title' => 'required',
            // 'host' => 'required',
            // 'featured' => 'required',
            // 'free_cancellation' => 'required',
            // 'building' => 'required',
            // 'position' => 'required',
            // 'position' => 'required|unique:properties,position',
        ]);

        $input = $request->all();
        // dd($input);
        $fail = false;

        if (!$fail) {


            $file = $request->file('image');
            // $file_contract = $request->file('contract');
            // dd($input);
            try {
                $old_property = DB::table('properties')->where('id',$id )->first();
                // dd($old_property->video_url);
                // dd($user_type);
                $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
           
                if($user_type == 1 || $user_type == 2 ){
                    //update position of others
                    DB::table('properties')->where('position','>=', $input['position'] ?? 0)->update(['position' => DB::raw('position + 1')]);

                    if(isset($input['position']) && $input['position'] ){
                        $position = $input['position'];
                    }else{
                        $position = $old_property->position;
                    }
                    if(isset($input['clean_status']) && $input['clean_status'] ){
                        $clean_status = $input['clean_status'];
                    }else{
                        $clean_status = $old_property->clean_status;
                    }
                    if(isset($input['featured']) && $input['featured'] ){
                        $featured = $input['featured'];
                    }else{
                        $featured = $old_property->featured;
                    }
                    if(isset($input['luxury']) && $input['luxury'] ){
                        $luxury = $input['luxury'];
                    }else{
                        $luxury = $old_property->luxury;
                    }

                    if(isset($input['rare']) && $input['rare'] ){
                        $rare = $input['rare'];
                    }else{
                        $rare = $old_property->rare;
                    }


                    if(isset($input['building']) && $input['building'] ){
                        $building = $input['building'];
                    }else{
                        $building = $old_property->building;
                    }
                    if(isset($input['tax']) && $input['tax'] ){
                        $tax = $input['tax'];
                    }else{
                        $tax = $old_property->tax;
                    }
                    $video_url = $input['video_url'];
                }else{
                    // dd('inn');
                    $position = $old_property->position;
                    $clean_status = $old_property->clean_status;
                    $featured = $old_property->featured;
                    $luxury = $old_property->luxury;
                    $rare = $old_property->rare;
                    $building = $old_property->building;
                    $tax = $old_property->tax;
                    $video_url = $input['video_url'];
                }
                // dd('inn');
                $commission = AdminSettings::pluck('commission')->first();
                if(isset($commission) && !empty($commission)){
                    $price1 = $input['price'] * $commission / 100;
                    $updated_price = $price1 + $input['price'];
                    if(isset($input['tax']) && $input['tax'] ){
                        $price1 = $updated_price * $input['tax'] / 100;
                        $updated_price = $price1 + $updated_price;
                    }
                }else{
                    if(isset($input['tax']) && $input['tax'] ){
                        $price1 = $input['price'] * $input['tax'] / 100;
                        $updated_price = $price1 + $input['price'];
                    }
                }
               

                $datas =  [
                    // 'reference' => $input['reference'],
                    'title' => $input['title'],
                    'type' => $input['type'],
                    'host' => $input['host']??auth()->id(),
                    'featured' => $featured,
                    'luxury' => $luxury,
                    'rare' => $rare,
                    // 'free_cancellation' => $input['free_cancellation'],
                    'additional_notes' => $input['additional_notes'],
                    'description' => $input['description'] ?? null,
                    'booking_condition' => $input['booking_condition'] ?? null,
                    'cancellation_policy' => $input['cancellation_policy'] ?? null,
                    'note' => $input['note']??null,
                    'max_guest' => $input['max_guest'],
                    'status' => $input['status']??0,
                    'clean_status' => $clean_status,
                    'price' => $input['price'],
                    'updated_price' => $updated_price,
                    'tax' => $tax,
                    'building' => $building,
                    'minimum_no_of_nights' => $input['minimum_no_of_nights'],
                    'security_deposit_amount' => $input['security_deposit_amount'],
                    'video_url' => $video_url,
                    'book_type' => $input['book_type']??null,

                    'swimming_pool' => $input['swimming_pool'],
                    'pool_opening_period' => $input['pool_opening_period'],
                    'pool_closing_period' => $input['pool_closing_period'],
                    'heated_swimming_pool' => $input['heated_swimming_pool'],
                    'heated_pool_opening_period' => $input['heated_pool_opening_period'],
                    'heated_pool_closing_period' => $input['heated_pool_closing_period'],

                    'address' => $input['address'],
                    'latitude' => $input['latitude'],
                    'longitude' => $input['longitude'],
                    'position' => $position,
                    'check_in_from_time' => $input['check_in_from_time'],
                    'check_in_to_time' => $input['check_in_to_time'],
                    'check_out_time' => $input['check_out_time'],

                    'pets_allow' => $input['pets_allow'],
                    'cctv' => $input['cctv'],
                    'cctv_locations' => $input['cctv_locations'],
                    'response_time' => $input['response_time'],
                    'location_of_television' => $input['location_of_television'],
                    'standout_amenities' => $input['standout_amenities'],
                    'refund' => $input['refund'],
                ];

                if(isset($file))
                {
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'property/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);
                    $datas['image'] = $result['file'];
                   // dd($datas);
                }

                $data = Property::where('id', $id)->update($datas);

                if(isset($input['category'])){
                    PropertyCategory::where('property_id',$id)->delete();
                    foreach($input['category'] as $cat){
                        $data_category = new PropertyCategory;
                        $data_category->property_id = $id;
                        $data_category->category_id = $cat;
                        $data_category->save();
                    }
                }
                if(isset($input['city_id']) && !empty($input['city_id'])){
                    PropertyAddress::where('property_id',$id)->delete();
                    if (str_contains($input['address'], 'Select Area, ')) { 
                        $address = str_replace('Select Area, ','', $input['address']);
                    }else{
                        $address = $input['address'];
                    }
                    $data_address = new PropertyAddress;
                    $data_address->property_id = $id;
                    $data_address->address = $address;
                    $data_address->country_id = $input['country_id'];
                    $data_address->province_id = $input['province_id'];
                    $data_address->city_id = $input['city_id'];
                    $data_address->area = $input['area']??null;
                    $data_address->postal_code = $input['postal_code']??null;

                    $data_address->street_name = $input['street_name'];
                    $data_address->street_type = $input['street_type']??null;
                    $data_address->street_number = $input['street_number']??null;
                    $data_address->house_number = $input['house_number'] ?? null;
                    $data_address->floor = $input['floor'];
                    $data_address->staircase = $input['staircase']??null;
                    $data_address->elevator = $input['elevator']??0;
                    $data_address->apartment_door_no = $input['apartment_door_no'];
                    $data_address->save();
                }
                if(isset($input['no_of_bedrooms'])){
                    PropertyBedroom::where('property_id',$id)->delete();
                    $data_bedrooms = new PropertyBedroom;
                    $data_bedrooms->property_id = $id;
                    $data_bedrooms->no_of_bedrooms = $input['no_of_bedrooms'];
                    if(isset($input['communal_zones']) && count($input['communal_zones']) > 0){
                        $communal_zones = implode(',',$input['communal_zones']);
                        // $communal_zones = rtrim($communal_zones1, ',');
                    }else{
                        $communal_zones = null;
                    }
                    $data_bedrooms->communal_zones = $communal_zones;
                    $data_bedrooms->no_of_bunk_bed = $input['no_of_bunk_bed'];
                    $data_bedrooms->no_of_double_bed = $input['no_of_double_bed'];
                    $data_bedrooms->no_of_double_sofa_bed = $input['no_of_double_sofa_bed'];
                    $data_bedrooms->no_of_extra_bed = $input['no_of_extra_bed'];
                    $data_bedrooms->no_of_kingsize_bed = $input['no_of_kingsize_bed'];
                    $data_bedrooms->no_of_qweensize_bed = $input['no_of_qweensize_bed'];
                    $data_bedrooms->no_of_single_bed = $input['no_of_single_bed'];
                    $data_bedrooms->no_of_single_sofa_bed = $input['no_of_single_sofa_bed'];
                    $data_bedrooms->save();
                }
                
                if(isset($input['towels'])){
                    PropertyBathroom::where('property_id',$id)->delete();
                    $data_bathrooms = new PropertyBathroom;
                    $data_bathrooms->property_id = $id;
                    $data_bathrooms->bathroom_with_bathtub = $input['bathroom_with_bathtub'];
                    $data_bathrooms->bathroom_with_shower = $input['bathroom_with_shower'];
                    $data_bathrooms->toilets = $input['toilets'];
                    $data_bathrooms->sauna = $input['sauna']??0;
                    $data_bathrooms->jacuzzi = $input['jacuzzi']??0;
                    $data_bathrooms->hair_dryer = $input['hair_dryer']??0;
                    $data_bathrooms->towels = $input['towels'];
                    $data_bathrooms->towel_change = $input['towel_change']??0;
                    $data_bathrooms->towel_change_frequency = $input['towel_change_frequency'];
                    $data_bathrooms->save();
                }

                if(isset($input['no_of_kitchens'])){
                    PropertyKitchen::where('property_id',$id)->delete();
                    $data_kitchen = new PropertyKitchen;
                    $data_kitchen->property_id = $id;
                    $data_kitchen->no_of_kitchens = $input['no_of_kitchens'];
                    $data_kitchen->kitchen_type = $input['kitchen_type'];
                    $data_kitchen->kitchen_category = $input['kitchen_category'];

                    if(isset($input['kitchen_amenities']) && count($input['kitchen_amenities']) > 0){
                        $kitchen_amenities = implode(',',$input['kitchen_amenities']);
                        // $kitchen_amenities = rtrim($communal_zones1, ',');
                    }else{
                        $kitchen_amenities = null;
                    }
                    // dd($kitchen_amenities);
                    $data_kitchen->kitchen_amenities = $kitchen_amenities;
                    $data_kitchen->save();
                }

                if(isset($input['bed_linen'])){
                    PropertyBedding::where('property_id',$id)->delete();
                    $data_bedding = new PropertyBedding;
                    $data_bedding->property_id = $id;
                    $data_bedding->bed_linen = $input['bed_linen'];
                    $data_bedding->bed_linen_change = $input['bed_linen_change']??0;
                    $data_bedding->bed_Change_frequency = $input['bed_Change_frequency'];
                    $data_bedding->washing_machine = $input['washing_machine']??0;
                    $data_bedding->dryer = $input['dryer']??0;
                    $data_bedding->iron = $input['iron']??0;
                    $data_bedding->television = $input['television']??0;
                    $data_bedding->no_of_television = $input['no_of_television'];
                    $data_bedding->fans = $input['fans'];
                    $data_bedding->satellite_tv = $input['satellite_tv']??0;
                    $data_bedding->radio = $input['radio']??0;
                    $data_bedding->dvd_player = $input['dvd_player']??0;
                    if(isset($input['satellite_tv_language']) && count($input['satellite_tv_language']) > 0){
                        $satellite_tv_language = implode(',',$input['satellite_tv_language']);
                        // $satellite_tv_language = rtrim($satellite_tv_language1, ',');
                    }else{
                        $satellite_tv_language = null;
                    }
                    $data_bedding->satellite_tv_language = $satellite_tv_language;
                    $data_bedding->mosquito_netting = $input['mosquito_netting']??0;
                    $data_bedding->electronic_mosquito_repellents = $input['electronic_mosquito_repellents']??0;
                    $data_bedding->internet_access = $input['internet_access'];
                    $data_bedding->network_name = $input['network_name'];
                    $data_bedding->password = $input['password'];
                    $data_bedding->safe = $input['safe']??0;
                    $data_bedding->mini_bar = $input['mini_bar']??0;
                    $data_bedding->key_code_number = $input['key_code_number'];
                    $data_bedding->save();
                }
                if(isset($input['property_amenities'])){
                    PropertyAmenity::where('property_id',$id)->delete();
                    foreach($input['property_amenities'] as $amenity){
                        $data_emenity = new PropertyAmenity;
                        $data_emenity->property_id = $id;
                        $data_emenity->amenities_id = $amenity;
                        $data_emenity->save();
                    }
                }
                if(isset($input['property_extra_services'])){
                    PropertyExtraService::where('property_id',$id)->delete();
                    foreach($input['property_extra_services'] as $service){
                        $data_service = new PropertyExtraService;
                        $data_service->property_id = $id;
                        $data_service->service_id = $service;
                        $data_service->save();
                    }
                }
                if(isset($input['house_rule']) && $input['house_rule'][0] != null){
                    PropertyHouserule::where('property_id',$id)->delete();
                    foreach($input['house_rule'] as $house){
                        $data_house = new PropertyHouserule;
                        $data_house->property_id = $id;
                        $data_house->name = $house;
                        $data_house->save();
                    }
                }
                else{
                    PropertyHouserule::where('property_id',$id)->delete();
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
        // dd($id);
        Gate::authorize('Property-delete');
        // return property::findOrFail($id)->delete();
        if(property::findOrFail($id)->delete()){
            PropertyAddress::where('property_id',$id)->delete();
            PropertyBathroom::where('property_id',$id)->delete();
            PropertyBedding::where('property_id',$id)->delete();
            PropertyBedroom::where('property_id',$id)->delete();
            PropertyKitchen::where('property_id',$id)->delete();
            PropertyImage::where('property_id',$id)->delete();
            PropertyExtraService::where('property_id',$id)->delete();
            PropertyHouserule::where('property_id',$id)->delete();
            Rating::where('property_id',$id)->delete();
            PropertyAmenity::where('property_id',$id)->delete();
            PropertyTag::where('property_id',$id)->delete();
            $response['status'] = true;
            $response['message'] = 'Accommodation delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Accommodation does not delete. ';
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

    public function changeStatus(Request $request)
    {

        $input = $request->all();

        $details = $this->Models::find($input['id']);
        if (!empty($details)) {
            $inp = ['status' => $input['value']];
            if ($details->update($inp)) {
                if ($input['value'] == 1) {

                    $userId= $details->host;                    
                    $userdata = User::where('id',$userId)->first();
                    $viewPage = 'emails.other_template';
                    $record = (object)[];
                    $email = EmailTemplateLang::where('email_id', 36)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $booking_data = $details;
                    $subject = $email->subject;  
                
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->title, $subject);
                    $description = $email->description;
                    $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                    $description = str_replace("[PROPERTY_NAME]", $booking_data->title,  $description);

                    $record->description = $description;    
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $userdata->name.' '.$userdata->surname;
                    $record->property_name = $booking_data->title;
                    $record->subject = $subject;
                    $record->user_email = $userdata->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');

                   
                    Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                        $message->to($userdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });


                   


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

    public function position($id,$position){
        $property = Property::where('position',$position)->where('id','!=',$id)->first();       
        $property1 = Property::where('id',$id)->first();
        if(isset($property))
        {
            if($property->position == $position)
            {
                $property->position = $property1->position;
                $property->save();
                Property::where('id',$id)->update(['position'=>$position]);
            }
            else{
                Property::where('id',$id)->update(['position'=>$position]);
    
            }
        }
        else{

            Property::where('id',$id)->update(['position'=>$position]);
        }
            
            $result['message'] = __("success");
            $result['status'] = 1;
            return response()->json($result);    
       
    }
}
