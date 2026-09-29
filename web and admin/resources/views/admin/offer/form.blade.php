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
							<label for="inputName" class="form-label">Discount</label>
							<?php  $errorClass =  !empty($errors->has('discount')) ? 'is-invalid':''; ?>
			                <select name="discount" class="form-control discount {{$errorClass}}" id='discount'>
			                    @if(!empty($discount))
				                    <option value="">Select Discount</option>
				                    @foreach($discount as $key_discount => $discount_value)
				                    	<?php $selected_discount = isset($data->discount) ? $data->discount : "";?>
				                        <option value="{{$discount_value->id}}" <?php echo $selected_discount == $discount_value->id  ? 'selected' : '' ?>>{{$discount_value->code}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
			                @error('discount')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('discount')) ?'<div class="invalid-feedback"><span>'.$errors->first('discount').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputImage" class="form-label">Offer Image*</label>
	                        <div class="input-group">
	                        	@if(isset($data) && $data->image)
                                    <div id="image_preview"><img id="previewing" src="{{ $data->image }}"></div>
                                @else
	                            	<div id="image_preview"><img id="previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
	                            @endif
	                            <div class="form-control" onclick="document.getElementById('file').click()">
	                                <label for="files">Select Offer Image</label>
	                                <input type="file" id="file" name="image" style="visibility:hidden;" class="form-control" <?php if(isset($data) && $data->image){}else{ echo 'required'; } ?>>
	                            </div>
	                         </div>
	                    </div>
						<div class="col-md-12 mb-3 properties-cls mt-3">
							<input type="checkbox" name="all_properties" id="all_properties" value="Yes" <?php if(isset($data) && $data->all_properties == 'Yes'){ echo 'checked'; } ?>>
							<label for="all_properties" class="form-label">All Accomodation</label>
						</div>
						<div class="col-md-6 mb-3 select_acco_div">
							<label for="inputPropertyId" class="form-label">Select Accomodation</label>
							<?php  $errorClass =  !empty($errors->has('property_id')) ? 'is-invalid':''; ?>
			                <select name="property_id[]" class="form-control property_id {{$errorClass}}" id='property_id' multiple>
			                    @if(!empty($properties))
				                    @foreach($properties as $property)
				                        <option value="{{$property->id}}" <?php if(isset($selected_offer_properties)){ if(in_array($property->id,$selected_offer_properties)){ echo 'selected'; } } ?> class="others">{{$property->title}}</option>
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
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Title*</label>
							<?php  $errorClass =  !empty($errors->has('title')) ? 'is-invalid':''; ?>   
			                {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Enter title', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('title')) ?'<div class="invalid-feedback"><span>'.$errors->first('title').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputProductDescription" class="form-label">Description</label>
							<textarea class="form-control" name="description" id="description" rows="3"><?php echo $data->description ?? '' ?></textarea>
						</div>
			            <div class="col-md-12">
						    <div class="d-flex">
								<a href="{{ route('admin.offer.index') }}" class="btn btn-light">Cancel</a>
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
	$('.property_id').select2();
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
		enable_cb();
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