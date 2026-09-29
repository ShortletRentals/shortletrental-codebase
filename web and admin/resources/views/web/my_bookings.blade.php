@extends('layouts.web.master')

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
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			@include('layouts.web.leftbar_itms')
	        			<div class="sidebar_r">
	        				<div class="inner-title">
	        					<h2 class="heading-inner-title">My Booking</h2>
	        				</div>
	        				<div class="my-booking-sec">
	        					<div class=tabing_top_c>
	        						<div class="tabing_head">
	        							<ul class="nav" id="myTab" role="tablist">
			                                <li class="nav-item" role="presentation">
			                                  <a class="nav-link active" href="javascript:void(0)" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" role="tab" aria-controls="all-tab-pane" aria-selected="true">All Bookings</a>
			                                </li>
			                                <li class="nav-item" role="presentation">
			                                  <a class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-tab-pane" onclick="getBookings('new_booking')" role="tab" aria-controls="new-tab-pane" aria-selected="false">New Bookings</a>
			                                </li>
			                                <li class="nav-item" role="presentation">
			                                  <a class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-tab-pane" role="tab" aria-controls="completed-tab-pane" onclick="getBookings('complete_booking')" aria-selected="false">Completed Bookings</a>
			                                </li>
			                                <li class="nav-item" role="presentation">
			                                  <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled-tab-pane" role="tab" aria-controls="cancelled-tab-pane" onclick="getBookings('cancel_booking')" aria-selected="false">Cancelled Bookings</a>
			                                </li>   
			                            </ul>
	        						</div>
	        						<div class="tabing_body_c">
	        							 <div class="tab-content" id="myTabContent">
				                            <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
												<div class="my-booking">

													<?php if (isset($data->data) && count($data->data)) { foreach ($data->data as $key => $value) { //dd($value->id); ?>


															<div class="hotel-listing">
									        					<div class="hotel-img">
									        						<img src="<?php if (isset($value->get_property)) { echo $value->get_property->image; } ?>" alt="">
									        					</div>
									        					<div class="hotel-cont">
									        						<div class="my-booking-tit-cls">
										        						<div class="my-booking-title">
										        							<h5> <?php if (isset($value->get_property)) { echo $value->get_property->title; } ?></h5>
										        							<div class="booking-status">
																				<a href="#" data-booking_id="{{ $value->booking_id }}" title="Share Booking" class="btn primary_btn share_booking_btn ms-3"><span><img src="{{ URL::asset('assets/web/img/shortlet/share-icon.svg') }}"> </span>  Share </a>
										        								<span class="btn secondary_btn">{{ str_replace("-"," ",$value->booking_status)}}</span>
										        							</div>
										        						</div>
										        						<ul class="meta_list">
										        							<?php if (isset($value->get_property) && !empty($value->get_property->get_host_details)) { ?>
															                    <li class="meta_single">
															                      <a href="{{url('/chat?booking=').$value->booking_id}}">Host - {{$value->get_property->get_host_details[0]->name}}</a>
															                    </li>
															                <?php } ?>
														                    <li class="meta_single">
														                      <span>#{{$value->booking_id}}</span>
														                    </li>
														                </ul>
														            </div>
													                <div class="my-booking-price">
													                	<div class="price-wrap">
													                		<ul>
														                      <li>
														                        <span class="left_price_p">NGN {{$value->per_night_price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> {{$value->total_days}} nights =</span>
														                        <span class="right_price_p">NGN {{$value->per_night_price*$value->total_days}}</span>
														                      </li>
														                      @if($value->selected_options)
														                      <?php $selected_options = json_decode($value->selected_options);
														                      if(isset($selected_options) && !empty($selected_options)){ ?>
															                      <li>
															                      	<span>Optional Services</span>
															                      </li>
																				  <div class="opt-serv-main">
																					<div class="opt-serv-cls">
																						<?php foreach ($selected_options as $k => $v) { ?>
																							<li>
																								<span class="left_price_p">NGN {{$v->value}} <span>{{$v->name}}</span></span>
																							</li>
																						<?php } ?>
																					</div>
																					<li>
																						<span class="right_price_p">NGN {{$value->optional_service_amount}}</span>
																					</li>
																				</div>
																			<?php } ?>
														                      @endif

																			 

																				<li>
															                      	<span>Caution Fee</span>
																					  <span class="right_price_p">NGN {{$value->security_deposite}}</span>
															                      </li>
																				 

																			@if($value->discount_amount)
																				<li>
																					<span>Discount Amount</span>
																					<span class="right_price_p">- NGN {{$value->discount_amount}}</span>
																				</li>
																			@endif
														                    </ul>
													                	</div>
													                </div>
													                <div class="my-booking-bottom-cls">
													                	<div class="booking_grand_total">
														                	<ul>
															                	<li>
														                      		<span class="left_price_p">Grand Total</span>
														                      		<span class="right_price_p">NGN {{$value->optional_service_amount + $value->total_booking_amount - $value->discount_amount}}</span>
														                      	</li>
														                    </ul>
														                </div>
													                	<div class="booking-detail-wrap">
														                	<div class="checkin-checkout">
														                		<h6>Check in - Check out</h6>
														                		<p>{{ date('M d, Y', strtotime($value->from_date))}} / {{ date('M d, Y', strtotime($value->to_date))}}</p>
														                	</div>
														                	<div class="checkin-checkout">
														                		<h6>Who</h6>
														                		<p>{{ $value->no_of_adult_guest+$value->no_of_children_guest+$value->no_of_babies_guest+$value->no_of_pet }} Guests</p>
														                	</div>
														                	<div class="checkin-checkout">
														                		<h6>Booking Date & Time</h6>
														                		<p>{{ date('M d, Y h:i:s', strtotime($value->created_at))}}</p>
														                	</div>
														                </div>

														                @if(count($value->get_loyalty_points))
															                <div class="booking-detail-wrap">

															                	<?php foreach ($value->get_loyalty_points as $k => $v) { ?>
																                	<div class="checkin-checkout">

																                		@if ($v->type == 'Credit')
																                			<h6>Loyalty Points Earned</h6>
																                			<p>{{ $v->points }}</p>

																                		@else
																                			<h6>Loyalty Points Spent</h6>
																                			<p>{{ $v->points }}</p>
																                		@endif
																                	</div>
																                <?php } ?>
															                </div>
																		@endif

														                @if($value->booking_status == 'Cancelled-Booking' || $value->booking_status == 'Completed-Booking')
															                <div class="order-btn d-flex">
															                	<!-- <a href="javascript:void(0);" onclick="rebook_order({{$value->id}})" class="btn primary_btn me-3">Re-Book</a> -->
																				@if($value->booking_status == 'Completed-Booking')
															                		<a href="javascript:void(0);" onclick="rate_order({{$value->id}})" class="btn primary_btn">Rate Now</a>
																				@endif
															                </div>
															            @endif

															            @if($value->booking_status == 'Not-confirmed-by-Host' || $value->booking_status == 'Confirmed-by-Host')
																            <div class="order-btn d-flex">
															                	<a href="javascript:void(0);" data-policy_data="{!! htmlentities($value->get_property->cancellation_policy) !!}" data-id="{{$value->id}}" class="btn primary_btn showBookingCancelPolicy">Cancel</a>
															                </div>
															            @endif
															           </div>
									        					</div>
									        				</div>
														<?php } ?>
									                <?php } else { ?>
									                    <div class="data_not_found">
													      <img src="{{ URL::asset('assets/web/img/Data_not_found.png')}}" alt="">
													    </div>
									                <?php } ?>
												</div>
				                            </div>
				                            <div class="tab-pane fade" id="new-tab-pane" role="tabpanel" aria-labelledby="new-tab" tabindex="0">
				                            	<div class="my-booking new_booking">

												</div>
				                            </div>
				                            <div class="tab-pane fade" id="completed-tab-pane" role="tabpanel" aria-labelledby="completed-tab" tabindex="0">
				                                <div class="my-booking complete_booking">
							        				
				                            	</div>
				                            </div>
				                            <div class="tab-pane fade" id="cancelled-tab-pane" role="tabpanel" aria-labelledby="cancelled-tab" tabindex="0">
				                                <div class="my-booking cancel_booking">
							        				
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
	    </section>
    </main>



@endsection

@section('script')
	<script type="text/javascript">

		$(document).on('click','.showBookingCancelPolicy',function(){
			$('#bookingCancelPolicyModal').modal('show');
			var policy = $(this).data('policy_data');
			@if(isset($value) && $value->get_property->refund=='YES')
			if(policy.length > 0)
			{
				$('.shwoCancelPolicy').html(policy);
			}
			else
			{
				$('.shwoCancelPolicy').html('This is the cancelation policy: In case of cancellation the following charges will apply:<br/>'+
					'– Up to 14 days before the arrival date, free cancellation.-<br/>'+
					'– 13 days before the arrival date, 50% of the booking amount.-<br/>'+
					'– In case of a no show: the full amount of the booking <br/>'+
					'– For Bookings that fall during festive periods, (Valentine, Easter, Christmas, ) 50% of booking amount will only be refunded if cancelled regardless of when it was booked.No cancellation policy applied in the booking<br/><br/>'+
					'– To avoid loosing any money paid, you can move your check in date to another date that fits your schedule with the same accommodation booked. Notice must be given minimum 3 days before check in date.'
					);
			}
			@else
			$('.shwoCancelPolicy').html('Not refundable.');
			@endif
			$('.checkBookingPolicy').val($(this).data('id'));			
		});	
		
		$(document).on('click','.bookingCancelModalClose',function(){
			$('#bookingCancelPolicyModal').modal('hide');
		})

		$(document).on('click','.share_booking_btn',function(e){
			e.preventDefault();
			var booking_id = $(this).data('booking_id');
			if(booking_id != ''){
				$('.share_booking_id').val(booking_id);
			}
			$('#shareBookingModal').modal('show');
		});

		$(document).on('click','.booingCancel',function(){
			if($('.checkBookingPolicy').is(':checked')){
				cancelBooking($('.checkBookingPolicy').val());
			}
			else{
				alert('Please accept terms and conditions')
			}
		});

		$("#first-load").fadeOut(); 
		function cancelBooking(order_id) {

			if (order_id) {

				//if (confirm("Are you sure you want to cancel this booking?") == true) {
					var formData = new FormData(); // Currently empty
					var token = "{{ csrf_token() }}";
					formData.append('_token', token);
					formData.append('order_id', order_id);

					$.ajax({
						url: '{{ route("web.cancelBooking") }}',
						dataType: 'json',
						data: formData,
						type: 'POST',
						cache: false,
						contentType: false,
						processData: false,
						success: function(res) {

						  if (res.status === true) {
						    toastr.success(res.message);
						    window.location.reload();

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
				//}

			} else {
				toastr.error('Invalid selection');
			}
		}

		function rebook_order(order_id) {
			
			if (order_id) {

				if (confirm("Are you sure you want to rebook this booking?") == true) {
					var formData = new FormData(); // Currently empty
					var token = "{{ csrf_token() }}";
					formData.append('_token', token);
					formData.append('order_id', order_id);

					$.ajax({
						url: '{{ route("web.reBooking") }}',
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

			} else {
				toastr.error('Invalid selection');
			}
		}

		function rate_order(order_id) {
			// toastr.error('Under working!!!');
			$('#rate_order_id').val(order_id);
			$('#ratingModal').modal('show');
		}

		function getBookings(booking_status) {
			var status = '';
			$("#first-load").fadeIn(1000);

			if (booking_status == 'new_booking') {
				status = 'Not-confirmed-by-Host';

			} else if (booking_status == 'complete_booking') {
				status = 'Completed-Booking';

			} else if (booking_status == 'cancel_booking') {
				status = 'Cancelled-Booking';
			}
			var formData = new FormData(); // Currently empty
			var token = "{{ csrf_token() }}";
			formData.append('_token', token);
			formData.append('status', status);

			$.ajax({
				url: '{{ route("web.filtered_bookings") }}',
				dataType: 'html',
				data: formData,
				type: 'POST',
				cache: false,
				contentType: false,
				processData: false,
				success: function(res) {
				  $('.'+booking_status).html(res);
				  $("#first-load").fadeOut()
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
	</script>
@endsection