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
							<div class="col-md-2 mt-2">
		                        <select class="form-control booking_type" name="booking_type">
		                            <option value="">Status</option> 
		                            <option value="Pre-booking" <?php echo isset($booking_type) && $booking_type == 'Pre-booking' ? 'selected' :'' ?>>Pre booking</option> 
		                            <option value="Confirmed" <?php echo isset($booking_type) && $booking_type == 'Confirmed' ? 'selected' :'' ?>>Confirmed</option> 
		                            <option value="Information_Request" <?php echo isset($booking_type) && $booking_type == 'Information_Request' ? 'selected' :'' ?>>Information Request</option> 
		                            <option value="Owner_Booking" <?php echo isset($booking_type) && $booking_type == 'Owner_Booking' ? 'selected' :'' ?>>Owner Booking</option> 
		                            <option value="Not_Available" <?php echo isset($booking_type) && $booking_type == 'Not_Available' ? 'selected' :'' ?>>Not Available</option> 
		                            <option value="Paid" <?php echo isset($booking_type) && $booking_type == 'Paid' ? 'selected' :'' ?>>Paid</option> 
		                        </select>
		                    </div>
		                    
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control booking_id"  placeholder="Booking ID"></div>
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
		                    </div>
		                </div>
		            </div>         
					<div class="card-body">
						<div class="table-responsive">
								<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Accommodation</th>
										<th>Customer ID</th>
										<th>Code</th>
										<th>Reference No.</th>
										<th>No. of nights</th>
										<th>Amount</th>
										<th>Stay</th>
										<th>Booking Type</th>
										<th>Status</th>
										<th>Booking Date</th>
										<!--th>Actions</th-->
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
					'booking_status':$('.booking_status').val(),
					'booking_type':$('.booking_type').val(),
					'booking_id':$('.booking_id').val(),
				});
			},
		}, 
		
		"aoColumns": [
			//{mData: 'id'},
			{mData: 'book'},
			{mData: 'customer_id'},
			{mData: 'unique_id'},
			{mData: 'reference_no'},
			{mData: 'total_days'},
			
			{mData: 'total_amount'},
			{mData: 'stay'},
			{mData: 'booking_type'},
			{mData: 'booking_status'},
			{mData: 'created_at'}
			//{mData: 'actions'}
		],
			"aoColumnDefs": [
			{"bSortable": false, "aTargets": ['action']},
			{ "orderable": false, "targets": [0, 1, 2, 3, 4] }
		],
		"order": [[6, "desc"]],
		
		language: {
			searchPlaceholder: "Search"
		}, 
	});
	$('.refresh').click(function (e){
		$('.start_date').val("");
		$('.end_date').val("");
		$('.booking_status').val('');
		$('.booking_type').val('');
		$('.booking_id').val('');
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
	});
</script>
@endsection