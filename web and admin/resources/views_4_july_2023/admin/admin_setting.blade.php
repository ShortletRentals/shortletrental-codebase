@extends('layouts.master')

@section('content')
<!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin Settings</div>
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
        <div class="main-body password_box">
            <!-- <div class="col-lg-4">
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
            </div> -->
              <form method="POST" action="{{ url('admin/setting-save').'/'.$settingData[0]['id'] }}" id="save_profile">
                @csrf
                  <div class="card">
                    <div class="card-body p-3">
                      @include('flash-message')
                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Commission</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('commission')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Commission (%)" id="commission" value="{{$settingData[0]['commission']}}" name="commission" class="form-control" data-parsley-required="true" data-parsley-type="digits">
                          {!! !empty($errors->has('commission')) ?'<div class="invalid-feedback"><span>'.$errors->first('commission').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Loyalty Point</h6>
                        </div>
                        <!-- <div class="col-sm-4">
                          <input type="text" placeholder="Amount (NGN)" id="loyalty_point_amount" value="{{$settingData[0]['loyalty_point_amount']}}" name="loyalty_point_amount" class="form-control form-control-line" data-parsley-type="digits">*
                        </div> -->
                        <div class="col-sm-8">
                          <input type="text" placeholder="Royalty Point" id="loyalty_percentage" value="{{$settingData[0]['loyalty_percentage']}}" name="loyalty_percentage" class="form-control form-control-line" data-parsley-type="digits" min="1" max="100">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Loyalty Point Equal To</h6>
                        </div>
                        <div class="col-6 col-sm-4">
                          <div class="d-flex align-items-center multi_cls mb-3">
                            <input type="text" placeholder="Royalty point" id="royalty_point_equal_to" value="{{$settingData[0]['royalty_point_equal_to']}}" name="royalty_point_equal_to" class="form-control form-control-line" data-parsley-type="digits">
                            <span class="text-black">*</span>
                          </div>
                        </div>
                        <div class="col-6 col-sm-4">
                          <div class="mb-3">
                            <input type="text" placeholder="Amount (NGN)" id="second_royalty_amount" value="{{$settingData[0]['second_royalty_amount']}}" name="second_royalty_amount" class="form-control form-control-line" data-parsley-type="digits">
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Contact Title</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('title')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Contact Title" id="title" value="{{$settingData[0]['title']}}" name="title" class="form-control form-control-line">
                          {!! !empty($errors->has('title')) ?'<div class="invalid-feedback"><span>'.$errors->first('title').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Contact E-mail</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('email')) ? 'is-invalid':''; ?>
                          <input type="email" placeholder="Enter Contact Email" id="email" value="{{$settingData[0]['email']}}" name="email" class="form-control form-control-line" data-parsley-required="true">
                          {!! !empty($errors->has('email')) ?'<div class="invalid-feedback"><span>'.$errors->first('email').'</span></div>' :'' !!}
                        </div>
                      </div>
                      
                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Contact Phone Number</h6>
                        </div>
                        <div class="col-5 col-sm-2">
                          <?php $error = !empty($errors->first('country_code'))?' is-invalid':''; ?>
                          <select name="country_code" class="form-control country_code {{$error}}" id='country_code'>
                            @if(!empty($country))
                            @foreach($country as $key => $country)
                              <?php $selected = isset($settingData[0]['country_code']) ? $settingData[0]['country_code'] : "+234" ; ?>
                              <option value="{{$country->phonecode}}" <?php echo $selected == $country->phonecode  ? 'selected' : '' ?>>{{$country->sortname}} +{{$country->phonecode}}</option>
                            @endforeach
                            @else
                            @endif
                          </select>
                        </div>
                        <div class=" col-7 col-sm-7">
                          <?php  $errorClass =  !empty($errors->has('mobile')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Contact Phone Number" id="mobile" value="{{$settingData[0]['mobile']}}" name="mobile" class="form-control form-control-line" data-parsley-required="true" minlength="7" maxlength="12" onkeypress="allowNumbersOnly(event)">
                          {!! !empty($errors->has('mobile')) ?'<div class="invalid-feedback"><span>'.$errors->first('mobile').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Facebook URL</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('facebook_url')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Facebook URL" id="facebook_url" value="{{$settingData[0]['facebook_url']}}" name="facebook_url" class="form-control form-control-line">
                          {!! !empty($errors->has('facebook_url')) ?'<div class="invalid-feedback"><span>'.$errors->first('facebook_url').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Twitter URL</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('twitter_url')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Twitter URL" id="twitter_url" value="{{$settingData[0]['twitter_url']}}" name="twitter_url" class="form-control form-control-line">
                          {!! !empty($errors->has('twitter_url')) ?'<div class="invalid-feedback"><span>'.$errors->first('twitter_url').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Instagram URL</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('instagram_url')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Instagram URL" id="instagram_url" value="{{$settingData[0]['instagram_url']}}" name="instagram_url" class="form-control form-control-line">
                          {!! !empty($errors->has('instagram_url')) ?'<div class="invalid-feedback"><span>'.$errors->first('instagram_url').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Whats Up URL</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('whatsup_url')) ? 'is-invalid':''; ?>
                          <input type="text" placeholder="Enter Whats Up URL" id="whatsup_url" value="{{$settingData[0]['whatsup_url']}}" name="whatsup_url" class="form-control form-control-line">
                          {!! !empty($errors->has('whatsup_url')) ?'<div class="invalid-feedback"><span>'.$errors->first('whatsup_url').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Country</h6>
                        </div>
                        <div class="col-sm-9">
                          <?php  $errorClass =  !empty($errors->has('country')) ? 'is-invalid':''; ?>
                          <select name="country" class="form-control country {{$error}}" id='country'>
                            @if(!empty($country))
                            @foreach($country_all as $key => $country1)
                              <?php $selected = isset($settingData[0]['country']) ? $settingData[0]['country'] : "" ; ?>
                              <option value="{{$country1->sortname}}" <?php echo $selected == $country1->sortname  ? 'selected' : '' ?>>{{$country1->name}}</option>
                            @endforeach
                            @else
                            @endif
                          </select>
                          {!! !empty($errors->has('country')) ?'<div class="invalid-feedback"><span>'.$errors->first('country').'</span></div>' :'' !!}
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                          <input type="submit" class="btn btn-light px-4" value="Update" />
                        </div>
                      </div>
                    </div>
                  </div>                     
              </form>
          
          </div>
        </div>
      </div>
@endsection