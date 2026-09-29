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
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                {!! Form::model($data,array('url' =>customeRoute($page.'.update',['id'=>$data->id]),'class'=>'formAction rate_form','method'=>'POST', 'data-parsley-validate'=>'true','enctype'=>'multipart/form-data')) !!}
                    @include('admin.'.$page.'.form')
                {!! Form::close() !!}
            </div>
            </div>
        </div>
@endsection

