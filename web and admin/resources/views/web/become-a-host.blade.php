@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
@extends('layouts.web.master')
<?php
	use App\Models\Property;
	// if (!$data) {
	// 	// Redirect browser
	// 	header("Location: ".route('web.home'));		 
	// 	exit;
	// }
	$auth_user = Session::get('AuthUserData');
	$is_guest = Session::get('is_guest');
	// dd($auth_user->data->id);
	if(isset($auth_user) && $auth_user){
		$property_exist = Property::where('host',$auth_user->data->id)->first();
	}
	// dd($is_guest);
?>
@section('content')
  <main class="host-property-main">
  	<div class="sld_s" style="background-image: url(assets/web/img/slider.jpg);">
  		<div class="sld_s_con">
  			<div class="container">
  				<h2>Start your hosting journey with us</h2>
  			</div>
  		</div>
  	</div>
  	<section class="became-host-sec space-cls">
        <div class="container">
			<div class="became-host">
				<div class="row">
					<div class="col-md-5">
                  <div class="about-img">
                      <img src="{{ URL::asset('assets/web/img/host_img.jpg')}}" alt="About">
                  </div>
                </div>
					<div class="col-md-7">
						<div class="became-cont">
							<h3>Hosts</h3>
							<p>Thank you for your interest in becoming a host on the Short-let Rentals platform. Shortlet Rentals is an online booking platform created for owners of shortlet properties and lovers of shortlet living</p>
							<p>With Shortlet Rentals, You get the opportunity to put your property right in front of your target audience.</p>
							<p>To get your property listed on the platform, you must create a profile and upload your accommodation information and wait for approval from shortletrentals.com . A specialist will contact you within 24/48 hours after your profile has been created. The specialist will review your information and request for more information if needed. Once successfully reviewed and approved by specialist, You will be required to schedule an in -person inspection to verify the current state of your accommodation </p>
							<p>Please do not schedule an appointment for inspection unless you have gotten approval from a specialist to do so </p>
							<div class="click-to">
								<span>Click here to</span>
								@if($auth_user != null && $is_guest != 1)
									<a href="{{ route('web.become_a_host.host_type') }}" class="btn primary_btn">Get Started</a>
								@else
									<a href="javascript:void(0)" class="btn primary_btn" onclick="showBecomeModal()">Get Started</a>
									<!-- <a href="javascript:void(0)" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#BecomeAHostModal">Get Started</a> -->
								@endif
							</div>
							<p>Once you have received approval to proceed, kindly schedule your in-person appointment.</p>
							<div class="click-to">
								<span>Click here to</span>
								@if($auth_user != null && $is_guest != 1)
									<a href="{{ route('web.become_a_host.host_type') }}" class="btn primary_btn">Schedule Appointment</a>
								@else
									<a href="javascript:void(0)" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#schedule_appointment">Schedule Appointment</a>
								@endif
								<!-- <a href="#" class="btn primary_btn">Schedule Appointment</a> -->
							</div>
							<p>Once your appointment has been scheduled, an inspector will show up to your property on the scheduled date and time</p>
							<p class="font-bold">Thank you for wanting to be a part of a community that makes it easier for guest to find their perfect Shortlet</p>
						</div>
					</div>
					<!-- <div class="col-md-5">
						<div class="host-cont">
							<div class="shortlet-logo">
								<img src="{{ URL::asset('assets/web/img/shortlet-fav.png')}}" alt="Fav">
							</div>
							<div class="shortlet-desc">
								<div class="shortlet-inner-desc">
									<h4>shortlet Rental</h4>
									<p>Welcome to the scheduling page. Before you proceed to schedule your appointment, ensure you have filled the form and have been cleared by a team member to schedule your appointment. Please follow the instructions to add an event to our calendar.</p>
								</div>
								<div class="cookies-cls">
									<a href="{{ route('web.cookies-policy') }}" class="cookies-inner">Cookies policy</a>
								</div>
							</div>
						</div>
					</div> -->
				</div>
			</div>
			<div class="became-host-earn">
				<h4>Earn more money when you list with Shortlet Rentals</h2>
				<h3>Become a host</h3>
				<p>Shortlet Rentals hosts earn more money and get more exposure when they ist their property on the platform</p>
				<div class="d-flex get-started">
					@if($auth_user != null && $is_guest != 1)
						<a href="{{ route('web.become_a_host.host_type') }}" class="btn primary_btn">Get Started</a>
					@else
						<a href="javascript:void(0)" class="btn primary_btn" onclick="showBecomeModal()">Get Started</a>
						<!-- <a href="javascript:void(0)" class="btn primary_btn" id="BecomeAHostModal1">Get Started</a> -->
						<!-- <a href="javascript:void(0)" class="btn primary_btn" id="BecomeAHostModal" data-bs-toggle="modal" data-bs-target="#BecomeAHostModal">Get Started</a> -->
					@endif
				</div>
			</div>
        </div>
    </section>
  </main>

<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ asset('js/parsley.min.js') }}"></script>
  <script>
	function showBecomeModal(){
		$('#become_a_host_signup_form')[0].reset();
		$('#BecomeAHostModal').modal('show');
	}

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
	// $("#edit_profile_form").on('submit', async function(e) {
	// 	e.preventDefault();
	// 	var formData = new FormData(this);
	// 	// alert($.type(formData));
	// 	$('.preload').show();
	// 	var result = await ajaxFunction('update_profile', formData);
	// 	$('.preload').hide();
	// 	if (result.status == true) {
	// 		alertmessage('update_profile', result.message, 'success');
	// 		toastr.success(result.message);
	// 		setTimeout(function(){ 
				
	// 		}, 2000);
	// 	} else {
	// 		if (typeof result.message == 'object') {
	// 			var errors = result.message;
	// 			console.log(errors);
	// 			$.each(errors, function(i, error) {
	// 				$("." + i).after("<div class='errors'>" + error + "</div>");
	// 			});
	// 		} else {
	// 			toastr.error(result.message);
	// 			alertmessage('update_profile', result.message, 'warning');
	// 		}
	// 	}
	// 	$('#vefify_otp').trigger("reset");
	// 	return false;
	// });

	$(document).on('submit', "#edit_profile_form", function(e) {
		e.preventDefault();
		var _this = $(this);
		$('#group_loader').fadeIn();
		var formData = new FormData(this);
		$.ajax({
		url: '{{ route("web.update_profile") }}',
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
	                getCity();
					// getArea();
	            }
	        });
        }
    }

    function getCity() {
        var country_id = $('#country_id').val();
        var province_id = $('#province_id1').val();
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
			// minDate:"-1Y",
			maxDate:"+0D",
			numberOfMonths: 1,
			dateFormat:'dd-mm-yy',
			yearRange: "-100:+0",
			changeMonth: true,
			changeYear: true,
			// defaultDate: '-1Y', //Default to One Year Ago to show 12 months
			// onSelect: function(selected) {
			// 	$(".start_date").datepicker("option","maxDate", selected)
			// }
		});
	});
  </script>
@endsection