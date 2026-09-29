@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
@extends('layouts.web.master')
<?php
	use App\Models\Property;
	$auth_user = Session::get('AuthUserData');
	$is_guest = Session::get('is_guest');
	if(isset($auth_user) && $auth_user){
		$property_exist = Property::where('host',$auth_user->data->id)->first();
	}
?>
@section('content')
  <main class="host-property-main">
  	<div class="sld_s" style="background-image: url(assets/web/img/slider.jpg);">
  		<div class="sld_s_con">
  			<div class="container">
  				<h2>Start your Partner journey with us</h2>
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
							<h3>Partners</h3>
							<p>Thank you for your interest in becoming a partner on the Short-let Rentals platform. Shortlet Rentals is the biggest online booking platform created for 
								owners of shortlet properties and lovers of shortlet living.</p>
							<p> Shortlet Rentals gives you the unique opportunity to build an extra income for yourself and your business. </p>
							<p> As Nigeria's most recognized online  booking platform, You get the opportunity to refer your audience to a wide variety of accommodation and earn a commision when you refer your audience to book on our platform.</p> 
							<p> Guess what?  Your audience also gets a discount for using your referal code </p>
							<p> Join Shortlet Rentals Affiliate Partner Program and start earning commission on bookings made through your PROMOCODE. </p>
							<p>Signing up is free, easy and confirmed instantly!</p>
							<!-- <p>Thank you for your interest in becoming a partner on the Short-let Rentals platform. Shortlet Rentals is an online booking platform created for owners of shortlet properties and lovers of shortlet living</p>
							<p>With Shortlet Rentals, You get the opportunity to put your property right in front of your target audience.</p>
							<p>To get your property listed on the platform, you must fill a form to enable us know you and your property a little more and you must schedule an appointment for an in-person property inspection.</p>
							<p>The information provided will be used to create your profile account on the platform after the property inspection has been completed</p> -->
							<div class="click-to">
								<span>Click here to</span>
								@if($auth_user != null && $is_guest != 1)
									<!-- <a href="{{ route('web.become_a_host.host_type') }}" class="btn primary_btn">Get Started</a> -->
									<a href="javascript:void(0)" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#BecomePartnerModal">Get Started</a>
								@else
									<a href="javascript:void(0)" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#BecomePartnerModal">Get Started</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="became-host-earn">
				<!-- <h4>Earn more money when you list with Shortlet Rentals</h2> -->
				<h3>Become a partner today</h3>
				<p>Build an extra stream of income when you Partner with Shortlet Rentals.</p>
				<!-- <p>Become a partner . </p> -->
				<p>Shortlet Rentals partners earn commission on every booking and get unlimited income.</p>
				<div class="d-flex get-started">
					@if($auth_user != null && $is_guest != 1)
						<a href="{{ route('web.become_a_host.host_type') }}" class="btn primary_btn">Get Started</a>
					@else
						<a href="javascript:void(0)" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#BecomePartnerModal">Get Started</a>
					@endif
				</div>
			</div>
        </div>
    </section>
  </main>

<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ asset('js/parsley.min.js') }}"></script>
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
				setInterval(function () {
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