<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Offer;
use App\Models\OfferAccommodation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\FacilityOwnerExport;
use App\Models\Discount;
use App\Models\Country;
use App\Models\Property;
use App\Models\EmailTemplateLang;
use App\Models\PasswordReset as ModelsPasswordReset;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class OfferController extends Controller
{
    protected  $page = 'offer';
    protected  $lang = 'Offers';
    protected  $table;

    public function __construct() {
        $this->middleware('auth');
        $this->Models = new Offer();
        $this->sortableColumns = [
            0 => 'image',
            1 => 'accomodation',
            2 => 'discount',
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
        Gate::authorize('Transaction-section');
        
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
                if(isset($value) && $value->all_properties == 'No'){
                    $offerAccommodation = OfferAccommodation::where('offer_id',$value->id)->pluck('property_id')->toArray();
                    $all_accommodation = Property::select('title')->whereIn('id', $offerAccommodation)->get();
                    $accommodation = "";
                    foreach ($all_accommodation as $ac_value) {
                        $accommodation != "" && $accommodation .= ", ";
                        $accommodation .= $ac_value->title;
                    }                    
                }else{
                    $accommodation = 'All';
                }
                if(isset($value) && $value->discount != Null){
                    $discount_code = Discount::select('code')->where('id', $value->discount)->pluck('code')->first();
                }else{
                    $discount_code = '-';
                }
                $row['id'] = $i;
                $row['accomodation'] = $accommodation;
                $row['discount'] = $discount_code;
                $row['image'] = "<img src='$value->image' width='40' height='40'> ";              
                $row['created_at'] = date('d M Y', strtotime($value->created_at));
                $row['status'] = statusAction($value->status, $value->id,[0=>'Inactive',1=>'Active'],'statusAction',$this->page.'.status')->toHtml();
               
                $edit = editAction($this->page.'.edit',['id'=>$value->id]);
                $view = viewAction($this->page.'.show',['id'=>$value->id]);
                $delete = '<a href="javascript:void(0)" data-property_id="'.$value->id.'" title="Images Details" class="btn btn-info btn-xs delete_btn ms-3"><span class="bx bx-trash"></span></a>';
              
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
        $properties = Property::where('status',1)->get();
        $discount = Discount::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page, 'properties'=>$properties, 'discount'=>$discount];        
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
        //sent mail to payment user
        $message = [];
        $validation = [
            // 'image' => 'required',
        ];
            
        $this->validate($request,$validation,$message);       
        try{
            $data = new Offer;
            if ($request->file('image')) {
                $file = $request->file('image');
                $newFolder  = strtoupper(date('M') . date('Y'));
                $folderPath	=	'offer/'.$newFolder; 
                $result =  fileUploads('s3',$file,$folderPath,false);
                $data->image = $result['file'];
            }
            if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                $data->all_properties = 'Yes';
            }else{
                $data->all_properties = 'No';
            }
            $data->discount = $input['discount'];
            $data->title = $input['title'] ?? null;
            $data->description = $input['description'] ?? null;
            $data->status = 1;
            if($data->save()){
                if(isset($input['property_id']) && !empty($input['property_id'])){
                    // dd($input['property_id']);
                    foreach($input['property_id'] as $property){
                        $property_name = Property::where('id',$property)->pluck('title')->first();
                        $data_service = new OfferAccommodation;
                        $data_service->offer_id = $data->id;
                        $data_service->property_id = $property;
                        $data_service->property_name = $property_name;
                        $data_service->save();
                    }
                }
            }
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
        $selected_offer_properties = OfferAccommodation::where('offer_id',$id)->pluck('property_id')->toArray();
        if(isset($selected_offer_properties) && count($selected_offer_properties) > 0){
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->whereIn('properties.id',$selected_offer_properties)->get();
        }else{
            $accommodations = Property::select('properties.status','properties.code','properties.title','properties.type','properties.contract','property_address.city_id','cities.name as city_name')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('cities','cities.id','=','property_address.city_id')->get();
        }
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data, 'accommodations'=>$accommodations];
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
        $properties = Property::where('status',1)->get();
        $selected_offer_properties = OfferAccommodation::where('offer_id',$id)->pluck('property_id')->toArray();
        $discount = Discount::where('status',1)->get();
        $data= ['title'=>$this->lang,'page'=>$this->page,'data'=>$data,'discount'=>$discount,'properties'=>$properties,'selected_offer_properties'=>$selected_offer_properties];
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
            // 'discount' => 'required',
        ]);

        $input = $request->all();
        $fail = false;

        if (!$fail) {
            try {
                $file = $request->file('image');
                if(isset($input['all_properties']) && $input['all_properties'] == 'Yes'){
                    $all_properties = 'Yes';
                    OfferAccommodation::where('offer_id',$id)->delete();
                }else{
                    $all_properties = 'No';
                }
                if (isset($file)) {
 
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'offer/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    $data = Offer::where('id', $id)->update(['title' => $input['title'] ?? null,'description' => $input['description'] ?? null,'discount' => $input['discount'],'all_properties'=>$all_properties, 'image' => $result['file'] ]);
                }else{
                    $data = Offer::where('id', $id)->update(['title' => $input['title'] ?? null,'description' => $input['description'] ?? null,'discount' => $input['discount'],'all_properties'=>$all_properties ]);
                }
                if(isset($input['property_id'])){
                    if(!isset($input['all_properties'])){
                        OfferAccommodation::where('offer_id',$id)->delete();
                        foreach($input['property_id'] as $property){
                            $property_name = Property::where('id',$property)->pluck('title')->first();
                            $data_service = new OfferAccommodation;
                            $data_service->offer_id = $id;
                            $data_service->property_id = $property;
                            $data_service->property_name = $property_name;
                            $data_service->save();
                        }
                    }
                }else{
                    OfferAccommodation::where('offer_id',$id)->delete();
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
        Gate::authorize('Transaction-delete');
        if(Offer::findOrFail($id)->delete()){
            OfferAccommodation::where('offer_id',$id)->delete();
            $response['status'] = true;
            $response['message'] = 'Offer delete successfully. ';
        }else{
            $response['status'] = false;
            $response['message'] = 'Offer does not delete. ';
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
        $details = Offer::find($id);
        if (!empty($details)) {
            if ($status == 'active') {
                $inp = ['status' => 1];
            } else {
                $inp = ['status' => 0];
            }
            $User = Offer::findOrFail($id);
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
