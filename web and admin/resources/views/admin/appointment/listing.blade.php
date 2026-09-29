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
		                    
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
		                    <div class="col-md-2 mt-2">
		                        <select class="form-control status" name="status">
		                            <option value="">Select Status</option> 
		                            <option value="Upcoming">Upcoming</option> 
		                            <option value="Expire">Expire</option> 
		                        </select>
		                    </div>
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
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
										<th>Name</th>
										<th>Email</th>
										<th>Mobile Number</th>
										<th>Appointment DateTime</th>
										<th>Address</th>
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
		$(document).on('click','.delete_btn',function(){
			var id = $(this).data('property_id');
		
			if(id != undefined){
				if(window.confirm('Are you sure want to cancel the appointment ?')) {
					var path = $(this).data('path');               
					$('.loader').show();
					$.ajax({
						url: "{{url('admin/appointment/cancel')}}"+"/"+id,
						method: 'get',
						success: function(result){
							// console.log('result--'+result);
							if(result.status == true){
								// console.log('inn--');
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
                        'status':$('.status').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                {mData: 'name'},
                {mData: 'email'},
                {mData: 'mobile'},
                {mData: 'appointment_date'},
                {mData: 'address'},
                {mData: 'created_at'},
                {mData: 'actions'}
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                { "orderable": false, "targets": [6] }
            ],
            "order": [[3, "desc"]],
            
            language: {
                searchPlaceholder: "Search by name"
            }, 
        });
        $('.refresh').click(function (e){
            $('.start_date').val("");
            $('.end_date').val("");
            $('.status').val('');
            tables.ajax.reload();
        });
        $('.filter').click(function (e) {
            tables.ajax.reload();
        });

        $(document).ready(function(){
            $(".start_date").datepicker({
                 minDate: "-1Y",
                 maxDate: "+0D",
                 numberOfMonths: 1,
                 dateFormat:'yy-mm-dd',
                 onSelect: function(selected) {
                   $(".end_date").datepicker("option","minDate", selected)
                 }
			});
			$(".end_date").datepicker({
                 minDate:"-1Y",
                 maxDate:"+0D",
                 numberOfMonths: 1,
                 dateFormat:'yy-mm-dd',
                 onSelect: function(selected) {
                    $(".start_date").datepicker("option","maxDate", selected)
                 }
			});
		});
	</script>
@endsection