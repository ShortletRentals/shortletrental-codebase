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
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.'.$page.'.index')}}">{{$title}}</a></li>
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
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
								<button type="button" class="btn btn-primary me-3 ms-2 export_btn" onclick="exportMenuData('Excel')" data-discount_id="{{ $id }}"><i class="bx bx-download"></i>Export</button>
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
										<th>S No.</th>
										<th>Customer Name</th>
										<th>Discount Code</th>
										<th>Amount</th>
										<th>Date & Time</th>
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
				if(window.confirm('Are you sure want to delete this Discount?')) {
					var path = $(this).data('path');               
					$('.loader').show();
					$.ajax({
						url: '{{url('admin/discount/delete')}}'+'/'+id,
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
                url: "{{ url('admin/'.$page.'/discount_customer_view/'.$id ) }}",
                data: function (d) {
                    return $.extend({}, d, {
                        'start_date':$('.start_date').val(),
                        'end_date':$('.end_date').val(),
                        // 'status':$('.status').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                {mData: 's_no'},
                {mData: 'customer_name'},
                {mData: 'coupon_code'},
                {mData: 'discount_amount'},
                {mData: 'created_at'},
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                // { "orderable": false, "targets": [2,3] }
            ],
            "order": [[4, "desc"]],
            
            language: {
                searchPlaceholder: "Search"
            }, 
        });
        $('.refresh').click(function (e){
            $('.start_date').val("");
            $('.end_date').val("");
            // $('.status').val('');
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
			// var booking_status = $('.booking_status').val();
			var start_date = $('.start_date').val();
			var end_date = $('.end_date').val();
			var discount_id = $('.export_btn').data('discount_id');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			// alert(link);
			var url = "{{ url('admin/discount/discount_customer_export') }}";
			$.ajax({
				type: 'post',
				dataType:'json',
				data:{'file_type':file_type,'start_date':start_date,'discount_id':discount_id,'end_date':end_date},
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