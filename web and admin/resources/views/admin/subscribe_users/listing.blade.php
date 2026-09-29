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
		                    <div class="col-md-4 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>

								<button type="button" class="btn btn-primary filter me-3" onclick="exportMenuData('Excel')" >Export</button>
		                    </div>
							<div class="export_div">
								<!-- <span class="export_span btn btn-primary me-3">Export</span> -->
								<!-- <ul class="export_ul" style="display:none;">
									<li> -->
										<!-- <button type="button" class="btn btn-primary filter me-3" onclick="exportMenuData('Excel')" >Export</button> -->
										<!-- <a href="javascript:void(0);" onclick="exportMenuData('Excel')" class="" title="{{ __('Export') }}"><i class="fa fa-download"></i> {{ __('Excel') }}</a> -->
									<!-- </li> -->
									<!-- <li>
										<a href="javascript:void(0);" onclick="exportMenuData('Csv')" class="" title="{{ __('Export') }}"><i class="fa fa-download"></i> {{ __('Csv') }}</a>
									</li> -->
								<!-- </ul> -->
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
										<th>Subscribe Type</th>
										<th>Email</th>
										<!-- <th>Message</th> -->
										<!-- <th>Status</th> -->
										<th>Added Date</th>
										<!-- <th>Actions</th> -->
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
                        // 'status':$('.status').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                {mData: 'type'},
                {mData: 'email'},
                // {mData: 'message'},
                // {mData: 'status'},
                {mData: 'created_at'},
                // {mData: 'actions'}
            ],
             "aoColumnDefs": [
                // {"bSortable": false, "aTargets": ['action']},
                // { "orderable": false, "targets": [2] }
            ],
            "order": [[2, "desc"]],
            
            language: {
                searchPlaceholder: "Search"
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

		/*Export Excel and Csv Function */
		function exportMenuData(file_type) {
			$(".overlay").fadeIn(300);
			var link = '?';
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			// alert(link);
			var url = "{{ url('admin/subscribe_users/exportSubscribeUsers') }}";
			// var url = "{{ url('admin/property/exportProperties') }}"+link;
			$.ajax({
				type: 'post',
				dataType:'json',
				data:{'file_type':file_type},
				url: url,
				success:function(response){
					// console.log('response--'+response);
					if(response.status == 1){
						// var hiddenElement = document.createElement('a');  
						// hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);  
						// hiddenElement.target = '_blank';  
						
						// //provide the name for the CSV file to be downloaded  
						// hiddenElement.download = 'Famous Personalities.csv';  
						// hiddenElement.click();  

						window.open(response.url,'_blank' );
						$('.export_ul').toggle();
					}
					$(".overlay").fadeOut(300);
					// var aLink = document.createElement('a');
					// var evt = document.createEvent("HTMLEvents");
					// evt.initEvent("click");
					// aLink.href = response.url;
					// aLink.download="warehouse.csv"
					// aLink.click(evt);
					// $(".overlay").fadeOut(300);
					// window.location.reload();
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
	<script>
		$(document).ready(function() {
			var table = $('#datatable').DataTable({
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			});
		 
			table.buttons().container()
				.appendTo( '#datatable_wrapper .col-md-6:eq(0)' );
		});
	</script>
@endsection