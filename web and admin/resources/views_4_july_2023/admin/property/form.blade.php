@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/magicsuggest/magicsuggest.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
@endsection
<?php 
	use App\Models\User;

	$user_id = Auth::id();
	$user_type = User::where('id',$user_id)->pluck('user_type')->first();
?>
<div class="row">
   	<div class="col-lg-12">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
		<?php //dd($data->getPropertyBedroom); ?>
       	<div class="form-body">
       		<div class="card margin-cls">
			   	<h4 class="dataLabel">GENERAL DATA</h4>
  				<div class="card-body">
		           	<div class="row">
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Name or reference*</label>
							<?php  $errorClass =  !empty($errors->has('title')) ? 'is-invalid':''; ?>   
			                {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Enter Name or reference', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('title')) ?'<div class="invalid-feedback"><span>'.$errors->first('title').'</span></div>' :'' !!}
						</div>
						@if($user_type == 1 || $user_type == 2)
						<div class="col-md-3 mb-3 main_building_div">
							<label for="inputName" class="form-label">Building/Urbanization*</label>
							<?php  $errorClass =  !empty($errors->has('building')) ? 'is-invalid':''; ?>   
							<select name="building" id="building" data-parsley-required="true" class="form-control {{$errorClass}}" placeholder="Select Building/Urbanization" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Building/Urbanization--</option>
								@if(count($buildings) > 0)
								@foreach($buildings as $building)
									<option value="{{$building->id}}" <?php echo isset($data->building) &&  ($data->building == $building->id)  ? 'selected' : '' ?>>{{ $building->name }}</option>
								@endforeach
								@endif
                            </select>
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newBuildingModal(this)" > Add New</a>
						</div>
						@endif
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Type*</label>
							<?php  $errorClass =  !empty($errors->has('type')) ? 'is-invalid':''; ?>   
							<select name="type" id="type" data-parsley-required="true" class="form-control type_list {{$errorClass}}" placeholder="Select Type" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Type--</option>
                                <option value="Aparthotel" <?php echo isset($data->type) &&  ($data->type == 'Aparthotel')  ? 'selected' : '' ?>>Aparthotel</option>
                                <option value="Apartment" <?php echo isset($data->type) &&  ($data->type == 'Apartment')  ? 'selected' : '' ?>>Apartment</option>
                                <option value="Boat" <?php echo isset($data->type) &&  ($data->type == 'Boat')  ? 'selected' : '' ?>>Boat</option>
                                <option value="Bungalow" <?php echo isset($data->type) &&  ($data->type == 'Bungalow')  ? 'selected' : '' ?>>Bungalow</option>
                                <option value="Chalet" <?php echo isset($data->type) &&  ($data->type == 'Chalet')  ? 'selected' : '' ?>>Chalet</option>
                                <option value="Cottage" <?php echo isset($data->type) &&  ($data->type == 'Cottage')  ? 'selected' : '' ?>>Cottage</option>
                                <option value="Country_house" <?php echo isset($data->type) &&  ($data->type == 'Country_house')  ? 'selected' : '' ?>>Country house</option>
                                <option value="Farm_stay" <?php echo isset($data->type) &&  ($data->type == 'Farm_stay')  ? 'selected' : '' ?>>Farm stay</option>
                                <option value="Garage" <?php echo isset($data->type) &&  ($data->type == 'Garage')  ? 'selected' : '' ?>>Garage</option>
                                <option value="House" <?php echo isset($data->type) &&  ($data->type == 'House')  ? 'selected' : '' ?>>House</option>
                                <option value="Mobile_home" <?php echo isset($data->type) &&  ($data->type == 'Mobile_home')  ? 'selected' : '' ?>>Mobile home</option>
                                <option value="Rent_by_room" <?php echo isset($data->type) &&  ($data->type == 'Rent_by_room')  ? 'selected' : '' ?>>Rent by room</option>
                                <option value="Residence" <?php echo isset($data->type) &&  ($data->type == 'Residence')  ? 'selected' : '' ?>>Residence</option>
                                <option value="Studio" <?php echo isset($data->type) &&  ($data->type == 'Studio')  ? 'selected' : '' ?>>Studio</option>
                                <option value="Townhouse" <?php echo isset($data->type) &&  ($data->type == 'Townhouse')  ? 'selected' : '' ?>>Townhouse</option>
                                <option value="Trullo" <?php echo isset($data->type) &&  ($data->type == 'Trullo')  ? 'selected' : '' ?>>Trullo</option>
                                <option value="Villa" <?php echo isset($data->type) &&  ($data->type == 'Villa')  ? 'selected' : '' ?>>Villa</option>
                            </select>
						</div>
						<div class="col-md-3 mb-3 show_categories">
							<label for="inputName" class="form-label">Category*</label>
							<?php  $errorClass =  !empty($errors->has('category')) ? 'is-invalid':''; ?>   
							<select name="category" id="category" data-parsley-required="true" class="form-control {{$errorClass}}" placeholder="Select Category" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Category--</option>
                            </select>
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newCategoryModal(this)" > Add New</a>
						</div>
						@if($user_type == 1 || $user_type == 2)
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Owner*</label>
							<?php  $errorClass =  !empty($errors->has('host')) ? 'is-invalid':''; ?>   
							<select name="host" id="host" data-parsley-required="true" class="form-control {{$errorClass}} owner_select2" data-placeholder="Select Owner" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Owner--</option>
								@if(count($host_users) > 0)
								@foreach($host_users as $host)
									<option value="{{$host->id}}" <?php echo isset($data->host) &&  ($data->host == $host->id)  ? 'selected' : '' ?>>{{ $host->name }}</option>
								@endforeach
								@endif
                            </select>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Status</label>
							<?php  $errorClass =  !empty($errors->has('status')) ? 'is-invalid':''; ?>   
							<select name="status" id="status" data-parsley-required="true" class="form-control {{$errorClass}}" data-placeholder="Select status" data-dropdown-css-class="select2-primary">
                                <option value="1" <?php echo isset($data->status) &&  ($data->status == 1)  ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?php echo isset($data->status) &&  ($data->status == 0)  ? 'selected' : '' ?>>Inactive</option>
                            </select>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Cleaning Status</label>
							<?php  $errorClass =  !empty($errors->has('clean_status')) ? 'is-invalid':''; ?>   
							<select name="clean_status" id="clean_status" data-parsley-required="true" class="form-control {{$errorClass}}" data-placeholder="Select cleaning status" data-dropdown-css-class="select2-primary">
                                <option value="0" <?php echo isset($data->clean_status) &&  ($data->clean_status == 0)  ? 'selected' : '' ?>>Extra Cleaned</option>
                                <option value="1" <?php echo isset($data->clean_status) &&  ($data->clean_status == 1)  ? 'selected' : '' ?>>Cleaned</option>
                            </select>
						</div>
						<div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Is Featured*</label>
                            <?php  $errorClass =  !empty($errors->has('featured')) ? 'is-invalid':''; ?>   
                            <select name="featured" id="featured" data-parsley-required="true" class="form-control featured {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Is Featured--</option>
                                <option value="Yes" <?php echo isset($data->featured) &&  ($data->featured == 'Yes')  ? 'selected' : '' ?>>Yes</option>
                                <option value="No" <?php echo isset($data->featured) &&  ($data->featured == 'No')  ? 'selected' : '' ?>>No</option>
                            </select>
                            {!! !empty($errors->has('featured')) ?'<div class="invalid-feedback"><span>'.$errors->first('featured').'</span></div>' :'' !!}
                        </div>
						<div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Is Luxury*</label>
                            <?php  $errorClass =  !empty($errors->has('luxury')) ? 'is-invalid':''; ?>   
                            <select name="luxury" id="luxury" data-parsley-required="true" class="form-control luxury {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Is Luxury--</option>
                                <option value="Yes" <?php echo isset($data->luxury) &&  ($data->luxury == 'Yes')  ? 'selected' : '' ?>>Yes</option>
                                <option value="No" <?php echo isset($data->luxury) &&  ($data->luxury == 'No')  ? 'selected' : '' ?>>No</option>
                            </select>
                            {!! !empty($errors->has('luxury')) ?'<div class="invalid-feedback"><span>'.$errors->first('luxury').'</span></div>' :'' !!}
                        </div>

						<div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Is Rare*</label>
                            <?php  $errorClass =  !empty($errors->has('rare')) ? 'is-invalid':''; ?>   
                            <select name="rare" id="rare" data-parsley-required="true" class="form-control rare {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Is Rare--</option>
                                <option value="Yes" <?php echo isset($data->rare) &&  ($data->rare == 'Yes')  ? 'selected' : '' ?>>Yes</option>
                                <option value="No" <?php echo isset($data->rare) &&  ($data->rare == 'No')  ? 'selected' : '' ?>>No</option>
                            </select>
                            {!! !empty($errors->has('rare')) ?'<div class="invalid-feedback"><span>'.$errors->first('rare').'</span></div>' :'' !!}
                        </div>



						@endif
                        <!-- <div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Free Cancellation*</label>
                            <?php  $errorClass =  !empty($errors->has('free_cancellation')) ? 'is-invalid':''; ?>   
                            <select name="free_cancellation" id="free_cancellation" data-parsley-required="true" class="form-control free_cancellation {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Free Cancellation--</option>
                                <option value="Yes" <?php echo isset($data->free_cancellation) &&  ($data->free_cancellation == 'Yes')  ? 'selected' : '' ?>>Yes</option>
                                <option value="No" <?php echo isset($data->free_cancellation) &&  ($data->free_cancellation == 'No')  ? 'selected' : '' ?>>No</option>
                            </select>
                            {!! !empty($errors->has('free_cancellation')) ?'<div class="invalid-feedback"><span>'.$errors->first('free_cancellation').'</span></div>' :'' !!}
                        </div> -->
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Max. guest capacity*</label>
							<?php  $errorClass =  !empty($errors->has('max_guest')) ? 'is-invalid':''; ?>   
							<input type="number" min="1"  name="max_guest" id="max_guest" class="form-control max_guest {{$errorClass}}" data-parsley-required="true" data-parsley-type="digits" placeholder="Max Guest" value="<?php echo isset($data->max_guest) ? $data->max_guest : 0 ?>">
							
							
                            <!--select name="max_guest" id="max_guest" data-parsley-required="true" class="form-control max_guest {{$errorClass}}" data-placeholder="Max Guest" data-dropdown-css-class="select2-primary">
								<option value="">--Select Max guest--</option>
								<option value="1" <?php echo isset($data->max_guest) &&  ($data->max_guest == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data->max_guest) &&  ($data->max_guest == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data->max_guest) &&  ($data->max_guest == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data->max_guest) &&  ($data->max_guest == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data->max_guest) &&  ($data->max_guest == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data->max_guest) &&  ($data->max_guest == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data->max_guest) &&  ($data->max_guest == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data->max_guest) &&  ($data->max_guest == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data->max_guest) &&  ($data->max_guest == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data->max_guest) &&  ($data->max_guest == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data->max_guest) &&  ($data->max_guest == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data->max_guest) &&  ($data->max_guest == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data->max_guest) &&  ($data->max_guest == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data->max_guest) &&  ($data->max_guest == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data->max_guest) &&  ($data->max_guest == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data->max_guest) &&  ($data->max_guest == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data->max_guest) &&  ($data->max_guest == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data->max_guest) &&  ($data->max_guest == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data->max_guest) &&  ($data->max_guest == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data->max_guest) &&  ($data->max_guest == 20)  ? 'selected' : '' ?>>20</option>
								<option value="more" <?php echo isset($data->max_guest) &&  ($data->max_guest == 20)  ? 'selected' : '' ?>>20</option>
								<!-- <option value="21" <?php echo isset($data->max_guest) &&  ($data->max_guest == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data->max_guest) &&  ($data->max_guest == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data->max_guest) &&  ($data->max_guest == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data->max_guest) &&  ($data->max_guest == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data->max_guest) &&  ($data->max_guest == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data->max_guest) &&  ($data->max_guest == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data->max_guest) &&  ($data->max_guest == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data->max_guest) &&  ($data->max_guest == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data->max_guest) &&  ($data->max_guest == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data->max_guest) &&  ($data->max_guest == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data->max_guest) &&  ($data->max_guest == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data->max_guest) &&  ($data->max_guest == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data->max_guest) &&  ($data->max_guest == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data->max_guest) &&  ($data->max_guest == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data->max_guest) &&  ($data->max_guest == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data->max_guest) &&  ($data->max_guest == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data->max_guest) &&  ($data->max_guest == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data->max_guest) &&  ($data->max_guest == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data->max_guest) &&  ($data->max_guest == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data->max_guest) &&  ($data->max_guest == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data->max_guest) &&  ($data->max_guest == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data->max_guest) &&  ($data->max_guest == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data->max_guest) &&  ($data->max_guest == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data->max_guest) &&  ($data->max_guest == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data->max_guest) &&  ($data->max_guest == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data->max_guest) &&  ($data->max_guest == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data->max_guest) &&  ($data->max_guest == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data->max_guest) &&  ($data->max_guest == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data->max_guest) &&  ($data->max_guest == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data->max_guest) &&  ($data->max_guest == 50)  ? 'selected' : '' ?>>50</option> ->
                            </select-->
			                {!! !empty($errors->has('max_guest')) ?'<div class="invalid-feedback"><span>'.$errors->first('max_guest').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Pets Allow</label>
                            <select name="pets_allow" id="pets_allow" class="form-control pets_allow" data-placeholder="Pets Allow" data-dropdown-css-class="select2-primary">
								<option value="Yes"  <?php echo isset($data) && $data->pets_allow == 'Yes' ? 'selected' : ''; ?>>Yes</option>
								<option value="No"  <?php echo isset($data) && $data->pets_allow == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputMobile" class="form-label">Main Image</label>
	                        <div class="input-group">
	                        	@if(isset($data) && $data->image)
                                    <div id="image_preview"><img id="previewing" src="{{ $data->image }}"></div>
                                @else
	                            	<div id="image_preview"><img id="previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
	                            @endif
	                            <div class="form-control" onclick="document.getElementById('file').click()">
	                                <label for="files">Select Image</label>
	                                <input type="file" id="file" name="image" style="visibility:hidden;" class="form-control">
	                            </div>
	                         </div>
	                    </div>
						<!-- <div class="col-md-3 mb-3">
							<label for="inputContract" class="form-label">Contract</label>
	                        <div class="input-group">
	                        	@if(isset($data) && $data->contract)
                                    <div id="image_preview1"><a href="{{ $data->contract }}" target="_blank"><i class="bx bx-down-arrow-circle"></i></a></div>
	                            @endif
	                            <div class="form-control" onclick="document.getElementById('file_contract').click()">
	                                <label for="files">Upload Contract</label>
	                                <input type="file" id="file_contract" name="contract" style="visibility:hidden;" class="form-control" accept=".doc, .docx,.ppt,.pptx,.pdf" >
	                            </div>
	                         </div>
	                    </div> -->
						<!-- <div class="col-md-3 mb-3">
							<label for="inputAmenities" class="form-label"></label>
							<input type="hidden">
						</div> -->
						<div class="col-md-3 mb-3">
							<label for="inputPostalCode" class="form-label">Price (NGN)*</label>
							<?php  $errorClass =  !empty($errors->has('price')) ? 'is-invalid':''; ?>   
							<input name="price" class="form-control" type="text" placeholder="Enter Property Price(NGN)" value="<?php echo isset($data) && !empty($data->price) ? $data->getRawOriginal('price') : "";?>" required="required" min="1" onkeypress="return onlyNumberKey(event)">
			                {!! !empty($errors->has('price')) ?'<div class="invalid-feedback"><span>'.$errors->first('price').'</span></div>' :'' !!}
						</div>
						@if($user_type == 1 || $user_type == 2)
						<div class="col-md-3 mb-3">
							<label for="inputTax" class="form-label">Tax (%)</label>
							<?php  $errorClass = !empty($errors->has('tax')) ? 'is-invalid':''; ?>   
							<input name="tax" class="form-control" type="text" placeholder="Enter Tax (%)" value="<?php echo isset($data) && !empty($data->tax) ? $data->tax : "";?>" min="0" onkeypress="return onlyNumberKey(event)">
			                {!! !empty($errors->has('tax')) ?'<div class="invalid-feedback"><span>'.$errors->first('tax').'</span></div>' :'' !!}
						</div>
						@endif
						<div class="col-md-3 mb-3">
							<label for="inputSecurityDeposit" class="form-label">Caution Fee (NGN)</label>
							<?php  $errorClass = !empty($errors->has('security_deposit_amount')) ? 'is-invalid':''; ?>   
							<input name="security_deposit_amount" class="form-control" type="text" placeholder="Enter Caution Fee Amount (NGN)" value="<?php echo isset($data) && !empty($data->security_deposit_amount) ? $data->security_deposit_amount : "";?>" min="0" onkeypress="return onlyNumberKey(event)">
			                {!! !empty($errors->has('security_deposit_amount')) ?'<div class="invalid-feedback"><span>'.$errors->first('security_deposit_amount').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputMinimumNoOfNights" class="form-label">Minimum number of nights*</label>
							<?php  $errorClass = !empty($errors->has('minimum_no_of_nights')) ? 'is-invalid':''; ?>   
							<input name="minimum_no_of_nights" class="form-control" type="number" placeholder="Enter Minimum number of nights" value="<?php echo isset($data) && !empty($data->minimum_no_of_nights) ? $data->minimum_no_of_nights : "";?>" min="1" required="required">
			                {!! !empty($errors->has('minimum_no_of_nights')) ?'<div class="invalid-feedback"><span>'.$errors->first('minimum_no_of_nights').'</span></div>' :'' !!}
						</div>
					
						@if($user_type == 1 || $user_type == 2)
                        <div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Position</label>
                            <?php  $errorClass =  !empty($errors->has('position')) ? 'is-invalid':''; ?>   
                            <select name="position" id="position" class="form-control position {{$errorClass}}" data-placeholder="Select Position" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Position*--</option>
								@for($inc = 1; $inc <= $property_count; $inc++)
									<option value="{{$inc}}" <?php echo isset($data->position) && $data->position == $inc ? 'selected' : ''; ?>>{{$inc}}</option>
								@endfor
                            </select>
                            {!! !empty($errors->has('position')) ?'<div class="invalid-feedback"><span>'.$errors->first('position').'</span></div>' :'' !!}
                        </div>
						@endif
                        <div class="col-md-3 mb-3">
                            <label for="inputMobile" class="form-label">Booking Type*</label>
                            <?php  $errorClass =  !empty($errors->has('book_type')) ? 'is-invalid':''; ?>   
                            <select name="book_type" id="book_type" data-parsley-required="true" class="form-control book_type {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Booking Type--</option>
                                <option value="Book_now" <?php echo isset($data->book_type) &&  ($data->book_type == 'Book_now')  ? 'selected' : '' ?>>Book Now</option>
                                <option value="Reserve" <?php echo isset($data->book_type) &&  ($data->book_type == 'Reserve')  ? 'selected' : '' ?>>Reserve</option>
                            </select>
                            {!! !empty($errors->has('book_type')) ?'<div class="invalid-feedback"><span>'.$errors->first('book_type').'</span></div>' :'' !!}
                        </div>
						<div class="col-md-3 mb-3">
							<label for="inputMinimumNoOfNights" class="form-label">Video URL</label>
							<input name="video_url" class="form-control" type="url" placeholder="Enter Video URL" value="<?php echo isset($data) && !empty($data->video_url) ? $data->video_url : "";?>">
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputMinimumNoOfNights" class="form-label">Check-In From Time*</label>
							<?php
							$check_in_from_start = "00:00"; //you can write here 00:00:00 but not need to it
							$check_in_from_end = "23:30";

							$check_in_from_tStart = strtotime($check_in_from_start);
							$check_in_from_tEnd = strtotime($check_in_from_end);
							$check_in_from_tNow = $check_in_from_tStart;
							echo '<select name="check_in_from_time" class="form-control check_in_from_time" id="check_in_from_time" data-parsley-required="true"><option value="">Select Check-In Time</option>';
							while($check_in_from_tNow <= $check_in_from_tEnd){
								$from_time = "";
								if(isset($data->check_in_from_time) && (date("H:i",$check_in_from_tNow) == $data->check_in_from_time)){ $from_time = "selected"; }
								echo '<option value="'.date("H:i",$check_in_from_tNow).'" '.$from_time.' >'.date("H:i",$check_in_from_tNow).'</option>';
								$check_in_from_tNow = strtotime('+30 minutes',$check_in_from_tNow);
							}
							echo '</select>';
                        	?>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputMinimumNoOfNights" class="form-label">Check-In To Time*</label>
							<?php
							// dd($data->check_in_to_time);
							$check_in_start = "00:00"; //you can write here 00:00:00 but not need to it
							$check_in_end = "23:30";

							$check_in_tStart = strtotime($check_in_start);
							$check_in_tEnd = strtotime($check_in_end);
							$check_in_tNow = $check_in_tStart;
							echo '<select name="check_in_to_time" class="form-control check_in_to_time" id="check_in_to_time" data-parsley-required="true"><option value="">Select Check-In Time</option>';
							while($check_in_tNow <= $check_in_tEnd){
								$time = "";
								if(isset($data->check_in_to_time) && (date("H:i",$check_in_tNow) == $data->check_in_to_time)){ $time = "selected"; }
								echo '<option value="'.date("H:i",$check_in_tNow).'" '.$time.' >'.date("H:i",$check_in_tNow).'</option>';
								$check_in_tNow = strtotime('+30 minutes',$check_in_tNow);
							}
							echo '</select>';
                        	?>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputMinimumNoOfNights" class="form-label">Check-Out Time*</label>
							<?php
							$start = "00:00"; //you can write here 00:00:00 but not need to it
							$end = "23:30";

							$tStart = strtotime($start);
							$tEnd = strtotime($end);
							$tNow = $tStart;
							echo '<select name="check_out_time" class="form-control check_out_time" id="check_out_time" data-parsley-required="true"><option value="">Select Check-Out Time</option>';
							while($tNow <= $tEnd){
								$time1 = "";
								if(isset($data->check_out_time) && (date("H:i",$tNow) == $data->check_out_time)){ $time1 = "selected"; }
								echo '<option value="'.date("H:i",$tNow).'" '.$time1.'>'.date("H:i",$tNow).'</option>';
								$tNow = strtotime('+30 minutes',$tNow);
							}
							echo '</select>';
                        	?>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">CCTV</label>
                            <select name="cctv" id="cctv" class="form-control cctv" data-placeholder="Select CCTV" data-dropdown-css-class="select2-primary">
								<option value="" >Select CCTV</option>
								<option value="Yes"  <?php echo isset($data) && $data->cctv == 'Yes' ? 'selected' : ''; ?>>Yes</option>
								<option value="No"  <?php echo isset($data) && $data->cctv == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
						</div>
						<div class="col-md-3 mb-3 cctv_locations_div">
							<label for="inputName" class="form-label">Indiate location of cameras</label>
			                {!! Form::text('cctv_locations', null, array('id'=>'cctv_locations','placeholder' => 'Indiate location of cameras', 'class' => 'form-control' )) !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">What is your response time when a guest has a complaint with your property?</label>
			                {!! Form::text('response_time', null, array('id'=>'response_time','placeholder' => 'Response time', 'class' => 'form-control' )) !!}
						</div>
						<div class="col-md-6 mb-3">
						<label for="inputProductDescription" class="form-label">Refundable</label>
							<select name="refund" class="form-control">
								<option value="YES" <?php echo isset($data) && $data->refund == 'YES' ? 'selected' : ''; ?>>YES</option>
								<option value="NO" <?php echo isset($data) && $data->refund == 'NO' ? 'selected' : ''; ?>>NO</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Additional notes</label>
							<textarea class="form-control" name="additional_notes" id="additional_notes" rows="3"><?php echo $data->additional_notes ?? '' ?></textarea>
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Booking Condition</label>
							<textarea class="form-control" name="booking_condition" id="booking_condition" rows="3"><?php echo $data->booking_condition ?? '' ?></textarea>
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Cancellation Policy</label>
							<textarea class="form-control" name="cancellation_policy" id="cancellation_policy" rows="3"><?php echo $data->cancellation_policy ?? '' ?></textarea>
						</div>
						<div class="col-md-4 mb-3 main_services_div">
							<label for="inputExtraServices" class="form-label">Select Extra Services</label>
							<?php  $errorClass =  !empty($errors->has('property_extra_services')) ? 'is-invalid':''; ?>   
							<select name="property_extra_services[]" id="property_extra_services" class="form-control property_extra_services select2 {{$errorClass}}" data-placeholder="Select Extra Services" multiple>
								@if(count($extra_services) > 0)
								@foreach($extra_services as $service)
									<option value="{{$service->id}}" <?php if(isset($selected_extra_services)){ if(in_array($service->id,$selected_extra_services)){ echo 'selected'; } } ?>>{{$service->name}}</option>
								@endforeach
								@endif
							</select>
							{!! !empty($errors->has('property_extra_services')) ?'<div class="invalid-feedback"><span>'.$errors->first('property_extra_services').'</span></div>' :'' !!}
						</div>
						<div class="col-md-2 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newExtraServiceModal(this)" > Add New</a>
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Description</label>
							<textarea class="form-control" name="description" id="description" rows="3"><?php echo $data->description ?? '' ?></textarea>
						</div>
						<!-- <div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Tags</label>
							<input type="text" id="inputTag" name="inputTag" class="form-control input_tag_test change_tag" placeholder="Enter tags" value="" data-parsley-required="true">  -->
							<!-- <form action="subscribe.php" method="post">
								<div class=">
									<label>Custom placeholder</label>
									<div id="magicsuggest"></div>
								</div>
							</form> -->
							<!-- <div id="magicsuggest"></div> -->
							<!-- <input type="text" id="inputTag" name="inputTag[]" value="" data-role="tagsinput" class="form-control input_tag_test change_tag" placeholder="Enter tags" value="" data-parsley-required="true"/> -->
							<!-- <input type="text" id="inputTag" name="inputTag[]" class="form-control input_tag_test change_tag" placeholder="Enter tags" value="" data-role="tagsinput"> -->
							<!-- <input type="text" name="inputTag" class="form-control input_tag_test" placeholder="Enter tags"> -->
							<!-- <select name="tags[]" id="tags" class="form-control tags select2 {{$errorClass}}" data-placeholder="Select Tags" multiple>
								@if(count($tags) > 0)
								@foreach($tags as $tag)
									<option value="{{$tag->id}}" <?php if(isset($selected_tags)){ if(in_array($tag->id,$selected_tags)){ echo 'selected'; } } ?>>{{$tag->name}}</option>
								@endforeach
								@endif
							</select> -->
						<!-- </div> -->
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">LOCATION OF ACCOMMODATION</h4>
				<div class="card-body">	
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Country*</label>
							<?php  $errorClass =  !empty($errors->has('country_id')) ? 'is-invalid':''; ?>
			                <select name="country_id" class="form-control country_id map_change {{$errorClass}}" /*onchange="getProvince()"*/ id='country_id' required>
			                    @if(!empty($country))
				                    <option value="">Select Country</option>
				                    @foreach($country as $key => $country1)
				                    	<?php $selected = isset($data) && !empty($data->getPropertyAddress[0]->country_id) ? $data->getPropertyAddress[0]->country_id : "";?>
				                        <option value="{{$country1->id}}" <?php echo $selected == $country1->id  ? 'selected' : '' ?>>{{$country1->name}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
			                @error('country_id')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('country_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('country_id').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3 show_provinceDiv">
							<label for="inputName" class="form-label">Province*</label>
							<?php  $errorClass =  !empty($errors->has('province_id')) ? 'is-invalid':''; ?>
			                <select name="province_id" class="form-control province_id {{$errorClass}}" id='province_id' required>
			                    @if(!empty($province))
				                    <option value="">Select Province</option>
			                    @endif
			                </select>
			                @error('country_id')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('province_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('province_id').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newProvinceModal(this)" > Add New</a>
						</div>

						<div class="col-md-4 mb-3 show_cityDiv">
							<label for="inputName" class="form-label">City*</label>
			                <select name="city_id" class="form-control city_id {{$errorClass}}" id='city_id' required>
			                    <option value="">Select City</option>
			                </select>
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newCityModal(this)" > Add New</a>
						</div>

						<div class="col-md-4 mb-3 show_areaDiv">
							<label for="inputName" class="form-label">Area</label>
							<select name="area" class="form-control area {{$errorClass}}" id='area'>
								<option value="">Select Area</option>
							</select>
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newAreaModal(this)" > Add New</a>
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputPostalCode" class="form-label">Postal Code</label>
							<?php  $errorClass =  !empty($errors->has('postal_code')) ? 'is-invalid':''; ?>   
							<input name="postal_code" class="form-control postal_code" type="text" placeholder="Enter Postal Code" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->postal_code) ? $data->getPropertyAddress[0]->postal_code : "";?>">
			                {!! !empty($errors->has('postal_code')) ?'<div class="invalid-feedback"><span>'.$errors->first('postal_code').'</span></div>' :'' !!}
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Street Type</label>
							<?php  $errorClass =  !empty($errors->has('street_type')) ? 'is-invalid':''; ?>   
                            <select name="street_type" id="street_type" class="form-control street_type {{$errorClass}}" data-placeholder="Select Street Type" data-dropdown-css-class="select2-primary">
								<option value="" >--None--</option>
								<option value="Alley" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Alley')  ? 'selected' : '' ?>>Alley</option>
								<option value="Avenue" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Avenue')  ? 'selected' : '' ?>>Avenue</option>
								<option value="Boulevard" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Boulevard')  ? 'selected' : '' ?>>Boulevard</option>
								<option value="Circle" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Circle')  ? 'selected' : '' ?>>Circle</option>
								<option value="Cul-de-sac" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Cul-de-sac')  ? 'selected' : '' ?>>Cul-de-sac</option>
								<option value="Passage" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Passage')  ? 'selected' : '' ?>>Passage</option>
								<option value="Path" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Path')  ? 'selected' : '' ?>>Path</option>
								<option value="Road" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Road')  ? 'selected' : '' ?>>Road</option>
								<option value="Roundabout" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Roundabout')  ? 'selected' : '' ?>>Roundabout</option>
								<option value="Square" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Square')  ? 'selected' : '' ?>>Square</option>
								<option value="Street" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Street')  ? 'selected' : '' ?>>Street</option>
								<option value="Walk" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Walk')  ? 'selected' : '' ?>>Walk</option>
								<option value="Way" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_type == 'Way')  ? 'selected' : '' ?>>Way</option>
                            </select>
			                {!! !empty($errors->has('street_type')) ?'<div class="invalid-feedback"><span>'.$errors->first('street_type').'</span></div>' :'' !!}
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputPostalCode" class="form-label">Street name</label>
							<?php  $errorClass =  !empty($errors->has('street_name')) ? 'is-invalid':''; ?>   
							<input name="street_name" class="form-control street_name" type="text" placeholder="Enter Street Name" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->street_name) ? $data->getPropertyAddress[0]->street_name : "";?>">
							{!! !empty($errors->has('street_name')) ?'<div class="invalid-feedback"><span>'.$errors->first('street_name').'</span></div>' :'' !!}
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Type street of numbering</label>
							<?php  $errorClass =  !empty($errors->has('street_type')) ? 'is-invalid':''; ?>   
							<select name="street_number" id="street_number" class="form-control street_number {{$errorClass}}" data-placeholder="Select Street Number" data-dropdown-css-class="select2-primary">
								<option value="" >--None--</option>
								<option value="Kilometer" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_number == 'Kilometer')  ? 'selected' : '' ?>>Kilometer</option>
								<option value="Number" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_number == 'Number')  ? 'selected' : '' ?>>Number</option>
								<option value="Other" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_number == 'Other')  ? 'selected' : '' ?>>Other</option>
								<option value="Without_number" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->street_number == 'Without_number')  ? 'selected' : '' ?>>Without number</option>
							</select>
							{!! !empty($errors->has('street_number')) ?'<div class="invalid-feedback"><span>'.$errors->first('street_number').'</span></div>' :'' !!}
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputPostalCode" class="form-label">Building/house no. </label>
							<?php  $errorClass =  !empty($errors->has('house_number')) ? 'is-invalid':''; ?>   
							<input name="house_number" class="form-control" type="text" placeholder="Enter Building/house no." value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->house_number) ? $data->getPropertyAddress[0]->house_number : "";?>">
							{!! !empty($errors->has('house_number')) ?'<div class="invalid-feedback"><span>'.$errors->first('house_number').'</span></div>' :'' !!}
						</div>
						
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Floor</label>
							<?php  $errorClass =  !empty($errors->has('floor')) ? 'is-invalid':''; ?>   
							<select name="floor" id="floor" class="form-control floor {{$errorClass}}" data-placeholder="Floor" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->floor == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('floor')) ?'<div class="invalid-feedback"><span>'.$errors->first('floor').'</span></div>' :'' !!}
						</div>

						<!-- <div class="col-md-2 mb-3">
							<label for="inputPostalCode" class="form-label">Staircase</label>
							<?php  $errorClass =  !empty($errors->has('staircase')) ? 'is-invalid':''; ?>   
							<input name="staircase" class="form-control" type="text" placeholder="Enter Staircase" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->staircase) ? $data->getPropertyAddress[0]->staircase : "";?>">
							{!! !empty($errors->has('staircase')) ?'<div class="invalid-feedback"><span>'.$errors->first('staircase').'</span></div>' :'' !!}
						</div> -->
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="staircase" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->staircase == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Staircase</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="elevator" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getPropertyAddress[0]->elevator == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Elevator</label>
							</div>
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputPostalCode" class="form-label">Apartment door no.</label>
							<?php  $errorClass =  !empty($errors->has('apartment_door_no')) ? 'is-invalid':''; ?>   
							<input name="apartment_door_no" class="form-control" type="text" placeholder="Enter Apartment door no." value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->apartment_door_no) ? $data->getPropertyAddress[0]->apartment_door_no : "";?>">
							{!! !empty($errors->has('apartment_door_no')) ?'<div class="invalid-feedback"><span>'.$errors->first('apartment_door_no').'</span></div>' :'' !!}
						</div>

						<input type="hidden" name="latitude" id="latitude" class="form-control" placeholder="Enter latitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->latitude) ? $data->getPropertyAddress[0]->latitude : "";?>">
					
						<input type="hidden" name="longitude" id="longitude" class="form-control" placeholder="Enter longitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->longitude) ? $data->getPropertyAddress[0]->longitude : "";?>">
					
						<input type="hidden" placeholder="Address" name="address" value="{{isset($data->address) ? $data->address :'' }}" class="form-control {{$errorClass}}" id="address" autocomplete="off" data-parsley-required="true">
						
						<!-- <div class="col-md-2 mb-3">
							<label for="inputPostalCode" class="form-label"> </label>
							<input type="text" placeholder="Address" name="address" value="{{isset($data->address) ? $data->address :'' }}" class="form-control {{$errorClass}}" id="address" autocomplete="off" data-parsley-required="true">
						</div> -->
						<div class="col-md-12 mb-3">
			                  <style>
			                    #map_canvas {
			                      width: 100%;
			                      height: 200px;
			                    }

			                    /* Optional: Makes the sample page fill the window. */
			                    html,
			                    body {
			                      height: 100%;
			                      margin: 0;
			                      padding: 0;
			                    }
			                  </style>
			                  <div class="mb-0">
			                    <div id="map_canvas"></div>
			                  </div>
			            </div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">BEDROOMS</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Number of bedrooms</label>
							<?php  $errorClass =  !empty($errors->has('no_of_bedrooms')) ? 'is-invalid':''; ?>   
                            <select name="no_of_bedrooms" id="no_of_bedrooms" data-parsley-required="true" class="form-control no_of_bedrooms {{$errorClass}}" data-placeholder="No. of Bedrooms" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bedrooms == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('no_of_bedrooms')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_bedrooms').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Communal zones</label>
							<?php  $errorClass =  !empty($errors->has('communal_zones')) ? 'is-invalid':''; ?>   

							<?php if(isset($data) && !empty($data->getPropertyBedroom[0]->communal_zones)){
									$communal_zones = explode(",",$data->getPropertyBedroom[0]->communal_zones);
								}else{
									$communal_zones = [];
								} 
							?>
                            <select name="communal_zones[]" id="communal_zones" class="form-control communal_zones {{$errorClass}}" data-placeholder="Communal Zones" data-dropdown-css-class="select2-primary" multiple>
								<option value="Bunk_bed" <?php if(isset($communal_zones)){ if(in_array('Bunk_bed',$communal_zones)){ echo 'selected'; } } ?>>Bunk bed</option>
								<option value="Double_bed" <?php if(isset($communal_zones)){ if(in_array('Double_bed',$communal_zones)){ echo 'selected'; } } ?>>Double bed</option>
								<option value="Double_sofa_bed" <?php if(isset($communal_zones)){ if(in_array('Double_sofa_bed',$communal_zones)){ echo 'selected'; } } ?>>Double sofa bed</option>
								<option value="Extra_bed" <?php if(isset($communal_zones)){ if(in_array('Extra_bed',$communal_zones)){ echo 'selected'; } } ?>>Extra bed</option>
								<option value="Kingsize_bed" <?php if(isset($communal_zones)){ if(in_array('Kingsize_bed',$communal_zones)){ echo 'selected'; } } ?>>Kingsize bed</option>
								<option value="Qweensize_bed" <?php if(isset($communal_zones)){ if(in_array('Qweensize_bed',$communal_zones)){ echo 'selected'; } } ?>>Queensize bed</option>
								<option value="Single_bed" <?php if(isset($communal_zones)){ if(in_array('Single_bed',$communal_zones)){ echo 'selected'; } } ?>>Single bed</option>
								<option value="Single_sofa_bed" <?php if(isset($communal_zones)){ if(in_array('Single_sofa_bed',$communal_zones)){ echo 'selected'; } } ?>>Single sofa bed</option>
                            </select>
			                {!! !empty($errors->has('communal_zones')) ?'<div class="invalid-feedback"><span>'.$errors->first('communal_zones').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Bunk bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_bunk_bed')) ? 'is-invalid':''; ?>   
							<!-- {!! Form::text('no_of_bunk_bed', null, array('id'=>'no_of_bunk_bed','placeholder' => 'No. of Bunk bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_bunk_bed" id="no_of_bunk_bed" class="form-control no_of_bunk_bed {{$errorClass}}" data-placeholder="no_of_bunk_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_bunk_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_bunk_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_bunk_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Double bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_double_bed')) ? 'is-invalid':''; ?>   
							<!-- {!! Form::text('no_of_double_bed', null, array('id'=>'no_of_double_bed','placeholder' => 'No. of Double bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_double_bed" id="no_of_double_bed" class="form-control no_of_double_bed {{$errorClass}}" data-placeholder="no_of_double_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_double_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_double_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Double sofa bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_double_sofa_bed')) ? 'is-invalid':''; ?>   
							<!-- {!! Form::text('no_of_double_sofa_bed', null, array('id'=>'no_of_double_sofa_bed','placeholder' => 'No. of Double sofa bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_double_sofa_bed" id="no_of_double_sofa_bed" class="form-control no_of_double_sofa_bed {{$errorClass}}" data-placeholder="no_of_double_sofa_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_double_sofa_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_double_sofa_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_double_sofa_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Extra bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_extra_bed')) ? 'is-invalid':''; ?>      
							<!-- {!! Form::text('no_of_extra_bed', null, array('id'=>'no_of_extra_bed','placeholder' => 'No. of Extra bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_extra_bed" id="no_of_extra_bed" class="form-control no_of_extra_bed {{$errorClass}}" data-placeholder="no_of_extra_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_extra_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_extra_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_extra_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Kingsize bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_kingsize_bed')) ? 'is-invalid':''; ?>      
							<!-- {!! Form::text('no_of_kingsize_bed', null, array('id'=>'no_of_kingsize_bed','placeholder' => 'No. of Kingsize bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_kingsize_bed" id="no_of_kingsize_bed" class="form-control no_of_kingsize_bed {{$errorClass}}" data-placeholder="no_of_kingsize_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_kingsize_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_kingsize_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_kingsize_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Queensize bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_qweensize_bed')) ? 'is-invalid':''; ?>      
							<!-- {!! Form::text('no_of_qweensize_bed', null, array('id'=>'no_of_qweensize_bed','placeholder' => 'No. of Qweensize bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_qweensize_bed" id="no_of_qweensize_bed" class="form-control no_of_qweensize_bed {{$errorClass}}" data-placeholder="no_of_qweensize_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_qweensize_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_qweensize_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_qweensize_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Single bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_single_bed')) ? 'is-invalid':''; ?>      
							<!-- {!! Form::text('no_of_single_bed', null, array('id'=>'no_of_single_bed','placeholder' => 'No. of Single bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_single_bed" id="no_of_single_bed" class="form-control no_of_single_bed {{$errorClass}}" data-placeholder="no_of_single_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_single_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_single_bed').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Number of Single sofa bed</label>
							<?php  $errorClass =  !empty($errors->has('no_of_single_sofa_bed')) ? 'is-invalid':''; ?>      
							<!-- {!! Form::text('no_of_single_sofa_bed', null, array('id'=>'no_of_single_sofa_bed','placeholder' => 'No. of Single sofa bed', 'class' => 'form-control '.$errorClass )) !!} -->
							<select name="no_of_single_sofa_bed" id="no_of_single_sofa_bed" class="form-control no_of_single_sofa_bed {{$errorClass}}" data-placeholder="no_of_single_sofa_bed" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyBedroom[0]->no_of_single_sofa_bed == 16)  ? 'selected' : '' ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_single_sofa_bed')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_single_sofa_bed').'</span></div>' :'' !!}
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">BATHROOMS</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Bathrooms with bathtub</label>
							<?php  $errorClass =  !empty($errors->has('bathroom_with_bathtub')) ? 'is-invalid':''; ?>   
                            <select name="bathroom_with_bathtub" id="bathroom_with_bathtub" data-parsley-required="true" class="form-control bathroom_with_bathtub {{$errorClass}}" data-placeholder="Bathrooms with bathtub" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_bathtub == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('bathroom_with_bathtub')) ?'<div class="invalid-feedback"><span>'.$errors->first('bathroom_with_bathtub').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Bathrooms with Shower</label>
							<?php  $errorClass =  !empty($errors->has('bathroom_with_shower')) ? 'is-invalid':''; ?>   
                            <select name="bathroom_with_shower" id="bathroom_with_shower" data-parsley-required="true" class="form-control bathroom_with_shower {{$errorClass}}" data-placeholder="Bathrooms with Shower" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->bathroom_with_shower == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('bathroom_with_shower')) ?'<div class="invalid-feedback"><span>'.$errors->first('bathroom_with_shower').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Toilets</label>
							<?php  $errorClass =  !empty($errors->has('toilets')) ? 'is-invalid':''; ?>   
                            <select name="toilets" id="toilets" data-parsley-required="true" class="form-control toilets {{$errorClass}}" data-placeholder="Toilets" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->toilets == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('toilets')) ?'<div class="invalid-feedback"><span>'.$errors->first('toilets').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<div class="ck-box">
								<input name="sauna" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->sauna == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Sauna</label>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="ck-box">
								<input name="jacuzzi" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->jacuzzi == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Jacuzzi</label>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="ck-box">
								<input name="hair_dryer" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->hair_dryer == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Hair dryer</label>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label"></label>
							<input type="hidden">
						</div>
						<div class="col-md-4 mb-3">
                            <label for="inputTowels" class="form-label">Towels*</label>
                            <?php  $errorClass =  !empty($errors->has('towels')) ? 'is-invalid':''; ?>   
                            <select name="towels" id="towels" data-parsley-required="true" class="form-control towels {{$errorClass}}" data-placeholder="Select towels" data-dropdown-css-class="select2-primary">
                                <option value="">--Select Towels--</option>
								<option value="0" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towels == 0)  ? 'selected' : '' ?>>Not provided</option>
								<option value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towels == 1)  ? 'selected' : '' ?>>Provided</option>
                            </select>
                            {!! !empty($errors->has('towels')) ?'<div class="invalid-feedback"><span>'.$errors->first('towels').'</span></div>' :'' !!}
                        </div>
						<div class="col-md-4 mb-3 towel_change_div">
							<div class="ck-box">
								<input name="towel_change" class="checkbox" type="checkbox" value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change == 1)  ? 'checked' : '' ?>>
								<label for="inputName" class="form-label">Towel change</label>
							</div>
						</div>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Change frequency</label>
							<?php  $errorClass =  !empty($errors->has('towel_change_frequency')) ? 'is-invalid':''; ?>   
                            <select name="towel_change_frequency" id="towel_change_frequency" class="form-control toilets {{$errorClass}}" data-placeholder="Change Frequency" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getProPropertyBathroom[0]->towel_change_frequency == 30)  ? 'selected' : '' ?>>30</option>
                            </select>
			                {!! !empty($errors->has('towel_change_frequency')) ?'<div class="invalid-feedback"><span>'.$errors->first('towel_change_frequency').'</span></div>' :'' !!}
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">Kitchen</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Number of Kitchens</label>
							<?php  $errorClass =  !empty($errors->has('no_of_kitchens')) ? 'is-invalid':''; ?>   
                            <select name="no_of_kitchens" id="no_of_kitchens" data-parsley-required="true" class="form-control no_of_kitchens {{$errorClass}}" data-placeholder="Number of kitchens" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 0)  ? 'selected' : '' ?>>0</option>
								<option value="1" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 1)  ? 'selected' : '' ?>>1</option>
								<option value="2" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 2)  ? 'selected' : '' ?>>2</option>
								<option value="3" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 3)  ? 'selected' : '' ?>>3</option>
								<option value="4" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 4)  ? 'selected' : '' ?>>4</option>
								<option value="5" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 5)  ? 'selected' : '' ?>>5</option>
								<option value="6" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 6)  ? 'selected' : '' ?>>6</option>
								<option value="7" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 7)  ? 'selected' : '' ?>>7</option>
								<option value="8" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 8)  ? 'selected' : '' ?>>8</option>
								<option value="9" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 9)  ? 'selected' : '' ?>>9</option>
								<option value="10" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 10)  ? 'selected' : '' ?>>10</option>
								<option value="11" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 11)  ? 'selected' : '' ?>>11</option>
								<option value="12" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 12)  ? 'selected' : '' ?>>12</option>
								<option value="13" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 13)  ? 'selected' : '' ?>>13</option>
								<option value="14" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 14)  ? 'selected' : '' ?>>14</option>
								<option value="15" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 15)  ? 'selected' : '' ?>>15</option>
								<option value="16" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 16)  ? 'selected' : '' ?>>16</option>
								<option value="17" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 17)  ? 'selected' : '' ?>>17</option>
								<option value="18" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 18)  ? 'selected' : '' ?>>18</option>
								<option value="19" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 19)  ? 'selected' : '' ?>>19</option>
								<option value="20" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 20)  ? 'selected' : '' ?>>20</option>
								<option value="21" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 21)  ? 'selected' : '' ?>>21</option>
								<option value="22" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 22)  ? 'selected' : '' ?>>22</option>
								<option value="23" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 23)  ? 'selected' : '' ?>>23</option>
								<option value="24" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 24)  ? 'selected' : '' ?>>24</option>
								<option value="25" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 25)  ? 'selected' : '' ?>>25</option>
								<option value="26" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 26)  ? 'selected' : '' ?>>26</option>
								<option value="27" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 27)  ? 'selected' : '' ?>>27</option>
								<option value="28" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 28)  ? 'selected' : '' ?>>28</option>
								<option value="29" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 29)  ? 'selected' : '' ?>>29</option>
								<option value="30" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 30)  ? 'selected' : '' ?>>30</option>
								<option value="31" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 31)  ? 'selected' : '' ?>>31</option>
								<option value="32" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 32)  ? 'selected' : '' ?>>32</option>
								<option value="33" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 33)  ? 'selected' : '' ?>>33</option>
								<option value="34" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 34)  ? 'selected' : '' ?>>34</option>
								<option value="35" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 35)  ? 'selected' : '' ?>>35</option>
								<option value="36" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 36)  ? 'selected' : '' ?>>36</option>
								<option value="37" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 37)  ? 'selected' : '' ?>>37</option>
								<option value="38" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 38)  ? 'selected' : '' ?>>38</option>
								<option value="39" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 39)  ? 'selected' : '' ?>>39</option>
								<option value="40" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 40)  ? 'selected' : '' ?>>40</option>
								<option value="41" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 41)  ? 'selected' : '' ?>>41</option>
								<option value="42" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 42)  ? 'selected' : '' ?>>42</option>
								<option value="43" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 43)  ? 'selected' : '' ?>>43</option>
								<option value="44" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 44)  ? 'selected' : '' ?>>44</option>
								<option value="45" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 45)  ? 'selected' : '' ?>>45</option>
								<option value="46" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 46)  ? 'selected' : '' ?>>46</option>
								<option value="47" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 47)  ? 'selected' : '' ?>>47</option>
								<option value="48" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 48)  ? 'selected' : '' ?>>48</option>
								<option value="49" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 49)  ? 'selected' : '' ?>>49</option>
								<option value="50" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->no_of_kitchens == 50)  ? 'selected' : '' ?>>50</option>
                            </select>
			                {!! !empty($errors->has('no_of_kitchens')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_kitchens').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Type</label>
							<?php  $errorClass =  !empty($errors->has('kitchen_type')) ? 'is-invalid':''; ?>   
                            <select name="kitchen_type" id="kitchen_type" data-parsley-required="true" class="form-control kitchen_type {{$errorClass}}" data-placeholder="Type" data-dropdown-css-class="select2-primary">
								<option value="Unspecified" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_type == 'Unspecified')  ? 'selected' : '' ?>>Unspecified</option>
								<option value="American" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_type == 'American')  ? 'selected' : '' ?>>American</option>
								<option value="Independent" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_type == 'Independent')  ? 'selected' : '' ?>>Independent</option>
                            </select>
			                {!! !empty($errors->has('kitchen_type')) ?'<div class="invalid-feedback"><span>'.$errors->first('kitchen_type').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Category</label>
							<?php  $errorClass =  !empty($errors->has('kitchen_category')) ? 'is-invalid':''; ?>   
                            <select name="kitchen_category" id="kitchen_category" data-parsley-required="true" class="form-control kitchen_type {{$errorClass}}" data-placeholder="Category" data-dropdown-css-class="select2-primary">
								<option value="Unspecified" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_category == 'Unspecified')  ? 'selected' : '' ?>>Unspecified</option>
								<option value="Gas" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_category == 'Gas')  ? 'selected' : '' ?>>Gas</option>
								<option value="Hotplate_of_glass_ceramics" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_category == 'Hotplate_of_glass_ceramics')  ? 'selected' : '' ?>>Hotplate of glass ceramics</option>
								<option value="Electric" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_category == 'Electric')  ? 'selected' : '' ?>>Electric</option>
								<option value="Combined" <?php echo isset($data) &&  ($data->getPropertyKitchen[0]->kitchen_category == 'Combined')  ? 'selected' : '' ?>>Combined(gas and electric)</option>
                            </select>
			                {!! !empty($errors->has('kitchen_category')) ?'<div class="invalid-feedback"><span>'.$errors->first('kitchen_category').'</span></div>' :'' !!}
						</div>
						<?php if(isset($data) && !empty($data->getPropertyKitchen[0]->kitchen_amenities)){
								if( strpos($data->getPropertyKitchen[0]->kitchen_amenities, ',') !== false ) {
									$kitchen_amenities = explode(",",$data->getPropertyKitchen[0]->kitchen_amenities);
								}else{
									$kitchen_amenities = (array)$data->getPropertyKitchen[0]->kitchen_amenities;
								}
							}else{
								$kitchen_amenities = [];
							} 
						?>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Fridge" <?php if(isset($kitchen_amenities)){ if(in_array('Fridge',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Fridge</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Dishwasher" <?php if(isset($kitchen_amenities)){ if(in_array('Dishwasher',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Dishwasher</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Kitchen_utensils" <?php if(isset($kitchen_amenities)){ if(in_array('Kitchen_utensils',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Kitchen utensils</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Freezer" <?php if(isset($kitchen_amenities)){ if(in_array('Freezer',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Freezer</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Tableware" <?php if(isset($kitchen_amenities)){ if(in_array('Tableware',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Tableware</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Coffee_machine" <?php if(isset($kitchen_amenities)){ if(in_array('Coffee_machine',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Coffee machine</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Oven" <?php if(isset($kitchen_amenities)){ if(in_array('Oven',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Oven</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Electric_kettle" <?php if(isset($kitchen_amenities)){ if(in_array('Electric_kettle',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Electric kettle</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Mini_deep_fryer" <?php if(isset($kitchen_amenities)){ if(in_array('Mini_deep_fryer',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Mini Deep fryer</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Microwave" <?php if(isset($kitchen_amenities)){ if(in_array('Microwave',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Microwave</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Blender" <?php if(isset($kitchen_amenities)){ if(in_array('Blender',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Blender</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="kitchen_amenities[]" class="checkbox" type="checkbox" value="Toaster" <?php if(isset($kitchen_amenities)){ if(in_array('Toaster',$kitchen_amenities)){ echo 'checked'; } } ?>>
								<label for="inputName" class="form-label">Toaster</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">EQUIPMENT / BEDDING</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Bed linen</label>
							<?php  $errorClass =  !empty($errors->has('bed_linen')) ? 'is-invalid':''; ?>   
                            <select name="bed_linen" id="bed_linen" data-parsley-required="true" class="form-control bed_linen {{$errorClass}}" data-placeholder="Bed linen" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen == 0 ? 'selected' : ''; ?>>Not provided</option>
								<option value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen == 1 ? 'selected' : ''; ?>>Provided</option>
                            </select>
			                {!! !empty($errors->has('bed_linen')) ?'<div class="invalid-feedback"><span>'.$errors->first('bed_linen').'</span></div>' :'' !!}
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="bed_linen_change" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Bed linen change</label>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Bed change frequency</label>
							<?php  $errorClass =  !empty($errors->has('bed_Change_frequency')) ? 'is-invalid':''; ?>   
                            <select name="bed_Change_frequency" id="bed_Change_frequency" class="form-control bed_Change_frequency {{$errorClass}}" data-placeholder="Bed change frequency" data-dropdown-css-class="select2-primary">
								<option value="0" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 0 ? 'selected' : ''; ?>>0</option>
								<option value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 1 ? 'selected' : ''; ?>>1</option>
								<option value="2" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 2 ? 'selected' : ''; ?>>2</option>
								<option value="3" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 3 ? 'selected' : ''; ?>>3</option>
								<option value="4" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 4 ? 'selected' : ''; ?>>4</option>
								<option value="5" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 5 ? 'selected' : ''; ?>>5</option>
								<option value="6" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 6 ? 'selected' : ''; ?>>6</option>
								<option value="7" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 7 ? 'selected' : ''; ?>>7</option>
								<option value="8" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 8 ? 'selected' : ''; ?>>8</option>
								<option value="9" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 9 ? 'selected' : ''; ?>>9</option>
								<option value="10" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 10 ? 'selected' : ''; ?>>10</option>
								<option value="11" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 11 ? 'selected' : ''; ?>>11</option>
								<option value="12" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 12 ? 'selected' : ''; ?>>12</option>
								<option value="13" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 13 ? 'selected' : ''; ?>>13</option>
								<option value="14" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 14 ? 'selected' : ''; ?>>14</option>
								<option value="15" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 15 ? 'selected' : ''; ?>>15</option>
								<option value="16" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_Change_frequency == 16 ? 'selected' : ''; ?>>16</option>
                            </select>
			                {!! !empty($errors->has('bed_Change_frequency')) ?'<div class="invalid-feedback"><span>'.$errors->first('bed_Change_frequency').'</span></div>' :'' !!}
						</div>

						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="washing_machine" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->washing_machine == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Washing machine</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="dryer" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->dryer == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Dryer</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="iron" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->iron == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Iron</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="television" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->television == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Television</label>
							</div>
						</div>

						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Televisions</label>
							<?php  $errorClass =  !empty($errors->has('no_of_television')) ? 'is-invalid':''; ?>   
                            <select name="no_of_television" id="no_of_television" data-parsley-required="true" class="form-control no_of_television {{$errorClass}}" data-placeholder="Select Televisions" data-dropdown-css-class="select2-primary">
								<option value="0"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '0' ? 'selected' : ''; ?>>0</option>
								<option value="1"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '1' ? 'selected' : ''; ?>>1</option>
								<option value="2"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '2' ? 'selected' : ''; ?>>2</option>
								<option value="3"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '3' ? 'selected' : ''; ?>>3</option>
								<option value="4"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '4' ? 'selected' : ''; ?>>4</option>
								<option value="5"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '5' ? 'selected' : ''; ?>>5</option>
								<option value="6"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '6' ? 'selected' : ''; ?>>6</option>
								<option value="7"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '7' ? 'selected' : ''; ?>>7</option>
								<option value="8"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '8' ? 'selected' : ''; ?>>8</option>
								<option value="9"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '9' ? 'selected' : ''; ?>>9</option>
								<option value="10"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '10' ? 'selected' : ''; ?>>10</option>
								<option value="11"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '11' ? 'selected' : ''; ?>>11</option>
								<option value="12"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '12' ? 'selected' : ''; ?>>12</option>
								<option value="13"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '13' ? 'selected' : ''; ?>>13</option>
								<option value="14"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '14' ? 'selected' : ''; ?>>14</option>
								<option value="15"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '15' ? 'selected' : ''; ?>>15</option>
								<option value="16"  <?php echo isset($data) && $data->getPropertyBedding[0]->no_of_television == '16' ? 'selected' : ''; ?>>16</option>
                            </select>
			                {!! !empty($errors->has('no_of_television')) ?'<div class="invalid-feedback"><span>'.$errors->first('no_of_television').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Location of Television</label>
			                {!! Form::text('location_of_television', null, array('id'=>'location_of_television','placeholder' => 'Location of Television', 'class' => 'form-control' )) !!}
						</div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Fans</label>
							<?php  $errorClass =  !empty($errors->has('fans')) ? 'is-invalid':''; ?>   
                            <select name="fans" id="fans" data-parsley-required="true" class="form-control fans {{$errorClass}}" data-placeholder="Fans" data-dropdown-css-class="select2-primary">
								<option value="0"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '0' ? 'selected' : ''; ?>>0</option>
								<option value="1"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '1' ? 'selected' : ''; ?>>1</option>
								<option value="2"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '2' ? 'selected' : ''; ?>>2</option>
								<option value="3"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '3' ? 'selected' : ''; ?>>3</option>
								<option value="4"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '4' ? 'selected' : ''; ?>>4</option>
								<option value="5"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '5' ? 'selected' : ''; ?>>5</option>
								<option value="6"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '6' ? 'selected' : ''; ?>>6</option>
								<option value="7"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '7' ? 'selected' : ''; ?>>7</option>
								<option value="8"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '8' ? 'selected' : ''; ?>>8</option>
								<option value="9"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '9' ? 'selected' : ''; ?>>9</option>
								<option value="10"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '10' ? 'selected' : ''; ?>>10</option>
								<option value="11"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '11' ? 'selected' : ''; ?>>11</option>
								<option value="12"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '12' ? 'selected' : ''; ?>>12</option>
								<option value="13"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '13' ? 'selected' : ''; ?>>13</option>
								<option value="14"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '14' ? 'selected' : ''; ?>>14</option>
								<option value="15"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '15' ? 'selected' : ''; ?>>15</option>
								<option value="16"  <?php echo isset($data) && $data->getPropertyBedding[0]->fans == '16' ? 'selected' : ''; ?>>16</option>
                            </select>
			                {!! !empty($errors->has('fans')) ?'<div class="invalid-feedback"><span>'.$errors->first('fans').'</span></div>' :'' !!}
						</div>

						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="satellite_tv" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Satellite TV</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="radio" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Radio</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="dvd_player" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">DVD player</label>
							</div>
						</div>

						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Mosquito netting</label>
							<?php  $errorClass =  !empty($errors->has('mosquito_netting')) ? 'is-invalid':''; ?>   
                            <select name="mosquito_netting" id="mosquito_netting" class="form-control mosquito_netting {{$errorClass}}" data-placeholder="Mosquito netting" data-dropdown-css-class="select2-primary">
								<option value="in_bedroom"  <?php echo isset($data) && $data->getPropertyBedding[0]->mosquito_netting == 'in_bedroom' ? 'selected' : ''; ?>>In Bedroom</option>
								<option value="in_the_whole_house"  <?php echo isset($data) && $data->getPropertyBedding[0]->mosquito_netting == 'in_the_whole_house' ? 'selected' : ''; ?>>In the whole house</option>
                            </select>
			                {!! !empty($errors->has('mosquito_netting')) ?'<div class="invalid-feedback"><span>'.$errors->first('mosquito_netting').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3">
							<div class="ck-box">
								<input name="electronic_mosquito_repellents" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->bed_linen_change == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Electronic mosquito repellents</label>
							</div>
						</div>
						<?php if(isset($data) && !empty($data->getPropertyBedding[0]->satellite_tv_language)){
								$satellite_tv_language = explode(",",$data->getPropertyBedding[0]->satellite_tv_language);
							}else{
								$satellite_tv_language = [];
							} 
							// dd($satellite_tv_language);
						?>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Satellite TV Languages</label>
							<div class="ck-box-items">
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Spanish" <?php if(isset($satellite_tv_language)){ if(in_array('Spanish',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Spanish</label>
								</div>
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="English" <?php if(isset($satellite_tv_language)){ if(in_array('English',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">English</label>
								</div>
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="German" <?php if(isset($satellite_tv_language)){ if(in_array('German',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">German</label>
								</div>
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Italian" <?php if(isset($satellite_tv_language)){ if(in_array('Italian',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Italian</label>
								</div>
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Dutch" <?php if(isset($satellite_tv_language)){ if(in_array('Dutch',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Dutch</label>
								</div>
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Portugese" <?php if(isset($satellite_tv_language)){ if(in_array('Portugese',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Portugese</label>
								</div>
									
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="French" <?php if(isset($satellite_tv_language)){ if(in_array('French',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">French</label>
								</div>
									
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Norwegian" <?php if(isset($satellite_tv_language)){ if(in_array('Norwegian',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Norwegian</label>
								</div>
									
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Swedish" <?php if(isset($satellite_tv_language)){ if(in_array('Swedish',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Swedish</label>
								</div>
									
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Russian" <?php if(isset($satellite_tv_language)){ if(in_array('Russian',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Russian</label>
								</div>
									
								<div class="ck-box">
									<input name="satellite_tv_language[]" class="checkbox" type="checkbox" value="Arabic" <?php if(isset($satellite_tv_language)){ if(in_array('Arabic',$satellite_tv_language)){ echo 'checked'; } } ?>>
									<label for="inputName" class="form-label">Arabic</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Internet access</label>
							<?php  $errorClass =  !empty($errors->has('internet_access')) ? 'is-invalid':''; ?>   
                            <select name="internet_access" id="internet_access" class="form-control internet_access {{$errorClass}}" data-placeholder="Internet Access" data-dropdown-css-class="select2-primary">
								<option value="wifi"  <?php echo isset($data) && $data->getPropertyBedding[0]->internet_access == 'wifi' ? 'selected' : ''; ?>>Wifi</option>
								<option value="usb"  <?php echo isset($data) && $data->getPropertyBedding[0]->internet_access == 'usb' ? 'selected' : ''; ?>>USB</option>
								<option value="network_cable" <?php echo isset($data) && $data->getPropertyBedding[0]->internet_access == 'network_cable' ? 'selected' : ''; ?>>Network cable</option>
                            </select>
			                {!! !empty($errors->has('internet_access')) ?'<div class="invalid-feedback"><span>'.$errors->first('internet_access').'</span></div>' :'' !!}
						</div>
						<div class="col-md-2 mb-3 network_name_div">
							<label for="inputName" class="form-label">Network name (SSID)</label>
                            <input name="network_name" class="form-control" type="text" value="<?php echo isset($data) && $data->getPropertyBedding[0]->key_code_number ? $data->getPropertyBedding[0]->network_name : ''; ?>">
						</div>
						<div class="col-md-2 mb-3 password_div">
							<label for="inputName" class="form-label">Password</label>
                            <input name="password" class="form-control" type="text" value="<?php echo isset($data) && $data->getPropertyBedding[0]->key_code_number ? $data->getPropertyBedding[0]->password : ''; ?>">
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="safe" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->safe == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Safe</label>
							</div>
						</div>
						<div class="col-md-2 mb-3">
							<div class="ck-box">
								<input name="mini_bar" class="checkbox" type="checkbox" value="1" <?php echo isset($data) && $data->getPropertyBedding[0]->mini_bar == 1 ? 'checked' : ''; ?>>
								<label for="inputName" class="form-label">Mini bar</label>
							</div>
						</div>
						<div class="col-md-3 mb-4">
							<label for="inputName" class="form-label">Key Code Number</label>
                            <input name="key_code_number" class="form-control" type="text" value="<?php echo isset($data) && $data->getPropertyBedding[0]->key_code_number ? $data->getPropertyBedding[0]->key_code_number : ''; ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">AMENITIES</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 mb-3 main_amenity_div">
							<label for="inputAmenities" class="form-label">Select Amenities*</label>
							<?php  $errorClass =  !empty($errors->has('property_amenities')) ? 'is-invalid':''; ?>   
							<select name="property_amenities[]" id="property_amenities" data-parsley-required="true" class="form-control property_amenities select2 {{$errorClass}}" data-placeholder="Select Amenities"  multiple>
								@if(count($amenities) > 0)
								@foreach($amenities as $amenity)
									<option value="{{$amenity->id}}" <?php if(isset($selected_amenities)){ if(in_array($amenity->id,$selected_amenities)){ echo 'selected'; } } ?>>{{$amenity->name}}</option>
								@endforeach
								@endif
							</select>
							{!! !empty($errors->has('property_amenities')) ?'<div class="invalid-feedback"><span>'.$errors->first('property_amenities').'</span></div>' :'' !!}
						</div>
						<div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
							<label for=""></label>
							<a href="javascript:void(0)" class="btn btn-primary add_new" onclick="newAmenityModal(this)" > Add New</a>
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Standout Amenities</label>
			                {!! Form::text('standout_amenities', null, array('id'=>'standout_amenities','placeholder' => 'Standout Amenities', 'class' => 'form-control' )) !!}
						</div>
						<div class="col-md-6 mb-3"></div>
						<div class="col-md-4 mb-3">
							<label for="inputSwimmingpool" class="form-label">Swimming pool</label>
							<?php  $errorClass =  !empty($errors->has('swimming_pool')) ? 'is-invalid':''; ?>   
							<select name="swimming_pool" id="swimming_pool" class="form-control swimming_pool {{$errorClass}}" data-placeholder="Select Swimming pool">
								<option value="None" <?php echo isset($data) && $data->swimming_pool == 'None' ? 'selected' : ''; ?>>None</option>
								<option value="Shared" <?php echo isset($data) && $data->swimming_pool == 'Shared' ? 'selected' : ''; ?>>Shared</option>
								<option value="Shared_kids_pool" <?php echo isset($data) && $data->swimming_pool == 'Shared_kids_pool' ? 'selected' : ''; ?>>Shared + kids pool</option>
								<option value="Private" <?php echo isset($data) && $data->swimming_pool == 'Private' ? 'selected' : ''; ?>>Private</option>
							</select>
							{!! !empty($errors->has('swimming_pool')) ?'<div class="invalid-feedback"><span>'.$errors->first('swimming_pool').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3 pool_opening_period_div">
							<label for="inputpoolOpeningPeriod" class="form-label">Opening Period</label>
							<?php  $errorClass =  !empty($errors->has('pool_opening_period')) ? 'is-invalid':''; ?>   
							<input type="text" name="pool_opening_period" class="form-control pool_opening_period" placeholder="Select Opening period" value="<?php echo isset($data) && $data->pool_opening_period != Null ? date('Y-m-d', strtotime($data->pool_opening_period)) : ''; ?>">
							{!! !empty($errors->has('pool_opening_period')) ?'<div class="invalid-feedback"><span>'.$errors->first('pool_opening_period').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3 pool_closing_period_div">
							<label for="inputpoolClosingPeriod" class="form-label">Closing Period</label>
							<?php  $errorClass = !empty($errors->has('pool_closing_period')) ? 'is-invalid':''; ?>   
							<input type="text" name="pool_closing_period" class="form-control pool_closing_period" placeholder="Select Closing period" value="<?php echo isset($data) && $data->pool_closing_period != Null ? date('Y-m-d', strtotime($data->pool_closing_period)) : ''; ?>">
							{!! !empty($errors->has('pool_closing_period')) ?'<div class="invalid-feedback"><span>'.$errors->first('pool_closing_period').'</span></div>' :'' !!}
						</div>

						<div class="col-md-4 mb-3">
							<label for="inputHeatedSwimmingpool" class="form-label">Heated swimming pool</label>
							<?php  $errorClass =  !empty($errors->has('heated_swimming_pool')) ? 'is-invalid':''; ?>   
							<select name="heated_swimming_pool" id="heated_swimming_pool" class="form-control heated_swimming_pool {{$errorClass}}" data-placeholder="Select Heated swimming pool">
								<option value="None" <?php echo isset($data) && $data->heated_swimming_pool == 'None' ? 'selected' : ''; ?>>None</option>
								<option value="Shared" <?php echo isset($data) && $data->heated_swimming_pool == 'Shared' ? 'selected' : ''; ?>>Shared</option>
								<option value="Private" <?php echo isset($data) && $data->heated_swimming_pool == 'Private' ? 'selected' : ''; ?>>Private</option>
							</select>
							{!! !empty($errors->has('heated_swimming_pool')) ?'<div class="invalid-feedback"><span>'.$errors->first('heated_swimming_pool').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3 heated_pool_opening_period_div">
							<label for="inputHeatedpoolOpeningPeriod" class="form-label">Opening Period</label>
							<?php  $errorClass =  !empty($errors->has('heated_pool_opening_period')) ? 'is-invalid':''; ?>   
							<input type="text" name="heated_pool_opening_period" class="form-control heated_pool_opening_period" placeholder="Select Opening period" value="<?php echo isset($data) && $data->heated_pool_opening_period != Null ? date('Y-m-d', strtotime($data->heated_pool_opening_period)) : ''; ?>">
							{!! !empty($errors->has('heated_pool_opening_period')) ?'<div class="invalid-feedback"><span>'.$errors->first('heated_pool_opening_period').'</span></div>' :'' !!}
						</div>
						<div class="col-md-4 mb-3 heated_pool_closing_period_div">
							<label for="inputHeatedpoolClosingPeriod" class="form-label">Closing Period</label>
							<?php  $errorClass = !empty($errors->has('heated_pool_closing_period')) ? 'is-invalid':''; ?>   
							<input type="text" name="heated_pool_closing_period" class="form-control heated_pool_closing_period" placeholder="Select Closing period" value="<?php echo isset($data) && $data->heated_pool_closing_period != Null ? date('Y-m-d', strtotime($data->heated_pool_closing_period)) : ''; ?>">
							{!! !empty($errors->has('heated_pool_closing_period')) ?'<div class="invalid-feedback"><span>'.$errors->first('heated_pool_closing_period').'</span></div>' :'' !!}
						</div>
					</div>
				</div>
			</div>
			<div class="card margin-cls">
				<h4 class="dataLabel">GUEST AREA</h4>
				<div class="card-body">
					<div class="row">
						<div class="col-md-3 mb-4">
							<label for="inputName" class="form-label">House Rules</label>
							<div id='TextBoxesGroup'>
								@if(isset($data) && count($data->getPropertyHouserule) > 0)
								@foreach($data->getPropertyHouserule as $house_key => $house_value)
									<div id="TextBoxDiv{{$house_key}}">
										<div class="row appendAttrDiv_{{$house_key}}">
											<div class="col-md-12">
												<div class="input-group input-cls">
													<input type="text" placeholder="Add House Rule" name="house_rule[{{$house_key}}]" data-count="0" data-id="product_qty_{{$house_key}}" id="product_qty_{{$house_key}}"  class="form-control form-control-line product_qty" value="{{ $house_value->name }}">
													@if($house_key < 1)
													<div class="add-btn">
														<a id='addButton' onclick="addMoreProducts()" class="btn btn-primary btn-xs ms-2" style="color:white;"><i class="bx bx-plus"></i></a>
													</div>
													@endif
													@if($house_key >= 1)
													<div class="add-btn">
														<a onclick="removeAttributeField('{{$house_key}}')" class="btn btn-danger btn-xs delete_button_{{$house_key}} ms-2" style="color:white;"><i class="bx bx-trash"></i></a>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								@endforeach
								@else
									<div id="TextBoxDiv0">
										<div class="row">
											<div class="col-md-12">
												<div class="input-group input-cls">
													<input type="text" placeholder="Add House Rule" name="house_rule[0]" data-count="0" data-id="product_qty_0" id="product_qty_0"  class="form-control form-control-line product_qty">
													<div class="add-btn">
														<a id='addButton' onclick="addMoreProducts()" class="btn btn-primary btn-xs ms-2" style="color:white;"><i class="bx bx-plus"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endif
							</div>
						</div>
						<!-- <div class="col-md-9 mb-4"></div>
						<div class="col-md-3 mb-3">
							<label for="inputName" class="form-label">Above information are correct or not? *</label>
                            <select name="information_correct_or_not" id="information_correct_or_not" class="form-control information_correct_or_not" data-placeholder="Above information are correct or not?" data-dropdown-css-class="select2-primary" data-parsley-required="true">
								<option value="">Above information are correct or not?</option>
								<option value="Yes"  <?php echo isset($data) && $data->information_correct_or_not == 'Yes' ? 'selected' : ''; ?>>Yes</option>
								<option value="No"  <?php echo isset($data) && $data->information_correct_or_not == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
						</div> -->
					</div>
				</div>
			</div>	
			<div class="pb-2">
			    <div class="d-flex">
                   <a href="{{ route('admin.property.index') }}" class="btn btn-light">Cancel</a>
				   	@if(isset($data) && !empty($data->id))
				   		<button type="submit" class="btn btn-light ms-auto">Update</button>
				   	@else
                   		<button type="submit" class="btn btn-light ms-auto">Submit</button>
					@endif
			    </div>	
            </div>	
	</div>
</div>

<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script> 
<script src="{{ URL::asset('assets/plugins/magicsuggest/magicsuggest.js')}}"></script>
<script src="{{ URL::asset('assets/js/bootstrap-tagsinput.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
	$(document).ready(function(){
		CKEDITOR.replace('description',{

		});
	});
	$(document).ready(function(){
		// $(".add_new").click(function(){
		// 	$("#newBuildingModal").modal('toggle');
		// });
		var cctv = "{{isset($data->cctv) ? $data->cctv :''}}";
		if(cctv == 'Yes'){
			$('.cctv_locations_div').css('display','block');
		}else{
			$('.cctv_locations_div').css('display','none');
		}
	});

    $('.cctv').on('change', function(){
      if($(this).val() == 'Yes'){
        $('.cctv_locations_div').css('display','block');
      }else{
        $('.cctv_locations_div').css('display','none');
      }
    })

	$( document ).ready(function() {
		$( ".txtOnly" ).keypress(function(e) {
			var key = e.keyCode;
			if (key >= 48 && key <= 57) {  // 0-9
				e.preventDefault();
			}
		});
	});

    function onlyNumberKey(evt) {  
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
          return false;
      return true;
    }
	// $(function () {
	// 	var tags = $('#magicsuggest').magicSuggest({
	// 		data: ['London', 'Paris', 'Barcelona'],
	// 		width: 160,
	// 		placeholder: 'Search Issue',
	// 		maxDropHeight: 500,
	// 		maxSelection: 1,
	// 		sortOrder: 'name'
	// 	});
	// });

	$(function() {
        // var continent = $('#magicsuggest').magicSuggest({
        var continent = $('.input_tag_test').magicSuggest({
           /* autoSelect: false,
            allowFreeEntries: false,*/
            maxSelection:10,
            placeholder: 'Enter tag name',
            data: '{{url('admin/property/showTags')}}',
            method:'get',
            valueField: 'tag',
            displayField: 'tag',
            renderer: function(data){
                return '<div class="country">' +
                        '<div class="name">' + data.tag + '</div>' +
                        '<div style="clear:both;"></div>' +
                        '<div style="clear:both;"></div>' +
                    '</div>';
            }
        });
    });

	var counter = "{{ isset($data) && !empty($data->getPropertyHouserule) ? count($data->getPropertyHouserule) -1 : 0 }}";
	function addMoreProducts() {
		counter++;
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
		newTextBoxDiv.after().html('<div id="TextBoxDiv'+counter+'"><div class="row appendAttrDiv_'+counter+'"><div class="col-md-12"><div class="input-group input-cls"><input type="text" placeholder="Add House Rule" name="house_rule['+counter+']" data-count="'+counter+'" data-id="product_qty_'+counter+'" id="product_qty_'+counter+'" class="form-control form-control-line" data-parsley-required="true"><div class="add-btn removeDelete_'+counter+'"><a onclick="removeAttributeField('+counter+')" class="btn btn-danger btn-xs ms-2" style="color:white;"><i class="bx bx-trash"></i></a></div></div></div></div></div>');
		newTextBoxDiv.appendTo("#TextBoxesGroup");
	}

	function removeAttributeField(id) {
		$('.appendAttrDiv_'+id).remove();
		$('.delete_button_'+id).remove();
	}
	// $('.property_amenities').select2(
	// 	// placeholder: "Select Amenities"
	// );
	$('.select2').select2({
		// minimumResultsForSearch: -1,
		placeholder: function(){
			$(this).data('placeholder');
		}
	});

	$('.communal_zones').select2();
	$('.owner_select2').select2();
	// changetowels();
	changeInternetAccess();
	changeswimming_pool();
	changeheated_swimming_pool();
	function changeInternetAccess(){
		var internet_access_val = $('.internet_access').val();
		if(internet_access_val == 'wifi'){
			$('.network_name_div').css('display','block');
			$('.password_div').css('display','block');
		}else{
			$('.network_name_div').css('display','none');
			$('.password_div').css('display','none');
		}
	}

	$(document).ready(function(){
		$(".pool_opening_period").datepicker({
				minDate: "+0D",
				// maxDate: "+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".end_date").datepicker("option","minDate", selected)
				}
		});
		$(".pool_closing_period").datepicker({
				minDate: "+0D",
				// maxDate:"+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".start_date").datepicker("option","maxDate", selected)
				}
		});
		$(".heated_pool_opening_period").datepicker({
				minDate: "+0D",
				// maxDate: "+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".end_date").datepicker("option","minDate", selected)
				}
		});
		$(".heated_pool_closing_period").datepicker({
				minDate:"+0D",
				// maxDate:"+0D",
				numberOfMonths: 1,
				dateFormat:'yy-mm-dd',
				onSelect: function(selected) {
				$(".start_date").datepicker("option","maxDate", selected)
				}
		});
	});
	$(function() {
		$('.internet_access').change(function(){
			if($(this).val() == 'wifi'){
				$('.network_name_div').css('display','block');
				$('.password_div').css('display','block');
			}else{
				$('.network_name_div').css('display','none');
				$('.password_div').css('display','none');
			}
		});
	});
	
	/*Swimming pool open close Start*/
	function changeswimming_pool(){
		var swimming_pool = $('.swimming_pool').val();
		if(swimming_pool == 'None'){
			$('.pool_opening_period_div').css('display','none');
			$('.pool_closing_period_div').css('display','none');
		}else{
			$('.pool_opening_period_div').css('display','block');
			$('.pool_closing_period_div').css('display','block');
		}
	}
	$(function() {
		$('.swimming_pool').change(function(){
			if($(this).val() == 'None'){
				$('.pool_opening_period_div').css('display','none');
				$('.pool_closing_period_div').css('display','none');
			}else{
				$('.pool_opening_period_div').css('display','block');
				$('.pool_closing_period_div').css('display','block');
			}
		});
	});	

	function changeheated_swimming_pool(){
		var heated_swimming_pool = $('.heated_swimming_pool').val();
		if(heated_swimming_pool == 'None'){
			$('.heated_pool_opening_period_div').css('display','none');
			$('.heated_pool_closing_period_div').css('display','none');
		}else{
			$('.heated_pool_opening_period_div').css('display','block');
			$('.heated_pool_closing_period_div').css('display','block');
		}
	}
	$(function() {
		$('.heated_swimming_pool').change(function(){
			if($(this).val() == 'None'){
				$('.heated_pool_opening_period_div').css('display','none');
				$('.heated_pool_closing_period_div').css('display','none');
			}else{
				$('.heated_pool_opening_period_div').css('display','block');
				$('.heated_pool_closing_period_div').css('display','block');
			}
		});
	});
	/*Swimming pool open close End*/

	$(document).ready(function () {
		getProvince();
		initialize();
		getCategory();
	    var latitude = "{{isset($data->latitude) ? $data->latitude :'26.8549135'}}";
	    var longitude = "{{isset($data->longitude) ? $data->longitude :'75.7676642'}}";
	    autoload(latitude, longitude);

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

	$(document).on('change', '.type_list',function(){
		var type_val = $(this).val();
		var category = "<?php if (isset($data) && $data->category) { echo $data->category; } ?>";

		if (type_val) {
			$.ajax({
				url:'{{url("admin/category/show_category")}}/'+type_val+'/'+category,
				dataType: 'html',
				success:function(result)
				{
					$('.show_categories').html(result);
				}
			});
		}
	})

	function getCategory() {
		var type_val = $('.type_list').val();
		var category = "<?php if (isset($selected_category) && $selected_category) { echo json_encode($selected_category); } ?>";

		if (type_val) {
			$.ajax({
				url:'{{url("admin/category/show_category")}}/'+type_val+'/'+category,
				dataType: 'html',
				success:function(result)
				{
					$('.show_categories').html(result);
				}
			});
		}
	}

	/*Country province city Area start*/
	$(document).on('change', '.country_id',function(){
		var country_id = $('#country_id').val();
        var province_id = "<?php if (isset($data) && $data->province_id) { echo $data->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
					// alert(country_name);
					var country_name = $('#country_id').find("option:selected").text();
					// getLatLongByCountry(country_name);

					if ($('#country_id').find("option:selected").val() ){
						var street_name = $(".street_name").val();
						if(street_name != ''){
							const address = street_name+', '+ country_name;
							getLatLongByCountry(address);
						}else{
							const address = country_name;
							getLatLongByCountry(address);
						}
					}
	            }
	        });
        }
	})

	$(document).on('change', '.province_id',function(){
        var country_id = $('#country_id').val();
        var province_id = $('#province_id').val();
        var city_id = "<?php if (isset($data) && $data->city_id) { echo $data->city_id; } ?>";
		// alert(city_id);
        if (province_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					var country_name = $('#country_id').find("option:selected").text();
					var province_name = $('#province_id').find("option:selected").text();
					// const address = province_name +', '+ country_name;
					// // alert('address--'+address);
					// getLatLongByCountry(address);

					if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() ){
						var street_name = $(".street_name").val();
						if(street_name != ''){
							const address = street_name+', '+ province_name +', '+ country_name;
							getLatLongByCountry(address);
						}else{
							const address = province_name +', '+ country_name;
							getLatLongByCountry(address);
						}
					}
	            }
	        });
        }
	})

	$(document).on('change', '.city_id',function(){
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = "<?php if ( isset($data->area) && $data->area != null) { echo $data->area; }else{ echo ''; } ?>";
		
		if (city_id) {
			$.ajax({
				url:'{{url("admin/area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
				dataType: 'html',
				success:function(result)
				{
					$('.show_areaDiv').html(result);
					var country_name = $('#country_id').find("option:selected").text();
					var province_name = $('#province_id').find("option:selected").text();
					var city_name = $('#city_id').find("option:selected").text();
					// const address = city_name+', '+province_name +', '+ country_name;

					if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
						var street_name = $(".street_name").val();
						if(street_name != ''){
							const address = street_name+', '+ city_name+', '+province_name +', '+ country_name;
							getLatLongByCountry(address);
						}else{
							const address = city_name+', '+province_name +', '+ country_name;
							getLatLongByCountry(address);
						}
					}
					// alert('address--'+address);
					// getLatLongByCountry(address);
				}
			});
		}
	})

	$(document).ready(function(){
		$(".postal_code").keyup(function(){
			// console.log(this.value.length);
			if(this.value.length > 4){
				var country_name = $('#country_id').find("option:selected").text();
				var province_name = $('#province_id').find("option:selected").text();
				var city_name = $('#city_id').find("option:selected").text();
				var area_name = $('#area').find("option:selected").text();
				var postal_code = this.value;

				if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
					var street_name = $(".street_name").val();
					if(street_name != ''){
						const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
						getLatLongByCountry(address);
					}else{
						const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
						getLatLongByCountry(address);
					}
				}
				// const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
				// alert('address--'+address);
				// getLatLongByCountry(address);
			}
		});
	});

	$(document).ready(function(){
		$(".street_name").keyup(function(){
			// console.log(this.value.length);
			if(this.value.length >= 3){
				var country_name = $('#country_id').find("option:selected").text();
				var province_name = $('#province_id').find("option:selected").text();
				var city_name = $('#city_id').find("option:selected").text();
				var area_name = $('#area').find("option:selected").text();
				var postal_code = $('.postal_code').val();
				var street_name = this.value;

				if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
					const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
					getLatLongByCountry(address);
				}
			}
		});
	});

	function getStreet(){
		var country_name = $('#country_id').find("option:selected").text();
		var province_name = $('#province_id').find("option:selected").text();
		var city_name = $('#city_id').find("option:selected").text();
		var area_name = $('#area').find("option:selected").text();
		var postal_code = "<?php if (isset($data) && $data->getPropertyAddress[0]->postal_code) { echo $data->getPropertyAddress[0]->postal_code; } ?>";
		var street_name = "<?php if (isset($data) && $data->getPropertyAddress[0]->street_name) { echo $data->getPropertyAddress[0]->street_name; } ?>";
		// const address = street_name+', '+postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
		// alert('address--'+address);

		if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
			const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
			getLatLongByCountry(address);
		}
		// getLatLongByCountry(address);
	}

	function getPostalCode(){
		var country_name = $('#country_id').find("option:selected").text();
		var province_name = $('#province_id').find("option:selected").text();
		var city_name = $('#city_id').find("option:selected").text();
		var area_name = $('#area').find("option:selected").text();
		var postal_code = "<?php if (isset($data) && $data->getPropertyAddress[0]->postal_code) { echo $data->getPropertyAddress[0]->postal_code; } ?>";
		var street_name = "<?php if (isset($data) && $data->getPropertyAddress[0]->street_name) { echo $data->getPropertyAddress[0]->street_name; } ?>";
		// const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
		// getLatLongByCountry(address);

		if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
			const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
			getLatLongByCountry(address);
		}
	}

    function getProvince() {
        var country_id = $('#country_id').val();
        var province_id = "<?php if (isset($data) && $data->getPropertyAddress[0]->province_id) { echo $data->getPropertyAddress[0]->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
	                getCity();
					// var address = $('#country_id').find("option:selected").text();
					// getLatLongByCountry(address);
					if ($('#country_id').find("option:selected").val()  ){
						const address = country_name;
						getLatLongByCountry(address);
					}
	            }
	        });
        }
    }

    function getCity() {
        var country_id = $('#country_id').val();
        var province_id = $('#province_id').val();
        var city_id = "<?php if (isset($data) && $data->getPropertyAddress[0]->city_id) { echo $data->getPropertyAddress[0]->city_id; } ?>";
		// alert(city_id);
        if (province_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					getArea();
					// console.log('areaaaaaaaaaaaaaa1111');
					var country_name = $('#country_id').find("option:selected").text();
					var province_name = $('#province_id').find("option:selected").text();
					const address = province_name +', '+ country_name;
					// alert('address--'+address);
					getLatLongByCountry(address);
	            }
	        });
        }
    }

	function getArea() {
		// console.log('areaaaaaaaaaaaaaa');
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = "<?php if (isset($data) && $data->getPropertyAddress[0]->area) { echo $data->getPropertyAddress[0]->area; } ?>";
		// alert(country_id);
		// alert(province_id);
		// alert(city_id);
		// alert(area_id);
		if (city_id) {
			$.ajax({
				url:'{{url("admin/area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
				dataType: 'html',
				success:function(result)
				{
					$('.show_areaDiv').html(result);
					changeAreaInner();
					var country_name = $('#country_id').find("option:selected").text();
					var province_name = $('#province_id').find("option:selected").text();
					var city_name = $('#city_id').find("option:selected").text();
					const address = city_name+', '+province_name +', '+ country_name;
					// alert('address--'+address);
					getLatLongByCountry(address);
				}
			});
		}
	}


	$(document).on('change', '.area',function(){
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = $('#area').val();
		if (area_id) {
			var country_name = $('#country_id').find("option:selected").text();
			var province_name = $('#province_id').find("option:selected").text();
			var city_name = $('#city_id').find("option:selected").text();
			var area_name = $('#area').find("option:selected").text();
			if(area_name){
				const address = area_name+', '+city_name+', '+province_name +', '+ country_name;
				// alert('address--'+address);
				getLatLongByCountry(address);
			}else{
				const address = city_name+', '+province_name +', '+ country_name;
				// alert('address--'+address);
				getLatLongByCountry(address);
			}
		}
	})

	function changeAreaInner() {
		var country_name = $('#country_id').find("option:selected").text();
			var province_name = $('#province_id').find("option:selected").text();
			var city_name = $('#city_id').find("option:selected").text();
			var area_name = $('#area').find("option:selected").text();
			if(area_name){
				const address = area_name+', '+city_name+', '+province_name +', '+ country_name;
				// alert('address--'+address);
				getLatLongByCountry(address);
			}else{
				const address = city_name+', '+province_name +', '+ country_name;
				// alert('address--'+address);
				getLatLongByCountry(address);
			}
			getPostalCode();
			getStreet();
	}
	/*Country province city Area End*/

	$(".input_tag_test").keyup(function(){
		var value = $(this).val();
		// alert(value);
	});

    // $(".input_tag_test").keypress(function(){  
	// 	alert('inn');
    //     // $("span").text (i += 1);  
    // });  
	// $(document).ready( function() {
		// $(".input_tag_test").on("keyup", function( e ) {
		// 	var value = $(this).val();
		// 	alert(value);
		// 	// $.ajax({
		// 	// 	type: 'GET',
		// 	// 	url: '{{url('admin/property1/showTags')}}/'+value,
		// 	// 	// data: { search: searchString, other: otherString, isChecked: yourCheckBoxProperty }
		// 	// 	success:function(result)
		// 	// 	{
		// 	// 		$('.show_areaDiv').html(result);
		// 	// 	}
		// 	// })
		// });
	// });

	// var citynames = new Bloodhound({
	// 	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	// 	queryTokenizer: Bloodhound.tokenizers.whitespace,
	// 	prefetch: {
	// 		// url: 'assets/citynames.json',
	// 		type: 'GET',
	// 		// dataType:'json',
	// 		url: '{{url('admin/property1/showTags')}}',

    //         // renderer: function(data){
    //         //     return '<div class="country">' +
    //         //             '<div class="name">' + data.tag + '</div>' +
    //         //             '<div style="clear:both;"></div>' +
    //         //             '<div style="clear:both;"></div>' +
    //         //         '</div>';
    //         // }
	// 		filter: function(list) {
	// 			return $.map(list, function(cityname) {
	// 				console.log('cityname--'+cityname);
	// 				return { name: cityname }; 
	// 			});
	// 		}
	// 	}
	// });
	// citynames.initialize();

	// $('.input_tag_test').tagsinput({
	// 	typeaheadjs: {
	// 		name: 'citynames',
	// 		displayKey: 'name',
	// 		valueKey: 'name',
	// 		source: citynames.ttAdapter()
	// 	}
	// });

	// $(function () {
	// 	var tags = $('#magicsuggest').magicSuggest({
	// 		data: ['London', 'Paris', 'Barcelona'],
	// 		width: 160,
	// 		placeholder: 'Search Issue',
	// 		maxDropHeight: 500,
	// 		maxSelection: 1,
	// 		sortOrder: 'name'
	// 	});
	// });

	// $(function() {
    //     var continent = $('#magicsuggest').magicSuggest({
    //     // var continent = $('.input_tag_test').magicSuggest({
    //        /* autoSelect: false,
    //         allowFreeEntries: false,*/
    //         maxSelection:10,
    //         placeholder: 'Enter tag name',
    //         data: '{{url('admin/property/showTags')}}',
    //         method:'get',
    //         valueField: 'tag',
    //         displayField: 'tag',
    //         renderer: function(data){
    //             return '<div class="country">' +
    //                     '<div class="name">' + data.tag + '</div>' +
    //                     '<div style="clear:both;"></div>' +
    //                     '<div style="clear:both;"></div>' +
    //                 '</div>';
    //         }
    //     });
    // });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".alert").delay(5000).slideUp(300);
	});
  // get address start
	var geocoder;
	var map;
	var marker;
	var infowindow = new google.maps.InfoWindow({
		size: new google.maps.Size(150, 50)
	});
    // initialize();
  // autoload(25.204849, 55.270783);

    var autocomplete;
	function getLatLongByCountry(address){
		if (address) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$('#latitude').val('');
			$('#longitude').val('');
			// alert(country_name);
	        $.ajax({
	            url:'{{url("admin/getLatLongByCountry")}}',
				data: { 'address': address },
	            type: 'post',
	            success:function(result)
	            {
					// console.log('result--'+result.lat+'result1--'+result.long);
					if(result.lat){
						console.log('result--'+result.lat+', result1--'+result.lng);
						const lat = result.lat;
						const lng = result.lng;
						$('#latitude').val(lat);
						$('#longitude').val(lng);
						$('#address').val(address);
						autoload(lat, lng);
					}
	            }
	        });
        }
	};

	function autoload(latitude,longitude) {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(latitude, longitude);
		var mapOptions = {
			zoom: 13,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		google.maps.event.addListener(map, 'click', function() {
			infowindow.close();
		});

		marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: {lat:latitude, lng: longitude}
		});
		marker.addListener('click', toggleBounce);
	}

	function toggleBounce()
	{
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
</script>