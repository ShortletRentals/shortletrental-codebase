@extends('layouts.web.master')

@section('content')
<?php ///Page 3 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>More information about the accommodation</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                  <form class="booking-form">
                    <div class="row">
                      <!-- <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Building/Urbanization*</label>
                        <select name="building" id="building" data-parsley-required="true" class="form-control building" data-placeholder="Select Building/Urbanization" data-dropdown-css-class="select2-primary">
                          <option value="">--Select Building/Urbanization--</option>
                          @if(count($buildings) > 0)
                          @foreach($buildings as $building)
                            <option value="{{$building->id}}" >{{ $building->name }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <div class="col-md-3 col-xl-2 col-xxl-2 mb-3">
                        <label for=""></label>
                        <a href="javascript:void(0)" class="btn primary_btn add_new" onclick="newBuildingModal(this)" > Add New</a>
                      </div> -->
                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Select Max Guest*</label>
                        <select name="max_guest" id="max_guest" data-parsley-required="true" class="form-control max_guest" data-placeholder="Max Guest" data-dropdown-css-class="select2-primary">
                          <option value="">--Select Max guest--</option>
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                          <option value="6" >6</option>
                          <option value="7" >7</option>
                          <option value="8" >8</option>
                          <option value="9" >9</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="inputNamePets" class="form-label">Pets Allow</label>
                        <select name="pets_allow" id="pets_allow" class="form-control pets_allow" data-placeholder="Pets Allow" data-dropdown-css-class="select2-primary">
                          <option value="">--Pets Allow--</option>
                          <option value="Yes" selected>Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="inputNamePets" class="form-label">Minimum number of nights</label>
                        <select name="minimum_no_of_nights" id="minimum_no_of_nights" class="form-control minimum_no_of_nights" data-placeholder="Minimum number of nights" data-dropdown-css-class="select2-primary">
                          <option value="">--Minimum number of nights--</option>
                          <option value="1" selected>1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Select CCTV*</label>
                        <select name="cctv" id="cctv" data-parsley-required="true" class="form-control cctv" data-placeholder="Select CCTV" data-dropdown-css-class="select2-primary">
                          <!-- <option value="">--Select CCTV--</option> -->
                          <option value="Yes" >Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3 cctv_locations_div">
                        <label for="inputPostalCode" class="form-label">Indiate location of cameras</label>
                        <input name="cctv_locations" class="form-control cctv_locations" id="cctv_locations" type="text" placeholder="Indiate location of cameras">
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Wi-Fi Username</label>
                        <input name="wifi_username" class="form-control wifi_username" id="wifi_username" type="text" placeholder="Enter Wi-Fi Username">
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Wi-Fi-Password</label>
                        <input name="wifi_password" class="form-control wifi_password" id="wifi_password" type="text" placeholder="Enter Wi-Fi Password">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Select Televisions</label>
                        <select name="no_of_television" id="no_of_television" data-parsley-required="true" class="form-control no_of_television" data-placeholder="Televisions" data-dropdown-css-class="select2-primary">
                          <option value="">--Select Televisions--</option>
                          <option value="1" >1</option>
                          <option value="2" >2</option>
                          <option value="3" >3</option>
                          <option value="4" >4</option>
                          <option value="5" >5</option>
                          <option value="6" >6</option>
                          <option value="7" >7</option>
                          <option value="8" >8</option>
                          <option value="9" >9</option>
                          <option value="10">10</option>
                        </select>
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Location of Television</label>
                        <input name="location_of_television" class="form-control location_of_television" id="location_of_television" type="text" placeholder="Enter Location of Television">
                      </div>
                      <!-- <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Does your apartment allow a day booking?</label>
                        <select name="allow_a_day_booking" id="allow_a_day_booking" class="form-control allow_a_day_booking" data-placeholder="Does your apartment allow a day booking?" data-dropdown-css-class="select2-primary">
                          <option value="Yes" >Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div> -->
                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">House rules</label>
                        <input name="house_rule" class="form-control house_rule" id="house_rule" type="text" placeholder="House Rule">
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">What is your response time when a guest has a complaint with your property?</label>
                        <input name="response_time" class="form-control response_time" id="response_time" type="text" placeholder="Response time">
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">What is your responsibility for this apartment you intend to list on the platform?</label>
                        <select name="apartment_responsible" id="apartment_responsible" class="form-control apartment_responsible" data-placeholder="Choose One" data-dropdown-css-class="select2-primary">
                          <option value="">--Select--</option>
                          <option value="Owner" >Owner</option>
                          <option value="Facility_manager" >Facility manager</option>
                          <option value="Agent" >Agent</option>
                        </select>
                      </div>
                      <div class="col-md-6 col-lg-6 mb-3 other_apartment_responsible" style="display: none;">
                        <label for="inputPostalCode" class="form-label">If these titles do not best explain your roles, Kindly explain your responsibilities</label>
                        <input name="other_responsibility" class="form-control other_responsibility" id="other_responsibility" type="text" placeholder="Explain your responsibilities">
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Is your property located within an estate?</label>
                        <select name="estate_located" id="estate_located" class="form-control estate_located" data-placeholder="Choose One" data-dropdown-css-class="select2-primary">
                          <option value="">--Select--</option>
                          <option value="Yes" >Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3 estateFields" style="display: none;">
                        <label for="inputPostalCode" class="form-label">Estate Name</label>
                        <input name="estate_name" class="form-control estate_name" id="estate_name" type="text" placeholder="Estate Name">
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3 estateFields" style="display: none;">
                        <label for="inputPostalCode" class="form-label">Landmark</label>
                        <input name="landmark" class="form-control landmark" id="landmark" type="text" placeholder="Landmark">
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Is the street where your property located on tarred road?</label>
                        <select name="tarred_located" id="tarred_located" class="form-control tarred_located" data-placeholder="Choose One" data-dropdown-css-class="select2-primary">
                          <option value="">--Select--</option>
                          <option value="Yes" >Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div>
                      <!-- <div class="col-md-6 col-lg-6 mb-3 tarred_road" style="display: none;">
                        <label for="inputPostalCode" class="form-label">If the question above is yes please provide it</label>
                        <input name="tarred_road" class="form-control tarred_road" id="tarred_road" type="text" placeholder="Enter tarred road">
                      </div> -->

                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Which of the following does your home support?</label>
                        <select name="home_support" id="home_support" class="form-control home_support" data-placeholder="Choose One" data-dropdown-css-class="select2-primary">
                          <option value="">--Select--</option>
                          <option value="None" >None</option>
                          <option value="hosting_parties" >Hosting Parties</option>
                          <option value="mini_events" >Hosting get together/mini-events</option>
                        </select>
                      </div>

                      <div class="col-md-12 col-lg-12 mb-12">
                        <label for="inputPostalCode" class="form-label">What is the maximum number of people allowed for parties?</label>
                        <input name="people_allowed_parties" class="form-control people_allowed_parties" id="people_allowed_parties" type="text" placeholder="We allow minimum of 20 guests above for party and 10 guests for get together">
                      </div>
                      
                    </div>
                  </form>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <!-- <a href="javascript:void(0)" class="btn secondary_btn return_category_page">Back</a> -->
                <?php /*  <a href="{{ route('web.become_a_host.host_amenity') }}" class="btn secondary_btn">Back</a> */?>
                  <a href="{{ route('web.become_a_host.host_guest') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn max_guest_next">Next</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>  
  </main>


		<!--Add Building Modal -->
		<div class="modal fade select-sec" id="newBuildingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add Building</h5>
						<button type="button" class="close buildingModalClose" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="" class="formAction" id="buildingFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
							<div class="form-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="margin-cls border border-3 p-4 rounded row">
											<div class="col-md-12 mb-3">
												<label for="inputName" class="form-label">Name*</label>
												<?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
												{!! Form::text('name', null, array('id'=>'name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
												{!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
											</div>
											<div class="col-md-12 mb-3">
												<label for="inputMobile" class="form-label">Image</label>
												<div class="input-group">
													<div id="image_preview"><img id="previewing_build" src="{{ URL::asset('assets/images/image.png')}}" width="50" height="50"></div>
													<div class="form-control" onclick="document.getElementById('file_build').click()">
														<label for="files">Select Image</label>
														<input type="file" id="file_build" name="image" style="visibility:hidden;" class="form-control">
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="d-flex">
													<a href="javascript:void(0)" class="btn btn-light buildingModalClose" data-dismiss="modal">Cancel</a>
													<button type="submit" class="btn btn-light ms-auto">Save</button>
												</div>		  
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary buildingModalClose" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save</button> -->
					</div>
				</div>
			</div>
		</div>
		<!--Add Building Modal End -->

  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <!-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>  -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY"></script>

  <script>
  	function newBuildingModal($this){
      $('#newBuildingModal').modal('show');
    }
    $(document).ready(function(){
      $(".buildingModalClose").click(function(){
        $("#newBuildingModal").modal('toggle');
      });
    });
    $('.cctv').on('change', function(){
      if($(this).val() == 'Yes'){
        $('.cctv_locations_div').css('display','block');
      }else{
        $('.cctv_locations_div').css('display','none');
      }
    })

    $('.estate_located').on('change', function(){

      if ($(this).val() == 'Yes') {
        $('.estateFields').css('display','block');

      } else {
        $('.estateFields').css('display','none');
      }
    })

    // $('.tarred_located').on('change', function(){
    //   if ($(this).val() == 'Yes') {
    //     $('.tarred_road').css('display','block');
    //   } else {
    //     $('.tarred_road').css('display','none');
    //   }
    // })

    $('.apartment_responsible').on('change', function() {

      if($(this).val() == 'Agent'){
        $('.other_apartment_responsible').css('display','block');
      }else{
        $('.other_apartment_responsible').css('display','none');
      }
    })

    /*Add card Start */
    $(document).ready(function(){
      getGuest();
    })
    function getGuest(){
      if(sessionStorage.getItem("max_guest") != null){
        $("#max_guest").val(sessionStorage.getItem("max_guest"));
        $("#cctv").val(sessionStorage.getItem("cctv"));
        $("#cctv_locations").val(sessionStorage.getItem("cctv_locations"));
        $("#wifi_username").val(sessionStorage.getItem("wifi_username"));
        $("#wifi_password").val(sessionStorage.getItem("wifi_password"));
        $("#no_of_television").val(sessionStorage.getItem("no_of_television"));
        $("#location_of_television").val(sessionStorage.getItem("location_of_television"));
        // alert(sessionStorage.getItem("pets_allow"));
        $("#pets_allow").val(sessionStorage.getItem("pets_allow"));
        $("#response_time").val(sessionStorage.getItem("response_time"));
        $("#minimum_no_of_nights").val(sessionStorage.getItem("minimum_no_of_nights"));
        $("#apartment_responsible").val(sessionStorage.getItem("apartment_responsible"));
        $("#other_responsibility").val(sessionStorage.getItem("other_responsibility"));
        $("#estate_located").val(sessionStorage.getItem("estate_located"));
        $("#estate_name").val(sessionStorage.getItem("estate_name"));
        $("#landmark").val(sessionStorage.getItem("landmark"));
        $("#tarred_located").val(sessionStorage.getItem("tarred_located"));
        // $("#tarred_road").val(sessionStorage.getItem("tarred_road"));
        $("#home_support").val(sessionStorage.getItem("home_support"));
        $("#people_allowed_parties").val(sessionStorage.getItem("people_allowed_parties"));
        // $("#allow_a_day_booking").val(sessionStorage.getItem("allow_a_day_booking"));
        $("#house_rule").val(sessionStorage.getItem("house_rule"));
        // $("#building").val(sessionStorage.getItem("building"));
      }
    }

    $(document).on('click', ".max_guest_next", function(e) {
      var max_guest = $('#max_guest').val();
      var cctv = $('#cctv').val();
      var cctv_locations = $('#cctv_locations').val();
      var wifi_username = $('#wifi_username').val();
      var wifi_password = $('#wifi_password').val();
      var no_of_television = $('#no_of_television').val();
      var pets_allow = $('#pets_allow').val();
      var location_of_television = $('#location_of_television').val();
      var response_time = $('#response_time').val();
      var minimum_no_of_nights = $('#minimum_no_of_nights').val();
      var apartment_responsible = $('#apartment_responsible').val();
      var other_responsibility = $('#other_responsibility').val();
      var estate_located = $('#estate_located').val();
      var estate_name = $('#estate_name').val();
      var landmark = $('#landmark').val();
      var tarred_located = $('#tarred_located').val();
      // var tarred_road = $('#tarred_road').val();
      var home_support = $('#home_support').val();
      var people_allowed_parties = $('#people_allowed_parties').val();
      // var allow_a_day_booking = $('#allow_a_day_booking').val();
      var house_rule = $('#house_rule').val();
      // var building = $('#building').val();
      if(max_guest != ''){
          sessionStorage.setItem("max_guest", max_guest);
          sessionStorage.setItem("cctv", cctv);
          sessionStorage.setItem("cctv_locations", cctv_locations);
          sessionStorage.setItem("wifi_username", wifi_username);
          sessionStorage.setItem("wifi_password", wifi_password);
          sessionStorage.setItem("no_of_television", no_of_television);
          sessionStorage.setItem("location_of_television", location_of_television);
          sessionStorage.setItem("pets_allow", pets_allow);
          sessionStorage.setItem("response_time", response_time);
          sessionStorage.setItem("minimum_no_of_nights", minimum_no_of_nights);
          sessionStorage.setItem("apartment_responsible", apartment_responsible);
          sessionStorage.setItem("other_responsibility", other_responsibility);
          sessionStorage.setItem("estate_located", estate_located);
          sessionStorage.setItem("estate_name", estate_name);
          sessionStorage.setItem("landmark", landmark);
          sessionStorage.setItem("tarred_located", tarred_located);
          // sessionStorage.setItem("tarred_road", tarred_road);
          sessionStorage.setItem("home_support", home_support);
          sessionStorage.setItem("people_allowed_parties", people_allowed_parties);
          // sessionStorage.setItem("allow_a_day_booking", allow_a_day_booking);
          sessionStorage.setItem("house_rule", house_rule);
          // sessionStorage.setItem("building", building);
          window.location.replace("{{ route('web.become_a_host.host_title') }}");
      }else{
        alert('Please enter mandatory fields.');
        return false;
      }
    });



    $("#buildingFormSubmit").on('submit',function(e){
      e.preventDefault();
      var _this=$(this); 
      var name = $('#name').val();
      var formData = new FormData(this);
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      // $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
      // var url = '{{ url("admin/building/buildingStore") }}';
      var url = '{{ route("web.become_a_host.buildingStore") }}';
      if(name != ''){
        $.ajax({
          url:url,
          // dataType:'html',
          dataType:'json',
          data:formData,
          cache:false,
          contentType: false,
          processData: false,
          type:'POST',
          success:function(result){
            console.log('result--'+result);
            // var result1 = JSON.parse(result);
            // if(result.status == true){
              toastr.success('Building added successfully.');
              $('.main_building_div').html(result.message);
              $('#newBuildingModal').modal('hide');
              $('#buildingFormSubmit')[0].reset();
              setTimeout(function(){
              	location.reload();
              	// window.location.replace("{{ route('admin.rate.index') }}");
              }, 1000);
            // }else{
            // 	toastr.error(result.message);
            // }
          },
          error:function(jqXHR,textStatus,textStatus){
            if(jqXHR.responseJSON.errors){
              $.each(jqXHR.responseJSON.errors, function( index, value ) {
                toastr.error(value)
              });
            }else{
              toastr.error(jqXHR.responseJSON.message)
            }
          }
        });
      }
      return false;   
    });

    $(document).ready(function () {
      $("#file_build").change(function(){
        var fileObj = this.files[0];
        var imageFileType = fileObj.type;
        var imageSize = fileObj.size;

        var file = $('#file_build')[0].files[0].name;
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
        $("#file_build").css("color","green");
        $('#previewing_build').attr('src',e.target.result);
      }
    })
  </script>
@endsection