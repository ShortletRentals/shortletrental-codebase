@extends('layouts.master')
@section('content')
<!--start page wrapper -->
<?php 
use App\Models\Province;

if(isset($data) && !empty($data->province_id)){
    $province_name = Province::where('id',$data->province_id)->pluck('name')->first();
}else{
    $province_name = '';
}
// dd($properties);
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
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">{{$title}} Data</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Name : </h5>
                                        <h6>{{ isset($data->title) && !empty($data->title) ? $data->title.' ' : '' }} {{ isset($data->full_name) && !empty($data->full_name) ? $data->full_name : '' }} {{ isset($data->surname) && !empty($data->surname) ? $data->surname : '' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Email : </h5>
                                        <h6>{{ isset($data) && !empty($data->email) ? $data->email : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Mobile Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->country_code) ? $data->country_code.' - ' : '-' }} {{ isset($data) && !empty($data->mobile) ? $data->mobile : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Province : </h5>
                                        <h6>{{ isset($province_name) && !empty($province_name) ? $province_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Landmark : </h5>
                                        <h6>{{ isset($data) && !empty($data->landmark) ? $data->landmark : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Contact Person Name : </h5>
                                        <h6>{{ isset($data) && !empty($data->contact_person_name) ? $data->contact_person_name : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Appointment Date : </h5>
                                        <h6>{{ isset($data) && !empty($data->appointment_date) ? date('d-M-Y', strtotime($data->appointment_date)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Appointment Time : </h5>
                                        <h6>{{ isset($data) && !empty($data->schedule_time) ? $data->schedule_time : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Added Date : </h5>
                                        <h6>{{ isset($data->created_at) && !empty($data->created_at) ? date('d-M-Y', strtotime($data->created_at)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Address : </h5>
                                        <h6>{{ isset($data) && !empty($data->address) ? $data->address : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Onsite Inspection : </h5>
                                        <h6>{{ isset($data) && !empty($data->onsite_inspection) ? $data->onsite_inspection : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Contact Person Number : </h5>
                                        @if($data->contact_person_number)
                                        <h6>{{ isset($data) && !empty($data->contact_person_country_code) ? $data->contact_person_country_code : '-' }}-{{ isset($data) && !empty($data->contact_person_number) ? $data->contact_person_number : '-' }}</h6>
                                        @else
                                        <h6>-</h6>
                                        @endif
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
