@extends('layouts.master')
@section('content')
<!--start page wrapper -->
<?php 
use App\Models\Discount;

if(isset($data) && !empty($data->discount)){
    $discount = Discount::where('id',$data->discount)->pluck('code')->first();
    // dd($host_name);
}else{
    $discount = '';
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
                            <h5>General Data</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Title : </h5>
                                        <h6>{{ isset($data) && !empty($data->title) ? $data->title : '-' }}</h6>
                                    </div>
                                    <!-- <div class="view-list">
                                        <h5>Host : </h5>
                                        <h6><b>{{ isset($host_name) && !empty($host_name) ? $host_name : '-' }}</b></h6>
                                    </div> -->
                                    <div class="view-list">
                                        <h5>Discount : </h5>
                                        <h6>{{ isset($discount) && !empty($discount) ? $discount : '-' }}</h6>
                                    </div>
                                    <!-- <div class="view-list">
                                        <h5>Valid From : </h5>
                                        <h6>{{ isset($data) && !empty($data->start_date) ? date('d/m/Y', strtotime($data->start_date)) : '-' }}</h6>
                                    </div> -->
                                </div>
                                <div class="table-half">
                                    <!-- <div class="view-list">
                                        <h5>Valid Upto : </h5>
                                        <h6>{{ isset($data) && !empty($data->end_date) ? date('d/m/Y', strtotime($data->end_date)) : '-' }}</h6>
                                    </div> -->
                                    <div class="view-list">
                                        <h5>All Accomodation : </h5>
                                        <h6>{{ isset($data) && !empty($data->all_properties) ? $data->all_properties : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Description : </h5>
                                        <h6>{{ isset($data) && !empty($data->description) ? $data->description : '-' }}</h6>
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
                            <h5>List of Accommodations</h5>
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
<!--end page wrapper -->

@endsection
