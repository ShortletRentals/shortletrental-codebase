@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');

    if (isset($auth_user)) {
      $userId = $auth_user->data->id;
    }
?>
@section('content')
    <main>
      <section class="detail_page space-cls">
          <div class="container">
            <div class="detail_title">
              <h3>{{$data['propertyData']->title}}</h3>
              <div class="share_opt">
                <?php $username = 'Demo'; ?>
                <div class="share-link dropdown">
                  <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownShare" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span><img src="{{ URL::asset('assets/web/img/share-icon.png')}}"></span> Share 
                  </a>
                  <div class="dropdown-menu share-dropdown" aria-labelledby="navbarDropdownShare">
                        <ul>
                           <li class="facebook">
                              <a href="javascript:;" onclick="window.open('https://facebook.com/sharer.php?u={{url('/property-detail'.$data['propertyData']->id,$username)}}')">
                                 <img src="{{ URL::asset('assets/web/img/facebook.png')}}"> Facebook
                              </a>
                           </li>
                           <li class="twiter">
                              <a href="javascript:;" onclick="window.open('https://twitter.com/share?url={{url('/property-detail'.$data['propertyData']->id,$username)}}&via=ShortletRental&hashtags=property')">
                                 <img src="{{ URL::asset('assets/web/img/twitter.png')}}"> Twitter
                              </a>
                           </li>
                           <li class="whatup">
                              <a href="javascript:;" onclick="window.open('https://web.whatsapp.com/send?text={{url('/property-detail'.$data['propertyData']->id,$username)}}')">
                                 <img src="{{ URL::asset('assets/web/img/whatsapp.png')}}"> What's app
                              </a>
                           </li>
                        </ul>
                     </div>
                </div>
                
                 
                <!-- <div class="fav_link">
                  <a href="#">
                    <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                  </a>
                </div> -->
                <div class="fav_link" onclick="addToWishList(this)" data-id="{{$data['propertyData']->id}}">
                  <span class="heart_empty_product_{{$data['propertyData']->id}} {{$data['propertyData']->is_fav == 0 ? '' : 'd-none'}}">
                    <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                  </span>
                  <span class="heart_filled_product_{{$data['propertyData']->id}}  {{$data['propertyData']->is_fav == 1 ? '' : 'd-none'}}">
                    <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                  </span>
                </div>
              </div>
            </div>
            <div class="meta_dlt">
              <ul class="meta_list">
                <li class="meta_single">
                  <span class="pro-ic">
                    <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
                  </span>
                  <span>Superhost</span>
                </li>
                <li class="meta_single">
                  <div class="location-cls">
                    <div class="location-icon">
                      <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
                    </div>
                    <div class="location-cont">
                      <p>{{$data['propertyData']->getPropertyAddress[0]->address}}</p>
                    </div>
                  </div>
                </li>
                <li class="meta_single">
                  <div class="reting-cls">
                    <div class="reting-icon">
                      <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                    </div>
                    <div class="location-cont">
                      <p>0</p>
                    </div>
                  </div>
                </li>
                <li class="meta_single">
                  <span>0 Reviews</span>
                </li>
              </ul>
            </div>
            <div class="gallery">
              <div class="row">
                <div class="col-md-6">
                  <div  class="gallery-img">
                    <a class="example-image-link" href="{{$data['propertyData']->image}}" data-lightbox="example-set" data-title=""><img class="example-image" src="{{$data['propertyData']->image}}" alt=""/></a>
                    <!-- <img src="{{$data['propertyData']->image}}"> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    @if(count($data['propertyImages']) > 0)
                      @foreach($data['propertyImages'] as $key => $image)
                        @if($key <= 3)
                        <div class="col-md-6">
                          <div  class="gallery-img">
                            <a class="example-image-link" href="{{ $image->image }}" data-lightbox="example-set" data-title=""><img class="example-image" src="{{ $image->image }}" alt=""/></a>
                            <!-- <img src="{{ $image->image }}"> -->
                          </div>
                        </div>
                        @else
                          <div class="col-md-6 d-none">
                            <div  class="gallery-img">
                              <a class="example-image-link" href="{{ $image->image }}" data-lightbox="example-set" data-title=""><img class="example-image" src="{{ $image->image }}" alt=""/></a>
                              <!-- <img src="{{ $image->image }}"> -->
                            </div>
                          </div>
                        @endif
                      @endforeach
                    @endif
                    <!-- <div class="col-md-6">
                      <div  class="gallery-img">
                        <img src="{{ URL::asset('assets/web/img/dtl-2.png')}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div  class="gallery-img">
                        <img src="{{ URL::asset('assets/web/img/dtl-3.png')}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div  class="gallery-img">
                        <img src="{{ URL::asset('assets/web/img/dtl-4.png')}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div  class="gallery-img">
                        <img src="{{ URL::asset('assets/web/img/dtl-5.png')}}">
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="product_dtl_cont">
              <div class="product_dtl_left">
                <div class="product_user_title">
                  <div class="user_wrap">
                    <span class="user_img">
                      <img src="{{ URL::asset('assets/web/img/user-profile.png')}}" alt="">
                    </span>
                    <h2>{{$data['propertyData']->host_name}} <span>(Host)</span></h2>
                  </div>
                  <div class="chat-option">
                    <a href="" class="btn secondary_btn">
                      <span class="chat_icon"><img src="{{ URL::asset('assets/web/img/message.png')}}" alt=""></span>
                      <span>Chat With a Booking Specialist</span>
                    </a>
                  </div>
                </div>
                <div class="product_tag">
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt=""></span> Occupants: {{$data['propertyData']->max_guest}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/bed.png')}}" alt=""></span> King Size Beds: {{$data['propertyData']->getPropertyBedroom[0]->no_of_kingsize_bed}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/bed.png')}}" alt=""></span> Queen Size Beds: {{$data['propertyData']->getPropertyBedroom[0]->no_of_qweensize_bed}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/home.png')}}" alt=""></span> Bedrooms: {{$data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms}}</span>
                  </div>
                </div>
                <div class="product_desc">
                    <h4 class="heading-inner-title">Description</h4>
                    <p>{{$data['propertyData']->additional_notes}}</p>
                </div>
                
                @if(count($data['propertyAmenities']) > 0)
                <div class="special_feature_sec">
                  <h4 class="heading-inner-title">Special Features</h4>
                  <ul>
                    @foreach($data['propertyAmenities'] as $amenities)
                      <li>
                        <span class="sf-icon">
                          @if(isset($amenities) && $amenities->image != Null)
                          <img src="{{ $amenities->image }}" alt="">
                          @endif
                        </span>
                        <span>{{ $amenities->name }}</span>
                      </li>
                    @endforeach
                    <!-- <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-1.png')}}" alt="">
                      </span>
                      <span>Sea view</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-2.png')}}" alt="">
                      </span>
                      <span>Beach access – Beachfront</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-3.png')}}" alt="">
                      </span>
                      <span>Kitchen</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-4.png')}}" alt="">
                      </span>
                      <span>Wifi</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-5.png')}}" alt="">
                      </span>
                      <span>Free driveway parking on premises – 1 space</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-6.png')}}" alt="">
                      </span>
                      <span>Air conditioning</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-7.png')}}" alt="">
                      </span>
                      <span>Patio or balcony</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-8.png')}}" alt="">
                      </span>
                      <span>Private backyard – Fully fenced</span>
                    </li>
                    <li>
                      <span class="sf-icon">
                        <img src="{{ URL::asset('assets/web/img/sf-9.png')}}" alt="">
                      </span>
                      <span>Luggage drop-off allowed</span>
                    </li> -->

                  </ul>
                </div>
                @endif

                @if(count($data['propertyData']->getExtraService) > 0)
                  <div class="other_service_sec">
                    <h4 class="heading-inner-title">Mandatory & Included Services</h4>
                    <div class="list-serv">

                      @foreach($data['propertyData']->getExtraService as $extraService)

                        @if (ucwords($extraService->getServiceData->name) != 'Security Deposit')
                          <div class="single_serv">
                            <h6>{{ ucwords($extraService->getServiceData->name) }}</h6>
                            <h5>Included</h5>
                          </div>
                        @endif
                      @endforeach
                      <!-- <div class="single_serv">
                        <h6>Internet Access</h6>
                        <h5>Included</h5>
                      </div>
                      <div class="single_serv">
                        <h6>Security deposit (Refundable)</h6>
                        <h5>NGN50,000.00 /booking</h5>
                      </div> -->
                    </div>
                  </div>
                  <!-- <div class="other_service_sec">
                    <h4 class="heading-inner-title">Optional services</h4>
                    <div class="list-serv">
                      <div class="single_serv">
                        <h6>Early Check in/Late check Out</h6>
                        <h5>NGN10,000.00 /booking</h5>
                      </div>
                      <div class="single_serv">
                        <h6>Video shoot</h6>
                        <h5>NGN50,000.00 /booking</h5>
                      </div>
                    </div>
                  </div> -->
                @endif
                <div class="celender_cls">
                  <div class="celecder-title">
                    <div class="celender-title-left">
                        <h4 class="heading-inner-title">5 nights in Aewol-eup, Cheju</h4>
                        <p>20 Nov 2022 - 25 Nov 2022</p>
                    </div>
                    <div class="celender-title-right">
                        <a href="#" class="btn primary_btn">Clear Dates</a>
                    </div>
                  </div>
                  <div class="celecder-row">
                    <div class="celecder-col">
                      <div class="app">
                        <div class="app__main">
                          <div class="calendar">
                              <div id="calendar"></div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="celecder-col">
                      <div class="app">
                        <div class="app__main">
                          <div class="calendar">
                              <div id="calendar1"></div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                @if(count($data['propertyData']->getExtraService) > 0)

                  @foreach($data['propertyData']->getExtraService as $extraService)

                    @if (ucwords($extraService->getServiceData->name) == 'Security Deposit')
                      <div class="security-deposit-sec">
                          <h4 class="heading-inner-title">Security Deposit</h4>
                          <div class="security-listing">
                            <h5>Amount: <span>NGN{{ ucwords($extraService->getServiceData->price) }} /booking</span></h5>
                            <h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
                            <p>To be paid when booking or Checkout.</p>
                          </div>
                      </div>
                    @endif
                  @endforeach
                @endif
                <div class="review-sec">
                  <ul class="meta_list">
                    <li class="meta_single">
                      <div class="reting-cls">
                        <div class="reting-icon">
                          <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                        </div>
                        <div class="location-cont">
                          <p>4.5</p>
                        </div>
                      </div>
                    </li>
                    <li class="meta_single">
                      <span>22 Reviews</span>
                    </li>
                  </ul>
                  <div class="review-view">
                    <div class="review-view-left">
                      <div class="review-inner">
                          <span class="review_left">Cleanliness</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width:90%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.5</span>  
                          </div>
                      </div>
                      <div class="review-inner">
                          <span class="review_left">Communication</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.9</span>  
                          </div>
                      </div>
                      <div class="review-inner">
                          <span class="review_left">Check-in</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.9</span>  
                          </div>
                      </div>
                    </div>
                    <div class="review-view-left">
                      <div class="review-inner">
                          <span class="review_left">Accuracy</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width:88%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.5</span>  
                          </div>
                      </div>
                      <div class="review-inner">
                          <span class="review_left">Location</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.9</span>  
                          </div>
                      </div>
                      <div class="review-inner">
                          <span class="review_left">Value</span>
                          <div class="review_right">
                            <div class="progress">
                              <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>4.4</span>  
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="reviewer_sec">
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                      <div class="review-single">
                        <div class="reviewer-inner">
                          <div class="reviewer_img">
                            <img src="{{ URL::asset('assets/web/img/andrea.png')}}" alt="">
                          </div>
                          <div class="reviewer_cont">
                            <h5>Gina</h5>
                            <p>August 2022</p>
                          </div>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                      </div>
                  </div>
                  <div class="see-all-review d-flex">
                    <a href="#" class="btn primary_btn">See All Reviews</a>
                  </div>
                </div>
                <div class="distance-sec">
                  <h4 class="heading-inner-title">Map and Distances</h4>
                  <div class="map-img contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1465667.6741617478!2d7.120038189595075!3d9.670463454963356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0baf7da48d0d%3A0x99a8fe4168c50bc8!2sNigeria!5e0!3m2!1sen!2sin!4v1668000370753!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                  </div>
                </div>
                <div class="things-sec">
                  <h4 class="heading-inner-title">Things to know</h4>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="things-box">
                        <h5>Booking Condition</h5>
                        <p>From the booking date until 15 days before the check-in, there is no cancellation penalty</p>
                        <!-- <a href="#" class="view-more">View More</a> -->
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="things-box">
                        <h5>Additional Notes</h5>
                        <p>Refund of security deposit to the credit card 24/48h after your departure</p>
                        <!-- <a href="#" class="view-more">View More</a> -->
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="things-box">
                        <h5>Cancellation Policy</h5>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy</p>
                        <a href="#" class="view-more">View More</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="product-dtl-price">
                <div class="product_dtl_bg">
                  <div class="product_dtl_price">
                    <h3>NGN {{$data['propertyData']->price}}<span> night</span></h3>
                  </div>
                  <div class="checkinout-sec">
                    <div class="checkin_checkout" id="daterangepicker_detailPage">
                      <input type="hidden" name="start_date" class="start_date_detailPage" value="">
                      <input type="hidden" name="end_date" class="end_date_detailPage" value="">
                      <input type="hidden" name="total_days" class="total_days_detailPage" value="">
                      <input type="hidden" name="per_night_price" class="per_night_price" value="{{$data['propertyData']->price}}">
                      <input type="hidden" name="total_booking_amount" class="total_booking_amount" value="">
                      <input type="hidden" name="property_id" class="property_id" value="{{$data['propertyData']->id}}">
                      <div class="checkin_wrap">
                        <label>CHECK-IN</label>
                        <p id="datepicker2_detailPage">Nov 01, 2022</p>
                      </div>
                      <div class="checkin_wrap">
                        <label>CHECK-OUT</label>
                        <p id="datepicker12_detailPage">Nov 05, 2022</p>
                      </div>
                    </div>
                    <div class="checkout-dtl">
                      <div class="guest-add">
                        <div class="guest-main">
                          <div class="guest-left">
                            <h4>Who</h4>
                            <div class="dropdown_sec">
                              <a href="javascript:void(0);" class="dropdown_sec_sec"><span class='totalGuest_detail'>Add Guest</span></a>
                              <div class="dropdown-menu_sec">
                                <div class="dropdown_cls">
                                  <div class="dropdown_list">
                                    <h4>Adults</h4>
                                    <p>Ages 13 or above</p>
                                  </div>
                                  <div class="guest_count">
                                    <div class="wrap">
                                      <button type="button" id="sub" onclick="remove_detailPage(this)" data-id="adultCount_detail" class="sub">-</button>
                                      <input class="count adultCount_detail" type="text" id="adultCount_detail" value="0" min="1" max="100" />
                                      <button type="button" id="add" onclick="add_detailPage(this)" data-id="adultCount_detail" class="add">+</button>
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
                                      <button type="button" id="sub" onclick="remove_detailPage(this)" data-id="childCount_detail" class="sub">-</button>
                                      <input class="count childCount_detail" type="text" id="childCount_detail" value="0" min="1" max="100" />
                                      <button type="button" id="add" onclick="add_detailPage(this)" data-id="childCount_detail" class="add">+</button>
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
                                      <button type="button" id="sub" onclick="remove_detailPage(this)" data-id="infantCount_detail"  class="sub">-</button>
                                      <input class="count infantCount_detail" type="text" id="infantCount_detail" value="0" min="1" max="100" />
                                      <button type="button" id="add" onclick="add_detailPage(this)" data-id="infantCount_detail" class="add">+</button>
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
                                      <button type="button" id="sub" onclick="remove_detailPage(this)" data-id="petCount_detail" class="sub">-</button>
                                      <input class="count petCount_detail" type="text" id="petCount_detail" value="0" min="1" max="100" />
                                      <button type="button" id="add" onclick="add_detailPage(this)" data-id="petCount_detail" class="add">+</button>
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
                  <div class="booknow-cls">
                    <a href="javascript:void(0);" onclick="submitCartData()" class="btn primary_btn">Book Now</a>
                  </div>
                  <div class="charged-cls">
                    <p>You won't be charged yet</p>
                  </div>
                  <div class="hotel-price-wrap">
                    <ul>
                      <li>
                        <span class="left_price_p">NGN {{$data['propertyData']->price}} <span><img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> <span class="total_nights">1</span> nights</span>
                        <span class="right_price_p days_price">NGN {{$data['propertyData']->price}}</span>
                      </li>
                      <!-- <li>
                        <span class="left_price_p">Service fee</span>
                        <span class="right_price_p">NGN 100.00</span>
                      </li> -->
                    </ul>
                    <ul class="total_price">
                      <li>
                        <span class="left_price_p">Total Price</span>
                        <span class="right_price_p grand_total_price">NGN {{$data['propertyData']->price}}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
    </main>
@endsection
@section('script')
<script type="text/javascript">
  $('.dropdown-menu-detail-page').on('click', function (event) {
      $(this).parent().toggleClass('open');
  });
  
  function addToWishList($this) {
    var product_id = $($this).attr('data-id');
    var user_id = "{{$userId ?? ''}}";

    if (user_id != '') {
      //AddToWishList
      const element = document.querySelector(".heart_empty_product_"+product_id);

      if (element.classList.contains("d-none") == true) {
        $('.heart_empty_product_'+product_id).removeClass('d-none');
        $('.heart_filled_product_'+product_id).addClass('d-none');

      } else {
        $('.heart_empty_product_'+product_id).addClass('d-none');
        $('.heart_filled_product_'+product_id).removeClass('d-none');
      }
      //call ajax for addtowishlist
      var formData = new FormData(); // Currently empty
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      formData.append('userId', user_id);
      formData.append('product_id', product_id);

      $.ajax({
        url: '{{ route("web.addToWishList") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
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

    } else {
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }

  function add_detailPage($this) {
    var className = $($this).attr('data-id');
    var inputVal = $('.'+className).val();
    $('.'+className).val(parseInt(inputVal)+1);

    var adultCount = $('.adultCount_detail').val();
    var childCount = $('.childCount_detail').val();
    var infantCount = $('.infantCount_detail').val();
    var petCount = $('.petCount_detail').val();

    var total_guest = parseInt(adultCount)+parseInt(childCount)+parseInt(infantCount)+parseInt(petCount);

    if (total_guest == 0) {
      $('.totalGuest_detail').text('Add Guest');

    } else {
      $('.totalGuest_detail').text(total_guest+' guest');
    }
  }

  function remove_detailPage($this) {
    var className = $($this).attr('data-id');
    var inputVal = $('.'+className).val();
    var total = parseInt(inputVal)-1;

    if (total > 0) {
      $('.'+className).val(total);
    } else {
      $('.'+className).val(0);
    }

    var adultCount = $('.adultCount_detail').val();
    var childCount = $('.childCount_detail').val();
    var infantCount = $('.infantCount_detail').val();
    var petCount = $('.petCount_detail').val();

    var total_guest = parseInt(adultCount)+parseInt(childCount)+parseInt(infantCount)+parseInt(petCount);

    if (total_guest == 0) {
      $('.totalGuest_detail').text('Add Guest');

    } else {
      $('.totalGuest_detail').text(total_guest+' guest');
    }
  }

  function submitCartData() {

    var user_id = "{{$userId ?? ''}}";

    if (user_id != '') {
      var start_date_detailPage = $('.start_date_detailPage').val();
      var end_date_detailPage = $('.end_date_detailPage').val();
      var total_days_detailPage = $('.total_days_detailPage').val();
      var per_night_price = $('.per_night_price').val();
      var total_booking_amount = $('.total_booking_amount').val();
      var property_id = $('.property_id').val();
      var adultCount = $('.adultCount_detail').val();
      var childCount = $('.childCount_detail').val();
      var infantCount = $('.infantCount_detail').val();
      var petCount = $('.petCount_detail').val();

      var formData = new FormData(); // Currently empty
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      formData.append('start_date', start_date_detailPage);
      formData.append('end_date', end_date_detailPage);
      formData.append('total_days', total_days_detailPage);
      formData.append('per_night_price', per_night_price);
      formData.append('total_booking_amount', total_booking_amount);
      formData.append('property_id', property_id);
      formData.append('adultCount', adultCount);
      formData.append('childCount', childCount);
      formData.append('infantCount', infantCount);
      formData.append('petCount', petCount);

      $.ajax({
        url: '{{ route("web.addToCart") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {

          if (res.status === true) {
            toastr.success(res.message);
            window.location.href = '{{ route("web.checkout") }}';
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

    } else {
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }
</script>
<script type="text/javascript">
  $(function() {

    /*var start = moment();
    var end = moment();*/
    var start_detailPage = moment();
    var end_detailPage = moment().add(1, 'days');

    function cb_detailPage(start_detailPage, end_detailPage) {

        $('#datepicker2_detailPage').text(start_detailPage.format('MMM D, YYYY'));
        $('#datepicker12_detailPage').text(end_detailPage.format('MMM D, YYYY'));

        $('.start_date_detailPage').val(start_detailPage.format('YYYY-MM-DD'));
        $('.end_date_detailPage').val(end_detailPage.format('YYYY-MM-DD'));
   
        var totalDays = parseInt(end_detailPage.diff(start_detailPage, 'days'));
        $('.total_days_detailPage').val(totalDays);
        $('.total_nights').text(totalDays);

        var days_price = parseInt($('.per_night_price').val())*parseInt(totalDays);
        var grand_total_price = parseInt($('.per_night_price').val())*parseInt(totalDays);

        $('.days_price').text('NGN '+days_price);
        $('.grand_total_price').text('NGN '+grand_total_price);
        $('.total_booking_amount').val(grand_total_price);
    }

    $('#daterangepicker_detailPage').daterangepicker({
        startDate: start_detailPage,
        endDate: end_detailPage,
        autoApply: true
    }, cb_detailPage);

    cb_detailPage(start_detailPage, end_detailPage);
    
});
</script>
@endsection