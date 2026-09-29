@extends('layouts.master')
@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

		<section id="first-load">
			<span>Loading...</span>
			<img src="{{ URL::asset('assets/web/img/logo.png')}}" alt="logo" width="auto">
			<div class="box-loader">
				<div class="container">
					<span class="circle-loader"></span>
					<span class="circle-loader"></span>
					<span class="circle-loader"></span>
					<span class="circle-loader"></span>
				</div>
			</div>
      	</section>
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
		                        <select class="form-control status" name="status">
		                            <option value="">Select Status</option> 
		                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' :'' ?>>Active</option> 
		                            <option value="0">Inactive</option> 
		                        </select>
		                    </div>
		                
		                    
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
		                    <div class="col-md-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
		                    <div class="col-md-6 d-inline-flex mt-2">
		                        <button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
		                        <button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
								@can('Host-create')
		                        <a href="{{customeRoute($page.'.create')}}" class="btn btn-primary me-3"><i class="bx bx-plus"></i> Add</a>
								<a href="javascript:void(0)" class="btn btn-primary import_btn" title="{{ __('Import') }}" onclick="importModal(this)"><i class="fa fa-upload"></i> {{ __('Import') }}</a>
								@endcan
								<button type="button" class="btn btn-primary me-3 ms-2" onclick="exportMenuData('Excel')"><i class="bx bx-download"></i>Export</button>
		                    </div>           
		                </div>
		            </div>         
					<div class="card-body">
						<div class="table-responsive">
								<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Host Name</th>
										<th>Mobile Number</th>
										<th>Email Address</th>
										<th>Super Host</th>
										<th>Status</th>
										<th>Register Date</th>
										<!-- <th>View Details</th> -->
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

		<!--Add Building Modal -->
		<div class="modal fade" id="ownerImportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Import Owner</h5>
						<button type="button" class="close ownerModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

					<!-- <form method="POST" action="" id="import_dish_data" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<input type="hidden" class="popup_product_for" name="product_for" value="">

							<div class="col-md-6">
								<div class="form-group">
								<label class="col-md-6" for="file">Import Excel File</label>
								<input type="file" name="file" class="form-control">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group mt-btn">
								<a href="{{url('public/uploads/Dish_import_sample.xlsx')}}" class="btn btn-success btn-lg" title="Demo Download"><i class="fas fa-file-excel" aria-hidden="true"></i> Sample File</a>
								</div>
							</div>
						</div>				

						<hr style="margin: 1em -15px">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary float-right save"><span class="spinner-grow spinner-grow-sm formloader"
								style="display: none;" role="status" aria-hidden="true"></span> Save</button>
					</form> -->

						<form action="" class="formAction" id="ownerFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<!-- <div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div> -->
											<div class="col-md-8 mb-3">
												<label for="inputMobile" class="form-label">Import Excel File</label>
												<div class="input-group">
													<!-- <div class="form-control" onclick="document.getElementById('file_build').click()"> -->
													<div class="form-control" >
														<label for="files">Select File</label>
														<input type="file" id="file" name="file" class="form-control" required>
													</div>
												</div>
											</div>
											<div class="col-md-4 mb-3">
												<div class="form-group mt-btn">
												<a href="{{url('public/uploads/Owners_import_sample.xls')}}" class="btn btn-success" title="Demo Download"><i class="fas fa-file-excel" aria-hidden="true"></i> Sample File</a>
												</div>
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light ownerModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary ownerModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Building Modal End -->
	<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript">
  		$("#first-load").fadeOut(1000); 
		function importModal($this){
			$('#ownerImportModal').modal('show');
		}
		$(document).ready(function(){
			$(".ownerModalClose").click(function(){
				$("#ownerImportModal").modal('toggle');
			});
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
                {mData: 'mobile'},
                {mData: 'email'},
                {mData: 'is_super_host'},
                {mData: 'status'},
                {mData: 'created_at'},
                {mData: 'actions'}
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                { "orderable": false, "targets": [3, 6] }
            ],
            "order": [[5, "desc"]],
            
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

		$("#ownerFormSubmit").on('submit',function(e){
			e.preventDefault();
			var _this=$(this); 
    		$("#first-load").fadeIn(1000);
			var name = $('#name').val();
			var formData = new FormData(this);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			var url = '{{ url("admin/host/importData") }}';
			if(name != ''){
				$.ajax({
					url:url,
					// dataType:'html',
					dataType:'json',
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					type:'POST',
					success:function(result){
						console.log('result--'+result);
        				$("#first-load").fadeOut();
						// var result1 = JSON.parse(result);
						if(result.status == true){
							toastr.success(result.message);
							// $('.main_building_div').html(result.message);
							$('#ownerImportModal').modal('hide');
							$('#ownerFormSubmit')[0].reset();
							setTimeout(function(){
								location.reload();
								// window.location.replace("{{ route('admin.rate.index') }}");
							}, 2000);
						}else{
							toastr.error(result.message);
						}
					},
					error:function(jqXHR,textStatus,textStatus){
						if(jqXHR.responseJSON.errors){
							$.each(jqXHR.responseJSON.errors, function( index, value ) {
								toastr.error(value)
							});
						}else{
							toastr.error(jqXHR.responseJSON.message)
						}
					}
				});
			}
			return false;   
		});

		$(document).on('click','.delete_btn',function(){
			var id = $(this).data('property_id');
			if(id != undefined){
				if(window.confirm('Are you sure want to delete this accommodation?')) {
					var path = $(this).data('path');               
					$('.loader').show();
					$.ajax({
						// url:path,
						url: '{{url('admin/host/delete')}}'+'/'+id,
						method: 'get',
						// data: {'id':id,'value':value},
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

		$(document).on('click','.superHostChange',function(){
			var id = $(this).attr('id');
			if ($(this).prop('checked')==true){ 
				value = 'Yes';
			}else{
				value = 'No';
			}
			$.ajax({
				url:"{{ url('admin/host/change_superhost') }}",
				method: 'post',
				dataType:'json',
				data: {_token: "{{ csrf_token() }}",'id':id,'value':value},
				success: function(res){
					if(res.status === 1){
						toastr.success(res.message);
					}else{
						toastr.error(res.message);
					}
				}
			});
		});

		/*Export Excel and Csv Function */
		function exportMenuData(file_type) {
			$(".overlay").fadeIn(300);
			var link = '?';
			var start_date = $('.start_date').val();
			var end_date = $('.end_date').val();
			var status = $('.status').val();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			var url = "{{ url('admin/host/exportData') }}";
			$.ajax({
				type: 'post',
				dataType:'json',
				data:{
					'file_type':file_type,
					'start_date':start_date,
					'end_date':end_date,
					'status':status
				},
				url: url,
				success:function(response){
					if(response.status == 1){ 

						window.open(response.url,'_blank' );
						$('.export_ul').toggle();
					}
					$(".overlay").fadeOut(300);
				},
				error:function(jqXHR,textStatus,textStatus){
					console.log(jqXHR);
					toastr.error(jqXHR.statusText)
				}
			});
		}
		/*Export Excel and Csv Function End*/
	</script>
@endsection