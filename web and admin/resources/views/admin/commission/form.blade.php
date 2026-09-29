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
									<!-- <div class="col-md-6 mb-3">
										<label for="inputName" class="form-label">Host</label>
										<?php  $errorClass =  !empty($errors->has('host_id')) ? 'is-invalid':''; ?>
						                <select name="host_id" class="form-control host_id {{$errorClass}}" id='host_id' required>
						                    @if(!empty($user))
							                    <option value="">Select Host</option>
							                    @foreach($user as $key_user => $user_value)
							                    	<?php $selected_user = isset($data->host_id) ? $data->host_id : "";?>
							                        <option value="{{$user_value->id}}" <?php echo $selected_user == $user_value->id  ? 'selected' : '' ?>>{{$user_value->name}}</option>
							                    @endforeach
						                    @else
						                    @endif
						                </select>
						                @error('host_id')
						                    <span class="invalid-feedback" role="alert">
						                    <label>{{ $message }}</label>
						                    </span>
						                @enderror
						                {!! !empty($errors->has('host_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('host_id').'</span></div>' :'' !!}
									</div> -->
									<div class="col-md-12 mb-3 properties-cls mt-3">
										<input type="checkbox" name="all_hosts" id="all_hosts" value="Yes" <?php if(isset($data) && $data->all_hosts == 'Yes'){ echo 'checked'; } ?>>
										<label for="all_hosts" class="form-label">All Owners</label>
									</div>
									<div class="col-md-6 mb-3 select_host_div">
										<label for="inputName" class="form-label">Select Owner*</label>
										<?php  $errorClass =  !empty($errors->has('host_id')) ? 'is-invalid':''; ?>
						                <select name="host_id" class="form-control host_id {{$errorClass}}" onchange="getAccomodation()" id='host_id' required>
						                    @if(!empty($user))
							                    <option value="">Select Host</option>
							                    @foreach($user as $key_user => $user_value)
							                    	<?php $selected_user = isset($data->host_id) ? $data->host_id : "";?>
							                        <option value="{{$user_value->id}}" <?php echo $selected_user == $user_value->id  ? 'selected' : '' ?>>{{$user_value->name}}</option>
							                    @endforeach
						                    @else
						                    @endif
						                </select>
						                @error('host_id')
						                    <span class="invalid-feedback" role="alert">
						                    <label>{{ $message }}</label>
						                    </span>
						                @enderror
						                {!! !empty($errors->has('host_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('host_id').'</span></div>' :'' !!}
									</div>
									<div class="col-md-12 mb-3 properties-cls mt-3">
										<input type="checkbox" name="all_properties" id="all_properties" value="Yes" <?php if(isset($data) && $data->all_properties == 'Yes'){ echo 'checked'; } ?>>
										<label for="all_properties" class="form-label">All Accomodation</label>
									</div>
									<div class="col-md-6 mb-3 select_acco_div show_accomodation">
										<label for="inputPropertyId" class="form-label">Select Accomodation</label>
										<?php  $errorClass =  !empty($errors->has('property_id')) ? 'is-invalid':''; ?>
						                <select name="property_id[]" class="form-control property_id {{$errorClass}}" id='property_id' multiple>
						                    @if(!empty($properties))
							                    @foreach($properties as $property)
							                        <option value="{{$property->id}}" <?php if(isset($selected_commission_properties)){ if(in_array($property->id,$selected_commission_properties)){ echo 'selected'; } } ?> class="others">{{$property->title}}</option>
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
											<label for="inputCommission" class="form-label">Commission (%)*</label>
											<?php  $errorClass =  !empty($errors->has('commission')) ? 'is-invalid':''; ?>   
							                {!! Form::text('percentage', null, array('id'=>'percentage','placeholder' => 'Enter commission (%)*', 'required'=>'required', 'min'=>'1', 'max'=>'100', 'onkeypress'=>'return onlyNumberKey(event)', 'class' => 'form-control '.$errorClass )) !!}
							                {!! !empty($errors->has('percentage')) ?'<div class="invalid-feedback"><span>'.$errors->first('percentage').'</span></div>' :'' !!}
										</div>
										<div class="col-md-6 mb-3">
						            <div class="form-group">
					                  <label for="discount_code" class="form-label">Valid From*</label>
					                  <input type="text" name="start_date" id="valid_from" data-parsley-required="true" class="form-control start_date" value="{{ isset($data) ? date('Y-m-d', strtotime($data->start_date)) : ''  }}" placeholder="From Date" readonly />
						            </div>
						        </div>
						        <div class="col-md-6 mb-3">
						            <div class="form-group">
						              <label for="discount_code" class="form-label">Valid Upto*</label>
						              <input type="text" name="end_date" id="valid_upto" data-parsley-required="true" class="form-control end_date" value="{{ isset($data) ? date('Y-m-d', strtotime($data->end_date)) : ''  }}" placeholder="To Date" readonly />
						            </div>
						        </div>
						
						      	<div class="col-md-12">
									    <div class="d-flex">
												<a href="{{ route('admin.commission.index') }}" class="btn btn-light">Cancel</a>
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
	function enable_cb() {
		var val = "{{ isset($data) && !empty($data->all_properties) ? $data->all_properties : '' }}";
		if (val == 'Yes') {
			$('.select_acco_div').css('display','none');
		} else {
			$('.select_acco_div').css('display','block');
		}
	}
	function enable_host() {
		var val_host = "{{ isset($data) && !empty($data->all_hosts) ? $data->all_hosts : '' }}";
		if (val_host == 'Yes') {
			$('.select_host_div').css('display','none');
			$("#host_id").prop('required',false);
		} else {
			$('.select_host_div').css('display','block');
			$("#host_id").prop('required',true);
		}
	}

	function getAccomodation() {
  	var host_id = $('#host_id').val();
  	
  	if (host_id) {
			$.ajax({
				url:'{{url("admin/commission/getAccomodation")}}/'+host_id,
				dataType: 'html',
				success:function(result)
				{
					$('.show_accomodation').html(result);
					$('.property_id_onload').select2();
				}
			});
		}
  }

	$(document).ready(function () {
		enable_cb();
		enable_host();
		$('#all_properties').on('click', function(){
			if($(this).is(':checked')) {
				$('.select_acco_div').css('display','none');
			}else{
				$('.select_acco_div').css('display','block');
			}
		});

		$('#all_hosts').on('click', function(){
			if($(this).is(':checked')) {
				$('.select_host_div').css('display','none');
				$("#host_id").prop('required',false);
			}else{
				$('.select_host_div').css('display','block');
				$("#host_id").prop('required',true);
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