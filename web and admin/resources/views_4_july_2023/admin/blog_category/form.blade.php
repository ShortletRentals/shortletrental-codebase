@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
<div class="row">
  	<div class="col-12">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
		<div class="form-body">
			<div class="card margin-cls">
			   	<h4 class="dataLabel">Blog Category DETAILS</h4>
			   	<div class="card-body">
		           	<div class="row">
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Name*</label>
							<div class="input-group">
								<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>
								{!! Form::text('name', null, array('id'=>'name','placeholder' => 'Name*', 'required'=>'required', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!}
								{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
				            </div> 
				        </div>
		            </div>
		        </div>
	        </div>
			
			<div class="pb-2">
				<div class="d-flex">
					<a href="{{ route('admin.blog_category.index') }}" class="btn btn-light">Cancel</a>
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
</script>
<script>
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