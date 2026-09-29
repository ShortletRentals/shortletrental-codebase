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
                            <div class="qr_code_img">
                              <img src="{{ URL::asset('assets/web/img/qr-code.png') }}">
                            </div>                          
                            <div class="qr_link">
                              <a href="#">https://www.shortletrentals.com/guest-area/checkin/MTY3NzYzMjgjOGNlMmQ1MDU0Yg/guests</a>
                              <span><img src="{{ URL::asset('assets/web/img/copy.png') }}"></span>
                            </div>
                          </div>
                          <div class="d-flex mt-4">
                            <a href="{{ url('/guest-area/checkin/upload/').'/'.$data->booking_token }}" class="btn">Back</a>
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
      formData.append('document_type',sessionStorage.getItem("document_type"));
      formData.append('upload_type',sessionStorage.getItem("upload_type"));
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
			// minDate:"-1Y",
			maxDate:"+0D",
			numberOfMonths: 1,
			dateFormat:'dd-mm-yy',
			yearRange: "-100:+0",
			changeMonth: true,
			changeYear: true,
			// defaultDate: '-1Y', //Default to One Year Ago to show 12 months
			// onSelect: function(selected) {
			// 	$(".start_date").datepicker("option","maxDate", selected)
			// }
		});
	});
</script>
@endsection