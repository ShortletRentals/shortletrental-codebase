@if ($data && $data->status == true)
	<?php if (count($data->data)) { foreach ($data->data as $key => $value) { ?>
			<div class="hotel-listing">
				<div class="hotel-img">
					<img src="<?php if (isset($value->get_property)) { echo $value->get_property->image; } ?>" alt="">
				</div>
				<div class="hotel-cont">
					<div class="my-booking-title">
						<h5><?php if (isset($value->get_property)) { echo $value->get_property->title; } ?></h5>
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
		                  <a href="#">#{{$value->booking_id}}</a>
		                </li>
		                <!-- <li class="meta_single">
		                  <span>Lekki - Apartment</span>
		                </li> -->
		            </ul>
		            <div class="my-booking-price">
		            	<div class="price-wrap">
		            		<ul>
								<li>
									<span class="left_price_p">NGN {{$value->per_night_price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> {{$value->total_days}} nights =</span>
									<span class="right_price_p">NGN {{$value->total_booking_amount}}</span>
								</li>
								@if($value->selected_options)
									<li>
										<span>Optional Services</span>
									</li>
									<div class="opt-serv-main">
									<div class="opt-serv-cls">
										<?php $selected_options = json_decode($value->selected_options); foreach ($selected_options as $k => $v) { ?>	
											<li>
												<span class="left_price_p">NGN {{$v->value}} <span>{{$v->name}}</span></span>
											</li>
										<?php } ?>
									</div>
									<li>
										<span class="right_price_p">NGN {{$value->optional_service_amount}}</span>
									</li>
								</div>
								@endif
								<li>
									<span class="left_price_p">Grand Total</span>
									<span class="right_price_p">NGN {{$value->optional_service_amount + $value->total_booking_amount}}</span>
								</li>
		                    </ul>
		            	</div>
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
		                	<a href="{{url('/property-detail').'/'.$value->property_id}}" class="btn primary_btn me-3">Re-Book</a>
							@if($value->booking_status == 'Completed-Booking')
		                		<a href="javascript:void(0);" onclick="rate_order({{$value->id}})" class="btn primary_btn">Rate Now</a>
							@endif
		                </div>
		            @endif

		            @if($value->booking_status == 'Not-confirmed-by-Host')
			            <div class="order-btn d-flex">
		                	<a href="javascript:void(0);" onclick="cancelBooking({{$value->id}})" class="btn primary_btn">Cancel</a>
		                </div>
		            @endif
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
	<span>No record found!</span>
	<?php } ?>
@else
<div class="data_not_found">
  <img src="{{ URL::asset('assets/web/img/Data_not_found.png')}}" alt="">
</div>
@endif