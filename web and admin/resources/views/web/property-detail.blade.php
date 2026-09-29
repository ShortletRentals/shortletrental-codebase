@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');
    $username = 'Demo';
    if (isset($auth_user)) {
      $userId = $auth_user->data->id;
      $username = $auth_user->data->name;
    }
?>

@if(isset($data) && isset($data['propertyData']))
@section('title')
{{$data['propertyData']->title}} - 
@if(isset($data['propertyData']->getPropertyAddress[0]))
{{$data['propertyData']->getPropertyAddress[0]->getPropertyCity->name}}
@endif
- 
@if(isset($data['propertyData']->getPropertyArea[0]))
{{$data['propertyData']->getPropertyArea[0]->getPropertyArea->name}}
@endif
@endsection
@section('metadata')


<link rel="canonical" href="{{url('property-detail/'.$data['propertyData']->id)}}"/>
<link rel="alternate" hreflang="en" href="{{url('property-detail/'.$data['propertyData']->id)}}" />
<meta name="description" content="{{$data['propertyData']->title}} ">
<meta name="keywords" content="{{$data['propertyData']->title}} - {{$data['propertyData']->description}}">
<meta name="language" content="en">
<meta content=Holidays name=classification>
<meta name="revisit-after" content="1 month">
<meta name="rating" content="General">
<meta property="og:title" content="{{$data['propertyData']->title}} " />
<meta property="og:description" content="{{$data['propertyData']->title}} - {{$data['propertyData']->description}}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{url('property-detail/'.$data['propertyData']->id)}}" />
<meta property="og:image" content="{{$data['propertyData']->image}}" />
<meta property="og:image:width" content="650" />
<meta property="og:image:height" content="450" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{$data['propertyData']->title}}">
<meta name="twitter:description" content="{{$data['propertyData']->title  }} - {{$data['propertyData']->description}}">
<meta name="twitter:image:src" content="{{$data['propertyData']->image}}">
<meta name="twitter:domain" content="{{url('property-detail/'.$data['propertyData']->id)}}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="robots" content="index,follow">
<meta http-equiv="X-UA-Compatible" content="IE=edge" ><meta http-equiv="Content-Type" content="text/html; charset=utf-8">


@endsection

@endif

@section('content')

<style>
  .fa-icon-box{
    border: 1px solid #a7a7a7;
    padding: 4px;
    padding-left: 12px;
    padding-right: 11px;
  }
</style>
    
    
      <main>
      <section class="detail_page space-cls">
          
          <div class="container">
            <div class="breadcrumb-cls">
              <ol class="cd-breadcrumb">
                <li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
                <li>></li>

                @if(isset($data['propertyData']->getPropertyAddress[0]))

                  @if($data['propertyData']->getPropertyAddress[0]->getPropertyCity)
                    <li><a href="{{url('properties?city_id=').$data['propertyData']->getPropertyAddress[0]->getPropertyCity->id}}">{{$data['propertyData']->getPropertyAddress[0]->getPropertyCity->name}}</a></li>
                    <li>></li>
                  @endif

                  @if($data['propertyData']->getPropertyAddress[0]->getPropertyArea)
                    <li><a href="{{url('properties?area_id=').$data['propertyData']->getPropertyAddress[0]->getPropertyArea->id}}">{{$data['propertyData']->getPropertyAddress[0]->getPropertyArea->name}}</a></li>
                    <li>></li>
                  @endif
                @endif
                <li class="current">{{$data['propertyData']->title}}</li>
              </ol>
            </div>
            <div class="detail_title">
              <h3>{{$data['propertyData']->title}}</h3>
                @if($data['propertyData']->video_url)
                  <?php $video_url = $data['propertyData']->video_url;
                    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches);
                    $video_id = $matches[1] ?? 0;
                  ?>
                  <div class="watch_vid_btn">
                    <a href="javascript:void(0)" id="video1" class="btn primary_btn youtube" onclick="removeVideoPopupClass()"><i class="fa fa-play-circle"></i> Watch The Video</a>
                    <div class="vid_popup_display_cls d-none">
                      <div id="vidBox">
                        <div id="videCont">
                           <div id="yt_video">
                            <i class="fa fa-play-circle"></i> Watch The Video
                            <iframe id="v1" src="https://www.youtube.com/embed/{{$video_id}}?rel=0" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                           </div>
                        </div> 
                      </div>
                    </div>
                  </div>
                @endif
              <div class="share_opt">
                <div class="share-link dropdown">
                  <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownShare" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span><img src="{{ URL::asset('assets/web/img/shortlet/share-icon.svg')}}"></span> Share 
                  </a>
                  <div class="dropdown-menu share-dropdown" aria-labelledby="navbarDropdownShare">
                        <ul>
                           <li class="facebook">
                              <a href="javascript:;" onclick="window.open('https://facebook.com/sharer.php?u={{url('/property-detail/'.$data['propertyData']->id)}}')">
                                 <img src="{{ URL::asset('assets/web/img/shortlet/facebook.svg')}}"> Facebook
                              </a>
                           </li>
                           <li class="twiter">
                              <a href="javascript:;" onclick="window.open('https://twitter.com/share?url={{url('/property-detail/'.$data['propertyData']->id)}}&via=ShortletRental&hashtags=property')">
                                 <img src="{{ URL::asset('assets/web/img/shortlet/twitter.svg')}}"> Twitter
                              </a>
                           </li>
                           <li class="whatup">
                              <a href="javascript:;" onclick="window.open('https://web.whatsapp.com/send?text={{url('/property-detail/'.$data['propertyData']->id)}}')">
                                 <img src="{{ URL::asset('assets/web/img/shortlet/whatsapp.svg')}}"> What's app
                              </a>
                           </li>
                        </ul>
                     </div>
                </div>
                <div class="fav_link" onclick="addToWishList(this)" data-id="{{$data['propertyData']->id}}">
                  <span class="heart_empty_product_{{$data['propertyData']->id}} {{$data['propertyData']->is_fav == 0 ? '' : 'd-none'}}">
                    <img src="{{ URL::asset('assets/web/img/shortlet/heart.svg')}}" alt="">
                  </span>
                  <span class="heart_filled_product_{{$data['propertyData']->id}}  {{$data['propertyData']->is_fav == 1 ? '' : 'd-none'}}">
                    <img src="{{ URL::asset('assets/web/img/shortlet/heart-2.svg')}}" alt="">
                  </span>
                </div>
              </div>
            </div>
            <p><h6>Accommodation Reference: {{ $data['propertyData']->code }}</h6></p>
            <div class="meta_dlt">
              <ul class="meta_list">
                @if(isset($data['propertyData']->getUser->is_super_host) && $data['propertyData']->getUser->is_super_host == 'Yes')
                <li class="meta_single">
                  <span class="pro-ic">
                    <img src="{{ URL::asset('assets/web/img/shortlet/superhost.svg')}}" alt="">
                  </span>
                  <span>Superhost</span>
                </li>
                @endif
                <!-- <li class="meta_single">
                  <div class="location-cls">
                    <div class="location-icon">
                      <img src="{{ URL::asset('assets/web/img/shortlet/location_icon.svg')}}" alt="">
                    </div>
                    <div class="location-cont">
                      @if(isset($data['propertyData']->getPropertyAddress[0]))
                        <p>{{$data['propertyData']->getPropertyAddress[0]->address}}</p>
                      @endif
                    </div>
                  </div>
                </li> -->
                <li class="meta_single">
                  <div class="reting-cls">
                    <div class="reting-icon">
                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    </div>
                    <div class="location-cont">
                      <p>{{ number_format($data['propertyData']->avg_rating,1) }}</p>
                    </div>
                  </div>
                </li>
                <li class="meta_single" data-bs-toggle="modal" data-bs-target="#ratingModal">
                  <span>{{count($data['ratings'])}}  Reviews</span>
                </li>
              </ul>
            </div>
            <div class="gallery">
              <div class="row">
                <div class="col-md-6">
                  <div  class="gallery-img-cls">
                    <a class="example-image-link" href="{{$data['propertyData']->image}}" onclick="addClassBody()" data-lightbox="example-set" data-title=""><img class="example-image" src="{{$data['propertyData']->image}}" alt=""/></a>
                    <!-- <img src="{{$data['propertyData']->image}}"> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="posiotion_relative">
                    <div class="row">
                      @if(count($data['propertyImages']) > 0)
                        @foreach($data['propertyImages'] as $key => $image)
                          @if($key <= 3)
                          <div class="col-md-6">
                            <div  class="gallery-img">
                              <a class="example-image-link" href="{{ $image->image }}" onclick="addClassBody()" data-lightbox="example-set" data-title=""><img class="example-image" src="{{ $image->image }}" alt=""/></a>
                              <!-- <img src="{{ $image->image }}"> -->
                            </div>
                          </div>
                          @else
                            <div class="col-md-6 ">
                              <div  class="gallery-img d-none">
                                <a class="example-image-link" href="{{ $image->image }}" data-lightbox="example-set" data-title=""><img class="example-image" src="{{ $image->image }}" alt=""/></a>
                                <!-- <img src="{{ $image->image }}"> -->
                              </div>
                            </div>
                          @endif
                          <div class="see_more_img">
                            <a class="example-image-link see_more btn primary_btn" href="{{ $image->image }}" onclick="addClassBody()" data-lightbox="  example-set" data-title="">See More Images</a>
                          </div>    
                        @endforeach
                        
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>


            
            <div class="product_dtl_cont">
              <div class="product_dtl_left">
                <div class="product_tag">
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/shortlet/account-user.svg')}}" alt=""></span> {{$data['propertyData']->max_guest}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/shortlet/bed.svg')}}" alt="Kingsize bed" title="Kingsize bed"></span> {{ isset($data['propertyData']->getPropertyBedroom[0]->no_of_kingsize_bed) ? $data['propertyData']->getPropertyBedroom[0]->no_of_kingsize_bed : ''}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/shortlet/bed.svg')}}" alt="Queensize bed" title="Queensize bed"></span> {{ isset($data['propertyData']->getPropertyBedroom[0]->no_of_qweensize_bed) ? $data['propertyData']->getPropertyBedroom[0]->no_of_qweensize_bed : ''}}</span>
                  </div>
                  <div class="tag_list">
                    <span href="#" class="btn secondary_btn">
                      <span class="tag_icon"><img src="{{ URL::asset('assets/web/img/shortlet/home.svg')}}" alt="Total Bedrooms" title="Total Bedrooms"></span> {{ isset($data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms) ? $data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                  </div>
                </div>
                <div class="product_user_title">
                  <div class="user_wrap">
                    <span class="user_img">
                      <img src="{{ URL::asset('assets/web/img/shortlet/user-profile.svg')}}" alt="">
                    </span>
                    <h2>{{$data['propertyData']->host_name}} <span>(Host)</span></h2>
                  </div>
                  <div class="chat-option">
                    <a href="{{ url('guest-chat?property_id='.$data['propertyData']->id) }}" class="btn secondary_btn">
                      <span class="chat_icon"><img src="{{ URL::asset('assets/web/img/shortlet/message.svg')}}" alt=""></span>
                      <span>Chat With a Booking Specialist</span>
                    </a>
                  </div>
                </div>

                <div class="product_desc">
                    <h4 class="heading-inner-title">Description</h4>
                    <p>{!! $data['propertyData']->description !!}</p>
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
                  </ul>
                </div>
                @endif
                <div class="special_feature_sec_main">

                  @if(count($data['propertyData']->getPropertyBedroom) > 0 && $data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms > 0)
                  <div class="special_feature_sec">
                    <h4 class="heading-inner-title">Bedroom(s)</h4>
                    <ul>

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_bedrooms ?? 0}} Bedrooms</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_kingsize_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_kingsize_bed ?? 0}} King Size Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_qweensize_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_qweensize_bed ?? 0}} Queen Size Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_single_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_single_bed ?? 0}} Single Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_double_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_double_bed ?? 0}} Double Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_single_sofa_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_single_sofa_bed ?? 0}} Single Sofa Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_double_sofa_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_double_sofa_bed ?? 0}} Double Sofa Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_bunk_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_bunk_bed ?? 0}} Bunk Beds</span>
                        </li>
                      @endif

                      @if($data['propertyData']->getPropertyBedroom[0]->no_of_extra_bed > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyBedroom[0]->no_of_extra_bed ?? 0}} Extra Beds</span>
                        </li>
                      @endif
                    </ul>
                  </div>
                  @endif

                  @if(count($data['propertyData']->getPropertyKitchen) > 0 && $data['propertyData']->getPropertyKitchen[0]->no_of_kitchens > 0)
                  <div class="special_feature_sec">
                    <h4 class="heading-inner-title">Kitchen</h4>
                    <ul>

                      @if($data['propertyData']->getPropertyKitchen[0]->no_of_kitchens > 0)
                        <li>
                          <span>{{$data['propertyData']->getPropertyKitchen[0]->no_of_kitchens ?? 0}} Kitchen</span>
                        </li>
                      @endif

                      <?php $kitchen_amenities = explode(',', $data['propertyData']->getPropertyKitchen[0]->kitchen_amenities); ?>

                      @if(count($kitchen_amenities) > 0)

                        @foreach($kitchen_amenities as $k_amenities)
                          <li><span>{{ ucwords(str_replace('_', ' ', $k_amenities)) }}</span></li>
                        @endforeach

                      @endif
                    </ul>
                  </div>
                  @endif

                  @if(count($data['propertyData']->getProPropertyBathroom) > 0)

                  <?php
                    $bathroom_with_bathtub = $data['propertyData']->getProPropertyBathroom[0]->bathroom_with_bathtub;
                    $bathroom_with_shower = $data['propertyData']->getProPropertyBathroom[0]->bathroom_with_shower;
                    $toilets = $data['propertyData']->getProPropertyBathroom[0]->toilets;

                    if ($toilets > 0 || $bathroom_with_bathtub > 0 || $bathroom_with_shower > 0) { ?>
                        <div class="special_feature_sec">
                          <h4 class="heading-inner-title">Bathroom(s)</h4>
                          <ul>
                            @if ($data['propertyData']->getProPropertyBathroom[0]->bathroom_with_bathtub > 0)
                              <li><span>{{$data['propertyData']->getProPropertyBathroom[0]->bathroom_with_bathtub}} Bathroom With Bathtub</span></li>
                            @endif

                            @if ($data['propertyData']->getProPropertyBathroom[0]->bathroom_with_shower > 0)
                              <li><span>{{$data['propertyData']->getProPropertyBathroom[0]->bathroom_with_shower}} Bathrooms With Shower</span></li>
                            @endif

                            @if ($data['propertyData']->getProPropertyBathroom[0]->toilets > 0)
                            <li><span>{{$data['propertyData']->getProPropertyBathroom[0]->toilets}} Toilets</span></li>
                            @endif
                          </ul>
                        </div>
                    <?php } ?>
                  @endif

                  @if(count($data['propertyData']->getPropertyBedding) > 0)
                  <div class="special_feature_sec">
                    <h4 class="heading-inner-title">General</h4>
                    <ul>

                      @if($data['propertyData']->getPropertyBedding[0]->no_of_television > 0)
                        <li><span>{{$data['propertyData']->getPropertyBedding[0]->no_of_television ?? 0}} TVs</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->satellite_tv)
                        <li><span>TV Satellite</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->iron)
                        <li><span>Iron</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->internet_access)
                        <li><span>Internet</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->safe)
                        <li><span>Safe</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->mini_bar)
                        <li><span>Mini Bar</span></li>
                      @endif
                      
                      @if($data['propertyData']->getPropertyBedding[0]->washing_machine)
                        <li><span>Washing Machine</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->dryer)
                        <li><span>Dryer</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->radio)
                        <li><span>Radio</span></li>
                      @endif

                      @if($data['propertyData']->getPropertyBedding[0]->dvd_player)
                        <li><span>Dvd Player</span></li>
                      @endif
                    </ul>
                  </div>
                  @endif
                </div>
                <div class="see_more_features">
                  <a class="see_more_features_a" href="javascript:void(0);">
                    <span class="show_mr_a">Show More Features</span><span class="show_ls_a">Show Less Features</span>
                  </a>
                </div>

                @if(count($data['propertyData']->getPropertyHouserule) > 0)
                  <div class="special_feature_sec celender_cls">
                    <h4 class="heading-inner-title">House Rule(s)</h4>
                      <ul>
                        @foreach($data['propertyData']->getPropertyHouserule as $record)

                          @if ($record)
                            <li><span>{{ ucwords($record->name) }}</span></li>
                          @endif
                        @endforeach
                      </ul>
                  </div>
                @endif

                
                <div class="other_service_sec">
                  <h4 class="heading-inner-title">Optional services</h4>
                  <div class="list-serv">
                    <div class="single_serv">
                      <h6>Extra Cleaning</h6>
                      <h5>{{ $data['propertyData']->clean_status == 1 ? 'Available' : 'Unavailable' }}</h5>
                    </div>

                    @foreach($data['propertyData']->getExtraService as $extraService)

                      @if ($extraService->getServiceData && ucwords($extraService->getServiceData->name) != 'Caution Fee')
                        <div class="single_serv">
                          <h6>{{ ucwords($extraService->getServiceData->name) }}</h6>
                          <h5>Available</h5>
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>

           

                <div class="product_tmt">
                    <h4 class="heading-inner-title">Your schedule</h4>
                    <div class="product_tmt_itms">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="product_tmt_itm">
                            <span>Check-in</span>
                            <p>From {{ isset($data['propertyData']->check_in_from_time) && !empty($data['propertyData']->check_in_from_time) ? $data['propertyData']->check_in_from_time : '15:00' }} to {{ isset($data['propertyData']->check_in_to_time) && !empty($data['propertyData']->check_in_to_time) ? $data['propertyData']->check_in_to_time : '18:00' }} Every day</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="product_tmt_itm">
                            <span>Check-out</span>
                            <p>Before {{ isset($data['propertyData']->check_out_time) && !empty($data['propertyData']->check_out_time) ? $data['propertyData']->check_out_time : '12:00' }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="celender_cls">
                  <div class="celecder-title">
                    <div class="celender-title-left">

                        <!-- @if(isset($data['propertyData']->getPropertyAddress[0]))
                          <h4 class="heading-inner-title"><span class="total_nights">1</span> nights in {{$data['propertyData']->getPropertyAddress[0]->address}}</h4>
                        @endif -->
                        <?php $main_address = ''; ?>
                        @if(isset($data['propertyData']->getPropertyAddress[0]))

                          @if($data['propertyData']->getPropertyAddress[0]->getPropertyArea)
                          <?php $main_address .= isset($data['propertyData']->getPropertyAddress[0]->getPropertyArea->name) && !empty($data['propertyData']->getPropertyAddress[0]->getPropertyArea->name) ? $data['propertyData']->getPropertyAddress[0]->getPropertyArea->name.', ' : ''; ?>
                          @endif

                          @if($data['propertyData']->getPropertyAddress[0]->getPropertyCity)
                            <?php $main_address .= isset($data['propertyData']->getPropertyAddress[0]->getPropertyCity->name) && !empty($data['propertyData']->getPropertyAddress[0]->getPropertyCity->name) ? $data['propertyData']->getPropertyAddress[0]->getPropertyCity->name.', ' : ''; ?>
                          @endif

                          @if($data['propertyData']->getPropertyAddress[0]->getPropertyProvince)
                          <?php $main_address .= isset($data['propertyData']->getPropertyAddress[0]->getPropertyProvince->name) && !empty($data['propertyData']->getPropertyAddress[0]->getPropertyProvince->name) ? $data['propertyData']->getPropertyAddress[0]->getPropertyProvince->name.', ' : ''; ?>
                          @endif

                          @if($data['propertyData']->getPropertyAddress[0]->getPropertyCountry)
                          <?php $main_address .= isset($data['propertyData']->getPropertyAddress[0]->getPropertyCountry->name) && !empty($data['propertyData']->getPropertyAddress[0]->getPropertyCountry->name) ? $data['propertyData']->getPropertyAddress[0]->getPropertyCountry->name.' ' : ''; ?>
                          @endif
                        @endif
                        @if(isset($main_address))
                          <h4 class="heading-inner-title"><span class="total_nights">1</span> nights in {{ $main_address }}</h4>
                        @endif
                        <p><span class="start_date_detailPage">20 Nov 2022</span> - <span class="end_date_detailPage">25 Nov 2022</span></p>
                    </div>
                    <div class="celender-title-right">
                        <!-- <a href="#" class="btn primary_btn">Clear Dates</a> -->
                    </div>
                  </div>
                  <div class="bookingCalenderMain">
                    <!--div class="celecder-col">
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
                    </div-->

                    <div class="bookingCalender"></div>
                    <div class="row">
                      <div class="col-md-1"><i class="fa fa-angle-left fa-icon-box booking_left_icon" data-type='-1' aria-hidden="true"></i></div>
                      <div class="col-md-10"></div>
                      <div class="col-md-1" style="text-align: right;"><i class="fa fa-angle-right fa-icon-box booking_right_icon"  data-type='1' aria-hidden="true"></i></div>
                    </div>
                  </div>
                </div>

                <?php /* ?>
                @if(count($data['propertyData']->getExtraService) > 0)

                  @foreach($data['propertyData']->getExtraService as $extraService)

                    @if (ucwords($extraService->getServiceData->name) == 'Caution Fee')
                      <div class="security-deposit-sec">
                          <h4 class="heading-inner-title">Caution Fee</h4>
                          <div class="security-listing">
                            <!-- <h5>Amount: <span>NGN{{ ucwords($extraService->getServiceData->price) }} /booking</span></h5> -->
                            <h5>Amount: <span>NGN{{ $data['propertyData']->security_deposit_amount }} /booking</span></h5>
                            <h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
                            <p>To be paid when booking or Checkout.</p>
                          </div>
                      </div>
                    @endif
                  @endforeach
                @endif

                */ ?>
                @if($data['propertyData']->security_deposit_amount)
                  <div class="security-deposit-sec">
                      <h4 class="heading-inner-title">Caution Fee</h4>
                      <div class="security-listing">
                        <h5>Amount: <span>NGN{{ $data['propertyData']->security_deposit_amount }} /booking</span></h5>
                        <h5>Payment method: <span>Both options are available (Card or Bank Transfer)</span></h5>
                        <p>Refund of caution fee to be paid 24 hrs after checkout.</p>
                      </div>
                  </div>
                @endif

                @if($data['propertyData']->total_rating > 0)
                <div class="review-sec">
                  <ul class="meta_list">
                    <li class="meta_single">
                      <div class="reting-cls">
                        <div class="reting-icon">
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                        </div>
                        <div class="location-cont">
                          <p>{{ number_format($data['propertyData']->avg_rating,1) }}</p>
                        </div>
                      </div>
                    </li>
                    <li class="meta_single" data-bs-toggle="modal" data-bs-target="#ratingModal"> 
                      <span>{{count($data['ratings'])}} Reviews</span>
                    </li>
                  </ul>
                  <!-- <div class="review-view">
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
                  </div> -->
                  <div class="reviewer_sec">

                 
                      @if (count($data['ratings']))

                        @foreach($data['ratings'] as $rating)

                        
                    
                            <div class="review-single">
                              <div class="reviewer-inner">
                                <div class="reviewer_img">
                                  <img src="{{$rating->getUser->image ?? '-'}}" alt="">
                                </div>
                                <div class="reviewer_cont"> 
                                  <h5>{{$rating->getUser->name ?? '-'}}</h5>
                                  <p>{{ date('F Y', strtotime($rating->created_at)) }}</p>
                                </div>
                              </div>
                              @if($rating->rate <= 1)
                              <p><h6>.</h6><p>
                              <p>
                              <div class="row">
                                <div class="col-md-8">
                                <h6>Experience is not good</h6>
                                </div>
                                <div class="col-md-4">
                                  <div class="reting-icon">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                  </div>
                                </div>
                              </div>   
                              <p>

                              <div class="reting-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                            </div>
                              @elseif($rating->rate >1 && $rating->rate <=2)

                              <p>
                              <div class="row">
                                <div class="col-md-8">
                                <h6>Average experience as i am expected</h6>
                                </div>
                                <div class="col-md-4">
                                  <div class="reting-icon">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                  </div>
                                </div>
                              </div>   
                              <p>

                              @elseif($rating->rate >2 && $rating->rate <=3)
                             
                              <p>
                              <div class="row">
                                <div class="col-md-8">
                                <h6>Good experience as i am expected</h6>
                                </div>
                                <div class="col-md-4">
                                  <div class="reting-icon">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                  </div>
                                </div>
                              </div>   
                              <p>


                              @elseif($rating->rate >3 && $rating->rate <=4)
                              <p>
                              <div class="row">
                                <div class="col-md-8">
                                <h6>Very good experience</h6>
                                </div>
                                <div class="col-md-4">
                                  <div class="reting-icon">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                  </div>
                                </div>
                              </div>  
                              <p>
                              @elseif($rating->rate >4 && $rating->rate <=5)
                              
                              <p>
                              <div class="row">
                                <div class="col-md-8">
                                <h6>Excellent experience</h6>
                                </div>
                                <div class="col-md-4">
                                  <div class="reting-icon">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                  </div>
                                </div>
                              </div>   
                              <p>
                              @endif                             
                              <p>{{$rating->review}}</p>
                            </div>
                          
                        @endforeach
                      @endif
                  </div>
                  <!-- <div class="see-all-review d-flex">
                    <a href="#" class="btn primary_btn">See All Reviews</a>
                  </div> -->
                </div>
                @endif

                @if($data['propertyData']->video_url)
                  <?php $video_url = $data['propertyData']->video_url;
                    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $video_url, $matches);
                    $video_id = $matches[1] ?? 0;
                  ?>
                  <div class="video_sec">
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" width="560" height="315" src="https://www.youtube.com/embed/{{$video_id}}?rel=0" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                  </div>
                @endif

               
                  <div class="distance-sec">
                    <h4 class="heading-inner-title">Map and Distances</h4>
                    <div class="map-img contact-map">
                    <div id="map"></div>

                    </div>
                  </div>
                        
                <div class="things-sec">
                  <h4 class="heading-inner-title">Things to know</h4>
                  <div class="row">
                    @if($data['propertyData']->booking_condition)
                      <div class="col-md-4">
                        <div class="things-box">
                          <h5>Booking Condition</h5>
                          <p>{{$data['propertyData']->booking_condition}}</p>
                          <!-- <a href="#" class="view-more">View More</a> -->
                        </div>
                      </div>
                    @endif

                    @if($data['propertyData']->additional_notes)
                      <!-- <div class="col-md-4">
                        <div class="things-box">
                          <h5>Additional Notes</h5>
                          <p>{{$data['propertyData']->additional_notes}}</p>
                          <a href="#" class="view-more">View More</a>
                        </div>
                      </div> -->
                    @endif

                    @if($data['propertyData']->cancellation_policy)
                      <div class="col-md-4">
                        <div class="things-box">
                          <h5>Cancellation Policy</h5>
                          <p>{{$data['propertyData']->cancellation_policy}}</p>
                          <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleCancellationPolicy" class="view-more">View More</a>
                        </div>
                      </div>
                    @endif
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
                        <input type="hidden" name="caution_fee" class="caution_fee" value="{{$data['propertyData']->security_deposit_amount}}">
                      <div class="checkin_wrap" onclick="resetChangeDate()">
                        <label>CHECK-IN</label>
                        <p id="datepicker2_detailPage" >Nov 01, 2022</p>
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

                    <?php if ($data['propertyData']->book_type == 'Reserve') { ?>
                      <!-- <a href="javascript:void(0);" onclick="submitReserveData()" class="btn primary_btn">Reserve</a> -->
                      <a href="javascript:void(0);" onclick="submitCartData()" class="btn primary_btn">Reserve</a>
                    <?php } else { ?>
                      <a href="javascript:void(0);" onclick="submitCartData()" class="btn primary_btn">Book Now</a>
                    <?php } ?>
                  </div>
                  <?php if ($data['propertyData']->book_type == 'Reserve') { ?>
                  <div class="charged-cls">
                    <p>This accommodation listing has been set to Reserve by host and you will receive an email to make payment if host accepts the booking.</p>
                  </div>
                  <?php } ?>
                  <div class="hotel-price-wrap">
                    <ul>
                      <li>
                        <span class="left_price_p" id="left_price_ngn">NGN {{$data['propertyData']->price}} 
                          <span>
                            <img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span> 
                            <span class="total_nights">1</span> nights </span>
                        
                        <span class="right_price_p days_price" id="right_price_ngn">NGN {{$data['propertyData']->price}}</span>
                      </li>
                      <li>
                        <span class="left_price_p">Caution Fee</span>
                        <span class="right_price_p">NGN {{$data['propertyData']->security_deposit_amount ?? 0}}</span>
                      </li>
                    </ul>
                    <ul class="total_price">
                      <li>
                        <span class="left_price_p">Total Price</span>
                        <span class="right_price_p grand_total_price">NGN {{($data['propertyData']->price + $data['propertyData']->security_deposit_amount)}}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
      <!-- <section class="properties_sec space-cls">
        <div class="container">
          <div class="suggest_property">
            <div class="inner_title">
              <div class="title-left">
                <h3>Suggestion Properties</h3>
              </div>
            </div>
            <div class="product_list">
              <div class="product_main_slider owl-carousel">
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="item">
                  <div class="pro_box">
                    <div class="pro_img_main">
                      <a href="">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                          <div class="item">
                            <div class="pro_img">
                              <img src="http://localhost/shortletrental/uploads/property/NOV2022/1667293364-property.jpeg" alt="">
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="badge-cls">
                      <span>
                        <img src="http://localhost/shortletrental/assets/web/img/featured-badge.png" alt="">
                      </span>
                    </div>
                    <div class="heart_right" onclick="addToWishList(this)" data-id="1">
                      <span class="heart_empty_product_1 d-none">
                        <img src="http://localhost/shortletrental/assets/web/img/heart.png" alt="">
                      </span>
                      <span class="heart_filled_product_1 ">
                        <img src="http://localhost/shortletrental/assets/web/img/heart-2.png" alt="">
                      </span>
                    </div>
                    <a href="http://localhost/shortletrental/property-detail/1">
                      <div class="pro-cont">
                        <h3>1  bedroom Apartment Freedom way,lekki _ Olufemi A</h3>
                        <div class="reting-location">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/location_icon.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>Jhotwara, Jaipur, Rajasthan, India</p>
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="http://localhost/shortletrental/assets/web/img/star.png" alt="">
                            </div>
                            <div class="location-cont">
                              <p>0.0</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price">
                          <h2>NGN 5000 <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/account-user.png" alt="">
                              </span>
                              <span>5</span>
                            </div>
                            <div class="pro-dtl-list">
                              <span class="icon-cls">
                                <img src="http://localhost/shortletrental/assets/web/img/bed.png" alt="">
                              </span>
                              <span>3</span>
                            </div>
                          </div>
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg">ratingModal
                              <span class="pro-ic">
                                <img src="http://localhost/shortletrental/assets/web/img/superhost.png" alt="">
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> -->
    </main>
    

@endsection
@section('script')
<div class="modal_main_cls">
<div class="modal fade select-sec" id="ratingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <h2>Ratings</h2>
                  </div>
                 
                  <div class="form-body">
                    <div class="row">
                      <div class="col-lg-12">
                      @if (count($data['ratings']))
                          @foreach($data['ratings'] as $rating)



                              <div class="review-single">
                                <div class="reviewer-inner">
                                  <div class="reviewer_img">
                                    <img src="{{$rating->getUser->image ?? '-'}}" alt="">
                                  </div>
                                  <div class="reviewer_cont"> 
                                    <h5>{{$rating->getUser->name ?? '-'}}</h5>
                                    <p>{{ date('F Y', strtotime($rating->created_at)) }}</p>
                                  </div>
                                </div>
                                @if($rating->rate <= 1)
                                <p><h6>.</h6><p>
                                <p>
                                <div class="row">
                                  <div class="col-md-8">
                                  <h6>Experience is not good</h6>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="reting-icon">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    </div>
                                  </div>
                                </div>   
                                <p>

                                <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                              </div>
                                @elseif($rating->rate >1 && $rating->rate <=2)

                                <p>
                                <div class="row">
                                  <div class="col-md-8">
                                  <h6>Average experience as i am expected</h6>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="reting-icon">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    </div>
                                  </div>
                                </div>   
                                <p>

                                @elseif($rating->rate >2 && $rating->rate <=3)
                              
                                <p>
                                <div class="row">
                                  <div class="col-md-8">
                                  <h6>Good experience as i am expected</h6>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="reting-icon">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    </div>
                                  </div>
                                </div>   
                                <p>


                                @elseif($rating->rate >3 && $rating->rate <=4)
                                <p>
                                <div class="row">
                                  <div class="col-md-8">
                                  <h6>Very good experience</h6>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="reting-icon">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    </div>
                                  </div>
                                </div>  
                                <p>
                                @elseif($rating->rate >4 && $rating->rate <=5)
                                
                                <p>
                                <div class="row">
                                  <div class="col-md-8">
                                  <h6>Excellent experience</h6>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="reting-icon">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                      <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                                    </div>
                                  </div>
                                </div>   
                                <p>
                                @endif                             
                                <p>{{$rating->review}}</p>
                              </div>
                            
                          @endforeach
                          @endif
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
                    
<script type="text/javascript">
  $('.dropdown-menu-detail-page').on('click', function (event) {
      $(this).parent().toggleClass('open');
  });
  
  function addToWishList($this) {
    var product_id = $($this).attr('data-id');
    var user_id = "{{$userId ?? ''}}";
    var is_guest = "{{$is_guest ?? ''}}";

    if (user_id != '' && is_guest != 1) {
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
    var minimum_night = "{{$data['propertyData']->minimum_no_of_nights ?? ''}}";

    if (user_id != '') {
      var total_days_detailPage = $('.total_days_detailPage').val();
      if (parseInt(total_days_detailPage) >= parseInt(minimum_night)) {
        var start_date_detailPage = $('.start_date_detailPage').val();
        var end_date_detailPage = $('.end_date_detailPage').val();
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

        if(adultCount > 0 || childCount > 0 || infantCount > 0 || petCount > 0){
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
                <?php if ($data['propertyData']->book_type == 'Reserve') { ?>
                  window.location.href = '{{ route("web.checkout_reserve") }}';
                <?php }else{ ?>
                  window.location.href = '{{ route("web.checkout") }}';
                <?php } ?>
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
        }else{
          toastr.error('Please select atlease one guest.');
        }
      } else {
        toastr.error('Minimun night should be '+minimum_night);
      }

    } else {
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }

  function submitReserveData() {
    var user_id = "{{$userId ?? ''}}";
    var minimum_night = "{{$data['propertyData']->minimum_no_of_nights ?? ''}}";

    if (user_id != '') {
      var total_days_detailPage = $('.total_days_detailPage').val();

      if (total_days_detailPage >= minimum_night) {
        var start_date_detailPage = $('.start_date_detailPage').val();
        var end_date_detailPage = $('.end_date_detailPage').val();
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
          url: '{{ route("web.addToReserve") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {

            if (res.status === true) {
              toastr.success(res.message);

              setTimeout(function() {
                window.location.reload();
              },1500);
              // window.location.href = '{{ route("web.checkout") }}';
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
        toastr.error('Minimun night should be '+minimum_night);
      }
    } else {
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }
</script>
<script type="text/javascript">
  //$(function() {
    var blockDates = "{{$blockDates}}";
    blockDates = JSON.parse(blockDates.replaceAll('&quot;','"'));
    // $("#calendar-new").trigger("click");
    /*var start = moment();
    var end = moment();*/
    var start_detailPage = moment();
      var end_detailPage = moment().add(1, 'days');
    function cb_detailPage(start_detailPage, end_detailPage) {
        var security_deposite = "{{$data['propertyData']->security_deposit_amount ?? 0}}";
        

        $('#datepicker2_detailPage').text(start_detailPage.format('MMM D, YYYY'));
        $('#datepicker12_detailPage').text(end_detailPage.format('MMM D, YYYY'));

        $('.start_date_detailPage').val(start_detailPage.format('YYYY-MM-DD'));
        $('.end_date_detailPage').val(end_detailPage.format('YYYY-MM-DD'));

        $('.start_date_detailPage').text(start_detailPage.format('DD-MMM-YYYY'));
        $('.end_date_detailPage').text(end_detailPage.format('DD-MMM-YYYY'));

        if(end_detailPage ===start_detailPage )
        {
          end_detailPage = start_detailPage.add(1, "day");
         }
        // alert(end_detailPage); 

             var totalDays = parseInt(end_detailPage.diff(start_detailPage, 'days'));
       
         if (totalDays === 0) {
            end_detailPage.add(1, "day");
          $('#datepicker12_detailPage').text(end_detailPage.format('MMM D, YYYY'));
           $('.end_date_detailPage').val(end_detailPage.format('YYYY-MM-DD'));
          $('.end_date_detailPage').text(end_detailPage.format('DD-MMM-YYYY'));

          totalDays = 1;
        }
        // else{
        //   totalDays = totalDays+1;
        // }
        $('.total_days_detailPage').val(totalDays);
        $('.total_nights').text(totalDays);

          rateListPrice(start_detailPage.format('Y-MM-DD'),end_detailPage.format('Y-MM-DD')); 
        
   
     
  
       

        var days_price = parseInt($('.per_night_price').val())*parseInt(totalDays);
        var grand_total_price = (parseInt($('.per_night_price').val())*parseInt(totalDays))+parseInt(security_deposite);
       // console.log("days_price::",days_price);
        $('.days_price').text('NGN '+days_price);
        $('.grand_total_price').text('NGN '+grand_total_price);
        $('.total_booking_amount').val(grand_total_price);
    }

    /*function demoFunction() {
      console.log('herer');
      $('body').addClass('herewego');
    }*/




    $('#daterangepicker_detailPage').daterangepicker({
        minDate: new Date(),
        startDate: start_detailPage,
        endDate: end_detailPage,
        autoApply: true,
        isInvalidDate: function(date) {


            /*if (date.format('YYYY-MM-DD') == blockDates[date.format('YYYY-MM-DD')]) {
                return true; 
            } else {
                return false; 
            }*/
        }

    }, cb_detailPage);


    cb_detailPage(start_detailPage, end_detailPage);

    function rateListPrice(startDate,endDate){
     
   
     
     var dstartDate = new Date(startDate);
var dendDate = new Date(endDate);

// Calculate the difference in milliseconds
var timeDifference = dendDate - dstartDate;

// Convert milliseconds to days
var daysDifference = timeDifference / (1000 * 60 * 60 * 24);
  
  if(daysDifference==0)
  {
    daysDifference=1;
  }
  // else{
  //   daysDifference +=1;
  // }
 $('.total_days_detailPage').val(daysDifference);

      var property_id = $(".property_id").val();

      $.ajax({
          url: '{{ route("web.rateListPricePropertyDetails") }}',
          dataType: 'json',
          data: {
            property_id:property_id,
            startDate:startDate,endDate:endDate,
        "_token": "{{ csrf_token() }}"},
          type: 'POST',
          success: function(res) {
          if(res)
          {
            var tDay = $('.total_days_detailPage').val();
           
            var per_n_price = Math.round(res/tDay);
            $(".per_night_price").val(per_n_price);
           $(".product_dtl_price").html("<h3>NGN "+per_n_price+" <span>night</span></h3>");
             var html = "NGN "+per_n_price;
              html += "<span>";
            html+=' <img src="{{ URL::asset('assets/web/img/cross_icon.png')}}"></span>';
            html+='  <span class="total_nights">'+ tDay+'</span> nights';
             

         

           $("#left_price_ngn").html(html);  
           //$(".days_price").text("NGN:"+);
           var right_total = parseInt(per_n_price) * tDay;
           grand_total_price = parseInt(right_total)+parseInt($(".caution_fee").val());
         //  console.log("grand_total_price::",grand_total_price);
           $(".total_booking_amount").val(grand_total_price);
            $("#right_price_ngn").html("NGN "+right_total);
            $('.grand_total_price').text('NGN '+grand_total_price);

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

    } 

    /*$("#datepicker4").daterangepicker({
      startDate: start_detailPage,
      endDate: end_detailPage,
      alwaysShowCalendars: true,
      autoApply: true,
    }, demoFunction);*/
    


  /*function resetChangeDate(){

      $('#daterangepicker_detailPage').daterangepicker({
          minDate: new Date(),
          startDate: start_detailPage,
          endDate: end_detailPage,
          autoApply: true,
          isInvalidDate: function(date) {

              if (date.format('YYYY-MM-DD') == blockDates[date.format('YYYY-MM-DD')]) {
                  return true; 
              } else {
                  return false; 
              }
          }

      }, cb_detailPage);
  }*/

  function resetChangeDate() {
      $('#daterangepicker_detailPage').daterangepicker({
          minDate: new Date(),
          startDate: start_detailPage,
          endDate: end_detailPage,
          autoApply: true,
          isInvalidDate: function(date) {
              /*if (date.format('YYYY-MM-DD') == blockDates[date.format('YYYY-MM-DD')]) {
                  return true; 
              } else {
                  return false; 
              }*/
          }
      }, function(start, end) {
          // Callback function to handle date range selection
          // Add one day to the selected end date
         // var newEndDate = end.clone().add(1, 'day');
          
          // Set the new end date
          //$('#daterangepicker_detailPage').data('daterangepicker').setEndDate(newEndDate);
          //alert();
          // Your existing callback function logic
          //cb_detailPage(start, newEndDate);
          cb_detailPage(start, end);
      });
  }

  function addClassBody() {
    $('body').addClass('light-box-slider');
  }
  $('.lb-close').click(function(){
       $('body').removeClass('light-box-slider');
  });
/*  $("document").ready(function() {
    setTimeout(function() {
        $("#datepicker4").trigger('click');
    },1000);
});*/
</script>

<script src="https://netteria.net/myscript/jquery/html5videopopup/js/videopopup.js"></script>
<script>
  $(function () {
  $('#vidBox').VideoPopUp({ 
    backgroundColor: "#17212a",
    opener: "video1",
    maxweight: "640",
    idvideo: "v1"
  });

    $('#closer_videopopup').click(function(){
      $("#videCont iframe").attr("src", $("#videCont iframe").attr("src"));
    });
 });



 var page = 1;

 $(document).on('click','.fa-icon-box',function(){
    var type = $(this).data('type');
    if(page >= 1)
    {
      if(type == '-1')
      {
        --page;
        booking_calender(page);
      }
      else
      {
        ++page;
        booking_calender(page);
      }
    }
 });
 booking_calender(page);

 function booking_calender(page)
 {
  var url = "{{url('property-detail-booking_calender')}}?id="+"{{$id}}&page="+page;
   $(".bookingCalender").load(url);
 }

 function initMap(){
  var latitude = parseFloat("{{ $data['propertyData']->latitude }}");
  var longitude = parseFloat("{{ $data['propertyData']->longitude }}");



  const myLatLng = { lat: latitude, lng: longitude };

  const map = new google.maps.Map(
    document.getElementById("map"),
    {
      zoom: 19,
      center: myLatLng,
    }
  );

  new google.maps.Marker({
    position: myLatLng,
    map,
    title: "Hello World!",
  });
}

function removeVideoPopupClass() {
  $('.vid_popup_display_cls').removeClass('d-none');
}

$(document).mouseup(function (e) {
    if ($(e.target).closest(".dropdown-menu_sec").length
                === 0) {
      $('body').removeClass('dropdown-menu_tgl');
    }
});

</script>
<style>
  #map {
  height: 350px;
}
</style>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY&callback=initMap&v=weekly"
      defer
    ></script>
@endsection