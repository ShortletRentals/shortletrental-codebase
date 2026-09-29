@extends('layouts.master')
@section('content')
<?php 
use App\Models\Province;
use App\Models\Country;
use App\Models\City;

if(isset($data) && !empty($data->country_id)){
    $country_name = Country::where('id',$data->country_id)->pluck('name')->first();
}else{
    $country_name = '';
}
if(isset($data) && !empty($data->province_id)){
    $province_name = Province::where('id',$data->province_id)->pluck('name')->first();
}else{
    $province_name = '';
}
if(isset($data) && !empty($data->city_id)){
    $city_name = City::where('id',$data->city_id)->pluck('name')->first();
}else{
    $city_name = '';
}
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
                    <div class="card-body">
                        <div class="view_table">
                            <h5>SUB-ADMIN DETAILS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <!-- <div class="view-list">
                                        <h5>Customer ID</h5>
                                        <h6><b>{{ isset($data) && !empty($data->unique_id) ? $data->unique_id : '-' }}</b></h6>
                                    </div> -->
                                    <div class="view-list">
                                        <h5>Name : </h5>
                                        <h6>{!! ucwords($data->title).' '. ucwords($data->name) !!}</h6>
                                    </div>
                                    <div class="view-list view-desc">
                                        <h5>Remarks : </h5>
                                        <h6>{!! ucwords($data->remarks) !!}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Surname : </h5>
                                        <h6>{{ isset($data) && !empty($data->surname) ? ucwords($data->surname) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Image : </h5>
                                        <h6><img src="{{ $data->image }}" class="logo-icon" alt="logo icon"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body">
                        <div class="view_table">
                            <h5>CONTACT</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Email : </h5>
                                        <h6>{{ isset($data) && !empty($data->email) ? $data->email : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Mobile Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->country_code) ? '+'.$data->country_code : '' }} - {{ isset($data) && !empty($data->mobile) ? $data->mobile : '' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Secondary Email : </h5>
                                        <h6>{{ isset($data) && !empty($data->email) ? $data->email : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>2nd Phone Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->second_country_code) ? $data->second_country_code : '' }} - {{ isset($data) && !empty($data->second_mobile) ? $data->second_mobile : '' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5>ADDRESS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Country : </h5>
                                        <h6>{{ isset($country_name) && !empty($country_name) ? $country_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Province : </h5>
                                        <h6>{{ isset($province_name) && !empty($province_name) ? $province_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>City : </h5>
                                        <h6>{{ isset($city_name) && !empty($city_name) ? $city_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Street : </h5>
                                        <h6>{{ isset($data) && !empty($data->street) ? $data->street : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Street Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->street_number) ? $data->street_number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->number) ? $data->number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Postal Code : </h5>
                                        <h6>{{ isset($data) && !empty($data->postal_code) ? $data->postal_code : '-' }}</h6>
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
