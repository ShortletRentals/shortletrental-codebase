@extends('layouts.master')

@section('content')
<!--start page wrapper -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
  <div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">User Profile</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
          </nav>
        </div>
        <!-- <div class="ms-auto">
          <div class="btn-group">
            <button type="button" class="btn btn-light">Settings</button>
            <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">  <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">  <a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">Another action</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>  <a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div> -->
      </div>
      <!--end breadcrumb-->
      <div class="container">
        <div class="main-body password_box">
          <div class="row">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="{{Auth::user()->image}}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                    <div class="mt-3">
                      <h4>{{ucwords(Auth::user()->name)}}</h4>
                      <p class="mb-1">{{Auth::user()->email}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <form method="POST" action="{{ url('admin/change_password') }}" id="save_profile" data-parsley-validate="true">
                  @csrf
                  <div class="card">
                      <div class="card-body">
                        @include('flash-message')
                        <div class="row mb-3">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Current Password</h6>
                          </div>
                          <div class="col-md-9">
                            <div class="input-group" id="show_hide_password">
                              <a href="javascript:;" class="input-group-text bg-transparent toggle-password1"><i class='bx bx-hide'></i></a>	
                              <input type="password" name="old_password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="inputOldPassword" placeholder="Enter Old Password" maxlength="32" data-parsley-required-message="Please enter old password." data-parsley-required> 
                            </div>
                          </div>
                          <!-- <div class="col-sm-9">
                            <?php  $errorClass =  !empty($errors->has('old_password')) ? 'is-invalid':''; ?>
                              <div class="eye_icon">
								                <input type="password" name="old_password" placeholder="Old Password" class="form-control" data-parsley-required-message="Please enter your old password." data-parsley-required>
								                <i class="fa fa-eye password123_eye" onclick="showPassword('password123')"></i>
                              </div>
                          </div> -->
                        </div>
                        <div class="row mb-3">
                          <div class="col-sm-3">
                            <h6 class="mb-0">New Password</h6>
                          </div>
                          <div class="col-md-9">
                            <div class="input-group" id="show_hide_password">
                              <a href="javascript:;" class="input-group-text bg-transparent toggle-password2"><i class='bx bx-hide'></i></a>	
                              <input type="password" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="inputChoosePassword" placeholder="Enter New Password" data-parsley-minlength="[6]" minlength="8" data-parsley-minlength="8" maxlength="32" data-parsley-required-message="Please enter new password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required> 
                            </div>
                          </div>
                          <!-- <div class="col-sm-9">
                            <?php  $errorClass =  !empty($errors->has('password')) ? 'is-invalid':''; ?>
                              <div class="eye_icon">
								                <input type="password" name="password" placeholder="Password" class="form-control" minlength="8" data-parsley-minlength="8"  data-parsley-required-message="Please enter your password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required id="password123">
								                <i class="fa fa-eye password123_eye" onclick="showPassword('password123')"></i>
                              </div>
                          </div> -->
                        </div>
                        <div class="row mb-3">
                          <div class="col-sm-3">
                            <h6 class="mb-0">Confirm Password</h6>
                          </div>
                          <div class="col-md-9">
                            <div class="input-group" id="show_hide_password">
                              <a href="javascript:;" class="input-group-text bg-transparent toggle-password3"><i class='bx bx-hide'></i></a>	
                              <input type="password" name="confirm_password" class="form-control border-end-0 @error('password') is-invalid @enderror" id="inputConfirmPassword" placeholder="Enter Confirm Password" data-parsley-minlength="[6]" minlength="8" maxlength="32" data-parsley-minlength="8" data-parsley-required-message="Please enter confirm password." data-parsley-pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=]).*" data-parsley-pattern-message="Your password must contain at least (1) lowercase, (1) uppercase letter, (1) special character and minimum 8 characters." data-parsley-required data-parsley-equalto="#inputChoosePassword" data-parsley-equalto-message="This value should be the same as New password."> 
                            </div>
                          </div>
                          <!-- <div class="col-sm-9">
                            <div class="eye_icon">
                              <input type="password" name="confirm_password" placeholder="Confirm Password" data-parsley-equalto-message="New Password & Confirm Password shouldn't match" minlength="8" data-parsley-minlength="8" data-parsley-equalto="#password123" class="form-control" id="confirm_password" >
                              <i class="fa fa-eye confirm_password_eye"  onclick="showPassword('confirm_password')"></i>
                            </div>
                          </div> -->
                        </div>
                        <div class="row">
                          <div class="col-sm-3"></div>
                          <div class="col-sm-9">
                            <input type="submit" class="btn btn-light px-4" value="Save Changes" />
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
  </div>
</div>
<script>
	$("body").on('click', '.toggle-password1', function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $("#inputOldPassword");
		if (input.attr("type") === "password") {
			$('.toggle-password').html("<i class='bx bx-show'></i>");
			input.attr("type", "text");
		} else {
			$('.toggle-password').html("<i class='bx bx-hide'></i>");
			input.attr("type", "password");
		}
	});

	$("body").on('click', '.toggle-password2', function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $("#inputChoosePassword");
		if (input.attr("type") === "password") {
			$('.toggle-password').html("<i class='bx bx-show'></i>");
			input.attr("type", "text");
		} else {
			$('.toggle-password').html("<i class='bx bx-hide'></i>");
			input.attr("type", "password");
		}
	});

	$("body").on('click', '.toggle-password3', function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $("#inputConfirmPassword");
		if (input.attr("type") === "password") {
			$('.toggle-password').html("<i class='bx bx-show'></i>");
			input.attr("type", "text");
		} else {
			$('.toggle-password').html("<i class='bx bx-hide'></i>");
			input.attr("type", "password");
		}
	});
</script>
@endsection