@extends('layouts.web.master')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/css/select2.min.css">
<?php 
use App\Models\User;

$userData =  Session::get('AuthUserData') ?? null;
// dd($userData);
if(isset($userData) && !empty($userData)){
	$user_id = $userData->data->id;
	if(isset($user_id) && !empty($user_id)){
		$user_data1 = User::where('id',$user_id)->first();
	}
	$token = $userData->token;
}else{
	$user_data1 = '';
	$token = '';
}
// dd($user_data1->country_id);
?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          	<div class="col-md-6 grad-bg">
              <h3>Update Profile</h3>
          	</div>

			<div class="col-md-6">
				<div class="property-cont">
					<div class="property-list">
		
						<form method="POST" action="" class="booking-form" enctype="" id="edit_profile_form_host" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="user_id" value="{{ $user_data1->id }}">
							<div class="row">
								<div class="col-xl-6 mb-3">
									<div class="form-group select-country">
										<label>Full Name</label>
										<div  class="input-group">
											<select name="title" class="form-control title" id='title'>
												<!-- <option value="">-- Select Title--</option> -->
												<option value="Company" <?php if($user_data1->title == 'Company'){ echo 'selected'; } ?>>Company</option>
												<option value="Dr" <?php if($user_data1->title == 'Dr'){ echo 'selected'; } ?>>Dr</option>
												<option value="Family" <?php if($user_data1->title == 'Family'){ echo 'selected'; } ?>>Family</option>
												<option value="Mr_&_Mrs" <?php if($user_data1->title == 'Mr_&_Mrs'){ echo 'selected'; } ?>>Mr & Mrs</option>
												<option value="Mr" <?php if($user_data1->title == 'Mr'){ echo 'selected'; } ?>>Mr.</option>
												<option value="Mrs" <?php if($user_data1->title == 'Mrs'){ echo 'selected'; } ?>>Mrs.</option>
												<option value="Ms" <?php if($user_data1->title == 'Ms'){ echo 'selected'; } ?>>Ms.</option>
												<option value="PhD" <?php if($user_data1->title == 'PhD'){ echo 'selected'; } ?>>PhD</option>
												<option value="Prof" <?php if($user_data1->title == 'Prof'){ echo 'selected'; } ?>>Prof</option>
											</select>
											<input type="text" name="name" placeholder="Name" value="{{ $user_data->name }}" class="form-control" data-parsley-required="true">
										</div>
									</div>
								</div>
								<!-- <div class="col-xl-6 mb-3">
									<div class="form-group">
										<label>Surname</label>
										<input type="text" name="surname" placeholder="Surname" class="form-control" value="{{ $user_data1->surname }}" data-parsley-required="true">
									</div>
								</div> -->
								<div class="col-xl-6 mb-3">
									<div class="form-group">
										<label>Email Address</label>
										<input type="text" name="email" placeholder="Email" class="form-control" value="{{ $user_data1->email }}" data-parsley-required="true">
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group select-country">
										<label>Phone Number</label>
										<div class="input-group">
											<select name="country_code" class="form-control country_code select_img_country" id='country_code'>
												@if(!empty($country_phone))
													@foreach($country_phone as $key => $country_ph)
														<?php $selected1 = isset($user_data1->country_code) ? $user_data1->country_code : "+234" ; ?>
														<?php //$selected1 = "+234" ; ?>
														<option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php echo $selected1 == $country_ph->phonecode  ? 'selected' : '' ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
													@endforeach
												@else
												@endif
											</select>
											<input type="text" name="mobile" placeholder="Second Mobile" value="{{ isset($user_data1) && !empty($user_data1->mobile) ? $user_data1->mobile : '' }}" class="form-control">
										</div> 
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group">
										<label>Date of Birth</label>
										<!-- <input type="text" name="" placeholder="05-06-1993" class="form-control dob11"> -->
										<input type="text" name="dob" placeholder="Select DOB*" class="form-control profile_date11" value="{{ isset($user_data1->dob) && !empty($user_data1->dob) ? date('d-m-Y',strtotime($user_data1->dob)) : '' }}" data-parsley-required="true" readonly>
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group radio-inner-w host_profile_radio">
										<label>Gender</label>
										<div class="radio-main">
											<label class="custom_radio_b">
											<input type="radio" name="gender" value="Male" data-parsley-required="true" <?php if($user_data1->gender == 'Male'){ echo 'checked'; } ?>>
											<span class="checkmark"></span>Male
											</label>
											<label class="custom_radio_b">
											<input type="radio" name="gender" value="Female" data-parsley-required="true" <?php if($user_data1->gender == 'Female'){ echo 'checked'; } ?>>
											<span class="checkmark"></span> Female
											</label>
										</div>
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group radio-inner-w host_profile_radio">
										<label>Marital Status</label>
										<div class="radio-main">
											<label class="custom_radio_b">
											<input type="radio" name="marital_status" value="Married" data-parsley-required="true" <?php if($user_data1->marital_status == 'Married'){ echo 'checked'; } ?>>
											<span class="checkmark"></span>Married
											</label>
											<label class="custom_radio_b">
											<input type="radio" name="marital_status" value="Unmarried" data-parsley-required="true" <?php if($user_data1->marital_status == 'Unmarried'){ echo 'checked'; } ?>>
											<span class="checkmark"></span> Single
											</label>
										</div>
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<label for="inputDocumentImage" class="form-label">Profile Image</label>
									<div class="form-group input-group">
										@if(isset($user_data1->image) && !empty($user_data1->image))
											<div id="document_image_view_div" class="image_preview_div"><img id="previewing" src="{{ $user_data1->image }}" width="30" height="30"></div>
										@else
											<div id="document_image_view_div" class="image_preview_div"><img id="previewing" src="{{ URL::asset('assets/images/image.png')}}" width="30" height="30"></div>
										@endif
										<div class="form-control" onclick="document.getElementById('image').click()">
											<label for="files">Select Profile Image</label>
											<input type="file" id="image" name="image" style="visibility:hidden;" class="form-control">
										</div>
									</div>
								</div>

								<div class="col-lg-12 mb-3">
									<div class="form-group">
										<label>Remarks</label>
										<textarea name="remarks" id="remarks" cols="30" rows="4" placeholder="Remarks" class="form-control">{{ $user_data1->remarks }}</textarea>
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group">
										<label>Secondary Email</label>
										<input type="text" name="secondary_email" placeholder="Email" value="{{ $user_data1->secondary_email }}" class="form-control">
									</div>
								</div>
								<div class="col-xl-6 mb-3">
									<div class="form-group select-country">
										<label>2nd Phone</label>
										<div class="input-group">
											<select name="second_country_code" class="form-control second_country_code select_img_country" id='second_country_code'>
												@if(!empty($country_phone))
													@foreach($country_phone as $key1 => $country_ph1)
														<?php $selected1 = "+234" ; ?>
														<option data-src="{{url('public/images/country_image/'.$country_ph1->sortname.'.png')}}" value="{{$country_ph1->phonecode}}" <?php echo $selected1 == $country_ph1->phonecode  ? 'selected' : '' ?>>{{$country_ph1->sortname}} +{{$country_ph1->phonecode}}</option>
													@endforeach
												@else
												@endif
											</select>
											<input type="text" name="second_mobile" placeholder="Second Mobile" value="{{ $user_data1->second_mobile }}" class="form-control">
										</div> 
									</div>
								</div>
								
								<div class="col-12">
									<div class="margin-cls border border-1 p-2 rounded row">
										<h4 class="dataLabel">ADDRESS</h4>
										<div class="col-xl-4 mb-3">
											<div class="form-group">
												<label for="inputName" class="form-label">Country</label>
												<select name="country_id" class="form-control country_id" /*onchange="getProvince()"*/ id='country_id'  data-parsley-required="true">
													@if(!empty($country))
														<option value="">Select Country</option>
														@foreach($country as $key => $country1)
															<option value="{{$country1->id}}" <?php if($user_data1->country_id == $country1->id){ echo 'selected	'; } ?> >{{$country1->name}}</option>
														@endforeach
													@else
													@endif
												</select>
											</div>
										</div>
										<div class="col-xl-4 mb-3 show_provinceDiv">
											<label for="inputName" class="form-label">Province</label>
											<select name="province_id" class="form-control province_id1" id='province_id1'  data-parsley-required="true">
												<option value="">Select Province</option>
											</select>
										</div>

										<div class="col-xl-4 mb-3 show_cityDiv">
											<label for="inputName" class="form-label">City</label>
											<select name="city_id" class="form-control city_id" id='city_id'  data-parsley-required="true">
												<option value="">Select City</option>
											</select>
										</div>
										
										<div class="col-xl-4 mb-3">
											<div class="form-group">
												<label for="inputStreet" class="form-label">Street </label>
												<input type="text" name="street" placeholder="Street" class="form-control" value="{{ $user_data1->street }}">
											</div>
										</div>
										<div class="col-xl-4 mb-3">
											<div class="form-group">
												<label for="inputStreetNumber" class="form-label">Street Number</label>
												<input type="text" name="street_number" placeholder="Street Number" class="form-control" value="{{ $user_data1->street_number }}">
											</div>
										</div>
										<div class="col-xl-4 mb-3">
											<div class="form-group">
												<label for="inputNumber" class="form-label">Number </label>
												<input type="text" name="number" placeholder="Number" class="form-control" value="{{ $user_data1->number }}">
											</div>
										</div>
										<div class="col-xl-4 mb-3">
											<div class="form-group">
												<label for="inputPostalCode" class="form-label">Postal Code</label>
												<input type="text" name="postal_code" placeholder="Postal Code" class="form-control" value="{{ $user_data1->postal_code }}">
											</div>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="margin-cls border border-3 p-4 rounded row">
										<h4 class="dataLabel">DOCUMENTATION</h4>
										<div class="col-xl-6 mb-3">
											<label for="inputDocumentnumber" class="form-label">Document number </label>
											<input type="text" name="document_number" placeholder="Document Number"  class="form-control"  value="{{ $user_data1->document_number }}">
										</div>
										<div class="col-xl-6 mb-3">
											<label for="inputDocumentImage" class="form-label">Document Image</label>
											<div class="form-group input-group">
												@if(isset($user_data1->image) && !empty($user_data1->image))
													<div id="document_image_view_div" class="image_preview_div"><img id="document_previewing" src="{{ $user_data1->document_image }}"></div>
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
								</div>

								<div class="col-12">
									<div class="card margin-cls">
										<div class="card-body">
											<h4 class="dataLabel">BANK DATA</h4>
											<div class="row">
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Method of Payment </label>
													<select id="method_of_payment" name="method_of_payment" class="form-control">
														<option value="Bank_transfer" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'Bank_transfer' ? 'selected' : ''; ?>>Bank transfer</option>
														<option value="Cash" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'Cash' ? 'selected' : ''; ?>>Cash</option>
														<option value="Cheque" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'Cheque' ? 'selected' : ''; ?>>Cheque/IOU</option>
														<option value="Credit_Card_Hold" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'Credit_Card_Hold' ? 'selected' : ''; ?>>Credit Card Hold</option>
														<option value="Credit_Card_number_as_a_guaran" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'Credit_Card_number_as_a_guaran' ? 'selected' : ''; ?>>Credit Card number as a guaran</option>
														<option value="iDeal" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'iDeal' ? 'selected' : ''; ?>>iDeal</option>
														<option value="PayPal" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'PayPal' ? 'selected' : ''; ?>>PayPal</option>
														<option value="POS_Credit_card" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'POS_Credit_card' ? 'selected' : ''; ?>>POS/ Credit card</option>
														<option value="SOFORT" <?php echo !empty($bank_data) && $bank_data->method_of_payment == 'SOFORT' ? 'selected' : ''; ?>>SOFORT</option>
													</select>
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Account holder</label>
													<div class="ck-box">
														<input type="checkbox" name="account_holder" id="account_holder" class="checkbox" value="1" <?php echo !empty($bank_data) && $bank_data->account_holder == 1 ? 'checked' : ''; ?>> Use the name of the owner
													</div>
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Name of account holder</label>
													<input type="text" name="account_holder_name" id="account_holder_name" class="form-control txtOnly" value="<?php echo !empty($bank_data) && !empty($bank_data->account_holder_name) ? $bank_data->account_holder_name : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Account number</label>
													<input type="text" name="account_number" id="account_number" class="form-control" minlength="10" maxlength="16" onkeypress="return onlyNumberKey(event)" value="<?php echo isset($bank_data) && !empty($bank_data->account_number) ? $bank_data->account_number : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">IBAN</label>
													<input type="text" name="iban" id="iban" class="form-control" min="1" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->iban) ? $bank_data->iban : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Intra Community VAT number</label>
													<input type="text" name="vat_number" id="vat_number" class="form-control" min="1" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->vat_number) ? $bank_data->vat_number : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Fiscal code</label>
													<input type="text" name="fiscal_code" id="fiscal_code" class="form-control" min="1" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->fiscal_code) ? $bank_data->fiscal_code : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Route Nº ABA (USA)</label>
													<input type="text" name="route" id="route" class="form-control" value="<?php echo !empty($bank_data) && !empty($bank_data->route) ? $bank_data->route : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Invoicing type</label>
													<select id="invoicing_type" name="invoicing_type" class="form-control">
														<option value="Client" <?php echo !empty($bank_data) && $bank_data->invoicing_type == 'Client' ? 'selected' : ''; ?>>Client</option>
														<option value="Self-employed" <?php echo !empty($bank_data) && $bank_data->invoicing_type == 'Self-employed' ? 'selected' : ''; ?>>Self-employed</option>
														<option value="Company" <?php echo !empty($bank_data) && $bank_data->invoicing_type == 'Company' ? 'selected' : ''; ?>>Company</option>
													</select>
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Retention (%)</label>
													<input type="text" name="retention" id="retention" class="form-control" min="1" max="100" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->retention) ? $bank_data->retention : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Ledger Account</label>
													<input type="text" name="ledger_account" id="ledger_account" class="form-control" value="<?php echo !empty($bank_data) && !empty($bank_data->ledger_account) ? $bank_data->ledger_account : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">BIC Swift</label>
													<input type="text" name="bic_swift" id="bic_swift" class="form-control" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->bic_swift) ? $bank_data->bic_swift : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Tax (%)</label>
													<input type="text" name="tax" id="tax" class="form-control" min="1" max="100" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->tax) ? $bank_data->tax : ''; ?>">
												</div>
												<div class="col-xl-4 mb-3">
													<label for="inputMethododPayment" class="form-label">Bank Name</label>
													<input type="text" name="bank_name" id="bank_name" class="form-control" value="<?php echo !empty($bank_data) && !empty($bank_data->bank_name) ? $bank_data->bank_name : ''; ?>">
												</div>
												<div class="col-xl-4 mb-4">
													<label for="inputMethododPayment" class="form-label">CNAE Code</label>
													<input type="text" name="cnae_code" id="cnae_code" class="form-control" min="1" onkeypress="return onlyNumberKey(event)" value="<?php echo !empty($bank_data) && !empty($bank_data->cnae_code) ? $bank_data->cnae_code : ''; ?>">
												</div>
											</div>
										</div>
									</div>
								</div><br>

								<div class="col-xl-12 col-lg-12 mb-3">
									<div class="form-group">
										<label>Above information are correct or not? *</label>
										<div  class="input-group">
											<select name="information_correct_or_not" class="form-control title" id='information_correct_or_not' data-parsley-required="true">
												<option value="">Above information are correct or not?</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-12 mt-3">
									<div class="profile-save d-flex">
										<input type="submit" name="" class="btn primary_btn me-3" value="Finish">
									</div>
								</div>
							</div>
						</form>						
					</div>
					<!-- <div class="property-footer">
						<div class="btn_group">
						<a href="{{ route('web.become_a_host.host_description') }}" class="btn secondary_btn">Back</a>
						<a href="javascript:void(0)" class="btn primary_btn service_next">Next</a>
						</div>
					</div> -->
				</div>
			</div>
        </div>
      </div>
    </section>  
  </main>

<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/parsley.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/js/select2.min.js"></script>

  <script>
	$(document).ready(function () {
		var now = new Date();
		$(".profile_date11").datepicker({
			endDate:new Date(now.setFullYear(now.getFullYear() - 5)),
			format: "dd-mm-yyyy",
			autoclose: true,
		});
	});
    /*Add card Start */
	$('#edit_profile_form_host').parsley();
	$("#edit_profile_form_host").on('submit', async function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		// alert($.type(formData));
		$('.preload').show();
		var result = await ajaxFunction('update_profile_host', formData);
		$('.preload').hide();
		if (result.status == true) {
			if (result.status === true) {
				// toastr.success(result.message);
				sessionStorage.setItem("shortlet_session", '');
				// $.cookie("shortlet_session", null);
				sessionStorage.clear();
				// Swal.fire(
				// 	'The Internet?',
				// 	result.message,
				// 	'success'
				// )
				console.log(result.message);
				Swal.fire({
					// position: 'top-end',
					icon: 'success',
					// title: result.message,
					// showConfirmButton: false,
					// timer: 2500
					title: "Thank You",
					text: result.message,
					// type: "success"
				}).then(function() {
					window.location.replace("{{ route('admin.login') }}");
				});
				// setInterval(function () {
				// 	window.location.replace("{{ route('admin.login') }}");
				// 	// window.location.reload();
				// }, 2000); 
			} else {
				toastr.error(result.message);
			}
		} else {
			if (typeof result.message == 'object') {
				var errors = result.message;
				console.log(errors);
				$.each(errors, function(i, error) {
					$("." + i).after("<div class='errors'>" + error + "</div>");
				});
			} else {
				toastr.error(result.message);
				alertmessage('update_profile_host', result.message, 'warning');
			}
		}
		$('#vefify_otp').trigger("reset");
		return false;
	});

	function ajaxFunction(method, formdata, header) {
		$('div.errors').remove();
		return new Promise((resolve, reject) => {
		//  var currency = "{{ Session::get('currentCurrencyPaygen') ?? '' }}";
			var  url = '{{url("api/auth/")}}/' + method;
			//   var url = 'http://13.59.199.42/api/auth/'+method;
			var settings = {
				"url": url,
				"method": "POST",
				"timeout": 0,
				"headers": {
					"Accept": "application/json",
					"Authorization": "Bearer {{$token}}",
				},
				"processData": false,
				"mimeType": "multipart/form-data",
				"contentType": false,
				"data": formdata,
				error: function(error) {
					console.log(error, '----------------------');
				}
			};
			$.ajax(settings).done(function(response) {
				resolve(JSON.parse(response));
			});
		});
	}
  	/*Add card End*/


	$(document).ready(function () {
		getProvince();
		$("#file-upload").change(function(){
	      var fileObj = this.files[0];
	      var imageFileType = fileObj.type;
	      var imageSize = fileObj.size;

	      var file = $('#file-upload')[0].files[0].name;
	      $(this).prev('label').text(file);
	    
	      var match = ["image/jpeg","image/png","image/jpg"];
	      if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
	        $('#previewing').attr('src','images/image.png');
	        toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
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
	      $("#file-upload").css("color","green");
	      $('#previewing').attr('src',e.target.result);
	    }

		////Upload Document Image
		$("#document_image").change(function(){
			var fileObj = this.files[0];
			var imageFileType = fileObj.type;
			var imageSize = fileObj.size;

			var file = $('#document_image')[0].files[0].name;
			$(this).prev('label').text(file);

			var match = ["image/jpeg","image/png","image/jpg"];
			if(!((imageFileType == match[0]) || (imageFileType == match[1]) || (imageFileType == match[2]))){
				$('#document_previewing').attr('src','images/image.png');
				toastr.error('Please Select A valid Image File <br> Note: Only jpeg, jpg and png Images Type Allowed!!');
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
        var province_id = "<?php if (isset($data) && $data->data->province_id) { echo $data->data->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_provinceDiv').html(result);
	            }
	        });
        }
	})

	// $(document).on('change', '.province_id1',function(){
    //     var country_id = $('#country_id').val();
    //     var province_id = $('#province_id1').find(":selected").val();
    //     var city_id = "<?php if (isset($data) && $data->data->city_id) { echo $data->data->city_id; } ?>";
    //     if (province_id) {
	//         $.ajax({
	//             url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	//             dataType: 'html',
	//             success:function(result)
	//             {
	//                 $('.show_cityDiv').html(result);
	// 				// getArea();
	//             }
	//         });
    //     }
	// })

	$(document).on('change', '.city_id',function(){
		var country_id = $('#country_id').val();
		var province_id = $('#province_id').val();
		var city_id = $('#city_id').val();
		var area_id = "";
		if (city_id) {
			$.ajax({
				url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
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
        var province_id = "<?php if (isset($user_data1) && $user_data1->province_id) { echo $user_data1->province_id; } ?>";

        if (country_id) {
	        $.ajax({
	            url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
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
        var province_id = $('#province_id1').val();
        var city_id = "<?php if (isset($user_data1) && $user_data1->city_id) { echo $user_data1->city_id; } ?>";
		// alert(city_id);
        if (province_id) {
	        $.ajax({
	            url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	            dataType: 'html',
	            success:function(result)
	            {
	                $('.show_cityDiv').html(result);
					// getArea();
	            }
	        });
        }
    }
  </script>
@endsection