@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
@endsection
<div class="row">
  	<div class="col-12">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
		<div class="form-body">
			<div class="card margin-cls">
			   	<h4 class="dataLabel">Blog DETAILS</h4>
			   	<div class="card-body">
		           	<div class="row">
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Blog Category*</label>
							<?php  $errorClass =  !empty($errors->has('blog_category')) ? 'is-invalid':''; ?>
			                <select name="blog_category" class="form-control blog_category {{$errorClass}}" required id='blog_category'>
			                    @if(!empty($category))
				                    <option value="">Select Blog Category</option>
				                    @foreach($category as $key => $category_val)
				                    	<?php $selected = isset($data->blog_category) ? $data->blog_category : "";?>
				                        <option value="{{$category_val->id}}" <?php echo $selected == $category_val->id  ? 'selected' : '' ?>>{{$category_val->name}}</option>
				                    @endforeach
			                    @else
			                    @endif
			                </select>
			                @error('blog_category')
			                    <span class="invalid-feedback" role="alert">
			                    <label>{{ $message }}</label>
			                    </span>
			                @enderror
			                {!! !empty($errors->has('blog_category')) ?'<div class="invalid-feedback"><span>'.$errors->first('blog_category').'</span></div>' :'' !!}
						</div>
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Title*</label>
							<div class="input-group">
								<?php  $errorClass = !empty($errors->has('title')) ? 'is-invalid':''; ?>
								{!! Form::text('title', null, array('id'=>'title','placeholder' => 'Name*', 'required'=>'required', 'data-parsley-required'=>'true', 'class' => 'form-control '.$errorClass )) !!}
								{!! !empty($errors->has('title')) ?'<div class="invalid-feedback"><span>'.$errors->first('title').'</span></div>' :'' !!}
				            </div> 
				        </div>
						<div class="col-md-6 mb-3">
							<label for="inputMobile" class="form-label">Image</label>
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
						<div class="col-md-12 mb-3">
							<label for="inputRemarks" class="form-label">Description</label>
							<?php $errorClass =  !empty($errors->has('description')) ? 'is-invalid':''; ?>
							<textarea class="form-control ckeditor" name="description" id="Description" rows="3"><?php echo $data->description ?? '' ?></textarea>
			                {!! !empty($errors->has('remarks')) ?'<div class="invalid-feedback"><span>'.$errors->first('remarks').'</span></div>' :'' !!}
						</div>
		            </div>
		        </div>
	        </div>
			
			<div class="pb-2">
					<div class="d-flex">
						<a href="{{ route('admin.blog.index') }}" class="btn btn-light">Cancel</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	$(document).ready(function(){
		CKEDITOR.replace('Description',{

		});
	});
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