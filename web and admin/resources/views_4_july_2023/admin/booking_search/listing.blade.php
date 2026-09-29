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
							<div class="col-md-3 mt-2">
								<select class="form-control status" name="status">
		                            <option value="">Select Status</option> 
		                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' :'' ?>>Active</option> 
		                            <option value="0">Inactive</option> 
		                        </select>
		                    </div>
		                    <div class="col-md-3 mt-2"><input type="text" class="form-control start_date" placeholder="Check-in date"></div>
		                    <div class="col-md-3 mt-2"><input type="text" class="form-control end_date"  placeholder="Check-out date"></div>
							<div class="col-md-3 mt-2">
								<select name="city" id="city" class="form-control search_city" data-placeholder="Select City" data-dropdown-css-class="select2-primary">
									<option value="">Select City</option>
									@if(count($all_cities) > 0)
									@foreach($all_cities as $city)
										<option value="{{$city->id}}" <?php echo isset($search_city) && $search_city == $city->id ? 'selected' :'' ?>>{{ $city->name }}</option>
									@endforeach
									@endif
								</select>
		                    </div>
							<div class="col-md-3 mt-2">
								<select name="area" id="area" class="form-control search_area" data-placeholder="Select Area" data-dropdown-css-class="select2-primary">
									<option value="">Select Area</option>
									@if(count($all_area) > 0)
									@foreach($all_area as $area)
										<option value="{{$area->id}}" <?php echo isset($search_area) && $search_area == $area->id ? 'selected' :'' ?>>{{ $area->name }}</option>
									@endforeach
									@endif
								</select>
		                    </div>
							<div class="col-md-3 mt-2"><input type="text" class="form-control search_accommodation" placeholder="Search accommodation by text"></div>
							<div class="col-md-3 mt-2"><input type="text" class="form-control search_building" placeholder="Search Building / Urb by text"></div>
							<div class="col-md-3 mt-2">
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
									<option value="21" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 21 ? 'selected' :'' ?>>21</option>
									<option value="22" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 22 ? 'selected' :'' ?>>22</option>
									<option value="23" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 23 ? 'selected' :'' ?>>23</option>
									<option value="24" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 24 ? 'selected' :'' ?>>24</option>
									<option value="25" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 25 ? 'selected' :'' ?>>25</option>
									<option value="26" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 26 ? 'selected' :'' ?>>26</option>
									<option value="27" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 27 ? 'selected' :'' ?>>27</option>
									<option value="28" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 28 ? 'selected' :'' ?>>28</option>
									<option value="29" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 29 ? 'selected' :'' ?>>29</option>
									<option value="30" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 30 ? 'selected' :'' ?>>30</option>
									<option value="31" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 31 ? 'selected' :'' ?>>31</option>
									<option value="32" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 32 ? 'selected' :'' ?>>32</option>
									<option value="33" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 33 ? 'selected' :'' ?>>33</option>
									<option value="34" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 34 ? 'selected' :'' ?>>34</option>
									<option value="35" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 35 ? 'selected' :'' ?>>35</option>
									<option value="36" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 36 ? 'selected' :'' ?>>36</option>
									<option value="37" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 37 ? 'selected' :'' ?>>37</option>
									<option value="38" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 38 ? 'selected' :'' ?>>38</option>
									<option value="39" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 39 ? 'selected' :'' ?>>39</option>
									<option value="40" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 40 ? 'selected' :'' ?>>40</option>
									<option value="41" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 41 ? 'selected' :'' ?>>41</option>
									<option value="42" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 42 ? 'selected' :'' ?>>42</option>
									<option value="43" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 43 ? 'selected' :'' ?>>43</option>
									<option value="44" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 44 ? 'selected' :'' ?>>44</option>
									<option value="45" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 45 ? 'selected' :'' ?>>45</option>
									<option value="46" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 46 ? 'selected' :'' ?>>46</option>
									<option value="47" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 47 ? 'selected' :'' ?>>47</option>
									<option value="48" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 48 ? 'selected' :'' ?>>48</option>
									<option value="49" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 49 ? 'selected' :'' ?>>49</option>
									<option value="50" <?php echo isset($max_guest_capacity) && $max_guest_capacity == 50 ? 'selected' :'' ?>>50</option>
								</select>
		                    </div>
							<div class="col-md-3 mt-2">
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
							<div class="col-md-3 mt-2">
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
							<div class="col-md-3 mt-2">
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
		                    <div class="col-md-3 d-inline-flex mt-2">
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
										<!-- <th>Image</th> -->
										<th>Accommodation</th>
										<th>Location</th>
										<th>Owner</th>
										<th>Status</th>
										<th>Price(NGN)</th>
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
		var tables = $('#datatable').DataTable({
            "bProcessing": true,
            "serverSide": true,
            "pageLength": 10,
            retrieve: true,
            "ajax": {
                url: "{{ customeRoute($page.'.index') }}",
                data: function (d) {
                    return $.extend({}, d, {
                        'status':$('.status').val(),
                        'start_date':$('.start_date').val(),
                        'end_date':$('.end_date').val(),
                        'search_city':$('.search_city').val(),
                        'search_area':$('.search_area').val(),
                        'search_accommodation':$('.search_accommodation').val(),
                        'search_building':$('.search_building').val(),
                        'max_guest_capacity':$('.max_guest_capacity').val(),
                        'search_bedroom':$('.search_bedroom').val(),
                        // 'search_children':$('.search_children').val(),
                        'search_type_list':$('.search_type_list').val(),
                        'search_category':$('.search_category').val(),
                    });
                },
            }, 
            
            "aoColumns": [
                //{mData: 'id'},
                // {mData: 'image'},
                {mData: 'title'},
                {mData: 'address'},
                {mData: 'host'},
                {mData: 'status'},
                {mData: 'price'},
                {mData: 'created_at'},
                {mData: 'actions'}
            ],
             "aoColumnDefs": [
                {"bSortable": false, "aTargets": ['action']},
                { "orderable": false, "targets": [ 6] }
            ],
            "order": [[5, "desc"]],
            
            language: {
                searchPlaceholder: "Search by accommodation"
            }, 
        });
        $('.refresh').click(function (e){
            $('.status').val("");
            $('.start_date').val("");
            $('.end_date').val("");
            $('.search_city').val('');
            $('.search_area').val('');
            $('.search_accommodation').val('');
            $('.search_building').val('');
            $('.max_guest_capacity').val('');
            $('.search_bedroom').val('');
            $('.search_children').val('');
            $('.search_type_list').val('');
            $('.search_category').val('');
            tables.ajax.reload();
        });
        $('.filter').click(function (e) {
            tables.ajax.reload();
        });

        $(document).ready(function(){
            $(".start_date").datepicker({
                //  minDate: "-1Y",
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