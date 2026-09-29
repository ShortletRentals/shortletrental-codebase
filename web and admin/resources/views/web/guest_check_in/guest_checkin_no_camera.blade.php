@extends('layouts.web.master')

@section('content')

    <main>
      <section class="property-sec">
        <div class="container-fluid">
          <div class="row">
            
            <div class="col-md-6 grad-bg">
              <div class="checkin-price-dtl">
                <div class="product_dtl_bg">
                  <div class="checkin-location-bg" style="background-image: {{ URL::asset('assets/web/img/location-bg.jpg') }};">
                      <h5 class="ref-id">{{$data->booking_id}}</h5>
                      <p>{{$data->title}}</p>
                      <div class="checkin_location">
                        <span><img src="{{ URL::asset('assets/web/img/location.png') }}">{{$data->city_name}}</span>
                      </div>
                  </div>
                  <div class="checkinout-sec">
                    <div class="checkin_checkout">
                      <div class="checkin_wrap">
                        <label>CHECK-IN</label>
                        <p>{{date('d/m/Y', strtotime($data->from_date))}}</p>
                      </div>
                      <div class="checkin_wrap">
                        <label>CHECK-OUT</label>
                        <p>{{date('d/m/Y', strtotime($data->to_date))}}</p>
                        <!-- <p>9/24/2022</p> -->
                      </div>
                    </div>
                  </div>
                  <div class="hotel-price-wrap">
                    <h4>Payments Detail</h4>
                    <ul>
                      <li>
                        <span class="left_price_p">Payment 1 <span class="payment_date d-block">(21/11/2022)</span></span>
                        <span class="right_price_p">NGN {{$data->total_booking_amount}}</span>
                      </li>
                      <li>
                        <span class="left_price_p">Security Deposit payment <span class="payment_date d-block">(21/11/2022)</span></span>
                        <span class="right_price_p">NGN 0</span>
                      </li>
                      <li>
                        <span class="left_price_p">Security Deposit payment <span class="payment_date d-block">(18/12/2022)</span></span>
                        <span class="right_price_p">-NGN 0</span>
                      </li>
                    </ul>
                    <ul class="total_price">
                      <li>
                        <span class="left_price_p">Total amount</span>
                        <span class="right_price_p">NGN {{$data->total_booking_amount}}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="checkin_main_wrap">
                <div class="checkin-cont">
                    <div class="checkin_step">
                      <div class="step-head">
                        <div class="step-head-left">
                          <a href="{{ url('/guest-area/checkin/').'/'.$data->booking_token }}"><span><img src="{{ URL::asset('assets/web/img/al.png') }}" alt="Arrow Left"></span> Back to the property-list</a>
                        </div>
                        <div class="step-head-right">
                          <ul>
                            <li><a href="#" class="active"></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                          </ul>
                          <span>Step 1 of 3</span>
                        </div>
                      </div>

                      <form method="POST" action="" class="check_in_complete" id="check_in_complete">
                        @csrf
                        <div class="checkin-step-cont">
                          <h5>We have detected that you do not have a camera on your device.</h5>
                          <p>"If your device doesn't have a camera, you can scan this QR code and continue the process from your mobile device so that we can validate your document. </p>
                          <p>Or if you prefer you can fill in your details manually."</p>
                          <div class="qr_code_wrap">
                            <!-- <div class="qr_code_img">
                              <img src="{{ URL::asset('assets/web/img/qr-code.png') }}">
                            </div> -->
                            <!-- <div class="qr_link">
                              <a href="#">https://www.shortletrentals.com/guest-area/checkin/MTY3NzYzMjgjOGNlMmQ1MDU0Yg/guests</a>
                              <span><img src="{{ URL::asset('assets/web/img/copy.png') }}"></span>
                            </div> -->

                          <div class="row">
                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>First Name*</label>
                                <input type="text" name="first_name" placeholder="First Name*" class="form-control" data-parsley-required="true">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Last Name*</label>
                                <input type="text" name="last_name" placeholder="Last Name*" class="form-control" data-parsley-required="true">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Email*</label>
                                <input type="email" name="email" class="form-control" data-parsley-required="true">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Phone Number*</label>
                                <div class="input-group">
                                  <select name="country_code" class="form-control country_code" id='country_code' style="width:120px;">
                                    @if(!empty($country_phone))
                                      @foreach($country_phone as $key1 => $country_ph1)
                                        <?php $selected1 = isset($data->data->country_code) ? $data->data->country_code : "+234" ; ?>
                                        <option value="{{$country_ph1->phonecode}}" <?php echo $selected1 == $country_ph1->phonecode  ? 'selected' : '' ?>>{{$country_ph1->sortname}} +{{$country_ph1->phonecode}}</option>
                                      @endforeach
                                    @else
                                    @endif
                                  </select>
                                  <input type="text" name="phone_number" placeholder="Phone Number*" class="form-control" data-parsley-required="true">
                                </div>                                
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Document*</label>
                                <select name="document_type" id="document_type" class="form-control" data-parsley-required="true">
                                  <option value="Document_ID">Document ID</option>
                                  <option value="Passport">Passport</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Upload Document Image*</label>
                                <input type="file" name="document_image" class="form-control" data-parsley-required="true">
                              </div>
                            </div>

                            <div class="col-md-6 mb-3">
                              <label for="inputName" class="form-label">Country*</label>
                              <select name="country_id" class="form-control country_id" /*onchange="getProvince()"*/ id='country_id'  data-parsley-required="true">
                                @if(!empty($country))
                                  <option value="">Select Country*</option>
                                  @foreach($country as $key => $country1)
                                    <?php $selected = isset($data) && !empty($data->data->country_id) ? $data->data->country_id : "";?>
                                    <option value="{{$country1->id}}" <?php echo $selected == $country1->id  ? 'selected' : '' ?>>{{$country1->name}}</option>
                                  @endforeach
                                @else
                                @endif
                              </select>
                            </div>
                            <div class="col-md-6 mb-3 show_provinceDiv">
                              <label for="inputName" class="form-label">Province*</label>
                              <select name="province_id" class="form-control province_id1" id='province_id1'  data-parsley-required="true" data-parsley-required="true">
                                <option value="">Select Province</option>
                              </select>
                            </div>

                            <div class="col-md-6 mb-3 show_cityDiv">
                              <label for="inputName" class="form-label">City*</label>
                              <select name="city_id" class="form-control city_id" id='city_id'  data-parsley-required="true">
                                <option value="">Select City</option>
                              </select>
                            </div>
                          </div>
                          </div>

                          <div class="d-flex mt-4">
                            <a href="{{ url('/guest-area/checkin/documents/').'/'.$data->booking_token }}" class="btn">Back</a>
                            <!-- <a href="{{ url('/guest-area/checkin/no_camera/').'/'.$data->booking_token }}" class="btn primary_btn">Next</a> -->
                            <input type="submit" value="Complete Manually" class="btn primary_btn">
                          </div> 
                        </div>
                      </form>

                    </div>
                </div>
                <div class="property-footer">
                  <p>© Shortlet Rentals Ltd 2022</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>  
    </main>
    <!-- <div class="button-fix">
    	<a href="#" class="message-right">Message</a>
    </div> -->

  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script>
    var booking_token = "{{ $data->booking_token }}";
    $('#check_in_complete').parsley();
    $(document).on('submit', "#check_in_complete", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();

      var token = "{{ csrf_token() }}";
      var formData = new FormData(this);
      formData.append('_token', token);

      formData.append('nationality',sessionStorage.getItem("nationality"));
      formData.append('dob',sessionStorage.getItem("dob"));
      // formData.append('document_type',sessionStorage.getItem("document_type"));
      // formData.append('upload_type',sessionStorage.getItem("upload_type"));
      formData.append('booking_token',booking_token);
      $.ajax({
        url: '{{ route("web.check_in_complete") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
          if (res.status === true) {
            toastr.success(res.message);
            setInterval(function () {  
              window.location.replace("{{ url('/guest-area/checkin') }}"+'/'+booking_token);
              // window.location.reload();
            }, 2000); 
          } else {
            toastr.error(res.message);
          }
        },
        error: function(jqXHR, textStatus, textStatus) {
          if (jqXHR.responseJSON.errors) {
            $.each(jqXHR.responseJSON.errors, function(index, value) {
              toastr.error(value)
            });
          } else {
            toastr.error(jqXHR.responseJSON.message)
          }
        }
      });
      return false;
    });


    $(document).on('change', '.country_id',function(){
      var country_id = $('#country_id').val();
      var province_id = "";

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

    $(document).on('change', '.province_id1',function(){
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').find(":selected").val();
      var city_id = "";
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
    })

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
      var province_id = "";

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
      var country_id = $('#country_id').val();
      var province_id = $('#province_id1').val();
      var city_id = "";
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
</script>
@endsection