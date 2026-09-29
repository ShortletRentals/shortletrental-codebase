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
					   <div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Type*</label>
							<?php  $errorClass =  !empty($errors->has('type')) ? 'is-invalid':''; ?>
							<select name="type" id="type" data-parsley-required="true" class="form-control {{$errorClass}}" placeholder="Select Type" data-dropdown-css-class="select2-primary">
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
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Name*</label>
							<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
			                <!-- {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Name', 'required'=>'required','data-parsley-pattern'=>'^[a-zA-Z ]+$', 'data-parsley-pattern-message'=>'Name allow only character', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!} -->
			                {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Name*', 'required'=>'required', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputMobile" class="form-label">Image</label>
	                        <div class="input-group">
	                        	@if(isset($data) && $data->image)
                                    <div id="image_preview"><img id="previewing" src="{{ $data->image }}"></div>
                                @else
	                            	<div id="image_preview"><img id="previewing" src="{{ URL::asset('assets/images/image.png')}}"></div>
	                            @endif

	                            <!-- <input type="file" id="file" name="image" class="form-control"> -->
	                            <!-- <label for="files" >{{__('backend.Select_Image')}}</label>
	                            <input type="file" id="file" name="image" style="visibility:hidden;" class="form-control"> -->
	                            <div class="form-control" onclick="document.getElementById('file').click()">
	                                <label for="files">Select Image</label>
	                                <input type="file" id="file" name="image" style="visibility:hidden;" class="form-control">
	                            </div>
	                         </div>
	                    </div>
			            <div class="col-md-12">
						    <div class="d-flex">
	                           <a href="{{ route('admin.category.index') }}" class="btn btn-light">Cancel</a>
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
	   </div><!--end row-->
	</div>
</div>
</div>
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script>
	$(document).ready(function () {
		$("#file").change(function(){
	      var fileObj = this.files[0];
	      var imageFileType = fileObj.type;
	      var imageSize = fileObj.size;

	      var file = $('#file')[0].files[0].name;
	      $(this).prev('label').text(file);
		  
	      var match = ["image/jpeg","image/png","image/jpg","image/svg+xml"];
	      if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]) || (imageFileType == match[3]) )){
	        $('#previewing').attr('src','images/image.png');
	        toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg, png and svg Images Type Allowed!!');
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