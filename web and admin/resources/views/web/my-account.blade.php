@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
@extends('layouts.web.master')
<?php

	if (!$data) {
		// Redirect browser
		header("Location: ".route('web.home'));
		 
		exit;
	}
	$userData =  Session::get('AuthUserData') ?? null;
	// dd($userData);
	if(isset($userData) && !empty($userData)){
		$token = $userData->token;
	}else{
		$token = '';
	}


?>
@section('content')
  <main>
    <section class="mybooking_page space-cls">
        <div class="container">
        	<div class="booking-inner">
        		<div class="sidebar-wrap">
        			@include('layouts.web.leftbar_itms')
        			<div class="sidebar_r">
        				<div class="inner-title">
        					<h2 class="heading-inner-title">My Account</h2>
        				</div>

						<form method="POST" action="" class="booking-form" enctype="" id="edit_profile_form" enctype="multipart/form-data">
							@csrf

						
							<div class="my-account-sec">
								<div class="user-prof-img">
									<img src="{{$data->data->image}}" alt="uplaod" id="previewing">
									<div class="file_input">
									<input type="file" name="image" id="file-upload">
									<span for="image">
										<span class="upload_icon"><img src="{{ URL::asset('assets/web/img/upload_icon.png')}}" alt="uplaod"></span>
									</span>
									</div>
								</div>
								<div class="my-account-cont">
									<!-- <form class="booking-form"> -->
									<div class="row">
										
										<div class="col-md-7">
											<div class="form-group select-country">
												<label>Name</label>
												<div  class="input-group">
													@if(isset($data) && !empty($data->title))
														<select name="title" class="form-control title" id='title'>
															<!-- <option value="">-- Select Title--</option> -->
															<option value="Company" <?php echo isset($data) && $data->title == 'Company' ? 'selected' : ''; ?>>Company</option>
															<option value="Dr" <?php echo isset($data) && $data->title == 'Dr' ? 'selected' : ''; ?>>Dr</option>
															<option value="Family" <?php echo isset($data) && $data->title == 'Family' ? 'selected' : ''; ?>>Family</option>
															<option value="Mr_&_Mrs" <?php echo isset($data) && $data->title == 'Mr_&_Mrs' ? 'selected' : ''; ?>>Mr & Mrs</option>
															<option value="Mr" <?php echo isset($data) && $data->title == 'Mr' ? 'selected' : ''; ?>>Mr.</option>
															<option value="Mrs" <?php echo isset($data) && $data->title == 'Mrs' ? 'selected' : ''; ?>>Mrs.</option>
															<option value="Ms" <?php echo isset($data) && $data->title == 'Ms' ? 'selected' : ''; ?>>Ms.</option>
															<option value="PhD" <?php echo isset($data) && $data->title == 'PhD' ? 'selected' : ''; ?>>PhD</option>
															<option value="Prof" <?php echo isset($data) && $data->title == 'Prof' ? 'selected' : ''; ?>>Prof</option>
														</select>
													@else
														<select name="title" class="form-control title" id='title'>
															<!-- <option value="">-- Select Title--</option> -->
															<option value="Company">Company</option>
															<option value="Dr" >Dr</option>
															<option value="Family" >Family</option>
															<option value="Mr_&_Mrs" >Mr & Mrs</option>
															<option value="Mr" selected>Mr.</option>
															<option value="Mrs" >Mrs.</option>
															<option value="Ms" >Ms.</option>
															<option value="PhD" >PhD</option>
															<option value="Prof" >Prof</option>
														</select>
													@endif
													<input type="text" name="name" placeholder="Name" value="{{$data->data->name}}" class="form-control" data-parsley-required="true">
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label>Surname</label>
												<input type="text" name="surname" placeholder="Surname" value="{{$data->data->surname}}" class="form-control" data-parsley-required="true">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Email Address</label>
												<input type="text" name="email" placeholder="Email" value="{{$data->data->email}}" class="form-control" data-parsley-required="true">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Date of Birth</label>
												<input type="text" name="dob" placeholder="05-06-1993" value="{{date('m/d/Y',strtotime($data->data->dob)) }}" class="form-control dob">
											</div>		
										</div>
										<div class="col-md-6">
											
											<div class="form-group radio-inner-w">
												<label>Gender</label>
												<div class="radio-main">
													<label class="custom_radio_b">
													<input type="radio" name="gender" value="Male" <?php echo isset($data->data->gender) ?  $data->data->gender == 'Male' ? 'checked' : '' : 'checked'; ?> data-parsley-required="true">
													<span class="checkmark"></span>Male
													</label>
													<label class="custom_radio_b">
													<input type="radio" name="gender" value="Female" <?php echo isset($data->data->gender	) ? $data->data->gender == 'Female' ?  'checked'  : '' 	: ''; ?> data-parsley-required="true">
													<span class="checkmark"></span> Female
													</label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group radio-inner-w">
												<label>Marital Status</label>
												<div class="radio-main">
													<label class="custom_radio_b">
													<input type="radio" name="marital_status" value="Married" <?php echo isset($data->data->marital_status) ?  $data->data->marital_status == 'Married' ? 'checked' : '' : 'checked'; ?> data-parsley-required="true">
													<span class="checkmark"></span>Married
													</label>
													<label class="custom_radio_b">
													<input type="radio" name="marital_status" value="Unmarried" <?php echo isset($data->data->marital_status) ? $data->data->marital_status == 'Unmarried' ? 'checked' : '' : ''; ?> data-parsley-required="true">
													<span class="checkmark"></span> Single
													</label>
												</div>
											</div>
										</div>

										<?php if(isset($data) && $data->data->user_type == 5){ ?>
										<div class="col-md-6">
											<div class="form-group radio-inner-w">
												<label>Is chat disabled for Owner</label>
												<select name="is_chat_disabled_for_host" id="is_chat_disabled_for_host" class="form-control">
													<option value="Yes" <?php isset($data) && $data->data->is_chat_disabled_for_host == 'Yes' ? 'selected' : '' ?>>Yes</option>
													<option value="No" <?php isset($data) && $data->data->is_chat_disabled_for_host == 'No' ? 'selected' : '' ?>>No</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Is Super Owner</label>
												<select name="is_super_host" id="is_super_host" class="form-control">
													<option value="Yes" <?php isset($data) && $data->data->is_super_host == 'Yes' ? 'selected' : '' ?>>Yes</option>
													<option value="No" <?php isset($data) && $data->data->is_super_host == 'No' ? 'selected' : '' ?>>No</option>
												</select>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Remarks</label>
												<textarea name="remarks" id="remarks" cols="30" rows="4" placeholder="Remarks" class="form-control">{{$data->data->remarks}}</textarea>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Secondary Email</label>
												<input type="text" name="secondary_email" placeholder="Email" value="{{ isset($data) && !empty($data->data->secondary_email) ? $data->data->secondary_email : '' }}" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group select-country">
												<label>2nd Phone</label>
												<div class="input-group">
													<select name="second_country_code" class="form-control second_country_code" id='second_country_code'>
														@if(!empty($country_phone))
															@foreach($country_phone as $key1 => $country_ph1)
																<?php $selected1 = isset($data->data->second_country_code) ? $data->data->second_country_code : "+234" ; ?>
																<option value="{{$country_ph1->phonecode}}" <?php echo $selected1 == $country_ph1->phonecode  ? 'selected' : '' ?>>{{$country_ph1->sortname}} +{{$country_ph1->phonecode}}</option>
															@endforeach
														@else
														@endif
													</select>
													<input type="text" name="second_mobile" placeholder="Second Mobile" value="{{ isset($data) && !empty($data->data->second_mobile) ? $data->data->second_mobile : '' }}" class="form-control">
												</div> 
											</div>
										</div>
										<?php } ?>
										<div class="col-12">
											<div class="margin-cls border border-1 p-2 rounded row">
												<h4 class="dataLabel">ADDRESS</h4>
												<div class="col-md-6 col-xl-6 col-xxl-4">
													<div class="form-group">
														<label for="inputName" class="form-label">Country</label>
														<select name="country_id" class="form-control country_id" /*onchange="getProvince()"*/ id='country_id'  data-parsley-required="true">
															@if(!empty($country))
																<option value="">Select Country</option>
																@foreach($country as $key => $country1)
																	<?php $selected = isset($data) && !empty($data->data->country_id) ? $data->data->country_id : "";?>
																	<option value="{{$country1->id}}" <?php echo $selected == $country1->id  ? 'selected' : '' ?>>{{$country1->name}}</option>
																@endforeach
															@else
															@endif
														</select>
													</div>
												</div>
												<div class="col-md-6 col-xl-6 col-xxl-4 show_provinceDiv">
													<label for="inputName" class="form-label">Province</label>
													<select name="province_id" class="form-control province_id1" id='province_id1'>
														<option value="">Select Province</option>
													</select>
												</div>

												<div class="col-md-6 col-xl-6 col-xxl-4 show_cityDiv">
													<label for="inputName" class="form-label">City</label>
													<select name="city_id" class="form-control city_id" id='city_id'>
														<option value="">Select City</option>
													</select>
												</div>
												
													
												<div class="col-md-6 col-xl-6 col-xxl-8">
													<div class="form-group">
														<label for="inputStreet" class="form-label">Address </label>
														<input type="text" name="address" id="address"	 placeholder="Address" value="{{ isset($data) && !empty($data->data->address) ? $data->data->address : '' }}" class="form-control">
														<input type="hidden" name="latitude" id="latitude" class="form-control" placeholder="Enter latitude" value="<?php echo isset($data) && !empty($data->data->latitude) ? $data->data->latitude : "";?>">
														<input type="hidden" name="longitude" id="longitude" class="form-control" placeholder="Enter longitude" value="<?php echo isset($data) && !empty($data->data->longitude) ? $data->data->longitude : "";?>">

													</div>
												</div>
												<!--div class="col-md-6 col-xl-6 col-xxl-4">
													<div class="form-group">
														<label for="inputStreetNumber" class="form-label">Street Number</label>
														<input type="text" name="street_number" placeholder="Street Number" value="{{ isset($data) && !empty($data->data->street_number) ? $data->data->street_number : '' }}" class="form-control">
													</div>
												</div>
												<!- <div class="col-md-6 col-xl-6 col-xxl-3">
													<div class="form-group">
														<label for="inputNumber" class="form-label">Number </label>
														<input type="text" name="number" placeholder="Number" value="{{ isset($data) && !empty($data->data->number) ? $data->data->number : '' }}" class="form-control">
													</div>
												</div> -->
												<div class="col-md-6 col-xl-6 col-xxl-4">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Postal Code</label>
														<input type="text" name="postal_code" placeholder="Postal Code" value="{{ isset($data) && !empty($data->data->postal_code) ? $data->data->postal_code : '' }}" class="form-control">
													</div>
												</div>
											</div>
										</div>
										<?php if($data->data->user_type == 3 || $data->data->user_type == 5){ ?>
										<div class="col-12">
											<div class="margin-cls border border-3 p-4 rounded row">
												<h4 class="dataLabel">DOCUMENTATION</h4>
												<div class="col-md-6">
													<label for="inputDocumentnumber" class="form-label">Document number </label>
													<input type="text" name="document_number" placeholder="Document Number" value="{{ isset($data) && !empty($data->data->document_number) ? $data->data->document_number : '' }}" class="form-control">
												</div>
												<div class="col-md-6">
													<label for="inputDocumentImage" class="form-label">Document Image</label>
													<div class="form-group input-group">
														@if(isset($data) && !empty($data->data->document_image))
															<div id="document_image_view_div" class="image_preview_div"><img id="document_previewing" src="{{ $data->data->document_image }}"></div>
														@else
															<div id="document_image_view_div" class="image_preview_div"><img id="document_previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
														@endif
														<div class="form-control" onclick="document.getElementById('document_image').click()">
															<label for="files">Select Document Image</label>
															<input type="file" id="document_image" name="document_image" style="visibility:hidden;" class="form-control">
														</div>
													</div>
												</div>
												<?php if($data->data->user_type == 3){ ?>
												<div class="col-md-6">
													<label for="inputIDCARD" class="form-label">ID Card</label>
													<div class="form-group input-group">
														@if(isset($data) && !empty($data->data->id_card_image))
															<div id="id_card_image_view_div" class="image_preview_div"><img id="id_card_previewing" src="{{ $data->data->id_card_image }}" width="32" height="32"></div>
														@else
															<div id="id_card_image_view_div" class="image_preview_div"><img id="id_card_previewing" src="{{ URL::asset('assets/images/image.png')}}" width="32" height="32"></div>
														@endif
														<div class="form-control" onclick="document.getElementById('id_card_image').click()">
															<label for="files">Upload ID Card Image</label>
															<input type="file" id="id_card_image" name="id_card_image" style="visibility:hidden;" class="form-control">
														</div>
													</div>
												</div>
												<?php } ?>	
											</div>
										</div>
										<?php } ?>
										<div class="col-md-12">
											<div class="profile-save d-flex">
												<input type="submit" name="" class="btn primary_btn me-3" value="Update">
												<!-- <a href="#" class="btn primary_btn me-3">Save</a> -->
												<a href="javascript:void(0)" style="width: 170px;" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</a>
											</div>
										</div>

									</div>
								</div>
							</div>
						</form>

        				<div class="loyalty_points_bg">
        					<div class="loyalty_point_title">
        						<div class="tilte-left">
        							<h4>Loyalty Points</h4>
        							<h3>{{ isset($data) && !empty($data->total_loyalty_points) ? $data->total_loyalty_points : 0 }} Points</h3>
        						</div>
        						<!-- <div class="tilte-right">
									<form method="POST" action="" class="subscribe-form row" id="update_royalty_form">
										@csrf
										<label>Purchase Loyalty Points</label>
										<div class="form-group">
											<input type="hidden" name="type" value="Loyalty">
											<input type="hidden" name="user_id" value="{{ $data->data->id }}">
											<input type="text" name="email" placeholder="Email" class="form-control" data-parsley-required="true">
											<div class="subscribe-now">
												<input type="submit" value="Subscribe Now" name="" class="btn primary_btn">
											</div>
										</div>
					                </form>
        						</div> -->
        					</div>
        					<div class="transaction-wrap">
        						<h3>Transactions</h3>
        						<div class="transaction-inner">
									@if(isset($data->loyalty_points) && count($data->loyalty_points) > 0)
									@foreach($data->loyalty_points as $points)
        							<div class="transaction-list">
        								<div class="transaction-left">
        									<div class="transaction-icon">
												@if(isset($points->type) && $points->type == 'Debit')
        											<img src="{{ URL::asset('assets/web/img/recevied_arrow.png')}}" alt="Received Points">
												@else
													<img src="{{ URL::asset('assets/web/img/send_arrow.png')}}" alt="Send Points">
												@endif
        									</div>
        									<div class="transaction-cont">
        										<h5>{{$points->points}}</h5>
        										<p>{{$points->title}}</p>
        									</div>
        								</div>
        								<div class="transaction-right">
        									<div class="transaction-date">
        										<p>{{date('d M Y', strtotime($points->created_at)) }}</p>
        									</div>
        								</div>
        							</div>
									@endforeach
									@else
										<div class="transaction-list">
											<p>No data found!</p>
										</div>
									@endif
        							<!-- <div class="transaction-list">
        								<div class="transaction-left">
        									<div class="transaction-icon">
        										<img src="{{ URL::asset('assets/web/img/recevied_arrow.png')}}" alt="Send Arrow">
        									</div>
        									<div class="transaction-cont">
        										<h5>500</h5>
        										<p>Lorem ipsum is a dummy text</p>
        									</div>
        								</div>
        								<div class="transaction-right">
        									<div class="transaction-date">
        										<p>08 th Sept</p>
        									</div>
        								</div>
        							</div>
        							<div class="transaction-list">
        								<div class="transaction-left">
        									<div class="transaction-icon">
        										<img src="{{ URL::asset('assets/web/img/recevied_arrow.png')}}" alt="Send Arrow">
        									</div>
        									<div class="transaction-cont">
        										<h5>1000</h5>
        										<p>Lorem ipsum is a dummy text</p>
        									</div>
        								</div>
        								<div class="transaction-right">
        									<div class="transaction-date">
        										<p>08 th Sept</p>
        									</div>
        								</div>
        							</div>
        							<div class="transaction-list">
        								<div class="transaction-left">
        									<div class="transaction-icon">
        										<img src="{{ URL::asset('assets/web/img/send_arrow.png')}}" alt="Send Arrow">
        									</div>
        									<div class="transaction-cont">
        										<h5>100</h5>
        										<p>Lorem ipsum is a dummy text</p>
        									</div>
        								</div>
        								<div class="transaction-right">
        									<div class="transaction-date">
        										<p>08 th Sept</p>
        									</div>
        								</div>
        							</div>
        							<div class="transaction-list">
        								<div class="transaction-left">
        									<div class="transaction-icon">
        										<img src="{{ URL::asset('assets/web/img/send_arrow.png')}}" alt="Send Arrow">
        									</div>
        									<div class="transaction-cont">
        										<h5>100</h5>
        										<p>Lorem ipsum is a dummy text</p>
        									</div>
        								</div>
        								<div class="transaction-right">
        									<div class="transaction-date">
        										<p>08 th Sept</p>
        									</div>
        								</div>
        							</div> -->
        						</div>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
    </section>
  </main>

  <script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
  
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY"></script>
<script src="{{ asset('js/parsley.min.js') }}"></script>
<script>
  // get address start
  var geocoder;
  var map;
  var marker;
  var infowindow = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
  var autocomplete;
  initializeAddres();

  // autoload(25.204849, 55.270783);


	function initializeAddres() {

autocomplete = new google.maps.places.Autocomplete((document.getElementById('address')),{ types: [] });

google.maps.event.addListener(autocomplete, 'place_changed', function() {
  var place = autocomplete.getPlace();
		// place variable will have all the information you are looking for.
		$('#latitude').val(place.geometry['location'].lat());
		$('#longitude').val(place.geometry['location'].lng());
  //codeAddress(place);
});
}



  function autoload(latitude,longitude) {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    google.maps.event.addListener(map, 'click', function() {
      infowindow.close();
    });

    marker = new google.maps.Marker({
            map: map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: {lat:latitude, lng: longitude}
          });
          marker.addListener('click', toggleBounce);
  }

  function toggleBounce()
  {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
  function geocodePosition(pos) {
    geocoder.geocode({
      latLng: pos
    }, function(responses) {
      if (responses && responses.length > 0) {
        marker.formatted_address = responses[0].formatted_address;
      } else {
        marker.formatted_address = 'Cannot determine address at this location.';
      }
      $('#address').val(marker.formatted_address);
      $('#latitude').val(marker.getPosition().lat());
      $('#longitude').val(marker.getPosition().lng());
      infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
      infowindow.open(map, marker);
    });
  }

</script>
  <script>
	/*OTP Start */
	$('#update_royalty_form').parsley();
	$(document).on('submit', "#update_royalty_form", function(e) {
		e.preventDefault();
		var _this = $(this);
		$('#group_loader').fadeIn();
		var formData = new FormData(this);
		$.ajax({
		url: '{{ route("web.update_royalty_form") }}',
		dataType: 'json',
		data: formData,
		type: 'POST',
		cache: false,
		contentType: false,
		processData: false,
		success: function(res) {
			if (res.status === true) {
			toastr.success(res.message);
			// window.location.reload();
			// $('#otp_form')[0].reset();
			// $('#otp_form').parsley().reset();
			setInterval(function () {  
				// window.location.replace("{{ route('web.my_account') }}");
				window.location.reload();
			}, 2000); 
			} else {
				toastr.error(res.message);
			}
		},
		error: function(jqXHR, textStatus, textStatus) {
			if (jqXHR.responseJSON.errors) {
			$.each(jqXHR.responseJSON.errors, function(index, value) {
				toastr.error(value)
			});
			} else {
			toastr.error(jqXHR.responseJSON.message)
			}
		}
		});
		return false;
	});

	$('#edit_profile_form').parsley();
	$("#edit_profile_form").on('submit', async function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		// alert($.type(formData));
		$('.preload').show();
		var result = await ajaxFunction('update_profile', formData);
		$('.preload').hide();
		if (result.status == true) {
			if (result.status === true) {
				toastr.success(result.message);
				setInterval(function () {  
					window.location.reload();
				}, 2000); 
			} else {
				toastr.error(result.message);
			}
		} else {
			if (typeof result.message == 'object') {
				var errors = result.message;
				console.log(errors);
				$.each(errors, function(i, error) {
					$("." + i).after("<div class='errors'>" + error + "</div>");
				});
			} else {
				toastr.error(result.message);
				alertmessage('update_profile', result.message, 'warning');
			}
		}
		$('#vefify_otp').trigger("reset");
		return false;
	});

	function ajaxFunction(method, formdata, header) {
      $('div.errors').remove();
      	return new Promise((resolve, reject) => {
			//  var currency = "{{ Session::get('currentCurrencyPaygen') ?? '' }}";
			var  url = '{{url("api/auth/")}}/' + method;
			//   var url = 'http://13.59.199.42/api/auth/'+method;
			var settings = {
				"url": url,
				"method": "POST",
				"timeout": 0,
				"headers": {
				"Accept": "application/json",
				"Authorization": "Bearer {{$token}}",
				//    "Authorization": "Bearer {{$AuthUserData->access_token ?? null}}",
				//    "currency": currency
				},
				"processData": false,
				"mimeType": "multipart/form-data",
				"contentType": false,
				"data": formdata,
				error: function(error) {
				console.log(error, '----------------------');
				}
			};
			$.ajax(settings).done(function(response) {
				resolve(JSON.parse(response));
			});
      	});
   	}

	// $(document).on('submit', "#edit_profile_form", function(e) {
	// 	e.preventDefault();
	// 	var _this = $(this);
	// 	$('#group_loader').fadeIn();
	// 	var formData = new FormData(this);
	// 	$.ajax({
	// 	url: '{{ route("web.update_profile") }}',
	// 	dataType: 'json',
	// 	data: formData,
	// 	type: 'POST',
	// 	cache: false,
	// 	contentType: false,
	// 	processData: false,
	// 	success: function(res) {
	// 		if (res.status === true) {
	// 		toastr.success(res.message);
	// 		setInterval(function () {  
	// 			window.location.reload();
	// 		}, 2000); 
	// 		} else {
	// 			toastr.error(res.message);
	// 		}
	// 	},
	// 	error: function(jqXHR, textStatus, textStatus) {
	// 		if (jqXHR.responseJSON.errors) {
	// 		$.each(jqXHR.responseJSON.errors, function(index, value) {
	// 			toastr.error(value)
	// 		});
	// 		} else {
	// 		toastr.error(jqXHR.responseJSON.message)
	// 		}
	// 	}
	// 	});
	// 	return false;
	// });

	$(document).ready(function () {
		getProvince();	
		$("#file-upload").change(function(){
	      var fileObj = this.files[0];
	      var imageFileType = fileObj.type;
	      var imageSize = fileObj.size;

	      var file = $('#file-upload')[0].files[0].name;
	      $(this).prev('label').text(file);
	    
	      var match = ["image/jpeg","image/png","image/jpg"];
	      if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
	        $('#previewing').attr('src','images/image.png');
	        toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
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
	      $("#file-upload").css("color","green");
	      $('#previewing').attr('src',e.target.result);
	    }

		////Upload Document Image
		$("#document_image").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#document_image')[0].files[0].name;
			$(this).prev('label').text(file);

			var match = ["image/jpeg","image/png","image/jpg"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
				$('#document_previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
				return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
				var reader = new FileReader();
				reader.onload = document_imageIsLoaded;
				reader.readAsDataURL(this.files[0]);
				}else{
				toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
				return false;
				} 
			}	      
		});
		function document_imageIsLoaded(e){
			//console.log(e);
			$("#document_image").css("color","green");
			$('#document_previewing').attr('src',e.target.result);
		}

		////Upload In Card
		$("#id_card_image").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#id_card_image')[0].files[0].name;
			$(this).prev('label').text(file);

			var match = ["image/jpeg","image/png","image/jpg"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
				$('#id_card_previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
				return false;
			}else{
				//console.log(imageSize);
				if(imageSize < 5000000){
					var reader = new FileReader();
					reader.onload = idcard_imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				} 
			}	      
		});
		function idcard_imageIsLoaded(e){
			//console.log(e);
			$("#id_card_image").css("color","green");
			$('#id_card_previewing').attr('src',e.target.result);
		}
	})

	$(document).on('change', '.country_id',function(){
		var country_id = $('#country_id').val();
        var province_id = "<?php if (isset($data) && $data->data->province_id) { echo $data->data->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
	            }
	        });
        }
	})

	$(document).on('change', '.province_id1',function(){
        var country_id = $('#country_id').val();
        var province_id = $('#province_id1').find(":selected").val();
        var city_id = "<?php if (isset($data) && $data->data->city_id) { echo $data->data->city_id; } ?>";
        if (province_id) {
	        $.ajax({
	            url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					// getArea();
	            }
	        });
        }
	})

	$(document).on('change', '.city_id',function(){
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = "";
		if (city_id) {
			$.ajax({
				url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
				dataType: 'html',
				success:function(result)
				{
					$('.show_areaDiv').html(result);
				}
			});
		}
	})

    function getProvince() {
		
        var country_id = $('#country_id').val();
        var province_id = "<?php if (isset($data) && $data->data->province_id) { echo $data->data->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);

	               // getCity();
					// getArea();
	            }
	        });
        }
    }

	getCity();
    function getCity() {
        var country_id = $('#country_id').val();
//        var province_id = $('#province_id1').val();
		var province_id = "<?php if (isset($data) && $data->data->province_id) { echo $data->data->province_id; } ?>";
        var city_id = "<?php if (isset($data) && $data->data->city_id) { echo $data->data->city_id; } ?>";
		// alert(city_id);
        if (province_id) {
	        $.ajax({
	            url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					// getArea();
	            }
	        });
        }
    }

	$(document).ready(function(){
		$(".dob").datepicker({
				minDate:"-1Y",
			maxDate:"-18Y	",
			numberOfMonths: 1,
			dateFormat:'yy-mm-dd',
			yearRange: "-100:+0",
			changeMonth: true,
			changeYear: true,
	
		});
	});
  </script>
@endsection