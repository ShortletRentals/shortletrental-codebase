@extends('layouts.master')
@section('content')
<!--start page wrapper -->
<?php 
use App\Models\Province;
use App\Models\Country;
use App\Models\Property;
use App\Models\Category;
use App\Models\User;

if(isset($data) && !empty($data->category_id)){
    $category_name = Category::where('id',$data->category_id)->pluck('name')->first();
}else{
    $category_name = '';
}
if(isset($data) && !empty($data->influencer)){
    $influencer_name = User::where('id',$data->influencer)->pluck('name')->first();
}else{
    $influencer_name = '';
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
                            <h5 class="dataLabel">General Data</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>All Accomodation : </h5>
                                        <h6><b>{{ isset($data->all_properties) && $data->all_properties == 'Yes' ? 'Yes' : 'No' }}</b></h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Accomodation : </h5>
                                        <h6>{{ isset($data) && !empty($data->title) ? $data->title : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Price(NGN) : </h5>
                                        <h6>{{ isset($data) && !empty($data->price) ? $data->price : '0' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Start Date : </h5>
                                        <h6>{{ isset($data) && !empty($data->start_date) ? $data->start_date : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>End Date : </h5>
                                        <h6>{{ isset($data) && !empty($data->end_date) ? $data->end_date : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Status : </h5>
                                        <h6>{{ isset($data->status) && $data->status == 1 ? 'Active' : 'Inactive' }}</h6>
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
