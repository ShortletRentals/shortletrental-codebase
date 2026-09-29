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
								<li class="breadcrumb-item"><a href="{{route('admin.'.$page.'.index','short')}}">{{$title}}</a></li>
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

		<!--Add Building Modal -->
		<div class="modal fade" id="newBuildingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Building</h5>
						<button type="button" class="close buildingModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="buildingFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputMobile" class="form-label">Image</label>
												<div class="input-group">
												@if(isset($data) && $data->image)
													<div id="image_preview"><img id="previewing_build" src="{{ $data->image }}"></div>
												@else
													<div id="image_preview"><img id="previewing_build" src="{{ URL::asset('assets/images/image.png')}}"></div>
												@endif
													<div class="form-control" onclick="document.getElementById('file_build').click()">
														<label for="files">Select Image</label>
														<input type="file" id="file_build" name="image" style="visibility:hidden;" class="form-control">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light buildingModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Building Modal End -->

		<!--Add Category Modal -->
		<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
						<button type="button" class="close categoryModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="categoryFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Type*</label>
												<?php  $errorClass =  !empty($errors->has('type')) ? 'is-invalid':''; ?>
												<select name="type" id="type" data-parsley-required="true" class="form-control {{$errorClass}}" placeholder="Select Type" data-dropdown-css-class="select2-primary">
													<option value="">--Select Type--</option>
													<option value="Aparthotel" <?php echo isset($data->type) &&  ($data->type == 'Aparthotel')  ? 'selected' : '' ?>>Aparthotel</option>
													<option value="Apartment" <?php echo isset($data->type) &&  ($data->type == 'Apartment')  ? 'selected' : '' ?>>Apartment</option>
													<option value="Boat" <?php echo isset($data->type) &&  ($data->type == 'Boat')  ? 'selected' : '' ?>>Boat</option>
													<option value="Bungalow" <?php echo isset($data->type) &&  ($data->type == 'Bungalow')  ? 'selected' : '' ?>>Bungalow</option>
													<option value="Chalet" <?php echo isset($data->type) &&  ($data->type == 'Chalet')  ? 'selected' : '' ?>>Chalet</option>
													<option value="Cottage" <?php echo isset($data->type) &&  ($data->type == 'Cottage')  ? 'selected' : '' ?>>Cottage</option>
													<option value="Country_house" <?php echo isset($data->type) &&  ($data->type == 'Country_house')  ? 'selected' : '' ?>>Country house</option>
													<option value="Farm_stay" <?php echo isset($data->type) &&  ($data->type == 'Farm_stay')  ? 'selected' : '' ?>>Farm stay</option>
													<option value="Garage" <?php echo isset($data->type) &&  ($data->type == 'Garage')  ? 'selected' : '' ?>>Garage</option>
													<option value="House" <?php echo isset($data->type) &&  ($data->type == 'House')  ? 'selected' : '' ?>>House</option>
													<option value="Mobile_home" <?php echo isset($data->type) &&  ($data->type == 'Mobile_home')  ? 'selected' : '' ?>>Mobile home</option>
													<option value="Rent_by_room" <?php echo isset($data->type) &&  ($data->type == 'Rent_by_room')  ? 'selected' : '' ?>>Rent by room</option>
													<option value="Residence" <?php echo isset($data->type) &&  ($data->type == 'Residence')  ? 'selected' : '' ?>>Residence</option>
													<option value="Studio" <?php echo isset($data->type) &&  ($data->type == 'Studio')  ? 'selected' : '' ?>>Studio</option>
													<option value="Townhouse" <?php echo isset($data->type) &&  ($data->type == 'Townhouse')  ? 'selected' : '' ?>>Townhouse</option>
													<option value="Trullo" <?php echo isset($data->type) &&  ($data->type == 'Trullo')  ? 'selected' : '' ?>>Trullo</option>
													<option value="Villa" <?php echo isset($data->type) &&  ($data->type == 'Villa')  ? 'selected' : '' ?>>Villa</option>
												</select>
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'cat_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputMobile" class="form-label">Image</label>
												<div class="input-group">
												@if(isset($data) && $data->image)
													<div id="image_preview"><img id="previewing_category" src="{{ $data->image }}"></div>
												@else
													<div id="image_preview"><img id="previewing_category" src="{{ URL::asset('assets/images/image.png')}}"></div>
												@endif
													<div class="form-control" onclick="document.getElementById('file_category').click()">
														<label for="files">Select Image</label>
														<input type="file" id="file_category" name="image" style="visibility:hidden;" class="form-control">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light categoryModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Category Modal End -->

		<!--Add Extra Service Modal -->
		<div class="modal fade" id="newExtraServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Extra Service</h5>
						<button type="button" class="close extraServiceModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="ExtraServiceFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'extra_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputPrice" class="form-label">Price</label>
												<?php  $errorClass =  !empty($errors->has('price')) ? 'is-invalid':''; ?>   
												{!! Form::text('price', null, array('id'=>'price','placeholder' => 'Enter price', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('price')) ?'<div class="invalid-feedback"><span>'.$errors->first('price').'</span></div>' :'' !!}
											</div>
											<!-- <div class="col-md-12 mb-3">
												<label for="inputMobile" class="form-label">Image</label>
												<div class="input-group">
												@if(isset($data) && $data->image)
													<div id="image_preview"><img id="previewing_extra" src="{{ $data->image }}"></div>
												@else
													<div id="image_preview"><img id="previewing_extra" src="{{ URL::asset('assets/images/image.png')}}"></div>
												@endif
													<div class="form-control" onclick="document.getElementById('file_extra').click()">
														<label for="files">Select Image</label>
														<input type="file" id="file_extra" name="image" style="visibility:hidden;" class="form-control">
													</div>
												</div>
											</div> -->
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light extraServiceModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Extra Service Modal End -->

		<!--Add Extra Service Modal -->
		<div class="modal fade" id="newAmenityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Amenity</h5>
						<button type="button" class="close amenityModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="amenityFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'amenity_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputMobile" class="form-label">Image</label>
												<div class="input-group">
												@if(isset($data) && $data->image)
													<div id="image_preview"><img id="previewing_amenity" src="{{ $data->image }}"></div>
												@else
													<div id="image_preview"><img id="previewing_amenity" src="{{ URL::asset('assets/images/image.png')}}"></div>
												@endif
													<div class="form-control" onclick="document.getElementById('file_amenity').click()">
														<label for="files">Select Image</label>
														<input type="file" id="file_amenity" name="image" style="visibility:hidden;" class="form-control">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light amenityModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Extra Service Modal End -->

		<!--Add Province Modal -->
		<div class="modal fade" id="newProvinceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Province</h5>
						<button type="button" class="close provinceModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="provinceFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">

											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Country*</label>
												<select name="country_id" class="form-control country_id" required id='country_id'>
													@if(!empty($country))
														<option value="">Select</option>
														@foreach($country as $key => $country2)
															<option value="{{$country2->id}}" >{{$country2->name}}</option>
														@endforeach
													@else
													@endif
												</select>
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Province Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'province_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light provinceModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Province Modal End -->

		<!--Add City Modal -->
		<div class="modal fade" id="newCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add City</h5>
						<button type="button" class="close cityModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="cityFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">

											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Country*</label>
												<select name="country_id" class="form-control country_id" onchange="getProvince_city()" required id='country_id_city'>
													@if(!empty($country))
														<option value="">Select</option>
														@foreach($country as $key => $country1)
															<option value="{{$country1->id}}" >{{$country1->name}}</option>
														@endforeach
													@else
													@endif
												</select>
											</div>
											<div class="col-md-12 mb-3 show_provinceDiv">
												<label for="inputName" class="form-label">Province*</label>
												<select name="province_id" class="form-control province_id" id='province_id' required>
													@if(!empty($province))
														<option value="">Select</option>
													@else
													@endif
												</select>
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">City Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'city_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light cityModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add City Modal End -->

		<!--Add Area Modal -->
		<div class="modal fade" id="newAreaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Area</h5>
						<button type="button" class="close areaModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="areaFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">

											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Country*</label>
												<select name="country_id" class="form-control country_id_area" required id='country_id_area'>
													@if(!empty($country))
														<option value="">Select</option>
														@foreach($country as $key => $country)
															<option value="{{$country->id}}" >{{$country->name}}</option>
														@endforeach
													@else
													@endif
												</select>
											</div>
											<div class="col-md-12 mb-3 show_provinceDiv">
												<label for="inputName" class="form-label">Province*</label>
												<select name="province_id" class="form-control province_id_area" id='province_id_area' required>
													<option value="">Select Province</option>
												</select>
											</div>
											<div class="col-md-12 mb-3 show_cityDiv">
												<label for="inputName" class="form-label">City*</label>
												<select name="city_id" class="form-control city_id" id='city_id' required>
													<option value="">Select City</option>
												</select>
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Area Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'area_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light areaModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Area Modal End -->

		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<!--end wrapper-->	

	<script>
  	function newBuildingModal($this){
		$('#newBuildingModal').modal('show');
	}
  	function newCategoryModal($this){
		$('#newCategoryModal').modal('show');
	}
  	function newExtraServiceModal($this){
		$('#newExtraServiceModal').modal('show');
	}
  	function newAmenityModal($this){
		$('#newAmenityModal').modal('show');
	}
  	function newProvinceModal($this){
		$('#newProvinceModal').modal('show');
	}
  	function newCityModal($this){
		$('#newCityModal').modal('show');
	}
  	function newAreaModal($this){
		$('#newAreaModal').modal('show');
	}
	$(document).ready(function(){
		$(".buildingModalClose").click(function(){
			$("#newBuildingModal").modal('toggle');
		});
		$(".categoryModalClose").click(function(){
			$("#newCategoryModal").modal('toggle');
		});
		$(".extraServiceModalClose").click(function(){
			$("#newExtraServiceModal").modal('toggle');
		});
		$(".amenityModalClose").click(function(){
			$("#newAmenityModal").modal('toggle');
		});
		$(".provinceModalClose").click(function(){
			$("#newProvinceModal").modal('toggle');
		});
		$(".cityModalClose").click(function(){
			$("#newCityModal").modal('toggle');
		});
		$(".areaModalClose").click(function(){
			$("#newAreaModal").modal('toggle');
		});
	});

    function getProvince_city() {
        var country_id = $('#country_id_city').val();
        var province_id = "";
        $.ajax({
            url:'{{url("admin/city/show_province")}}/'+country_id+'/'+province_id,
            dataType: 'html',
            success:function(result)
            {
                $('.show_provinceDiv').html(result);
            }
        });
    }

	$(document).on('change', '.country_id_area',function(){
		var country_id = $('.country_id_area').val();
        var province_id = "";

        if (country_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_province_new")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
	            }
	        });
        }
	})
	

	$("#areaFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#area_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/area/areaStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result.status'+result.status);
					if(result.status == true){
						toastr.success('Area added successfully.');
						$('.show_areaDiv').html(result.message);
						$('#newAreaModal').modal('hide');
						$('#areaFormSubmit')[0].reset();
					}else{
						toastr.error(result.message);
					}
					// if(result.status == true){
					// 	toastr.success(result.message);
					// 	setTimeout(function(){
					// 		location.reload();
					// 		// window.location.replace("{{ route('admin.rate.index') }}");
					// 	}, 1000);
					// }else{
					// 	toastr.error(result.message);
					// }
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#cityFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#city_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/city/cityStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result.status'+result.status);
					if(result.status == true){
						toastr.success('City added successfully.');
						$('.show_cityDiv').html(result.message);
						$('#newCityModal').modal('hide');
						$('#cityFormSubmit')[0].reset();
					}else{
						toastr.error(result.message);
					}
					// if(result.status == true){
					// 	toastr.success(result.message);
					// 	setTimeout(function(){
					// 		location.reload();
					// 		// window.location.replace("{{ route('admin.rate.index') }}");
					// 	}, 1000);
					// }else{
					// 	toastr.error(result.message);
					// }
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#provinceFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#province_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/province/provinceStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result.status'+result.status);
					if(result.status == true){
						toastr.success('Province added successfully.');
						$('.show_provinceDiv').html(result.message);
						$('#newProvinceModal').modal('hide');
						$('#provinceFormSubmit')[0].reset();
					}else{
						toastr.error(result.message);
					}
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#amenityFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#amenity_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/amenity/amenityStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result.status'+result);
					// toastr.success('Amenity added successfully.');
					// $('.main_amenity_div').html(result);
					// $('#newAmenityModal').modal('hide');
					if(result.status == true){
						toastr.success('Amenity added successfully.');
						$('.main_amenity_div').html(result.message);
						$('#newAmenityModal').modal('hide');
						$('#amenityFormSubmit')[0].reset();
					}else{
						toastr.error(result.message);
					}
				},
				error:function(jqXHR,textStatus,textStatus){
					console.log(jqXHR.responseJSON.errors);
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#ExtraServiceFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#extra_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/extra_service/extraServiceStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result.status'+result);
					// toastr.success('Extra Service added successfully.');
					// $('.main_services_div').html(result);
					// $('#newExtraServiceModal').modal('hide');
					if(result.status == true){
						toastr.success('Extra Service added successfully.');
						$('.main_services_div').html(result.message);
						$('#newExtraServiceModal').modal('hide');
						$('#ExtraServiceFormSubmit')[0].reset();
					}else{
						toastr.error(result.message);
					}
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#categoryFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#cat_name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/category/categoryStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				// dataType:'html',
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					// console.log('result.status'+result);
					// toastr.success('Category added successfully.');
					// $('.show_categories').html(result);
					// $('#newCategoryModal').modal('hide');
					if(result.status == true){
						toastr.success('Category added successfully.');
						$('.show_categories').html(result.message);
						$('#newCategoryModal').modal('hide');
						$('#categoryFormSubmit')[0].reset();
						// setTimeout(function(){
						// 	location.reload();
						// 	// window.location.replace("{{ route('admin.rate.index') }}");
						// }, 1000);
					}else{
						toastr.error(result.message);
					}
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;
	});

	$("#buildingFormSubmit").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var name = $('#name').val();
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = '{{ url("admin/building/buildingStore") }}';
		if(name != ''){
			$.ajax({
				url:url,
				// dataType:'html',
				dataType:'json',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				type:'POST',
				success:function(result){
					console.log('result--'+result);
					// var result1 = JSON.parse(result);
					// if(result.status == true){
						toastr.success('Building added successfully.');
						$('.main_building_div').html(result.message);
						$('#newBuildingModal').modal('hide');
						$('#buildingFormSubmit')[0].reset();
						// setTimeout(function(){
						// 	location.reload();
						// 	// window.location.replace("{{ route('admin.rate.index') }}");
						// }, 1000);
					// }else{
					// 	toastr.error(result.message);
					// }
				},
				error:function(jqXHR,textStatus,textStatus){
					if(jqXHR.responseJSON.errors){
						$.each(jqXHR.responseJSON.errors, function( index, value ) {
							toastr.error(value)
						});
					}else{
						toastr.error(jqXHR.responseJSON.message)
					}
				}
			});
		}
		return false;   
	});

	$(document).ready(function () {
		$("#file_build").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#file_build')[0].files[0].name;
			$(this).prev('label').text(file);
	    
			var match = ["image/jpeg","image/png","image/jpg","image/webp"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
				$('#previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and webp Images Type Allowed!!');
	        	return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				}	
			}
	    });
	    function imageIsLoaded(e){
	      //console.log(e);
	      $("#file_build").css("color","green");
	      $('#previewing_build').attr('src',e.target.result);
	    }
	})

	$(document).ready(function () {
		$("#file_category").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#file_category')[0].files[0].name;
			$(this).prev('label').text(file);
			
			var match = ["image/jpeg","image/png","image/jpg","image/webp"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
				$('#previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and webp Images Type Allowed!!');
				return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				}				
			}		
		});
		function imageIsLoaded(e){
		//console.log(e);
		$("#file_category").css("color","green");
		$('#previewing_category').attr('src',e.target.result);
		}
	})

	$(document).ready(function () {
		$("#file_extra").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#file_extra')[0].files[0].name;
			$(this).prev('label').text(file);
			
			var match = ["image/jpeg","image/png","image/jpg","image/webp"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
				$('#previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and webp Images Type Allowed!!');
				return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				}			
			}
		});

		function imageIsLoaded(e){
			//console.log(e);
			$("#file_extra").css("color","green");
			$('#previewing_extra').attr('src',e.target.result);
		}
	})

	$(document).ready(function () {
		$("#file_amenity").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#file_amenity')[0].files[0].name;
			$(this).prev('label').text(file);
			
			var match = ["image/jpeg","image/png","image/jpg","image/webp"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
				$('#previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and webp Images Type Allowed!!');
				return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				}			
			}
		});

		function imageIsLoaded(e){
			//console.log(e);
			$("#file_amenity").css("color","green");
			$('#previewing_amenity').attr('src',e.target.result);
		}
	})
	</script>
@endsection