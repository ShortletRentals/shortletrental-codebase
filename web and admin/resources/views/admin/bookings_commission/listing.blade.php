@extends('layouts.master')
@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<!-- <div class="breadcrumb-title pe-3">eCommerce</div> -->
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
			  
				<div class="card">
					<div class="card-header">
		                <div class="row">
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<label for="inputName" class="form-label">Date of</label>
								<select name="date_of" id="date_of" class="form-control date_of" placeholder="Select Date of" data-dropdown-css-class="select2-primary">
									<option value="from_date" <?php echo isset($date_of) && $date_of == 'from_date' ? 'selected' :'' ?>>Check-In</option>
									<option value="to_date" <?php echo isset($date_of) && $date_of == 'to_date' ? 'selected' :'' ?>>Check-Out</option>
								</select>
							</div>
		                    <div class="col-md-2 mt-2">
								<label for="inputName" class="form-label"></label>
								<input type="text" class="form-control start_date" placeholder="From Date">
							</div>
		                    <div class="col-md-2 mt-2">
								<label for="inputName" class="form-label"></label>
								<input type="text" class="form-control end_date"  placeholder="To Date">
							</div>

							<div class="col-md-2 mt-2">
								<label for="inputName" class="form-label">Accommodation</label>
								<input type="text" class="form-control accommodation"  placeholder="Accommodation">
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<label for="inputName" class="form-label">Building</label>
								<select name="search_building" id="search_building" class="form-control search_building" placeholder="Select Building" data-dropdown-css-class="select2-primary">
									<option value="">Select Building</option>
									@if(count($all_buildings) > 0)
									@foreach($all_buildings as $buildings)
										<option value="{{$buildings->id}}" <?php echo isset($search_building) && $search_building == $buildings->id ? 'selected' :'' ?>>{{ $buildings->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-2 mt-2">
								<label for="inputName" class="form-label">Booking Status</label>
		                        <select class="form-control booking_status" name="booking_status">
		                            <option value="">Booking Status</option> 
		                            <option value="Not-confirmed-by-Host" <?php echo isset($booking_type) && $booking_type == 'Not-confirmed-by-Host' ? 'selected' :'' ?>>Not confirmed by Host</option> 
		                            <option value="Confirmed-by-Host" <?php echo isset($booking_type) && $booking_type == 'Confirmed-by-Host' ? 'selected' :'' ?>>Confirmed by Host</option> 
		                            <option value="Ongoing-Booking" <?php echo isset($booking_type) && $booking_type == 'Ongoing-Booking' ? 'selected' :'' ?>>Ongoing Booking</option> 
		                            <option value="Completed-Booking" <?php echo isset($booking_type) && $booking_type == 'Completed Booking' ? 'selected' :'' ?>>Completed Booking</option> 
		                            <option value="Cancelled-Booking" <?php echo isset($booking_type) && $booking_type == 'Cancelled-Booking' ? 'selected' :'' ?>>Cancelled Booking</option>
		                        </select>
		                    </div>
							<div class="col-md-2 mt-2">
								<label for="inputName" class="form-label">Booking Type</label>
		                        <select class="form-control booking_type" name="booking_type">
		                            <option value="">Booking Type</option> 
		                            <option value="Pre-booking" <?php echo isset($booking_type) && $booking_type == 'Pre-booking' ? 'selected' :'' ?>>Pre booking</option> 
		                            <option value="Confirmed" <?php echo isset($booking_type) && $booking_type == 'Confirmed' ? 'selected' :'' ?>>Confirmed</option> 
		                            <option value="Information_Request" <?php echo isset($booking_type) && $booking_type == 'Information_Request' ? 'selected' :'' ?>>Information Request</option> 
		                            <option value="Owner_Booking" <?php echo isset($booking_type) && $booking_type == 'Owner_Booking' ? 'selected' :'' ?>>Owner Booking</option> 
		                            <option value="Not_Available" <?php echo isset($booking_type) && $booking_type == 'Not_Available' ? 'selected' :'' ?>>Not Available</option> 
		                            <option value="Paid" <?php echo isset($booking_type) && $booking_type == 'Paid' ? 'selected' :'' ?>>Paid</option> 
		                        </select>
		                    </div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<label for="inputName" class="form-label">Partner</label>
								<select name="search_influencer" id="search_influencer" class="form-control search_influencer" placeholder="Select Partner" data-dropdown-css-class="select2-primary">
									<option value="">Select Partner</option>
									@if(count($all_influencers) > 0)
									@foreach($all_influencers as $influencers)
										<option value="{{$influencers->id}}" <?php echo isset($search_influencer) && $search_influencer == $influencers->id ? 'selected' :'' ?>>{{ $influencers->name.' ('.$influencers->email.')' }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<label for="inputName" class="form-label">Customer</label>
								<select name="search_customer" id="search_customer" class="form-control search_customer" placeholder="Select Customer" data-dropdown-css-class="select2-primary">
									<option value="">Select Customer</option>
									@if(count($all_customers) > 0)
									@foreach($all_customers as $customer)
										<option value="{{$customer->id}}" <?php echo isset($search_customer) && $search_customer == $customer->id ? 'selected' :'' ?>>{{ $customer->name.' ('.$customer->email.')' }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<label for="inputName" class="form-label">Owner</label>
								<select name="search_host" id="search_host" class="form-control search_host" placeholder="Select Owner" data-dropdown-css-class="select2-primary">
									<option value="">Select Owner</option>
									@if(count($all_host) > 0)
									@foreach($all_host as $host)
										<option value="{{$host->id}}" <?php echo isset($search_host) && $search_host == $host->id ? 'selected' :'' ?>>{{ $host->name.' ('.$host->email.')' }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-2 mt-2">
								<label for="inputName" class="form-label">Filter By</label>
		                        <select class="form-control filter_by" name="filter_by">
		                            <option value="">Filter By</option> 
		                            <option value="day" <?php echo isset($filter_by) && $filter_by == 'day' ? 'selected' :'' ?>>Day</option> 
		                            <option value="month" <?php echo isset($filter_by) && $filter_by == 'month' ? 'selected' :'' ?>>Month</option> 
		                            <option value="year" <?php echo isset($filter_by) && $filter_by == 'year' ? 'selected' :'' ?>>Year</option> 
		                        </select>
		                    </div>
		                    <div class="col-md-2 mt-2">
								<label for="inputName" class="form-label">End Date</label>
								<input type="text" class="form-control filter_by_date" name="filter_by_date" placeholder="Date">
							</div>
		                    <div class="col-md-4 d-inline-flex mt-4">
								<label for="inputName" class="form-label"></label>
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>

								<button type="button" class="btn btn-primary me-3 ms-2" onclick="exportMenuData('Excel')"><i class="bx bx-download"></i>Export</button>
		                    </div>
		                </div>
		            </div>         
					<div class="card-body">
						<div class="table-responsive">
							<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive dataTable nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Booking reference</th>
										<th>Check-in date</th>
										<th>Check-out date</th>
										<th>Building</th>
										<th>Accommodation</th>
										<th>Customer</th>
										<th>Guest</th>
										<th>Influencer</th>
										<th>Influencer Reference Number</th>
										<th>Rental without VAT</th>
										<th>Rental with VAT</th>
										<th>Platform Fees</th>
										<th>Influencer Commission</th>
										<th>Caution Fee</th>
										<th>Booking Amount</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>

			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	</div>
	<!--end wrapper-->
	<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript">
	var tables = $('#datatable').DataTable({
		"bProcessing": true,
		"serverSide": true,
		"pageLength": 10,
		retrieve: true,
		"ajax": {
			url: "{{ customeRoute($page.'.index') }}",
			data: function (d) {
				return $.extend({}, d, {
					'date_of':$('.date_of').val(),
					'start_date':$('.start_date').val(),
					'end_date':$('.end_date').val(),
					'accommodation':$('.accommodation').val(),
					'search_building':$('.search_building').val(),
					'booking_status':$('.booking_status').val(),
					'booking_type':$('.booking_type').val(),
					'filter_by':$('.filter_by').val(),
					'filter_by_date':$('.filter_by_date').val(),
					'search_influencer':$('.search_influencer').val(),
					'search_customer':$('.search_customer').val(),
					'search_host':$('.search_host').val(),
				});
			},
		}, 
		
		"aoColumns": [
			//{mData: 'id'},
			{mData: 'booking_id'},
			{mData: 'from_date'},
			{mData: 'to_date'},
			{mData: 'building'},
			{mData: 'accommodation'},
			{mData: 'customer'},
			{mData: 'guest'},
			{mData: 'influencer'},
			{mData: 'influencer_code'},
			{mData: 'rental_without_tax'},
			{mData: 'rental_with_tax'},
			{mData: 'admin_commission'},
			{mData: 'influencer_commission'},
			{mData: 'caution_fee'},
			{mData: 'total_amount'},
		],
			"aoColumnDefs": [
			{"bSortable": false, "aTargets": ['action']},
			{ "orderable": false, "targets": [3,5,6,7,8] }
		],
		"order": [[2, "desc"]],
		
		language: {
			searchPlaceholder: "Search By Accommodation"
		}, 
	});
	$('.refresh').click(function (e){
		$('.date_of').val("from_date");
		$('.start_date').val("");
		$('.end_date').val("");
		$('.accommodation').val("");
		$('.search_building').val("");
		$('.booking_status').val('');
		$('.booking_type').val('');
		$('.filter_by').val('');
		$('.filter_by_date').val('');
		$('.search_influencer').val('');
		$('.search_customer').val('');
		$('.search_host').val('');
		tables.ajax.reload();
	});
	$('.filter').click(function (e) {
		tables.ajax.reload();
	});

	/*Export Excel and Csv Function */
	function exportMenuData(file_type) {
		// alert(file_type);
		$(".overlay").fadeIn(300);
		var link = '?';
		var date_of = $('.date_of').val();
		var start_date = $('.start_date').val();
		var end_date = $('.end_date').val();
		var accommodation = $('.accommodation').val();
		var search_building = $('.search_building').val();
		var booking_status = $('.booking_status').val();
		var booking_type = $('.booking_type').val();
		var filter_by = $('.filter_by').val();
		var filter_by_date = $('.filter_by_date').val();
		var search_influencer = $('.search_influencer').val();
		var search_customer = $('.search_customer').val();
		var search_host = $('.search_host').val();
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var url = "{{ url('admin/bookings_commission/exportBookings') }}";
		// var url = "{{ url('admin/property/exportProperties') }}"+link;
		$.ajax({
			type: 'post',
			dataType:'json',
			data:{'file_type':file_type,'date_of':date_of,'start_date':start_date,'end_date':end_date,'accommodation':accommodation,'search_building':search_building,'booking_status':booking_status,'booking_type':booking_type,'filter_by':filter_by,'filter_by_date':filter_by_date,'search_influencer':search_influencer,'search_customer':search_customer,'search_host':search_host},
			url: url,
			success:function(response){
				// console.log('response--'+response);
				if(response.status == 1){
					window.open(response.url,'_blank' );
					$('.export_ul').toggle();
				}
				$(".overlay").fadeOut(300);
			},
			error:function(jqXHR,textStatus,textStatus){
				// window.open(url,'_blank' );
				console.log(jqXHR);
				toastr.error(jqXHR.statusText)
			}
		});
	}
	/*Export Excel and Csv Function End*/

	$(document).ready(function(){
		$(".start_date").datepicker({
				minDate: "-1Y",
			//  maxDate: "+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".end_date").datepicker("option","minDate", selected)
				}
		});
		$(".end_date").datepicker({
				minDate:"-1Y",
			//  maxDate:"+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".start_date").datepicker("option","maxDate", selected)
				}
		});
		$(".filter_by_date").datepicker({
			minDate: "-1Y",
		 	// maxDate: "+0D",
			numberOfMonths: 1,
			dateFormat:'yy-mm-dd',
			// onSelect: function(selected) {
			// $(".end_date").datepicker("option","minDate", selected)
			// }
		});
	});
</script>
@endsection