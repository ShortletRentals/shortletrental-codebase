@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
@endsection
<div class="card">
  	<div class="card-body p-4">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
       	<div class="form-body">
		    <div class="row">
			   	<div class="col-lg-12">
		           	<div class="margin-cls border border-3 p-4 rounded row padding-bottom">
					   <h4 class="dataLabel">GENERAL DATA</h4>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Discount Code*</label>
							<?php  $errorClass =  !empty($errors->has('code')) ? 'is-invalid':''; ?>   
			                {!! Form::text('code', null, array('id'=>'code','placeholder' => 'Enter Discount Code*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('code')) ?'<div class="invalid-feedback"><span>'.$errors->first('code').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Discount Percentage*</label>
							<?php  $errorClass =  !empty($errors->has('percentage')) ? 'is-invalid':''; ?>   
			                {!! Form::text('percentage', null, array('id'=>'percentage','placeholder' => 'Enter percentage*', 'required'=>'required', 'min'=>'1', 'max'=>'100', 'onkeypress'=>'return onlyNumberKey(event)', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('percentage')) ?'<div class="invalid-feedback"><span>'.$errors->first('percentage').'</span></div>' :'' !!}
						</div>
						<div class="col-md-12 mb-3 categories-cls">
							<input type="checkbox" name="all_categories" id="all_categories" value="Yes" <?php if(isset($data) && $data->all_categories == 'Yes'){ echo 'checked'; } ?>>
							<label for="all_categories" class="form-label">All Category</label>
						</div>
						<div class="col-md-6 mb-3 category_div">
							<label for="inputName" class="form-label">Category*</label>
							<?php  $errorClass =  !empty($errors->has('category_id')) ? 'is-invalid':''; ?>
			                <select name="category_id" class="form-control category_id {{$errorClass}}" onchange="getProvince()" id='category_id' required>
			                    @if(!empty($category))
				                    <option value="">Select Category</option>
				                    @foreach($category as $key => $category)
				                    	<?php $selected = isset($data->category_id) ? $data->category_id : "";?>
				                        <option value="{{$category->id}}" <?php echo $selected == $category->id  ? 'selected' : '' ?>>{{$category->name}}</option>
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
						<div class="col-md-6 mb-3">
				            <div class="form-group">
			                  <label for="discount_code" class="form-label">Valid From*</label>
			                  <input type="text" name="start_date" id="valid_from" data-parsley-required="true" class="form-control start_date" value="{{ isset($data) ? date('Y-m-d', strtotime($data->start_date)) : ''  }}" placeholder="From Date*" readonly />
				            </div>
				        </div>
				        <div class="col-md-6 mb-3">
				            <div class="form-group">
				              <label for="discount_code" class="form-label">Valid Upto*</label>
				              <input type="text" name="end_date" id="valid_upto" data-parsley-required="true" class="form-control end_date" value="{{ isset($data) ? date('Y-m-d', strtotime($data->end_date)) : ''  }}" placeholder="To Date*" readonly />
				            </div>
				        </div>
				        <div class="col-md-6 mb-3">
				            <div class="form-group">
				              <label for="total_use" class="form-label">No of user applied*</label>
				              <input type="text" name="total_use" id="total_use" value="{{ isset($data) ? $data->total_use : ''  }}" required='required' min=1 max=100 onkeypress='return onlyNumberKey(event)' class="form-control" placeholder="No of user applied*"/>
				            </div>
				        </div>
				        <div class="col-md-6 mb-3">
				            <div class="form-group">
				              <label for="total_single_use" class="form-label">No of use by single guest*</label>
				              <input type="text" name="total_single_use" id="total_single_use" value="{{ isset($data) ? $data->total_single_use : ''  }}" required='required' min=1 max=100 onkeypress='return onlyNumberKey(event)' class="form-control" placeholder="No of use by single guest*"/>
				            </div>
				        </div>
						<div class="col-md-6 mb-3">
							<label for="inputInfluencer" class="form-label">Select Partner</label>
							<?php  $errorClass =  !empty($errors->has('influencer')) ? 'is-invalid':''; ?>
			                <select name="influencer" class="form-control influencer {{$errorClass}}" id='influencer'>
			                    @if(!empty($influencer_list))
				                    <option value="">Select Partner</option>
				                    @foreach($influencer_list as $influencer)
				                        <option value="{{$influencer->id}}" <?php if(isset($data->influencer) && $data->influencer == $influencer->id){ echo 'selected'; } ?> >{{$influencer->name.' '.$influencer->surname}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
			                @error('influencer')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('influencer')) ?'<div class="invalid-feedback"><span>'.$errors->first('influencer').'</span></div>' :'' !!}
						</div>
						<div class="col-md-12 mb-3 properties-cls">
							<input type="checkbox" name="all_properties" id="all_properties" value="Yes" <?php if(isset($data) && $data->all_properties == 'Yes'){ echo 'checked'; } ?>>
							<label for="all_properties" class="form-label">All Accomodation</label>
						</div>
						<div class="col-md-6 mb-3 select_acco_div">
							<label for="inputPropertyId" class="form-label">Select Accomodation</label>
							<?php  $errorClass =  !empty($errors->has('property_id')) ? 'is-invalid':''; ?>
			                <select name="property_id[]" class="form-control property_id {{$errorClass}}" id='property_id' multiple>
			                    @if(!empty($properties))
				                    <!-- <option value="All">All Accomodation</option> -->
				                    @foreach($properties as $property)
				                        <option value="{{$property->id}}" <?php if(isset($selected_discount_properties)){ if(in_array($property->id,$selected_discount_properties)){ echo 'selected'; } } ?> class="others">{{$property->title}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
			                @error('property_id')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('property_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('property_id').'</span></div>' :'' !!}
						</div>
			            <div class="col-md-12">
						    <div class="d-flex">
								<a href="{{ route('admin.discount.index') }}" class="btn btn-light">Cancel</a>
								@if(isset($data) && !empty($data->id))
							   		<button type="submit" class="btn btn-light ms-auto">Update</button>
							   	@else
									<button type="submit" class="btn btn-light ms-auto">Save</button>
								@endif
						    </div>		  
			            </div>
		            </div>
			   	</div>
			</div>
	   </div><!--end row-->
	</div>
</div>
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
	function onlyNumberKey(evt) {  
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
          return false;
      return true;
    }

	$('.property_id').select2();
	function enable_category() {
		var val = "{{ isset($data) && !empty($data->all_categories) ? $data->all_categories : '' }}";
		if (val == 'Yes') {
			$('.category_id').prop("required", false);
			$('.category_div').css('display','none');
		} else {
			$('.category_id').prop("required", true);
			$('.category_div').css('display','block');
		}
	}
	function enable_cb() {
		var val = "{{ isset($data) && !empty($data->all_properties) ? $data->all_properties : '' }}";
		if (val == 'Yes') {
			// $('.property_id').prop("disabled", true);
			$('.select_acco_div').css('display','none');
		} else {
			// $('.property_id').prop("disabled", false);
			$('.select_acco_div').css('display','block');
		}
	}

	$(document).ready(function () {
		enable_category();
		enable_cb();
		$('#all_categories').on('click', function(){
			if($(this).is(':checked')) {
				$('.category_id').prop("required", false);
				$('.category_div').css('display','none');
			}else{
				$('.category_id').prop("required", true);
				$('.category_div').css('display','block');
			}
		});
		$('#all_properties').on('click', function(){
			if($(this).is(':checked')) {
				// $('.property_id').prop("disabled", true);
				$('.select_acco_div').css('display','none');
			}else{
				// $('.property_id').prop("disabled", false);
				$('.select_acco_div').css('display','block');
			}
		});

		$(".start_date").datepicker({
             minDate: "-0D",
             numberOfMonths: 1,
             dateFormat:'yy-mm-dd',
             onSelect: function(selected) {
               $(".end_date").datepicker("option","minDate", selected)
             }
         });
         $(".end_date").datepicker({
             minDate:"-0D",
             numberOfMonths: 1,
             dateFormat:'yy-mm-dd',
             onSelect: function(selected) {
                $(".start_date").datepicker("option","maxDate", selected)
             }
         });

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
</script>