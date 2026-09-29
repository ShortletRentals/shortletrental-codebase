@extends('layouts.master')
@section('content')
	<link href="{{ URL::asset('assets/plugins/highcharts/css/highcharts-white.css') }}" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
	<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content ">	

			<div class="row">
				<div class="col-md-3">
					<a href="{{ route('admin.discount.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$total_coupon_code}}</h5>
								<div class="ms-auto">
									<i class='bx bx-server fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Discount Code</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="{{ route('admin.discount.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$total_customer}}</h5>
								<div class="ms-auto">
									<i class='bx bx-group fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Customers</p>
							</div>
						</div>
					</a>
				</div>
				<!-- <div class="col-md-3">
					<a href="{{ route('admin.property.index', 'short') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$total_coupon_code}}</h5>
								<div class="ms-auto">
									<i class='bx bx-building fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Accommodations</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-3">
					<a href="{{ route('admin.booking.index') }}">
						<div class="card-body dash_itm">
							<div class="d-flex align-items-center">
								<h5 class="mb-0">{{$total_coupon_code}}</h5>
								<div class="ms-auto">
									<i class='bx bx-cart fs-3 '></i>
								</div>
							</div>
							<div class="progress my-3" style="height:4px;">
								<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="d-flex align-items-center ">
								<p class="mb-0">Number of Bookings</p>
							</div>
						</div>
					</a>
				</div> -->
			</div>

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
					if(result.confirmed_bookings == 0){
						$('.incoming_bookings_count').html('Incoming bookings ('+result.confirmed_bookings+')');
						$('.incoming_bookings_inner_div').html('<div class="incoming_bookings" style="text-align: center;color:black;"><strong>You have no incoming booking requests for the selected time period.</strong></div>');
					}else{
						$('.incoming_bookings_count').html('Incoming bookings ('+result.confirmed_bookings+')');
						$('.confirmed_bookings_div').html(result.confirmed_bookings);

						$('.incoming_bookings_inner_div').html('<div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">To be assigned</p> <p class="descripcion">Unavailable dates and Airbnb request to book</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">Instant Booking</p> <p class="descripcion">HomeAway confirmed bookings</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division redBarra"></div><div class="cantidad redNumber confirmed_bookings_div">'+result.confirmed_bookings+'</div><div class="informacion"><p class="title">To be confirmed</p> <p class="descripcion">New pre-bookings</p></div></a></div><div class="bloque"><a href="{{ route('admin.booking.index') }}" class="insideBloque"><div class="division greyBarra"></div><div class="cantidad greyNumber">0</div><div class="informacion"><p class="title">Information requests</p> <p class="descripcion">Requests to review</p></div></a></div>');
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
			dataType: 'html',
			success:function(result)
			{
				console.log(result);
				$('.upcoming_bookings_main_div').html(result);
				// $('#edit_content_response').html(result);
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

	$(document).ready(function(){
		var last_12_months_list = '';
		var last_12_months_amount = '';
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
			series: [{
				name: "Bookings",
				data: last_12_months_amount
			}/*, {
				name: "Photos",
				data: [3, 33, 21, 42, 19, 32]
			}, {
				name: "Files",
				data: [0, 39, 52, 11, 29, 43]
			}*/],
			title: {
				text: '',
				align: 'left',
				offsetY: 25,
				offsetX: 20
			},
			subtitle: {
				// text: 'Statistics',
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
		var val = '';
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
		var chart = new ApexCharts(document.querySelector("#chart6"), options);
		chart.render();
	}
</script>
@endsection