@extends('layouts.master')
@section('content')
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
        <div class="card">
            <div class="card-body">
                <!-- <div class="card-title">
                    <h5 class="mb-0">Available breakpoints</h5>
                </div>
                <hr/> -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Short Name</th><td>{!! ucwords($data->sortname)!!}</td></tr>
                            <tr><th>Name</th><td>{!! ucwords($data->name)!!}</td></tr>
<<<<<<< HEAD
                            <tr><th>Country Code</th><td>+{!! ucwords($data->phonecode)!!}</td></tr>
=======
                            <tr><th>Country Code</th>
                                @if(strpos($data->phonecode, "+") !== false){
                                    <td>{!! ucwords($data->phonecode)!!}</td>
                                @else
                                    <td>{{ '+'.ucwords($data->phonecode) }}</td>
                                @endif
                            </tr>
>>>>>>> 2392614a40e1d0811e1ea70c9ae71df52a12cb84
                            <!-- <tr><th>Image</th><td><img src="{{ $data->image }}" class="logo-icon" alt="logo icon"></td></tr> -->
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--end page wrapper -->

@endsection
