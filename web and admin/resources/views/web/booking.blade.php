@extends('layouts.web.master')
<?php
	use App\Models\AdminSettings;
	use App\Models\User;
	use App\Models\City;
	use App\Models\Province;
	$loyaltyDetails = AdminSettings::select('royalty_point_equal_to','second_royalty_amount')->first();
	if(isset($loyaltyDetails) && !empty($loyaltyDetails)){
		$royalty_point_equal_to1 = $loyaltyDetails->royalty_point_equal_to;
		if(isset($royalty_point_equal_to1) && !empty($royalty_point_equal_to1)){
			$royalty_point_equal_to = $royalty_point_equal_to1;
		}else{
			$royalty_point_equal_to = '';
		}
		$second_royalty_amount1 = $loyaltyDetails->second_royalty_amount;
		if(isset($second_royalty_amount1) && !empty($second_royalty_amount1)){
			$second_royalty_amount = $second_royalty_amount1;
		}else{
			$second_royalty_amount = '';
		}
	}
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');

    if (isset($auth_user)) {
      $userId = $auth_user->data->id;
	  if($is_guest != 1){
		$auth_user = User::where('id',$userId)->first();
	  }else{
		$auth_user = $auth_user->data;
	  }
    }

	if(isset($auth_user->city_id) && !empty($auth_user->city_id) ){
		$city_id = $auth_user->city_id;
		if(isset($city_id) && !empty($city_id)){
			$city_name = City::where('id',$city_id)->pluck('name')->first();
			$province_name = Province::where('id',$auth_user->province_id)->pluck('name')->first();
			
		}else{
			$city_name = '';
			$province_name = '';
		}
	}else{
		$city_name = '';
		$province_name = '';
	}
	// dd($auth_user);
?>
@section('content')

	@if($data->status == true)
    <main>
    	<section id="first-load">
        <span>Loading...</span>
        <img src="{{ URL::asset('assets/web/img/logo.png')}}" alt="logo" width="auto">
        <div class="box-loader">
          <div class="container">
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
          </div>
        </div>
      </section>
	    <section class="booking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="booking-row">
	        			<div class="booking-left">
	        			<form method="POST" action="" class="checkout-form booking-form" enctype="" id="checkoutBookingForm">
                			@csrf
	        				<div class="booking-title">
	        					<h4 class="heading-inner-title">Request to book</h4>
	        				</div>
	        				<div class="hotel-listing">
	        					<div class="hotel-img">
	        						<img src="{{$data->data->get_property->image}}" alt="">
	        					</div>
	        					<div class="hotel-cont">
	        						<h5>{{$data->data->get_property->title}}</h5>
	        						<!-- <p>{{$data->data->get_property->get_property_address[0]->address ?? ''}}</p> -->
									<?php $main_address = ''; ?>
									@if(isset($data->data->get_property->get_property_address[0] ))

										@if($data->data->get_property->get_property_address[0]->get_property_area)
										<?php $main_address .= isset($data->data->get_property->get_property_address[0]->get_property_area->name) && !empty($data->data->get_property->get_property_address[0]->get_property_area->name) ? $data->data->get_property->get_property_address[0]->get_property_area->name.', ' : ''; ?>
										@endif

										@if($data->data->get_property->get_property_address[0]->get_property_city)
											<?php $main_address .= isset($data->data->get_property->get_property_address[0]->get_property_city->name) && !empty($data->data->get_property->get_property_address[0]->get_property_city->name) ? $data->data->get_property->get_property_address[0]->get_property_city->name.', ' : ''; ?>
										@endif

										@if($data->data->get_property->get_property_address[0]->get_property_province)
										<?php $main_address .= isset($data->data->get_property->get_property_address[0]->get_property_province->name) && !empty($data->data->get_property->get_property_address[0]->get_property_province->name) ? $data->data->get_property->get_property_address[0]->get_property_province->name.', ' : ''; ?>
										@endif

										@if($data->data->get_property->get_property_address[0]->get_property_country)
										<?php $main_address .= isset($data->data->get_property->get_property_address[0]->get_property_country->name) && !empty($data->data->get_property->get_property_address[0]->get_property_country->name) ? $data->data->get_property->get_property_address[0]->get_property_country->name.' ' : ''; ?>
										@endif
									@endif

									@if(isset($main_address))
									<p> {{ $main_address }}</p>
									@endif
	        						<ul class="meta_list">
					                    <li class="meta_single">
					                      <div class="reting-cls">
					                        <div class="reting-icon">
					                          <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
					                        </div>
					                        <div class="location-cont">
					                          <p>{{ number_format($data->data->get_property->avg_rating,1) }}</p>
					                        </div>
					                      </div>
					                    </li>
					                    <li class="meta_single">
					                      <span>{{isset($data->data->get_property->total_rating) ? $data->data->get_property->total_rating : 0}} Reviews</span>
					                    </li>
					                </ul>
				                	<ul class="meta_list">
										@if(isset($data->data->get_property->get_user->is_super_host) && $data->data->get_property->get_user->is_super_host == 'Yes')
						                <li class="meta_single">
						                  <span class="pro-ic">
						                    <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
						                  </span>
						                  <span>Superhost</span>
						                </li>
										@endif
					            	</ul>
	        					</div>
	        				</div>


							<?php $include_service = 0; ?>
							@if(count($data->data->get_property->get_extra_service) > 0)
				            @foreach($data->data->get_property->get_extra_service as $extraService)
							@if(isset($extraService->get_service_data) && !empty($extraService->get_service_data))
							@if (ucwords($extraService->get_service_data->name) != 'Security Deposit')
							@if($extraService->get_service_data->price < 1)
							<?php $include_service = 1; ?>							
							@endif
							@endif
							@endif
				            @endforeach				                    
				            @endif

							
							@if($include_service == 1)
	    				   	<div class="other_service_sec">
								@if(count($data->data->get_property->get_extra_service) > 0)
									
				                    <!--h4 class="heading-inner-title">Included Services</h4-->
								
				                    <div class="list-serv">
				                      	@foreach($data->data->get_property->get_extra_service as $extraService)

											@if(isset($extraService->get_service_data) && !empty($extraService->get_service_data))
												@if (ucwords($extraService->get_service_data->name) != 'Security Deposit')
												@if($extraService->get_service_data->price < 1)
												<div class="single_serv">
													<h6>{{ ucwords($extraService->get_service_data->name) }}</h6>
													<h5>Included</h5>
												</div>
												
												@endif
												@endif
											@endif
				                      	@endforeach
				                    </div>
				                @endif
				                <!-- <h4 class="heading-inner-title">Mandatory & Included Services</h4>
				                <div class="list-serv">
				                    <div class="single_serv">
				                      <h6>Final Cleaning</h6>
				                      <h5>Included</h5>
				                    </div>
				                    <div class="single_serv">
				                      <h6>Internet Access</h6>
				                      <h5>Included</h5>
				                    </div>
				                    <div class="single_serv">
				                      <h6>Security deposit (Refundable)</h6>
				                      <h5>NGN50,000.00 /booking</h5>
				                    </div>
				                </div> -->
			                </div>
							@endif

			                @if(count($data->data->get_property->get_extra_service) > 0)

			                  	@foreach($data->data->get_property->get_extra_service as $extraService)
									@if(isset($extraService->get_service_data) && !empty($extraService->get_service_data))
										@if (ucwords($extraService->get_service_data->name) == 'Security Deposit')
										<div class="security-deposit-sec">
											<h4 class="heading-inner-title">Caution Fee</h4>
											<div class="security-listing">
												<!-- <h5>Amount: <span>NGN{{ ucwords($extraService->get_service_data->price) }} /booking</span></h5> -->
												<h5>Amount: <span>NGN{{ $data->data->get_property->security_deposit_amount }} /booking</span></h5>
												<h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
												<p>To be paid when booking or Checkout.</p>
											</div>
										</div>
										@endif
									@endif
			                  	@endforeach
			                @endif
			                <!-- <div class="security-deposit-sec">
			                    <h4 class="heading-inner-title">Security Deposit</h4>
			                    <div class="security-listing">
			                      <h5>Amount: <span>NGN50,000.00 /booking</span></h5>
			                      <h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
			                      <p>To be paid when booking or Checkout.</p>
			                    </div>
			                </div> -->
							<?php $include_service = 0; ?>
							@if(count($data->data->get_property->get_extra_service) > 0)
		                  			@foreach($data->data->get_property->get_extra_service as $extraService)
										@if(isset($extraService->get_service_data) && !empty($extraService->get_service_data))
											@if($extraService->get_service_data->price > 0)
											<?php $include_service = 1; ?>
											@endif
										@endif
									@endforeach
							@endif
							@if($include_service==1)
							@if(count($data->data->get_property->get_extra_service) > 0)
				                <div class="other_service_sec">
								
				                  	<h4 class="heading-inner-title">Optional services</h4>
									
		                  		
		                  			@foreach($data->data->get_property->get_extra_service as $extraService)
										@if(isset($extraService->get_service_data) && !empty($extraService->get_service_data))
											@if($extraService->get_service_data->price > 0)
												<label class="custom_checkbox">
													<input type="checkbox" name="optional_services" data-id="{{$extraService->get_service_data->id}}" data-name="{{$extraService->get_service_data->name}}" onclick="changePrice({{$extraService->get_service_data->price}})" value="{{$extraService->get_service_data->price}}" >
													<span class="checkmark"></span>
													{{$extraService->get_service_data->name}}  ( NGN {{$extraService->get_service_data->price}}  /booking )
												</label>
											@endif
										@endif
									@endforeach
								</div>
							@endif
							@else
							<br/>
							@endif
							
			                <div class="security-deposit-sec">
			                    <h4 class="heading-inner-title">Additional notes</h4>
			                    <div class="security-listing">
			                      <h5>Check-in schedule: <span>from {{ isset($data->getProperty->check_in_from_time) && !empty($data->getProperty->check_in_from_time) ? $data->getProperty->check_in_from_time : '15:00' }} to {{ isset($data->getProperty->check_in_to_time) && !empty($data->getProperty->check_in_to_time) ? $data->getProperty->check_in_to_time : '18:00' }} every day</span></h5>
			                      <h5>Check-out schedule: <span>Before {{ isset($data->getProperty->check_out_time) && !empty($data->getProperty->check_out_time) ? $data->getProperty->check_out_time : '12:00' }}</span></h5>
			                      <p>Refund of caution fee to be paid 24 hrs after checkout</p>
			                    </div>
			                </div>
			                
			         
			                <div class="personal_data_sec">
			                  	<h4 class="heading-inner-title">Personal data</h4>
			                  		<div class="row">
			                  			<div class="col-md-6">
			                  				<div class="form-group">
												
			                  					<input type="text" name="first_name" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." placeholder="First Name" class="form-control" value="<?php echo isset($auth_user->name) && !empty($auth_user->name) && (Session::get('is_guest') !=1) ? $auth_user->name : '' ?>"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="last_name" data-parsley-required="true" data-parsley-minlength="1" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." placeholder="Last Name" class="form-control" value="<?php echo isset($auth_user->surname) && !empty($auth_user->surname) && (Session::get('is_guest') !=1)  ? $auth_user->surname : '' ?>"> 
			                  				</div>
			                  			</div>
										  <div class="col-md-6">


										<div class="form-group select-country">
										<div class="input-group">

										<select name="country_code" class="fform-control country_code forget_country_code select_img_country_code" id='country_code' data-parsley-required="true" style="widht:120px">
										@if(!empty($countryData))
										<?php $selected = isset($_COOKIE["web_country_code"]) ? $_COOKIE["web_country_code"] : '234' ; ?>
										@foreach($countryData as $key => $country_ph)
										<option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == $selected){ echo 'selected'; } ?>> {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
										@endforeach
										@endif
										</select>
										<input type="text" name="phone_number" data-parsley-required="true" data-parsley-pattern="^[0-9 ]{8,15}$" data-parsley-pattern-message="Please enter valid mobile number" placeholder="Phone Number" class="form-control" value="<?php echo isset($auth_user) && !empty($auth_user->mobile) && (Session::get('is_guest') !=1)  ? $auth_user->mobile : '' ?>"> 
										</div>
										</div>



											<!--div class="form-group">
												<input type="text" name="phone_number" data-parsley-required="true" data-parsley-pattern="^[0-9 ]{8,15}$" data-parsley-pattern-message="Please enter valid mobile number" placeholder="Phone Number" class="form-control" value="<?php echo isset($auth_user) && !empty($auth_user->mobile) && (Session::get('is_guest') !=1)  ? $auth_user->mobile : '' ?>"> 
											</div-->
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<input type="text" name="email" data-parsley-required="true" data-parsley-pattern="^[a-z0-9][-a-z0-9._]+@([-a-z0-9]+[.])+[a-z]{2,5}$" data-parsley-pattern-message="Please enter valid email address" placeholder="Email" class="form-control" value="<?php echo isset($auth_user->email) && !empty($auth_user->email) && (Session::get('is_guest') !=1)  ? $auth_user->email : '' ?>"> 
											</div>
										</div>


			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="address" data-parsley-required="true" placeholder="Address" id="address" class="form-control" value="<?php echo isset($auth_user->address) && !empty($auth_user->address) && (Session::get('is_guest') !=1)  ? $auth_user->address : '' ?>"> 
												<input type="hidden" name="latitude" id="latitude" class="form-control" placeholder="Enter latitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->latitude) ? $data->getPropertyAddress[0]->latitude : "";?>">
												<input type="hidden" name="longitude" id="longitude" class="form-control" placeholder="Enter longitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->longitude) ? $data->getPropertyAddress[0]->longitude : "";?>">
	
			                  				</div>
			                  			</div>

										  <div class="col-md-6">
			                  				<div class="form-group">
												<select class="form-select form-control" data-parsley-required="true" name="country_id" aria-label="Default select example">
													<option value = '' selected>Country</option>
													<?php if (count($countryData)) { foreach ($countryData as $key => $value) { ?>
														<option value="{{$value->id}}" <?php if($auth_user->country_id == $value->id){ echo 'selected'; } ?>>{{$value->name}}</option>
													<?php } ?>
													<?php } ?>
												</select>
			                  				</div>
			                  			</div>


			                  			<div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="province" data-parsley-required="true" placeholder="Province" class="form-control" value="<?php echo $province_name ?? ''; ?>"> 
			                  				</div>
			                  			</div>

										  <div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="city" data-parsley-required="true" placeholder="City" class="form-control" value="<?php echo $city_name; ?>"> 
			                  				</div>
			                  			</div>

									
			                  			<div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="postal_code" placeholder="Postal Code" class="form-control" value="<?php echo isset($auth_user->postal_code) && !empty($auth_user->postal_code) && (Session::get('is_guest') !=1)  ? $auth_user->postal_code : '' ?>"> 
			                  				</div>
			                  			</div>
			                  		


										
			                  			<div class="col-md-12">
			                  				<div class="form-group">
			                  					<textarea id="" name="comment" rows="8" placeholder="Comments" class="form-control"></textarea>
			                  				</div>
			                  			</div>
			                  			<div class="col-md-12">
			                  				<div class="form-group">
				                  				<label class="custom_radio_b">
							                      <input type="radio" checked="checked" name="book_for" value="self" onclick="changeBookingForm(this)">
							                      <span class="checkmark"></span>I am the main guest
							                    </label>
							                    <label class="custom_radio_b">
							                      <input type="radio" name="book_for" value="other" onclick="changeBookingForm(this)">
							                      <span class="checkmark"></span> want to book for someone else
							                    </label>
							                </div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
																	<select class="form-select form-control" name="is_agency" data-parsley-required="true" aria-label="Default select example">
																	  <option value='' selected>Are you an agency?</option>
																	  <option value="Yes">Yes</option>
																	  <option value="No">No</option>
																	</select>
			                  				</div>
			                  			</div>
			                  		</div>
			                </div>
			                <div class="personal_data_sec guest_form_data d-none">
			                  	<h4 class="heading-inner-title">Guest's data</h4>
			                  		<div class="row">
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_first_name" value="" placeholder="First Name" class="form-control"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_last_name" value="" placeholder="Last Name" class="form-control"> 
			                  				</div>
			                  			</div>
										  <div class="col-md-6">
										  <div class="form-group select-country">
										<div class="input-group">

										<select name="guest_country_code" class="fform-control country_code forget_country_code select_img_guest_country_code" id='guest_country_code' data-parsley-required="true" style="widht:120px">
										@if(!empty($countryData))
										<?php $selected = isset($_COOKIE["web_country_code"]) ? $_COOKIE["web_country_code"] : '234' ; ?>
										@foreach($countryData as $key => $country_ph)
										<option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == $selected){ echo 'selected'; } ?>> {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
										@endforeach
										@endif
										</select>
										<input type="text" name="guest_phone_number" placeholder="Phone Number" class="form-control"> 
										</div>
										</div>
										</div>
			                  			
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_email" placeholder="Email" class="form-control"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_address" value="" placeholder="Address" id="guest_address" class="form-control"> 
												  <input type="hidden" name="guest_latitude" id="guest_latitude" class="form-control" placeholder="Enter latitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->latitude) ? $data->getPropertyAddress[0]->latitude : "";?>">
												<input type="hidden" name="guest_longitude" id="guest_longitude" class="form-control" placeholder="Enter longitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->longitude) ? $data->getPropertyAddress[0]->longitude : "";?>">

			                  				</div>
			                  			</div>

										  <div class="col-md-6">
			                  				<div class="form-group">
																	<select class="form-select form-control" name="guest_country_id" aria-label="Default select example">
																	  <option value = '' selected>Country</option>
																	  <?php if (count($countryData)) { foreach ($countryData as $key => $value) { ?>
											                  <option value="{{$value->id}}">{{$value->name}}</option>
											                <?php } ?>
											              <?php } ?>
																	</select>
			                  				</div>
			                  			</div>

										  <div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_province" value="" placeholder="Province" class="form-control"> 
			                  				</div>
			                  			</div>
			                  			
										  <div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_city" value="" placeholder="City" class="form-control"> 
			                  				</div>
			                  			</div>
			                  			
			                  			<div class="col-md-4">
			                  				<div class="form-group">
			                  					<input type="text" name="guest_zipcode" value="" placeholder="Postal Code" class="form-control"> 
			                  				</div>
			                  			</div>
			                  			
									
			                  		</div>
			                </div>
			                <div class="payment-method-sec">
			                	<h4 class="heading-inner-title">Payment method</h4>
			                	<div class="form-group">
		              				<label class="custom_radio_b">
			                      <input type="radio" name="payment_method" value="bank_account" onclick="changePaymentMethod(this)">
			                      <span class="checkmark"></span>Bank transfer Shortlet Rentals Ltd
			                    </label>
			                    <label class="custom_radio_b">
			                      <input type="radio" checked="checked" name="payment_method" value="card" onclick="changePaymentMethod(this)">
			                      <span class="checkmark"></span> Credit Card / Debit Card
			                    </label>
				                </div>
				                <div class="card-payemnt">
					                	<div class="row card-payemnt-info d-none">
					                		<div class="col-md-6">
					                			<div class="form-group">
					                				<input type="text" name="card_holder_name" placeholder="Card Holder Name" class="form-control">
					                			</div>
					                		</div>
					                		<div class="col-md-6">
					                			<div class="form-group">
					                				<input type="text" name="card_number" placeholder="Card Number" class="form-control">
					                			</div>
					                		</div>
					                		<div class="col-md-4">
					                			<div class="form-group">
					                				<input type="text" name="month" placeholder="Exp. Month" class="form-control">
					                			</div>
					                		</div>
					                		<div class="col-md-4">
					                			<div class="form-group">
					                				<input type="text" name="year" placeholder="Exp. Year" class="form-control">
					                			</div>
					                		</div>
					                		<div class="col-md-4">
					                			<div class="form-group">
					                				<input type="text" name="cvv" placeholder="CVV" class="form-control">
					                			</div>
					                		</div>
					                	</div>	
					                	<div class="payemnt-cont">
					                		<p class="bank_transfer_text d-none">If you choose this payment option, the accommodation will be pre-reserved for you.  However, to confirm the booking you will have to make a bank transfer deposit to Shortlet Rentals bank account-Zenith Bank- Account number- 1214818652. Send payment slip via Whatsapp to +234 912 287 7657. Thank you.</p>
					                		<div class="privacy_wrap">
					                			<div class="position-cls">
													<label class="custom_checkbox">
									                    <input type="checkbox" name="policy_read" value="Yes" data-parsley-required="true" class="form-control">
									                    <span class="checkmark"></span>
									                    I have read and I agree with the <a href="{{ route('web.term-of-use') }}" target="_blank">terms and conditions</a>, <a href="{{ route('web.privacy-policy') }}" target="_blank">privacy policy</a> and <a href="{{ route('web.cancellation-policy') }}" target="_blank">Cancellation Policy</a>
									                </label>
									              </div>
									              <div class="position-cls">
									                <label class="custom_checkbox">
									                    <input type="checkbox" name="send_special_offer" value="Yes">
									                    <span class="checkmark"></span>
									                    Send me special offers and promotions
									                </label>
									              </div>
												  @if(isset($data->loyalty_points) && !empty($data->points_amount))
													@if($data->data->get_property->book_type != 'Reserve')
													<input type="hidden" name="loyalty_points" value="{{$data->loyalty_points}}">
													<input type="hidden" name="loyalty_amount" value="{{$data->points_amount}}">
													<div class="position-cls">
														<label class="custom_checkbox">
															<input type="checkbox" name="redeem_royalty_points" class="redeem_royalty_points" value="Yes">
															<span class="checkmark"></span>
																Want to redeem from Loyalty Points, Your {{$data->loyalty_points}} L.P. In NGN is {{$data->points_amount}}.
														</label>
													</div>
													@endif
												  @endif
					                		</div>
					                	</div>
					                	<div class="book-now-sec">
											@if($data->data->get_property->book_type == 'Reserve')
                      							<input type="hidden" name="booking_type" value="Reserve">
					                			<input type="submit" class="btn primary_btn" value="Reserve"/>
											@else
					                			<input type="submit" class="btn primary_btn" value="Book Now"/>
											@endif
					                	</div>
					            </div>
			              </div>
		        			</div>
		        			<div class="booking-right">
		        				<div class="product_dtl_bg">
			                  	<div class="product_dtl_price">
			                    	<h3>Your Trip</h3>
			                  	</div>
			                  	<input type="hidden" name="cart_id" class="cart_id" value="{{$data->data->id}}">
			                  	<input type="hidden" name="discount_id" class="discount_id" value="">
			                  	<input type="hidden" name="discount_amount" class="discount_amount" value="">
			                  	<input type="hidden" name="per_night_price" class="per_night_price" value="{{$data->data->per_night_price}}">
			                  	<input type="hidden" name="total_days" class="total_days" value="{{$data->data->total_days}}">
			                  	<input type="hidden" name="total_booking_amount" class="total_booking_amount" value="{{$data->data->total_booking_amount}}">
			                  	<input type="hidden" name="total_booking_amount_show" class="total_booking_amount_show" value="{{$data->data->total_booking_amount}}">
			                  	<input type="hidden" name="discount_code" class="discount_code" value="">

								  <input type="hidden" id="check_in_date" value="{{$data->data->start_date}}">
					               	<input type="hidden" id="check_out_date" value="{{$data->data->end_date}}">
					                <div class="checkinout-sec">
					                    <div class="checkin_checkout">
					                      <div class="checkin_wrap">
					                        <label>Check in</label>
					                        <p>{{ date('M d, Y', strtotime($data->data->start_date))}}</p>
					                      </div>
					                      <div class="checkin_wrap">
					                        <label>Check out</label>
					                        <p>{{ date('M d, Y', strtotime($data->data->end_date))}}</p>
					                      </div>
					                    </div>
					                    <div class="checkout-dtl">
					                      <div class="guest-add">
					                        <div class="guest-main">
					                          <div class="guest-left">
					                            <h4>Who</h4>
					                            <div class="dropdown">
					                              <a href="#" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">{{ $data->data->adultCount + $data->data->childCount + $data->data->infantCount + $data->data->petCount. ' guests'}}</a>
					                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
					                                <div class="dropdown_cls">
					                                  <div class="dropdown_list">
					                                    <h4>Adults</h4>
					                                    <p>Ages 13 or above</p>
					                                  </div>
					                                  <div class="guest_count">
					                                    <div class="wrap">
					                                      <!-- <button type="button" id="sub" class="sub">-</button> -->
					                                      <input class="count" type="text" id="1" disabled value="{{$data->data->adultCount}}" min="1" max="100" />
					                                      <!-- <button type="button" id="add" class="add">+</button> -->
					                                    </div>
					                                  </div>
					                                </div>
					                                <div class="dropdown_cls">
					                                  <div class="dropdown_list">
					                                    <h4>Children</h4>
					                                    <p>Ages 2-12</p>
					                                  </div>
					                                  <div class="guest_count">
					                                    <div class="wrap">
					                                      <!-- <button type="button" id="sub" class="sub">-</button> -->
					                                      <input class="count" type="text" id="1" disabled value="{{$data->data->childCount}}" min="1" max="100" />
					                                      <!-- <button type="button" id="add" class="add">+</button> -->
					                                    </div>
					                                  </div>
					                                </div>
					                                <div class="dropdown_cls">
					                                  <div class="dropdown_list">
					                                    <h4>Infants</h4>
					                                    <p>Under 2</p>
					                                  </div>
					                                  <div class="guest_count">
					                                    <div class="wrap">
					                                      <!-- <button type="button" id="sub" class="sub">-</button> -->
					                                      <input class="count" type="text" id="1" disabled value="{{$data->data->infantCount}}" min="1" max="100" />
					                                      <!-- <button type="button" id="add" class="add">+</button> -->
					                                    </div>
					                                  </div>
					                                </div>
					                                <div class="dropdown_cls">
					                                  <div class="dropdown_list">
					                                    <h4>Pets</h4>
					                                    <p>Bringing a service animal?</p>
					                                  </div>
					                                  <div class="guest_count">
					                                    <div class="wrap">
					                                      <!-- <button type="button" id="sub" class="sub">-</button> -->
					                                      <input class="count" type="text" id="1" disabled value="{{$data->data->petCount}}" min="1" max="100" />
					                                      <!-- <button type="button" id="add" class="add">+</button> -->
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
				                  	<div class="promo_code subscribe-form">
				                  		<h3>Promo Code</h3>
						                  <div class="form-group">
						                    <input type="text" name="coupon_code" placeholder="" value="" class="form-control coupon_code">
						                    <div class="subscribe-now">
						                      <a href="javascript:void(0);" onclick="applyCouponCode()" class="btn primary_btn">Apply</a><br>
						                      <!-- <a href="javascript:void(0);" onclick="removeCouponCode()" class="btn primary_btn">Remove Promo Code</a> -->
											  <div class="remove_coupon_div"></div>
						                    </div>
						                  </div>
				                  	</div>
				                  	<div class="hotel-price-wrap">
				                  		<h3>Price Detail</h3>
					                    <ul class="price_structure">
					                      <li>
					                        <span class="left_price_p">NGN {{$data->data->per_night_price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> {{$data->data->total_days}} nights</span>
					                        <span class="right_price_p">NGN {{$data->data->per_night_price * $data->data->total_days}}</span>
					                      </li>
					                      <li>
					                        <span class="left_price_p">Caution Fee</span>
					                        <span class="right_price_p">NGN {{$data->data->get_property->security_deposit_amount ?? 0}}</span>
					                      </li>
					                      <div class="optionalServicesAppend">
					                      </div>
					                    </ul>
					                    <ul class="total_price">
					                      <li>
					                        <span class="left_price_p">Total</span>
					                        <span class="right_price_p total_booking_amount_shows">NGN {{$data->data->total_booking_amount}}</span>
					                      </li>
					                    </ul>
				                  	</div>
				                  	<div class="booknow-cls">
									  	@if($data->data->get_property->book_type == 'Reserve')
                      						<input type="hidden" name="booking_type" value="Reserve">
					                    	<a href="javascript:void(0)" onclick="submitCheckoutForm()" class="btn primary_btn">Reserve</a>
										@else
					                    	<a href="javascript:void(0)" onclick="submitCheckoutForm()" class="btn primary_btn">Book Now</a>
										@endif
					                </div>
				                </div>
	        					</form>
		        			</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>
	
	<div class="col-md-12 mb-3">
			                  <style>
			                    #map_canvas {
			                      width: 0%;
			                      height: 0px;
			                    }

			                    /* Optional: Makes the sample page fill the window. */
			                    
			                  </style>
			                  <div class="mb-0">
			                    <div id="map_canvas"></div>
			                  </div>
			            </div>			
  @else
  	<main>
	    <section class="booking_page space-cls">
	    	<div class="container">
	    		<p>Cart is empty!</p>
	    	</div>
			</section>
    </main>
  @endif
@endsection

@section('script')
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
  initializeGuestAddres();
  // autoload(25.204849, 55.270783);



    function initializeGuestAddres() {

    autocomplete = new google.maps.places.Autocomplete((document.getElementById('guest_address')),{ types: [] });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
            // place variable will have all the information you are looking for.
            $('#guest_latitude').val(place.geometry['location'].lat());
            $('#guest_longitude').val(place.geometry['location'].lng());
      //codeAddress(place);
    });
    }

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

  /*function codeAddress(pos) {
    var address = document.getElementById('address').value;
 
    geocoder.geocode({
      'latLng': pos
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        if (marker) {
          marker.setMap(null);
          if (infowindow) infowindow.close();
        }
        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: results[0].geometry.location
      });
      google.maps.event.addListener(marker, 'dragend', function() {
        geocodePosition(marker.getPosition());
      });
      google.maps.event.addListener(marker, 'click', function() {
        console.log('marker--'+marker.getPosition());
        if (marker.formatted_address) {
          infowindow.setContent(marker.formatted_address + "<br>coordinates2: " + marker.getPosition().toUrlValue(6));
          $('#address').val(marker.formatted_address);
        } else {
          infowindow.setContent(address + "<br>coordinates3: " + marker.getPosition().toUrlValue(6));
          $('#address').val(address);
        }
        $('#latitude').val(marker.getPosition().lat());
        $('#longitude').val(marker.getPosition().lng());
        infowindow.open(map, marker);
      });
        google.maps.event.trigger(marker, 'click');
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }*/
</script>
<script type="text/javascript">
	$('.redeem_royalty_points').on('click', function(){
		// alert($(this).val());
		if($(this).prop('checked') == true){
			//do something
			// alert($('.discount_amount').val());
			if($('.discount_amount').val() != ''){
				alert('Can\'t redeem Loyalty points when you apply promo code.');
			}
		}
	})
	$("#first-load").fadeOut()

	$('#checkoutBookingForm').parsley();
    $(document).on('submit', "#checkoutBookingForm", function(e) {
    	$("#first-load").fadeIn(1000);
		e.preventDefault();

		var is_guest = "{{$is_guest}}";
		var selectedOptions = [];
		var _this = $(this);
		// $('#group_loader').fadeIn();
		var formData = new FormData(this);

		$("input:checkbox[name=optional_services]:checked").each(function() {
		var data = {};
			data.id = $(this).attr('data-id');
			data.name = $(this).attr('data-name');
			data.value = $(this).val();
			selectedOptions.push(data);
		});
		formData.append('selected_options', JSON.stringify(selectedOptions));

		if($('.redeem_royalty_points').prop('checked') == true){
			if($('.discount_amount').val() != ''){
    			$("#first-load").fadeOut(1000);
				alert('Can\'t redeem Loyalty points when you apply promo code.');
			}else{
				$.ajax({
					url: '{{ route("web.checkout_form") }}',
					dataType: 'json',
					data: formData,
					type: 'POST',
					cache: false,
					contentType: false,
					processData: false,
					success: function(res) {
						$("#first-load").fadeOut()
						if (res.status === true) {
							if (res.data.authorization_url) {
//								console.log(res.data);
								window.location.href = res.data.authorization_url+'?booking_id='+res.data.booking_id;
								//window.location.href = res.data.authorization_url;
							}
							toastr.success(res.message);
							$('#checkoutBookingForm')[0].reset();
							$('#checkoutBookingForm').parsley().reset();

							// if (is_guest == 1) {
							// 	window.location.href = '{{ route("web.home") }}';

							// } else {
							// 	window.location.href = '{{ route("web.my_bookings") }}';
							// }
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
			}
		}else{
			$.ajax({
				url: '{{ route("web.checkout_form") }}',
				dataType: 'json',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				success: function(res) {
					$("#first-load").fadeOut()
					if (res.status === true) {
						if (res.data.authorization_url) { 
							//console.log(res.data);
							window.location.href = res.data.authorization_url+'?booking_id='+res.data.booking_id;
						}
						toastr.success(res.message);
						$('#checkoutBookingForm')[0].reset();
						$('#checkoutBookingForm').parsley().reset();

						// if (is_guest == 1) {
						// 	window.location.href = '{{ route("web.home") }}';

						// } else {
						// 	window.location.href = '{{ route("web.my_bookings") }}';
						// }
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
		}
      return false;
    });

    function submitCheckoutForm() {
		$("#checkoutBookingForm").submit();
    }

    function changeBookingForm($this) {
    	var booking_for = $($this).val();

    	if (booking_for == 'other') {
    		$('.guest_form_data').removeClass('d-none');

    	} else {
    		$('.guest_form_data').addClass('d-none');
    	}
    }

    function changePaymentMethod($this) {
    	var payment_method = $($this).val();

    	if (payment_method == 'card') {
    		$('.bank_transfer_text').addClass('d-none');

    	} else {
    		$('.bank_transfer_text').removeClass('d-none');
    	}
    }

    // function changePaymentMethod($this) {
    // 	var payment_method = $($this).val();

    // 	if (payment_method == 'card') {
    // 		$('.card-payemnt-info').removeClass('d-none');

    // 	} else {
    // 		$('.card-payemnt-info').addClass('d-none');
    // 	}
    // }

    function changePrice(price, text) {
    	var total_booking_amount = $('.total_booking_amount_show').val();
    	var discount_amount = $('.discount_amount').val();
    	var total_booking_amount_shows = parseInt(total_booking_amount);
    	var optionalServicesAppend = '';

    	if (discount_amount) {
    		 total_booking_amount_shows = total_booking_amount_shows - parseInt(discount_amount);
    	}

    	$("input:checkbox[name=optional_services]:checked").each(function(){
    			// alert($(this).attr('data-name'));
    			optionalServicesAppend += '<li><span class="left_price_p">'+$(this).attr('data-name')+'</span><span class="right_price_p">NGN '+$(this).val()+'</span></li>';

    			total_booking_amount_shows = parseInt(total_booking_amount_shows) + parseInt($(this).val());
			});

			$('.total_booking_amount_shows').text('NGN '+total_booking_amount_shows);
			$('.optionalServicesAppend').html(optionalServicesAppend);
    }

	function applyCouponCode() {
		var coupon_code = $('.coupon_code').val();
		var check_in_date = $("#check_in_date").val();
		var check_out_date = $("#check_out_date").val();

		if (coupon_code) {
			var formData = new FormData(); // Currently empty
			var token = "{{ csrf_token() }}";
			formData.append('_token', token);
			formData.append('coupon_code', coupon_code);
			formData.append('check_in_date', check_in_date);
			formData.append('check_out_date', check_out_date);

			$.ajax({
				url: '{{ route("web.checkCouponCode") }}',
				dataType: 'json',
				data: formData,
				type: 'POST', 
				cache: false,
				contentType: false,
				processData: false,
				success: function(res) {

					if (res.status === true) {
						// toastr.success(res.message);
						$('.discount_html').remove();
						$('.discount_code').val(coupon_code);
						$('.discount_id').val(res.data.id);
						var per_night_price = $('.per_night_price').val();
						var total_days = $('.total_days').val();
						var total_booking_amount = $('.total_booking_amount').val();
						var discount_percent = res.data.percentage;
						var total_discount_amount = parseInt(per_night_price) * parseInt(discount_percent) * parseInt(total_days) / 100;
						var total_booking_amount_shows = parseInt(total_booking_amount) - parseInt(total_discount_amount);
						$('.discount_amount').val(total_discount_amount);
						var discount_html = '<li class="discount_html"><span class="left_price_p">Discount Amount</span><span class="right_price_p"> - NGN '+total_discount_amount+'</span></li>';
						$('.price_structure').append(discount_html);
						$('.remove_coupon_div').html('<a href="javascript:void(0);" onclick="removeCouponCode()" class="btn primary_btn">Remove Promo Code</a>');

						$("input:checkbox[name=optional_services]:checked").each(function(){
							// alert($(this).val());
							total_booking_amount_shows = parseInt(total_booking_amount_shows) + parseInt($(this).val());
						});

						$('.total_booking_amount_shows').text('NGN '+total_booking_amount_shows);
						// $('.total_booking_amount_show').val(total_booking_amount_shows);

					} else {
						$('.discount_code').val('');
						$('.discount_id').val('');
						$('.discount_amount').val('');
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
		} else {
			toastr.error('Please enter coupon code');
		}
	}

	function removeCouponCode(){
		location.reload();
	}



</script>
@endsection