@extends('layouts.master')
@section('content')
<?php 
use App\Models\Category;
use App\Models\Province;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use App\Models\Amenity;
use App\Models\ExtraService;
use App\Models\PropertyImage;
use App\Models\PropertyAmenity;
use App\Models\PropertyExtraService;
use App\Models\PropertyAddress;
use App\Models\PropertyBedroom;
use App\Models\PropertyBathroom;
use App\Models\PropertyKitchen;
use App\Models\PropertyBedding;
use App\Models\PropertyHouserule;
use App\Models\PropertyCategory;
use App\Models\EmailTemplateLang;

$addressDetails = PropertyAddress::where('property_id',$data->id)->first();
$country_id = isset($addressDetails) && !empty($addressDetails->country_id) ? $addressDetails->country_id : '';
$province_id = isset($addressDetails) && !empty($addressDetails->province_id) ? $addressDetails->province_id : '';
$city_id = isset($addressDetails) && !empty($addressDetails->city_id) ? $addressDetails->city_id : '';
$area = isset($addressDetails) && !empty($addressDetails->area) ? $addressDetails->area : '';
if(isset($country_id) && !empty($country_id)){
    $country_name = Country::where('id',$country_id)->pluck('name')->first();
}else{
    $country_name = '';
}
if(isset($province_id) && !empty($province_id)){
    $province_name = Province::where('id',$province_id)->pluck('name')->first();
}else{
    $province_name = '';
}
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
$amenities = PropertyAmenity::where('property_id',$data->id)->pluck('amenities_id')->toArray();
// dd($amenities);
if(isset($amenities) && count($amenities) > 0){
    $amenity_details = Amenity::whereIn('id',$amenities)->pluck('name')->toArray();
    if(isset($amenity_details) && count($amenity_details) > 0){
        $amenity_name = implode (', ', $amenity_details);
    }
}else{
    $amenity_name = '-';
}
$services = PropertyExtraService::where('property_id',$data->id)->pluck('service_id')->toArray();
// dd($services);
if(isset($services) && count($services) > 0){
    $services_details = ExtraService::whereIn('id',$services)->pluck('name')->toArray();
    if(isset($services_details) && count($services_details) > 0){
        $service_name = implode (', ', $services_details);
    }
}else{
    $service_name = '-';
}
$house_rules_details = PropertyHouserule::where('property_id',$data->id)->pluck('name')->toArray();
// dd($house_rules_details);
if(isset($house_rules_details) && count($house_rules_details) > 0){
    $house_rules = implode (', ', $house_rules_details);
}else{
    $house_rules = '-';
}
$category = PropertyCategory::where('property_id',$data->id)->pluck('category_id')->toArray();
// dd($category);
if(isset($category) && count($category) > 0){
    $category_details = Category::whereIn('id',$category)->pluck('name')->toArray();
    if(isset($category_details) && count($category_details) > 0){
        $category_name = implode (', ', $category_details);
    }
}else{
    $category_name = '-';
}
// dd(Auth::user()->user_type);
// dd($data->getProPropertyBathroom[0]->bathroom_with_bathtub);
?>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{$title}}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.'.$page.'.index','short')}}">{{$title}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                   
                    <div class="card-body">
                        <div class="copy_cls">
                            <ul class="nav nav-tabs mb-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon">
                                                <!-- <i class="bx bx-comment-detail font-18 me-1"></i> -->
                                            </div>
                                            <div class="tab-title"> General </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon">
                                                <!-- <i class="bx bx-bookmark-alt font-18 me-1"></i> -->
                                            </div>
                                            <div class="tab-title">Other Info</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primarycontact" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon">
                                                <!-- <i class="bx bx-star font-18 me-1"></i> -->
                                            </div>
                                            <div class="tab-title">Photos</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                           
                            <div class="duplicate_cls">
                                <button class="btn btn-primary duplicate_btn" id="{{ $data->id }}" data-id="{{ $data->id }}">Duplicate</button>
                            </div>
                           
                        </div>
						<div class="tab-content pt-3">
                            <!--First Start Div-->
							<div class="tab-pane fade active show first_div" id="primaryhome" role="tabpanel">
                                
                                <div class="card">
                                    <div class="card-body">
                                        <div class="view_table">
                                            <h5 class="dataLabel">{{ strtoupper('general data')}} : {!! ucwords($data->title)!!}</h5>
                                            <div class="view_inner">
                                                <div class="table-half">
                                                    <div class="view-list">
                                                        <h5>Name or reference : </h5>
                                                        <h6>{!! ucwords($data->title)!!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Type : </h5>
                                                        <h6>{!! ucwords($data->type)!!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Building/Urbanization : </h5>
                                                        <h6>{!! ucwords($building_name)!!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Category : </h5>
                                                        <h6>{!! $category_name !!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Reference (Accommodation code) : </h5>
                                                        <h6><b>{{ isset($data) && !empty($data->code) ? $data->code : '-' }}</b></h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Status : </h5>
                                                        <h6><?php echo isset($data) && $data->status == 1 ? 'Activated' : 'Deactivated'; ?></h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Date of upload : </h5>
                                                        <h6><?php echo isset($data) && $data->created_at != Null ? date('d/m/Y', strtotime($data->created_at)) : '-'; ?></h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Web View : </h5>
                                                        <h6><a href="{{ url('property-detail/'.$data->id) }}" target="_blank" class="contract_cls">Web view of the accommodation</a></h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Owner : </h5>
                                                        <h6>{!! $host_name !!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Cleaning Status : </h5>
                                                        <h6><?php echo isset($data) && $data->clean_status == 1 ? 'Cleaned' : 'Uncleaned'; ?></h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Featured : </h5>
                                                        <h6>{!! ucwords($data->featured)!!}</h6>
                                                    </div>

                                                    <div class="view-list">
                                                        <h5>Luxury : </h5>
                                                        <h6>{!! ucwords($data->luxury)!!}</h6>
                                                    </div>

                                                    <div class="view-list">
                                                        <h5>Rare : </h5>
                                                        <h6>{!! ucwords($data->rare)!!}</h6>
                                                    </div>

                                                    <div class="view-list">
                                                        <h5>Free Cancellation : </h5>
                                                        <h6>{!! ucwords($data->free_cancellation)!!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Max Guest : </h5>
                                                        <h6>{!! $data->max_guest !!}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Price(NGN) : </h5>
                                                        <h6>{{ isset($data) && !empty($data->price) ? $data->price : '-' }}</h6>
                                                    </div>
                                                    @if(isset($data) && !empty($data->party_rate_commission))
                                                    <div class="view-list">
                                                        <h5>Party Rate Commission (%) : </h5>
                                                        <h6>{{ isset($data) && !empty($data->party_rate_commission) ? $data->party_rate_commission : '-' }}</h6>
                                                    </div>
                                                    @endif
                                                    <div class="view-list">
                                                        <h5>Caution Fee (NGN) : </h5>
                                                        <h6>{{ isset($data) && !empty($data->security_deposit_amount) ? $data->security_deposit_amount : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Extra Services : </h5>
                                                        <h6>{{ isset($service_name) && !empty($service_name) ? $service_name : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Check-In From Time : </h5>
                                                        <h6>{{ isset($data->check_in_from_time) && !empty($data->check_in_from_time) ? $data->check_in_from_time : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Check-In To Time : </h5>
                                                        <h6>{{ isset($data->check_in_to_time) && !empty($data->check_in_to_time) ? $data->check_in_to_time : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Check-Out Time : </h5>
                                                        <h6>{{ isset($data->check_out_time) && !empty($data->check_out_time) ? $data->check_out_time : '-' }}</h6>
                                                    </div>
                                                </div>
                                                <div class="table-half">
                                                    <div class="view-list">
                                                        <h5>Main Image : </h5>
                                                        <h6>
                                                            <a class="example-image-link" href="{{ $data->image }}" data-lightbox="example-set" data-title="">
                                                                <img src="{{ $data->image }}" class="logo-icon" alt="logo icon">
                                                            </a>
                                                        </h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Video Url : </h5>
                                                        @if(isset($data) && !empty($data->video_url))
                                                            <h6><a href="{{ isset($data) && !empty($data->video_url) ? $data->video_url : '-' }}" target="_blank" class="contract_cls">{{ isset($data) && !empty($data->video_url) ? $data->video_url : '-' }}</a></h6>
                                                        @else
                                                            <h6>-</h6>
                                                        @endif
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Minimum number of nights : </h5>
                                                        <h6>{{ isset($data) && !empty($data->minimum_no_of_nights) ? $data->minimum_no_of_nights : '0' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Pets Allow : </h5>
                                                        <h6>{{ isset($data) && !empty($data->pets_allow) ? $data->pets_allow : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>CCTV : </h5>
                                                        <h6>{{ isset($data) && !empty($data->cctv) ? $data->cctv : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Indiate location of cameras : </h5>
                                                        <h6>{{ isset($data) && !empty($data->cctv_locations) ? $data->cctv_locations : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Allow a day booking : </h5>
                                                        <h6>{{ isset($data) && !empty($data->allow_a_day_booking) ? $data->allow_a_day_booking : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Response time : </h5>
                                                        <h6>{{ isset($data) && !empty($data->response_time) ? $data->response_time : '-' }}</h6>
                                                    </div>

                                                    <div class="view-list">
                                                        <h5>What is your responsibility for this apartment you intend to list on the platform : </h5>
                                                        <h6>{{ isset($data) && !empty($data->apartment_responsible) ? str_replace('_',' ',$data->apartment_responsible) : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Responsibilities : </h5>
                                                        <h6>{{ isset($data) && !empty($data->other_responsibility) ? $data->other_responsibility : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Is your property located within an estate : </h5>
                                                        <h6>{{ isset($data) && !empty($data->response_time) ? $data->response_time : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Estate Name : </h5>
                                                        <h6>{{ isset($data) && !empty($data->estate_name) ? $data->estate_name : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Landmark : </h5>
                                                        <h6>{{ isset($data) && !empty($data->landmark) ? $data->landmark : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Is the street where your property located on tarred road : </h5>
                                                        <h6>{{ isset($data) && !empty($data->tarred_located) ? $data->tarred_located : '-' }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Which of the following does your home support : </h5>
                                                        @if(isset($data->home_support) && $data->home_support == 'hosting_parties')
                                                            <h6>Hosting Parties</h6>
                                                        @elseif(isset($data->home_support) && $data->home_support == 'mini_events')
                                                            <h6>Hosting get together/mini-events</h6>
                                                        @else
                                                            <h6>{{ isset($data) && !empty($data->home_support) ? $data->home_support : '-' }}</h6>
                                                        @endif
                                                    </div>
                                                    <div class="view-list">
                                                        <h5>Maximum number of people allowed for parties : </h5>
                                                        <h6>{{ isset($data) && !empty($data->people_allowed_parties) ? $data->people_allowed_parties : '-' }}</h6>
                                                    </div>
                                                </div>
                                                <div class="table-full">
                                                    <div class="view-list view-desc">
                                                        <h5>Additional notes : </h5>
                                                        <h6><span>{!! ucwords($data->additional_notes)!!}</span></h6>
                                                    </div>
                                                </div>
                                                <div class="table-full">
                                                    <div class="view-list view-desc">
                                                        <h5>Description : </h5>
                                                        <h6><span>{!! ucwords($data->description)!!}</span></h6>
                                                    </div>
                                                </div>
                                                <div class="table-full">
                                                    <div class="view-list view-desc">
                                                        <h5>Booking Condition : </h5>
                                                        <h6><span>{!! ucwords($data->booking_condition)!!}</span></h6>
                                                    </div>
                                                </div>
                                                <div class="table-full">
                                                    <div class="view-list view-desc">
                                                        <h5>Cancellation Policy : </h5>
                                                        <h6><span>{!! ucwords($data->cancellation_policy)!!}</span></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
                            <!--First End Div-->
                            <!--Second Start Div-->
							<div class="tab-pane fade second_div" id="primaryprofile" role="tabpanel">
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('Location of accommodation')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Country : </h5>
                                                            <h6>{!! ucwords($country_name)!!}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Province : </h5>
                                                            <h6>{!! ucwords($province_name)!!}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>City : </h5>
                                                            <h6>{!! ucwords($city_name)!!}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Area : </h5>
                                                            <h6>{!! $area_name !!}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Postal Code : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->postal_code) ? $data->getPropertyAddress[0]->postal_code : '' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Street Type : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->street_type) ? str_replace('_',' ',$data->getPropertyAddress[0]->street_type) : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Street Name : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->street_name) ? $data->getPropertyAddress[0]->street_name : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Street Number : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->street_number) ? str_replace('_',' ',$data->getPropertyAddress[0]->street_number) : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Building/house no. : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->house_number) ? $data->getPropertyAddress[0]->house_number : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Floor : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->floor) ? $data->getPropertyAddress[0]->floor : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Staircase : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyAddress[0]->staircase == 1 ? 'Yes' : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Elevator : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyAddress[0]->elevator == 1 ? 'Yes' : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Apartment door no. : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyAddress[0]->apartment_door_no) ? $data->getPropertyAddress[0]->apartment_door_no : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <!-- 2d => long, 3d => Lat -->
                                                    <div class="table-full">
                                                        <div class="view-list view-desc contact-map">
                                                            @if(isset($data) && $data->latitude != Null)
                                                                <?php 
                                                                $map_link = '';
                                                                // $map_link = "https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2013759.3772745128!2d{{ $data->longitude }}!3d{{ $data->latitude }}!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0baf7da48d0d%3A0x99a8fe4168c50bc8!2sNigeria!5e0!3m2!1sen!2sin!4v1668000631590!5m2!1sen!2sin"
                                                                ?>
                                                                <!-- <iframe src="{{ $map_link }}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->

                                                                <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2013759.3772745128!2d7.1200382!3d9.6704635!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0baf7da48d0d%3A0x99a8fe4168c50bc8!2sNigeria!5e0!3m2!1sen!2sin!4v1668000631590!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                                                                    <!-- <iframe src="https://www.google.com/maps/search/?api=1&query=26.8306769,75.7941898" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>  -->

                                                                    <!-- <iframe width="300" height="170" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=26.8306769,75.7941898&hl=es&z=14&amp;output=embed" > </iframe><br />
                                                                    <small><a href="https://maps.google.com/maps?q=26.8306769,75.7941898&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank">See map bigger</a></small> -->

                                                                <iframe height="270" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ $data->latitude }},{{ $data->longitude }}&hl=es&z=14&amp;output=embed" style="pointer-events:none"> </iframe>
                                                                <!-- <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='+{{ $data->latitude }}+','+{{ $data->longitude }}+'&hl=es&z=14&amp;output=embed" > </iframe> -->
                                                            @else
                                                                <iframe width="300" height="170" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=26.8306769,75.7941898&hl=es&z=14&amp;output=embed" style="pointer-events:none"> </iframe>

                                                                <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2013759.3772745128!2d7.1200382!3d9.6704635!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0baf7da48d0d%3A0x99a8fe4168c50bc8!2sNigeria!5e0!3m2!1sen!2sin!4v1668000631590!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                                                                <!-- <iframe src="https://www.google.com/maps/search/?api=1&query=26.8306769,75.7941898" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                                                                    
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <style>
                                                            #map_canvas {
                                                            width: 100%;
                                                            height: 200px;
                                                            }

                                                            /* Optional: Makes the sample page fill the window. */
                                                            html,
                                                            body {
                                                            height: 100%;
                                                            margin: 0;
                                                            padding: 0;
                                                            }
                                                        </style>
                                                        <div class="form-group mb-0">
                                                            <!-- <div id="map_canvas"> </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('BEDROOMS')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Number of bedrooms : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_bedrooms) ? $data->getPropertyBedroom[0]->no_of_bedrooms : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Communal zones : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->communal_zones) ? str_replace('_',' ',$data->getPropertyBedroom[0]->communal_zones) : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Bunk bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_bunk_bed) ? $data->getPropertyBedroom[0]->no_of_bunk_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Double bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_double_bed) ? $data->getPropertyBedroom[0]->no_of_double_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Double sofa bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_double_sofa_bed) ? $data->getPropertyBedroom[0]->no_of_double_sofa_bed : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Number of Extra bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_extra_bed) ? $data->getPropertyBedroom[0]->no_of_extra_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Kingsize bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_kingsize_bed) ? $data->getPropertyBedroom[0]->no_of_kingsize_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Qweensize bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_qweensize_bed) ? $data->getPropertyBedroom[0]->no_of_qweensize_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Single bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_single_bed) ? $data->getPropertyBedroom[0]->no_of_single_bed : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Single sofa bed : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedroom[0]->no_of_single_sofa_bed) ? $data->getPropertyBedroom[0]->no_of_single_sofa_bed : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('BATHROOMS')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Bathrooms with bathtub: </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->bathroom_with_bathtub) ? $data->getProPropertyBathroom[0]->bathroom_with_bathtub : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Bathrooms with Shower: </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->bathroom_with_shower) ? $data->getProPropertyBathroom[0]->bathroom_with_shower : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Toilets : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->toilets) ? $data->getProPropertyBathroom[0]->toilets : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Sauna : </h5>
                                                            <h6>{{ isset($data) && $data->getProPropertyBathroom[0]->sauna == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Jacuzzi : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->jacuzzi) ? $data->getProPropertyBathroom[0]->jacuzzi : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Hair dryer : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->hair_dryer) ? $data->getProPropertyBathroom[0]->hair_dryer : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Towels : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->towels) ? $data->getProPropertyBathroom[0]->towels : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Towel change : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->towel_change) ? $data->getProPropertyBathroom[0]->towel_change : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Change frequency : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getProPropertyBathroom[0]->towel_change_frequency) ? $data->getProPropertyBathroom[0]->towel_change_frequency : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('Kitchen')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Number of Kitchens : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyKitchen[0]->no_of_kitchens) ? $data->getPropertyKitchen[0]->no_of_kitchens : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Kitchen Type : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyKitchen[0]->kitchen_type) ? $data->getPropertyKitchen[0]->kitchen_type : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Kitchen Category : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyKitchen[0]->kitchen_category) ? str_replace('_',' ',$data->getPropertyKitchen[0]->kitchen_category) : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Kitchen Amenities : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyKitchen[0]->kitchen_amenities) ? str_replace(',',', ',$data->getPropertyKitchen[0]->kitchen_amenities) : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('EQUIPMENT / BEDDING')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Bed linen : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->bed_linen) ? $data->getPropertyBedding[0]->bed_linen : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Bed linen change : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Bed change frequency : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->bed_Change_frequency) ? $data->getPropertyBedding[0]->bed_Change_frequency : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Washing machine : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->washing_machine == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Dryer : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->dryer == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Iron : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->iron == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Television : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->television == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Number of Television : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->no_of_television) ? $data->getPropertyBedding[0]->no_of_television : '-' }}</h6>
                                                        </div>
                                                        @if(isset($data) && !empty($data->location_of_television))
                                                        <div class="view-list">
                                                            <h5>Location of Television : </h5>
                                                            <h6>{{ isset($data) && !empty($data->location_of_television) ? $data->location_of_television : '-' }}</h6>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Number of Fans : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->fans) ? $data->getPropertyBedding[0]->fans : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Satellite TV : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->satellite_tv == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Radio : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->radio == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>DVD player : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->dvd_player == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Mosquito netting : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->mosquito_netting) ? str_replace('_',' ',ucwords($data->getPropertyBedding[0]->mosquito_netting)) : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Electronic mosquito repellents : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->electronic_mosquito_repellents == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Satellite TV Languages : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->satellite_tv_language) ? str_replace(',',', ',$data->getPropertyBedding[0]->satellite_tv_language) : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card margin-cls">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <!-- <h5>{{ strtoupper('EQUIPMENT / BEDDING')}} </h5> -->
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Internet access : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->internet_access) ? str_replace(',',', ',$data->getPropertyBedding[0]->internet_access) : '-' }}</h6>
                                                        </div>
                                                        @if(isset($data) && $data->getPropertyBedding[0]->internet_access == 'wifi')
                                                            <div class="view-list">
                                                                <h5>Network name (SSID) : </h5>
                                                                <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->network_name) ? $data->getPropertyBedding[0]->network_name : '-' }}</h6>
                                                            </div>
                                                            <div class="view-list">
                                                                <h5>Password : </h5>
                                                                <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->password) ? $data->getPropertyBedding[0]->password : '-' }}</h6>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Safe : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->safe == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Mini Bar : </h5>
                                                            <h6>{{ isset($data) && $data->getPropertyBedding[0]->mini_bar == 1 ? 'Yes' : 'No' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Key Code Number : </h5>
                                                            <h6>{{ isset($data) && !empty($data->getPropertyBedding[0]->key_code_number) ? $data->getPropertyBedding[0]->key_code_number : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('AMENITIES')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Amenities : </h5>
                                                            <h6>{{ $amenity_name }}</h6>
                                                        </div>
                                                        @if(isset($data) && !empty($data->standout_amenities))
                                                        <div class="view-list">
                                                            <h5>Standout Amenities : </h5>
                                                            <h6>{{ isset($data) && !empty($data->standout_amenities) ? $data->standout_amenities : '-' }}</h6>
                                                        </div>
                                                        @endif
                                                        <div class="view-list">
                                                            <h5>Swimming pool : </h5>
                                                            <h6>{{ isset($data) && !empty($data->swimming_pool) ? $data->swimming_pool : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Swimming pool Opening Period : </h5>
                                                            <h6>{{ isset($data) && !empty($data->pool_opening_period) ? date('d/m/Y', strtotime($data->pool_opening_period)) : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Swimming pool Closing Period : </h5>
                                                            <h6>{{ isset($data) && !empty($data->pool_closing_period) ? date('d/m/Y', strtotime($data->pool_closing_period)) : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>Heated Swimming pool : </h5>
                                                            <h6>{{ isset($data) && !empty($data->heated_swimming_pool) ? $data->heated_swimming_pool : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Heated Swimming pool Opening Period : </h5>
                                                            <h6>{{ isset($data) && !empty($data->heated_pool_opening_period) ? date('d/m/Y', strtotime($data->heated_pool_opening_period)) : '-' }}</h6>
                                                        </div>
                                                        <div class="view-list">
                                                            <h5>Heated Swimming pool Closing Period : </h5>
                                                            <h6>{{ isset($data) && !empty($data->heated_pool_closing_period) ? date('d/m/Y', strtotime($data->heated_pool_closing_period)) : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12  margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('GUEST AREA')}} </h5>
                                                <div class="view_inner">
                                                    <div class="table-half">
                                                        <div class="view-list">
                                                            <h5>House Rules : </h5>
                                                            <h6>{{ isset($house_rules) && !empty($house_rules) ? $house_rules : '-' }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
                            <!--Second End Div-->
                            <!--Thitd Start Div-->
							<div class="tab-pane fade third_div" id="primarycontact" role="tabpanel">
                                @if(isset($propertyImages) && count($propertyImages) > 0)
                                <div class="col-md-12 col-12 margin-cls">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="view_table">
                                                <h5 class="dataLabel">{{ strtoupper('Accommodation Images')}} </h5>
                                                <div class="accommodation-img-main-wrap">    
                                                    <div class="view-list images_content_response">
                                                        @foreach($propertyImages as $image)
                                                            <div class="single-img">
                                                                <div class="single-inner">
                                                                    <a class="example-image-link" href="{{ $image->image }}" data-lightbox="example-set" data-title="">
                                                                        <img src="{{ $image->image }}" class="example-image" alt="" width="100" height="200">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
							</div>
                            <!--Thitd End Div-->
						</div>
					</div>
                </div>
            </div>

        </div>
       
        
    </div>
</div>
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/magicsuggest/magicsuggest.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript">
    $(document).on('click','.duplicate_btn',function(){
      var id = $(this).attr('id');
      if(window.confirm('Are you sure you want to duplicate this accommodation?')) {
          $('.loader').show();
          $.ajax({
              url:"{{ url('admin/property/duplicate') }}",
              method: 'get',
              data: {'id':id},
              success: function(result){
                if(result.status == 1){
                    toastr.success(result.message);
                    setTimeout(function () {
                        // location.reload(true);
                        window.location.replace("{{ route('admin.property.index') }}");
                    }, 2000);
                }else{
                    toastr.error(result.message);
                }
              }
          }); 
      }else{
          var oldValue = $(this).attr('data-value');
          $(this).val(oldValue);
          return false;
      }
    });

  // get address start
	var geocoder;
	var map;
	var marker;
	var infowindow = new google.maps.InfoWindow({
		size: new google.maps.Size(150, 50)
	});
    // initialize();
  // autoload(25.204849, 55.270783);
    var autocomplete;
    var latitude = "<?php if (isset($data) && $data->latitude) { echo $data->latitude; } ?>";
    var longitude = "<?php if (isset($data) && $data->longitude) { echo $data->longitude; } ?>";
    // alert('latitude');
    if(latitude){
        // alert(latitude);
        autoload(latitude, longitude);
    }

	function autoload(latitude,longitude) {
        // alert(latitude);
        // alert(longitude);
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(latitude, longitude);
		var mapOptions = {
			zoom: 13,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		google.maps.event.addListener(map, 'click', function() {
			infowindow.close();
		});

		marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: {lat:latitude, lng: longitude}
		});
		marker.addListener('click', toggleBounce);
	}

	function toggleBounce()
	{
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
</script>

</div>
<!--end page wrapper -->

@endsection
