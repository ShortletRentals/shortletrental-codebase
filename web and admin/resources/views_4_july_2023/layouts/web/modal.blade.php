<style>
  .login-inner .review-option label.star {
    line-height: 1;
    margin-bottom: 0;
  }
  .review-option.stars.parsley-error {
    border: 0 !important;
  }
  .review-option.stars.parsley-error label.star {
    color: #f00;
  }
  .review-option.stars.parsley-error + ul.parsley-errors-list {
    bottom: -5px;
    padding-left: 10px !important;
  }

  .pop-up-info {
    background-color: #fff;
    border-radius: 3px;
    box-shadow: 0 0 20px 0 rgba(0,0,0,.05);
    display: none;
    flex-direction: column;
    left: 0;
    margin: 0 auto;
    max-height: calc(100vh - 32px);
    max-width: 500px;
    min-height: 200px;
    padding: 0 58px 58px;
    position: fixed;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1001;
  }

  .pop-up-info .title {
    color: #404040;
    display: block;
    font-size: 16px;
    font-weight: 600;
    line-height: 28px;
    margin: 48px 0 12px 0;
  }
  .cancellation-conditions > .pop-up-info > p {
    color: #444;
    font-family: "Open Sans";
    font-size: 15px;
    line-height: 20px;
    margin-top: 24px;
  }
  .cancellation-case.free-cancellation {
    border-left-color: #129e65;
  }
  .cancellation-case {
    border-left: 16px solid transparent;
      border-left-color: transparent;
    display: flex;
    flex-direction: column;
    padding: 0 0 56px 16px;
    position: relative;
  }
.cancellation-case::after {
  background-color: #fff;
  border-radius: 100%;
  bottom: -4px;
  box-shadow: 1px 3px 3px 0 rgba(0,0,0,.1);
  content: "";
  height: 8px;
  left: -12px;
  position: absolute;
  width: 8px;
  z-index: 1;
}
.cancellation-case.cases-2:nth-of-type(2) {
  border-left-color: #ff6c51;
}
.cancellation-case {
  border-left: 16px solid transparent;
    border-left-color: transparent;
  display: flex;
  flex-direction: column;
  padding: 0 0 56px 16px;
  position: relative;
}
.cancellation-case > span:first-of-type {
  color: #4a4a4a;
  font-weight: 600;
  margin-bottom: 8px;
}
.cancellation-case > span {
  font-family: "Open Sans";
  font-size: 15px;
  line-height: 16px;
}
.cancellation-case.no-show {
  border-left-color: rgba(216,216,216,.4);
}
.cancellation-case {
  border-left: 16px solid transparent;
    border-left-color: transparent;
  display: flex;
  flex-direction: column;
  padding: 0 0 56px 16px;
  position: relative;
}
.cancellation-conditions > .pop-up-info > #politicaCancelacion {
  overflow-y: auto;
}
.cancellation-conditions > .pop-up-info > div {
  margin-top: 56px;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/css/select2.min.css">
<?php
use App\Models\Country;
use App\Models\Province;
$country_phone = Country::orderBy('name','asc')->where('status','1')->get();
$province = Province::where(['status'=>1])->orderBy('position', 'asc')->get();
$country = Country::where(['status'=>1])->orderBy('name', 'asc')->get();
// dd($country_phone);
$auth_user = Session::get('AuthUserData');
if (isset($auth_user)) {
  $userId = isset($auth_user) && !empty($auth_user->data->id) ? $auth_user->data->id : '';
  // dd($userId);
}
?>
<!-- Modal -->
    <div class="modal_main_cls">
      <!-- CancellationPolicy -->
      <div class="modal fade login-sec" id="exampleCancellationPolicy" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="login-inner">
                <div class="pop-up-info" style="display: flex;">
                  <span class="title">Cancellation policies</span>
                  <p id="applyCancellationPolicy">In case of cancellation the following charges will apply:</p>
                  <div id="politicaCancelacion">
                    <div class="cancellation-case free-cancellation">
                      <span>From the booking date until 15 days before check-in</span> 
                      <span>0% of the total rent</span>
                    </div>
                    <div class="cancellation-case cases-2">
                      <span>From 14 days before, until the check-in</span> 
                      <span>50% of the total rent</span>
                    </div>
                    <div class="cancellation-case no-show">
                      <span>No-show</span> 
                      <span>100% of the total rent</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- rating review -->
      <div class="modal fade login-sec" id="ratingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                          <h2>Give Review and Ratings</h2>
                        </div>
                        <form method="POST" action="" class="login-form" enctype="" id="rating_form">
                          @csrf
                          <input type="hidden" name="order_id" id="rate_order_id" value="">
                          <div class="form-group mb-2">
                            <div class="review-option stars">
                              <input class="star star-5" value="5" id="star-5-2" data-parsley-required="true" type="radio" name="star"/>
                              <label class="star star-5" for="star-5-2"></label>
                              <input class="star star-4" value="4" id="star-4-2" data-parsley-required="true" type="radio" name="star"/>
                              <label class="star star-4" for="star-4-2"></label>
                              <input class="star star-3" value="3" id="star-3-2" data-parsley-required="true" type="radio" name="star"/>
                              <label class="star star-3" for="star-3-2"></label>
                              <input class="star star-2" value="2" id="star-2-2" data-parsley-required="true" type="radio" name="star"/>
                              <label class="star star-2" for="star-2-2"></label>
                              <input class="star star-1" value="1" id="star-1-2" data-parsley-required="true" type="radio" name="star"/>
                              <label class="star star-1" for="star-1-2"></label>
                            </div> 
                          </div>
                          <div class="form-group">
                            <textarea class="form-control" name="review" rows="5" placeholder="Write Your Review..."></textarea>
                          </div>
                          <div class="form-group mb-0">
                            <input type="submit" class="btn primary_btn" value="Submit" name="">
                          </div>
                        </form>
                    </div>
            </div>
          </div>
        </div>
      </div>
      <!-- rating review -->
      <div class="modal fade login-sec" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="login-tab">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#guest">Guest Login</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('admin/login') }}">Partner Login</a>
                    <!-- <a class="nav-link" data-bs-toggle="tab" href="#host">Partner Login</a> -->
                  </li>
                </ul>
              </div>
              <div class="login-tab-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="guest">
                    <div class="login-inner">
                        <div class="sign-title">
                          <h2>Login</h2>
                          <p>Welcome Back To Shortlet Rentals</p>
                        </div>
                        <form method="POST" action="" class="login-form" enctype="" id="login_form">
                          @csrf
                          <input type="hidden" name="type" value="Mobile">
                          <div class="col-md-12">
                            <div class="form-group select-country">
                              <div class="input-group exampleModal1">
                                <!-- <select name="country_code" class="form-control login_country_code country_code select2" id='country_code' data-parsley-required="true">
                                  @if(!empty($country_phone))
                                    <?php $selected = isset($_COOKIE["web_country_code"]) ? $_COOKIE["web_country_code"] : '234' ; ?>
                                    @foreach($country_phone as $key => $country_ph)
                                      <option value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == $selected){ echo 'selected'; } ?>>{{$country_ph->emoji}} {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                      <option data-thumbnail="{{url('public/images/country_image/AD.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == $selected){ echo 'selected'; } ?>> {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                    @endforeach
                                  @endif
                                </select> -->
                                <select name="country_code" class="fform-control country_code forget_country_code select_img_country1" id='country_code' data-parsley-required="true">
                                  @if(!empty($country_phone))
                                    <?php $selected = isset($_COOKIE["web_country_code"]) ? $_COOKIE["web_country_code"] : '234' ; ?>
                                    @foreach($country_phone as $key => $country_ph)
                                      <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == $selected){ echo 'selected'; } ?>> {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                    @endforeach
                                  @endif
                                </select>
                                <input type="text" name="mobile" value="<?php if(isset($_COOKIE["web_mobile"])) { echo $_COOKIE["web_mobile"]; } ?>" data-parsley-required="true" placeholder="Mobile Number" class="form-control" onkeypress="return onlyNumberKey(event)">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group input-group" id="show_hide_password">
                              <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                                <i class='fa fa-eye-slash'></i>
                              </a>
                              <input type="password" value="<?php if(isset($_COOKIE["web_password"])) { echo $_COOKIE["web_password"]; } ?>" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password"  data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters."> 
                            </div>
                          </div>
                          <!-- <div class="col-md-12">
                            <div class="form-group">
                              <input type="password" name="password" data-parsley-required="true" placeholder="Password" class="form-control">
                            </div>
                          </div> -->
                          <div class="remember_sec">
                            <div class="remember_left">
                                <label class="custom_checkbox">
                                  <input name="remember_me" type="checkbox" {{isset($_COOKIE["web_remember_me"]) ? "checked" : ""}} >
                                  <span class="checkmark"></span>
                                  Remember Me
                                </label>
                            </div>
                            <div class="forgot-cls">
                              <div class="forgot-cont">
                                <a href="#">Forgot Password</a>
                              </div>
                            </div>
                          </div>
                          <div class="login-btn-cls">
                              <input type="submit" name="" value="Login" class="btn primary_btn">
                              <!-- <a href="#" class="primary_btn">Login</a> -->
                          </div>
                          <div class="account-regester">
                            <p>If you Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#SelectModal">Sign Up</a></p>
                            <p><a href="#" data-bs-toggle="modal" data-bs-target="#ForgetPasswordModal">Forget Password</a></p>
                            <!-- <p>Want to login from Host? <a href="{{ url('admin/login') }}">Host Login</a></p> -->
                          </div>  
                        </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade select-sec" id="SelectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Select One</h2>
                    <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="select-box-cls">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#SignupModal" class="btn-border">
                          <span>
                            <div class="select-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/s-1.svg')}}" alt="">
                            </div>
                            <div class="select-cont">
                                <h4>Create guest <span class="d-block">account</span></h4>
                            </div>
                          </span>
                        </a>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="select-box-cls">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#BecomePartnerModal" class="btn-border">
                          <span>
                            <div class="select-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/s-2.svg')}}" alt="">
                            </div>
                            <div class="select-cont">
                                <h4>Create partner <span class="d-block">account</span></h4>
                            </div>
                          </span>
                        </a>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="select-box-cls">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#BecomeAHostModal" class="btn-border">
                          <span>
                            <div class="select-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/s-3.svg')}}" alt="">
                            </div>
                            <div class="select-cont">
                                <h4>Become a <span class="d-block">Host</span></h4>
                            </div>
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!--Forgot Password Modal Start-->
      <div class="modal fade select-sec" id="ForgetPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Forget Password</h2>
                    <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
                  </div>
                  
                  <form method="POST" action="" class="forget-password-form row" enctype="" id="forget_password_form">
                    @csrf
                    <!-- <div class="col-md-12">
                      <div class="form-group">
                        <input type="text" name="fullname" placeholder="Full Name*" class="form-control txtOnly" data-parsley-required="true"> 
                      </div>
                    </div> -->
                    
                    <div class="col-md-12">
                      <div class="form-group select-country">
                          <div class="input-group ForgetPasswordModal1">
                            <select name="country_code" class="form-control country_code forget_country_code select_img_country2" id='country_code' data-parsley-required="true">
                              @if(!empty($country_phone))
                                @foreach($country_phone as $key => $country_ph)
                                  <!-- <option value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->emoji}} {{$country_ph->sortname}} +{{$country_ph->phonecode}}</option> -->
                                  <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                @endforeach
                              @endif
                            </select>
                            <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control forget_mobile" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                          </div>
                      </div>
                    </div>

                    <div class="remember_sec">
                      <div class="remember_left">
                          <input type="submit" name="" value="Send" class="btn primary_btn">
                          <!-- <a href="#" class="primary_btn" data-bs-toggle="modal" data-bs-target="#OTPModal">Sign up</a>   -->
                      </div>
                    </div>
                    <div class="account-regester">
                      <p>If you already have an account? <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</a></p>
                    </div>  
                  </form>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!--Forgot Password Modal End-->
      <div class="modal fade select-sec" id="SignupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Sign Up</h2>
                    <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> -->
                  </div>
                    <form method="POST" action="" class="login-form row" enctype="" id="customer_signup_form">
                      @csrf
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" name="fullname" placeholder="Enter Name*" class="form-control txtOnly" data-parsley-required="true"> 
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" name="surname" placeholder="Enter Surname" class="form-control txtOnly">
                        </div>
                      </div>
                      
                      <div class="col-md-6">
                        <div class="form-group select-country">
                            <div class="input-group SignupModal1">
                              <select name="country_code" class="form-control country_code select_img_country3" id='country_code' data-parsley-required="true">
                                @if(!empty($country_phone))
                                  @foreach($country_phone as $key => $country_ph)
                                    <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                  @endforeach
                                @endif
                              </select>
                              <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                            </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="text" name="email" placeholder="Email Address*" class="form-control" data-parsley-required="true" data-parsley-type="email" >
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group input-group" id="show_hide_password">
                          <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                            <i class='fa fa-eye-slash'></i>
                          </a>
                          <input type="password" value="" name="password" class="form-control border-end-0" id="inputChoosePasswordSignUp" placeholder="Enter Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters."> 
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group input-group" id="show_hide_password1">
                          <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                            <i class='fa fa-eye-slash' id="togglePassword"></i>
                          </a>
                          <input type="password" value="" name="confirm_password" class="form-control border-end-0" id="confirm_password" placeholder="Enter Confirm Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-equalto="#inputChoosePasswordSignUp"> 
                        </div>
                      </div>
                      <div class="remember_sec">
                        <div class="remember_left">
                            <input type="submit" name="" value="Sign up" class="btn primary_btn customer_signup_btn">
                            <!-- <a href="#" class="primary_btn" data-bs-toggle="modal" data-bs-target="#OTPModal">Sign up</a>   -->
                        </div>
                        <div class="forgot-cls">
                            <div class="signup-social">
                              <ul>
                                <li>
                                  <a href="{{ route('user.auth.redirect-tofacebook') }}">
                                    <img src="{{ URL::asset('assets/web/img/f-1.png')}}" alt="">
                                  </a>
                                </li>
                                <li>
                                  <a href="{{ route('user.auth.redirect-togoogle') }}">
                                    <img src="{{ URL::asset('assets/web/img/google.png')}}" alt="">
                                  </a>
                                </li>
                                <!-- <li>
                                  <a href="#">
                                    <img src="{{ URL::asset('assets/web/img/apple-logo.png')}}" alt="">
                                  </a>
                                </li> -->
                              </ul>
                            </div>
                        </div>
                      </div>
                      <div class="account-regester">
                        <p>If you already have an account? <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</a></p>
                      </div>  
                    </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--Change Password Modal Start-->
      <div class="modal fade add-card-popup" id="changePasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <h2>Change Password</h2>
                      </div>
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="update_password_form">
                        @csrf
                        <div class="form-group">
                          <label>Old Password</label>

                          <div class="input-group">
                            <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                              <i class='fa fa-eye-slash' id="toggleOldPasswordChange"></i>
                            </a>
                            <input type="password" name="old_password" placeholder="Password" class="form-control " id="old_password" data-parsley-required-message="Please enter your old password." data-parsley-required >
                          </div>
                        </div>
                        <div class="form-group show_hide_password">
                          <label>New Password</label>

                          <div class="input-group">
                            <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                              <i class='fa fa-eye-slash' id="togglePasswordChange"></i>
                            </a>
                            <input type="password" name="new_password" placeholder="New Password" data-parsley-minlength="[6]"  id="new_password" class="form-control" minlength="8" data-parsley-minlength="8"  data-parsley-required-message="Please enter your new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*"  data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required >
                          </div>
                        </div>
                        <div class="form-group confirm_hide_password">
                          <label>Confirm Password</label>

                          <div class="input-group">
                            <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                              <i class='fa fa-eye-slash' id="togglePasswordChange1"></i>
                            </a>
                            <input type="password" name="confirm_new_password" data-parsley-equalto="#new_password" data-parsley-minlength="[6]"  placeholder="Confirm-Password" class="form-control" id="confirm_new_password" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter your new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required data-parsley-equalto="#new_password" >
                          </div>
                        </div>
                        <div class="login-btn-cls">
                          <input type="submit" name="" value="Submit" class="btn primary_btn">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
      </div>
      <!--Change Password Modal End-->

      <!--Forget Update Password Modal Start-->
      <div class="modal fade add-card-popup" id="forgetUpdatePasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <h2>Update Password</h2>
                      </div>
                      <form method="POST" action="" class="forget-update-password-form row" enctype="" id="forget_update_password_form">
                        @csrf
                        <input type="hidden" name="country_code" class="forget_update_country_code1" />
                        <input type="hidden" name="mobile" class="forget_update_mobile1" />
                        <div class="form-group show_hide_password">
                          <label>New Password</label>

                          <div class="input-group">
                            <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                              <i class='fa fa-eye-slash' id="togglePasswordChangeForget"></i>
                            </a>
                            <input type="password" name="new_password" placeholder="New Password" data-parsley-minlength="[6]"  id="new_password_forget" class="form-control" minlength="8" data-parsley-minlength="8"  data-parsley-required-message="Please enter your new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*"  data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required >
                          </div>
                        </div>
                        <div class="form-group confirm_hide_password">
                          <label>Confirm Password</label>

                          <div class="input-group">
                            <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                              <i class='fa fa-eye-slash' id="togglePasswordChangeForget1"></i>
                            </a>
                            <input type="password" name="confirm_new_password" data-parsley-equalto="#new_password_forget" data-parsley-minlength="[6]"  placeholder="Confirm-Password" class="form-control" id="confirm_new_password_forget" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter your new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required data-parsley-equalto="#new_password_forget" >
                          </div>
                        </div>
                        <div class="login-btn-cls">
                          <input type="submit" name="" value="Update Password" class="btn primary_btn">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
      </div>
      <!--Forget Update Password Modal End-->

      <!--Add Card Modal Start-->
      <div class="modal fade add-card-popup" id="shareBookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog ">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="login-inner">
                      <div class="sign-title">
                        <h2>Share Booking</h2>
                      </div>
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="share_booking_form">
                        @csrf
                        <input type="hidden" name="booking_id" class="share_booking_id">
                        <div class="col-md-12">
                          <div class="form-group select-country">
                            <div class="input-group shareBookingModal1">
                              <select name="country_code" class="form-control country_code select_img_country4" id='country_code' data-parsley-required="true">
                                @if(!empty($country_phone))
                                  @foreach($country_phone as $key => $country_ph)
                                    <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                                  @endforeach
                                @endif
                              </select>
                              <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control share_mobile_number" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                            </div>
                          </div>
                        </div>
                        <!-- <div class="form-group">
                          <label>Mobile Number*</label>
                          <input type="text" name="mobile_number" placeholder="Enter Mobile Number" class="form-control" minlength="8" maxlength="15" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                        </div> -->
                        <div class="login-btn-cls">
                          <input type="submit" name="" value="Share" class="btn primary_btn">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
      </div>
      <!--Add Card Modal End-->

      <!--Add Card Modal Start-->
      <div class="modal fade add-card-popup" id="AddCard" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <h2>My Cards</h2>
                      </div>
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="card_form">
                        @csrf
                        <div class="form-group">
                          <label>Card Holder Name</label>
                          <input type="text" name="card_holder_name" placeholder="Card Holder Name" class="form-control" data-parsley-required="true">
                        </div>
                        <div class="form-group">
                          <label>Card Number</label>
                          <input type="text" name="card_number" placeholder="xxxx xxxx xxxx xxxx" class="form-control" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                        </div>
                        <div class="form-group">
                          <label>Expiry Date</label>
                          <div class="row">
                            <div class="col-md-6">
                              <input type="text" name="month" placeholder="03" class="form-control" minlength="2" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="year" placeholder="2024" class="form-control" minlength="4" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>CVV</label>
                          <input type="text" name="cvv" placeholder="xxx" class="form-control" minlength="3" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                        </div>
                        <div class="login-btn-cls">
                          <input type="submit" name="" value="Add" class="btn primary_btn">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
      </div>
      <!--Add Card Modal End-->
      <!--Edit Card Modal Start-->
        <div class="modal fade add-card-popup" id="EditCard" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <h2>Edit Card</h2>
                      </div>
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="edit_card_form">
                        @csrf
                        <input type="hidden" name="card_id" class="card_id" value="">
                        <div class="form-group">
                          <label>Card Holder Name</label>
                          <input type="text" name="card_holder_name" placeholder="Card Holder Name" class="form-control card_holder_name" data-parsley-required="true">
                        </div>
                        <div class="form-group">
                          <label>Card Number</label>
                          <input type="text" name="card_number" placeholder="xxxx xxxx xxxx xxxx" class="form-control card_number" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                        </div>
                        <div class="form-group">
                          <label>Expiry Date</label>
                          <div class="input-group">
                            <input type="text" name="month" placeholder="03" class="form-control month" minlength="2" maxlength="2" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                            <input type="text" name="year" placeholder="2024" class="form-control year" minlength="4" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                          </div>
                        </div>
                        <div class="form-group">
                          <label>CVV</label>
                          <input type="text" name="cvv" placeholder="xxx" class="form-control cvv" minlength="3" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');" data-parsley-required="true">
                        </div>
                        <div class="login-btn-cls">
                          <input type="submit" name="" value="Submit" class="btn primary_btn">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      <!--Edit Card Modal End-->

      <!--OTP Modal Start-->
      <div class="modal fade login-sec" id="OTPModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  <h2>OTP Verification</h2>
                  <!-- <p class="otp_number">Enter The OTP sent to + 00 22 11 33 44</p> -->
                  <p class="otp_number">Enter The OTP which is sent to your mobile number</p>
                </div>
                <form method="POST" action="" class="login-form row digit-group-otp" enctype="" id="otp_form">
                  @csrf
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="otp_one" id="otp_one" data-next="otp_two" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');"> 
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="otp_two" id="otp_two" data-next="otp_three" data-previous="otp_one" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="otp_three" id="otp_three" data-next="otp_four" data-previous="otp_two" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <input type="text" name="otp_four" id="otp_four" data-previous="otp_three" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                  </div>
                  <div class="remember_sec">
                    <div class="remember_left">
                      <input type="submit" name="" value="Verify & Proceed" class="btn primary_btn otp_verify_btn">
                      <!-- <a href="#" class="primary_btn">Verify & Proceed</a>   -->
                    </div>
                    <!-- <div class="forgot-cls">
                      <span class="second-cls">00:59</span>  
                    </div> -->
                  </div>
                  <div class="account-regester">
                    <p>Don't Receive the OTP ? <a href="javascript:void(0)" id="resend_otp_form">Resend OTP</a></p>
                    <!-- <p>Don't Receive the OTP ? 
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="resend_otp_form">
                        @csrf
                        <input type="submit" value="Resend OTP">
                      </form>
                    </p> -->
                  </div>  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--OTP Modal End-->

      <!--Forget Password OTP Modal Start-->
      <div class="modal fade login-sec" id="ForgetOTPModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  <h2>OTP Verification</h2>
                  <!-- <p class="otp_number">Enter The OTP sent to + 00 22 11 33 44</p> -->
                  <p class="otp_number">Enter The OTP which is sent to your mobile number</p>
                </div>
                <form method="POST" action="" class="forget_password_otp_form" enctype="" id="forget_otp_form">
                  @csrf
                  <input type="hidden" name="country_code" class="forget_update_country_code" />
                  <input type="hidden" name="mobile" class="forget_update_mobile" />

                  <div class="digit-group-otp">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <input type="text" name="otp_one" id="otp_one" data-next="otp_two" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');"> 
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <input type="text" name="otp_two" id="otp_two" data-next="otp_three" data-previous="otp_one" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <input type="text" name="otp_three" id="otp_three" data-next="otp_four" data-previous="otp_two" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <input type="text" name="otp_four" id="otp_four" data-previous="otp_three" class="form-control" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                      </div>
                </div>
                    <div class="remember_sec">
                      <div class="remember_left">
                        <input type="submit" name="" value="Verify & Proceed" class="btn primary_btn forget_otp_verify_btn">
                        <!-- <a href="#" class="primary_btn">Verify & Proceed</a>   -->
                      </div>
                      <!-- <div class="forgot-cls">
                        <span class="second-cls">00:59</span>  
                      </div> -->
                    </div>
                  </div>
                  <div class="account-regester">
                    <p>Don't Receive the OTP ? <a href="javascript:void(0)" id="resend_otp_form">Resend OTP</a></p>
                    <!-- <p>Don't Receive the OTP ? 
                      <form method="POST" action="" class="login-form row digit-group" enctype="" id="resend_otp_form">
                        @csrf
                        <input type="submit" value="Resend OTP">
                      </form>
                    </p> -->
                  </div>  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Forget Password OTP Modal End-->

      <!--BecomeAHostModal Modal Start-->
      <div class="modal fade select-sec" id="BecomeAHostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Become A Host</h2>
                    <p>Personal information</p>
                  </div>
                  <form method="POST" action="" class="login-form row" enctype="" id="become_a_host_signup_form">
                    @csrf
                    <div class="col-md-2">
                      <label>Title</label>
                      <div class="form-group">
                        <select name="title" class="form-control title" id='title' data-parsley-required="true">
                          <option value="">-- Select Title--</option>
                          <option value="Company" >Company</option>
                          <option value="Dr" >Dr</option>
                          <option value="Family" >Family</option>
                          <option value="Mr_&_Mrs" >Mr & Mrs</option>
                          <option value="Mr" >Mr.</option>
                          <option value="Mrs" >Mrs.</option>
                          <option value="Ms" >Ms.</option>
                          <option value="PhD" >PhD</option>
                          <option value="Prof" >Prof</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Full Name*</label>
                        <input type="text" name="full_name" placeholder="Enter Full Name*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                      <div class="form-group radio-inner-w host_profile_radio">
                        <label>Gender*</label>
                        <div class="radio-main">
                          <label class="custom_radio_b">
                          <input type="radio" name="gender" value="Male" data-parsley-required="true">
                          <span class="checkmark"></span>Male
                          </label>
                          <label class="custom_radio_b">
                          <input type="radio" name="gender" value="Female" data-parsley-required="true">
                          <span class="checkmark"></span> Female
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="col-md-5">
                      <div class="form-group">
                        <input type="text" name="surname" placeholder="Enter Surname*" class="form-control" data-parsley-required="true">
                      </div>
                    </div> -->

                    <div class="col-md-5">
                      <div class="form-group">
                        <label>Email*</label>
                        <input type="email" name="email" placeholder="Email Address*" class="form-control" data-parsley-required="true" data-parsley-type="email">
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group select-country">
                        <label>Phone Number*</label>
                        <div class="input-group BecomeAHostModal1">
                          <select name="country_code" class="form-control country_code select_img_country5" id='country_code' data-parsley-required="true">
                            @if(!empty($country_phone))
                              @foreach($country_phone as $key => $country_ph)
                                <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                              @endforeach
                            @endif
                          </select>
                          <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                        <label>Password*</label>
                      <div class="form-group input-group" id="show_hide_password">
                        <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                          <i class='fa fa-eye-slash'></i>
                        </a>
                        <input type="password" value="" name="password" class="form-control border-end-0" id="inputChoosePasswordBecome" placeholder="Enter Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters."> 
                      </div>
                    </div>
                    <div class="col-md-6">
                        <label>Confirm Password*</label>
                      <div class="form-group input-group" id="show_hide_password2">
                        <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                          <i class='fa fa-eye-slash' id="togglePasswordBecome"></i>
                        </a>
                        <input type="password" value="" name="confirm_password" class="form-control border-end-0" id="confirm_password_become" placeholder="Enter Confirm Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-equalto="#inputChoosePasswordBecome"> 
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="hear_about_us" placeholder="How did you hear about us?" class="form-control">
                      </div>
                    </div>

										<div class="col-12">
											<div class="margin-cls border border-1 p-2 rounded row">
												<!-- <h4 class="dataLabel">ADDRESS</h4> -->
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
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
												<!--div class="col-md-6 col-xl-6 col-xxl-4 show_provinceDiv">
													<label for="inputName" class="form-label">Province</label>
													<select name="province_id" class="form-control province_id1" id='province_id1'>
														<option value="">Select Province</option>
													</select>
												</div>

												<div class="col-md-6 col-xl-6 col-xxl-4 show_cityDiv">
													<label for="inputName" class="form-label">City</label>
													<select name="city_id" class="form-control city_id" id='city_id'>
														<option value="">Select City</option>
													</select>
												</div>

                        <div class="col-md-6 col-xl-6 col-xxl-4 show_areaDiv">
                          <label for="inputName" class="form-label">Area</label>
                          <select name="area" class="form-control area" id='area'>
                            <option value="">Select Area</option>
                          </select>
                        </div-->

                        <div class="col-md-6 col-xl-6 col-xxl-6 ">
                          <div class="row">
                            <div class="col-md-9 show_provinceDiv">
                              <label for="inputName" class="form-label">Province</label>
                              <select name="province_id" class="form-control province_id1" id='province_id1'>
                                <option value="">Select province</option>
                            </select>
                            </div>
                            <div class="col-md-3">
                            <label for="inputName" class="form-label">&nbsp;</label>
                            <a href="javascript:void(0)" class="btn primary_btn " onclick="newProvinceModal(this)" ><i class="fa fa-plus"></i></a>
                            </div>                          
                          </div>
                        </div>


												
												

												<div class="col-md-6 col-xl-6 col-xxl-6 ">
                          <div class="row">
                              <div class="col-md-9 show_cityDiv">
                              <label for="inputName" class="form-label">City</label>
                              <select name="city_id" class="form-control city_id" id='city_id'>
                                  <option value="">Select City</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                              <label for="inputName" class="form-label">&nbsp;</label>
                              <a href="javascript:void(0)" class="btn primary_btn " onclick="newCityModal(this)" ><i class="fa fa-plus"></i></a>
                              </div>													
                          </div>
                        </div>

                        <div class="col-md-6 col-xl-6 col-xxl-6 ">

                          <div class="row">
                              <div class="col-md-9 show_areaDiv">
                              <label for="inputName" class="form-label">Area</label>
                                <select name="area" class="form-control area" id='area'>
                                  <option value="">Select Area</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label for="inputName" class="form-label">&nbsp;</label>
                                <a href="javascript:void(0)" class="btn primary_btn " onclick="newAreaModal(this)" ><i class="fa fa-plus"></i></a>
                              </div>
                            </div>
                        </div>
												
												
												<!-- <div class="col-md-6 col-xl-6 col-xxl-4">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Landmark</label>
														<input type="text" name="landmark" placeholder="Landmark" class="form-control">
													</div>
												</div> -->
												
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Address</label>
														<input type="text" name="address" placeholder="Address" class="form-control">
													</div>
												</div>
												
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Hosting Type</label>
														<select name="hosting_type" id="hosting_type" class="form-control hosting_type">
                              <option value="Individual">Individual</option>
                              <option value="Business">Business</option>
                            </select>
													</div>
												</div>
												
												<div class="col-md-6 col-xl-6 col-xxl-6 business_name_div" style="display:none;">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Business Name</label>
														<input type="text" name="business_name" placeholder="Business Name" class="form-control">
													</div>
												</div>
												
												<div class="col-md-6 col-xl-6 col-xxl-6 business_image_div" style="display:none;">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Upload Business Registration</label>
														<input type="file" name="business_registration_image" placeholder="Upload Business Registration" class="form-control">
													</div>
												</div>

											</div>
										</div>

                    <div class="remember_sec">
                      <div class="remember_left">
                        <!-- <a href="#" class="primary_btn" data-bs-toggle="modal" data-bs-target="#OTPModal">Sign up</a>   -->
                        <input type="submit" name="" value="Sign up" class="btn primary_btn">
                        <!-- <a href="javascript:void(0)" class="primary_btn" data-bs-toggle="modal" data-bs-target="#OTPModal">Sign up</a>   -->
                      </div>
                      <div class="forgot-cls">
                        <div class="signup-social">
                          <ul>
                            <li>
                              <a href="{{ route('user.auth.redirect-tofacebook') }}">
                                <img src="{{ URL::asset('assets/web/img/f-1.png')}}" alt="">
                              </a>
                            </li>
                            <li>
                              <a href="{{ route('user.auth.redirect-togoogle') }}">
                                <img src="{{ URL::asset('assets/web/img/google.png')}}" alt="">
                              </a>
                            </li>
                            <li>
                              <a href="#">
                                <img src="{{ URL::asset('assets/web/img/apple-logo.png')}}" alt="">
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="account-regester">
                      <!-- <p>If you already have an account? <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">Login</a></p> -->
                      <p>If you already have an account? <a href="{{ url('admin/login') }}">Login</a></p>
                    </div>  
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--BecomeAHostModal Modal End-->

      <!--PartnerModal Modal Start-->
      <div class="modal fade select-sec" id="BecomePartnerModal" style="overflow:hidden;" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Become A Partner</h2>
                    <p>Partner Details</p>
                  </div>
                  <form method="POST" action="" class="login-form row" enctype="" id="partner_signup_form">
                    @csrf
                    <div class="col-md-3">
                      <div class="form-group">
                        <select name="title" class="form-control title" id='title' data-parsley-required="true">
                          <option value="">-- Select Title--</option>
                          <option value="Company" >Company</option>
                          <option value="Dr" >Dr</option>
                          <option value="Family" >Family</option>
                          <option value="Mr_&_Mrs" >Mr & Mrs</option>
                          <option value="Mr" >Mr.</option>
                          <option value="Mrs" >Mrs.</option>
                          <option value="Ms" >Ms.</option>
                          <option value="PhD" >PhD</option>
                          <option value="Prof" >Prof</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="fullname" placeholder="Enter First Name*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input type="text" name="surname" placeholder="Enter Surname*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>

                    <div class="col-md-5">
                      <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address*" class="form-control" data-parsley-required="true" data-parsley-type="email">
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group select-country ">
                        <div class="input-group BecomePartnerModal2">
                          <select name="country_code" class="form-control country_code select_img_country6" id='country_code' data-parsley-required="true">
                            @if(!empty($country_phone))
                              @foreach($country_phone as $key => $country_ph)
                                <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                              @endforeach
                            @endif
                          </select>
                          <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group input-group" id="show_hide_password">
                        <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                          <i class='fa fa-eye-slash'></i>
                        </a>
                        <input type="password" value="" name="password" class="form-control border-end-0" id="inputChoosePasswordPartner" placeholder="Enter Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters."> 
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group input-group" id="show_hide_password2">
                        <a href="javascript:;" class="input-group-text bg-transparent" style="color:black !important;">
                          <i class='fa fa-eye-slash' id="togglePasswordPartner"></i>
                        </a>
                        <input type="password" value="" name="confirm_password" class="form-control border-end-0" id="confirm_password_partner" placeholder="Enter Confirm Password" value="{{isset($_COOKIE["admin_password"]) ? $_COOKIE["admin_password"] : '' }}" data-parsley-required="true" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" data-parsley-required-message="Please enter password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-equalto="#inputChoosePasswordPartner"> 
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="hear_about_us" placeholder="How did you hear about us?" class="form-control">
                      </div>
                    </div>

										<div class="col-12">
											<div class="margin-cls border border-1 p-2 rounded row">
												<!-- <h4 class="dataLabel">ADDRESS</h4> -->
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
														<label for="inputName" class="form-label">Country*</label>
														<select name="country_id" class="form-control country_id_partner" /*onchange="getProvince()"*/ id='country_id_partner'  data-parsley-required="true">
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

												<div class="col-md-6 col-xl-6 col-xxl-6 ">
                          <div class="row">
                            <div class="col-md-9 show_provinceDiv_partner">
                              <label for="inputName" class="form-label">Province</label>
                              <select name="province_id" class="form-control province_id1" id='province_id1'>
                                <option value="">Select province</option>
                            </select>
                            </div>
                            <div class="col-md-3">
                            <label for="inputName" class="form-label">&nbsp;</label>
                            <a href="javascript:void(0)" class="btn primary_btn " onclick="newProvinceModal(this)" ><i class="fa fa-plus"></i></a>
                            </div>                          
                          </div>
                        </div>


												
												

												<div class="col-md-6 col-xl-6 col-xxl-6 ">
                          <div class="row">
                              <div class="col-md-9 show_cityDiv_partner">
                              <label for="inputName" class="form-label">City</label>
                              <select name="city_id" class="form-control city_id" id='city_id'>
                                  <option value="">Select City</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                              <label for="inputName" class="form-label">&nbsp;</label>
                              <a href="javascript:void(0)" class="btn primary_btn " onclick="newCityModal(this)" ><i class="fa fa-plus"></i></a>
                              </div>													
                          </div>
                        </div>

                        <div class="col-md-6 col-xl-6 col-xxl-6 ">

                          <div class="row">
                              <div class="col-md-9 show_areaDiv_partner">
                              <label for="inputName" class="form-label">Area</label>
                                <select name="area" class="form-control area" id='area'>
                                  <option value="">Select Area</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label for="inputName" class="form-label">&nbsp;</label>
                                <a href="javascript:void(0)" class="btn primary_btn " onclick="newAreaModal(this)" ><i class="fa fa-plus"></i></a>
                              </div>
                            </div>
                        </div>
												
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Landmark</label>
														<input type="text" name="landmark" placeholder="Landmark" class="form-control">
													</div>
												</div>
												
												<div class="col-md-6 col-xl-6 col-xxl-6">
													<div class="form-group">
														<label for="inputPostalCode" class="form-label">Address</label>
														<input type="text" name="address" placeholder="Address" class="form-control">
													</div>
												</div>

											</div>
										</div>

                    <div class="remember_sec">
                      <div class="remember_left">
                        <input type="submit" name="" value="Sign up" class="btn primary_btn">
                      </div>
                      <!-- <div class="forgot-cls">
                        <div class="signup-social">
                          <ul>
                            <li>
                              <a href="#">
                                <img src="{{ URL::asset('assets/web/img/f-1.png')}}" alt="">
                              </a>
                            </li>
                            <li>
                              <a href="#">
                                <img src="{{ URL::asset('assets/web/img/google.png')}}" alt="">
                              </a>
                            </li>
                            <li>
                              <a href="#">
                                <img src="{{ URL::asset('assets/web/img/apple-logo.png')}}" alt="">
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div> -->
                    </div>
                    <!-- <div class="account-regester">
                      <p>If you already have an account? <a href="{{ url('admin/login') }}">Login</a></p>
                    </div> -->
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--PartnerModal Modal End-->

      <!--schedule_appointment Modal Start-->
      <div class="modal fade select-sec" id="schedule_appointment" tabindex="-1" aria-labelledby="schedule_appointment" aria-hidden="true">
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
                    <h2>Schedule Appointment</h2>
                  </div>
                  <form method="POST" action="" class="schedule_appointment-form row" enctype="" id="schedule_appointment_form">
                    @csrf
                    <div class="col-md-3">
                      <div class="form-group">
                        <select name="title" class="form-control title" id='title' data-parsley-required="true">
                          <option value="">-- Select Title--</option>
                          <option value="Company" >Company</option>
                          <option value="Dr" >Dr</option>
                          <option value="Family" >Family</option>
                          <option value="Mr_&_Mrs" >Mr & Mrs</option>
                          <option value="Mr" >Mr.</option>
                          <option value="Mrs" >Mrs.</option>
                          <option value="Ms" >Ms.</option>
                          <option value="PhD" >PhD</option>
                          <option value="Prof" >Prof</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="full_name" placeholder="Enter First Name*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input type="text" name="surname" placeholder="Enter Surname*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>

                    <div class="col-md-5">
                      <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address*" class="form-control" data-parsley-required="true" data-parsley-type="email">
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group select-country">
                        <div class="input-group schedule_appointment1">
                          <select name="country_code" class="form-control country_code select_img_country7" id='country_code' data-parsley-required="true">
                            @if(!empty($country_phone))
                              @foreach($country_phone as $key => $country_ph)
                                <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                              @endforeach
                            @endif
                          </select>
                          <input type="text" name="mobile" placeholder="Mobile Number*" class="form-control" data-parsley-required="true" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" name="appointment_date" placeholder="Appointment Date*" class="form-control appointment_date" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select name="schedule_time" class="form-control schedule_time" id="schedule_time" data-parsley-required="true">
                          <option value="">Select time</option>
                          <option value="12:00">12:00 PM</option>
                          <option value="02:00">02:00 PM</option>
                          <option value="04:00">04:00 PM</option>
                        </select>
                        <?php
                            // $start = "00:00"; //you can write here 00:00:00 but not need to it
                            // $end = "23:30";

                            // $tStart = strtotime($start);
                            // $tEnd = strtotime($end);
                            // $tNow = $tStart;
                            // echo '<select name="schedule_time" class="form-control schedule_time" id="schedule_time" data-parsley-required="true"><option value="">Select time</option>';
                            // while($tNow <= $tEnd){
                            //     echo '<option value="'.date("H:i",$tNow).'">'.date("H:i",$tNow).'</option>';
                            //     $tNow = strtotime('+30 minutes',$tNow);
                            // }
                            // echo '</select>';
                        ?>

                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <div class="input-group">
                          <select name="province_id" class="form-control province_id select2" id='province_id' data-parsley-required="true">
                            <option value="">Select State</option>
                            @if(!empty($province))
                              @foreach($province as $key => $pro_data)
                                <option value="{{$pro_data->id}}">{{$pro_data->name}}</option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <div class="input-group">
                          <select name="onsite_inspection" onchange="contactpersoninfo(this)" class="form-control onsite_inspection select2" id='onsite_inspection' data-parsley-required="true">
                            <option value="">Available for the onsite-inspection?</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="form-group">
                        <input type="address" name="address" placeholder="Address*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <input type="landmark" name="landmark" placeholder="Please share anything that will help us locate your property on time, like landmarks*" class="form-control" data-parsley-required="true">
                      </div>
                    </div>
                    <div class="col-md-5 contact_person d-none">
                      <div class="form-group">
                        <input type="text" name="contact_person_name" placeholder="Contact Person Name*" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-7 contact_person d-none">
                      <div class="form-group select-country">
                        <div class="input-group schedule_appointment2">
                          <select name="contact_person_country_code" class="form-control country_code select_img_country8" id='country_code'>
                            @if(!empty($country_phone))
                              @foreach($country_phone as $key => $country_ph)
                                <option data-src="{{url('public/images/country_image/'.$country_ph->sortname.'.png')}}" value="{{$country_ph->phonecode}}" <?php if($country_ph->phonecode == '234'){ echo 'selected'; } ?>>{{$country_ph->sortname}} +{{$country_ph->phonecode}}</option>
                              @endforeach
                            @endif
                          </select>
                          <input type="text" name="contact_person_number" placeholder="Contact Person Number*" class="form-control" data-parsley-type="digits" data-parsley-minlength="8" autocomplete="off" onkeypress="return onlyNumberKey(event)">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="position-cls form-group">
                        <label class="custom_checkbox">
                            <input type="checkbox" name="policy_read" value="Yes" data-parsley-required="true" class="form-control">
                            <span class="checkmark"></span>
                            I have read and I agree with the <a href="{{ url('host-policy') }}" target="_blank"> host policy </a>
                        </label>
                      </div>
                    </div>
                    <div class="remember_sec">
                      <div class="remember_left">
                        <input type="submit" name="" value="Submit" class="btn primary_btn">
                      </div>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--schedule_appointment Modal End-->


    </div>




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
                            <select name="country_id" class="form-control country_id" onchange="getProvince_city1()" required id='country_id_city1'>
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
    <div class="modal fade select-sec" id="bookingCancelPolicyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Booking Cancel Policy</h2>
                  </div>
                 
                  <div class="form-body">
                    <div class="row">
                      <div class="col-lg-12 shwoCancelPolicy">

                      
                            </div>
                            <hr/>
                            <div class="col-lg-12">
                              <input type="checkbox" class="checkBookingPolicy">  I understand the cancellation policy and I agree to the terms and conditions. 
                            </div>
                            <hr/>
                            <div class="col-md-12">
                              <div class="d-flex">
                                <a href="javascript:void(0)" class="btn primary_btn bookingCancelModalClose" data-dismiss="modal">Back</a>
                                <button type="submit" class="btn primary_btn ms-auto booingCancel" >Cancel Booking</button>
                                <!-- <input type="submit" name="" value="Submit" class="btn primary_btn"> -->
                              </div>
                            </div>
                            
                        </div>
                      </div>

              </div>
            </div>
          </div>
        </div>
      </div>

		</div>

<script src="{{ asset('js/parsley.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/js/select2.min.js"></script>
<script>

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



    function getProvince_city() {
      var country_id = $('#country_id_city').val();
      var province_id = "";
      $.ajax({
        url:'{{url("area/show_provinceBefour")}}/'+country_id+'/'+province_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_provinceDiv').html(result);
        }
      });
    }

    function getProvince_city1() {
      var country_id = $('#country_id_city1').val();
      var province_id = "";
      $.ajax({
        url:'{{url("area/show_provinceBefour")}}/'+country_id+'/'+province_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_provinceDiv').html(result);
        }
      });
    }

    
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
      var url = '{{ route("web.become_a_host.provinceStore_befour") }}';
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
              //setTimeout(function(){
                $('.country_id_partner option').prop('selected',false);
                $('.province_id_partner option').prop('selected',false);
                $('.city_id_partner option').prop('selected',false);
                $('.country_id option').prop('selected',false);
                $('#province_name').val('');
                $('#city_name').val('');
                $('#area_name').val('');
                
                
                $('.area option').prop('selected',false);
                $('#newProvinceModal').modal('hide');
               // location.reload();
                // window.location.replace("{{ route('admin.rate.index') }}");
              //}, 1000);
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
      var url = '{{ route("web.become_a_host.cityStore_befour") }}';
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
                $('.country_id_partner option').prop('selected',false);
                $('.province_id_partner option').prop('selected',false);
                $('.city_id_partner option').prop('selected',false);
                $('.area option').prop('selected',false);
                $('#province_name').val('');
                $('#city_name').val('');
                $('#area_name').val('');
                $('#newCityModal').modal('hide');


                //location.reload();
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
      var url = '{{ route("web.become_a_host.areaStore_befour") }}';
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

                $('.country_id_partner option').prop('selected',false);
                $('.province_id_partner option').prop('selected',false);
                $('.city_id_partner option').prop('selected',false);
                $('.area option').prop('selected',false);
                $('#province_name').value('');
                $('#city_name').value('');
                $('#area_name').value('');
                $('#newAreaModal').modal('hide');
              
              setTimeout(function(){
                //location.reload();
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
          url:'{{url("area/show_provinceBefour")}}/'+country_id+'/'+province_id,
          // url:'{{url("admin/area/show_province_new")}}/'+country_id+'/'+province_id,
          dataType: 'html',
          success:function(result)
          {
            $('.show_provinceDiv').html(result);
          }
        });
      }
    })





  var langArray = [];
  $('.login_country_code option').each(function(){
    var img = $(this).attr("data-thumbnail");
    var text = this.innerText;
    var value = $(this).val();
    var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
    langArray.push(item);
  })

  $('#a').html(langArray);

  //Set the button value to the first el of the array
  $('.btn-select').html(langArray[0]);
  $('.btn-select').attr('value', 'en');

  //change button stuff on click
  $('#a li').click(function(){
    var img = $(this).find('img').attr("src");
    var value = $(this).find('img').attr('value');
    var text = this.innerText;
    var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
    $('.btn-select').html(item);
    $('.btn-select').attr('value', value);
    $(".b").toggle();
    //console.log(value);
  });

  $(".btn-select").click(function(){
    $(".b").toggle();
  });

  //check local storage for the lang
  var sessionLang = localStorage.getItem('lang');
  if (sessionLang){
    //find an item with value of sessionLang
    var langIndex = langArray.indexOf(sessionLang);
    $('.btn-select').html(langArray[langIndex]);
    $('.btn-select').attr('value', sessionLang);
  } else {
    var langIndex = langArray.indexOf('ch');
    console.log(langIndex);
    $('.btn-select').html(langArray[langIndex]);
    //$('.btn-select').attr('value', 'en');
  }

  function contactpersoninfo($this) {
    if ($($this).val() == 'No') {
      $('.contact_person').removeClass('d-none');
    } else {
      $('.contact_person').addClass('d-none');
    }
  }

	$( document ).ready(function() {
    const togglePasswordBecome = document.querySelector('#togglePasswordBecome');
    const passwordBecome = document.querySelector('#confirm_password_become');
    togglePasswordBecome.addEventListener('click', function (e) {
      if(passwordBecome.getAttribute('type') == 'password'){
        passwordBecome.setAttribute('type', 'text');  
      }else{
        passwordBecome.setAttribute('type', 'password');
      }
    });

    const togglePasswordPartner = document.querySelector('#togglePasswordPartner');
    const passwordPartner = document.querySelector('#confirm_password_partner');
    togglePasswordPartner.addEventListener('click', function (e) {
      if(passwordBecome.getAttribute('type') == 'password'){
        passwordBecome.setAttribute('type', 'text');  
      }else{
        passwordBecome.setAttribute('type', 'password');
      }
    });

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#confirm_password');
    togglePassword.addEventListener('click', function (e) {
      if(password.getAttribute('type') == 'password'){
        password.setAttribute('type', 'text');  
      }else{
        password.setAttribute('type', 'password');
      }
    });

    const togglePasswordOld = document.querySelector('#toggleOldPasswordChange');
    const password_old = document.querySelector('#old_password');
    togglePasswordOld.addEventListener('click', function (e) {
      if(password_old.getAttribute('type') == 'password'){
        password_old.setAttribute('type', 'text');  
      }else{
        password_old.setAttribute('type', 'password');
      }
    });

    const togglePasswordNew = document.querySelector('#togglePasswordChange');
    const password_new = document.querySelector('#new_password');
    togglePasswordNew.addEventListener('click', function (e) {
      if(password_new.getAttribute('type') == 'password'){
        password_new.setAttribute('type', 'text');  
      }else{
        password_new.setAttribute('type', 'password');
      }
    });

    const togglePasswordConfirmNew = document.querySelector('#togglePasswordChange1');
    const password_confirm_new = document.querySelector('#confirm_new_password');
    togglePasswordConfirmNew.addEventListener('click', function (e) {
      if(password_confirm_new.getAttribute('type') == 'password'){
        password_confirm_new.setAttribute('type', 'text');  
      }else{
        password_confirm_new.setAttribute('type', 'password');
      }
    });

    const togglePasswordNewForget = document.querySelector('#togglePasswordChangeForget');
    const password_new_forget = document.querySelector('#new_password_forget');
    togglePasswordNewForget.addEventListener('click', function (e) {
      if(password_new_forget.getAttribute('type') == 'password'){
        password_new_forget.setAttribute('type', 'text');  
      }else{
        password_new_forget.setAttribute('type', 'password');
      }
    });

    const togglePasswordConfirmNewForget = document.querySelector('#togglePasswordChangeForget1');
    const password_confirm_new_forget = document.querySelector('#confirm_new_password_forget');
    togglePasswordConfirmNewForget.addEventListener('click', function (e) {
      if(password_confirm_new_forget.getAttribute('type') == 'password'){
        password_confirm_new_forget.setAttribute('type', 'text');  
      }else{
        password_confirm_new_forget.setAttribute('type', 'password');
      }
    });

		$( ".txtOnly" ).keypress(function(e) {
			var key = e.keyCode;
			if (key >= 48 && key <= 57) {  // 0-9
				e.preventDefault();
			}
		});
	});
  function onlyNumberKey(evt) {  
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
      return false;
    return true;
  }
  $(document).ready(function () {
    $(".appointment_date").datepicker({
      startDate:new Date(),
      format: "dd/mm/yyyy",
      autoclose: true,
      todayHighlight: true
    });

		$("#show_hide_password a").on('click', function (event) {
			event.preventDefault();
			if ($('#show_hide_password input').attr("type") == "text") {
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass("bx-hide");
				$('#show_hide_password i').removeClass("bx-show");
			} else if ($('#show_hide_password input').attr("type") == "password") {
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass("bx-hide");
				$('#show_hide_password i').addClass("bx-show");
			}
		});
	});
  
/*Change Password Start */
  $('#update_password_form').parsley();
  $(document).on('submit', "#update_password_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.update_password_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res.message);
        if (res.status === true) {
          toastr.success(res.message);
          $('#changePasswordModal').modal('hide');
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
  /*Add card End*/
  
/*Change Password Start */
  $('#forget_update_password_form').parsley();
  $(document).on('submit', "#forget_update_password_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    // alert('in');
    var formData = new FormData(this);
    console.log(formData);
    $.ajax({
      url: '{{ route("forget_update_password_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res.message);
        if (res.status === true) {
          toastr.success(res.message);
          // $('#forgetUpdatePasswordModal').modal('hide');
          setInterval(function () {  
            window.location.replace("{{ route('web.home') }}");
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
    // return false;
  });
  /*Add card End*/

  /*Share booking start */
  $('#share_booking_form').parsley();
    $(document).on('submit', "#share_booking_form", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();
      var formData = new FormData(this);
      var mobile = $('.share_mobile_number').val();
      if(mobile != ''){
        $.ajax({
          url: '{{ route("web.share_booking_form") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {
            console.log('--status'+res.message);
            if (res.status === true) {
              toastr.success(res.message);
              $('#shareBookingModal').modal('hide');
              // setTimeout( window.location.reload(), 2000);
              // setTimeout(function () {
              //   location.reload(true);
              // }, 2000);
              
            } else {
              // toastr.error(res.message);
              if (res.error) {
                $.each(res.error, function(index, value) {
                  console.log(value);
                  toastr.error(value[0]);
                });
              }else{
                toastr.error(res.message);
              }
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
      }else{
        alert('Please enter mobile number.');
      }
      return false;
    });
  /*Share booking end*/

  /*Add card Start */
    $('#card_form').parsley();
    $(document).on('submit', "#card_form", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();
      var formData = new FormData(this);
      $.ajax({
        url: '{{ route("web.card_form") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {
          console.log('--status'+res.message);
          if (res.status === true) {
            toastr.success(res.message);
            $('#AddCard').modal('hide');
            window.location.reload();
          } else {
            // toastr.error(res.message);
            if (res.error) {
              $.each(res.error, function(index, value) {
                console.log(value);
                toastr.error(value[0]);
              });
            }else{
              toastr.error(res.message);
            }
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
  /*Add card End*/

  /*Edit card Start */
    $('#edit_card_form').parsley();
    $(document).on('submit', "#edit_card_form", function(e) {
      e.preventDefault();
      var _this = $(this);
      $('#group_loader').fadeIn();
      var formData = new FormData(this);
      $.ajax({
        url: '{{ route("web.edit_card_form") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {

          if (res.status === true) {
            toastr.success(res.message);
            window.location.reload();
            $('#EditCard').modal('hide');
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
  /*Edit card End*/

/*Login Start */
  $('#login_form').parsley();
  $(document).on('submit', "#login_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.login_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
          window.location.reload();
          $('#login_form')[0].reset();
          $('#login_form').parsley().reset();
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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

  /*Rating Start */
  $('#rating_form').parsley();
  $(document).on('submit', "#rating_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.rating_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
          window.location.reload();
          $('#rating_form')[0].reset();
          $('#rating_form').parsley().reset();
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

  $('#host_login_form').parsley();
  $(document).on('submit', "#host_login_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.host_login_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
          window.location.reload();
          $('#host_login_form')[0].reset();
          $('#host_login_form').parsley().reset();
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
/*Login End */
/*Schedule_appointment Start */
  $('#schedule_appointment_form').parsley();
  $(document).on('submit', "#schedule_appointment_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.schedule_appointment_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {

        if (res.status === true) {
          toastr.success(res.message);
          $('#schedule_appointment_form')[0].reset();
          $('#schedule_appointment_form').parsley().reset();
          $('#schedule_appointment_form').modal('hide');
          setInterval(function () {  
            window.location.reload();
          }, 2000); 
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
/*Schedule_appointment End */ 

/*Become A Host Signup Start */
  $('#become_a_host_signup_form').parsley();
  $(document).on('submit', "#become_a_host_signup_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    $('.otp_number').html('');
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.become_a_host_signup_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res.mobile);
        if (res.status === true) {
          toastr.success(res.message);
          $('#BecomeAHostModal').modal('hide');
          $('#OTPModal').modal('show');
          $('.otp_number').text('Enter The OTP sent to +'+res.country_code+' '+res.mobile);
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
  /*Become A Host Signup End*/

/*Partner Signup Start */
  $('#partner_signup_form').parsley();
  $(document).on('submit', "#partner_signup_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.partner_signup_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res.mobile);
        if (res.status === true) {
          toastr.success(res.message);
          setInterval(function () {  
            window.location.replace("{{ route('web.home') }}");
          }, 3000); 
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
  /*Partner Signup End*/

  /*Customer Signup Start */
  $('#customer_signup_form').parsley();
  $(document).on('submit', "#customer_signup_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    $('.customer_signup_btn').prop('disabled',true);
    $.ajax({
      url: '{{ route("web.customer_signup_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res);
        $('.customer_signup_btn').prop('disabled',false);
        if (res.status === true) {
          toastr.success(res.message);
          window.location.reload();
          // $('#BecomeAHostModal').modal('hide');
          // $('#OTPModal').modal('show');
          // $('.otp_number').text('Enter The OTP sent to +'+res.country_code+' '+res.mobile);
        } else {
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
  /*Become A Host Signup End*/

  /*Customer Signup Start */
  $('#forget_password_form').parsley();
  $(document).on('submit', "#forget_password_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    var formData = new FormData(this);
    var forget_country_code = $('.forget_country_code').val();
    var forget_mobile = $('.forget_mobile').val();
    $.ajax({
      url: '{{ route("web.forget_password_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        console.log('--status'+res);
        if (res.status === true) {
          toastr.success(res.message);
          // window.location.reload();
          $('#ForgetPasswordModal').modal('hide');
          $('#ForgetOTPModal').modal('show');

          $('.forget_update_country_code').val(forget_country_code);
          $('.forget_update_mobile').val(forget_mobile);
          // $('.otp_number').text('Enter The OTP sent to +'+res.country_code+' '+res.mobile);
        } else {
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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
  /*Become A Host Signup End*/

/*OTP Start */
  $('#otp_form').parsley();
  $(document).on('submit', "#otp_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    $('.otp_verify_btn').prop('disabled', true);
    var formData = new FormData(this);
    $.ajax({
      url: '{{ route("web.otp_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
          $('.otp_verify_btn').prop('disabled', false);
          // window.location.reload();
          // $('#otp_form')[0].reset();
          // $('#otp_form').parsley().reset();
          setInterval(function () {  
            window.location.replace("{{ route('web.become_a_host.host_type') }}");
            // window.location.replace("{{ route('web.become_a_host') }}");
            // window.location.replace("{{ route('web.my_account') }}");
          }, 2000); 
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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

  $('#forget_otp_form').parsley();
  $(document).on('submit', "#forget_otp_form", function(e) {
    e.preventDefault();
    var _this = $(this);
    $('#group_loader').fadeIn();
    $('.forget_otp_verify_btn').prop('disabled', true);
    var formData = new FormData(this);
    var forget_update_country_code = $('.forget_update_country_code').val();
    var forget_update_mobile = $('.forget_update_mobile').val();
    $.ajax({
      url: '{{ route("web.forget_otp_form") }}',
      dataType: 'json',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
          $('.forget_otp_verify_btn').prop('disabled', false);
          $('#ForgetOTPModal').modal('hide');
          $('#forgetUpdatePasswordModal').modal('show');

          $('.forget_update_country_code1').val(forget_update_country_code);
          $('.forget_update_mobile1').val(forget_update_mobile);

          // window.location.reload();
          // $('#otp_form')[0].reset();
          // $('#otp_form').parsley().reset();

          // setInterval(function () {  
          //   window.location.replace("{{ route('web.become_a_host.host_type') }}");
          //   // window.location.replace("{{ route('web.become_a_host') }}");
          //   // window.location.replace("{{ route('web.my_account') }}");
          // }, 2000); 
        } else {
          // toastr.error(res.message);
          if (res.error) {
            $.each(res.error, function(index, value) {
              console.log(value);
              toastr.error(value[0]);
            });
          }else{
            toastr.error(res.message);
          }
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

  $(document).on('click', "#resend_otp_form", function(e) {
    e.preventDefault();
    $.ajax({
      url: '{{ route("web.resend_otp_form") }}',
      dataType: 'json',
      type: 'GET',
      success: function(res) {
        if (res.status === true) {
          toastr.success(res.message);
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
  /*Become A Host Signup End*/
</script>

<script type="text/javascript">
  $('.digit-group-otp').find('input').each(function() {
    $(this).attr('maxlength', 1);

    $(this).on('keyup', function(e) {
      var parent = $($(this).parent());
      
      if (e.keyCode === 8 || e.keyCode === 37) {
        var prev = $('input#' + $(this).data('previous'));

        if(prev.length) {
          $(prev).select();
        }
      } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
        var next = $('input#' + $(this).data('next'));
        
        if(next.length) {
          $(next).select();

        } else {
          // if(parent.data('autosubmit')) {
          //   parent.submit();
          // }
        }
      }
    });
  });

	$(document).on('change', '.hosting_type',function(){
		var hosting_type = $(this).val();
    if (hosting_type == 'Business') {
      $('.business_name_div').css('display','block');
      $('.business_image_div').css('display','block');
    }else{
      $('.business_name_div').css('display','none');
      $('.business_image_div').css('display','none');
    }
	})


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

  $(document).on('change', '.country_id_partner',function(){
    var country_id_partner = $('#country_id_partner').val();
    var province_id = "";

    if (country_id_partner) {
      $.ajax({
        url:'{{url("area/show_province_partner")}}/'+country_id_partner+'/'+province_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_provinceDiv_partner').html(result);
        }
      });
    }
  })

  function getCity() {
      var country_id = sessionStorage.getItem("host_country_id");
      var province_id = sessionStorage.getItem("host_province_id");
      var city_id = sessionStorage.getItem("host_city_id");
    
      if (province_id) {
        $.ajax({
            url:'{{url("area/show_city_befour")}}/'+country_id+'/'+province_id+'/'+city_id,
            dataType: 'html',
            success:function(result)
            {
              $('.show_cityDiv').html(result);
            }
        });
      }
    }



	// $(document).on('change', '.province_id1',function(){
  //   var country_id = $('#country_id').val();
  //   var province_id = $('#province_id1').find(":selected").val();
  //   var city_id = "";
  //   if (province_id) {
  //     $.ajax({
  //       url:'{{url("area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
  //       dataType: 'html',
  //       success:function(result)
  //       {
  //         $('.show_cityDiv').html(result);
  //       }
  //     });
  //   }
	// })

	// $(document).on('change', '.city_id',function(){
	// 	var country_id = $('#country_id').val();
	// 	var province_id = $('#province_id').val();
	// 	var city_id = $('#city_id').val();
	// 	var area_id = "";
	// 	if (city_id) {
	// 		$.ajax({
	// 			url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
	// 			dataType: 'html',
	// 			success:function(result)
	// 			{
	// 				$('.show_areaDiv').html(result);
	// 			}
	// 		});
	// 	}
	// })

  // $(document).on('change', '.city_id',function(){
  //   var country_id = $('#country_id').val();
  //   var province_id = $('#province_id').val();
  //   var city_id = $('#city_id').val();
  //   var area_id = "";
  //   if (city_id) {
  //     $.ajax({
  //       url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id+'/'+area_id,
  //       dataType: 'html',
  //       success:function(result)
  //       {
  //         $('.show_areaDiv').html(result);
  //       }
  //     });
  //   }
  // })
</script>