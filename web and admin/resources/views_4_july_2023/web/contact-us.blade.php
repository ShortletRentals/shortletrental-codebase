@extends('layouts.web.master')

@section('content')
    <main>
	    <section class="contact_page space-cls">
	        <div class="container">
	        	<div class="contact_inner">
              <div class="row">
                <div class="col-md-8">
                  <div class="contact_form">
                    <h3 class="heading-inner-title">Contact Us</h3>
                    <form method="POST" action="" class="booking-form" enctype="" id="add_contact">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="first_name" placeholder="First Name" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="last_name" placeholder="Last Name" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="city" placeholder="City" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="state" placeholder="State/Province" data-parsley-required="true" data-parsley-minlength="3" data-parsley-pattern="^[A-Za-z ]+$" data-parsley-pattern-message="This field is filled only with alphabets and space." class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="mobile_number" placeholder="Mobile Number" data-parsley-required="true" data-parsley-pattern="^[0-9 ]{8,15}$" data-parsley-pattern-message="Please enter valid mobile number" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" name="email" placeholder="Email" data-parsley-required="true" data-parsley-pattern="^[a-z0-9][-a-z0-9._]+@([-a-z0-9]+[.])+[a-z]{2,5}$" data-parsley-pattern-message="Please enter valid email address" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <textarea id="" name="message" rows="4" placeholder="Your Message" data-parsley-required="true" data-parsley-minlength="5" placeholder="{{__('backend.Message')}}" class="form-control"></textarea>
                          </div>
                        </div>
                        <div class="col-md-12">
                            <div class="terms-sec">
                              <label class="custom_checkbox">
                                <input type="checkbox" name="policy" data-parsley-required="true" >
                                <span class="checkmark"></span>
                                I have read and accepted the  privacy policy and general conditions
                              </label>
                            </div>
                            <div class="terms-sec">
                              <label class="custom_checkbox">
                                <input type="checkbox" name="commercial_info" data-parsley-required="true" >
                                <span class="checkmark"></span>
                                Acceptance to receive commercial information
                              </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                              <input type="submit" name="" value="Send" class="btn primary_btn">
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="contact-right">
                    <div class="contact-bg">
                      <div class="contact-list">
                        <div class="contact-icon">
                          <img src="{{ URL::asset('assets/web/img/contact-1.png')}}" alt="Home">
                        </div>
                        <div class="contact-cont">
                          <p>{{ isset($settingData) && !empty($settingData[0]->title) ? $settingData[0]->title : '' }}</p>
                        </div>
                      </div>
                      <div class="contact-list">
                        <div class="contact-icon">
                          <img src="{{ URL::asset('assets/web/img/contact-2.png')}}" alt="Address">
                        </div>
                        <div class="contact-cont">
                          <p>Lekki Phase 1, Lagos Nigeria</p>
                        </div>
                      </div>
                      <div class="contact-list">
                        <div class="contact-icon">
                          <img src="{{ URL::asset('assets/web/img/contact-3.png')}}" alt="Address">
                        </div>
                        <div class="contact-cont">
                          <a href="tel:{{ isset($settingData) && !empty($settingData[0]->country_code) ? '+'.$settingData[0]->country_code : '' }} {{ isset($settingData) && !empty($settingData[0]->mobile) ? $settingData[0]->mobile : '' }}">{{ isset($settingData) && !empty($settingData[0]->country_code) ? '+'.$settingData[0]->country_code : '' }} {{ isset($settingData) && !empty($settingData[0]->mobile) ? $settingData[0]->mobile : '' }}</a>
                        </div>
                      </div>
                      <div class="contact-list">
                        <div class="contact-icon">
                          <img src="{{ URL::asset('assets/web/img/contact-4.png')}}" alt="Address">
                        </div>
                        <div class="contact-cont">
                          <a href="mailto:{{ isset($settingData) && !empty($settingData[0]->email) ? $settingData[0]->email : 'info@shortletrentals.com' }}">{{ isset($settingData) && !empty($settingData[0]->email) ? $settingData[0]->email : 'info@shortletrentals.com' }}</a>
                        </div>
                      </div>
                      <div class="contact-social d-flex align-items-center">
                        <div class="social-left">
                          <h4>Social Media:</h4>
                        </div>
                        <div class="social-link ms-auto">
                          <ul>
                            <li><a href="{{ isset($settingData) && !empty($settingData[0]->facebook_url) ? $settingData[0]->facebook_url : '' }}" target="_blank"><img src="{{ URL::asset('assets/web/img/social-f.png')}}" alt="Facebook"></a></li>
                            <li><a href="{{ isset($settingData) && !empty($settingData[0]->twitter_url) ? $settingData[0]->twitter_url : '' }}" target="_blank"><img src="{{ URL::asset('assets/web/img/social-t.png')}}" alt="Twitter"></a></li>
                            <li><a href="{{ isset($settingData) && !empty($settingData[0]->instagram_url) ? $settingData[0]->instagram_url : '' }}" target="_blank"><img src="{{ URL::asset('assets/web/img/social-i.png')}}" alt="Instagram"></a></li>
                            <li><a href="{{ isset($settingData) && !empty($settingData[0]->whatsup_url) ? $settingData[0]->whatsup_url : '' }}" target="_blank"><img src="{{ URL::asset('assets/web/img/social-w.png')}}" alt="Whatsapp"></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
            <div class="contact-map">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1465667.6741617478!2d7.120038189595075!3d9.670463454963356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0baf7da48d0d%3A0x99a8fe4168c50bc8!2sNigeria!5e0!3m2!1sen!2sin!4v1668000370753!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
	        </div>
	    </section>
    </main>
    <div class="button-fix">
    	<a href="#" class="message-right">Message</a>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/parsley.min.js') }}"></script>
    <script>
      $('#add_contact').parsley();
      $(document).on('submit', "#add_contact", function(e) {
        e.preventDefault();
        var _this = $(this);
        $('#group_loader').fadeIn();
        var formData = new FormData(this);
        $.ajax({
          url: '{{ route("web.save-contact-us") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {
            if (res.status === 1) {
              toastr.success(res.message);
              $('#add_contact')[0].reset();
              $('#add_contact').parsley().reset();
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