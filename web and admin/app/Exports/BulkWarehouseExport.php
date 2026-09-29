<?php
namespace App\Exports;
use App\Models\Orders;
use App\Models\Property;
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
use App\Models\PropertyCategory;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;
Use \Carbon\Carbon;

class BulkWarehouseExport implements FromQuery,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function __construct($request)
    {
        // dd($request->all());
        $this->file_type = $request->file_type;
        $this->status = $request->status??null;
        $this->start_date = $request->start_date??null;
        $this->end_date = $request->end_date??null;
        $this->host = $request->host??null;
        $this->search_type_list = $request->search_type_list??null;
        $this->search_city = $request->search_city??null;
        $this->search_area = $request->search_area??null;
        $this->max_guest_capacity = $request->max_guest_capacity??null;
        $this->search_bedroom = $request->search_bedroom??null;
        $this->search_category = $request->search_category??null;
        $this->price = $request->price??null;
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
            'Name or reference',
            'Type',
            'Category',
            'Host',
            'Building',
            'Price',
            'Tax (%)',
            'Featured',
            'Free Cancellation',
            'Max Guest',
            'Amenities',
            'Extra Services',
            'Country',
            'Province',
            'City',
            'Area',
            'Postal Code',
            'Street name',
            'Street Type',
            'Street Number',
            'House Number',
            'Floor',
            'Staircase',
            'Apartment Door No.',
            'Additional notes',
            // 'Created-At',
        ];
    }
    public function query()
    {
        // dd(auth()->id());
        $user_type = User::where('id',auth()->id())->pluck('user_type')->first();
                
        $q = Property::query()->select('properties.*','property_address.property_id as main_property_id','property_address.city_id as main_city_id','property_address.area','property_bedrooms.no_of_bedrooms')->leftjoin('property_address','property_address.property_id','=','properties.id')->leftjoin('property_bedrooms','property_bedrooms.property_id','=','properties.id')->with('getPropertyAddress','getProPropertyBathroom','getPropertyBedding','getPropertyBedroom','getPropertyKitchen','getPropertyHouserule');

        if($user_type == 5){
            $q->where('properties.host',auth()->id());
        }
        
        $orderby = 'properties.created_at';
        $order = 'desc';

        if(isset($this->status) && in_array($this->status,[0,1,2]))
        {
            $q->where('properties.status',$this->status);
        }

        if(isset($this->start_date) && isset($this->end_date))
        {
            $q->whereBetween('properties.created_at',[$this->start_date.' 00:00:01',$this->end_date.' 23:59:59']);
        }else{
            if(isset($this->start_date) && $this->end_date == null){
                $q->whereDate('properties.created_at',$this->start_date);
            }
        }

        if(isset($this->host) && !empty($this->host))
        {
            $q->where('properties.host',$this->host);
        }

        if(isset($this->search_type_list) && !empty($this->search_type_list))
        {
            $q->where('properties.type',$this->search_type_list);
        }

        if(isset($this->search_city) && !empty($this->search_city))
        { 
            $q->where('property_address.city_id',$this->search_city);
        }
        if(isset($this->search_area))
        {
          $q->where('property_address.area',$this->search_area);
        }
        if(isset($this->max_guest_capacity))
        {
            $q->where('properties.max_guest','<=',$this->max_guest_capacity);
        }
        if(isset($this->search_bedroom))
        {
            if ($this->search_bedroom != 'All') {
                $q->where('property_bedrooms.no_of_bedrooms',$this->search_bedroom);
            }
        }
        if(isset($this->search_category))
        {
            $getPropertyByCat = PropertyCategory::where('category_id', $this->search_category)->groupBy('property_id')->pluck('property_id')->toArray();
            $q->whereIn('properties.id',$getPropertyByCat);
        }
        if(isset($this->price))
        {
            if( strpos($this->price, ',') !== false ) {
                $price1 = str_replace(',', '', $this->price);
                $q->where('properties.price',$price1);
            }else{
                $q->where('properties.price',$this->price);
            }
        }
        if(isset($this->search_type_list))
        {
          $q->where('properties.type',$this->search_type_list);
        }

        return $q;
        /*you can use condition in query to get required result
         return Bulk::query()->whereRaw('id > 5');*/
    }
    public function map($bulk): array
    {
        // echo '<pre>'; print_r($bulk);
        $amenities = PropertyAmenity::where('property_id',$bulk->id)->pluck('amenities_id')->toArray();
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

            } else {
                $service_name = '-';
            }

        }else{
            $service_name = '-';
        }
        $category = PropertyCategory::where('property_id',$bulk->id)->pluck('category_id')->toArray();
        if(isset($category) && count($category) > 0){
            $category_details = Category::whereIn('id',$category)->pluck('name')->toArray();
            if(isset($category_details) && count($category_details) > 0){
                $category_name = implode (', ', $category_details);
            }
        }else{
            $category_name = '-';
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
        }
        $user_host = User::where('id',$bulk->host)->first();
        $host_name = isset($user_host) ? $user_host->name : '';
        // dd($country_name);
        return [
            $bulk->code,
            $bulk->title,
            $bulk->type,
            $category_name,
            $host_name,
            $bulk->building_name,
            $bulk->price,
            $bulk->tax,
            $bulk->featured,
            $bulk->free_cancellation,
            $bulk->max_guest,
            $amenity_name,
            $service_name,
            $country_name,
            $province_name,
            $city_name,
            $area_name,
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->postal_code : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->street_name : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->street_type : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->street_number : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->house_number : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->floor : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->staircase : '',
            isset($bulk->getPropertyAddress) && !empty($bulk->getPropertyAddress) ? $bulk->getPropertyAddress[0]->apartment_door_no : '',
            $bulk->additional_notes,
            // date('d M Y', strtotime($bulk->created_at)),
        ];
    }

}
