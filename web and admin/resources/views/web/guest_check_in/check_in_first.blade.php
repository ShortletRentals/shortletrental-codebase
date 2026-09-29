@extends('layouts.web.master')

@section('content')

    <main>
      <section class="property-sec">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 grad-bg">
              <div class="checkin-price-dtl">
                <div class="product_dtl_bg">
                  <div class="checkin-location-bg" style="background-image: {{url('uploads/property/'.$data->image)}};">
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
                  <div class="tabing_head">
                    <ul class="nav" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="javascript:void(0)" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" role="tab" aria-controls="all-tab-pane" aria-selected="true">Guests</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-tab-pane" role="tab" aria-controls="new-tab-pane" aria-selected="false">Organize your trip</a>
                      </li>
                      <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-tab-pane" role="tab" aria-controls="completed-tab-pane" aria-selected="false">Chat</a>
                      </li>   -->
                    </ul>
                  </div>
                  <div class="tabing_body_c">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                        <h4>Hi,</h4>
                        <p>please add the information of all the guests.</p>
                        <!-- <p>you have now completed all check-ins</p> -->
                        <div class="online_msg">
                            <h5><span>!</span>You must check-in online before your arrival</h5>
                            <p>Completing the information of all guests is <b>required to access the accommodation.</b></p>
                        </div>
                        <div class="guest_detail">
                          <h5>Main guest's details</h5>
                          @if(isset($organise_data) && $organise_data != null)
                          <div class="guest_bg">
                            <h5><i class="bx bx-check-circle-rounded" aria-hidden="true"></i>{{ isset($data) && $data->guest_first_name != null ? ucfirst($data->guest_first_name) : ucfirst($data->personal_first_name) }}</h5>
                          </div>
                          @else
                          <div class="guest_bg">
                            <h5>{{ isset($data) && $data->guest_first_name != null ? ucfirst($data->guest_first_name) : ucfirst($data->personal_first_name) }}</h5>
                            <div class="guest_check">
                              <a href="{{ url('/guest-area/checkin/personal/').'/'.$data->booking_token }}">Check In <img src="{{ URL::asset('assets/web/img/ar.png') }}"></a>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                      <div class="tab-pane fade" id="new-tab-pane" role="tabpanel" aria-labelledby="new-tab" tabindex="0">
                        <h4>Organise your trip. </h4><p>so we can help organise your arrival / departure.</p>
                        <div class="checkin-step-cont">
                        <!-- <form class="booking-form"> -->
                        <form method="POST" action="" class="organise_your_trip" id="organise_your_trip">
                          @csrf
                          <input type="hidden" name="booking_id" value="{{$data->id}}">
                          <input type="hidden" name="from_date" value="{{date('Y-m-d', strtotime($data->from_date))}}">
                          <input type="hidden" name="to_date" value="{{date('Y-m-d', strtotime($data->to_date))}}">
                          <h3>Arrival</h3>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Date</label>
                                <div class="date_wrap">
                                  <span>{{date('d F Y', strtotime($data->from_date))}}</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Arrival at The Property</label>
                                <select name="arrival_at_the_property" id="" class="form-control" data-parsley-required="true">
                                  <option value=""></option>
                                  <option value="15:00">15:00</option>
                                  <option value="12:00">12:00</option>
                                  <option value="11:30">11:30</option>
                                  <option value="09:30">09:30</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <h5>Mode of transport</h5>
                              <div class="form-group">
                                <label>Travelling By</label>
                                <select name="arrival_travelling_by" id="arrival_travelling_by" class="form-control">
                                  <option value=""></option>
                                  <option value="Car">Car</option>
                                  <option value="Plane">Plane</option>
                                  <option value="Bus">Bus</option>
                                  <option value="Train">Train</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Travelling From</label>
                                <input type="text" name="arrival_travelling_from" class="form-control">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Arriving At</label>
                                <input type="text" name="arrival_at" class="form-control">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Travel Company</label>
                                <input type="text" name="arrival_travel_company" class="form-control">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label class="arrival_number_label">Flight Number</label>
                                <input type="text" name="arrival_number" class="form-control">
                              </div>
                            </div>

                            <div class="col-md-6 arrival_travelling_div">
                              <div class="form-group">
                                <label>Time of Arrival</label>
                                <select name="arrival_time" id="arrival_time" class="form-control">
                                  <option value="00:00">00:00</option>
                                  <option value="00:15">00:15</option>
                                  <option value="00:30">00:30</option>
                                  <option value="00:45">00:45</option>
                                  <option value="01:00">01:00</option>
                                  <option value="01:15">01:15</option>
                                  <option value="01:30">01:30</option>
                                  <option value="01:45">01:45</option>
                                  <option value="02:00">02:00</option>
                                  <option value="02:15">02:15</option>
                                  <option value="02:30">02:30</option>
                                  <option value="02:45">02:45</option>
                                  <option value="03:00">03:00</option>
                                  <option value="03:15">03:15</option>
                                  <option value="03:30">03:30</option>
                                  <option value="03:45">03:45</option>
                                  <option value="04:00">04:00</option>
                                  <option value="04:15">04:15</option>
                                  <option value="04:30">04:30</option>
                                  <option value="04:45">04:45</option>
                                  <option value="05:00">05:00</option>
                                  <option value="05:15">05:15</option>
                                  <option value="05:30">05:30</option>
                                  <option value="05:45">05:45</option>
                                  <option value="06:00">06:00</option>
                                  <option value="06:15">06:15</option>
                                  <option value="06:30">06:30</option>
                                  <option value="06:45">06:45</option>
                                  <option value="07:00">07:00</option>
                                  <option value="07:15">07:15</option>
                                  <option value="07:30">07:30</option>
                                  <option value="07:45">07:45</option>
                                  <option value="08:00">08:00</option>
                                  <option value="08:15">08:15</option>
                                  <option value="08:30">08:30</option>
                                  <option value="08:45">08:45</option>
                                  <option value="09:00">09:00</option>
                                  <option value="09:15">09:15</option>
                                  <option value="09:30">09:30</option>
                                  <option value="09:45">09:45</option>
                                  <option value="10:00">10:00</option>
                                  <option value="10:15">10:15</option>
                                  <option value="10:30">10:30</option>
                                  <option value="10:45">10:45</option>
                                  <option value="11:00">11:00</option>
                                  <option value="11:15">11:15</option>
                                  <option value="11:30">11:30</option>
                                  <option value="11:45">11:45</option>
                                  <option value="12:00">12:00</option>
                                  <option value="12:15">12:15</option>
                                  <option value="12:30">12:30</option>
                                  <option value="12:45">12:45</option>
                                  <option value="13:00">13:00</option>
                                  <option value="13:15">13:15</option>
                                  <option value="13:30">13:30</option>
                                  <option value="13:45">13:45</option>
                                  <option value="14:00">14:00</option>
                                  <option value="14:15">14:15</option>
                                  <option value="14:30">14:30</option>
                                  <option value="14:45">14:45</option>
                                  <option value="15:00">15:00</option>
                                  <option value="15:15">15:15</option>
                                  <option value="15:30">15:30</option>
                                  <option value="15:45">15:45</option>
                                  <option value="16:00">16:00</option>
                                  <option value="16:15">16:15</option>
                                  <option value="16:30">16:30</option>
                                  <option value="16:45">16:45</option>
                                  <option value="17:00">17:00</option>
                                  <option value="17:15">17:15</option>
                                  <option value="17:30">17:30</option>
                                  <option value="17:45">17:45</option>
                                  <option value="18:00">18:00</option>
                                  <option value="18:15">18:15</option>
                                  <option value="18:30">18:30</option>
                                  <option value="18:45">18:45</option>
                                  <option value="19:00">19:00</option>
                                  <option value="19:15">19:15</option>
                                  <option value="19:30">19:30</option>
                                  <option value="19:45">19:45</option>
                                  <option value="20:00">20:00</option>
                                  <option value="20:15">20:15</option>
                                  <option value="20:30">20:30</option>
                                  <option value="20:45">20:45</option>
                                  <option value="21:00">21:00</option>
                                  <option value="21:15">21:15</option>
                                  <option value="21:30">21:30</option>
                                  <option value="21:45">21:45</option>
                                  <option value="22:00">22:00</option>
                                  <option value="22:15">22:15</option>
                                  <option value="22:30">22:30</option>
                                  <option value="22:45">22:45</option>
                                  <option value="23:00">23:00</option>
                                  <option value="23:15">23:15</option>
                                  <option value="23:30">23:30</option>
                                  <option value="23:45">23:45</option>
                                </select>
                              </div>
                            </div>


                          </div>
                          <div class="arival_wrap">
                            <h3>Departure</h3>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Date</label>
                                  <div class="date_wrap">
                                    <span>{{date('d F Y', strtotime($data->to_date))}}</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Departure From The Property</label>
                                  <select name="departure_at_the_property" id="departure_at_the_property" class="form-control" data-parsley-required="true">
                                    <option value=""></option>
                                    <option value="00:00">00:00</option>
                                    <option value="01:00">01:00</option>
                                    <option value="02:00">02:00</option>
                                    <option value="03:00">03:00</option>
                                    <option value="04:00">04:00</option>
                                    <option value="05:00">05:00</option>
                                    <option value="06:00">06:00</option>
                                    <option value="07:00">07:00</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <h5>Mode of transport</h5>
                                <div class="form-group">
                                  <label>Travelling By</label>
                                  <select name="departure_travelling_by" id="departure_travelling_by" class="form-control">
                                    <option value=""></option>
                                    <option value="Car">Car</option>
                                    <option value="Plane">Plane</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Train">Train</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-6 departure_travelling_div">
                                <div class="form-group">
                                  <label>Travelling From</label>
                                  <input type="text" name="departure_travelling_from" class="form-control">
                                </div>
                              </div>

                              <div class="col-md-6 departure_travelling_div">
                                <div class="form-group">
                                  <label>Arriving At</label>
                                  <input type="text" name="departure_at" class="form-control">
                                </div>
                              </div>

                              <div class="col-md-6 departure_travelling_div">
                                <div class="form-group">
                                  <label>Travel Company</label>
                                  <input type="text" name="departure_travel_company" class="form-control">
                                </div>
                              </div>

                              <div class="col-md-6 departure_travelling_div">
                                <div class="form-group">
                                  <label class="departure_number_label">Flight Number</label>
                                  <input type="text" name="departure_number" class="form-control">
                                </div>
                              </div>

                              <div class="col-md-6 departure_travelling_div">
                                <div class="form-group">
                                  <label>Time of Departure</label>
                                  <select name="departure_time" id="departure_time" class="form-control">
                                    <option value="00:00">00:00</option>
                                    <option value="00:15">00:15</option>
                                    <option value="00:30">00:30</option>
                                    <option value="00:45">00:45</option>
                                    <option value="01:00">01:00</option>
                                    <option value="01:15">01:15</option>
                                    <option value="01:30">01:30</option>
                                    <option value="01:45">01:45</option>
                                    <option value="02:00">02:00</option>
                                    <option value="02:15">02:15</option>
                                    <option value="02:30">02:30</option>
                                    <option value="02:45">02:45</option>
                                    <option value="03:00">03:00</option>
                                    <option value="03:15">03:15</option>
                                    <option value="03:30">03:30</option>
                                    <option value="03:45">03:45</option>
                                    <option value="04:00">04:00</option>
                                    <option value="04:15">04:15</option>
                                    <option value="04:30">04:30</option>
                                    <option value="04:45">04:45</option>
                                    <option value="05:00">05:00</option>
                                    <option value="05:15">05:15</option>
                                    <option value="05:30">05:30</option>
                                    <option value="05:45">05:45</option>
                                    <option value="06:00">06:00</option>
                                    <option value="06:15">06:15</option>
                                    <option value="06:30">06:30</option>
                                    <option value="06:45">06:45</option>
                                    <option value="07:00">07:00</option>
                                    <option value="07:15">07:15</option>
                                    <option value="07:30">07:30</option>
                                    <option value="07:45">07:45</option>
                                    <option value="08:00">08:00</option>
                                    <option value="08:15">08:15</option>
                                    <option value="08:30">08:30</option>
                                    <option value="08:45">08:45</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:15">09:15</option>
                                    <option value="09:30">09:30</option>
                                    <option value="09:45">09:45</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:15">10:15</option>
                                    <option value="10:30">10:30</option>
                                    <option value="10:45">10:45</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:15">11:15</option>
                                    <option value="11:30">11:30</option>
                                    <option value="11:45">11:45</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:15">12:15</option>
                                    <option value="12:30">12:30</option>
                                    <option value="12:45">12:45</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:15">13:15</option>
                                    <option value="13:30">13:30</option>
                                    <option value="13:45">13:45</option>
                                    <option value="14:00">14:00</option>
                                    <option value="14:15">14:15</option>
                                    <option value="14:30">14:30</option>
                                    <option value="14:45">14:45</option>
                                    <option value="15:00">15:00</option>
                                    <option value="15:15">15:15</option>
                                    <option value="15:30">15:30</option>
                                    <option value="15:45">15:45</option>
                                    <option value="16:00">16:00</option>
                                    <option value="16:15">16:15</option>
                                    <option value="16:30">16:30</option>
                                    <option value="16:45">16:45</option>
                                    <option value="17:00">17:00</option>
                                    <option value="17:15">17:15</option>
                                    <option value="17:30">17:30</option>
                                    <option value="17:45">17:45</option>
                                    <option value="18:00">18:00</option>
                                    <option value="18:15">18:15</option>
                                    <option value="18:30">18:30</option>
                                    <option value="18:45">18:45</option>
                                    <option value="19:00">19:00</option>
                                    <option value="19:15">19:15</option>
                                    <option value="19:30">19:30</option>
                                    <option value="19:45">19:45</option>
                                    <option value="20:00">20:00</option>
                                    <option value="20:15">20:15</option>
                                    <option value="20:30">20:30</option>
                                    <option value="20:45">20:45</option>
                                    <option value="21:00">21:00</option>
                                    <option value="21:15">21:15</option>
                                    <option value="21:30">21:30</option>
                                    <option value="21:45">21:45</option>
                                    <option value="22:00">22:00</option>
                                    <option value="22:15">22:15</option>
                                    <option value="22:30">22:30</option>
                                    <option value="22:45">22:45</option>
                                    <option value="23:00">23:00</option>
                                    <option value="23:15">23:15</option>
                                    <option value="23:30">23:30</option>
                                    <option value="23:45">23:45</option>
                                  </select>
                                </div>
                              </div>

                            </div>
                          </div>   
                          <div class="col-sm-12">
                              <div class="d-flex mt-3">
                                <input type="submit" class="btn primary_btn" value="Send">
                                <!-- <a href="#" class="btn primary_btn">Send</a> -->
                              </div>
                          </div>    
                        </form>               
                      </div>
                      </div>
                      <div class="tab-pane fade" id="completed-tab-pane" role="tabpanel" aria-labelledby="completed-tab" tabindex="0">
                        <div class="chat-wrapper">
                          <div class="filter_mobile_s">
                            <h4>Chat Listing</h4> <img src="{{ URL::asset('assets/web/img/filter.png') }}">
                          </div>
                          <div class="chat-sidebar">
                            <a href="javascript:void(0);" class="filter_cross">✖</a>
                            <div class="chat-sidebar-content">
                              <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-Chats">
                                  <div class="chat-list">
                                    <div class="list-group list-group-flush">
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/peter-parker.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Louis Litt</h6>
                                            <p class="mb-0 chat-msg">You just got LITT up, Mike.</p>
                                          </div>
                                          <div class="chat-time">9:51 AM</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item active">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/kattie.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Harvey Specter</h6>
                                            <p class="mb-0 chat-msg">Wrong. You take the gun....</p>
                                          </div>
                                          <div class="chat-time">4:32 PM</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/peter-parker.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Rachel Zane</h6>
                                            <p class="mb-0 chat-msg">I was thinking that we could...</p>
                                          </div>
                                          <div class="chat-time">Wed</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/kattie.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Donna Paulsen</h6>
                                            <p class="mb-0 chat-msg">Mike, I know everything!</p>
                                          </div>
                                          <div class="chat-time">Tue</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/peter-parker.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Jessica Pearson</h6>
                                            <p class="mb-0 chat-msg">Have you finished the draft...</p>
                                          </div>
                                          <div class="chat-time">9/3/2020</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/kattie.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Harold Gunderson</h6>
                                            <p class="mb-0 chat-msg">Thanks Mike! :)</p>
                                          </div>
                                          <div class="chat-time">12/3/2020</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/peter-parker.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Katrina Bennett</h6>
                                            <p class="mb-0 chat-msg">I've sent you the files for...</p>
                                          </div>
                                          <div class="chat-time">16/3/2020</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/kattie.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Charles Forstman</h6>
                                            <p class="mb-0 chat-msg">Mike, this isn't over.</p>
                                          </div>
                                          <div class="chat-time">18/3/2020</div>
                                        </div>
                                      </a>
                                      <a href="javascript:;" class="list-group-item">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ URL::asset('assets/web/img/peter-parker.png') }}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Jonathan Sidwell</h6>
                                            <p class="mb-0 chat-msg">That's bullshit. This deal..</p>
                                          </div>
                                          <div class="chat-time">24/3/2020</div>
                                        </div>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="chat-content">
                            <div class="chat-content-leftside">
                              <div class="d-flex">
                                <div class="flex-grow-1 ms-2">
                                  <p class="chat-left-msg">Hi, harvey where are you now a days?</p>
                                  <p class="chat-time">Harvey, 2:35 PM</p>
                                </div>
                              </div>
                            </div>
                            <div class="chat-content-rightside">
                              <div class="d-flex ms-auto">
                                <div class="flex-grow-1 me-2">
                                  <p class="chat-right-msg">I am in USA</p>
                                  <p class="chat-time text-end">you, 2:37 PM</p>
                                </div>
                              </div>
                            </div>  
                          </div>
                          <div class="chat-footer d-flex align-items-center">
                            <div class="flex-grow-1 pe-2">
                              <div class="input-group">
                                <input type="text" class="form-control" placeholder="Type a message">
                                <span class="input-group-text"><img src="{{ URL::asset('assets/web/img/send.png') }}" alt="Send"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
</script>
@endsection