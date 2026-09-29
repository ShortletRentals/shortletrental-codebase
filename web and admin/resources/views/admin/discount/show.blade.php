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
                                        <h5>Discount Code : </h5>
                                        <h6><b>{{ isset($data) && !empty($data->code) ? $data->code : '-' }}</b></h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Discount Percentage : </h5>
                                        <h6>{{ isset($data) && !empty($data->percentage) ? $data->percentage : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Category : </h5>
                                        <h6>{{ isset($data) && $data->all_categories == 'No' ? $category_name : 'All' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Valid From : </h5>
                                        <h6>{{ isset($data) && !empty($data->start_date) ? date('d/m/Y', strtotime($data->start_date)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Valid Upto : </h5>
                                        <h6>{{ isset($data) && !empty($data->end_date) ? date('d/m/Y', strtotime($data->end_date)) : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>No of user applied : </h5>
                                        <h6>{{ isset($data) && !empty($data->total_use) ? $data->total_use : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>No of use by single guest : </h5>
                                        <h6>{{ isset($data) && !empty($data->total_single_use) ? $data->total_single_use : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Partner : </h5>
                                        <h6>{{ isset($influencer_name) && !empty($influencer_name) ? $influencer_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>All Accomodation : </h5>
                                        <h6>{{ isset($data) && !empty($data->all_properties) ? $data->all_properties : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>            
        </div>

        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">List of Accommodations</h5>
                            <div class="view_inner d-block">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Accomodation ID</th>
                                                <th>ACCOMMODATION</th>
                                                <th>ACCOMMODATION TYPE</th>
                                                <th>CITY</th>
                                                <th>STATUS</th>
                                            </tr>
                                            
                                                @if(isset($accommodations) && count($accommodations) > 0 )
                                                    @foreach($accommodations as $accommodation)
                                                    <tr>
                                                        <td>{{ isset($accommodation) && !empty($accommodation->code) ? $accommodation->code : '-' }}</td>
                                                        <td>{{ isset($accommodation) && !empty($accommodation->title) ? $accommodation->title : '-' }}</td>
                                                        <td>{{ isset($accommodation) && !empty($accommodation->type) ? str_replace('_',' ',$accommodation->type) : '-' }}</td>
                                                        <td>{{ isset($accommodation) && !empty($accommodation->city_name) ? $accommodation->city_name : '-' }}</td>
                                                        <td>@if(isset($accommodation) && $accommodation->status == 1 )
                                                                Activated
                                                            @else
                                                                Deactivated
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="5" style="text-align:center;">No Accommodations found.</td>
                                                </tr>
                                                @endif
                                            
                                        </thead>
                                    </table>
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
