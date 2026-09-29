@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
@endsection
<div class="row">
   	<div class="col-lg-12">

	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
       	<div class="form-body">
		    <div class="card margin-cls">
					<div class="card-body mt-2">
           			<div class="row">
						<div class="col-md-6 mb-3">
							<label for="inputName" class="form-label">Notification For*</label>
                            <?php  $errorClass =  !empty($errors->has('notification_for')) ? 'is-invalid':''; ?>   
                            <select name="notification_for" id="notification_for" data-parsley-required="true" class="form-control notification_for {{$errorClass}}" data-placeholder="Is super" data-dropdown-css-class="select2-primary">
                                <option value="">--Select--</option>
                                <option value="All" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'All')  ? 'selected' : '' ?>>All</option>
                                <option value="Sub-Admin" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Sub-Admin')  ? 'selected' : '' ?>>Sub-Admin</option>
                                <option value="Influencer" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Influencer')  ? 'selected' : '' ?>>Partner</option>
                                <option value="Customer" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Customer')  ? 'selected' : '' ?>>Customer</option>
                                <option value="Host" <?php echo isset($data->notification_for) &&  ($data->notification_for == 'Host')  ? 'selected' : '' ?>>Owner</option>
                            </select>
                            {!! !empty($errors->has('notification_for')) ?'<div class="invalid-feedback"><span>'.$errors->first('notification_for').'</span></div>' :'' !!}
						</div>

						<div class="subadmin_main_div" style="display:none;">
							<div class="row">
								<div class="col-md-6 mb-3 properties-cls mt-3">
									<input type="checkbox" name="all_subadmin_users" id="all_subadmin_users" value="Yes">
									<label for="all_subadmin_users" class="form-label">All Sub-Admins</label>
								</div>
								<div class="col-md-6 mb-3 subadmin_users_div">
									<div class="form-group">
										<label for="inputName" class="form-label">Select Sub-Admins</label>
										<select name="subadmin_users[]" class="form-control subadmin_users select2" id='subadmin_users' multiple>
											<!-- <option value="">Select Users</option> -->
											@if(isset($subadmin_users) && count($subadmin_users) > 0)
											@foreach($subadmin_users as $subadmin)
												<option value="{{$subadmin->id}}" >{{$subadmin->name.' '.$subadmin->surname.' ('.$subadmin->email.')'}}</option>	
											@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="influencer_main_div" style="display:none;">
							<div class="row">
								<div class="col-md-6 mb-3 properties-cls mt-3">
									<input type="checkbox" name="all_influencer_users" id="all_influencer_users" value="Yes">
									<label for="all_influencer_users" class="form-label">All Partners</label>
								</div>
								<div class="col-md-6 mb-3 influencer_users_div">
									<div class="form-group">
										<label for="inputName" class="form-label">Select Partners</label>
										<select name="influencer_users[]" class="form-control influencer_users select2" id='influencer_users' multiple>
											<!-- <option value="">Select Users</option> -->
											@if(isset($influencer_partner_users) && count($influencer_partner_users) > 0)
											@foreach($influencer_partner_users as $influencer)
												<option value="{{$influencer->id}}" >{{$influencer->name.' '.$influencer->surname.' ('.$influencer->email.')'}}</option>	
											@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="customer_main_div" style="display:none;">
							<div class="row">
								<div class="col-md-6 mb-3 properties-cls mt-3">
									<input type="checkbox" name="all_customer_users" id="all_customer_users" value="Yes">
									<label for="all_customer_users" class="form-label">All Customers</label>
								</div>
								<div class="col-md-6 mb-3 customer_users_div">
									<div class="form-group">
										<label for="inputName" class="form-label">Select Customers</label>
										<select name="customer_users[]" class="form-control customer_users select2" id='customer_users' multiple>
											<!-- <option value="">Select Users</option> -->
											@if(isset($customer_users) && count($customer_users) > 0)
											@foreach($customer_users as $customer)
												<option value="{{$customer->id}}" >{{$customer->name.' '.$customer->surname.' ('.$customer->email.')'}}</option>	
											@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="host_main_div" style="display:none;">
							<div class="row">
								<div class="col-md-6 mb-3 properties-cls mt-3">
									<input type="checkbox" name="all_host_users" id="all_host_users" value="Yes">
									<label for="all_host_users" class="form-label">All Owners</label>
								</div>
								<div class="col-md-6 mb-3 host_users_div">
									<div class="form-group">
										<label for="inputName" class="form-label">Select Owners</label>
										<select name="host_users[]" class="form-control host_users select2" id='host_users' multiple>
											<!-- <option value="">Select Users</option> -->
											@if(isset($host_owner_users) && count($host_owner_users) > 0)
											@foreach($host_owner_users as $host)
												<option value="{{$host->id}}" >{{$host->name.' '.$host->surname.' ('.$host->email.')'}}</option>	
											@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
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
		            <div class="pt-2 pb-md-3">
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
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
	$('.select2').select2({
		// minimumResultsForSearch: -1,
		placeholder: function(){
			$(this).data('placeholder');
		}
	});

	$('.notification_for').on('change', function(){
		if($(this).val() == 'Sub-Admin') {
			$('.subadmin_main_div').css('display','block');
			$('.influencer_main_div').css('display','none');
			$('.customer_main_div').css('display','none');
			$('.host_main_div').css('display','none');
		}else if($(this).val() == 'Influencer') {
			$('.influencer_main_div').css('display','block');
			$('.subadmin_main_div').css('display','none');
			$('.customer_main_div').css('display','none');
			$('.host_main_div').css('display','none');
		}else if($(this).val() == 'Customer') {
			$('.customer_main_div').css('display','block');
			$('.influencer_main_div').css('display','none');
			$('.subadmin_main_div').css('display','none');
			$('.host_main_div').css('display','none');
		}else if($(this).val() == 'Host') {
			$('.host_main_div').css('display','block');
			$('.influencer_main_div').css('display','none');
			$('.customer_main_div').css('display','none');
			$('.subadmin_main_div').css('display','none');
		}else{
			$('.subadmin_main_div').css('display','none');
			$('.influencer_main_div').css('display','none');
			$('.customer_main_div').css('display','none');
			$('.host_main_div').css('display','none');
		}
	});

	$('#all_subadmin_users').on('click', function(){
		if($(this).is(':checked')) {
			$('.subadmin_users_div').css('display','none');
			$("#subadmin_users").prop('required',false);
		}else{
			$('.subadmin_users_div').css('display','block');
			$("#subadmin_users").prop('required',true);
		}
	});

	$('#all_influencer_users').on('click', function(){
		if($(this).is(':checked')) {
			$('.influencer_users_div').css('display','none');
			$("#influencer_users").prop('required',false);
		}else{
			$('.influencer_users_div').css('display','block');
			$("#influencer_users").prop('required',true);
		}
	});

	$('#all_customer_users').on('click', function(){
		if($(this).is(':checked')) {
			$('.customer_users_div').css('display','none');
			$("#customer_users").prop('required',false);
		}else{
			$('.customer_users_div').css('display','block');
			$("#customer_users").prop('required',true);
		}
	});

	$('#all_host_users').on('click', function(){
		if($(this).is(':checked')) {
			$('.host_users_div').css('display','none');
			$("#host_users").prop('required',false);
		}else{
			$('.host_users_div').css('display','block');
			$("#host_users").prop('required',true);
		}
	});
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