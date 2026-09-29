@extends('layouts.web.master')

@section('content')
    <main>
      <section class="property-sec">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 grad-bg">
                <div class="checkin-img">
                  <img src="{{ URL::asset('assets/web/img/check_in.png') }}" alt="">
                </div>
            </div>
            <div class="col-md-6">
              <div class="checkin_main_wrap">
                <div class="checkin-cont">
                  <h3>Check-in</h3>
                  <form class="booking-form checkin_login_form" id="checkin_login_form">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Booking Reference</label>
                          <input type="text" name="booking_id" placeholder="Booking Reference" class="form-control" data-parsley-required="true">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Main Guest's Last Name</label>
                          <input type="text" name="last_name" placeholder="Main Guest's Last Name" class="form-control" data-parsley-required="true">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="d-flex">
                          <input type="submit" value="Next" class="btn primary_btn">
                          <!-- <a href="javascript:void(0)" class="btn primary_btn login_next">Next</a> -->
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="find-cont">
                    <h4>Where do I find them?</h4>
                    <p>Both the booking reference and the Main Guest's last name can be found <b>in the booking confirmation email.</b></p>
                    <p>Be sure to write them down as they appear on it, otherwise we can't find your booking.</p>
                    <p>Here's an example of a booking reference: <b>10893815</b></p> 
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
  // $(document).on('click', ".login_next", function(e) {
  //   var booking_id = $('input[name="booking_id"]').val();
  //   var last_name = $('input[name="last_name"]').val();
  //   if(booking_id != undefined && last_name != undefined){
  //       sessionStorage.setItem("host_type", type);
  //       // alert(type);
  //       window.location.replace("{{ url('host_category') }}"+'/'+type);
  //     // }
  //   }else{
  //     alert('Please select any one type.');
  //     return false;
  //   }
  // });

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
</script>
@endsection