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
					<!-- <div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-light">Settings</button>
							<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
								<a class="dropdown-item" href="javascript:;">Another action</a>
								<a class="dropdown-item" href="javascript:;">Something else here</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
							</div>
						</div>
					</div> -->
				</div>
				<!--end breadcrumb-->
				<div class="card">
					<div class="card-header">
		                <div class="row">
							<div class="col-md-2 mt-2">
		                        <select class="form-control booking_status1" name="booking_status">
		                            <option value="">Booking Status</option> 
		                            <option value="Not-confirmed-by-Host" <?php echo isset($booking_status) && $booking_status == 'Not-confirmed-by-Host' ? 'selected' :'' ?>>Not confirmed by Host</option> 
		                            <option value="Confirmed-by-Host" <?php echo isset($booking_status) && $booking_status == 'Confirmed-by-Host' ? 'selected' :'' ?>>Confirmed by Host</option> 
		                            <option value="Ongoing-Booking" <?php echo isset($booking_status) && $booking_status == 'Ongoing-Booking' ? 'selected' :'' ?>>Ongoing Booking</option> 
		                            <option value="Completed-Booking" <?php echo isset($booking_status) && $booking_status == 'Completed Booking' ? 'selected' :'' ?>>Completed Booking</option> 
		                            <option value="Cancelled-Booking" <?php echo isset($booking_status) && $booking_status == 'Cancelled-Booking' ? 'selected' :'' ?>>Cancelled Booking</option>
		                        </select>
		                    </div>
							<div class="col-md-2 mt-2">
		                        <select class="form-control booking_type1" name="booking_type">
		                            <option value="">Booking Type</option> 
		                            <option value="Pre-booking" <?php echo isset($booking_type) && $booking_type == 'Pre-booking' ? 'selected' :'' ?>>Pre booking</option> 
		                            <option value="Confirmed" <?php echo isset($booking_type) && $booking_type == 'Confirmed' ? 'selected' :'' ?>>Confirmed</option> 
		                            <option value="Information_Request" <?php echo isset($booking_type) && $booking_type == 'Information_Request' ? 'selected' :'' ?>>Information Request</option> 
		                            <option value="Owner_Booking" <?php echo isset($booking_type) && $booking_type == 'Owner_Booking' ? 'selected' :'' ?>>Owner Booking</option> 
		                            <option value="Not_Available" <?php echo isset($booking_type) && $booking_type == 'Not_Available' ? 'selected' :'' ?>>Not Available</option> 
		                            <option value="Paid" <?php echo isset($booking_type) && $booking_type == 'Paid' ? 'selected' :'' ?>>Paid</option> 
		                            <option value="Pending-payment" <?php echo isset($booking_type) && $booking_type == 'Pending-payment' ? 'selected' :'' ?>>Pending Payment</option> 
		                        </select>
		                    </div>
							<div class="col-md-2 mt-2">
		                        <select class="form-control booking" name="booking">
		                            <option value="">Booking</option> 
		                            <option value="Website" <?php echo isset($booking) && $booking == 'Website' ? 'selected' :'' ?>>Website</option> 
		                            <option value="Mobile" <?php echo isset($booking) && $booking == 'Mobile' ? 'selected' :'' ?>>Mobile</option> 
		                            <option value="Guest_bookings" <?php echo isset($booking) && $booking == 'Guest_bookings' ? 'selected' :'' ?>>Guest Bookings</option> 
		                            <option value="Customer_bookings" <?php echo isset($booking) && $booking == 'Customer_bookings' ? 'selected' :'' ?>>Customer Bookings</option> 
		                        </select>
		                    </div>
		                    
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>

							<input type="hidden" class="form-control upcoming_booking"  value="{{$upcoming_booking}}">
		                    <input type="hidden" class="form-control days"  value="{{$days}}" >



		                    <div class="col-md-2 mt-2"><input type="text" class="form-control booking_id"  placeholder="Booking ID"></div>
							<div class="col-md-4 mt-2">
		                        <select class="form-control guest_id select2" name="guest_id">
		                            <option value="">Select Guest</option> 
									@foreach($guest_users as $guest)
										<option value="{{ $guest->id }}">{{ $guest->name .' (+'.$guest->country_code.' '.$guest->mobile.')' }}</option> 
									@endforeach
		                        </select>
		                    </div>
							<input type="hidden" name="incoming_booking_confirm" class="incoming_booking_confirm" value="{{ isset($incoming_booking_confirm) && !empty($incoming_booking_confirm) ? $incoming_booking_confirm : '' }}">
							<input type="hidden" name="from_date" class="from_date" value="{{ isset($from_date) && !empty($from_date) ? $from_date : '' }}">
							<input type="hidden" name="pending_actions" class="pending_actions" value="{{ isset($pending_actions) && !empty($pending_actions) ? $pending_actions : '' }}">
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
		                        <!-- <a href="{{customeRoute($page.'.create')}}" class="btn btn-primary me-3"><i class="bx bx-plus"></i> Add</a> -->

								<!-- <div class="export_div">
									<span class="export_span btn btn-primary me-3">Export</span>
									<ul class="export_ul" style="display:none;">
										<li>
											<a href="javascript:void(0);" onclick="exportMenuData('Excel')" class="" title="{{ __('Export') }}"><i class="fa fa-download"></i> {{ __('Excel') }}</a>
										</li>
										<li>
											<a href="javascript:void(0);" onclick="exportMenuData('Csv')" class="" title="{{ __('Export') }}"><i class="fa fa-download"></i> {{ __('Csv') }}</a>
										</li>
									</ul>
								</div> -->
		                    </div>
		                    <!-- <div class="col-md-3 d-inline-flex mt-2 float-left">
		                        <a href="javaScript:void(0)" class="btn btn-primary filter me-3"><i class='bx bx-plus'></i>Add Subadmin</a>
		                    </div>   -->                    
		                </div>
		            </div>         
					<div class="card-body">
						<div class="table-responsive">
							<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Booking Id</th>
										<th>Book</th>
										<th>Customer Id</th>
										<th>Guest</th>
										<th>Host</th>
										<th>Total Amount(NGN)</th>
										<th>Platform Fees(NGN)</th>
										<th>Extra Service Amount(NGN)</th>
										<th>Host Amount(NGN)</th>
										<th>Stay</th>
										<th>CheckIn</th>

										<th>Booking Status</th>
										<th>Booking Type</th>
										<th>Booking Date</th>
										<th>Booking From</th>
										<th>Actions</th>
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
	<!--end wrapper-->
	<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript">
		var booking_type = "{{$booking_type}}";
		$('.select2').select2();
		// // var booking_type = 'Pending-payment';

		// if($('.booking_type1').val()) {
		// 	var booking_type = $('.booking_type1').val();
		// }
		// $('.booking_type1').on('change',function(){
		// 	var booking_type = $('.booking_type1').val();
		// 	tables.ajax.reload();
		// })
	// alert(booking_type);
	var tables = $('#datatable').DataTable({
		"bProcessing": true,
		"serverSide": true,
		"pageLength": 10,
		retrieve: true,
		"ajax": {
			url: "{{ customeRoute($page.'.index') }}",
			data: function (d) {
				return $.extend({}, d, {
					'start_date':$('.start_date').val(),
					'end_date':$('.end_date').val(),
					'booking_status':$('.booking_status1').val(),
					'booking_type':$('.booking_type1').val(),
					'booking':$('.booking').val(),
					'incoming_booking_confirm':$('.incoming_booking_confirm').val(),
					'from_date':$('.from_date').val(),
					'pending_actions':$('.pending_actions').val(),
					'booking_id':$('.booking_id').val(),
					'guest_id':$('.guest_id').val(),
					'upcoming_booking':$('.upcoming_booking').val(),
					'days':$('.days').val()
				});
			},
		}, 
		
		"aoColumns": [
			//{mData: 'id'},
			{mData: 'booking_id'},
			{mData: 'book'},
			{mData: 'unique_id'},
			{mData: 'guest_name'},
			{mData: 'host_name'},
			{mData: 'total_amount'},
			{mData: 'admin_amount'},
			{mData: 'extra_service'},
			{mData: 'host_amount'},
			{mData: 'stay'},
			{mData: 'booking_check_in'},
			{mData: 'booking_status'},
			{mData: 'booking_type'},
			{mData: 'created_at'},
			{mData: 'booking_from'},
			{mData: 'actions'}
		],
			"aoColumnDefs": [
			{"bSortable": false, "aTargets": ['action']},
			{ "orderable": false, "targets": [1, 2, 3, 4,10, 13] }
		],
		"order": [[12, "desc"]],
		
		language: {
			searchPlaceholder: "Search by accommodation"
		}, 
	});
	$('.refresh').click(function (e){
		$('.start_date').val("");
		$('.end_date').val("");
		$('.booking_status1').val('');
		$('.booking_type1').val('');
		$('.booking').val('');
		$('.incoming_booking_confirm').val('');
		$('.from_date').val('');
		$('.pending_actions').val('');
		$('.booking_id').val('');
		$('.guest_id').val('');
		tables.ajax.reload();
	});
	$('.filter').click(function (e) {
		tables.ajax.reload();
	});

	$(document).on('click','.delete_btn',function(){
		var id = $(this).data('property_id');
		if(id != undefined){
			if(window.confirm('Are you sure want to delete this Booking?')) {
				var path = $(this).data('path');
				$('.loader').show();
				$.ajax({
					url: '{{url('admin/booking/delete')}}'+'/'+id,
					method: 'get',
					success: function(result){
						if(result.status == true){
							toastr.success(result.message);
						}else{
							toastr.error(result.message);
						}
						setTimeout(function () {
							location.reload(true);
						}, 2000);
					}
				});
			}else{
				toastr.error(res.message);
				// var oldValue = $(this).attr('data-value');
				// $(this).val(oldValue);
				return false;
			}
		}else{
			var oldValue = $(this).attr('data-value');
			$(this).val(oldValue);
			return false;
		}
	});

	/*Export Excel and Csv Function */
	function exportMenuData(file_type) {
		// alert(file_type);
		$(".overlay").fadeIn(300);
		var link = '?';
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		// alert(link);
		var url = "{{ url('admin/booking/exportProperties') }}";
		// var url = "{{ url('admin/property/exportProperties') }}"+link;
		$.ajax({
			type: 'post',
			dataType:'json',
			data:{'file_type':file_type},
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

	$(document).on('change','.booking_status',function(){
		var id = $(this).attr('id');
		var value = $(this).val();
		let statusMsg = "";
		statusMsg = 'Are you sure you want to change booking status?';
		if(window.confirm(statusMsg)) {
			var path = $(this).data('path');
			$('.loader').show();
			$.ajax({
				url:path,
				method: 'get',
				data: {'id':id,'value':value},
				success: function(result){
					tables.ajax.reload();  
					if(result.status == true){
						toastr.success(result.message);
					}else{
						toastr.error(result.message);
					}
					// sweetalert(result.type,result.message);
					$('.loader').hide();
				}
			});        
		}else{
			var oldValue = $(this).attr('data-value');
			$(this).val(oldValue);
			return false;
		}
	});

	$(document).on('change','.booking_type',function(){
		var id = $(this).attr('id');
		var value = $(this).val();
		let statusMsg = "";
		statusMsg = 'Are you sure you want to change booking type?';
		if(window.confirm(statusMsg)) {
			var path = $(this).data('path');
			$('.loader').show();
			$.ajax({
				url:path,
				method: 'get',
				data: {'id':id,'value':value},
				success: function(result){
					tables.ajax.reload();  
					if(result.status == true){
						toastr.success(result.message);
					}else{
						toastr.error(result.message);
					}
					// sweetalert(result.type,result.message);
					$('.loader').hide();
				}
			});        
		}else{
			var oldValue = $(this).attr('data-value');
			$(this).val(oldValue);
			return false;
		}
	});

	$(document).ready(function(){
		// $('.guest_select2').select2();
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
	});
</script>
@endsection