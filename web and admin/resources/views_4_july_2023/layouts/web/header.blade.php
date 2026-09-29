<?php
  use App\Models\AdminSettings;
  use App\Models\Country;
    $countryList = getCountryList();
    $is_guest = Session::get('is_guest');
    $auth_user = Session::get('AuthUserData');
    $settingData = AdminSettings::get();

    if (isset($auth_user)) {
      $userId = isset($auth_user) && !empty($auth_user->data->id) ? $auth_user->data->id : '';
    }

    $adultCount = $_GET['adultCount'] ?? 0;
    $childCount = $_GET['childCount'] ?? 0;
    $infantCount = $_GET['infantCount'] ?? 0;
    $petCount = $_GET['petCount'] ?? 0;
    $province_id = $_GET['province_id'] ?? 0;
    $city_id = $_GET['city_id'] ?? 0;
    $area_id = $_GET['area_id'] ?? 0;
    $no_of_bedrooms = $_GET['no_of_bedrooms'] ?? 0;

    $totalGuest =  $adultCount+$childCount+$infantCount+$petCount;

    if ($totalGuest <= 0) {
      $totalGuest = 'Add';
    }
?>


<div
<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" /> -->
<script type="text/javascript" src="{{asset('assets/js/moment.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/daterangepicker.css')}}" />
<script type="text/javascript" src="{{asset('js/daterangepicker.js')}}">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css" />
<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">

<header>
  <div class="container">
    <div class="header_inner">
      <div class="logo_wrap">
        <a href="{{ route('web.home') }}"><img src="{{ URL::asset('assets/web/img/logo.png')}}" alt=""></a>
      </div>
      <div class="search_s">
        <div class="search_s_in">
          <div class="search_s_location">Location</div>
          <div class="search_s_time">Any Time</div>
          <div class="search_s_guest">Add Guests</div>
          <div class="search_s_btn search_icon">
            <button class="btn btn_primary"><img src="{{ URL::asset('assets/web/img/shortlet/search.svg')}}"></button>
          </div>
        </div>
        <div class="search_s_cn">
          <h4>Stays</h4>
          <p>Search Your Destination</p>
          <div class="search_s_cn_cross"><img src="{{ URL::asset('assets/web/img/shortlet/cross_icon.svg')}}" alt="Remove"></div>
        </div>
      </div>     
      
      @if($auth_user != null && $is_guest != 1)
        <div class="account-sec-tog">&#x2630;</div>
        <div class="account-sec">
          <div class="account-sec-cross">&#x2716;</div>
          <div class="become_host">
            <a href="{{ route('web.become_a_host') }}" class="btn primary_btn">Become a Host</a>
          </div>
          <div class="become_host">
            <a href="{{ route('web.become_a_partner') }}" class="btn primary_btn">Become a Partner</a>
          </div>

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
           
            <div class="button-fix">
              <a href="{{ url('guest-chat') }}" class="message-right">Message</a>
            </div>
          @if($auth_user->data->user_type != 4)
          <div class="become_host">
            <a href="{{ url('admin/login') }}" class="btn primary_btn" >Host Login</a>
          </div>
          @endif
          <div class="become_host after_login dropdown">
            <a href="javascript:void(0);" class="btn secondary_btn dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
               <span class="icon"><img src="{{ isset($auth_user) && !empty($auth_user->data->image) ? $auth_user->data->image : '' }}" alt=""></span>{{$auth_user->data->fullname ? $auth_user->data->fullname:$auth_user->data->country_code.'-'.$auth_user->data->mobile}}</a>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                @if($auth_user->data->user_type != 5)
                <li><a class="dropdown-item" href="{{ route('web.home') }}">Home</a></li>
                <li><a class="dropdown-item" href="{{ route('web.my_account') }}">My Account</a></li>
                @endif
                <li><a class="dropdown-item logout_web" href="javascript:void(0);">Logout</a></li>
                <!-- <li><a class="dropdown-item" href="#">Something else here</a></li> -->
              </ul>
          </div>
        </div>
      @else
      <div class="account-sec-tog">&#x2630;</div>
        <div class="account-sec">
          <div class="account-sec-cross">&#x2716;</div>
          <div class="become_host">
            <a href="{{ route('web.become_a_host') }}" class="btn primary_btn" >Become a Host</a>
            <!-- <a href="#" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#BecomeAHostModal">Become a Host</a> -->
          </div>
          <div class="become_host">
            <a href="{{ route('web.become_a_partner') }}" class="btn primary_btn">Become a Partner</a>
          </div>
          <div class="become_host">
            <a href="{{ url('admin/login') }}" class="btn primary_btn" >Host Login</a>
          </div>
          <div class="become_host">
            <a href="javascript:void(0)" class="btn secondary_btn" data-bs-toggle="modal" data-bs-target="#exampleModal"> <span class="icon"><img src="{{ URL::asset('assets/web/img/shortlet/user.svg')}}" alt=""></span> Login/Signup</a>
          </div>

        
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
           
            <div class="button-fix">
              <a href="{{ url('guest-chat') }}" class="message-right">Message</a>
            </div>
        </div>
        
      @endif
    </div>
    <div class="header_inner header_inner_sss">
      <div class="search-wrap">
          <div class="search_cls">
            <div class="search_location">
              <label for="">Location</label>
              <select name="type" id="province_id" onchange="getCityArea()">
                <option value="all">-All-</option>

                <?php if (count($countryList)) { foreach ($countryList as $key => $value) { ?>
                    <option value="{{$value->province_id}}" {{$value->province_id == $province_id ? 'selected' : ''}} >{{$value->name}} ({{$value->total}})</option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="search_location">
              <label for="">City/Area</label>
              <select name="type" id="type" class="show_areas">
                <option value="">Select</option>
              </select>
            </div>
            <div class="date_wrap" id="daterangepicker">
              <div class="check_in" id="reportrange">
                <span></span>
                <label for="">Check in</label>
                <div for="datepicker" class="datepicker_cls">
                  <input type="text" id="datepicker2" autocomplete="off" placeholder="Add Dates" value="{{$_GET['checkIn'] ?? ''}}">
                </div>  
              </div>
              <div class="check_out">
                <label for="">Check Out</label>
                <div for="datepicker1" class="datepicker_cls">
                  <input type="text" id="datepicker12" autocomplete="off" placeholder="Add Dates" value="{{$_GET['checkOut'] ?? ''}}">
                </div>
              </div>
            </div>
            <div class="number_of_bedrooms_div">
              <label for="">Bedrooms</label>
              <select name="no_of_bedrooms" class="form-control no_of_bedrooms" id="no_of_bedrooms">
                <option value="0" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 0 ? 'selected' : ''; ?>>0</option>
                <option value="1" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 1 ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 2 ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 3 ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 4 ? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 5 ? 'selected' : ''; ?>>5</option>
                <option value="6" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 6 ? 'selected' : ''; ?>>6</option>
                <option value="7" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 7 ? 'selected' : ''; ?>>7</option>
                <option value="8" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 8 ? 'selected' : ''; ?>>8</option>
                <option value="9" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 9 ? 'selected' : ''; ?>>9</option>
                <option value="10" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 10 ? 'selected' : ''; ?>>10</option>
                <option value="11" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 11 ? 'selected' : ''; ?>>11</option>
                <option value="12" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 12 ? 'selected' : ''; ?>>12</option>
                <option value="13" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 13 ? 'selected' : ''; ?>>13</option>
                <option value="14" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 14 ? 'selected' : ''; ?>>14</option>
                <option value="15" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 15 ? 'selected' : ''; ?>>15</option>
                <option value="16" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 16 ? 'selected' : ''; ?>>16</option>
                <option value="17" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 17 ? 'selected' : ''; ?>>17</option>
                <option value="18" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 18 ? 'selected' : ''; ?>>18</option>
                <option value="19" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 19 ? 'selected' : ''; ?>>19</option>
                <option value="20" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 20 ? 'selected' : ''; ?>>20</option>
              </select>
            </div>
            <div class="guest-add">
              <div class="guest-main">
                <div class="guest-left">
                  <h4>Who</h4>
                  <div class="dropdown_sec">
                    <a href="javascript:void(0)" class="header_dropdown_sec"><span class='totalGuest'>{{$totalGuest}} Guest</span></a>
                    <div class="dropdown-menu_sec">
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Adults</h4>
                          <p>Ages 13 or above</p>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" onclick="remove(this)" data-id="adultCount" class="sub">-</button>
                            <input class="count adultCount" type="text" id="adultCount" value="{{$_GET['adultCount'] ?? 0}}" min="1" max="100" />
                            <button type="button" id="add" onclick="add(this)" data-id="adultCount" class="add">+</button>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Children</h4>
                          <p>Ages 2-12</p>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" onclick="remove(this)" data-id="childCount" class="sub">-</button>
                            <input class="count childCount" type="text" id="childCount" value="{{$_GET['childCount'] ?? 0}}" min="1" max="100" />
                            <button type="button" id="add" onclick="add(this)" data-id="childCount" class="add">+</button>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Infants</h4>
                          <p>Under 2</p>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" onclick="remove(this)" data-id="infantCount" class="sub">-</button>
                            <input class="count infantCount" type="text" id="infantCount" value="{{$_GET['infantCount'] ?? 0}}" min="1" max="100" />
                            <button type="button" id="add" onclick="add(this)" data-id="infantCount" class="add">+</button>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Pets</h4>
                          <p>If Allow pets</p>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" onclick="remove(this)" data-id="petCount" class="sub">-</button>
                            <input class="count petCount" type="text" id="petCount" value="{{$_GET['petCount'] ?? 0}}" min="1" max="100" />
                            <button type="button" id="add" onclick="add(this)" data-id="petCount" class="add">+</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="guest-right">
                  <div class="search_icon">
                    <button class="btn btn_primary" onclick="filterData()" >
                      <img src="{{ URL::asset('assets/web/img/shortlet/search.svg')}}" alt="">
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</header>

<div class="search_toggle">
  <div class="mobile_header_tgl_cross"><img src="{{ URL::asset('assets/web/img/shortlet/cross_icon.svg')}}" alt="Remove"></div>
  <div class="search_toggle_in">
    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Location
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <div class="form-group">
              <label for="">Location</label>
              <select name="type" class="form-control" id="province_id_mobile" onchange="getCityInMobile()">
                <option value="all">-All-</option>

                <?php if (count($countryList)) { foreach ($countryList as $key => $value) { ?>
                    <option value="{{$value->province_id}}" {{$value->province_id == $province_id ? 'selected' : ''}} >{{$value->name}} ({{$value->total}})</option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="form-group mb-0">
              <label for="">City/Area</label>
              <select name="type" id="type" class="show_areas_mobile form-control">
                <option value="">Select</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Any Time
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <div class="date_wrap" id="daterangepicker_new">
              <div class="check_in" id="reportrange">
                <span></span>
                <label for="">Check in</label>
                <div for="datepicker" class="datepicker_cls">
                  <input type="text" id="datepicker3" autocomplete="off" placeholder="Add Dates" value="{{$_GET['checkIn'] ?? ''}}">
                </div>  
              </div>
              <div class="check_out">
                <label for="">Check Out</label>
                <div for="datepicker1" class="datepicker_cls">
                  <input type="text" id="datepicker13" autocomplete="off" placeholder="Add Dates" value="{{$_GET['checkOut'] ?? ''}}">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            Number of Bedrooms
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
          <div class="accordion-body">
              <div class="number_of_bedrooms_mobile_div" id="number_of_bedrooms_mobile_div">
                <label for="">Bedrooms</label>
                <select name="no_of_bedrooms" class="form-control no_of_bedrooms" id="no_of_bedrooms">
                  <option value="0" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 0 ? 'selected' : ''; ?>>0</option>
                  <option value="1" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 1 ? 'selected' : ''; ?>>1</option>
                  <option value="2" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 2 ? 'selected' : ''; ?>>2</option>
                  <option value="3" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 3 ? 'selected' : ''; ?>>3</option>
                  <option value="4" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 4 ? 'selected' : ''; ?>>4</option>
                  <option value="5" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 5 ? 'selected' : ''; ?>>5</option>
                  <option value="6" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 6 ? 'selected' : ''; ?>>6</option>
                  <option value="7" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 7 ? 'selected' : ''; ?>>7</option>
                  <option value="8" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 8 ? 'selected' : ''; ?>>8</option>
                  <option value="9" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 9 ? 'selected' : ''; ?>>9</option>
                  <option value="10" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 10 ? 'selected' : ''; ?>>10</option>
                  <option value="11" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 11 ? 'selected' : ''; ?>>11</option>
                  <option value="12" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 12 ? 'selected' : ''; ?>>12</option>
                  <option value="13" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 13 ? 'selected' : ''; ?>>13</option>
                  <option value="14" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 14 ? 'selected' : ''; ?>>14</option>
                  <option value="15" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 15 ? 'selected' : ''; ?>>15</option>
                  <option value="16" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 16 ? 'selected' : ''; ?>>16</option>
                  <option value="17" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 17 ? 'selected' : ''; ?>>17</option>
                  <option value="18" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 18 ? 'selected' : ''; ?>>18</option>
                  <option value="19" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 19 ? 'selected' : ''; ?>>19</option>
                  <option value="20" <?php echo isset($no_of_bedrooms) && $no_of_bedrooms == 20 ? 'selected' : ''; ?>>20</option>
                </select>
              </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Add Guests
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <div class="dropdown-menu_sec">
              <div class="dropdown_cls">
                <div class="dropdown_list">
                  <h4>Adults</h4>
                  <p>Ages 13 or above</p>
                </div>
                <div class="guest_count">
                  <div class="wrap">
                    <button type="button" id="sub" onclick="remove(this)" data-id="adultCount" class="sub">-</button>
                    <input class="count adultCount" type="text" id="adultCount" value="{{$_GET['adultCount'] ?? 0}}" min="1" max="100" />
                    <button type="button" id="add" onclick="add(this)" data-id="adultCount" class="add">+</button>
                  </div>
                </div>
              </div>
              <div class="dropdown_cls">
                <div class="dropdown_list">
                  <h4>Children</h4>
                  <p>Ages 2-12</p>
                </div>
                <div class="guest_count">
                  <div class="wrap">
                    <button type="button" id="sub" onclick="remove(this)" data-id="childCount" class="sub">-</button>
                    <input class="count childCount" type="text" id="childCount" value="{{$_GET['childCount'] ?? 0}}" min="1" max="100" />
                    <button type="button" id="add" onclick="add(this)" data-id="childCount" class="add">+</button>
                  </div>
                </div>
              </div>
              <div class="dropdown_cls">
                <div class="dropdown_list">
                  <h4>Infants</h4>
                  <p>Under 2</p>
                </div>
                <div class="guest_count">
                  <div class="wrap">
                    <button type="button" id="sub" onclick="remove(this)" data-id="infantCount" class="sub">-</button>
                    <input class="count infantCount" type="text" id="infantCount" value="{{$_GET['infantCount'] ?? 0}}" min="1" max="100" />
                    <button type="button" id="add" onclick="add(this)" data-id="infantCount" class="add">+</button>
                  </div>
                </div>
              </div>
              <div class="dropdown_cls">
                <div class="dropdown_list">
                  <h4>Pets</h4>
                  <p>Bringing a service animal?</p>
                </div>
                <div class="guest_count">
                  <div class="wrap">
                    <button type="button" id="sub" onclick="remove(this)" data-id="petCount" class="sub">-</button>
                    <input class="count petCount" type="text" id="petCount" value="{{$_GET['petCount'] ?? 0}}" min="1" max="100" />
                    <button type="button" id="add" onclick="add(this)" data-id="petCount" class="add">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="search_toggle_footer">
      <a href="#">Clear All</a>
      <button class="btn primary_btn" onclick="filterMobileData()" >
        <img src="{{ URL::asset('assets/web/img/shortlet/search.svg')}}" alt="" style="filter: brightness(0) invert(1);">
      </button>

    </div>
  </div>
</div>
<script type="text/javascript">
  getCityArea();
  getCityInMobile();
  $(document).on('click', '.logout_web', function() {
      if (confirm("{{__('backend.confirm_box_logout')}}") == true) {
          window.location.href = "{{route('web.logout')}}";
      }
  })
  
  function getCityArea() {
    var province_id = $('#province_id').val();
    var area_id = "{{$area_id}}";

    if (province_id) {
      $.ajax({
        url:'{{url("getCityArea")}}/'+province_id+'/'+area_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_areas').html(result);
        }
      });
    }
  }
  function getCityInMobile() {
    var province_id = $('#province_id_mobile').val();
    var area_id = "{{$area_id}}";

    if (province_id) {
      $.ajax({
        url:'{{url("getCityArea")}}/'+province_id+'/'+area_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_areas_mobile').html(result);
        }
      });
    }
  }

  function add($this) {
    var className = $($this).attr('data-id');
    var inputVal = $('.'+className).val();
    $('.'+className).val(parseInt(inputVal)+1);

    var adultCount = $('.adultCount').val();
    var childCount = $('.childCount').val();
    var infantCount = $('.infantCount').val();
    var petCount = $('.petCount').val();

    var total_guest = parseInt(adultCount)+parseInt(childCount)+parseInt(infantCount)+parseInt(petCount);

    if (total_guest == 0) {
      $('.totalGuest').text('Add Guest');
    } else {
      $('.totalGuest').text(total_guest+' guest');
    }
  }

  function remove($this) {
    var className = $($this).attr('data-id');
    var inputVal = $('.'+className).val();
    var total = parseInt(inputVal)-1;

    if (total > 0) {
      $('.'+className).val(total);
    } else {
      $('.'+className).val(0);
    }

    var adultCount = $('.adultCount').val();
    var childCount = $('.childCount').val();
    var infantCount = $('.infantCount').val();
    var petCount = $('.petCount').val();

    var total_guest = parseInt(adultCount)+parseInt(childCount)+parseInt(infantCount)+parseInt(petCount);

    if (total_guest == 0) {
      $('.totalGuest').text('Add Guest');
    } else {
      $('.totalGuest').text(total_guest+' guest');
    }
  }

  function filterData() {
    var adultCount = $('.adultCount').val();
    var childCount = $('.childCount').val();
    var infantCount = $('.infantCount').val();
    var petCount = $('.petCount').val();
    var province_id = $('#province_id').val();
    var city_id = $('.show_cities').val();

    if (typeof city_id === 'undefined') {
      city_id = '';
    }
    var area_id = $('.show_areas').val();
    var checkIn = $('#datepicker2, #datepicker3').val();
    var checkOut = $('#datepicker12, #datepicker13').val();
    var no_of_bedrooms = $('.no_of_bedrooms').val();

    // console.log(adultCount, childCount, infantCount, petCount, province_id, city_id, checkIn, checkOut);
    var url = '{{url("properties")}}?province_id='+province_id+'&city_id='+city_id+'&area_id='+area_id+'&checkIn='+checkIn+'&checkOut='+checkOut+'&no_of_bedrooms='+no_of_bedrooms+'&adultCount='+adultCount+'&childCount='+childCount+'&infantCount='+infantCount+'&petCount='+petCount;
    window.location.assign(url);
  }

  function filterMobileData() {
    var adultCount = $('.adultCount').val();
    var childCount = $('.childCount').val();
    var infantCount = $('.infantCount').val();
    var petCount = $('.petCount').val();
    var province_id = $('#province_id_mobile').val();
    var city_id = $('.show_cities').val();

    if (typeof city_id === 'undefined') {
      city_id = '';
    }
    var area_id = $('.show_areas_mobile').val();
    var checkIn = $('#datepicker2, #datepicker3').val();
    var checkOut = $('#datepicker12, #datepicker13').val();
    var no_of_bedrooms = $('.no_of_bedrooms').val();

    // console.log(adultCount, childCount, infantCount, petCount, province_id, city_id, checkIn, checkOut);
    var url = '{{url("properties")}}?province_id='+province_id+'&city_id='+city_id+'&area_id='+area_id+'&checkIn='+checkIn+'&checkOut='+checkOut+'&no_of_bedrooms='+no_of_bedrooms+'&adultCount='+adultCount+'&childCount='+childCount+'&infantCount='+infantCount+'&petCount='+petCount;
    window.location.assign(url);
  }
</script>
<script type="text/javascript">
  $(function() {
    /*var start = moment();
    var end = moment();*/
    var start = moment();
    var end = moment();

    function cb(start, end) {
      $('#datepicker2, #datepicker3').val(start.format('MMM D, YYYY'));
      $('#datepicker12, #datepicker13').val(end.format('MMM D, YYYY'));
    }

    $('#daterangepicker, #daterangepicker_new').daterangepicker({
      minDate: new Date(),
      startDate: start,
      endDate: end,
      autoApply: true
    }, cb);

    // cb(start, end);
});

  $(document).mouseup(function (e) {
    if ($(e.target).closest(".dropdown-menu_sec").length === 0) {
      $('body').removeClass('header-dropdown-menu_tgl');
    }
  });

</script>