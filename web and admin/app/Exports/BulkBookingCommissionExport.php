<?php
namespace App\Exports;
use App\Models\Orders;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Building;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Area;
use App\Models\User;
use App\Models\PropertyAmenity;
use App\Models\PropertyExtraService;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\Amenity;
use App\Models\ExtraService;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;
use Illuminate\Http\Request;

class BulkBookingCommissionExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        // dd($request->filter_by);
        $this->file_type = $request->file_type;

        $this->date_of = $request->date_of??null;
        $this->start_date = $request->start_date??null;
        $this->end_date = $request->end_date??null;
        $this->accommodation = $request->accommodation??null;
        $this->search_building = $request->search_building??null;
        $this->booking_status = $request->booking_status??null;
        $this->booking_type = $request->booking_type??null;
        // dd($request->filter_by);
        $this->filter_by = $request->filter_by??null;
        $this->filter_by_date = $request->filter_by_date??null;
        $this->search_influencer = $request->search_influencer??null;
        $this->search_customer = $request->search_customer??null;
        $this->search_host = $request->search_host??null;
        // dd($this->filter_by);
    }

    public function headings(): array
    {
        return [
            'Booking Reference',
            'Check-in date',
            'Check-out date',
            'Building',
            'Accommodation',
            'Customer',
            'Guest',
            'Influencer',
            'Influencer Reference Number',
            'Rental without VAT',
            'Rental with VAT',
            'Influencer Commission',
            'Booking Amount',
            // 'Created-At',
        ];
    }

    public function query()
    {
        // dd($request->all());
        // dd(auth()->id());
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
        // $q = Property::query()->select('properties.*','property_address.property_id as main_property_id','property_address.city_id as main_city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id')->with('getPropertyAddress','getProPropertyBathroom','getPropertyBedding','getPropertyBedroom','getPropertyKitchen','getPropertyHouserule')->orderBy('properties.id','desc');
        $q = Booking::query()->select('bookings.*','properties.title','properties.building')->leftjoin('properties','properties.id','bookings.property_id');
        if($user_type == 5){
            $q->where('properties.host',auth()->id());
        }else{
            $q->where('properties.host_property_status','Accept');
        }
        // dd($request->filter_by);
        if(isset($this->search_building) && !empty($this->search_building))
        {
        $q->where('properties.building',$this->search_building);
        }
        if(isset($this->search_influencer) && !empty($this->search_influencer))
        {
          $q->where('bookings.influencer_id',$this->search_influencer);
        }
        if(isset($this->search_customer) && !empty($this->search_customer))
        {
          $q->where('bookings.guest_id',$this->search_customer);
        }
        if(isset($this->search_host) && !empty($this->search_host))
        {
          $q->where('bookings.host_id',$this->search_host);
        }
        if(isset($this->accommodation) && !empty($this->accommodation))
        {
        $q->where('properties.title', 'LIKE', '%' . $this->accommodation . '%');
        }
        if(isset($this->booking_type) && in_array($this->booking_type,['Pre-booking','Confirmed','Information_Request','Owner_Booking','Not_Available','Paid']))
        {
        $q->where('bookings.booking_type',$this->booking_type);
        }
        if(isset($this->booking_status) && in_array($this->booking_status,['Not-confirmed-by-Host','Confirmed-by-Host','Ongoing-Booking','Completed-Booking','Cancelled-Booking']))
        {
        $q->where('bookings.booking_status',$this->booking_status);
        }

        if(isset($this->date_of) && isset($this->date_of)){
            if(isset($this->start_date) && isset($this->end_date))
            {
                $q->whereDate('bookings.'.$this->date_of,'>=',$this->start_date)->whereDate('bookings.'.$this->date_of,'<=',$this->end_date);
            }
        }
        // dd($this->filter_by);
        if(isset($this->filter_by_date) && !empty($this->filter_by_date))
        {
          if($this->filter_by == 'year'){
            $last_date = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $this->filter_by_date ) ) . "-12 month" ) );
            $q->whereDate('bookings.from_date','>=',$last_date)->whereDate('bookings.from_date','<=',$this->filter_by_date);
            // $q->whereBetween('bookings.from_date',[Carbon::now()->subMonth(12), Carbon::now()]);
          }else if($this->filter_by == 'month'){
            $last_date = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $this->filter_by_date ) ) . "-1 month" ) );
            $q->whereDate('bookings.from_date','>=',$last_date)->whereDate('bookings.from_date','<=',$this->filter_by_date);
            // $q->whereBetween('bookings.from_date',[Carbon::now()->subMonth(1), Carbon::now()]);
          }else{
            $q->where('from_date', '=', $this->filter_by_date.' 00:00:00');
            // $q->where('from_date', '=', date('Y-m-d').' 00:00:00');
          }
        }
        
        return $q;
    }
    public function map($value): array
    {
        // echo '<pre>'; print_r($bulk);
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
            }
        }else{
            $property_name = '-';
            $building = '-';
        }
        // dd($country_name);
        if(isset($value->tax_amount) && !empty($value->tax_amount)){
            $rental_without_tax = $value->host_amount - $value->tax_amount;
        }else{
            $rental_without_tax = $value->host_amount;
        }
        return [

            $value->booking_id,
            isset($value->from_date) && !empty($value->from_date) ? date('d/m/Y', strtotime($value->from_date)) : '-',
            isset($value->to_date) && !empty($value->to_date) ? date('d/m/Y', strtotime($value->to_date)) : '-',
            $building,
            $property_name,
            $customer,
            $guest_name,
            $influencer_name,
            $influencer_code,
            $rental_without_tax,
            isset($value->host_amount) && !empty($value->host_amount) ? $value->host_amount : '-',
            isset($value->influencer_amount) && !empty($value->influencer_amount) ? $value->influencer_amount : '-',
            isset($value->total_amount)? $value->total_amount:'N/A',
            // date('d M Y', strtotime($bulk->created_at)),
        ];
    }

}
