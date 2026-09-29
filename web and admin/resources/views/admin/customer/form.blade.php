@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
<div class="card">
  	<div class="card-body p-4">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
       	<div class="form-body">
		    <div class="row">
			   	<div class="col-lg-12">
		           	<div class="margin-cls border border-3 p-4 rounded row">
					   	<h4 class="dataLabel">CUSTOMER DETAILS</h4>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Name*</label>
							<div class="input-group">
					            <div class="input-group-prepend">
					                <?php $error = !empty($errors->first('title'))?' is-invalid':''; ?>
									@if(isset($data) && !empty($data->title))
										<select name="title" class="form-control title {{$error}}" id='title'>
											<!-- <option value="">-- Select Title--</option> -->
											<option value="Company" <?php echo isset($data) && $data->title == 'Company' ? 'selected' : ''; ?>>Company</option>
											<option value="Dr" <?php echo isset($data) && $data->title == 'Dr' ? 'selected' : ''; ?>>Dr</option>
											<option value="Family" <?php echo isset($data) && $data->title == 'Family' ? 'selected' : ''; ?>>Family</option>
											<option value="Mr_&_Mrs" <?php echo isset($data) && $data->title == 'Mr_&_Mrs' ? 'selected' : ''; ?>>Mr & Mrs</option>
											<option value="Mr" <?php echo isset($data) && $data->title == 'Mr' ? 'selected' : ''; ?>>Mr.</option>
											<option value="Mrs" <?php echo isset($data) && $data->title == 'Mrs' ? 'selected' : ''; ?>>Mrs.</option>
											<option value="Ms" <?php echo isset($data) && $data->title == 'Ms' ? 'selected' : ''; ?>>Ms.</option>
											<option value="PhD" <?php echo isset($data) && $data->title == 'PhD' ? 'selected' : ''; ?>>PhD</option>
											<option value="Prof" <?php echo isset($data) && $data->title == 'Prof' ? 'selected' : ''; ?>>Prof</option>
										</select>
									@else
										<select name="title" class="form-control title {{$error}}" id='title'>
											<!-- <option value="">-- Select Title--</option> -->
											<option value="Company">Company</option>
											<option value="Dr" >Dr</option>
											<option value="Family" >Family</option>
											<option value="Mr_&_Mrs" >Mr & Mrs</option>
											<option value="Mr" selected>Mr.</option>
											<option value="Mrs" >Mrs.</option>
											<option value="Ms" >Ms.</option>
											<option value="PhD" >PhD</option>
											<option value="Prof" >Prof</option>
										</select>
									@endif
					                @error('title')
					                    <span class="invalid-feedback" role="alert">
					                    	<label>{{ $message }}</label>
					                    </span>
					                @enderror
					            </div>
								<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>
								{!! Form::text('name', null, array('id'=>'name','placeholder' => 'Name*', 'required'=>'required','data-parsley-pattern'=>'^[a-zA-Z ]+$', 'data-parsley-pattern-message'=>'Name allow only character', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!}
								{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
				            </div> 
				        </div>
						<div class="col-md-6 mb-3">
							<label for="inputSurname" class="form-label">Surname*</label>
							<?php $errorClass =  !empty($errors->has('surname')) ? 'is-invalid':''; ?>
			                {!! Form::text('surname', null, array('id'=>isset($data->surname)?true:false,'placeholder' => 'Surname*', 'required'=>'required', 'class' => 'form-control '.$errorClass)) !!}
			                {!! !empty($errors->has('surname')) ?'<div class="invalid-feedback"><span>'.$errors->first('surname').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputPassword" class="form-label">Password*</label>
							<?php $errorClass = !empty($errors->has('password')) ? 'is-invalid':''; ?>
							<div class="input-group" id="show_hide_password">
								<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>	
								<input type="password" value="" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="inputChoosePassword" placeholder="Enter Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" <?php if(isset($data->password) == null){ ?> required='required' <?php } ?> data-parsley-minlength="8" data-parsley-maxlength="32" maxlength="32" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters."> 
								@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
			                {!! !empty($errors->has('password')) ?'<div class="invalid-feedback"><span>'.$errors->first('password').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputMobile" class="form-label">Profile Image</label>
	                        <div class="input-group">
	                        	@if(isset($data) && $data->image)
                                    <div id="image_preview" class="image_preview_div"><img id="previewing" src="{{ $data->image }}"></div>
                                @else
	                            	<div id="image_preview" class="image_preview_div"><img id="previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
	                            @endif
	                            <div class="form-control" onclick="document.getElementById('file').click()">
	                                <label for="files">Select Image</label>
	                                <input type="file" id="file" name="image" style="visibility:hidden;" class="form-control">
	                            </div>
	                         </div>
	                    </div>
						<div class="col-md-6 mb-3">
							<label for="inputDOB" class="form-label">Date of birth</label>
							<input type="text" class="form-control dob" name="dob" value="{{ isset($data) && !empty($data->dob) ? date('d-m-y', strtotime($data->dob)) : '' }}" placeholder="Enter Date of birth">
	                    </div>
						<div class="col-md-6 mb-3">
							<label for="inputRemarks" class="form-label">Remarks</label>
							<?php $errorClass =  !empty($errors->has('remarks')) ? 'is-invalid':''; ?>
			                <textarea name="remarks" id="remarks" cols="30" rows="4" placeholder="Remarks" class="form-control"><?php echo isset($data) && !empty($data->remarks) ? $data->remarks : ''; ?></textarea>
			                {!! !empty($errors->has('remarks')) ?'<div class="invalid-feedback"><span>'.$errors->first('remarks').'</span></div>' :'' !!}
						</div>
		            </div>
					<div class="margin-cls border border-3 p-4 rounded row">
						<h4 class="dataLabel">CONTACT</h4>
						<div class="col-md-6 mb-3">
							<label for="inputEmail" class="form-label">Email*</label>
							<?php $errorClass =  !empty($errors->has('email')) ? 'is-invalid':''; ?>
			                {!! Form::email('email', null, array('id'=>isset($data->email)?true:false,'placeholder' => 'Email*', 'required'=>'required', 'class' => 'form-control '.$errorClass)) !!}
			                {!! !empty($errors->has('email')) ?'<div class="invalid-feedback"><span>'.$errors->first('email').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputEmail" class="form-label">Secondary Email</label>
							<?php $errorClass =  !empty($errors->has('secondary_email')) ? 'is-invalid':''; ?>
			                {!! Form::email('secondary_email', null, array('id'=>isset($data->secondary_email)?true:false,'placeholder' => 'Secondary Email', 'class' => 'form-control '.$errorClass)) !!}
			                {!! !empty($errors->has('secondary_email')) ?'<div class="invalid-feedback"><span>'.$errors->first('secondary_email').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputMobile" class="form-label">Mobile*</label>
							<div class="input-group">
					            <div class="input-group-prepend">
					                <?php $error = !empty($errors->first('county_code'))?' is-invalid':''; ?>
					                <select name="country_code" class="form-control country_code {{$error}}" id='country_code'>
					                    @if(!empty($country_phone))
						                    @foreach($country_phone as $key => $country_ph)
						                    	<?php $selected = isset($data->country_code) ? $data->country_code : "+234" ; ?>
						                        <option value="{{$country_ph->phonecode}}" <?php echo $selected == $country_ph->phonecode  ? 'selected' : '' ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
						                    @endforeach
					                    @else
					                    @endif
					                </select>
					                @error('county_code')
					                    <span class="invalid-feedback" role="alert">
					                    <label>{{ $message }}</label>
					                    </span>
					                @enderror
					            </div> 
					            <?php 
					            $errorClass =  !empty($errors->has('mobile')) ? 'is-invalid':''; ?>
					            {!! Form::text('mobile', null, array('placeholder' => "Mobile Number*",'data-parsley-minlength'=>"8",'data-parsley-maxlength'=>"13",'maxlength'=>"13",'onkeypress'=>"return onlyNumberKey(event)", 'data-parsley-minlength-message'=>'Enter number between 8 to 13 digits.','required'=>'required', 'class' => 'form-control '.$errorClass)) !!}
					            {!! !empty($errors->has('mobile')) ?'<div class="invalid-feedback"><span>'.$errors->first('mobile').'</span></div>' :'' !!}                                            
				            </div> 
				        </div>
						<div class="col-md-6 mb-3">
							<label for="inputSecondMobile" class="form-label">2nd Phone</label>
							<div class="input-group">
					            <div class="input-group-prepend">
					                <?php $error = !empty($errors->first('second_county_code'))?' is-invalid':''; ?>
					                <select name="second_country_code" class="form-control second_country_code {{$error}}" id='second_country_code'>
					                    @if(!empty($country_phone))
						                    @foreach($country_phone as $key1 => $country_ph1)
						                    	<?php $selected1 = isset($data->second_country_code) ? $data->second_country_code : "+234" ; ?>
						                        <option value="{{$country_ph1->phonecode}}" <?php echo $selected1 == $country_ph1->phonecode  ? 'selected' : '' ?>>{{$country_ph1->sortname}} +{{$country_ph1->phonecode}}</option>
						                    @endforeach
					                    @else
					                    @endif
					                </select>
					                @error('second_county_code')
					                    <span class="invalid-feedback" role="alert">
					                    <label>{{ $message }}</label>
					                    </span>
					                @enderror
					            </div> 
					            <?php 
					            $errorClass =  !empty($errors->has('second_mobile')) ? 'is-invalid':''; ?>
					            {!! Form::text('second_mobile', null, array('placeholder' => "2nd Phone Number",'data-parsley-minlength'=>"8",'data-parsley-maxlength'=>"13",'maxlength'=>"13",'onkeypress'=>"return onlyNumberKey(event)", 'data-parsley-minlength-message'=>'Enter number between 8 to 13 digits.', 'class' => 'form-control '.$errorClass)) !!}
					            {!! !empty($errors->has('second_mobile')) ?'<div class="invalid-feedback"><span>'.$errors->first('second_mobile').'</span></div>' :'' !!}                                            
				            </div> 
				        </div>
		            </div>

					<div class="margin-cls border border-3 p-4 rounded row">
						<h4 class="dataLabel">ADDRESS</h4>
						<div class="col-md-4 mb-3">
							<label for="inputName" class="form-label">Country*</label>
							<?php  $errorClass =  !empty($errors->has('country_id')) ? 'is-invalid':''; ?>
			                <select name="country_id" class="form-control country_id" id='country_id' required>
			                    @if(!empty($country))
				                    <option value="">Select Country</option>
				                    @foreach($country as $key => $country1)
				                    	<?php $selected = isset($data) && !empty($data->country_id) ? $data->country_id : "";?>
				                        <option value="{{$country1->id}}" <?php echo $selected == $country1->id  ? 'selected' : '' ?>>{{$country1->name}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
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

						<div class="col-md-4 mb-3 show_cityDiv">
							<label for="inputName" class="form-label">City*</label>
			                <select name="city_id" class="form-control city_id {{$errorClass}}" id='city_id' required>
			                    <option value="">Select City</option>
			                </select>
						</div>
						
						

						<div class="col-md-9 mb-9">
							<label for="inputStreet" class="form-label">Address </label>
							<?php $errorClass =  !empty($errors->has('address')) ? 'is-invalid':''; ?>
			                {!! Form::text('address', null, array('id'=>'address','placeholder' => 'Address', 'class' => 'form-control '.$errorClass)) !!}
							<input type="hidden" name="latitude" id="latitude" class="form-control" placeholder="Enter latitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->latitude) ? $data->getPropertyAddress[0]->latitude : "";?>">
												<input type="hidden" name="longitude" id="longitude" class="form-control" placeholder="Enter longitude" value="<?php echo isset($data) && !empty($data->getPropertyAddress[0]->longitude) ? $data->getPropertyAddress[0]->longitude : "";?>">

			                {!! !empty($errors->has('address')) ?'<div class="invalid-feedback"><span>'.$errors->first('address').'</span></div>' :'' !!}
						</div>


					
						<div class="col-md-3 mb-3">
							<label for="inputPostalCode" class="form-label">Postal Code</label>
							<?php $errorClass = !empty($errors->has('postal_code')) ? 'is-invalid':''; ?>
							{!! Form::text('postal_code', null, array('id'=>isset($data->postal_code)?true:false,'placeholder' => 'Postal Code','data-parsley-minlength'=>"5",'data-parsley-maxlength'=>"7",'maxlength'=>"7",'onkeypress'=>"return onlyNumberKey(event)", 'class' => 'form-control '.$errorClass)) !!}
							{!! !empty($errors->has('postal_code')) ?'<div class="invalid-feedback"><span>'.$errors->first('postal_code').'</span></div>' :'' !!}
						</div>
					</div>

					<div class="margin-cls border border-3 p-4 rounded row">
						<h4 class="dataLabel">DOCUMENTATION</h4>
						<div class="col-md-6 mb-3">
							<label for="inputDocumentnumber" class="form-label">Document number </label>
							<?php $errorClass =  !empty($errors->has('document_number')) ? 'is-invalid':''; ?>
							{!! Form::text('document_number', null, array('id'=>isset($data->document_number)?true:false,'placeholder' => 'Enter Document number', 'class' => 'form-control '.$errorClass)) !!}
							{!! !empty($errors->has('document_number')) ?'<div class="invalid-feedback"><span>'.$errors->first('document_number').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputDocumentImage" class="form-label">Document Image</label>
							<div class="input-group">
								@if(isset($data) && $data->document_image)
									<div id="document_image_view_div" class="image_preview_div"><img id="document_previewing" src="{{ $data->document_image }}"></div>
								@else
									<div id="document_image_view_div" class="image_preview_div"><img id="document_previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
								@endif
								<div class="form-control" onclick="document.getElementById('document_image').click()">
									<label for="files">Select Document Image</label>
									<input type="file" id="document_image" name="document_image" style="visibility:hidden;" class="form-control">
								</div>
							</div>
						</div>
					</div>
					

					<div class="p-0">
						<div class="col-md-12">
							<div class="d-flex">
								<a href="{{ route('admin.customer.index') }}" class="btn btn-light">Cancel</a>
								@if(isset($data) && !empty($data->id))
									<button type="submit" class="btn btn-light ms-auto">Update</button>
								@else
									<button type="submit" class="btn btn-light ms-auto">Submit</button>
								@endif
							</div>		  
						</div>
					</div>

			   	</div>
			</div>
	   </div>
	   <!--end row-->
	</div>
</div>
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script>
	$(document).ready(function () {
		$("#show_hide_password a").on('click', function (event) {
			event.preventDefault();
			if ($('#show_hide_password input').attr("type") == "text") {
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass("bx-hide");
				$('#show_hide_password i').removeClass("bx-show");
			} else if ($('#show_hide_password input').attr("type") == "password") {
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass("bx-hide");
				$('#show_hide_password i').addClass("bx-show");
			}
		});
	});
</script>
<script>
    function onlyNumberKey(evt) {  
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
          return false;
      return true;
    }
	$(document).ready(function () {
		initialize();
		getProvince();
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

		////Upload Document Image
		$("#document_image").change(function(){
	      var fileObj = this.files[0];
	      var imageFileType = fileObj.type;
	      var imageSize = fileObj.size;

	      var file = $('#document_image')[0].files[0].name;
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
					reader.onload = document_imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
				}else{
					toastr.error('Images Size Too large Please Select Less Than 5MB File!!');
					return false;
				} 
			}
	    });
	    function document_imageIsLoaded(e){
	      //console.log(e);
	      $("#document_image").css("color","green");
	      $('#document_previewing').attr('src',e.target.result);
	    }
	})

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
	                // getCity();
					// getArea();
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
					// getArea();
	            }
	        });
        }
	})

	$(document).on('change', '.city_id',function(){
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = "<?php if (isset($data) && $data->area) { echo $data->area; } ?>";
		if (city_id) {
			$.ajax({
				url:'{{url("admin/area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
				dataType: 'html',
				success:function(result)
				{
					$('.show_areaDiv').html(result);
				}
			});
		}
	})

    function getProvince() {
        var country_id = $('#country_id').val();
        var province_id = "<?php if (isset($data) && $data->province_id) { echo $data->province_id; } ?>";
        if (country_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
	                getCity();
					// getArea();
	            }
	        });
        }
    }

    function getCity() {
        var country_id = $('#country_id').val();
        var province_id = $('#province_id').val();
        var city_id = "<?php if (isset($data) && $data->city_id) { echo $data->city_id; } ?>";
        if (province_id) {
	        $.ajax({
	            url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					// getArea();
	            }
	        });
        }
    }

	$(document).ready(function(){
		$(".dob").datepicker({
			// minDate:"-1Y",
			maxDate:"+0D",
			numberOfMonths: 1,
			dateFormat:'dd-mm-yy',
			yearRange: "-100:+0",
			changeMonth: true,
			changeYear: true,
			// defaultDate: '-1Y', //Default to One Year Ago to show 12 months
			onSelect: function(selected) {
				$(".start_date").datepicker("option","maxDate", selected)
			}
		});
	});
</script>