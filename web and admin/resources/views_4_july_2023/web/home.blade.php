@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');
    // dd(gettype($auth_user));
    if (isset($auth_user) && $is_guest != 1) {
      $userId = $auth_user->data->id;
    }
    // dd($data);
    // dd($data['featuredProperty']);
?>
@section('content')
  <main>
    <section class="banner-sec" style="background-image:url('{{ URL::asset('assets/web/img/slider-banner.png')}}')">
      <div class="container">
        <div class="owl-carousel">

          <?php if ($data['category']) { ?>
            <?php foreach ($data['category'] as $key => $value) { ?>
              <div class="item">
                <div class="category-bg">
                  <a href='{{url("properties")."?category_id=".$value->id}}'>
                    <div class="category-icon skeleton">
                      <img class="" src="{{$value->image}}" alt="">
                    </div>
                    <div class="cate-name skeleton">
                      <h5>{{$value->name}}</h5>
                    </div>
                  </a>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </section>
    <div class="home_page_sss">
 

    
    <?php if (isset($data['featuredProperty'][0]) && count($data['featuredProperty']) > 0) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Featured Listings</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=featured' }}">View All</a>
            </div>
          </div>
          <div class="">
            <div class="row">
                <?php foreach ($data['featuredProperty'] as $key => $value) {  //dd(); ?>
                  <div class="col_5">
                    <div class="pro_box skeleton">
                      <div class="pro_img_main skeleton">
                        <a href="{{ url('property-detail').'/'.$value->id }}">
                          <div class="inner_img_sld owl-carousel">
                            <div class="item">
                              <div class="pro_img">
                                <img src="{{$value->image}}" alt="">
                              </div>
                            </div>
                            <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>
                              <?php if ($k >= 8) { break; } ?>
                                <div class="item">
                                  <div class="pro_img">
                                    <img src="{{$v->image}}" alt="">
                                  </div>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </a>
                      </div>
                      @if($value->featured == 'Yes')
                        <div class="badge-cls">
                          <span>
                            <img src="{{ URL::asset('assets/web/img/shortlet/featured-badge.svg')}}" alt="">
                          </span>
                        </div>
                      @endif
                      <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                        <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/shortlet/heart.svg')}}" alt="">
                        </span>
                        <span class="heart_filled_product_{{$value->id}}  {{$value->is_fav == 1 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/shortlet/heart-2.svg')}}" alt="">
                        </span>
                      </div>
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="pro-cont">
                            <h3 class="skeleton">{{$value->title}}</h3>
                          <div class="reting-location skeleton">
                            <div class="location-cls">
                              <div class="location-icon">
                                <img src="{{ URL::asset('assets/web/img/shortlet/location_icon.svg')}}" alt="">
                              </div>
                              <div class="location-cont">
                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                                @endif

                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                                <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                                @endif
                              </div>
                            </div>
                            <div class="reting-cls">
                              <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                              </div>
                              <div class="location-cont">
                                <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                              </div>
                            </div>
                          </div>
                          <div class="pro-price skeleton">
                            <h2>NGN {{$value->price}} <span>Night</span></h2>
                          </div>
                          <div class="pro-dtl">
                            <div class="pro-dtl-left">
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/shortlet/account-user.svg')}}" alt="">
                                </span>
                                <span>{{$value->max_guest}}</span>
                              </div>
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/shortlet/bed.svg')}}" alt="">
                                </span>
                                <span>{{ isset($value->getPropertyBedroom) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                              </div>
                            </div>
                            @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                            <div class="pro-dtl-right">
                              <div class="pro-icon-bg skeleton">
                                <span class="pro-ic">
                                  <img src="{{ URL::asset('assets/web/img/shortlet/superhost.svg')}}" alt="">
                                </span>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                <?php } ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

                              

    <?php if (isset($data['offers'][0]) && count($data['offers'])) { ?>
      <section class="special-offer space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Special Offers</h3>
            </div>
            <div class="title_right">
              <a href="{{ url('offer_list') }}">View All</a>
            </div>
          </div>
          <div class="offer_carousel owl-carousel">

            <?php foreach ($data['offers'] as $key => $value) { ?>
              <div class="item">
                <a href="{{ url('offer_list') }}">
                  <div class="offer-list skeleton">
                    <div class="offer-left">
                      <div class="offer_img">
                          <img src="{{ $value->image }}" alt="">
                      </div>
                    </div>
                    <div class="offer-right">
                      <div class="offer-cont">
                        <h3>#{{$value->getDiscountData->code ?? ''}}</h3>

                        @if (count($value->getOfferAccommodation))
                          <h5>{{$value->getOfferAccommodation[0]->getProperty->title}}</h5>
                        @endif

                        <h6>{{$value->title}}</h6>

                        @if (strlen($value->description) >= 50)
                          <p>{{ substr($value->description, 0, 35). " ... "}}</p>
                        @else
                          <p>{{ $value->description }}</p>
                        @endif
                        <!-- <div class="view-offer">
                          <a href="#" class="offer-btn">View Details</a>
                        </div> -->
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            <?php } ?>
          </div>
        </div>
      </section>
    <?php } ?>


    <?php if (isset($data['superHostProperty'][0]) && count($data['superHostProperty']) > 0) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Superhost- host who provide exceptional services</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=superhost' }}">View All</a>
            </div>
          </div>
          <div class="">
            <div class="row">

              <?php foreach ($data['superHostProperty'] as $key => $value) { ?>
                <div class="col_5">
                  <div class="pro_box skeleton">
                    <!-- <div class="pro_img">
                      <a href="{{ url('property-detail').'/'.$value->id }}"><img src="{{$value->image}}" alt=""></a>
                    </div> -->
                    <div class="pro_img_main skeleton">
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="inner_img_sld owl-carousel">
                          <div class="item">
                            <div class="pro_img">
                              <img src="{{$value->image}}" alt="">
                            </div>
                          </div>
                          <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>

                              <?php if ($k >= 8) { break; } ?>
                              <div class="item">
                                <div class="pro_img">
                                  <img src="{{$v->image}}" alt="">
                                </div>
                              </div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                      </a>
                    </div>
                 
                      <div class="badge-cls">
                        <span>
                          <img src="{{ URL::asset('assets/web/img/superhost-badge.svg')}}" alt="">
                        </span>
                      </div>
                  
                    <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                      <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                        <img src="{{ URL::asset('assets/web/img/shortlet/heart.svg')}}" alt="">
                      </span>
                      <span class="heart_filled_product_{{$value->id}} {{$value->is_fav == 1 ? '' : 'd-none'}}">
                        <img src="{{ URL::asset('assets/web/img/shortlet/heart-2.svg')}}" alt="">
                      </span>
                    </div>
                    <a href="{{ url('property-detail').'/'.$value->id }}">
                      <div class="pro-cont">
                        <h3 class="skeleton">{{$value->title}}</h3>
                        <div class="reting-location skeleton">
                          <div class="location-cls">
                            <div class="location-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/location_icon.svg')}}" alt="">
                            </div>
                            <div class="location-cont">
                              @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                              @endif

                              @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                              <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                              @endif
                            </div>
                          </div>
                          <div class="reting-cls">
                            <div class="reting-icon">
                              <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                            </div>
                            <div class="location-cont">
                              <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                            </div>
                          </div>
                        </div>
                        <div class="pro-price skeleton">
                          <h2>NGN {{$value->price}} <span>Night</span></h2>
                        </div>
                        <div class="pro-dtl">
                          <div class="pro-dtl-left">
                            <div class="pro-dtl-list skeleton">
                              <span class="icon-cls">
                                <img src="{{ URL::asset('assets/web/img/shortlet/account-user.svg')}}" alt="">
                              </span>
                              <span>{{$value->max_guest}}</span>
                            </div>
                            <div class="pro-dtl-list skeleton">
                              <span class="icon-cls">
                                <img src="{{ URL::asset('assets/web/img/shortlet/bed.svg')}}" alt="">
                              </span>
                              <span>{{ isset($value->getPropertyBedroom[0]) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                            </div>
                          </div>
                          @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                          <div class="pro-dtl-right">
                            <div class="pro-icon-bg skeleton">
                              <span class="pro-ic">
                                <img src="{{ URL::asset('assets/web/img/shortlet/superhost.svg')}}" alt="">
                              </span>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              <?php } ?>
            </div>
        </div>
      </section>
    <?php } ?>

    <?php if (isset($data['rareProperty_book_now'][0])) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Listings you can book instantly</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties') }}">View All</a>
            </div>
          </div>
          <div class="">
            <div class="row">
              <?php foreach ($data['rareProperty_book_now'] as $key => $value) { ?>
                  <div class="col_5">
                    <div class="pro_box skeleton">
                      <!-- <div class="pro_img">
                        <a href="{{ url('property-detail').'/'.$value->id }}"><img src="{{$value->image}}" alt=""></a>
                      </div> -->
                      <div class="pro_img_main skeleton">
                        <a href="{{ url('property-detail').'/'.$value->id }}">
                          <div class="inner_img_sld owl-carousel">
                            <div class="item">
                              <div class="pro_img">
                                <img src="{{$value->image}}" alt="">
                                </div>
                            </div>
                            <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>

                                <?php if ($k >= 8) { break; } ?>
                                <div class="item">
                                  <div class="pro_img">
                                    <img src="{{$v->image}}" alt="">
                                  </div>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </a>
                      </div>
                      @if($value->featured == 'Yes')
                        <div class="badge-cls">
                          <span>
                            <img src="{{ URL::asset('assets/web/img/featured-badge.png')}}" alt="">
                          </span>
                        </div>
                      @endif
                      <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                        <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                        </span>
                        <span class="heart_filled_product_{{$value->id}} {{$value->is_fav == 1 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                        </span>
                      </div>
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="pro-cont">
                          <h3 class="skeleton">{{$value->title}}</h3>
                          <div class="reting-location skeleton">
                            <div class="location-cls">
                              <div class="location-icon">
                                <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                                @endif

                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                                @endif
                              </div>
                            </div>
                            <div class="reting-cls">
                              <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                              </div>
                            </div>
                          </div>
                          <div class="pro-price skeleton">
                            <h2>NGN {{$value->price}} <span>Night</span></h2>
                          </div>
                          <div class="pro-dtl">
                            <div class="pro-dtl-left">
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt=""> 
                                  </span>
                                <span>{{$value->max_guest}}</span>
                              </div>
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                                </span>
                                <span>{{isset($value->getPropertyBedroom) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                              </div>
                            </div>
                            @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                            <div class="pro-dtl-right">
                              <div class="pro-icon-bg skeleton">
                                <span class="pro-ic">
                                  <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
                                </span>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
              <?php } ?>
            </div>
          </div>
          </div>
      </section>
    <?php } ?>


    <?php if (isset($data['luxuryProperty'][0])) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Luxury Listings</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=luxury' }}">View All</a>
            </div>
          </div>
          <div class="">
            <div class="row">
              <?php foreach ($data['luxuryProperty'] as $key => $value) { ?>
                  <div class="col_5">
                    <div class="pro_box skeleton">
                      <!-- <div class="pro_img">
                        <a href="{{ url('property-detail').'/'.$value->id }}"><img src="{{$value->image}}" alt=""></a>
                      </div> -->
                      <div class="pro_img_main skeleton">
                        <a href="{{ url('property-detail').'/'.$value->id }}">
                          <div class="inner_img_sld owl-carousel">
                            <div class="item">
                              <div class="pro_img">
                                <img src="{{$value->image}}" alt="">
                              </div>
                            </div>
                            <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>

                                <?php if ($k >= 8) { break; } ?>
                                <div class="item">
                                  <div class="pro_img">
                                    <img src="{{$v->image}}" alt="">
                                  </div>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </a>
                      </div>

                      @if($value->luxury == 'Yes')
                        <div class="badge-cls">
                          <span>
                            <img src="{{ URL::asset('assets/web/img/luxury.svg')}}" alt="">
                          </span>
                        </div>
                      @endif
                      <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                        <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                        </span>
                        <span class="heart_filled_product_{{$value->id}} {{$value->is_fav == 1 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                        </span>
                      </div>
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="pro-cont">
                          <h3 class="skeleton">{{$value->title}}</h3>
                          <div class="reting-location skeleton">
                            <div class="location-cls">
                              <div class="location-icon">
                                <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                                @endif

                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                                @endif
                              </div>
                            </div>
                            <div class="reting-cls">
                              <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                              </div>
                            </div>
                          </div>
                          <div class="pro-price skeleton">
                            <h2>NGN {{$value->price}} <span>Night</span></h2>
                          </div>
                          <div class="pro-dtl">
                            <div class="pro-dtl-left">
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt="">
                                </span>
                                <span>{{$value->max_guest}}</span>
                              </div>
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                                </span>
                                <span>{{isset($value->getPropertyBedroom) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                              </div>
                            </div>
                            @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                            <div class="pro-dtl-right">
                              <div class="pro-icon-bg skeleton">
                                <span class="pro-ic">
                                  <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
                                </span>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

    <section class="location_gallery_sec space-cls">    
      <div class="container">
      <div class="inner_title">
            <div class="title-left">
              <h3>Listings By Ratings</h3>
            </div>
            <div class="title_right">
             
            </div>
          </div>
        <div class="row">
          <div class="col-md-6">
            <a href='{{url("properties")."?rating=5"}}'>
              <div class="galelry_box skeleton">
                <div class="gallery_img">
                  <img src="{{ asset('images/pexels-vecislavas-popa-1571460.JPG' )}}">
                </div>
                <div class="gallery_location">
                  <h3>
                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                    Rating
                </h3>
                </div>
              </div>
            </a>
            <div class="row">
                <!--div class="col-md-12">
                  <a href='{{url("properties")."?accommodation_type=".$data["accommodation_types"][1]->id}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{$data['accommodation_types'][1]->image}}">
                      </div>
                      <div class="gallery_location">
                        <h3>{{$data['accommodation_types'][1]->name}}</h3>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-6">
                  <a href='{{url("properties")."?accommodation_type=".$data["accommodation_types"][2]->id}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{$data['accommodation_types'][2]->image}}">
                      </div>
                      <div class="gallery_location">
                        <h3>{{$data['accommodation_types'][2]->name}}</h3>
                      </div>
                    </div>
                  </a>
                </div-->
            </div>
          </div>
          <div class="col-md-6">
           
            <div class="galelry_box skeleton">

              <a href='{{url("properties")."?rating=4"}}'>
                <div class="gallery_img">
                  <img src="{{ asset('images/pexels-max-rahubovskiy-6058444.JPG' )}}">
                </div>
                <div class="gallery_location">
                  <h3>
                  <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                  <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                  <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                  <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                  Rating
                  </h3>
                </div>
              </a>
            </div>
            <!--div class="row">
                <div class="col-md-6">
                  <a href='{{url("properties")."?accommodation_type=".$data["accommodation_types"][3]->id}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{$data['accommodation_types'][3]->image}}">
                      </div>
                      <div class="gallery_location">
                        <h3>{{$data['accommodation_types'][3]->name}}</h3>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-6">
                  <a href='{{url("properties")."?accommodation_type=".$data["accommodation_types"][4]->id}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{$data['accommodation_types'][4]->image}}">
                      </div>
                      <div class="gallery_location">
                        <h3>{{$data['accommodation_types'][4]->name}}</h3>
                      </div>
                    </div>
                  </a>
                </div>
            </div>
          </div-->
        </div>
        <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
          <a href='{{url("properties")."?rating=3"}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{ asset('images/pexels-burst-545034.JPG' )}}">
                      </div>
                      <div class="gallery_location">
                        <h3> 
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                          Rating
                        </h3>
                      </div>
                    </div>
                  </a>
          </div>
          <div class="col-md-3">
          <a href='{{url("properties")."?rating=2"}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{ asset('images/pexels-houzlook-com-3797991.JPG  ' )}}">
                      </div>
                      <div class="gallery_location">
                        <h3>
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                          <img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">
                          Rating
                        </h3>
                      </div>
                    </div>
                  </a>
          </div>
          <div class="col-md-3">
          <a href='{{url("properties")."?rating=1"}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{ asset('images/pexels-photo-1571463.jpg' )}}">
                      </div>
                      <div class="gallery_location">
                        <h3><img src="{{ URL::asset('assets/web/img/shortlet/star.svg')}}" alt="">Rating </h3>
                      </div>
                    </div>
                  </a>
          </div>
          <div class="col-md-3">
          <a href='{{url("properties")."?rating=0"}}'>
                    <div class="galelry_box gallery_center skeleton">
                      <div class="gallery_img">
                        <img src="{{ asset('images/pexels-max-rahubovskiy-6782574.JPG' )}}">
                      </div>
                      <div class="gallery_location">
                        <h3>No Ratings Yet</h3>
                      </div>
                    </div>
                  </a>
          </div>
        </div>  
        </div>
      </div>
    </section>
    

    <?php if (isset($data['rareProperty'][0])) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>Rare Listings</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=rare' }}">View All</a>
            </div>
          </div>
          <div class="">
            <div class="row">
              <?php foreach ($data['rareProperty'] as $key => $value) { ?>
                  <div class="col_5">
                    <div class="pro_box skeleton">
                      <!-- <div class="pro_img">
                        <a href="{{ url('property-detail').'/'.$value->id }}"><img src="{{$value->image}}" alt=""></a>
                      </div> -->
                      <div class="pro_img_main skeleton">
                        <a href="{{ url('property-detail').'/'.$value->id }}">
                          <div class="inner_img_sld owl-carousel">
                            <div class="item">
                              <div class="pro_img">
                                <img src="{{$value->image}}" alt="">
                              </div>
                            </div>
                            <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>

                                <?php if ($k >= 8) { break; } ?>
                                <div class="item">
                                  <div class="pro_img">
                                    <img src="{{$v->image}}" alt="">
                                  </div>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </a>
                      </div>
                      @if($value->rare == 'Yes')
                        <div class="badge-cls">
                          <span>
                            <img src="{{ URL::asset('assets/web/img/rare.svg')}}" alt="">
                          </span>
                        </div>
                      @endif
                      <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                        <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                        </span>
                        <span class="heart_filled_product_{{$value->id}} {{$value->is_fav == 1 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                        </span>
                      </div>
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="pro-cont">
                          <h3 class="skeleton">{{$value->title}}</h3>
                          <div class="reting-location skeleton">
                            <div class="location-cls">
                              <div class="location-icon">
                                <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                                @endif

                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                                @endif
                              </div>
                            </div>
                            <div class="reting-cls">
                              <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                              </div>
                            </div>
                          </div>
                          <div class="pro-price skeleton">
                            <h2>NGN {{$value->price}} <span>Night</span></h2>
                          </div>
                          <div class="pro-dtl">
                            <div class="pro-dtl-left">
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt="">
                                </span>
                                <span>{{$value->max_guest}}</span>
                              </div>
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                                </span>
                                <span>{{isset($value->getPropertyBedroom) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                              </div>
                            </div>
                            @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                            <div class="pro-dtl-right">
                              <div class="pro-icon-bg skeleton">
                                <span class="pro-ic">
                                  <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
                                </span>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

    <?php if (isset($data['province'][0])) { ?>
      <section class="location_gallery_sec space-cls">
        <div class="container">

        <div class="inner_title">
            <div class="title-left">
              <h3>Listings by locations</h3>
            </div>
            <div class="title_right">
             
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">

              @if(isset($data['province'][0]))
                <a href='{{url("properties")."?province_id=".$data["province"][0]->id}}'>
                  <div class="galelry_box skeleton">
                    <div class="gallery_img">
                      <img src="{{$data['province'][0]->image}}">
                    </div>
                    <div class="gallery_location">
                      <h3>{{$data['province'][0]->name}}</h3>
                    </div>
                  </div>
                </a>
              @endif

              @if(isset($data['province'][1]))
                <a href='{{url("properties")."?province_id=".$data["province"][1]->id}}'>
                  <div class="galelry_box skeleton">
                    <div class="gallery_img">
                      <img src="{{$data['province'][1]->image}}">
                    </div>
                    <div class="gallery_location">
                      <h3>{{$data['province'][1]->name}}</h3>
                    </div>
                  </div>
                </a>
              @endif
            </div>
            <div class="col-md-4">
              @if(isset($data['province'][2]))
                <a href='{{url("properties")."?province_id=".$data["province"][2]->id}}'>
                  <div class="galelry_box gallery_center skeleton">
                    <div class="gallery_img">
                      <img src="{{$data['province'][2]->image}}">
                    </div>
                    <div class="gallery_location">
                      <h3>{{$data['province'][2]->name}}</h3>
                    </div>
                  </div>
                </a>
              @endif
            </div>
            <div class="col-md-4">

              @if(isset($data['province'][3]))
                <a href='{{url("properties")."?province_id=".$data["province"][3]->id}}'>
                  <div class="galelry_box skeleton">
                    <div class="gallery_img">
                      <img src="{{$data['province'][3]->image}}">
                    </div>
                    <div class="gallery_location">
                      <h3>{{$data['province'][3]->name}}</h3>
                    </div>
                  </div>
                </a>
              @endif

              @if(isset($data['province'][4]))
                <a href='{{url("properties")."?province_id=".$data["province"][4]->id}}'>
                  <div class="galelry_box skeleton">
                    <div class="gallery_img">
                      <img src="{{$data['province'][4]->image}}">
                    </div>
                    <div class="gallery_location">
                      <h3>{{$data['province'][4]->name}}</h3>
                    </div>
                  </div>
                </a>
              @endif
            </div>

          </div>
        </div>
      </section>
    <?php } ?>

    <?php /*if (isset($data['property'][0])) { ?>
      <section class="properties_sec space-cls">
        <div class="container">
          <div class="inner_title">
            <div class="title-left">
              <h3>More Listings</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=property' }}">View All</a>
            </div>
          </div>
          <div class="product_list">
            <div class="row">
              <?php foreach ($data['property'] as $key => $value) { ?>
                  <div class="col_5">
                    <div class="pro_box skeleton">
                      <!-- <div class="pro_img">
                        <a href="{{ url('property-detail').'/'.$value->id }}"><img src="{{$value->image}}" alt=""></a>
                      </div> -->
                      <div class="pro_img_main skeleton">
                        <a href="{{ url('property-detail').'/'.$value->id }}">
                          <div class="inner_img_sld owl-carousel">
                            <div class="item">
                              <div class="pro_img">
                                <img src="{{$value->image}}" alt="">
                              </div>
                            </div>
                            <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>

                                <?php if ($k >= 8) { break; } ?>
                                <div class="item">
                                  <div class="pro_img">
                                    <img src="{{$v->image}}" alt="">
                                  </div>
                                </div>
                              <?php } ?>
                            <?php } ?>
                          </div>
                        </a>
                      </div>
                      @if($value->featured == 'Yes')
                        <div class="badge-cls">
                          <span>
                            <img src="{{ URL::asset('assets/web/img/featured-badge.png')}}" alt="">
                          </span>
                        </div>
                      @endif
                      <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                        <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                        </span>
                        <span class="heart_filled_product_{{$value->id}} {{$value->is_fav == 1 ? '' : 'd-none'}}">
                          <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                        </span>
                      </div>
                      <a href="{{ url('property-detail').'/'.$value->id }}">
                        <div class="pro-cont">
                          <h3 class="skeleton">{{$value->title}}</h3>
                          <div class="reting-location skeleton">
                            <div class="location-cls">
                              <div class="location-icon">
                                <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyArea)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyArea->name}} - </p>
                                @endif

                                @if(isset($value->getPropertyAddress[0]) && $value->getPropertyAddress[0]->getPropertyCity)
                                  <p>{{$value->getPropertyAddress[0]->getPropertyCity->name}}</p>
                                @endif
                              </div>
                            </div>
                            <div class="reting-cls">
                              <div class="reting-icon">
                                <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
                              </div>
                              <div class="location-cont">
                                <p>{{ number_format($value->avg_rating,1) }}({{ $value->total_rating ?? 0 }})</p>
                              </div>
                            </div>
                          </div>
                          <div class="pro-price skeleton">
                            <h2>NGN {{$value->price}} <span>Night</span></h2>
                          </div>
                          <div class="pro-dtl">
                            <div class="pro-dtl-left">
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt="">
                                </span>
                                <span>{{$value->max_guest}}</span>
                              </div>
                              <div class="pro-dtl-list skeleton">
                                <span class="icon-cls">
                                  <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                                </span>
                                <span>{{isset($value->getPropertyBedroom) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                              </div>
                            </div>
                            @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                            <div class="pro-dtl-right">
                              <div class="pro-icon-bg skeleton">
                                <span class="pro-ic">
                                  <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
                                </span>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
    <?php }*/ ?>

    <input type="hidden" name="count_all_properties" class="count_all_properties" value="{{$data['count_all_properties'] ?? 0}}">
    <input type="hidden" name="last_record_value" class="last_record_value" value="15">
    <section class="properties_sec space-cls">
      <div class="container">

      <div class="inner_title">
            <div class="title-left">
              <h3>More Listings</h3>
            </div>
            <div class="title_right">
              <a href="{{ route('web.properties').'?list_type=property' }}">View All</a>
            </div>
          </div>
        <div class="more_product_list"></div>
      </div>
    </section>

    <div class="container load_more_btn_div">
                <div class="load-more">
                  <a href="javascript:void(0);" class="load-more-cls btn primary_btn load_more_btn"><span class="spin"><img src="{{ URL::asset('assets/web/img/spinner.png')}}" alt=""></span> Load More</a>
                </div>
              </div>

    

</div>
    <!-- <div class="container">
      <div class="load-more">
        <a href="#" class="load-more-cls btn primary_btn"><span class="spin"><img src="{{ URL::asset('assets/web/img/spinner.png')}}" alt=""></span> Load More</a>
      </div>
    </div> -->
  </main>
@endsection
@section('script')
<script type="text/javascript">

getPropertyFiltered(15);
$(".load_more_btn").click(function(){
    var oldVal   = parseInt($(".last_record_value").val());
    getPropertyFiltered(oldVal+15);
  })


  function getPropertyFiltered(val) {
    $("#first-load").fadeIn(1000);
    var list_type = "property";

    var last_record_value = val;
    var last_record_value_1 = $('.count_all_properties').val();
    $(".last_record_value").val(val);

    var formData = new FormData(); // Currently empty
    var token = "{{ csrf_token() }}";
    formData.append('_token', token);
    formData.append('last_record_value', last_record_value);
    formData.append('list_type', list_type);

   

    $.ajax({
      url: '{{ route("web.getHomeProperties") }}',
      dataType: 'json',
      // dataType: 'html',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        $('.count_all_properties').val(res.count);
        // $('.load_more_btn_div').hide();
        console.log('res.count--'+res.count);
        console.log('last_record_value--'+last_record_value);
        console.log('last_record_value_1--'+last_record_value_1);
        // if(res.count > last_record_value){
          if(last_record_value >= last_record_value_1){
            console.log('iff');
            $('.load_more_btn_div').hide();
          }else{
            if(res.count > 0){
              if(res.count > last_record_value){
                console.log('iff1');
                $('.load_more_btn_div').show();
                // $('.load_more_btn_div').hide();
              }else{
                console.log('else1');
                $('.load_more_btn_div').show();
              }
            }else{
              $('.load_more_btn_div').hide();
            }
          }
        // }else{
        //   $('.load_more_btn_div').hide();
        // }

        // console.log(res.count);
        $('.more_product_list').html(res.message);
        $("#first-load").fadeOut();
        $('.property_exact_count').html('');
        if(res.exact_count > 0){
          $('.property_exact_count').html('1-'+res.exact_count);
          $('.property_total_count').html(res.count);
        }else{
          $('.property_exact_count').html('0');
          $('.property_total_count').html('0');
        }
        /*if (res.status === true) {
          toastr.success(res.message);
        } else {
          toastr.error(res.message);
        }*/
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
</script>
@endsection