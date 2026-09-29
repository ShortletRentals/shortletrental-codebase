@extends('layouts.web.master')

@section('content')
<?php ///Page 3 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>Where is your place located?</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                  <form class="booking-form">
                    <div class="row">
                      <!-- <div class="col-md-12">
                          <div class="form-group">
                            <input type="text" placeholder="Address (Line 1/2)" name="" class="form-control" />
                          </div>
                      </div> -->
                      <div class="col-md-6 mb-3">
                        <label for="inputName" class="form-label">Country*</label>
                        <select name="country_id" class="form-control country_id" /*onchange="getProvince()"*/ id='country_id'  data-parsley-required="true">
                          @if(!empty($country))
                            <option value="">Select Country</option>
                            @foreach($country as $key => $country1)
                              <?php $selected = isset($data) && !empty($data->data->country_id) ? $data->data->country_id : "";?>
                              <option value="{{$country1->id}}" <?php echo $selected == $country1->id  ? 'selected' : '' ?>>{{$country1->name}}</option>
                            @endforeach
                          @else
                          @endif
                        </select>
                      </div>
                    </div>
                    <div class="row d-flex align-items-end">
                      <div class="col-md-6 mb-3 show_provinceDiv">
                        <label for="inputName" class="form-label">Province*</label>
                        <select name="province_id" class="form-control province_id1" id='province_id1'  data-parsley-required="true">
                          <option value="">Select Province</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <a href="javascript:void(0)" class="btn primary_btn add_new" onclick="newProvinceModal(this)" > Add New</a>
                      </div>
                    </div>
                    <div class="row d-flex align-items-end">
                      <div class="col-md-6 mb-3 show_cityDiv">
                        <label for="inputName" class="form-label">City*</label>
                        <select name="city_id" class="form-control city_id1" id='city_id1'  data-parsley-required="true">
                          <option value="">Select City</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <a href="javascript:void(0)" class="btn primary_btn add_new" onclick="newCityModal(this)" > Add New</a>
                      </div>
                    </div>
                    <div class="row d-flex align-items-end">
                      <div class="col-md-6 mb-3 show_areaDiv">
                        <label for="inputName" class="form-label">Area</label>
                        <select name="area" class="form-control area" id='area' required>
                          <option value="">Select Area</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <a href="javascript:void(0)" class="btn primary_btn add_new" onclick="newAreaModal(this)" > Add New</a>
                      </div>
                    </div>
                    <div class="row">

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Postal Code</label>
                        <input name="postal_code" class="form-control postal_code" id="postal_code" type="text" placeholder="Enter Postal Code">
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputName" class="form-label">Street Type</label>
                        <select name="street_type" id="street_type" class="form-control street_type" data-placeholder="Select Street Type" data-dropdown-css-class="select2-primary">
                          <option value="" >--None--</option>
                          <option value="Alley" >Alley</option>
                          <option value="Avenue" >Avenue</option>
                          <option value="Boulevard" >Boulevard</option>
                          <option value="Circle" >Circle</option>
                          <option value="Cul-de-sac" >Cul-de-sac</option>
                          <option value="Passage" >Passage</option>
                          <option value="Path" >Path</option>
                          <option value="Road" >Road</option>
                          <option value="Roundabout" >Roundabout</option>
                          <option value="Square" >Square</option>
                          <option value="Street" >Street</option>
                          <option value="Walk" >Walk</option>
                          <option value="Way" >Way</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Street name</label>
                        <input name="street_name" id="street_name" class="form-control street_name" type="text" placeholder="Enter Street Name">
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputName" class="form-label">Type street of numbering</label>
                        <select name="street_number" id="street_number" class="form-control street_number" data-placeholder="Select Street Number" data-dropdown-css-class="select2-primary">
                          <option value="" >--None--</option>
                          <option value="Kilometer" >Kilometer</option>
                          <option value="Number" >Number</option>
                          <option value="Other" >Other</option>
                          <option value="Without_number">Without number</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Building/house no. </label>
                        <input name="house_number" id="house_number" class="form-control" type="text" placeholder="Enter Building/house no.">
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputName" class="form-label">Floor</label>
                        <select name="floor" id="floor" class="form-control floor" data-placeholder="Floor" data-dropdown-css-class="select2-primary">
                          <option value="0" >0</option>
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
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                          <option value="25">25</option>
                          <option value="26">26</option>
                          <option value="27">27</option>
                          <option value="28">28</option>
                          <option value="29">29</option>
                          <option value="30">30</option>
                          <option value="31">31</option>
                          <option value="32">32</option>
                          <option value="33">33</option>
                          <option value="34">34</option>
                          <option value="35">35</option>
                          <option value="36">36</option>
                          <option value="37">37</option>
                          <option value="38">38</option>
                          <option value="39">39</option>
                          <option value="40">40</option>
                          <option value="41">41</option>
                          <option value="42">42</option>
                          <option value="43">43</option>
                          <option value="44">44</option>
                          <option value="45">45</option>
                          <option value="46">46</option>
                          <option value="47">47</option>
                          <option value="48">48</option>
                          <option value="49">49</option>
                          <option value="50">50</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <!-- <input name="staircase" id="staircase" class="checkbox" type="checkbox" value="1">
                        <label for="inputName" class="form-label">Staircase</label> -->
                        <label for="inputName" class="form-label">Select Staircase</label>
                        <select name="staircase" id="staircase" class="form-control staircase" data-placeholder="Select Staircase" data-dropdown-css-class="select2-primary">
                          <!-- <option value="" >--Select Staircase--</option> -->
                          <option value="Yes">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <!-- <input name="elevator" id="elevator" class="checkbox" type="checkbox" value="1">
                        <label for="inputName" class="form-label">Elevator</label> -->
                        <label for="inputName" class="form-label">Select Elevator</label>
                        <select name="elevator" id="elevator" class="form-control" data-placeholder="Select Elevator" data-dropdown-css-class="select2-primary">
                          <!-- <option value="" >--Select Elevator--</option> -->
                          <option value="Yes" >Yes</option>
                          <option value="No" >No</option>
                        </select>
                      </div>

                      <div class="col-md-6 col-lg-6 mb-3">
                        <label for="inputPostalCode" class="form-label">Apartment door no.</label>
                        <input name="apartment_door_no" id="apartment_door_no" class="form-control" type="text" placeholder="Enter Apartment door no.">
                      </div>
                      <input type="hidden" name="latitude" id="latitude" class="form-control" placeholder="Enter latitude">
                      <input type="hidden" name="longitude" id="longitude" class="form-control" placeholder="Enter longitude">
                      <input type="hidden" placeholder="Address" name="address" class="form-control" id="address" autocomplete="off" data-parsley-required="true">
                      <div class="col-md-12 mb-3">
                          <style>
                            #map_canvas {
                              width: 100%;
                              height: 300px;
                            }

                            /* Optional: Makes the sample page fill the window. */
                            html,
                            body {
                              height: 100%;
                              margin: 0;
                              padding: 0;
                            }
                          </style>
                          <div class="show-location-map">
                            <h3>Show your specific location</h3>
                            <div id="map_canvas"></div>
                          </div>
                      </div>
                    </div>
                  </form>
                  <!-- <div class="show-location-map">
                    <h3>Show your specific location</h3>
                    <div class="map-img">
                        <img src="img/contact-map.png" alt="Map">
                    </div>
                  </div> -->
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="javascript:void(0)" class="btn secondary_btn return_category_page">Back</a>
                  <!-- <a href="{{ url('web.become_a_host.host_address') }}" class="btn secondary_btn">Back</a> -->
                  <a href="javascript:void(0)" class="btn primary_btn address_next">Next</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>  
  </main>


  <div class="modal_main_cls">
		<!--Add Province Modal -->
    <div class="modal fade select-sec" id="newProvinceModal" tabindex="-1" aria-labelledby="schedule_appointment" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="login-inner">
                <div class="sign-title">
                  <h2>Add Province</h2>
                </div>
                <form action="" class="formAction" id="provinceFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="row">

                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">Country*</label>
                            <select name="country_id" class="form-control country_id" onchange="getProvince_city()" required id='country_id_city'>
                              @if(!empty($country))
                                <option value="">Select Country</option>
                                @foreach($country as $key => $country1)
                                  <option value="{{$country1->id}}" >{{$country1->name}}</option>
                                @endforeach
                              @else
                              @endif
                            </select>
                          </div>
                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">Province Name*</label>
                            <?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
                            {!! Form::text('name', null, array('id'=>'province_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
                            {!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
                          </div>
                          <div class="col-md-12">
                            <div class="d-flex">
                              <a href="javascript:void(0)" class="btn primary_btn cityModalClose" data-dismiss="modal">Cancel</a>
                              <button type="submit" class="btn primary_btn ms-auto">Save</button>
                              <!-- <input type="submit" name="" value="Submit" class="btn primary_btn"> -->
                            </div>		  
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
		<!--Add Province Modal End -->

		<!--Add City Modal -->
    <div class="modal fade select-sec" id="newCityModal" tabindex="-1" aria-labelledby="schedule_appointment" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="login-inner">
                <div class="sign-title">
                  <h2>Add City</h2>
                </div>
                <form action="" class="formAction" id="cityFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="row">

                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">Country*</label>
                            <select name="country_id" class="form-control country_id" onchange="getProvince_city()" required id='country_id_city'>
                              @if(!empty($country))
                                <option value="">Select Country</option>
                                @foreach($country as $key => $country1)
                                  <option value="{{$country1->id}}" >{{$country1->name}}</option>
                                @endforeach
                              @else
                              @endif
                            </select>
                          </div>
                          <div class="col-md-12 mb-3 show_provinceDiv">
                            <label for="inputName" class="form-label">Province*</label>
                            <select name="province_id" class="form-control province_id" id='province_id' placeholder="Select Province" required>
                              <option value="">Select Province</option>
                            </select>
                          </div>
                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">City Name*</label>
                            <?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
                            {!! Form::text('name', null, array('id'=>'city_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
                            {!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
                          </div>
                          <div class="col-md-12">
                            <div class="d-flex">
                              <a href="javascript:void(0)" class="btn primary_btn cityModalClose" data-dismiss="modal">Cancel</a>
                              <button type="submit" class="btn primary_btn ms-auto">Save</button>
                              <!-- <input type="submit" name="" value="Submit" class="btn primary_btn"> -->
                            </div>		  
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
		<!--Add City Modal End -->

		<!--Add Area Modal -->
    <div class="modal fade select-sec" id="newAreaModal" tabindex="-1" aria-labelledby="schedule_appointment" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="login-inner">
                <div class="sign-title">
                  <h2>Add Area</h2>
                </div>
                <form action="" class="formAction" id="areaFormSubmit" method="post" enctype="multipart/form-data" data-parsley-validate="true">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="row">

                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">Country*</label>
                            <select name="country_id" class="form-control country_id country_id_area" required id='country_id_area'>
                              @if(!empty($country))
                                <option value="">Select Country</option>
                                @foreach($country as $key => $country)
                                  <option value="{{$country->id}}" >{{$country->name}}</option>
                                @endforeach
                              @else
                              @endif
                            </select>
                          </div>
                          <div class="col-md-12 mb-3 show_provinceDiv">
                            <label for="inputName" class="form-label">Province*</label>
                            <select name="province_id" class="form-control province_id_area" id='province_id_area' required>
                              <option value="">Select Province</option>
                            </select>
                          </div>
                          <div class="col-md-12 mb-3 show_cityDiv">
                            <label for="inputName" class="form-label">City*</label>
                            <select name="city_id" class="form-control city_id" id='city_id' required>
                              <option value="">Select City</option>
                            </select>
                          </div>
                          <div class="col-md-12 mb-3">
                            <label for="inputName" class="form-label">Area Name*</label>
                            <?php  $errorClass =  !empty($errors->has('name')) ? 'is-invalid':''; ?>   
                            {!! Form::text('name', null, array('id'=>'area_name','placeholder' => 'Enter Name*', 'required'=>'required', 'class' => 'form-control '.$errorClass )) !!}
                            {!! !empty($errors->has('name')) ?'<div class="invalid-feedback"><span>'.$errors->first('name').'</span></div>' :'' !!}
                          </div>
                          <div class="col-md-12">
                            <div class="d-flex">
                              <a href="javascript:void(0)" class="btn primary_btn areaModalClose" data-dismiss="modal">Cancel</a>
                              <button type="submit" class="btn primary_btn ms-auto">Save</button>
                              <!-- <a href="javascript:void(0)" class="btn btn-light areaModalClose" data-dismiss="modal">Cancel</a>
                              <button type="submit" class="btn btn-light ms-auto">Save</button> -->
                            </div>		  
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
		<!--Add Area Modal End -->
		</div>
    
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <!-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>  -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY"></script>

  <script>
    /*Add card Start */
    $(document).ready(function(){
      getProvince();
      getAddress();

	    var latitude = "{{isset($data->latitude) ? $data->latitude :'26.8549135'}}";
	    var longitude = "{{isset($data->longitude) ? $data->longitude :'75.7676642'}}";
	    autoload(latitude, longitude);
    })
    function getAddress(){
      // alert(sessionStorage.getItem("host_province_id"));
      if(sessionStorage.getItem("host_country_id") != null){
        $("#country_id").val(sessionStorage.getItem("host_country_id"));

        $("#province_id").val(sessionStorage.getItem("host_province_id"));
        $("#city_id").val(sessionStorage.getItem("host_city_id"));
        $("#area").val(sessionStorage.getItem("host_area"));
        $("#postal_code").val(sessionStorage.getItem("host_postal_code"));

        $("#street_type").val(sessionStorage.getItem("host_street_type"));
        $("#street_name").val(sessionStorage.getItem("host_street_name"));
        $("#street_number").val(sessionStorage.getItem("host_street_number"));
        $("#house_number").val(sessionStorage.getItem("host_house_number"));
        $("#floor").val(sessionStorage.getItem("host_floor"));
        $("#staircase").val(sessionStorage.getItem("host_staircase"));
        $("#elevator").val(sessionStorage.getItem("host_elevator"));
        $("#apartment_door_no").val(sessionStorage.getItem("host_apartment_door_no"));
        $("#address").val(sessionStorage.getItem("host_address"));
        $("#latitude").val(sessionStorage.getItem("host_latitude"));
        $("#longitude").val(sessionStorage.getItem("host_longitude"));
      }
    }

    $(document).on('click', ".return_category_page", function(e) {
      var type = sessionStorage.getItem("host_type");
      window.location.replace("{{ url('host_category') }}"+'/'+type);
    });
    /*
    $(document).on('click', ".address_next", function(e) {
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').find(":selected").val();
      var city_id = $('#city_id1').find(":selected").val();
      var area = $('#area').val();
      var postal_code = $('#postal_code').val();

      var street_type = $('#street_type').val();
      var street_name = $('#street_name').val();
      var street_number = $('#street_number').val();
      var house_number = $('#house_number').val();
      var floor = $('#floor').val();
      var staircase = $('#staircase').val();
      var elevator = $('#elevator').val();
      var apartment_door_no = $('#apartment_door_no').val();

      var address = $('#address').val();
      var latitude = $('#latitude').val();
      var longitude = $('#longitude').val();
      if(country_id != '' && province_id != '' ){
          sessionStorage.setItem("host_country_id", country_id);
          sessionStorage.setItem("host_province_id", province_id);
          sessionStorage.setItem("host_city_id", city_id);
          sessionStorage.setItem("host_area", area);
          sessionStorage.setItem("host_postal_code", postal_code);

          sessionStorage.setItem("host_street_type", street_type);
          sessionStorage.setItem("host_street_name", street_name);
          sessionStorage.setItem("host_street_number", street_number);
          sessionStorage.setItem("host_house_number", house_number);
          sessionStorage.setItem("host_floor", floor);
          sessionStorage.setItem("host_staircase", staircase);
          sessionStorage.setItem("host_elevator", elevator);
          sessionStorage.setItem("host_apartment_door_no", apartment_door_no);

          sessionStorage.setItem("host_address", address);
          sessionStorage.setItem("host_latitude", latitude);
          sessionStorage.setItem("host_longitude", longitude);
          window.location.replace("{{ route('web.become_a_host.host_guest') }}");
      }else{
        alert('Please select all mandetory fields.');
        return false;
      }
    });
    */
    $(document).on('click', ".address_next", function(e) {
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').find(":selected").val();
      var city_id = $('#city_id1').find(":selected").val();
      var area = $(".area").val();
    
      var postal_code = $('.postal_code').val();

      var street_type = $('.street_type').val();
      var street_name = $('.street_name').val();
      var street_number = $('.street_number').val();
      var house_number = $('#house_number').val();
      var floor = $('#floor').val();
      var staircase = $('#staircase').val();
      var elevator = $('#elevator').val();
      var apartment_door_no = $('#apartment_door_no').val();
      var address = $('#address').val();
      var latitude = $('#latitude').val();
      var longitude = $('#longitude').val();
      if(country_id != '' && province_id != '' ){
          sessionStorage.setItem("host_country_id", country_id);
          sessionStorage.setItem("host_province_id", province_id);
          sessionStorage.setItem("host_city_id", city_id);
          sessionStorage.setItem("host_area", area);
          sessionStorage.setItem("host_postal_code", postal_code);

          sessionStorage.setItem("host_street_type", street_type);
          sessionStorage.setItem("host_street_name", street_name);
          sessionStorage.setItem("host_street_number", street_number);
          sessionStorage.setItem("host_house_number", house_number);
          sessionStorage.setItem("host_floor", floor);
          sessionStorage.setItem("host_staircase", staircase);
          sessionStorage.setItem("host_elevator", elevator);
          sessionStorage.setItem("host_apartment_door_no", apartment_door_no);

          sessionStorage.setItem("host_address", address);
          sessionStorage.setItem("host_latitude", latitude);
          sessionStorage.setItem("host_longitude", longitude);
         // console.log(sessionStorage);
          window.location.replace("{{ route('web.become_a_host.host_guest') }}");
      }else{
        alert('Please select all mandetory fields.');
        return false;
      }
    });


  	function newProvinceModal($this){
      $('#newProvinceModal').modal('show');
    }
  	function newCityModal($this){
      $('#newCityModal').modal('show');
    }
  	function newAreaModal($this){
      $('#newAreaModal').modal('show');
    }
    $(document).ready(function(){
      $(".ProvinceModalClose").click(function(){
        $("#newProvinceModal").modal('toggle');
      });
      $(".cityModalClose").click(function(){
        $("#newCityModal").modal('toggle');
      });
      $(".areaModalClose").click(function(){
        $("#newAreaModal").modal('toggle');
      });
    });


    $("#provinceFormSubmit").on('submit',function(e){
      e.preventDefault();
      var _this=$(this); 
      var name = $('#province_name').val();
      var formData = new FormData(this);
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      // $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
      var url = '{{ route("web.become_a_host.provinceStore") }}';
      // var url = '{{ url("admin/city/cityStore") }}';
      if(name != ''){
        $.ajax({
          url:url,
          dataType:'json',
          data:formData,
          cache:false,
          contentType: false,
          processData: false,
          type:'POST',
          success:function(result){
            console.log('result.status'+result.status);
            if(result.status == true){
              toastr.success(result.message);
              setTimeout(function(){
                location.reload();
                // window.location.replace("{{ route('admin.rate.index') }}");
              }, 1000);
            }else{
              toastr.error(result.message);
            }
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

    $("#cityFormSubmit").on('submit',function(e){
      e.preventDefault();
      var _this=$(this); 
      var name = $('#city_name').val();
      var formData = new FormData(this);
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      // $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
      var url = '{{ route("web.become_a_host.cityStore") }}';
      // var url = '{{ url("admin/city/cityStore") }}';
      if(name != ''){
        $.ajax({
          url:url,
          dataType:'json',
          data:formData,
          cache:false,
          contentType: false,
          processData: false,
          type:'POST',
          success:function(result){
            console.log('result.status'+result.status);
            if(result.status == true){
              toastr.success(result.message);
              setTimeout(function(){
                location.reload();
                // window.location.replace("{{ route('admin.rate.index') }}");
              }, 1000);
            }else{
              toastr.error(result.message);
            }
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

    $("#areaFormSubmit").on('submit',function(e){
      e.preventDefault();
      var _this=$(this); 
      var name = $('#area_name').val();
      var formData = new FormData(this);
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      // $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
      // var url = '{{ url("admin/area/areaStore") }}';
      var url = '{{ route("web.become_a_host.areaStore") }}';
      if(name != ''){
        $.ajax({
          url:url,
          dataType:'json',
          data:formData,
          cache:false,
          contentType: false,
          processData: false,
          type:'POST',
          success:function(result){
            console.log('result.status'+result.status);
            if(result.status == true){
              toastr.success(result.message);
              setTimeout(function(){
                location.reload();
                // window.location.replace("{{ route('admin.rate.index') }}");
              }, 1000);
            }else{
              toastr.error(result.message);
            }
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

    $(document).on('change', '.country_id_area',function(){
      var country_id = $('.country_id_area').val();
      var province_id = "";

      if (country_id) {
        $.ajax({
          url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
          // url:'{{url("admin/area/show_province_new")}}/'+country_id+'/'+province_id,
          dataType: 'html',
          success:function(result)
          {
            $('.show_provinceDiv').html(result);
          }
        });
      }
    })

    function getProvince_city() {
      var country_id = $('#country_id_city').val();
      var province_id = "";
      $.ajax({
        url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_provinceDiv').html(result);
        }
      });
    }

    $(document).on('change', '.country_id',function(){
      var country_id = $('#country_id').val();
      var province_id = "<?php if (isset($data) && $data->data->province_id) { echo $data->data->province_id; } ?>";

      if (country_id) {
        $.ajax({
          url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
          dataType: 'html',
          success:function(result)
          {
            console.log('--result'+result);
            $('.show_provinceDiv').html(result);
            var country_name = $('#country_id').find("option:selected").text();
            getLatLongByCountry(country_name);
          }
        });
      }
	  })

	  // $(document).on('change', '.province_id1',function(){
    //   var country_id = $('#country_id').val();
    //   var province_id = $('#province_id1').find(":selected").val();
    //   var city_id = "<?php if (isset($data) && $data->data->city_id) { echo $data->data->city_id; } ?>";
    //   if (province_id) {
    //     $.ajax({
    //       url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
    //       dataType: 'html',
    //       success:function(result)
    //       {
    //         $('.show_cityDiv').html(result);
    //         var country_name = $('#country_id').find("option:selected").text();
    //         var province_name = $('#province_id1').find("option:selected").text();
    //         const address = province_name +', '+ country_name;
    //         // alert('address--'+address);
    //         getLatLongByCountry(address);
    //         // getArea();
    //       }
    //     });
    //   }
	  // })

    $(document).on('change', '.city_id1',function(){
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').val();
      var city_id = $('#city_id1').val();
      var area_id = "";
      if (city_id) {
        $.ajax({
          url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
          dataType: 'html',
          success:function(result)
          {
            $('.show_areaDiv').html(result);
            var country_name = $('#country_id').find("option:selected").text();
            var province_name = $('#province_id1').find("option:selected").text();
            var city_name = $('#city_id1').find("option:selected").text();
            const address = city_name+', '+province_name +', '+ country_name;
            // alert('address--'+address);
            getLatLongByCountry(address);
          }
        });
      }
    })

    function getProvince() {
      var country_id = sessionStorage.getItem("host_country_id");
      var province_id = sessionStorage.getItem("host_province_id");
      // alert(country_id);
      // alert(province_id);
      if (country_id) {
        $.ajax({
          url:'{{url("area/show_province")}}/'+country_id+'/'+province_id,
          dataType: 'html',
          success:function(result)
          {
            $('.show_provinceDiv').html(result);
            getCity();
          }
        });
      }
    }

    function getCity() {
      var country_id = sessionStorage.getItem("host_country_id");
      var province_id = sessionStorage.getItem("host_province_id");
      var city_id = sessionStorage.getItem("host_city_id");
    
      if (province_id) {
        $.ajax({
            url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
            dataType: 'html',
            success:function(result)
            {
              $('.show_cityDiv').html(result);
            }
        });
      }
    }

    function getArea() {
      var country_id = sessionStorage.getItem("host_country_id");
      var province_id = sessionStorage.getItem("host_province_id");
      var city_id = sessionStorage.getItem("host_city_id");
      var area = sessionStorage.getItem("host_area");
      // alert(country_id);
      // alert(province_id);
      // alert(city_id);
      // alert(area_id);
      if (city_id) {
        $.ajax({
          url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
          dataType: 'html',
          success:function(result)
          {
            $('.show_areaDiv').html(result);
            changeAreaInner();
          }
        });
      }
    }

    $(document).ready(function(){
      $(".postal_code").keyup(function(){
        // console.log(this.value.length);
        if(this.value.length > 4){
          var country_name = $('#country_id').find("option:selected").text();
          var province_name = $('#province_id1').find("option:selected").text();
          var city_name = $('#city_id1').find("option:selected").text();
          var area_name = $('#area').find("option:selected").text();
          var postal_code = this.value;
          // alert(postal_code);
          if ($('#country_id').find("option:selected").val() && $('#province_id1').find("option:selected").val() && $('#city_id1').find("option:selected").val() ){
            var street_name = $(".street_name").val();
            if(street_name != ''){
              const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
              getLatLongByCountry(address);
            }else{
              const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
              // alert(country_name);
              // alert(province_name);
              // alert(city_name);
              // alert(area_name);
              // alert(address);
              getLatLongByCountry(address);
            }
          }
          // const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
          // getLatLongByCountry(address);
        }
      });
    });

    $(document).ready(function(){
      $(".street_name").keyup(function(){
        // console.log(this.value.length);
        if(this.value.length >= 3){
          var country_name = $('#country_id').find("option:selected").text();
          var province_name = $('#province_id1').find("option:selected").text();
          var city_name = $('#city_id1').find("option:selected").text();
          var area_name = $('#area').find("option:selected").text();
          var postal_code = $('.postal_code').val();
          var street_name = this.value;

          if ($('#country_id').find("option:selected").val() && $('#province_id1').find("option:selected").val() && $('#city_id1').find("option:selected").val() ){
            const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
            getLatLongByCountry(address);
          }
        }
      });
    });

    function getStreet(){
      var country_name = $('#country_id').find("option:selected").text();
      var province_name = $('#province_id').find("option:selected").text();
      var city_name = $('#city_id').find("option:selected").text();
      var area_name = $('#area').find("option:selected").text();
      var postal_code = "<?php if (isset($data) && $data->getPropertyAddress[0]->postal_code) { echo $data->getPropertyAddress[0]->postal_code; } ?>";
      var street_name = "<?php if (isset($data) && $data->getPropertyAddress[0]->street_name) { echo $data->getPropertyAddress[0]->street_name; } ?>";
      // const address = street_name+', '+postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
      // alert('address--'+address);

      if ($('#country_id').find("option:selected").val() && $('#province_id').find("option:selected").val() && $('#city_id').find("option:selected").val() ){
        const address = street_name+', '+ postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
        getLatLongByCountry(address);
      }
      // getLatLongByCountry(address);
    }

    function getPostalCode(){
      var country_name = $('#country_id').find("option:selected").text();
      var province_name = $('#province_id1').find("option:selected").text();
      var city_name = $('#city_id1').find("option:selected").text();
      var area_name = $('#area').find("option:selected").text();
      var postal_code = "<?php if (isset($data) && $data->getPropertyAddress[0]->postal_code) { echo $data->getPropertyAddress[0]->postal_code; } ?>";
      const address = postal_code+', '+area_name+', '+city_name+', '+province_name +', '+ country_name;
      // alert('address--'+address);
      getLatLongByCountry(address);
    }
    $(document).on('change', '.area',function(){
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').val();
      var city_id = $('#city_id1').val();
      var area_id = $('#area').val();
      if (area_id) {
        var country_name = $('#country_id').find("option:selected").text();
        var province_name = $('#province_id1').find("option:selected").text();
        var city_name = $('#city_id1').find("option:selected").text();
        var area_name = $('#area').find("option:selected").text();
        if(area_name){
          // alert(country_name);
          // alert(province_name);
          // alert(city_name);
          // alert(area_name);
          const address = area_name+', '+city_name+', '+province_name +', '+ country_name;
          // alert('address--'+address);
          getLatLongByCountry(address);
        }else{
          const address = city_name+', '+province_name +', '+ country_name;
          // alert('address--'+address);
          getLatLongByCountry(address);
        }
      }
    })

    function changeAreaInner() {
      var country_name = $('#country_id').find("option:selected").text();
      var province_name = $('#province_id1').find("option:selected").text();
      var city_name = $('#city_id1').find("option:selected").text();
      var area_name = $('#area').find("option:selected").text();
      if(area_name){
        const address = area_name+', '+city_name+', '+province_name +', '+ country_name;
        // alert('address--'+address);
        getLatLongByCountry(address);
      }else{
        const address = city_name+', '+province_name +', '+ country_name;
        // alert('address--'+address);
        getLatLongByCountry(address);
      }
      getPostalCode();
      getStreet();
    }
  /*Add card End*/
  </script>


<script type="text/javascript">
	$(document).ready(function(){
		$(".alert").delay(5000).slideUp(300);
	});
  // get address start
	var geocoder;
	var map;
	var marker;
	var infowindow = new google.maps.InfoWindow({
		size: new google.maps.Size(150, 50)
	});
    // initialize();
  // autoload(25.204849, 55.270783);

    var autocomplete;
	function getLatLongByCountry(address){
		if (address) {
			// $.ajaxSetup({
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   }
      // });
      var token = "{{ csrf_token() }}";
      // formData.append('_token', token);
			$('#latitude').val('');
			$('#longitude').val('');
			// alert(address);
      $.ajax({
        url:'{{route("web.getLatLongByCountry")}}',
				data: { '_token': token, 'address': address },
        type: 'post',
        success:function(result)
        {
          // console.log('result--'+result.lat+'result1--'+result.long);
          if(result.lat){
            console.log('result--'+result.lat+', result1--'+result.lng);
            const lat = result.lat;
            const lng = result.lng;
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#address').val(address);
            autoload(lat, lng);
          }
        }
      });
    }
	};

	function autoload(latitude,longitude) {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(latitude, longitude);
		var mapOptions = {
			zoom: 13,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		google.maps.event.addListener(map, 'click', function() {
			infowindow.close();
		});

		marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: {lat:latitude, lng: longitude}
		});
		marker.addListener('click', toggleBounce);
	}

	function toggleBounce()
	{
    if (marker.getAnimation() !== null) {
      marker.setAnimation(null);
    } else {
      marker.setAnimation(google.maps.Animation.BOUNCE);
    }
  }
</script>
@endsection