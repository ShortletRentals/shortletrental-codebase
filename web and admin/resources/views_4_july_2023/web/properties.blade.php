@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');
    $is_guest = Session::get('is_guest');

    if (isset($auth_user) && $is_guest != 1) {
      $userId = $auth_user->data->id;
    }
?>
@section('content')

<link href="//www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
    <main>
      <section id="first-load">
        <span>Loading...</span>
        <img src="{{ URL::asset('assets/web/img/logo.png')}}" alt="logo" width="auto">
        <div class="box-loader">
          <div class="container">
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
          </div>
        </div>
      </section>
      <section class="filter_sec">
        <div class="container-fluid">
          <div class="filter_row">
            <div class="filter_left_s">
              <div class="filter_mobile_s">
                <h4>Filter</h4> <img src="{{ URL::asset('assets/web/img/filter.png')}}">
              </div>
              <div class="filter_s">
                <a href="javascript:void(0);" class="filter_cross">✖</a>
                <div class="filter_title">
                  <h3>Filter</h3>
                  <a href="javascript:void(0);" onclick="resetFilter()">Clear All</a>
                </div>
                <div class="filter_serch">
                  <div class="form-group mb-0">
                    <input type="text" name="property_name" class="property_name form-control" value="" onkeyup="getPropertyFiltered(15)" placeholder="Search...">
                    <div class="search_i">
                      <img src="{{ URL::asset('assets/web/img/search.png')}}" alt="">
                    </div>
                  </div>
                </div>
                <div class="filter_cont_s">
                  <h4>TYPE OF ACCOMMODATION</h4>
                  <div class="filter_cont_s_inner">
                    <label class="custom_radio_b">
                      <input type="radio" checked="checked" name="accommodation_type" onclick="getPropertyFiltered(15)" value="all">
                      <span class="checkmark"></span>All
                    </label>

                    <?php if (count($data['accommodation_type'])) { foreach ($data['accommodation_type'] as $k_acco => $v_acco) { ?>
                        <label class="custom_radio_b">
                          <input type="radio" name="accommodation_type" <?php if(isset($_GET['accommodation_type']) && $_GET['accommodation_type']) { echo $v_acco->id == $_GET['accommodation_type'] ? 'checked' : ''; } ?> onclick="getPropertyFiltered(15)" value="{{$v_acco->id}}" >
                          <span class="checkmark"></span>{{$v_acco->name}}
                        </label>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
                <div class="filter_cont_s">
                  <h4>CATEGORY</h4>
                  <div class="filter_cont_s_inner">
                  <?php if (count($data['category'])) { foreach ($data['category'] as $k_cat => $v_cat) { ?>
                      <label class="custom_checkbox">
                        <input type="checkbox" name="category_type" <?php if(isset($_GET['category_id']) && $_GET['category_id']) { echo $v_cat->id == $_GET['category_id'] ? 'checked' : ''; } ?> onclick="getPropertyFiltered(15)" value="{{$v_cat->id}}">
                        <span class="checkmark"></span>
                        {{$v_cat->name}}
                      </label>
                    <?php } ?>
                  <?php } ?>
                </div>
                </div>
                <div class="filter_cont_s">
                  <h4>NUMBER OF BEDROOMS</h4>
                  <div class="filter_number">
                    <label class="custom_checkbox">
                      <input type="radio" name="bedroom" value="1" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">1</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bedroom" value="2" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">2</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bedroom" value="3" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">3</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bedroom" value="4" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">4</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bedroom" value="5" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">5</span>
                    </label>
                      <span class="more_cls">or more</span>
                  </div>
                </div>

                <div class="filter_cont_s">
                  <h4>NUMBER OF BATHROOMS</h4>
                  <div class="filter_number">
                    <label class="custom_checkbox">
                      <input type="radio" name="bathroom" value="1" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">1</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bathroom" value="2" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">2</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bathroom" value="3" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">3</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bathroom" value="4" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">4</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="bathroom" value="5" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">5</span>
                    </label>
                      <span class="more_cls">or more</span>
                  </div>
                </div>

                <div class="filter_cont_s">
                  <h4>IS FEATURED</h4>
                  <div class="filter_number">
                    <label class="custom_checkbox">
                      <input type="radio" name="featured" value="Yes" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">Yes</span>
                    </label>
                    <label class="custom_checkbox">
                      <input type="radio" name="featured" value="No" onclick="getPropertyFiltered(15)" >
                      <span class="checkmark">No</span>
                    </label>
                  </div>
                </div>
                
                <div class="filter_cont_s">
                  <h4>Amenity</h4>
                  <div class="filter_cont_s_inner mb-0">
                  <?php if (count($data['amenities'])) { foreach ($data['amenities'] as $k_amt => $v_amt) { ?>
                      <label class="custom_checkbox">
                        <input type="checkbox" name="amenity_type" onclick="getPropertyFiltered(15)" value="{{$v_amt->id}}">
                        <span class="checkmark"></span>
                        {{$v_amt->name}}
                      </label>
                    <?php } ?>
                  <?php } ?>
                </div>
                </div>
                <div class="filter_cont_s">
                    <h4>REVIEW</h4>
                    <div class="review-option stars">
                      <input class="star star-5" value="5" id="star-5-2" type="radio" onclick="getPropertyFiltered(15)" name="star"/>
                      <label class="star star-5" for="star-5-2"></label>
                      <input class="star star-4" value="4" id="star-4-2" type="radio" onclick="getPropertyFiltered(15)" name="star"/>
                      <label class="star star-4" for="star-4-2"></label>
                      <input class="star star-3" value="3" id="star-3-2" type="radio" onclick="getPropertyFiltered(15)" name="star"/>
                      <label class="star star-3" for="star-3-2"></label>
                      <input class="star star-2" value="2" id="star-2-2" type="radio" onclick="getPropertyFiltered(15)" name="star"/>
                      <label class="star star-2" for="star-2-2"></label>
                      <input class="star star-1" value="1" id="star-1-2" type="radio" onclick="getPropertyFiltered(15)" name="star"/>
                      <label class="star star-1" for="star-1-2"></label>
                    </div>
                </div>
              </div>
            </div>
            <div class="filter_right_cont">
              <div class="listing-archive-top">
                <h2 class="rtin-title count_properties">Showing <span class="property_exact_count">1–{{count($data['properties'])}}</span> of <span class="property_total_count">{{$count_all_properties}}</span> results</h2>
                <!-- <h2 class="rtin-title count_properties">Showing <span class="property_exact_count">1–{{count($data['properties'])}}</span> of {{count($data['properties'])}} results</h2> -->
                <div class="listing-sorting">
                  <form class="ordering" method="get">
                    <select name="orderby" class="orderby price_orderby" onchange="getPropertyFiltered(15)" aria-label="Listing order">
                      <option value="">Sort By</option>
                      <option value="max_guest">Nº of people</option>
                      <option value="asc">Price Low to high</option>
                      <option value="desc">Price High to low</option>
                      <option value="name_asc">Name A-Z</option>
                      <option value="name_desc">Name Z-A</option>
                    </select>
                    <div class="sort_i">
                      <img src="{{ URL::asset('assets/web/img/sortby.png')}}" alt="">
                    </div>
                  </form>
                  <div class="view-switcher">
                    <a class="view-trigger list_btn" href="javascript:void(0)" >
                      <img src="{{ URL::asset('assets/web/img/list.png')}}" alt="">
                    </a>
                    <a class="view-trigger list_remove_btn" href="javascript:void(0)">
                      <img src="{{ URL::asset('assets/web/img/grid.png')}}" alt="">
                    </a>
                    <a class="view-trigger" href="{{ url('properties/map'); }}">
                      <img src="{{ URL::asset('assets/web/img/map-white.png')}}" alt="">
                    </a>
                  </div>
                </div>
              </div>
              <div class="product_list">
                <div class="row">
                  <?php if (count($data['properties'])) { foreach ($data['properties'] as $key => $value) { ?>
                      <div class="col_5">
                        <div class="pro_box skeleton">
                          <div class="pro_img_main skeleton">
                              <div class="inner_img_sld owl-carousel">
                                <div class="item">
                                  <a href="{{ url('property-detail').'/'.$value->id }}">
                                    <div class="pro_img">
                                      <img src="{{$value->image}}" alt="">
                                    </div>
                                  </a>
                                </div>
                                <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>
                                    <div class="item">
                                      <a href="{{ url('property-detail').'/'.$value->id }}">
                                        <div class="pro_img">
                                          <img src="{{$v->image}}" alt="">
                                        </div>
                                      </a>
                                    </div>
                                  <?php } ?>
                                <?php } ?>
                              </div>
                              
                              @if(!empty($list_type))
                            <div class="badge-cls">
                              <span>
                                  @if($list_type == 'featured')
                                <img src="{{ URL::asset('assets/web/img/featured-badge.png')}}" alt="">
                                @elseif($list_type == 'superhost')
                                <img src="{{ URL::asset('assets/web/img/superhost-badge.svg')}}" alt="">
                                @elseif($list_type == 'luxury')
                                <img src="{{ URL::asset('assets/web/img/luxury.svg')}}" alt="">

                                @elseif($list_type == 'rare')
                                <img src="{{ URL::asset('assets/web/img/rare.svg')}}" alt="">
                 

                                @endif
                              </span>
                            </div>
                            @else
                            <div class="badge-cls">
                              <span>

                              @if($value->featured == 'Yes')
                                <img src="{{ URL::asset('assets/web/img/featured-badge.png')}}" alt="">
                                @elseif($value->luxury == 'Yes')
                                <img src="{{ URL::asset('assets/web/img/luxury.svg')}}" alt="">
                                @elseif($value->rare == 'Yes')
                                <img src="{{ URL::asset('assets/web/img/rare.svg')}}" alt="">
                                @endif
                                </span>
                            </div>
                            @endif
                         
                          <div class="heart_right" onclick="addToWishList(this)" data-id="{{$value->id}}">
                            <span class="heart_empty_product_{{$value->id}} {{$value->is_fav == 0 ? '' : 'd-none'}}">
                              <img src="{{ URL::asset('assets/web/img/heart.png')}}" alt="">
                            </span>
                            <span class="heart_filled_product_{{$value->id}}  {{$value->is_fav == 1 ? '' : 'd-none'}}">
                              <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
                            </span>
                          </div>
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

                                  @if(count($value->getPropertyBedroom))
                                    <div class="pro-dtl-list skeleton">
                                      <span class="icon-cls">
                                        <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                                      </span>
                                      <span>{{$value->getPropertyBedroom[0]->no_of_bedrooms}}</span>
                                    </div>
                                  @endif
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
                  <?php } else { ?>
                    <div class="data_not_found">
                      <img src="{{ URL::asset('assets/web/img/Data_not_found.png')}}" alt="">
                    </div>
                  <?php } ?>
                </div>
              </div>
              <input type="hidden" name="count_all_properties" class="count_all_properties" value="{{$count_all_properties}}">
              <input type="hidden" name="last_record_value" class="last_record_value" value="15">
              @if($count_all_properties > 15)
              <div class="container load_more_btn_div">
                <div class="load-more">
                  <a href="javascript:void(0);" class="load-more-cls btn primary_btn load_more_btn"><span class="spin"><img src="{{ URL::asset('assets/web/img/spinner.png')}}" alt=""></span> Load More</a>
                </div>
              </div>
              @endif

            </div>
          </div>
        </div>
      </section>
    </main>

@endsection
@section('script')
<script type="text/javascript">
  $("#first-load").fadeOut(1000); 
  $(".load_more_btn").click(function(){
    var oldVal   = parseInt($(".last_record_value").val());
    getPropertyFiltered(oldVal+15);
  })
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



  function getPropertyFiltered(val) {
    $("#first-load").fadeIn(1000);
    var list_type = "{{ $list_type }}";
    var price_sort = $('.price_orderby').val();
    var property_name = $('.property_name').val();
    var accommodation_type = $('input[name=accommodation_type]:checked').val();
    var star = $('input[name=star]:checked').val();
    var bedroom = $('input[name=bedroom]:checked').val();
    var bathroom = $('input[name=bathroom]:checked').val();
    var featured = $('input[name=featured]:checked').val();
    var category_type = $.map($('input[name="category_type"]:checked'), function(c){return c.value; });
    var amenity_type = $.map($('input[name="amenity_type"]:checked'), function(c){return c.value; });
    var last_record_value = val;
    var last_record_value_1 = $('.count_all_properties').val();
    $(".last_record_value").val(val);
    // alert(last_record_value_1);

    var formData = new FormData(); // Currently empty
    var token = "{{ csrf_token() }}";
    formData.append('_token', token);
    formData.append('price_sort', price_sort);
    formData.append('property_name', property_name);
    formData.append('accommodation_type', accommodation_type);
    formData.append('category_type', category_type);
    formData.append('amenity_type', amenity_type);
    formData.append('bedroom', bedroom);
    formData.append('bathroom', bathroom);
    formData.append('featured', featured);
    formData.append('last_record_value', last_record_value);
    formData.append('list_type', list_type);
    @foreach($request as $key =>$value)
    formData.append('{{$key}}', '{{$value}}');
    @endforeach

    if (star >= 1) {
      formData.append('star', star);
    }

    $.ajax({
      url: '{{ route("web.getFilteredProperties") }}',
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
        $('.product_list').html(res.message);
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
</script>
<script type="text/javascript">  
   
   function resetFilter() {
     window.location.href = '{{ route("web.properties") }}';
   }
   $(function(){
		$('.list_btn').on("click", function () {
		$('body').addClass("list_view");
				});
      });
      $(function(){
    $('.list_remove_btn').on("click", function () {
      $('body').removeClass("list_view");
				});
		
		}); 
   
</script>  
@endsection