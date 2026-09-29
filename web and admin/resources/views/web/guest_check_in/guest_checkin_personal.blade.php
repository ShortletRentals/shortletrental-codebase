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
                    <div class="checkin-step-cont">
                      <h5>Check In!</h5>
                      <p>By completing your details, you help us to speed up the check-in process and offer you a better experience.</p>
                      <form class="booking-form">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Nationality*</label>
                              <select name="nationality" id="nationality" class="form-control">
                                <option value="">Select Nationality*</option>
                                @if(count($country_data) > 0)
                                @foreach($country_data as $country)
                                  <option value="{{$country->id}}">{{ $country->name }}</option>
                                @endforeach
                                @endif
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                <label>Date of Birth</label>
                                <div class="input-group">
                                  <input type="text" name="" placeholder="05-06-1993" class="form-control dob">
                                </div>
                              </div>
                          </div>   
                          <div class="col-md-12">
                              <div class="d-flex">
                                <a href="{{ url('/guest-area/checkin/').'/'.$data->booking_token }}" class="btn">Back</a>
                                <a href="{{ url('/guest-area/checkin/documents/').'/'.$data->booking_token }}" class="btn primary_btn personal_next">Next</a>
                              </div>
                          </div>                           
                        </div>
                      </form>
                    </div>
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
    $(document).on('click', ".personal_next", function(e) {
      var nationality = $('#nationality option:selected').val();
      var dob = $('.dob').val();
      // alert(nationality);
      // alert(dob);
      if(nationality != ''){
          // alert('inn');
          sessionStorage.setItem("nationality", nationality);
          sessionStorage.setItem("dob", dob);
          // alert(type);
          window.location.replace("{{ url('/guest-area/checkin/documents') }}"+'/'+booking_token);
      }else{
        alert('Please select mandatory fields.');
        return false;
      }
    });


    $(document).ready(function(){
      arrival();
      departure();
    })

    function arrival(){
      var value = $('#arrival_travelling_by option:selected').val();
      if(value != ''){
        if(value == 'Car'){
          $('.arrival_travelling_div').css('display','none');
        }else{
          $('.arrival_travelling_div').css('display','block');
          $('.arrival_number_label').html(value+' Number');
        }
      }else{
        $('.arrival_travelling_div').css('display','none');
      }
    }

    function departure(){
      var value = $('#departure_travelling_by option:selected').val();
      if(value != ''){
        if(value == 'Car'){
          $('.departure_travelling_div').css('display','none');
        }else{
          $('.departure_travelling_div').css('display','block');
          $('.departure_number_label').html(value+' Number');
        }
      }else{
        $('.departure_travelling_div').css('display','none');
      }
    }

    $(document).on('change', "#arrival_travelling_by", function(e) {
      var value = $('#arrival_travelling_by option:selected').val();
      if(value != ''){
        if(value == 'Car'){
          $('.arrival_travelling_div').css('display','none');
        }else{
          $('.arrival_travelling_div').css('display','block');
          $('.arrival_number_label').html(value+' Number');
        }
      }else{
        $('.arrival_travelling_div').css('display','none');
      }
    });

    $(document).on('change', "#departure_travelling_by", function(e) {
      var value = $('#departure_travelling_by option:selected').val();
      if(value != ''){
        if(value == 'Car'){
          $('.departure_travelling_div').css('display','none');
        }else{
          $('.departure_travelling_div').css('display','block');
          $('.departure_number_label').html(value+' Number');
        }
      }else{
        $('.departure_travelling_div').css('display','none');
      }
    });

    $('#organise_your_trip').parsley();
    $(document).on('submit', "#organise_your_trip", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();
      var token = "{{ csrf_token() }}";
      var formData = new FormData(this);
      formData.append('_token', token);
      $.ajax({
        url: '{{ route("web.organise_your_trip") }}',
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
              // window.location.replace("{{ url('/guest-area/checkin') }}"+'/'+res.booking_token);
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

    /*OTP Start */
    $('#checkin_login_form').parsley();
    $(document).on('submit', "#checkin_login_form", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();
      var token = "{{ csrf_token() }}";
      var formData = new FormData(this);
      formData.append('_token', token);
      $.ajax({
        url: '{{ route("web.checkin_login_form") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
          if (res.status === true) {
            // toastr.success(res.message);
            setInterval(function () {  
              window.location.replace("{{ url('/guest-area/checkin') }}"+'/'+res.booking_token);
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

	$(document).ready(function(){
		$(".dob").datepicker({
      endDate:new Date(),
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true
			// minDate:"-1Y",
          // maxDate:"+0D",
          // numberOfMonths: 1,
          // dateFormat:'dd-mm-yy',
          // yearRange: "-100:+0",
          // changeMonth: true,
          // changeYear: true,
			// defaultDate: '-1Y', //Default to One Year Ago to show 12 months
			// onSelect: function(selected) {
			// 	$(".start_date").datepicker("option","maxDate", selected)
			// }
		});
	});
</script>
@endsection