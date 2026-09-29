@extends('layouts.master')
@section('content')
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">

				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Owner</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Add New</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
			  	{!! Form::open(array('url' =>customeRoute($page.'.store'),'method'=>'POST','class'=>'formAction', 'id' => 'form', 'data-parsley-validate'=>'true' , 'enctype'=>'multipart/form-data')) !!}
			        @include('admin.'.$page.'.form')                        
			    {!! Form::close() !!}

			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<!--end wrapper-->	
@endsection