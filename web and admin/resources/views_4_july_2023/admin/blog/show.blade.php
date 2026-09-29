@extends('layouts.master')
@section('content')
<?php 
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
                            <h5 class="dataLabel">{{strtoupper($title)}} DETAILS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Blog Category : </h5>
                                        <h6><b>{{ isset($category) && !empty($category) ? $category : '-' }}</b></h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Title : </h5>
                                        <h6>{!! ucwords($data->title) !!}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Created At : </h5>
                                        <h6>{{ isset($data) && !empty($data->created_at) ? date('d-m-Y', strtotime($data->created_at)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Image : </h5>
                                        <h6><img src="{{ $data->image }}" class="" alt="logo icon" width="100" height="100"></h6>
                                    </div>
                                </div>
                                <div class="table-full">
                                    <div class="view-list view-desc">
                                        <h5>Description : </h5>
                                        <h6>{!! ucwords($data->description) !!}</h6>
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
