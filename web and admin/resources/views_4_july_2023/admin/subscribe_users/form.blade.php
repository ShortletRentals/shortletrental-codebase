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
		           	<div class="border border-3 p-4 rounded row">
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Notification For*</label>
                            <?php  $errorClass =  !empty($errors->has('notification_for')) ? 'is-invalid':''; ?>   
                            <select name="notification_for" id="notification_for" data-parsley-required="true" class="form-control notification_for {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select--</option>
                                <option value="All" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'All')  ? 'selected' : '' ?>>All</option>
                                <option value="Sub-Admin" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Sub-Admin')  ? 'selected' : '' ?>>Sub-Admin</option>
                                <option value="Influencer" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Influencer')  ? 'selected' : '' ?>>Influencer</option>
                                <option value="Customer" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Customer')  ? 'selected' : '' ?>>Customer</option>
                                <option value="Host" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Host')  ? 'selected' : '' ?>>Host</option>
                            </select>
                            {!! !empty($errors->has('notification_for')) ?'<div class="invalid-feedback"><span>'.$errors->first('notification_for').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Title*</label>
							<?php  $errorClass =  !empty($errors->has('title')) ? 'is-invalid':''; ?>   
			                {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Enter Title*', 'required'=>'required', 'class' => 'form-control txtOnly '.$errorClass )) !!}
			                {!! !empty($errors->has('title')) ?'<div class="invalid-feedback"><span>'.$errors->first('title').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Message</label>
							<textarea class="form-control" name="message" id="inputProductDescription" rows="3"><?php echo $data->description ?? '' ?></textarea>
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
	                           <a href="{{ route('admin.notification.index') }}" class="btn btn-light">Cancel</a>
	                           <button type="submit" class="btn btn-light ms-auto">Send</button>
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