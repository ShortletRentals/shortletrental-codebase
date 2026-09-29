@extends('layouts.master')
@section('content')
<!--start page wrapper -->

<?php
    use App\Models\Province;
    use App\Models\Country;
    use App\Models\City;

    $country_id = isset($check_in_data) && !empty($check_in_data->country_id) ? $check_in_data->country_id : '';
    $province_id = isset($check_in_data) && !empty($check_in_data->province_id) ? $check_in_data->province_id : '';
    $city_id = isset($check_in_data) && !empty($check_in_data->city_id) ? $check_in_data->city_id : '';
    $nationality_id = isset($check_in_data) && !empty($check_in_data->nationality) ? $check_in_data->nationality : '';
    // $area = isset($check_in_data) && !empty($check_in_data->area) ? $check_in_data->area : '';
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
    if(isset($nationality_id) && !empty($nationality_id)){
        $nationality = Country::where('id',$nationality_id)->pluck('name')->first();
    }else{
        $nationality = '';
    }

    $fdate = $data->to_date;
    $tdate = $data->from_date;
    $datetime1 = new DateTime($fdate);
    $datetime2 = new DateTime($tdate);
    $interval = $datetime1->diff($datetime2);
    $days1 = $interval->format('%a');
    if(isset($days1) && $days1 != 0){
        $days = $days1;
    }else{
        $days = 0;
    }
?>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{$title}}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.'.$page.'.index')}}">{{$title}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="margin-cls border border-3 p-4 rounded"> 
                            <div class="view_table">
                                <h5 class="dataLabel">CHECK-IN DATA</h5>
                                <div class="view_inner">
                                    <div class="table-half">
                                        <div class="view-list">
                                            <h5>Accommodation : </h5>
                                            <h6>{{ isset($data) && !empty($data->property_details->title) ? $data->property_details->title : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Booking ID : </h5>
                                            <h6>{{ isset($data) && !empty($data->from_date) ? $data->booking_id : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>First Name : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->first_name) ? $check_in_data->first_name : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Last Name : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->last_name) ? $check_in_data->last_name : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Email : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->email) ? $check_in_data->email : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Phone Number : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->country_code) ? '+'.$check_in_data->country_code : '' }} - {{ isset($check_in_data) && !empty($check_in_data->phone_number) ? '+'.$check_in_data->phone_number : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Document Type : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->document_type) ? str_replace('_',' ',$check_in_data->document_type) : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Document Image : </h5>
                                            <!-- <h6>{{ isset($check_in_data) && !empty($check_in_data->document_image) ? $check_in_data->document_image : '' }}</h6> -->
                                            <h6> <img src="{{ $check_in_data->document_image }}" alt="" width="100" height="100"> </h6>
                                        </div>
                                    </div>
                                    <div class="table-half">
                                        <div class="view-list">
                                            <h5>Country : </h5>
                                            <h6>{{ isset($country_name) && !empty($country_name) ? $country_name : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Province : </h5>
                                            <h6>{{ isset($province_name) && !empty($province_name) ? $province_name : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>City : </h5>
                                            <h6>{{ isset($city_name) && !empty($city_name) ? $city_name : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Nationality : </h5>
                                            <h6>{{ isset($nationality) && !empty($nationality) ? $nationality : '' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pb-0">
                        <div class="margin-cls border border-3 p-4 rounded"> 
                            <div class="view_table">
                                <h5 class="dataLabel">ORGANISE YOUR TRIP DATA</h5>
                                <div class="view_inner">
                                    <div class="table-half">
                                        <div class="view-list">
                                            <h5>Departure Date : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->from_date) ? date('d/m/Y', strtotime($check_in_data->from_date)) : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure Time : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_time) ? $check_in_data->departure_time : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure at The Property : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_at_the_property) ? $check_in_data->departure_at_the_property : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure Travelling By : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_travelling_by) ? $check_in_data->departure_travelling_by : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure Travelling From : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_travelling_from) ? $check_in_data->departure_travelling_from : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure At : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_at) ? $check_in_data->departure_at : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure Travel Company : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_travel_company) ? $check_in_data->departure_travel_company : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Departure Number : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->departure_number) ? $check_in_data->departure_number : '' }}</h6>
                                        </div>
                                    </div>
                                    <div class="table-half">
                                    <div class="view-list">
                                            <h5>Arrival Date : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->to_date) ? date('d/m/Y', strtotime($check_in_data->to_date)) : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival Time : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_time) ? $check_in_data->arrival_time : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival at The Property : </h5>
                                            <!-- <h6>{{ isset($check_in_data) && !empty($check_in_data->to_date) ? date('d/m/Y', strtotime($check_in_data->to_date)) : '' }}</h6> -->
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_at_the_property) ? $check_in_data->arrival_at_the_property : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival Travelling By : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_travelling_by) ? $check_in_data->arrival_travelling_by : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival Travelling From : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_travelling_from) ? $check_in_data->arrival_travelling_from : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival At : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_at) ? $check_in_data->arrival_at : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival Travel Company : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_travel_company) ? $check_in_data->arrival_travel_company : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Arrival Number : </h5>
                                            <h6>{{ isset($check_in_data) && !empty($check_in_data->arrival_number) ? $check_in_data->arrival_number : '' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>


    </div>
</div>
</div>

<!--end page wrapper -->

@endsection
