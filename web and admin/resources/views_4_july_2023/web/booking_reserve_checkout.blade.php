@extends('layouts.web.master')
<?php
	use App\Models\AdminSettings;
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
    }
?>
@section('content')

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
	        			<form method="POST" action="" class="checkout-form booking-form" enctype="" id="checkoutBookingReserverForm">
                			@csrf
							<input type="hidden" name="booking_type1" value="Reserve">
	        				<div class="booking-title">
	        					<h4 class="heading-inner-title">Request to book</h4>
	        				</div>
	        				<div class="hotel-listing">
	        					<div class="hotel-img">
	        						<img src="{{$data->getProperty->image}}" alt="">
	        					</div>
	        					<div class="hotel-cont">
	        						<h5>{{$data->getProperty->title}}</h5>
	        						<p>{{$data->getProperty->getPropertyAddress[0]->address ?? ''}}</p>
									<?php $main_address = ''; ?>
									@if(isset($data->getProperty->getPropertyAddress[0] ))
										@if($data->getProperty->getPropertyAddress[0]->getPropertyArea)
										<?php  $main_address .= isset($data->getProperty->getPropertyAddress[0]->getPropertyArea->name) && !empty($data->getProperty->getPropertyAddress[0]->getPropertyArea->name) ? $data->getProperty->getPropertyAddress[0]->getPropertyArea->name.', ' : ''; ?>
										@endif

										@if($data->getProperty->getPropertyAddress[0]->getPropertyCity)
											<?php $main_address .= isset($data->getProperty->getPropertyAddress[0]->getPropertyCity->name) && !empty($data->getProperty->getPropertyAddress[0]->getPropertyCity->name) ? $data->getProperty->getPropertyAddress[0]->getPropertyCity->name.', ' : ''; ?>
										@endif

										@if($data->getProperty->getPropertyAddress[0]->getPropertyProvince)
										<?php $main_address .= isset($data->getProperty->getPropertyAddress[0]->getPropertyProvince->name) && !empty($data->getProperty->getPropertyAddress[0]->getPropertyProvince->name) ? $data->getProperty->getPropertyAddress[0]->getPropertyProvince->name.', ' : ''; ?>
										@endif

										@if($data->getProperty->getPropertyAddress[0]->getPropertyCountry)
										<?php $main_address .= isset($data->getProperty->getPropertyAddress[0]->getPropertyCountry->name) && !empty($data->getProperty->getPropertyAddress[0]->getPropertyCountry->name) ? $data->getProperty->getPropertyAddress[0]->getPropertyCountry->name.' ' : ''; ?>
										@endif
									@endif
									@if(isset($main_address))
										<p> {{ $main_address }}</p>
									@endif
									@if($data->getProperty->total_rating > 0)
	        						<ul class="meta_list">
					                    <li class="meta_single">
					                      <div class="reting-cls">
					                        <div class="reting-icon">
					                          <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
					                        </div>
					                        <div class="location-cont">
												<p>{{ number_format($data->getProperty->avg_rating,1) }}</p>
					                        </div>
					                      </div>
					                    </li>
					                    <li class="meta_single">
											<span>{{$data->getProperty->total_rating}} Reviews</span>
					                    </li>
					                </ul>
									@endif
				                	<ul class="meta_list">
						                <li class="meta_single">
						                  <span class="pro-ic">
						                    <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
						                  </span>
						                  <span>Superhost</span>
						                </li>
					            	</ul>
	        					</div>
	        				</div>
	    				   	<div class="other_service_sec">
									@if(count($data->getProperty->getExtraService) > 0)
				                    <h4 class="heading-inner-title">Included Services</h4>
				                    <div class="list-serv">
				                      @foreach($data->getProperty->getExtraService as $extraService)
									  	@if($extraService->getServiceData)
											@if (ucwords($extraService->getServiceData->name) != 'Security Deposit')
											<div class="single_serv">
												<h6>{{ ucwords($extraService->getServiceData->name) }}</h6>
												<h5>Included</h5>
											</div>
											@endif
										@endif
				                      @endforeach
				                    </div>
				                @endif
			                </div>

			                @if(count($data->getProperty->getExtraService) > 0)

			                  @foreach($data->getProperty->getExtraService as $extraService)
							  	@if($extraService->getServiceData)
									@if (ucwords($extraService->getServiceData->name) == 'Security Deposit')
									<div class="security-deposit-sec">
										<h4 class="heading-inner-title">Caution Fee</h4>
										<div class="security-listing">
											<!-- <h5>Amount: <span>NGN{{ ucwords($extraService->getServiceData->price) }} /booking</span></h5> -->
											<h5>Amount: <span>NGN{{ $data->getProperty->security_deposit_amount }} /booking</span></h5>
											<h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
											<p>To be paid when booking or Checkout.</p>
										</div>
									</div>
									@endif
								@endif
			                  @endforeach
			                @endif
			                <div class="security-deposit-sec">
			                    <h4 class="heading-inner-title">Additional notes</h4>
			                    <div class="security-listing">
			                      <h5>Check-in schedule: <span>from {{ isset($data->getProperty->check_in_from_time) && !empty($data->getProperty->check_in_from_time) ? $data->getProperty->check_in_from_time : '15:00' }} to {{ isset($data->getProperty->check_in_to_time) && !empty($data->getProperty->check_in_to_time) ? $data->getProperty->check_in_to_time : '18:00' }} every day</span></h5>
			                      <h5>Check-out schedule: <span>Before {{ isset($data->getProperty->check_out_time) && !empty($data->getProperty->check_out_time) ? $data->getProperty->check_out_time : '12:00' }}</span></h5>
			                      <p>Refund of caution fee to be paid 24 hrs after checkout</p>
			                    </div>
			                </div>
			                
			                <!-- @if(count($data->getProperty->getExtraService) > 0)
				                <div class="other_service_sec">
				                  <h4 class="heading-inner-title">Optional services</h4>
		                  		
		                  		@foreach($data->getProperty->getExtraService as $extraService)
									@if($extraService->getServiceData)
		                  			@if($extraService->getServiceData->price > 0)
										<label class="custom_checkbox">
											<input type="checkbox" name="optional_services" data-id="{{$extraService->getServiceData->id}}" data-name="{{$extraService->getServiceData->name}}" onclick="changePrice({{$extraService->getServiceData->price}})" value="{{$extraService->getServiceData->price}}" >
											<span class="checkmark"></span>
											{{$extraService->getServiceData->name}}  ( NGN {{$extraService->getServiceData->price}}  /booking )
										</label>
									@endif
									@endif
								@endforeach
				                </div>
               				@endif -->
			                <div class="personal_data_sec">
			                  	<h4 class="heading-inner-title">Personal data</h4>
			                  		<div class="row">
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="first_name" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." placeholder="First Name" class="form-control" value="{{ isset($data->personal_first_name) && !empty($data->personal_first_name) ? $data->personal_first_name : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="last_name" data-parsley-required="true" data-parsley-minlength="1" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." placeholder="Last Name" class="form-control" value="{{ isset($data->personal_last_name) && !empty($data->personal_last_name) ? $data->personal_last_name : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="address" data-parsley-required="true" placeholder="Address" class="form-control" value="{{ isset($data->personal_address) && !empty($data->personal_address) ? $data->personal_address : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="city" data-parsley-required="true" placeholder="City" class="form-control" value="{{ isset($data->personal_city) && !empty($data->personal_city) ? $data->personal_city : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="postal_code" placeholder="Postal Code" class="form-control" value="{{ isset($data->personal_postal_code) && !empty($data->personal_postal_code) ? $data->personal_postal_code : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
												<select class="form-select form-control" data-parsley-required="true" name="country_id" aria-label="Default select example">
													<option value = '' selected>Country</option>
													<?php if (count($countryData)) { foreach ($countryData as $key => $value) { ?>
														<option value="{{$value->id}}" <?php if($value->id == $data->personal_country_id){ echo 'selected'; } ?>>{{$value->name}}</option>
														<?php } ?>
													<?php } ?>
												</select>
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="phone_number" data-parsley-required="true" data-parsley-pattern="^[0-9 ]{8,15}$" data-parsley-pattern-message="Please enter valid mobile number" placeholder="Phone Number" class="form-control" value="{{ isset($data->personal_phone_number) && !empty($data->personal_phone_number) ? $data->personal_phone_number : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-6">
			                  				<div class="form-group">
			                  					<input type="text" name="email" data-parsley-required="true" data-parsley-pattern="^[a-z0-9][-a-z0-9._]+@([-a-z0-9]+[.])+[a-z]{2,5}$" data-parsley-pattern-message="Please enter valid email address" placeholder="Email" class="form-control" value="{{ isset($data->personal_email) && !empty($data->personal_email) ? $data->personal_email : '' }}"> 
			                  				</div>
			                  			</div>
			                  			<div class="col-md-12">
			                  				<div class="form-group">
			                  					<textarea id="" name="comment" rows="8" placeholder="Comments" class="form-control">{{ isset($data->personal_comment) && !empty($data->personal_comment) ? $data->personal_comment : '' }}</textarea>
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
												<select class="form-select form-control" name="is_agency" aria-label="Default select example">
													<option value='' selected>Are you an agency?</option>
													<option value="Yes" {{ isset($data->is_agency) && $data->is_agency == 'Yes' ? 'selected' : '' }}>Yes</option>
													<option value="No" {{ isset($data->is_agency) && $data->is_agency == 'No' ? 'selected' : '' }}>No</option>
												</select>
			                  				</div>
			                  			</div>
			                  		</div>
			                </div>
							<div class="personal_data_sec guest_form_data d-none">
			                  	<h4 class="heading-inner-title">Guests' data</h4>
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
										<div class="form-group">
											<input type="text" name="guest_address" value="" placeholder="Address" class="form-control"> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" name="guest_city" value="" placeholder="City" class="form-control"> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" name="guest_zipcode" value="" placeholder="Postal Code" class="form-control"> 
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
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" name="guest_phone_number" placeholder="Phone Number" class="form-control"> 
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" name="guest_email" placeholder="Email" class="form-control"> 
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
													<input type="checkbox" name="policy_read" value="Yes" data-parsley-required="true" class="form-control" <?php if(isset($data->policy_read) && $data->policy_read == "Yes"){ echo 'checked'; } ?>>
													<span class="checkmark"></span>
													I have read and I agree with the <a href="{{ route('web.term-of-use') }}" target="_blank">terms and conditions</a>, <a href="{{ route('web.privacy-policy') }}" target="_blank">privacy policy</a> and <a href="{{ route('web.cancellation-policy') }}" target="_blank">Cancellation Policy</a>
												</label>
												</div>
												<div class="position-cls">
												<label class="custom_checkbox">
													<input type="checkbox" name="send_special_offer" value="Yes" <?php if(isset($data->send_special_offer) && $data->send_special_offer == 'Yes'){ echo 'checked'; } ?>>
													<span class="checkmark"></span>
													Send me special offers and promotions
												</label>
												</div>
										</div>
									</div>
									<div class="book-now-sec">
										<input type="submit" class="btn primary_btn" value="Book Now"/>
									</div>
					            </div>
			              	</div>
			                
		        			</div>
		        			<div class="booking-right">
		        				<div class="product_dtl_bg">
			                  	<div class="product_dtl_price">
			                    	<h3>Your Trip</h3>
			                  	</div>
			                  	<input type="hidden" name="cart_id" class="cart_id" value="{{$data->id}}">
			                  	<input type="hidden" name="discount_id" class="discount_id" value="">
			                  	<input type="hidden" name="discount_amount" class="discount_amount" value="">
			                  	<input type="hidden" name="per_night_price" class="per_night_price" value="{{$data->per_night_price}}">
			                  	<input type="hidden" name="total_booking_amount" class="total_booking_amount" value="{{$data->total_booking_amount}}">
			                  	<input type="hidden" name="total_booking_amount_show" class="total_booking_amount_show" value="{{$data->total_booking_amount}}">
			                  	<input type="hidden" name="discount_code" class="discount_code" value="">
					                <div class="checkinout-sec">
					                    <div class="checkin_checkout">
					                      <div class="checkin_wrap">
					                        <label>Check in</label>
					                        <p>{{ date('M d, Y', strtotime($data->from_date))}}</p>
					                      </div>
					                      <div class="checkin_wrap">
					                        <label>Check out</label>
					                        <p>{{ date('M d, Y', strtotime($data->to_date))}}</p>
					                      </div>
					                    </div>
					                    <div class="checkout-dtl">
					                      <div class="guest-add">
					                        <div class="guest-main">
					                          <div class="guest-left">
					                            <h4>Who</h4>
					                            <div class="dropdown">
					                              <a href="#" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">{{ $data->no_of_adult_guest + $data->no_of_children_guest + $data->no_of_babies_guest + $data->no_of_pet. ' guests'}}</a>
					                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
					                                <div class="dropdown_cls">
					                                  <div class="dropdown_list">
					                                    <h4>Adults</h4>
					                                    <p>Ages 13 or above</p>
					                                  </div>
					                                  <div class="guest_count">
					                                    <div class="wrap">
					                                      <!-- <button type="button" id="sub" class="sub">-</button> -->
					                                      <input class="count" type="text" id="1" disabled value="{{$data->no_of_adult_guest}}" min="1" max="100" />
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
					                                      <input class="count" type="text" id="1" disabled value="{{$data->no_of_children_guest}}" min="1" max="100" />
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
					                                      <input class="count" type="text" id="1" disabled value="{{$data->no_of_babies_guest}}" min="1" max="100" />
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
					                                      <input class="count" type="text" id="1" disabled value="{{$data->no_of_pet}}" min="1" max="100" />
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
						                    <input type="text" name="coupon_code" placeholder="" value="{{$data->coupon_code}}" class="form-control coupon_code" disabled>
						                    <div class="subscribe-now">
						                      <!-- <a href="javascript:void(0);" class="btn primary_btn" disabled>Apply</a> -->
						                    </div>
						                  </div>
				                  	</div>
				                  	<div class="hotel-price-wrap">
				                  		<h3>Price Detail</h3>
					                    <ul class="price_structure">
					                      <li>
					                        <span class="left_price_p">NGN {{$data->per_night_price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> {{$data->total_days}} nights</span>
					                        <span class="right_price_p">NGN {{$data->per_night_price * $data->total_days}}</span>
					                      </li>
					                      <li>
					                        <span class="left_price_p">Caution Fee</span>
					                        <span class="right_price_p">NGN {{$data->getProperty->security_deposit_amount ?? 0}}</span>
					                      </li>
					                      <div class="optionalServicesAppend">
											@if(isset($data->selected_options) && !empty($data->selected_options))
											<?php $selected_options = json_decode($data->selected_options);
												foreach($selected_options as $options){ ?>
												<li>
													<span class="left_price_p">{{$options->name}}</span>
													<span class="right_price_p">NGN {{$options->value}}</span>
												</li>
											<?php } ?>
											@endif
											@if(isset($data->selected_options) && !empty($data->selected_options))
												<li class="discount_html"><span class="left_price_p">Discount Amount</span><span class="right_price_p"> - NGN {{ $data->discount_amount }}</span></li>
											@endif
					                      </div>
					                    </ul>
					                    <ul class="total_price">
					                      <li>
					                        <span class="left_price_p">Total</span>
					                        <span class="right_price_p total_booking_amount_shows">NGN {{$data->total_booking_amount + $data->optional_service_amount - $data->discount_amount }}</span>
					                      </li>
					                    </ul>
				                  	</div>
				                  	<div class="booknow-cls">
										<a href="javascript:void(0)" onclick="submitCheckoutForm()" class="btn primary_btn">Book Now</a>
					                </div>
				                </div>
	        					</form>
		        			</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>
@endsection

@section('script')
<script src="{{ asset('js/parsley.min.js') }}"></script>
<script type="text/javascript">
	$("#first-load").fadeOut()

	$('#checkoutBookingReserverForm').parsley();
    $(document).on('submit', "#checkoutBookingReserverForm", function(e) {
    	$("#first-load").fadeIn(1000);
		e.preventDefault();
		var user_id = "{{$userId ?? ''}}";
		// alert('user_id--'+user_id);
    	if (user_id != '') {
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

			$.ajax({
				url: '{{ route("web.checkout_form") }}',
				dataType: 'json',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				success: function(res) {
					$("#first-load").fadeOut();
					if (res.status === true) {
						if (res.data.authorization_url) {
							window.location.href = res.data.authorization_url;
						}
						toastr.success(res.message);
						$('#checkoutBookingReserverForm')[0].reset();
						$('#checkoutBookingReserverForm').parsley().reset();

						window.setTimeout(function() {
							window.location.href = '{{ route("web.home") }}';
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
		} else {
			$('#exampleModal').modal('show');
		}
    });

    function submitCheckoutForm() {
		$("#checkoutBookingReserverForm").submit();
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

			if (coupon_code) {
				var formData = new FormData(); // Currently empty
	      var token = "{{ csrf_token() }}";
	      formData.append('_token', token);
	      formData.append('coupon_code', coupon_code);

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
	            var total_booking_amount = $('.total_booking_amount').val();
	            var discount_percent = res.data.percentage;
	            var total_discount_amount = parseInt(per_night_price) * parseInt(discount_percent) / 100;
	            var total_booking_amount_shows = parseInt(total_booking_amount) - parseInt(total_discount_amount);
	            $('.discount_amount').val(total_discount_amount);
	            var discount_html = '<li class="discount_html"><span class="left_price_p">Discount Amount</span><span class="right_price_p"> - NGN '+total_discount_amount+'</span></li>';
	            $('.price_structure').append(discount_html);

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
	</script>
@endsection