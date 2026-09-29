@extends('layouts.master')
@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<?php 
// dd(auth()->user()->user_type);
?>
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
		                 	<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select class="form-control status" name="status">
									<option value="">Select Status</option> 
									<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' :'' ?>>Active</option> 
									<option value="0">Inactive</option> 
								</select>
			                </div>
                 			<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2"><input type="text" class="form-control start_date" placeholder="From Date"></div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2"><input type="text" class="form-control end_date"  placeholder="To Date"></div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="city" id="city" class="form-control" data-placeholder="Select City" data-dropdown-css-class="select2-primary">
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
							@if(in_array(auth()->user()->user_type,[1,2]))
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="host" id="host" class="form-control owner_select2" placeholder="Select Owner" data-dropdown-css-class="select2-primary">
									<option value="">Select Owner</option>
									@if(count($host_users) > 0)
									@foreach($host_users as $host)
										<option value="{{$host->id}}" <?php echo isset($data->host) &&  ($data->host == $host->id)  ? 'selected' : '' ?>>{{ $host->name.' '.$host->surname }}</option>
									@endforeach
									@endif
								</select>
							</div>
							@endif
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="max_guest_capacity" id="max_guest_capacity" class="form-control max_guest_capacity" data-placeholder="Select Max Guest Capacity" data-dropdown-css-class="select2-primary">
									<option value="" selected>Select Max Guest Capacity</option>
									<option value="1" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 1 ? 'selected' :'' ?>>1</option>
									<option value="2" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 2 ? 'selected' :'' ?>>2</option>
									<option value="3" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 3 ? 'selected' :'' ?>>3</option>
									<option value="4" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 4 ? 'selected' :'' ?>>4</option>
									<option value="5" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 5 ? 'selected' :'' ?>>5</option>
									<option value="6" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 6 ? 'selected' :'' ?>>6</option>
									<option value="7" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 7 ? 'selected' :'' ?>>7</option>
									<option value="8" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 8 ? 'selected' :'' ?>>8</option>
									<option value="9" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 9 ? 'selected' :'' ?>>9</option>
									<option value="10" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 10 ? 'selected' :'' ?>>10</option>
									<option value="11" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 11 ? 'selected' :'' ?>>11</option>
									<option value="12" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 12 ? 'selected' :'' ?>>12</option>
									<option value="13" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 13 ? 'selected' :'' ?>>13</option>
									<option value="14" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 14 ? 'selected' :'' ?>>14</option>
									<option value="15" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 15 ? 'selected' :'' ?>>15</option>
									<option value="16" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 16 ? 'selected' :'' ?>>16</option>
									<option value="17" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 17 ? 'selected' :'' ?>>17</option>
									<option value="18" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 18 ? 'selected' :'' ?>>18</option>
									<option value="19" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 19 ? 'selected' :'' ?>>19</option>
									<option value="20" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 20 ? 'selected' :'' ?>>20</option>
								</select>
										</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<select name="search_bedroom" id="search_bedroom" class="form-control search_bedroom" data-placeholder="Select Bedrooms" data-dropdown-css-class="select2-primary">
									<option value="" selected>Select Bedrooms</option>
									<option value="All" <?php echo isset($search_bedrooms) && $search_bedrooms == 'All' ? 'selected' :'' ?>>All</option>
									<option value="1" <?php echo isset($search_bedrooms) && $search_bedrooms == 1 ? 'selected' :'' ?>>1</option>
									<option value="2" <?php echo isset($search_bedrooms) && $search_bedrooms == 2 ? 'selected' :'' ?>>2</option>
									<option value="3" <?php echo isset($search_bedrooms) && $search_bedrooms == 3 ? 'selected' :'' ?>>3</option>
									<option value="4" <?php echo isset($search_bedrooms) && $search_bedrooms == 4 ? 'selected' :'' ?>>4</option>
									<option value="5" <?php echo isset($search_bedrooms) && $search_bedrooms == 5 ? 'selected' :'' ?>>5</option>
									<option value="6" <?php echo isset($search_bedrooms) && $search_bedrooms == 6 ? 'selected' :'' ?>>6</option>
									<option value="7" <?php echo isset($search_bedrooms) && $search_bedrooms == 7 ? 'selected' :'' ?>>7</option>
									<option value="8" <?php echo isset($search_bedrooms) && $search_bedrooms == 8 ? 'selected' :'' ?>>8</option>
									<option value="9" <?php echo isset($search_bedrooms) && $search_bedrooms == 9 ? 'selected' :'' ?>>9</option>
									<option value="10" <?php echo isset($search_bedrooms) && $search_bedrooms == 10 ? 'selected' :'' ?>>10</option>
								</select>
										</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<!-- <label for="inputName" class="form-label">Type</label> -->
								<select name="type" id="type" class="form-control search_type_list" placeholder="Select Type" data-dropdown-css-class="select2-primary">
									<option value="">Select Type</option>
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
								<select name="search_category" id="search_category" class="form-control search_category" placeholder="Select Category" data-dropdown-css-class="select2-primary">
									<option value="">Select Category</option>
									@if(count($all_categories) > 0)
									@foreach($all_categories as $category)
										<option value="{{$category->id}}" <?php echo isset($search_category) && $search_category == $category->id ? 'selected' :'' ?>>{{ $category->name }}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2"><input type="text" name="price" class="form-control price"  placeholder="Price Per Night"></div>
							<div class="col-md-3 col-lg-3 col-xl-3 xxl-2 mt-2">
								<!-- <label for="inputName" class="form-label">Type</label> -->
								<select name="book_type" id="book_type" class="form-control search_book_type" placeholder="Select Book Type" data-dropdown-css-class="select2-primary">
									<option value="">Select Book Type</option>
									<option value="Book_now" <?php echo isset($search_book_type) &&  ($search_book_type == 'Book_now')  ? 'selected' : '' ?>>Book Now</option>
									<option value="Reserve" <?php echo isset($search_book_type) &&  ($search_book_type == 'Reserve')  ? 'selected' : '' ?>>Reserve</option>
								</select>
							</div>
			                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-4 d-inline-flex mt-2">
								<button type="button" class="btn btn-primary filter me-3"><i class='bx bx-filter-alt' ></i>Filter</button>
								<button type="button" class="btn btn-light refresh me-3 ms-2"><i class='bx bx-refresh'></i></button>
								@can('Property-create')
								<a href="{{customeRoute($page.'.create')}}" class="btn btn-primary me-3"><i class="bx bx-plus"></i> Add</a>
								@endcan
								<button type="button" class="btn btn-primary me-3 ms-2" onclick="exportMenuData('Excel')"><i class="bx bx-download"></i>Export</button>
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
		                </div>
		            </div>         
					<div class="card-body">
						<div class="table-responsive">
							<table id="datatable" class="table mb-0 table-striped table-bordered dt-responsive nowrap exampledata" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead class="table-light">
									<tr>
										<th>Image</th>
										<th>Reference name/code</th>
										<th>Accommodation</th>
										<!-- <th>Featured</th> -->
										<!-- <th>Free Cancellation</th> -->
										<th>Location</th>
										<th>Price(NGN)</th>
										<th>Occupants</th>
										<th>Beds</th>
										<th>Bedrooms</th>
										<th>Bathrooms</th>
										<th>Owner</th>
										<th>Status</th>
										<th>Book Type</th>
										<th>Position</th>
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
	<!--end wrapper-->

	<div class="modal fade" id="imagesModal">
        <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Property Images</h4>
					<button type="button" class="close property_image_close_btn" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
					<div class="col-md-9">
					<form method="Put" id="add_images">
					@csrf
					<input type='hidden' id="property_id" class="form-control" name="property_id">
						<div class="col-md-12">
							<label class="control-label mb-1" for="name">Add More Other Images</label>	
							<div class="form-group input-group">
								<input type='file' id="addMoremultipalImage" class="form-control upload-Image" name="addMoremultipalImage[]" accept="image/*" onchange="loadImageFile();">
								<!-- <div class="previewing"></div> -->
								<button type="submit" class="btn btn-primary save"><span class="spinner-grow spinner-grow-sm formloader" style="display: none;" role="status" aria-hidden="true"></span> Save</button>
							</div>
						</div>
					</form>
</div>
<div class="col-md-3">
<label class="control-label mb-1" for="name">Add More Other Images</label>	
<button type="button" class="btn btn-primary removeImage">
										<span class="spinner-grow spinner-grow-sm " style="display: none;" role="status" aria-hidden="true"></span> Delete Image
									</button>
</div>
<div>
					<br>
					<div class="row">
								<div class="col-md-12" style="color: #000;">
									<span>Select to delete all image</span><input type="checkbox" class="deleteAll" style="width: 20px;height: 20px;margin: 10px;"/>								
								</div>
								
							</div>
					<br>
					<div class="images_content_response"></div>  
				</div>
			</div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
</div>
<style>
	.posiation_lable{
	    color: #fff;
    position: absolute;
    z-index: 1;
    background-color: rgb(58 58 58);
    border-color: rgb(58 58 58);
    left: 4px;
    top: 4px;
    border-radius: 50%;
    height: 30px;
    width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
}

.deleteImage{
	color: #fff;
    position: absolute;
    z-index: 1;
    background-color: rgb(58 58 58);
    border-color: rgb(58 58 58);
    right: 4px;
    top: 4px;
    border-radius: 50%;
    height: 20px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
    padding: 5px;
    margin: 8px;
    border: 1px solid #000;
}
</style>
	<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
	var fileReader = new FileReader();
	var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

	fileReader.onload = function (event) {
	  var image = new Image();
	  
	  image.onload=function(){
	      document.getElementById("addMoremultipalImage").src=image.src;
	      var canvas=document.createElement("canvas");
	      var context=canvas.getContext("2d");
	      canvas.width=image.width/4;
	      canvas.height=image.height/4;
	      context.drawImage(image,
	          0,
	          0,
	          image.width,
	          image.height,
	          0,
	          0,
	          canvas.width,
	          canvas.height
	      );
	      
	      /*document.getElementById("upload-Preview").src = canvas.toDataURL();*/
	  }
	  image.src=event.target.result;
	};

	var loadImageFile = function () {
	  var uploadImage = document.querySelector('.upload-Image');
	  
	  //check and retuns the length of uploded file.
	  if (uploadImage.files.length === 0) { 
	    return; 
	  }
	  
	  //Is Used for validate a valid file.
	  var uploadFile = document.querySelector('.upload-Image').files[0];
	  if (!filterType.test(uploadFile.type)) {
	    alert("Please select a valid image."); 
	    return;
	  }
	  
	  fileReader.readAsDataURL(uploadFile);
	}
</script>

	<script type="text/javascript">
		$('.export_span').on('click',function(){
			$('.export_ul').toggle();
		})
		$('.property_image_close_btn').on('click',function(){
			$("#imagesModal").modal('toggle');
		})
		$('.owner_select2').select2();
		$(document).on('click','.delete_btn',function(){
			var id = $(this).data('property_id');
			if(id != undefined){
				if(window.confirm('Are you sure want to delete this accommodation?')) {
					var path = $(this).data('path');               
					$('.loader').show();
					$.ajax({
						// url:path,
						url: '{{url('admin/property/delete')}}'+'/'+id,
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
                        'host':$('#host :selected').val(),
                        'type':$('#type :selected').val(),
                        'search_city':$('#city :selected').val(),
                        'search_area':$('.search_area').val(),
                        'max_guest_capacity':$('.max_guest_capacity').val(),
                        'search_bedroom':$('.search_bedroom :selected').val(),
                        // 'search_children':$('.search_children').val(),
                        'search_type_list':$('.search_type_list').val(),
                        'search_category':$('.search_category').val(),
                        'price':$('.price').val(),
                        'search_book_type':$('.search_book_type').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                {mData: 'image'},
                {mData: 'code'},
                {mData: 'title'},
                // {mData: 'featured'},
                // {mData: 'free_cancellation'},
                {mData: 'address'},
                {mData: 'price'},
                {mData: 'occupants'},
                {mData: 'beds'},
                {mData: 'bedrooms'},
                {mData: 'bathrooms'},
                {mData: 'host'},
                {mData: 'status'},
                {mData: 'book_type'},
				{mData: 'Position'},
                {mData: 'created_at'},
				
		
                {mData: 'actions'}
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                { "orderable": false, "targets": [0,6,7,8,12,14] }
            ],
            "order": [[13, "desc"]],
            
            language: {
                searchPlaceholder: "Search by accommodation"
            }, 
        });

        $('.refresh').click(function (e){
            $('.start_date').val("");
            $('.end_date').val("");
            $('.status').val('');
            $('#host').val('');
            $('#type').val('');
            $('#city').val('');
            $('.search_area').val('');
            $('.max_guest_capacity').val('');
            $('.search_bedroom').val('');
            $('.search_type_list').val('');
            $('.search_category').val('');
            $('.price').val('');
            $('.search_book_type').val('');
            //tables.ajax.reload();
            location.reload(true);
        });
		
        $('.filter').click(function (e) {
            tables.ajax.reload();
        });

		/*Export Excel and Csv Function */
		function exportMenuData(file_type) {
			// alert(file_type);
			$(".overlay").fadeIn(300);
			var link = '?';
			var start_date = $('.start_date').val();
			var end_date = $('.end_date').val();
			var status = $('.status').val();
			var host = $('#host :selected').val();
			var type = $('#type :selected').val();
			var search_city = $('#city :selected').val();
			var search_area = $('.search_area').val();
			var max_guest_capacity = $('.max_guest_capacity').val();
			var search_bedroom = $('.search_bedroom :selected').val();
			var search_type_list = $('.search_type_list').val();
			var search_category = $('.search_category').val();
			var price = $('.price').val();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			// alert(link);
			var url = "{{ url('admin/property/exportProperties') }}";
			// var url = "{{ url('admin/property/exportProperties') }}"+link;
			$.ajax({
				type: 'post',
				dataType:'json',
				data:{'file_type':file_type,'start_date':start_date,'end_date':end_date,'status':status,'host':host,'type':type,'search_city':search_city,'search_area':search_area,'max_guest_capacity':max_guest_capacity,'search_bedroom':search_bedroom,'search_type_list':search_type_list,'search_category':search_category,'price':price},
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

		$('.deleteAll').click(function(){
			if($(this).is(':checked'))
			{
				$('.deleteImage').prop( "checked", true );
			}
			else{
				$('.deleteImage').prop( "checked", false );
			}
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

		/*Add multiple images code start*/
		$(document).ready(function () {
			initialize();

			$("#file").change(function(){
				var fileObj = this.files[0];
				var imageFileType = fileObj.type;
				var imageSize = fileObj.size;

				var file = $('#file')[0].files[0].name;
				$(this).prev('label').text(file);
				
				var match = ["image/jpeg","image/png","image/jpg","image/webp"];
				if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
					$('#previewing').attr('src','images/image.png');
					toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and webp Images Type Allowed!!');
					return false;
				}else{
					//console.log(imageSize);
					if(imageSize < 5000000){
						var reader = new FileReader();
						reader.onload = imageIsLoaded;
						reader.readAsDataURL(this.files[0]);
					}else{
						toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
						return false;
					}
					
				}	
			});

			function imageIsLoaded(e){
				//console.log(e);
				$("#file").css("color","green");
				$('#previewing').attr('src',e.target.result);
			}
		})

		$("#add_images").on('submit',function(e){ 
			e.preventDefault();
			var _this=$(this); 
			var formData = new FormData(this);
			formData.append('_method', 'put');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			image_id = $('#property_id').val();
			$.ajax({
				url:'{{ url('admin/property/add-more-images') }}/'+image_id,
				dataType:'json',
				data:formData,
				type:'POST',
				cache:false,
				contentType: false,
				processData: false,
				// beforeSend: function (){before(_this)},
				// hides the loader after completion of request, whether successfull or failor.
				//complete: function (){complete(_this)},
				success:function(result){
					if(result.status == 1){
						$('.images_content_response').empty();
					
						showImage(image_id);
						
						toastr.success(result.message);
						$('#add_images')[0].reset();
						$('#add_images').parsley().reset();
						$('.previewing').html('');
						$('.images_content_response').empty();
						//$("#imagesModal").modal('hide');
						$.each(result.data.productImage, function(i, img){
							if(img.image_type=='IMAGE')
							{            
								$('.images_content_response').append(
									$('<div class="single-img single-img'+img.id+'"><img width="100" heigth="100" id="'+img.id+'" />').attr('src', img.image),
									"<input type='checkbox' value='"+img.id+"' class='deleteImage' name='deleteImage'></div>"
								)
							}
							else
							{
								$('.images_content_response').append(
									"<div class='single-img single-img"+img.id+"'><video width='100' height='100' id='+img.id+' controls><source src='"+img.image+"' type='video/mp4'></video><input type='checkbox' value='"+img.id+"' class='deleteImage' name='deleteImage'></div>"
								) 
							}
							// $('#imagesModal').hide();
							// $("#imagesModal").modal('hide');
						});
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
			return false;   
		});


		$(document).on('click','.images_btn',function(e){
			e.preventDefault();
			$('.images_content_response').empty();
			u_id = $(this).attr('data-property_id');
			$('#property_id').val(u_id);
			showImage(u_id);
		});

		function showImage(u_id)
		{
			$.ajax({
				url:'{{url('admin/property/imageView')}}/'+u_id,
				dataType: 'json',
				success:function(result)
				{
					$('.images_content_response').html('')

					$.each(result.propertyImage, function(i, img){


						if(img.image_type=='IMAGE')
						{            
							
								var html = '<div class="single-img single-img'+img.id+'">'+
								'<div class="single-inner">'+
								'<a class="example-image-link" href="'+img.image+'" data-lightbox="example-set" data-title="">'+	
								'<img width="100" src="'+img.image+'" heigth="100" id="'+img.id+'" />'+
								'</a>'+
								'<div><select name="changeposiation" class="form-control changeposiation" data-id="'+img.id+'" data-propertyid="'+u_id+'"> <option value=""> Move Position</option>';
								for(var j=1; j<=result.propertyImage.length; j++)
								{
									if(result.propertyImage[j-1].id != img.id)
									{

									
									html = html+'<option value="'+result.propertyImage[j-1].id+'"';
									/*if(i+1 ==j)
									{
										html = html+'selected';
									}*/
									html = html+'>'+j+' Position</option>';
									}
								}
								html = html+'</select></div>'+
								'<span class="posiation_lable">'+(	i+1)+'</span>'+
								'<input type="checkbox" value='+img.id+' class="deleteImage" name="deleteImage">'+
								'</div>'+
								'</div>';

								$('.images_content_response').append(html);
							
						}else{
							
							var html = "<video width='100' height='100' id='+img.id+' controls>"+
								"<source src='"+img.image+"' type='video/mp4'>"+
								"</video>";
								for(var j=1; j<=result.propertyImage.length;j++)
								{
									html = html+'<option value="'+j+'"';
									if(i+1 ==j)
									{
										html = html+'selected';
									}
									html = html+'>'+j+' Position</option>';
								}
								var html = html+'<input type="checkbox" value='+img.id+' class="deleteImage" name="deleteImage">';
							
							$('.images_content_response').append(html);
						}
					});
					//$('#images_content_response').html(result);
				} 
			});
			$('#imagesModal').modal('show');
		}

		$(document).on('change','.changeposiation',function(){
			var id =  $(this).data('id');
			var propertyid =  $(this).data('propertyid');
			var posiation = $(this).val();

			$.ajax({
				url:"{{ url('admin/property/change_posiation') }}",
				method: 'post',
				dataType:'json',
				data: {_token: "{{ csrf_token() }}",'id':id,'propertyid':propertyid,'posiation':posiation},
				success: function(res){
					showImage(propertyid);
				}
			});
		});

		$(document).on('click','.flexSwitchCheckCheckedDanger',function(){
			var id = $(this).attr('id');
			if ($(this).prop('checked')==true){ 
				value = 'Reserve';
			}else{
				value = 'Book_now';
			}
			$.ajax({
				url:"{{ url('admin/property/change_book_type') }}",
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

		$(document)

		$(document).on('click','.removeImage',function(e){

			e.preventDefault();
			var response = confirm('Are you sure want to delete this property image?');
			if(response){

				$('.deleteImage').each(function(data,i){
				if($(this).is(':checked'))
				{					
					id = $(this).val();
					$("#"+id).remove();
					$(this).remove();
					$.ajax({
						type: 'post',
						data: {_method: 'delete', _token: "{{ csrf_token() }}"},
						dataType:'json',
						url: "{!! url('admin/property/propertyImagesDelete' )!!}" + "/" + id,
						success:function(res){
							if(res.status === 1){
								$('.single-img'+id).remove();
								toastr.success(res.message);
							}else{
								toastr.error(res.message);
							}
							// $('#imagesModal').modal('hide');
						},   
						error:function(jqXHR,textStatus,textStatus){
							console.log(jqXHR);
							toastr.error(jqXHR.statusText)
						}
					});			
				}
			});
			}
			return false;
			
		});

		$("#multipalImage").change(function(){
			if (event.target.files && event.target.files[0]) {
				var filesAmount = event.target.files.length;	
				for (let i = 0; i < filesAmount; i++) {
					let files = event.target.files[i];
					var reader = new FileReader();
					reader.onload =  (event) => {
						$('.previewing').append("<img width='100' height='100' src='"+event.target.result+"' />")
					}
					reader.readAsDataURL(event.target.files[i]);
				}
			}
		});

			
		$("#addMoremultipalImage").change(function(){
			if (event.target.files && event.target.files[0]) {
				var filesAmount = event.target.files.length;	
				for (let i = 0; i < filesAmount; i++) {
					let files = event.target.files[i];
					var reader = new FileReader();
					reader.onload =  (event) => {
						$('.previewing').append("<img width='100' height='100' src='"+event.target.result+"' />")
					}
					reader.readAsDataURL(event.target.files[i]);
				}
			}
		});
		/*Add multiple images code end*/

		$(document).on('change','.changePosition',function(){
			

			var options = $(this).val();
			var id = $(this).data('id');

			$('.loader').show();
					$.ajax({
						// url:path,
						url: '{{url('admin/property/position')}}'+'/'+id+'/'+options,
						method: 'get',
						// data: {'id':id,'value':value},
						success: function(result){
							if(result.status == true){
								toastr.success(result.message);
							}else{
								toastr.error(result.message);
							}
							tables.ajax.reload();
							/*setTimeout(function () {
								location.reload(true);
							}, 3000);*/
						}
					});


		});
	</script>
@endsection