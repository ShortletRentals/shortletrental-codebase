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
		                    <!-- <div class="col-md-2 mt-2">
		                        <select class="form-control status" name="status">
		                            <option value="">Select Status</option> 
		                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' :'' ?>>Active</option> 
		                            <option value="0">Inactive</option> 
		                        </select>
		                    </div> -->
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="date_of" id="date_of" class="form-control date_of" placeholder="Select Date of" data-dropdown-css-class="select2-primary">
									<option value="from_date" <?php echo isset($date_of) && $date_of == 'from_date' ? 'selected' :'' ?>>Check-In</option>
									<option value="to_date" <?php echo isset($date_of) && $date_of == 'to_date' ? 'selected' :'' ?>>Check-Out</option>
								</select>
							</div>
							<div class="col-md-2 mt-2">
		                        <select class="form-control booking_status" name="booking_status">
		                            <option value="">Booking Status</option> 
		                            <option value="Not-confirmed-by-Host" <?php echo isset($booking_type) && $booking_type == 'Not-confirmed-by-Host' ? 'selected' :'' ?>>Not confirmed by Host</option> 
		                            <option value="Confirmed-by-Host" <?php echo isset($booking_type) && $booking_type == 'Confirmed-by-Host' ? 'selected' :'' ?>>Confirmed by Host</option> 
		                            <option value="Ongoing-Booking" <?php echo isset($booking_type) && $booking_type == 'Ongoing-Booking' ? 'selected' :'' ?>>Ongoing Booking</option> 
		                            <option value="Completed-Booking" <?php echo isset($booking_type) && $booking_type == 'Completed Booking' ? 'selected' :'' ?>>Completed Booking</option> 
		                            <option value="Cancelled-Booking" <?php echo isset($booking_type) && $booking_type == 'Cancelled-Booking' ? 'selected' :'' ?>>Cancelled Booking</option>
		                        </select>
		                    </div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="country" id="country" class="form-control search_country" data-placeholder="Select Country" data-dropdown-css-class="select2-primary">
									<option value="">Select Country</option>
									@if(count($all_countries) > 0)
									@foreach($all_countries as $country)
										<option value="{{$country->id}}">{{ $country->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="province" id="province" class="form-control search_province" data-placeholder="Select Province" data-dropdown-css-class="select2-primary">
									<option value="">Select Province</option>
									@if(count($all_province) > 0)
									@foreach($all_province as $province)
										<option value="{{$province->id}}">{{ $province->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="city" id="city" class="form-control search_city" data-placeholder="Select City" data-dropdown-css-class="select2-primary">
									<option value="">Select City</option>
									@if(count($all_cities) > 0)
									@foreach($all_cities as $city)
										<option value="{{$city->id}}">{{ $city->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="area" id="area" class="form-control search_area" data-placeholder="Select Area" data-dropdown-css-class="select2-primary">
									<option value="">Select Area</option>
									@if(count($all_area) > 0)
									@foreach($all_area as $area)
										<option value="{{$area->id}}" <?php echo isset($search_area) && $search_area == $area->id ? 'selected' :'' ?>>{{ $area->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<!-- <label for="inputName" class="form-label">Type</label> -->
								<select name="type[]" id="type" class="form-control search_type_list" data-placeholder="Select Type" data-dropdown-css-class="select2-primary" multiple>
									<option value="All">All</option>
									<option value="Aparthotel" <?php echo isset($search_type_list) &&  ($search_type_list == 'Aparthotel')  ? 'selected' : '' ?>>Aparthotel</option>
									<option value="Apartment" <?php echo isset($search_type_list) &&  ($search_type_list == 'Apartment')  ? 'selected' : '' ?>>Apartment</option>
									<option value="Boat" <?php echo isset($search_type_list) &&  ($search_type_list == 'Boat')  ? 'selected' : '' ?>>Boat</option>
									<option value="Bungalow" <?php echo isset($search_type_list) &&  ($search_type_list == 'Bungalow')  ? 'selected' : '' ?>>Bungalow</option>
									<option value="Chalet" <?php echo isset($search_type_list) &&  ($search_type_list == 'Chalet')  ? 'selected' : '' ?>>Chalet</option>
									<option value="Cottage" <?php echo isset($search_type_list) &&  ($search_type_list == 'Cottage')  ? 'selected' : '' ?>>Cottage</option>
									<option value="Country_house" <?php echo isset($search_type_list) &&  ($search_type_list == 'Country_house')  ? 'selected' : '' ?>>Country house</option>
									<option value="Farm_stay" <?php echo isset($search_type_list) &&  ($search_type_list == 'Farm_stay')  ? 'selected' : '' ?>>Farm stay</option>
									<option value="Garage" <?php echo isset($search_type_list) &&  ($search_type_list == 'Garage')  ? 'selected' : '' ?>>Garage</option>
									<option value="House" <?php echo isset($search_type_list) &&  ($search_type_list == 'House')  ? 'selected' : '' ?>>House</option>
									<option value="Mobile_home" <?php echo isset($search_type_list) &&  ($search_type_list == 'Mobile_home')  ? 'selected' : '' ?>>Mobile home</option>
									<option value="Rent_by_room" <?php echo isset($search_type_list) &&  ($search_type_list == 'Rent_by_room')  ? 'selected' : '' ?>>Rent by room</option>
									<option value="Residence" <?php echo isset($search_type_list) &&  ($search_type_list == 'Residence')  ? 'selected' : '' ?>>Residence</option>
									<option value="Studio" <?php echo isset($search_type_list) &&  ($search_type_list == 'Studio')  ? 'selected' : '' ?>>Studio</option>
									<option value="Townhouse" <?php echo isset($search_type_list) &&  ($search_type_list == 'Townhouse')  ? 'selected' : '' ?>>Townhouse</option>
									<option value="Trullo" <?php echo isset($search_type_list) &&  ($search_type_list == 'Trullo')  ? 'selected' : '' ?>>Trullo</option>
									<option value="Villa" <?php echo isset($search_type_list) &&  ($search_type_list == 'Villa')  ? 'selected' : '' ?>>Villa</option>
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<!-- <label for="inputName" class="form-label">Type</label> -->
								<select name="search_category[]" id="search_category" class="form-control search_category" data-placeholder="Select Category" data-dropdown-css-class="select2-primary" multiple>
									<option value="All">All</option>
									@if(count($all_categories) > 0)
									@foreach($all_categories as $category)
										<option value="{{$category->id}}" <?php echo isset($search_category) && $search_category == $category->id ? 'selected' :'' ?>>{{ $category->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							@if(auth()->user()->user_type == 1)
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="host[]" id="host" class="form-control owner_select2" data-placeholder="Select Owner" data-dropdown-css-class="select2-primary" multiple>
									<option value="All">All</option>
									@if(count($host_users) > 0)
									@foreach($host_users as $host)
										<option value="{{$host->id}}" <?php echo isset($data->host) &&  ($data->host == $host->id)  ? 'selected' : '' ?>>{{ $host->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							@endif
							<!--div class="col-md-2 mt-2">
								<input type="text" class="form-control filter_by_date" name="filter_by_date" placeholder="Date">
							</div-->
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
								<button type="button" class="btn btn-primary me-3 ms-2" onclick="exportMenuData('Excel')"><i class="bx bx-download"></i>Export</button>
		                        <!-- <a href="{{customeRoute($page.'.create')}}" class="btn btn-primary me-3"><i class="bx bx-plus"></i> Add</a> -->
		                    </div>
		                    <!-- <div class="col-md-3 d-inline-flex mt-2 float-left">
		                        <a href="javaScript:void(0)" class="btn btn-primary filter me-3"><i class='bx bx-plus'></i>Add Subadmin</a>
		                    </div>   -->
		                </div>
		            </div>         
					<div class="card-body">
					<!-- 	<div class="d-lg-flex align-items-center mb-4 gap-3">
							<div class="position-relative">
								<input type="text" class="form-control ps-5 radius-30" placeholder="Search Admin"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
							</div>
						  <div class="ms-auto"><a href="javascript:;" class="btn btn-light radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Admin</a></div>
						</div> -->
						<div class="table-responsive">
								<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Order ID</th>
										<th>Transaction ID</th>
										<th>Guest Name & Number</th>
										<th>Host Name & Number</th>
										<th>Total Amount(NGN)</th>
										<th>Platform Fees(NGN)</th>
										<th>Accommodation Type</th>
										<th>Category</th>
										<th># of Guests & Children</th>
										<th>Location</th>
										<th>Discount Code & Amount</th>
										<th>Settlement</th>
										<th>Status</th>
										<th>Added Date</th>
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
		$('.owner_select2').select2();
		// $('.search_category').select2();
		// $(document).ready(function(){
			$('.search_category').select2();
		// });
		$('.search_type_list').select2();

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
                        'booking_status':$('.booking_status').val(),
                        'search_country':$('#country :selected').val(),
                        'search_province':$('#province :selected').val(),
                        'search_city':$('#city :selected').val(),
                        'search_area':$('.search_area').val(),
                        'search_type_list':$('.search_type_list').val(),
                        'search_category':$('.search_category').val(),
                        'host':$('.owner_select2').val(),
						//'filter_by_date':$('.filter_by_date').val(),
                        // 'host':$('#host :selected').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                {mData: 'booking_id'},
                {mData: 'reference'},
                {mData: 'guestData'},
                {mData: 'hostData'},
                {mData: 'total_amount'},
                {mData: 'admin_amount'},
                {mData: 'type'},
                {mData: 'category'},
                {mData: 'no_of_adult_guest'},
                {mData: 'location'},
                {mData: 'coupon_code'},
                {mData: 'sattlement'},
                {mData: 'status'},
                {mData: 'created_at'},
                {mData: 'actions'}
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                { "orderable": false, "targets": [6, 7, 9, 14] }
            ],
            "order": [[13, "desc"]],
            
            language: {
                searchPlaceholder: "Search"
            }, 
        });
        $('.refresh').click(function (e){
			location.reload();
            // $('.start_date').val("");
            // $('.end_date').val("");
            // $('.booking_status').val('');
            // $('#country').val('');
            // $('#province').val('');
            // $('#city').val('');
            // $('.search_area').val('');
            // $('.search_type_list').val('');
            // // $('.search_category').val('');
            // // $('#search_category').select2("val","");
			// // $('.search_category').trigger('change');
			// $("#search_category").val(null).trigger("change");
			// // $("#search_category").select2('destroy').val('').select2();
            // $('#host').val('');
            // tables.ajax.reload();
        });
        $('.filter').click(function (e) {
            tables.ajax.reload();
        });


		/*Export Excel and Csv Function */
		function exportMenuData(file_type) {
			// alert(file_type);
			$(".overlay").fadeIn(300);
			var link = '?';
			var booking_status = $('.booking_status').val();
			var start_date = $('.start_date').val();
			var end_date = $('.end_date').val();
			var search_country = $('#country :selected').val();
			var search_province = $('#province :selected').val();
			var search_city = $('#city :selected').val();
			var search_area = $('.search_area').val();
			var search_type_list = $('.search_type_list').val();
			var search_category = $('.search_category').val();
			var host = $('.owner_select2').val();
			//var filter_by_date = $('.filter_by_date').val();
			var date_of = $('.date_of').val();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			// alert(link);
			var url = "{{ url('admin/transection/export') }}";
			$.ajax({
				type: 'post',
				dataType:'json',
				data:{'file_type':file_type,'booking_status':booking_status,'start_date':start_date,'end_date':end_date,'host':host,'search_type_list':search_type_list,'search_category':search_category,'search_country':search_country,'search_province':search_province,'search_city':search_city,'search_area':search_area,date_of:date_of},
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

		$(document).on('change','.sattlement_status',function(){
			var id = $(this).attr('id');
			var value = $(this).val();
			let statusMsg = "";
			statusMsg = 'Are you sure you want to sattlement this transaction?';
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
							// setInterval(function () {  
							// 	location.reload();
							// }, 1000); 
						}else{
							toastr.error(result.message);
						}
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
            $(".start_date").datepicker({
                 minDate: "-1Y",
                 /*maxDate: "+0D",*/
                 numberOfMonths: 1,
                 dateFormat:'yy-mm-dd',
                 onSelect: function(selected) {
                   $(".end_date").datepicker("option","minDate", selected)
                 }
             });
             $(".end_date").datepicker({
                 minDate:"-1Y",
                 /*maxDate:"+0D",*/
                 numberOfMonths: 1,
                 dateFormat:'yy-mm-dd',
                 onSelect: function(selected) {
                    $(".start_date").datepicker("option","maxDate", selected)
                 }
             });
         });
	</script>
@endsection