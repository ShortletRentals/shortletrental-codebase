<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BookingSearch;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\Area;
use App\Models\Country;
use App\Models\City;
use App\Models\Category;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class BookingSearchController extends Controller
{
    protected  $page = 'booking_search';
    protected  $lang = 'Booking Search';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new BookingSearch();
        $this->sortableColumns = [
            // 0 => 'image',
            0 => 'title',
            1 => 'address',
            2 => 'host',
            3 => 'status',
            4 => 'price',
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
        // dd($request->wantsJson());
       Gate::authorize('Booking-Reserve-section');
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
            $search_city = $request['search_city'] ?? null; 
            $search_area = $request['search_area'] ?? null; 
            $search_accommodation = $request['search_accommodation'] ?? null; 
            $search_building = $request['search_building'] ?? null; 
            $max_guest_capacity = $request['max_guest_capacity'] ?? null; 
            $search_bedroom = $request['search_bedroom'] ?? null; 
            $search_category = $request['search_category'] ?? null; 
            $search_type_list = $request['search_type_list'] ?? null; 
            $sortableColumns = $this->sortableColumns;
            $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
            $querydata = $this->Models->getModel($search, $sortableColumns[$orderby], $order,$status,$start_date,$end_date,$search_city,$search_area,$search_accommodation,$search_building,$max_guest_capacity,$search_bedroom,$search_category,$search_type_list,$user_type);
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
                $addressDetails = PropertyAddress::where('property_id',$value->id)->first();
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
                $row['title'] = isset($value->title)? $value->title:'N/A';
                $new_address = isset($city_name) && !empty($area_name) ? $city_name.' ('.$area_name.')' : '';
                $row['address'] = isset($new_address) && !empty($new_address) ? $new_address : $city_name;
                $row['host'] = isset($host_name)? $host_name:'N/A';
                $row['image'] = "<img src='$value->image'   width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = isset($value->status) && $value->status == 1 ? 'Active' : 'Inactive';
                $row['price'] = isset($value->price) && $value->price ? $value->price : 'N/A';
                // $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                // $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction('property'.'.show',['id'=>$value->id]);
              
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
        if(isset($request->status)){
            $status = $request->status;
        }else{
            $status = null;
        }
        if(isset($request->search_city)){
            $search_city = $request->search_city;
        }else{
            $search_city = null;
        }
        if(isset($request->max_guest_capacity)){
            $max_guest_capacity = $request->max_guest_capacity;
        }else{
            $max_guest_capacity = null;
        }
        if(isset($request->search_bedroom)){
            $search_bedroom = $request->search_bedroom;
        }else{
            $search_bedroom = null;
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
        $all_cities = City::select('id','name')->where(['status'=>1])->get();
        $all_area = Area::select('id','name')->where(['status'=>1])->get();
        $all_categories = Category::select('id','name')->where(['status'=>1])->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'user_type'=>$user_type,'all_categories'=>$all_categories,'status'=>$status,'search_city'=>$search_city,'max_guest_capacity'=>$max_guest_capacity,'search_bedroom'=>$search_bedroom,'search_children'=>$search_children,'all_cities'=>$all_cities,'all_area'=>$all_area];
        // dd($data);
        return view('admin.'.$this->page.'.listing',$data);
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
