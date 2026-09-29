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
							<label for="inputName" class="form-label">Short Name*</label>
							<?php  $errorClass =  !empty($errors->has('sortname')) ? 'is-invalid':''; ?>   
			                {!! Form::text('sortname', null, array('id'=>'sortname','placeholder' => 'Enter Short Name*', 'required'=>'required', 'data-parsley-minlength'=>"2", 'data-parsley-maxlength'=>"4", 'maxlength'=>"4", 'class' => 'form-control txtOnly '.$errorClass )) !!}
			                {!! !empty($errors->has('sortname')) ?'<div class="invalid-feedback"><span>'.$errors->first('sortname').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Country Name*</label>
							<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
			                {!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Country Name*', 'required'=>'required','data-parsley-pattern'=>'^[a-zA-Z ]+$', 'data-parsley-pattern-message'=>'Name allow only character', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!}
			                {!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Country Code*</label>
							<?php  $errorClass =  !empty($errors->has('phonecode')) ? 'is-invalid':''; ?>   
			                {!! Form::text('phonecode', null, array('id'=>'phonecode','placeholder' => 'Enter Phone Code*','maxlength'=>'4', 'minlength'=>'1', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
			                <!-- {!! Form::text('phonecode', null, array('id'=>'phonecode','placeholder' => 'Enter Phone Code*','onkeypress'=>"return onlyNumberKey(event)",'maxlength'=>'3', 'minlength'=>'1', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!} -->
			                {!! !empty($errors->has('phonecode')) ?'<div class="invalid-feedback"><span>'.$errors->first('phonecode').'</span></div>' :'' !!}
						</div>
						<!-- <div class="col-md-6 mb-3">
							<label for="inputMobile" class="form-label">Profile Image</label>
	                        <div class="form-group input-group">
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
	                    </div> -->
			            <div class="col-md-12">
						    <div class="d-flex">
								<a href="{{ route('admin.country.index') }}" class="btn btn-light">Cancel</a>
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
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script>
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

	$(document).ready(function () {
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