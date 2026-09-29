<?php
namespace App\Exports;
use App\Models\Orders;
use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkBookingExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        $this->file_type = $request->file_type;
        // $this->main_category_id = $_GET['main_category_id'] ?? null;
        // $this->main_category_id = $_GET['main_category_id'] ?? null;
        // $this->category_id = $_GET['category_id'] ?? null;
        // $this->subcategory_id = $_GET['subcategory_id'] ?? null;
        // $this->is_product_selected = $_GET['is_product_selected'] ?? null;
    }

    public function headings(): array
    {
        return [
            'Accomodation ID',
            'Accomodation Name',
            'Booking Type',
            'Booking Date',
            'Check-in date',
            'Check-out date',
            'Host Name',
            'Host Email',
            'Host Telephone',
            'Guest Name',
            'Guest Email',
            'Guest Telephone',
            'Number of guest',
            'Nights',
            'Total Amount',
            'Discount Amount',
            'Loyalty Amount',
            'Refund Amount',
            'Host Amount',
            'Admin Amount',
            'Tax Amount',
            'Influencer Amount',
            'Optional Service Amount',
            'Security Deposite',
            'Booking Status',
            'Booking Type',
        ];
    }
    public function query()
    {
        $Product = Booking::query()->select('bookings.*','properties.title','properties.code','properties.type','users.name as host_name')
                ->join('properties','properties.id','=','bookings.property_id')
                //->join('categories', 'categories.id', '=', 'properties.category')
                //->join('building', 'building.id', '=', 'properties.building')
                ->join('users', 'users.id', '=', 'properties.host')
                // ->leftJoin('main_category', 'main_category.id', '=', 'categories.main_category_id')
                // ->groupBy('products.id')
                ->orderBy('properties.id', 'DESC')/*->with('getPropertyAddress','getPropertyBedroom','getProPropertyBathroom','getPropertyKitchen','getPropertyBedding')*/;
               //  $q = Booking::select('bookings.*','properties.id as property_id','properties.code','properties.title','properties.host','properties.type','properties.category','properties.max_guest','property_address.country_id','property_address.province_id','property_address.city_id','property_address.area','property_address.postal_code','property_address.address')->join('properties','properties.id','=','bookings.property_id')->leftjoin('property_address','property_address.property_id','=','properties.id')->where('bookings.influencer_id',auth()->id());
        return $Product;
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($bulk): array
    {
       /* $amenities = PropertyAmenity::where('property_id',$bulk->id)->pluck('amenities_id')->toArray();
        if(isset($amenities) && count($amenities) > 0){
            $amenity_details = Amenity::whereIn('id',$amenities)->pluck('name')->toArray();
            if(isset($amenity_details) && count($amenity_details) > 0){
                $amenity_name = implode (', ', $amenity_details);
            }
        }else{
            $amenity_name = '-';
        }
        $services = PropertyExtraService::where('property_id',$bulk->id)->pluck('service_id')->toArray();
        if(isset($services) && count($services) > 0){
            $services_details = ExtraService::whereIn('id',$services)->pluck('name')->toArray();
            if(isset($services_details) && count($services_details) > 0){
                $service_name = implode (', ', $services_details);
            }
        }else{
            $service_name = '-';
        }
        if(isset($bulk) && !empty($bulk->getPropertyAddress[0]->country_id)){
            $country_name = Country::where('id',$bulk->getPropertyAddress[0]->country_id)->pluck('name')->first();
        }else{
            $country_name = '';
        }
        if(isset($bulk) && !empty($bulk->getPropertyAddress[0]->province_id)){
            $province_name = Province::where('id',$bulk->getPropertyAddress[0]->province_id)->pluck('name')->first();
        }else{
            $province_name = '';
        }
        if(isset($bulk) && !empty($bulk->getPropertyAddress[0]->city_id)){
            $city_name = City::where('id',$bulk->getPropertyAddress[0]->city_id)->pluck('name')->first();
        }else{
            $city_name = '';
        }
        if(isset($bulk) && !empty($bulk->getPropertyAddress[0]->area_id)){
            $area_name = Area::where('id',$bulk->getPropertyAddress[0]->area_id)->pluck('name')->first();
        }else{
            $area_name = '';
        }*/
        // dd($country_name);


        return [
            $bulk->code,
            $bulk->title,
            $bulk->type,
            date('d M Y', strtotime($bulk->created_at)),
            date('d M Y', strtotime($bulk->from_date)),
            date('d M Y', strtotime($bulk->to_date)),
            $bulk->personal_first_name.' '.$bulk->personal_last_name,
            $bulk->personal_email,
            $bulk->personal_country_code.' '.$bulk->personal_phone_number,
            $bulk->personal_first_name.' '.$bulk->personal_last_name,
            $bulk->personal_email,
            $bulk->personal_country_code.' '.$bulk->personal_phone_number,
            $bulk->no_of_adult_guest + $bulk->no_of_children_guest + $bulk->no_of_babies_guest,
            $bulk->total_days,
            $bulk->total_booking_amount,
            $bulk->discount_amount,
            $bulk->loyalty_amount,
            $bulk->refund_amount,
            $bulk->host_amount,
            $bulk->admin_amount,
            $bulk->tax_amount,
            $bulk->influencer_amount,
            $bulk->optional_service_amount,
            $bulk->security_deposite,
            $bulk->booking_status,
            $bulk->booking_type,
           /* $amenity_name,
            $service_name,
            $country_name,
            $province_name,
            $city_name,
            $area_name,
            $bulk->getPropertyAddress[0]->postal_code,
            $bulk->getPropertyAddress[0]->street_name,
            $bulk->getPropertyAddress[0]->street_type,
            $bulk->getPropertyAddress[0]->street_number,
            $bulk->getPropertyAddress[0]->house_number,
            $bulk->getPropertyAddress[0]->floor,
            $bulk->getPropertyAddress[0]->staircase,
            $bulk->getPropertyAddress[0]->apartment_door_no,
            $bulk->additional_notes,
            date('d M Y', strtotime($bulk->created_at)),*/
        ];
    }

}
