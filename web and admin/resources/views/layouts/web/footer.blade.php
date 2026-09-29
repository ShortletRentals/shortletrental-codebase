@include('layouts.web.modal')
<?php 
  use App\Models\AdminSettings;
  use App\Models\Country;
  $settingData = AdminSettings::get();
  // dd($title);
?>
<div class="button-fix">
  <a href="{{ url('guest-chat') }}" class="message-right">Message</a>
</div>

<?php if (isset($page) && $page == 'homepage') { ?>
  <div class="social_left_fix">
      <div class="social-list">
        <ul>
          <li>
            <a href="{{ isset($settingData) && !empty($settingData[0]->facebook_url) ? $settingData[0]->facebook_url : '' }}" target="_blank">
              <img src="{{ URL::asset('assets/web/img/shortlet/facebook.svg')}}" alt="facebook">
            </a>
          </li>
          <li>
            <a href="{{ isset($settingData) && !empty($settingData[0]->twitter_url) ? $settingData[0]->twitter_url : '' }}" target="_blank">
              <img src="{{ URL::asset('assets/web/img/shortlet/twitter.svg')}}" alt="Twitter">
            </a>
          </li>
          <li>
            <a href="{{ isset($settingData) && !empty($settingData[0]->instagram_url) ? $settingData[0]->instagram_url : '' }}" target="_blank">
              <img src="{{ URL::asset('assets/web/img/shortlet/instagram.svg')}}" alt="Instagram">
            </a>
          </li>
          <li>
            <a href="{{ isset($settingData) && !empty($settingData[0]->whatsup_url) ? $settingData[0]->whatsup_url : '' }}" target="_blank">
              <img src="{{ URL::asset('assets/web/img/shortlet/whatsapp.svg')}}" alt="Whatsapp">
            </a>
          </li>
        </ul>
      </div>
  </div>
<?php } ?>
<footer>
  <div class="footer-top">
    <div class="footer_remove">
      <a href="javascript:void(0)" class="btn_cross">
        <svg xmlns="http://www.w3.org/2000/svg" width="60.146" height="60.146" viewBox="0 0 60.146 60.146">
          <g id="_icons" transform="translate(-5 -5)">
            <path id="Path_3962" data-name="Path 3962" d="M6.289,63.858A3.9,3.9,0,0,0,9.3,65.146,3.9,3.9,0,0,0,12.3,63.858l22.77-22.77,22.77,22.77a4.153,4.153,0,0,0,6.015,0,4.153,4.153,0,0,0,0-6.015l-22.77-22.77L63.858,12.3a4.153,4.153,0,0,0,0-6.015,4.153,4.153,0,0,0-6.015,0l-22.77,22.77L12.3,6.289a4.153,4.153,0,0,0-6.015,0,4.153,4.153,0,0,0,0,6.015l22.77,22.77L6.289,57.843A4.153,4.153,0,0,0,6.289,63.858Z" transform="translate(0 0)"/>
          </g>
        </svg>

      </a>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-6">
          <div class="footer-left">
              <div class="footer-logo">
                  <a href="{{ route('web.home') }}">
                    <img src="{{ URL::asset('assets/web/img/logo.png')}}" class="logo"> 
                  </a>
              </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
          <div class="footer-center">
            <div class="footer-cont">
              <h2>Quick Links</h2>
              <div class="footer-link">
                <ul>
                  <li>
                    <a href="{{ route('web.about-us') }}">About Us</a>
                  </li>
                  <li>
                    <a href="{{ route('web.contact-us') }}">Contact Us</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
          <div class="footer-center">
            <div class="footer-cont">
              <h2>Help</h2>
              <div class="footer-link">
                <ul class="mb-0">
                  <li>
                    <a href="{{ route('web.privacy-policy') }}">Privacy policy</a>
                  </li>
                  <li>
                    <a href="{{ route('web.help-center') }}">Help Center</a>
                  </li>
                  <li>
                    <a href="{{ route('web.cancellation-policy') }}">Cancellation Policy</a>
                  </li>
                  <li>
                    <a href="{{ route('web.how-it-works') }}">How it works</a>
                  </li>
                  <li>
                    <a href="{{ route('web.term-of-use') }}">Terms of use</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-6">
          <div class="footer-center">
            <div class="footer-cont">
              <h2>Contact Us</h2>
              <div class="footer-link">
                <a href="mailto:info@shortletrentals.com"> <span><img src="{{ URL::asset('assets/web/img/shortlet/mail.svg')}}" alt="mail"></span>{{ isset($settingData) && !empty($settingData[0]->email) ? $settingData[0]->email : 'info@shortletrentals.com' }}</a>
                <a href="tel:+234 9122877657"> <span><img src="{{ URL::asset('assets/web/img/shortlet/call.svg')}}" alt="call"></span>{{ isset($settingData) && !empty($settingData[0]->country_code) ? '+'.$settingData[0]->country_code : ''}} {{isset($settingData) && !empty($settingData[0]->mobile) ? $settingData[0]->mobile : '' }}</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="footer-right">
            <div class="footer-cont">
              <h2>Subscribe Our Newsletter</h2>
              <p>Subscribe To Our Newsletter To Get The Latest Updates.</p>
              <form method="POST" action="" class="subscribe-form" enctype="" id="add_subscription">
                @csrf
                <div class="form-group">
                  <input type="text" name="email" placeholder="Email" data-parsley-required="true" data-parsley-pattern="^[a-z0-9][-a-z0-9._]+@([-a-z0-9]+[.])+[a-z]{2,5}$" data-parsley-pattern-message="Please enter valid email address" class="form-control">
                  <div class="subscribe-now">
                    <input type="submit" name="" value="Subscribe Now" class="btn primary_btn">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="copyright_sec">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="copyright_cont">
              <h5>© Shortlet Rentals Ltd <script>document.write(new Date().getFullYear())</script>. | All Rights Reserved</h5>
            </div>
          </div>
          <div class="col-md-6">
            <div class="copyright-right">
                <div class="social-media d-md-none">
                  <ul>
                      <li>
                        <a href="{{ isset($settingData) && !empty($settingData[0]->facebook_url) ? $settingData[0]->facebook_url : '' }}" target="_blank">
                          <img src="{{ URL::asset('assets/web/img/shortlet/facebook.svg')}}" alt="facebook">
                        </a>
                      </li>
                      <li>
                        <a href="{{ isset($settingData) && !empty($settingData[0]->twitter_url) ? $settingData[0]->twitter_url : '' }}" target="_blank">
                          <img src="{{ URL::asset('assets/web/img/shortlet/twitter.svg')}}" alt="twitter">
                        </a>
                      </li>
                      <li>
                        <a href="{{ isset($settingData) && !empty($settingData[0]->instagram_url) ? $settingData[0]->instagram_url : '' }}" target="_blank">
                          <img src="{{ URL::asset('assets/web/img/shortlet/instagram.svg')}}" alt="instagram">
                        </a>
                      </li>
                      <li>
                        <a href="{{ isset($settingData) && !empty($settingData[0]->whatsup_url) ? $settingData[0]->whatsup_url : '' }}" target="_blank">
                          <img src="{{ URL::asset('assets/web/img/shortlet/whatsapp.svg')}}" alt="whatsapp">
                        </a>
                      </li>
                  </ul>
                </div>
                <div class="payment-option">
                  <ul>
                      <li>
                        <a href="#">
                          <img src="{{ URL::asset('assets/web/img/shortlet/bank-transfer.svg')}}" alt="Bank Transfar">
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <img src="{{ URL::asset('assets/web/img/shortlet/mastercard.svg')}}" alt="Master Card">
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <img src="{{ URL::asset('assets/web/img/shortlet/visa.svg')}}" alt="Visa">
                        </a>
                      </li>
                  </ul>
                </div>
                <div class="footer_toggle_cls">
                  <a href="javascript:void(0)" class="footer_tgl">
                    Support & resources 
                    <svg xmlns="http://www.w3.org/2000/svg" width="20.539" height="11.519" viewBox="0 0 20.539 11.519">
                      <path id="_9042707_nav_arrow_down_icon" data-name="9042707_nav_arrow_down_icon" d="M6,17.5,14.5,9,23,17.5" transform="translate(-4.232 -7.75)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
                    </svg>
                  </a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
</footer>
<script src="{{ asset('js/parsley.min.js') }}"></script>
<script>
  $('#add_subscription').parsley();
  $(document).on('submit', "#add_subscription", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.subscription") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === 1) {
          toastr.success(res.message);
          $('#add_subscription')[0].reset();
          $('#add_subscription').parsley().reset();
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