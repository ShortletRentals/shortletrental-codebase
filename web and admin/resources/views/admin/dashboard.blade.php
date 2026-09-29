@extends('layouts.master')
@section('content')
	<link href="{{ URL::asset('assets/plugins/highcharts/css/highcharts-white.css') }}" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
	<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content ">	

			<div class="row dashboard_card">
				<div class="col-md-9">
					<div class="card">
						<div class="card-header">
							<h6>Bookings</h6>
						</div>
						<div class="card-body pt-1">
							<div class="top">
								<div class="selectWidget show_graph_text"> 
									<select class="resizeselect selectFilterWidget bookingChange" id="bookingChange" >
										<option value="this_month">This month</option>
										<option value="last_month">Last month</option>
										<option value="this_year" selected="">Last 12 Months</option>
									</select>
									<p style="color:black;">Booking amount in millions</p>
								</div>
							</div>
							<div class="dashboard-chart2" id="dashboard-chart2"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card radius-10 w-100">
						<div class="card-body pt-1">
							<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
								<div class="cabeceraBloque title-sec">
									<p class="incoming_bookings">Occupancy</p>
									<div class="top">
										<div class="selectWidget"> 
											<select class="resizeselect selectFilterWidget occupancyFilter" id="occupancyFilter" >
												<option value="today">Today</option>
												<option value="this_month" selected="">This month</option>
												<option value="this_year" >This Year</option>
											</select>
										</div>
									</div>
								</div> 
								<div class="contentBloque">
									<div class="bodyBloque noHidden">
										<div class="month_to_today_date_div ml-2" style="text-align:center; font-size:small;"><p>1 to {{ date('d F Y') }}</p></div>
											<div class="bloque">
												<div class="bodyBloque noHidden">
													<div class="mt-4" id="chart6"></div>
												</div>
											</div>
										<div class="bloque">
										<div class="footerBlock">
											<div class="blockImg">
												<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M5.85851 0L0.097168 6V16H11.6198V6L5.85851 0ZM9.6994 14H6.81873V11H4.89828V14H2.01761V6.83L5.85851 2.83L9.6994 6.83V14ZM6.81873 9H4.89828V7H6.81873V9ZM15.4607 16V4.35L11.2838 0H8.56633L13.5403 5.18V16H15.4607ZM19.3016 16V2.69L16.7186 0H14.0012L17.3812 3.52V16H19.3016Z" fill="#EF5F48" fill-opacity="0.8"></path>
												</svg>
											</div>
        									<div class="textAlojamiento">
												<a href="{{ route('admin.property.index', 'short') }}" onclick="addAnalyticsOcupacion('activos');" target="_blank">Accommodations now active<b> {{ $active_property }}</b></a></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row dashboard_card">
				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body">
							<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
								<div class="top">
									<div class="selectWidget"> 
										<select class="resizeselect selectFilterWidget filterWidgetReservasEntrantes" id="filterWidgetReservasEntrantes" >
											<option value="today">Today</option>
											<option value="last_7_days" selected="">Last 7 days</option>
											<option value="last_30_days">Last 30 days</option>
										</select>
									</div>
								</div>
								<div class="contentBloque">
									<div class="cabeceraBloque">
										<div class="blockClock">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M9.98903 0C4.46903 0 -0.000976562 4.48 -0.000976562 10C-0.000976562 15.52 4.46903 20 9.98903 20C15.519 20 19.999 15.52 19.999 10C19.999 4.48 15.519 0 9.98903 0ZM9.99903 18C5.57903 18 1.99903 14.42 1.99903 10C1.99903 5.58 5.57903 2 9.99903 2C14.419 2 17.999 5.58 17.999 10C17.999 14.42 14.419 18 9.99903 18ZM10.499 5H8.99903V11L14.249 14.15L14.999 12.92L10.499 10.25V5Z" fill="white"></path>
											</svg>
										</div>
										<p class="incoming_bookings_count">Incoming bookings ({{$booking['confirmed_bookings'] + $booking['incoming_bookings']}})</p>
									</div>
									<div class="bodyBloque noHidden incoming_bookings_inner_div">
										@if($booking['confirmed_bookings'] >0 || $booking['incoming_bookings'] > 0)
										<div class="bloque">
											<a href="javascript:;" class="insideBloque" onclick="incoming_booking_confirm('assigned')">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber">
													0
												</div>
												<div class="informacion">
													<p class="title">To be assigned</p> 
													<p class="descripcion">Unavailable dates and Airbnb request to book</p>
												</div>
											</a>
										</div>
										<div class="bloque">
											<a href="javascript:;" class="insideBloque" onclick="incoming_booking_confirm('instant')">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber incoming_bookings_div_count">
													{{$booking['incoming_bookings']}}
												</div>
												<div class="informacion">
													<p class="title">Instant Booking</p> 
													<p class="descripcion"><!--HomeAway confirmed bookings--> Paid and confirmed bookings</p>
												</div>
											</a>
										</div>
										<div class="bloque">
											<a href="javascript:;" class="insideBloque incoming_booking_confirm" onclick="incoming_booking_confirm('confirm')">
											<!-- <a href="{{ route('admin.booking.index') }}" class="insideBloque"> -->
												<div class="division redBarra"></div>
												<div class="cantidad redNumber confirmed_bookings_div">
													{{$booking['confirmed_bookings']}}
												</div>
												<div class="informacion">
													<p class="title">To be confirmed</p> 
													<p class="descripcion">New pre-bookings</p>
												</div>
											</a>
										</div>
										<div class="bloque">
											<a href="javascript:;" class="insideBloque" onclick="incoming_booking_confirm('request')">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber">
													0
												</div>
												<div class="informacion">
													<p class="title">Information requests</p> 
													<p class="descripcion">Requests to review</p>
												</div>
											</a>
										</div>
										@else
											<div class="incoming_bookings_div" style="text-align: center;color:black;"><strong>You have no incoming booking requests for the selected time period.</strong></div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body">
							<div id="renderWidgetAccionesPendientes" class="twigWidgetBloques">
								<div class="top">
									<div class="selectWidget">     
										<select class="resizeselect selectFilterWidget filterWidgetAccionesPendientes" id="filterWidgetAccionesPendientes" >
											<option value="today">Today</option>
											<option value="last_7_days" selected="">Last 7 days</option>
											<option value="last_30_days">Last 30 days</option>
										</select>
									</div>
								</div>
								<div class="contentBloque">
									<div class="cabeceraBloque">
										<div class="blockClock">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M10.0003 20C11.1003 20 12.0003 19.1 12.0003 18H8.00028C8.00028 19.1 8.90028 20 10.0003 20ZM16.0003 14V9C16.0003 5.93 14.3703 3.36 11.5003 2.68V2C11.5003 1.17 10.8303 0.5 10.0003 0.5C9.17028 0.5 8.50028 1.17 8.50028 2V2.68C5.64028 3.36 4.00028 5.92 4.00028 9V14L2.00028 16V17H18.0003V16L16.0003 14ZM14.0003 15H6.00028V9C6.00028 6.52 7.51028 4.5 10.0003 4.5C12.4903 4.5 14.0003 6.52 14.0003 9V15ZM5.58028 2.08L4.15028 0.65C1.75027 2.48 0.170274 5.3 0.0302734 8.5H2.03028C2.18028 5.85 3.54028 3.53 5.58028 2.08ZM17.9703 8.5H19.9703C19.8203 5.3 18.2403 2.48 15.8503 0.65L14.4303 2.08C16.4503 3.53 17.8203 5.85 17.9703 8.5Z" fill="white"></path>
											</svg>
										</div>
										<p class="total_pending_actions_count">Pending actions ({{ $total_pending_actions_count }})</p>
									</div> 
									<div class="bodyBloque hidden">
										<div class="bloque">

											<a href="{{ url('admin/booking?upcoming_booking=Pending-payment') }}" class="insideBloque">
												<div class="division redBarra"></div>
												<div class="cantidad redNumber seven_days_pending_payments_count">
													{{ $seven_days_pending_payments_count }}
												</div>
												<div class="informacion">
													<p class="title">Pending Payments</p> 
													<p class="descripcion seven_days_pending_payments_sum">{{ $seven_days_pending_payments_sum }} NGN to receive</p>
												</div>
											</a>
										</div>
										<!-- <div class="bloque">                         
											<a href="{{ route('admin.chat.index') }}" class="insideBloque">
												<div class="division redBarra"></div>
												<div class="cantidad redNumber">
													10
												</div>
												<div class="informacion">
													<p class="title">Unread messages</p> 
													<p class="descripcion">In bookings and requests</p>
												</div>
											</a>
										</div> -->
										<div class="bloque">
											<!-- <a href="{{ url('admin/booking?check_in=No') }}" class="insideBloque"> -->
											<a href="javascript:;" class="insideBloque pending_actions" onclick="pending_actions()">
											<!-- <a href="{{ route('admin.booking.index') }}" class="insideBloque"> -->
												<div class="division redBarra"></div>
												<div class="cantidad redNumber seven_days_pending_payments_checkin_count">
													{{ $seven_days_pending_payments_checkin_count }}
												</div>
												<div class="informacion">
													<p class="title">Check-in to be validated</p> 
													<p class="descripcion">Check-in online requests</p>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body">
							<div id="renderWidgetProximosCheckins" class="twigWidgetBloques">
								<div class="top">
									<div class="selectWidget">       
										<select class="resizeselect selectFilterWidget filterWidgetProximosCheckins" id="filterWidgetProximosCheckins" style="width: 97.2812px;">
											<option value="today">Today</option>
											<option value="next_7_days" selected="">Next 7 days</option>
											<option value="next_30_days">Next 30 days</option>
										</select>
									</div>
								</div>
								<div class="contentbloque">
										<div class="cabeceraBloque upcommingBoking">
							
											<div class="iconBlock">
												<svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.402 3.96667L7.93939 5.355L10.6557 7.93333H-0.000488281V9.91667H10.6557L7.93939 12.495L9.402 13.8833L14.6256 8.925L9.402 3.96667ZM18.8045 15.8667H10.4467V17.85H18.8045C19.9537 17.85 20.8939 16.9575 20.8939 15.8667V1.98333C20.8939 0.8925 19.9537 0 18.8045 0H10.4467V1.98333H18.8045V15.8667Z" fill="white"></path>
												</svg>
											</div>
											<p>Upcoming check-ins <span class="upcoming_bookings_totle">({{count($next_upcoming_bookings)}})</span></p>
										
									</div>
								
									<div class="bodyBloque hidden upcoming_bookings_main_div">
										@if(count($next_upcoming_bookings) > 0)
											<div id="wlvProximosCheckins" class="WAjaxListView table-responsive ">
												<!-- <table class="wListView" id="example1" style="display:" cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
													<tbody>
														@foreach($next_upcoming_bookings as $next_booking)
															<tr>
																<td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16237751&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
																	<a href="{{ route('admin.property.show',['id'=>$next_booking->id]) }}">
																		<div class="genericRow">
																			<div class="contentRow">
																				<span class="clientName columnRight">{{ $next_booking->property_title }}</span>
																				<span class="dateCheckin genericFormat columnLeft">enter {{ date('d/m', strtotime($next_booking->from_date)) }}</span>
																			</div>
																			<div class="contentRow">
																				<span class="propertyName genericFormat columnRight">{{ $next_booking->property_description }}</span>
																				<span class="timeCheckin genericFormat columnLeft">{{ date('h:i', strtotime($next_booking->from_date)) }}</span>
																			</div>
																		</div>
																	</a>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table> -->
												<!-- <table id="example1" class="table table-striped table-bordered" style="width:100%"> -->
												<table id="example1" class="wListView" style="display:" cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
													<thead>
														<tr>
															<th></th>
														</tr>
													</thead>
													<tbody>
														@foreach($next_upcoming_bookings as $next_booking)

															<tr>
																<td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16237751&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
																	<a href="{{ route('admin.booking.show',['id'=>$next_booking->id]) }}">
																		<div class="genericRow">
																			<div class="contentRow">
																				<span class="clientName columnRight">{{ $next_booking->property_title }}</span>
																				<span class="dateCheckin genericFormat columnLeft">enter {{ date('d/m', strtotime($next_booking->from_date)) }}</span>
																			</div>
																			<div class="contentRow">
																				<span class="propertyName genericFormat columnRight">{{ $next_booking->property_description }}</span>
																				<span class="timeCheckin genericFormat columnLeft">{{ date('h:i', strtotime($next_booking->from_date)) }}</span>
																			</div>
																		</div>
																	</a>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div> 
											<!-- @if($total_upcoming_pages > 1)
											<div class="pagination_div">
												<ul>
													@for($i = 1; $i <= $total_upcoming_pages; $i++)
														<li><a href="javascript::void(0)" onClick="goToPage({{$i}})">{{$i}}</a></li>
													@endfor
												</ul>
											</div>
											@endif -->
										@else
											<div class="no_upcoming_bookings" style="text-align: center;color:black;"><strong>You have no upcoming check-ins requests for the selected time period.</strong></div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body">
							<div id="renderWidgetProximosCheckins" class="twigWidgetBloques">
								<div class="top">
									<div class="selectWidget">       
										<span><strong>Today</strong></span>
									</div>
								</div>
								<div class="contentbloque">
									<div class="cabeceraBloque currentBooking">
										<div class="iconBlock">
											<svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M13.1079 3.13833L19.5545 8.38833V17.5H16.9759V10.5H9.24001V17.5H6.66139V8.38833L13.1079 3.13833ZM13.1079 0L0.214844 10.5H4.08277V19.8333H11.8186V12.8333H14.3972V19.8333H22.1331V10.5H26.001L13.1079 0Z" fill="white"></path>
											</svg>
										</div>
										<p>Current bookings ({{count($booking['current_bookings'])}})</p>
									</div>
									<div class="bodyBloque hidden surrent_bookings_main_div">
										@if(count($booking['current_bookings']) > 0)
										<div id="wlvProximosCheckins" class="WAjaxListView ">
											<table id="current_bookings_datatable" class="wListView" style="display:" cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
												<thead>
													<tr>
														<th></th>
													</tr>
												</thead>
												<tbody>
													@foreach($booking['current_bookings'] as $current)
														<tr>
															<td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16237751&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
																<a href="{{ route('admin.booking.show',['id'=>$current->property_id]) }}">
																	<div class="genericRow">
																		<div class="contentRow">
																			<span class="clientName columnRight">{{ $current->property_title }}</span>
																			<span class="dateCheckin genericFormat columnLeft">exit {{ date('d/m', strtotime($current->to_date)) }}</span>
																		</div>
																		<div class="contentRow">
																			<span class="propertyName genericFormat columnRight">{{ $current->property_description }}</span>
																			<span class="timeCheckin genericFormat columnLeft">{{ date('h:i', strtotime($current->to_date)) }}</span>
																		</div>
																	</div>
																</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div> 
										@else
											<div class="current_bookings" style="text-align: center;color:black;"><strong>No Current bookings available.</strong></div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>


			<div class="row">
				<div class="col-md-3">
					<a href="{{ route('admin.customer.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$customer}}</h5>
								<div class="ms-auto">
									<i class='bx bx-group fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Registered Guest/Customer</p>
								<!-- <p class="mb-0 ms-auto">+4.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="{{ route('admin.host.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$host}}</h5>
								<div class="ms-auto">
									<i class='bx bx-server fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Registered Hosts</p>
								<!-- <p class="mb-0 ms-auto">+1.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="{{ route('admin.property.index', 'short') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$property}}</h5>
								<div class="ms-auto">
									<i class='bx bx-building fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Accommodations</p>
								<!-- <p class="mb-0 ms-auto">+5.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="{{ route('admin.booking.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$total_booking}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Bookings</p>
								<!-- <p class="mb-0 ms-auto">+2.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>

				<div class="col-md-3">
					<!-- <a href="{{ route('admin.booking.index') }}"> -->
					<a href="{{ url('admin/booking?booking=Website') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$website_bookings}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Website Bookings</p>
								<!-- <p class="mb-0 ms-auto">+4.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<!-- <a href="{{ route('admin.booking.index') }}"> -->
					<a href="{{ url('admin/booking?booking=Mobile') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$mobile_bookings}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Mobile App Bookings</p>
								<!-- <p class="mb-0 ms-auto">+1.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<!-- <a href="{{ route('admin.booking.index', 'Guest_bookings') }}"> -->
					<a href="{{ url('admin/booking?booking=Guest_bookings') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$guest_bookings}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Unregistered Guest Bookings</p>
								<!-- <p class="mb-0 ms-auto">+5.2%<span><i class='bx bx-up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<!-- <a href="{{ route('admin.booking.index') }}"> -->
					<a href="{{ url('admin/booking?booking=Customer_bookings') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$customer_bookings}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Customer Bookings</p>
								<!-- <p class="mb-0 ms-auto">+2.2%<span><i class='bx bx -up-arrow-alt'></i></span></p> -->
							</div>
						</div>
					</a>
				</div>
			</div>

			<div class="row dashboard_card">
				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body pt-1">
							<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
								<div class="cabeceraBloque title-sec">
									<p class="incoming_bookings">Bookings and cancellations</p>
									<div class="top">
										<div class="selectWidget"> 
											<select class="resizeselect selectFilterWidget change_booking_cancellation" id="change_booking_cancellation" >
												<option value="today">Today</option>
												<option value="last_30_days" selected="">Last 30 days</option>
												<option value="this_month" >This month</option>
												<option value="last_12_months" >Last 12 months</option>
												<option value="this_year" >This Year</option>
											</select>
										</div>
									</div>
								</div> 
								<div class="contentBloque">
									<div class="bodyBloque noHidden">
										<div class="contentReservas bloque">
											<a href="{{ route('admin.booking.index',['upcoming_booking'=>'Confirmed-by-Host']) }}" class="insideBloque">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber">
														Bookings (<span class="last_30_day_total_bookings_cls">{{$last_30_day_bookings['last_30_day_total_bookings']}}</span>) 
												</div>
												<div class="informacion">
													<p class="amountBooking last_30_day_bookings_amount_cls">{{$last_30_day_bookings['last_30_day_bookings_amount']}}</p> 
												</div>
											</a>
										</div>
										<div class="contentReservas bloque">
											<a href="{{ route('admin.booking.index',['upcoming_booking'=>'Cancelled-Booking']) }}" target="_blank" class="insideBloque">
												<div class="division redBarra"></div>
												<div class="cantidad redNumber confirmed_bookings_div">
													Cancellations (<span class="last_30_day_total_cancellation_cls">{{$last_30_day_bookings['last_30_day_total_cancellation']}}</span>) 
												</div>
												<div class="informacion">
													<p class="amountBooking last_30_day_cancellation_amount_cls">{{$last_30_day_bookings['last_30_day_cancellation_amount']}}</p> 
												</div>
											</a>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body pt-1">
							<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
								<div class="cabeceraBloque title-sec">
									<p class="incoming_bookings">Average stay and amount</p>
									<div class="top">
										<div class="selectWidget"> 
											<select class="resizeselect selectFilterWidget averageStayAndAmount" id="averageStayAndAmount" >
												<option value="today">Today</option>
												<option value="last_30_days" selected="">Last 30 days</option>
												<option value="this_month" >This month</option>
												<option value="last_12_months" >Last 12 months</option>
												<option value="this_year" >This Year</option>
											</select>
										</div>
									</div>
								</div> 
								<div class="contentBloque">
									<div class="bodyBloque noHidden">
										<div class="contentReservas bloque">
											<a href="javascript::void(0)" class="insideBloque">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber">
													Average amount
												</div>
												<div class="informacion">
													<p class="amountBooking last_30_day_avg_amt_cls">{{ isset($last_30_day_bookings['last_30_day_avg_amt']) && $last_30_day_bookings['last_30_day_avg_amt'] != null ? number_format((float)$last_30_day_bookings['last_30_day_avg_amt'], 2, '.', '')  : '0'}}</p> 
												</div>
											</a>
										</div>
										<div class="contentReservas bloque">
											<a href="javascript::void(0)" class="insideBloque">
												<div class="division redBarra"></div>
												<div class="cantidad redNumber confirmed_bookings_div">
													Average nights
												</div>
												<div class="informacion">
													<p class="amountBooking last_30_day_avg_night_cls">{{ isset($last_30_day_bookings['last_30_day_avg_night']) && $last_30_day_bookings['last_30_day_avg_night'] != null ? round($last_30_day_bookings['last_30_day_avg_night'],0) : '0'}}</p> 
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card radius-10">
						<div class="card-body pt-1">
							<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
								<div class="cabeceraBloque title-sec">
									<p class="incoming_bookings">Platform Fees</p>
									<div class="top">
										<div class="selectWidget"> 
											<select class="resizeselect selectFilterWidget adminCommissionChangeDiv" id="adminCommissionChangeDiv" >
												<option value="last_24_hours" selected="">Last 24 Hours</option>
												<option value="this_week" >This Week</option>
												<option value="this_month" >This Month</option>
												<option value="this_year" >This Year</option>
												<option value="lifetime" >Lifetime</option>
											</select>
										</div>
									</div>
								</div> 
								<div class="contentBloque">
									<div class="bodyBloque noHidden"> 
										<div class="contentReservas bloque">
											<a href="javascript::void(0)" class="insideBloque">
												<div class="division greyBarra"></div>
												<div class="cantidad greyNumber">
													Platform Fees
												</div>
												<div class="informacion">
													<p class="admin_commission_main_div">{{ isset($last_24hour_admin_commission) && !empty($last_24hour_admin_commission) ? $last_24hour_admin_commission : '0'}}</p> 
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!--end row-->

			<div class="row">
				<div class="col-12 col-xl-4 d-flex">
					
				</div>
			</div>

			<!-- <div class="row">
				<div class="col-12 col-lg-12 col-xl-12 col-xxl-6 d-flex">
					<div class="card radius-10 w-100">
						<div class="card-body">
						<div class="row row-cols-1 row-cols-md-2 g-3 align-items-center">
							<div class="col-lg-7 col-xl-7 col-xxl-8">
							<div class="chart-js-container4 p-4">
								<div class="piechart-legend">
									<h2 class="mb-1">68%</h2>
									<h6 class="mb-0">Total Traffic</h6>
								</div>
								<canvas id="chart6"></canvas>
							</div>
							</div>
							<div class="col-lg-5 col-xl-5 col-xxl-4">
							<div class="">
								<ul class="list-group list-group-flush">
								<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
									<i class='bx bxs-circle '></i>Organic (12%)</span>
								</li>
								<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
									<i class='bx bxs-circle  text-opacity-75'></i><span>Direct (22%)</span>
								</li>
								<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
									<i class='bx bxs-circle  text-opacity-50'></i><span>Referral (34%)</span>
								</li>
								<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
									<i class='bx bxs-circle  text-opacity-25'></i><span>Others (18%)</span>
								</li>
								<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
									<i class='bx bxs-circle  text-light-1'></i><span>Social (37%)</span>
								</li>
								</ul>
								</div>
							</div>
						</div>
						</div>
					</div>
				</div>
					<div class="col-12 col-lg-12 col-xl-12 col-xxl-6 d-flex">
					<div class="card radius-10 w-100">
						<div class="card-body">
							<div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-3">
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class="bx bxl-facebook-square"></i>
												</div>
												<h4 class="my-1">84K</h4>
												<p class="mb-0 text-light-70">Facebook Users</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class="bx bxl-twitter"></i>
												</div>
												<h4 class="my-1">34M</h4>
												<p class="mb-0 text-light-70">Twitter Followers</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class="bx bxl-linkedin-square"></i>
												</div>
												<h4 class="my-1">56K</h4>
												<p class="mb-0 text-light-70">Linkedin Followers</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class="bx bxl-youtube"></i>
												</div>
												<h4 class="my-1">38M</h4>
												<p class="mb-0 text-light-70">YouTube Subscribers</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class="bx bxl-dropbox"></i>
												</div>
												<h4 class="my-1">28K</h4>
												<p class="mb-0 text-light-70">Dropbox Users</p>
											</div>
										</div>
									</div>
								</div>
								<div class="col">
									<div class="card radius-10 mb-0 shadow-none border bg-transparent">
										<div class="card-body">
											<div class="text-center">
												<div class="widgets-icons rounded-circle mx-auto bg-light  mb-3"><i class='bx bxl-dribbble'></i>
												</div>
												<h4 class="my-1">49K</h4>
												<p class="mb-0 text-light-70">Dribbble Users</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row row-cols-1 row-cols-md-1">
				<div class="col col-lg-8">
					<div class="card radius-10">
						<div class="card-body">
							<div id="geographic-map"></div>
						</div>
					</div>
				</div>
				<div class="col col-lg-4">
					<div class="card radius-10 overflow-hidden">
						<div class="card-header p-3">
							<div class="d-lg-flex align-items-center">
								<div>
									<h5 class="mb-0">Top Countries</h5>
								</div>
								<div class="ms-auto">
									<h3 class="mb-0"><span class="font-14">Total Visits:</span> 15K</h3>
								</div>
							</div>
						</div>
						<div class="dashboard-top-countries mb-3 p-3">
							<ul class="list-group list-group-flush radius-10">
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-in"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">India</h6>
										</div>
									</div>
									<div class="ms-auto">647</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-us"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">United States</h6>
										</div>
									</div>
									<div class="ms-auto">435</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-vn"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Vietnam</h6>
										</div>
									</div>
									<div class="ms-auto">287</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-au"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Australia</h6>
										</div>
									</div>
									<div class="ms-auto">432</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-dz"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Angola</h6>
										</div>
									</div>
									<div class="ms-auto">345</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-ax"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Aland Islands</h6>
										</div>
									</div>
									<div class="ms-auto">134</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-ar"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Argentina</h6>
										</div>
									</div>
									<div class="ms-auto">147</div>
								</li>
								<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
									<div class="d-flex align-items-center">
										<div class="font-20"><i class="flag-icon flag-icon-be"></i>
										</div>
										<div class="flex-grow-1 ms-2">
											<h6 class="mb-0">Belgium</h6>
										</div>
									</div>
									<div class="ms-auto">210</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
				<div class="col d-flex">
					<div class="card radius-10 w-100">
						<div class="card-header">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-0">Browser Statistics</h5>
								</div>
								<div class="dropdown options ms-auto">
									<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										<i class='bx bx-dots-horizontal-rounded'></i>
									</div>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="javascript:;">Action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
									</ul>
									</div>
								</div>
						</div>
						<div class="card-body">
							<div class="chart-js-container3">
								<canvas id="chart7"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="col d-flex">
					<div class="card radius-10 w-100 overflow-hidden">
						<div class="card-header">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-0">Device Sessions</h5>
								</div>
								<div class="dropdown options ms-auto">
									<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										<i class='bx bx-dots-horizontal-rounded'></i>
									</div>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="javascript:;">Action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
									</ul>
									</div>
								</div>
						</div>
						<div class="card-body">
							<div class="chart-js-container2">
								<canvas id="chart8"></canvas>
								</div>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center border-top bg-transparent">
								Desktop
								<span class="badge bg-white text-dark rounded-pill">558</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
								Mobile
								<span class="badge bg-white bg-opacity-50 rounded-pill">204</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
								Tablet
								<span class="badge bg-white bg-opacity-25 rounded-pill">108</span>
							</li>
							</ul>
					</div>
				</div>
				<div class="col d-flex">
					<div class="card radius-10 w-100">
					<div class="card-header">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-0">Social Traffic</h5>
								</div>
								<div class="dropdown options ms-auto">
									<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										<i class='bx bx-dots-horizontal-rounded'></i>
									</div>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="javascript:;">Action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										<li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
									</ul>
									</div>
								</div>
							</div>
						<div class="card-body">
							<div class="d-flex mt-2 mb-4">
								<h2 class="mb-0 font-weight-bold">89,421</h2>
								<p class="mb-0 ms-1 font-14 align-self-end">Total Visits</p>
							</div>
							<div class="progress radius-10" style="height: 10px">
								<div class="progress-bar bg-white" role="progressbar" style="width: 35%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 20%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar bg-white bg-opacity-50" role="progressbar" style="width: 15%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar bg-white bg-opacity-25" role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar bg-light" role="progressbar" style="width: 10%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table mb-0">
									<tbody>
										<tr>
											<td class="px-0">
												<div class="d-flex align-items-center">
													<div><i class="bx bxs-checkbox me-2 font-22 "></i>
													</div>
													<div>Facebook</div>
												</div>
											</td>
											<td>46 Visits</td>
											<td class="px-0 text-right">33%</td>
										</tr>
										<tr>
											<td class="px-0">
												<div class="d-flex align-items-center">
													<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-75"></i>
													</div>
													<div>YouTube</div>
												</div>
											</td>
											<td>12 Visits</td>
											<td class="px-0 text-right">17%</td>
										</tr>
										<tr>
											<td class="px-0">
												<div class="d-flex align-items-center">
													<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-50"></i>
													</div>
													<div>Linkedin</div>
												</div>
											</td>
											<td>29 Visits</td>
											<td class="px-0 text-right">21%</td>
										</tr>
										<tr>
											<td class="px-0">
												<div class="d-flex align-items-center">
													<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-25"></i>
													</div>
													<div>Twitter</div>
												</div>
											</td>
											<td>34 Visits</td>
											<td class="px-0 text-right">23%</td>
										</tr>
										<tr>
											<td class="px-0">
												<div class="d-flex align-items-center">
													<div><i class="bx bxs-checkbox me-2 font-22 text-light-1"></i>
													</div>
													<div>Dribbble</div>
												</div>
											</td>
											<td>28 Visits</td>
											<td class="px-0 text-right">19%</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="card radius-10">
				<div class="card-body">
					<div class="table-responsive lead-table">
						<table class="table mb-0 align-middle">
							<thead class="table-light">
								<tr>
									<th>Potential Leads</th>
									<th>Diposit</th>
									<th>Progress</th>
									<th>Last Update</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-1.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">Ronald Waters</h6>
												<p class="mb-0 font-13 text-secondary">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$89,620</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 66%"></div>
										</div>
									</td>
									<td>14 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">In Progress</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-2.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">David Buckley</h6>
												<p class="mb-0 font-13">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$38,520</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 76%"></div>
										</div>
									</td>
									<td>15 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">Cancelled</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-3.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">James Caviness</h6>
												<p class="mb-0 font-13">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$63,820</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 100%"></div>
										</div>
									</td>
									<td>16 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">Completed</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-4.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">John Roman</h6>
												<p class="mb-0 font-13">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$97,420</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 58%"></div>
										</div>
									</td>
									<td>18 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">In Progress</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-7.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">Johnny Seitz</h6>
												<p class="mb-0 font-13">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$48,360</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 66%"></div>
										</div>
									</td>
									<td>22 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">Cancelled</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="d-flex align-items-center">
											<div>
												<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
											</div>
											<div class="">
												<img src="assets/images/avatars/avatar-8.png" class="rounded-circle" width="40" height="40" alt="">
											</div>
											<div class="ms-2">
												<h6 class="mb-0 font-14">Pauline Bird</h6>
												<p class="mb-0 font-13">Lead Designers</p>
											</div>
										</div>
									</td>
									<td>$74,620</td>
									<td class=" w-25">
										<div class="progress radius-10" style="height:4.5px;">
											<div class="progress-bar" role="progressbar" style="width: 100%"></div>
										</div>
									</td>
									<td>24 Oct 2020</td>
									<td>
										<div class="badge rounded-pill bg-light w-100">Completed</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>  -->
			<!-- <input type="text" name="occupancy_name" id="occupancy_name" value="<?php echo $occupancy; ?>"> -->
		</div>
	</div>
</div>
	<!--end page wrapper -->
	<!--start overlay-->
	<div class="overlay toggle-icon"></div>
	<!--end overlay-->
	<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

	<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/dashboard-analytics.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
<!-- <script src="{{ URL::asset('assets/plugins/apexcharts-bundle/js/apex-custom.js') }}"></script> -->
<script>

	$('.upcommingBoking').click(function(){
		var filters = $('#filterWidgetProximosCheckins option:selected').val();

		window.location.href = "{{route('admin.booking.index',['upcoming_booking'=>'upcoming'])}}&days="+filters

	});

	$('.currentBooking').click(function(){
		window.location.href = "{{route('admin.booking.index',['upcoming_booking'=>'current'])}}";
	});

	


	function incoming_booking_confirm(booking){
		var filterWidgetReservasEntrantes = $('#filterWidgetReservasEntrantes').val();
		if(booking == 'confirm'){
			window.location.href = '{{ url("admin/booking_reserve")}}?incoming_booking_confirm='+booking+'&from_date='+filterWidgetReservasEntrantes;
		}else{
			window.location.href = '{{ url("admin/booking")}}?incoming_booking_confirm='+booking+'&from_date='+filterWidgetReservasEntrantes;
		}
	}
	function pending_actions(){
		var filterWidgetAccionesPendientes = $('#filterWidgetAccionesPendientes').val();
		window.location.href = '{{ url("admin/booking")}}?pending_actions=Yes&from_date='+filterWidgetAccionesPendientes;
	}

	$('#example1').DataTable({
		"iDisplayStart ": 4,
		"iDisplayLength": 4,
		"bFilter": false, //hide Search bar
		"bInfo": false, // hide showing entries
		"bLengthChange": false,
		"ordering": false,
	});
	$('#current_bookings_datatable').DataTable({
		"iDisplayStart ": 4,
		"iDisplayLength": 4,
		"bFilter": false, //hide Search bar
		"bInfo": false, // hide showing entries
		"bLengthChange": false,
		"ordering": false,
	});
	function goToPage(page = 0){
		// alert(page);
		var days = $('.filterWidgetProximosCheckins').val();
		// alert(days);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/goToPage") }}',
			method: 'post',
			data: {'page':page, 'days':days},
			success: function(result){
				console.log('result-'+result.last_30_day_bookings_amount);
				$('.upcoming_bookings_main_div').html(result);
				$('.loader').hide();
			}
		});
	}

	// get address end  
	$(document).on('change','.filterWidgetReservasEntrantes',function(){
		var value = $(this).val();
		// alert(value);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/previous_day_records") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				// console.log('result-'+result);
				if(result){
					if(result.incoming_bookings == 0){
						var incoming_booking_count = result.confirmed_bookings + result.incoming_bookings;
						$('.incoming_bookings_count').html('Incoming bookings ('+incoming_booking_count+')');
						$('.incoming_bookings_inner_div').html('<div class="incoming_bookings" style="text-align: center;color:black;"><strong>You have no incoming booking requests for the selected time period.</strong></div>');
					}else{
						var incoming_booking_count = result.confirmed_bookings + result.incoming_bookings;
						$('.incoming_bookings_count').html('Incoming bookings ('+incoming_booking_count+')');
						$('.confirmed_bookings_div').html(result.confirmed_bookings);
						$('.incoming_bookings_div_count').html(result.incoming_bookings);

						$('.incoming_bookings_inner_div').html('<div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">To be assigned</p> <p class="descripcion">Unavailable dates and Airbnb request to book</p></div></a></div><div class="bloque"><a href="javascript:;" class="insideBloque" onclick="incoming_booking_confirm(\'instant\')"><div class="division greyBarra"></div><div class="cantidad greyNumber">'+result.incoming_bookings+'</div><div class="informacion"><p class="title">Instant Booking</p> <p class="descripcion">HomeAway confirmed bookings</p></div></a></div><div class="bloque"><a href="javascript:;" class="insideBloque incoming_booking_confirm"  onclick="incoming_booking_confirm(\'confirm\')"><div class="division redBarra"></div><div class="cantidad redNumber confirmed_bookings_div">'+result.confirmed_bookings+'</div><div class="informacion"><p class="title">To be confirmed</p> <p class="descripcion">New pre-bookings</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">Information requests</p> <p class="descripcion">Requests to review</p></div></a></div>');
					}
				}
				$('.loader').hide();
			}
		});
  	});

	// get Pending Actions
	$(document).on('change','.filterWidgetAccionesPendientes',function(){
		var value = $(this).val();
		// alert(value);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/pending_action_records") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				// console.log('result-'+result);
				if(result){
					if(result.total_pending_actions_count == 0){
						$('.total_pending_actions_count').html('Pending actions ('+result.total_pending_actions_count+')');
						$('.seven_days_pending_payments_count').html(result.seven_days_pending_payments_count);
						$('.seven_days_pending_payments_sum').html(result.seven_days_pending_payments_sum+' NGN to receive');
						$('.seven_days_pending_payments_checkin_count').html(result.seven_days_pending_payments_checkin_count);
						// $('.incoming_bookings_inner_div').html('<div class="incoming_bookings" style="text-align: center;color:black;"><strong>You have no incoming booking requests for the selected time period.</strong></div>');
					}else{
						$('.total_pending_actions_count').html('Pending actions ('+result.total_pending_actions_count+')');
						$('.seven_days_pending_payments_count').html(result.seven_days_pending_payments_count);
						$('.seven_days_pending_payments_sum').html(result.seven_days_pending_payments_sum+' NGN to receive');
						$('.seven_days_pending_payments_checkin_count').html(result.seven_days_pending_payments_checkin_count);
						// $('.confirmed_bookings_div').html(result.confirmed_bookings);

						// $('.incoming_bookings_inner_div').html('<div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">To be assigned</p> <p class="descripcion">Unavailable dates and Airbnb request to book</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">Instant Booking</p> <p class="descripcion">HomeAway confirmed bookings</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division redBarra"></div><div class="cantidad redNumber confirmed_bookings_div">'+result.confirmed_bookings+'</div><div class="informacion"><p class="title">To be confirmed</p> <p class="descripcion">New pre-bookings</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">Information requests</p> <p class="descripcion">Requests to review</p></div></a></div>');
					}
				}
				$('.loader').hide();
			}
		});
	});

	$(document).on('change','.filterWidgetProximosCheckins',function(e){
		e.preventDefault();
		var value = $(this).val();
		$('.upcoming_bookings_main_div').html('');
		$.ajax({
			url:'{{url("admin/next_day_records")}}/'+value,
			// data: {'value':value},
			dataType: 'json',
			// dataType: 'html',
			success:function(result)
			{
				console.log(result);
				$('.upcoming_bookings_main_div').html(result.message);
				// $('#edit_content_response').html(result);
				$('.upcoming_bookings_totle').html('('+result.upcoming_bookings_totle+')');
			}
		});
	});

	$(document).on('change','.change_booking_cancellation',function(){
		var value = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/change_booking_cancellation") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				console.log('result-'+result.last_30_day_bookings_amount);
				if(result){
					$('.last_30_day_total_bookings_cls').html(result.last_30_day_total_bookings);
					$('.last_30_day_bookings_amount_cls').html(result.last_30_day_bookings_amount);
					$('.last_30_day_total_cancellation_cls').html(result.last_30_day_total_cancellation);
					$('.last_30_day_cancellation_amount_cls').html(result.last_30_day_cancellation_amount);
					// tables.ajax.reload();  
					// sweetalert(result.type,result.message);
				}
				$('.loader').hide();
			}
		});
  	});

	$(document).on('change','.averageStayAndAmount',function(){
		var value = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/change_avg_amount_and_nights") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				console.log('result-'+result.last_30_day_avg_amt);
				if(result){
					$('.last_30_day_avg_amt_cls').html(result.last_30_day_avg_amt);
					$('.last_30_day_avg_night_cls').html(result.last_30_day_avg_night);
				}else{
					$('.last_30_day_avg_amt_cls').html('0');
					$('.last_30_day_avg_night_cls').html('0');
				}
				$('.loader').hide();
			}
		});
	});

	$(document).on('change','.adminCommissionChangeDiv',function(){
		var value = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/change_admin_commission") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				console.log('result-'+result);
				if(result){
					$('.admin_commission_main_div').html(result);
				}else{
					$('.admin_commission_main_div').html('0');
				}
				$('.loader').hide();
			}
		});
	});



	$(document).ready(function(){
		var last_12_months_list = <?php echo $last_12_months_list; ?>;
		var last_12_months_amount = <?php echo $last_12_months_amount; ?>;
		// console.log('--'+last_12_months_list);
		getGraphData(last_12_months_list, last_12_months_amount);
		
	})

	$(document).on('change','.bookingChange',function(){
		var value = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/booking_change") }}',
			method: 'post',
			dataType: 'html',
			data: {'value':value},
			success: function(result){
				// console.log('result-'+result);
				$('.dashboard-chart2').replaceWith(result);
				// var last_12_months_list1 = result.last_12_months_list;
				// var last_12_months_amount1 = result.last_12_months_amount;
				// var obj = jQuery.parseJSON( last_12_months_list1 );
				// var obj1 = jQuery.parseJSON( last_12_months_amount1 );
			 	// getGraphData(obj, obj1);

				// setTimeout(getGraphData(obj, obj1), 1000);
				// getGraphData(obj, obj1);
				// $('.loader').hide();
			}
		});
	});

	function getGraphData(last_12_months_list, last_12_months_amount){
		console.log(last_12_months_list);
		console.log(last_12_months_amount);
		// alert(last_12_months_amount);
		var last_12_months_list = last_12_months_list;
		var last_12_months_amount = last_12_months_amount;

		"use strict";
		// chart 2
		var optionsLine = {
			chart: {
				foreColor: '#000',
				height: 420,
				type: 'line',
				zoom: {
					enabled: false
				},
				dropShadow: {
					enabled: true,
					top: 4,
					left: 2,
					blur: 4,
					opacity: 0.1,
				}
			},
			stroke: {
				curve: 'smooth',
				width: 3
			},
			colors: ["#000", '#000', '#000'],
			yaxis: {
			    labels: {
			        formatter: function(val) {
			            // Format the value as a number with commas
			            var formattedValue = val.toLocaleString('en-US');

			            // Append the currency symbol after the value
			            return formattedValue + ' M NGN';
			        }
			    }
			},
			series: [{
				name: "Booking Amount",
				data: last_12_months_amount
			}],
			title: {
				text: '',
				align: 'left',
				offsetY: 25,
				offsetX: 20
			},
			subtitle: {
				// text: 'Booking amount in millions',
				offsetY: 55,
				offsetX: 20
			},
			markers: {
				size: 4,
				strokeWidth: 0,
				hover: {
					size: 7
				}
			},
			grid: {
				show: true,
				borderColor: '#000',
				strokeDashArray: 4,
			},
			tooltip: {
				theme: 'dark',
			},
			labels: last_12_months_list,
			xaxis: {
				tooltip: {
					enabled: false
				}
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				offsetY: -20
			}
		}

		///console.log(optionsLine);
		var chartLine = new ApexCharts(document.querySelector('#dashboard-chart2'), optionsLine);
		chartLine.render();
	}

	$(function () {
	});

	// chart 6 Dashboard Occupancy Chart 

	$(document).on('change','.occupancyFilter',function(){
		var value = $(this).val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$('.loader').show();
		$.ajax({
			url:'{{ url("admin/occupancyFilter") }}',
			method: 'post',
			data: {'value':value},
			success: function(result){
				console.log('result-'+result);
				if(result){
					// $('#occupancy_name').val(result);
					// alert(value);
					if(value == 'this_month'){
						$('.month_to_today_date_div').css('display','block');
					}else{
						$('.month_to_today_date_div').css('display','none');
					}
					occupancy(result);
				}
				$('.loader').hide();
			}
		});
  	});

	$(document).ready(function(){
		var val = <?php echo $occupancy; ?>;
		occupancy(val);
	})

	function occupancy(val){
		var occupancy = val;
		var options = {
			chart: {
				height: 300,
				type: 'radialBar',
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				radialBar: {
					//startAngle: -135,
					//endAngle: 225,
					hollow: {
						margin: 0,
						size: '78%',
						//background: '#fff',
						image: undefined,
						imageOffsetX: 0,
						imageOffsetY: 0,
						position: 'front',
						dropShadow: {
							enabled: false,
							top: 3,
							left: 0,
							blur: 4,
							color: '#000',
							opacity: 0.65
						}
					},
					track: {
						background: '#000',
						//strokeWidth: '67%',
						margin: 0, // margin is in pixels
						dropShadow: {
							enabled: false,
							top: -3,
							left: 0,
							blur: 4,
							color: '#000',
							opacity: 0.65
						}
					},
					dataLabels: {
						showOn: 'always',
						name: {
							offsetY: -25,
							show: true,
							color: '#fff',
							fontSize: '16px'
						},
						value: {
							formatter: function (val) {
								return val + "%";
							},
							color: '#000',
							fontSize: '45px',
							show: true,
							offsetY: 10,
						}
					}
				}
			},
			fill: {
				type: 'gradient',
				gradient: {
					shade: 'light',
					type: 'horizontal',
					shadeIntensity: 0.5,
					gradientToColors: ['#fff'],
					inverseColors: false,
					opacityFrom: 1,
					opacityTo: 1,
					stops: [0, 100]
				}
			},
			colors: ["#fff"],
			series: [occupancy],
			stroke: {
				lineCap: 'round',
				//dashArray: 4
			},
			labels: [''],
		}
		$("#chart6").html('');
		var chart = new ApexCharts(document.querySelector("#chart6"), options);
		chart.render();
	}
</script>
@endsection