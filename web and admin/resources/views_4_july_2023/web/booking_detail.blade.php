@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');
    // dd(gettype($auth_user));
    if (isset($auth_user) && $is_guest != 1) {
      $userId = $auth_user->data->id;
    }
    // dd($data->getProperty);
    // dd($data['featuredProperty']);
?>
@section('content')
    <main>
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			<!-- <div class="sidebar_l">
		        			<div class="sidebar-link">
		        				<ul>
		        					<li><a href="#">My Account</a></li>
		        					<li><a href="#">My Cards</a></li>
		        					<li><a href="#" class="active">My Bookings</a></li>
		        					<li><a href="#">My Favorites</a></li>
		        					<li><a href="#">Notification</a></li>
		        					<li><a href="#">Logout</a></li>
		        				</ul>
		        			</div>
		        		</div> -->
	        			<div class="sidebar_r">
	        				<div class="inner-title  d-flex align-items-center">
	        					<h2 class="heading-inner-title mb-0">Booking Detail</h2>
	        					<div class="ms-auto order-id">
	        						<h6>{{ $data->booking_id }}</h6>
	        					</div>
	        				</div>
	        				<div class="my-booking-sec">
	        					<!-- <div class="hotel-listing">
		        					<div class="hotel-img">
		        						<img src="{{$data->getProperty->image}}" alt="">
		        					</div>
		        					<div class="hotel-cont">
		        						<h5>{{$data->getProperty->title}}</h5>
		        						<p>{{$data->getProperty->get_property_address[0]->address ?? ''}}</p>
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
						                      <span>{{isset($data->getProperty->total_rating) ? $data->getProperty->total_rating : 0}} Reviews</span>
						                    </li>
						                </ul>
					                	<ul class="meta_list">
											@if(isset($data->getProperty->get_user->is_super_host) && $data->getProperty->get_user->is_super_host == 'Yes')
							                <li class="meta_single">
							                  <span class="pro-ic">
							                    <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
							                  </span>
							                  <span>Superhost</span>
							                </li>
											@endif
						            	</ul>
		        					</div>
		        				</div> -->
		    				   	
								<div class="hotel-listing">
									<div class="hotel-img">
										<img src="<?php if (isset($data->getProperty)) { echo $data->getProperty->image; } ?>" alt="">
									</div>
									<div class="hotel-cont">
										<div class="my-booking-title">
											<h5> <?php if (isset($data->getProperty)) { echo $data->getProperty->title; } ?></h5>
											<div class="booking-status">
												<span class="btn secondary_btn">{{ str_replace("-"," ",$data->booking_status)}}</span>
											</div>
											
										</div>
										<ul class="meta_list">
											<?php if (isset($data->getProperty) && !empty($data->getProperty->get_host_details)) { ?>
												<li class="meta_single">
													<a href="{{url('/chat?booking=').$data->booking_id}}">Host - {{$data->getProperty->get_host_details[0]->name}}</a>
												</li>
											<?php } ?>
											<li class="meta_single">
												<span>#{{$data->booking_id}}</span>
											</li>
										</ul>
										<div class="my-booking-price">
											<div class="price-wrap">
												<ul>
													<li>
													<span class="left_price_p">NGN {{$data->per_night_price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> {{$data->total_days}} nights =</span>
													<span class="right_price_p">NGN {{$data->total_booking_amount}}</span>
													</li>
													@if($data->selected_options)
														<li>
														<span>Optional Services</span>
														</li>
														<div class="opt-serv-main">
														<div class="opt-serv-cls">
															<?php $selected_options = json_decode($data->selected_options); foreach ($selected_options as $k => $v) { ?>
																
																	<li>
																		<span class="left_price_p">NGN {{$v->value}} <span>{{$v->name}}</span></span>
																	</li>
															<?php } ?>
														</div>
														<li>
															<span class="right_price_p">NGN {{$data->optional_service_amount}}</span>
														</li>
													</div>
													@endif

													<li>
														<span class="left_price_p">Grand Total</span>
														<span class="right_price_p">NGN {{$data->optional_service_amount + $data->total_booking_amount}}</span>
													</li>
												</ul>
											</div>
										</div>
										<div class="booking-detail-wrap">
											<div class="checkin-checkout">
												<h6>Check in - Check out</h6>
												<p>{{ date('M d, Y', strtotime($data->from_date))}} / {{ date('M d, Y', strtotime($data->to_date))}}</p>
											</div>
											<div class="checkin-checkout">
												<h6>Who</h6>
												<p>{{ $data->no_of_adult_guest+$data->no_of_children_guest+$data->no_of_babies_guest+$data->no_of_pet }} Guests</p>
											</div>
											<div class="checkin-checkout">
												<h6>Booking Date & Time</h6>
												<p>{{ date('M d, Y h:i:s', strtotime($data->created_at))}}</p>
											</div>
										</div>

										@if($data->get_loyalty_points != null && count($data->get_loyalty_points))
											<div class="booking-detail-wrap">

												<?php foreach ($data->get_loyalty_points as $k => $v) { ?>
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