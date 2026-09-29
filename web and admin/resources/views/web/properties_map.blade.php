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
                    <a class="view-trigger list_btn" href="{{ url('properties'); }}" >
                      <img src="{{ URL::asset('assets/web/img/list.png')}}" alt="">
                    </a>
                    <a class="view-trigger list_remove_btn" href="{{ url('properties'); }}">
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
                  <div id="map"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection
@section('script')
<style type="text/css">
  #map {
    height: calc(100vh - 205px);
}
  body {
      height: 100vh;
      overflow: hidden;
  }
  .filter_left_s::-webkit-scrollbar {
    width: 5px;
  }
   
  .filter_left_s::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.3);
  }
   
  .filter_left_s::-webkit-scrollbar-thumb {
    background-color: darkgrey;
  }

  .filter_left_s {
      height: calc(100vh - 130px);
      overflow: auto;
  }
  @media (max-width:  767.98px) {
    #map {
        height: calc(100vh - 385px);
    }
    .filter_left_s {
      height: unset;
  }
  }
</style>
        <script>
            let initialMarkers = <?php echo json_encode($initialMarkers); ?>;
            let current_lat = <?php echo $location['latitude']; ?>;
            let current_lng = <?php echo $location['longitude']; ?>;
            let map, activeInfoWindow, markers = [];

            /* ----------------------------- Initialize Map ----------------------------- */
            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        //lat: 25.0376,
                        //lng: 76.4563,
                        lat: current_lat,
                        lng: current_lng,
                    },
                    zoom: 8
                });

                map.addListener("click", function(event) {
                    mapClicked(event);
                });

                initMarkers(initialMarkers);

            }

            /* --------------------------- Initialize Markers --------------------------- */
            function initMarkers(initialMarkers) {
                console.log('here--------------12', initialMarkers);

                for (let index = 0; index < initialMarkers.length; index++) {

                    const markerData = initialMarkers[index];
                    const marker = new google.maps.Marker({
                        position: markerData.position,
                        label: markerData.label,
                        icon: markerData.icon,
                        draggable: markerData.draggable,
                        map
                    });
                    markers.push(marker);

                    const infowindow = new google.maps.InfoWindow({
                        content: markerData.html,
                    });
                    marker.addListener("click", (event) => {
                        if(activeInfoWindow) {
                            activeInfoWindow.close();
                        }
                        infowindow.open({
                            anchor: marker,
                            shouldFocus: false,
                            map
                        });
                        activeInfoWindow = infowindow;
                        markerClicked(marker, index);
                    });

                    marker.addListener("dragend", (event) => {
                        markerDragEnd(event, index);
                    });
                }

                // Add a marker clusterer to manage the markers.
                // new MarkerClusterer({ markers, map });
            }

            /* ------------------------- Handle Map Click Event ------------------------- */
            function mapClicked(event) {
                console.log(map);
                console.log(event.latLng.lat(), event.latLng.lng());
            }

            /* ------------------------ Handle Marker Click Event ----------------------- */
            function markerClicked(marker, index) {
                console.log('map--------------');
                console.log(marker.position.lat());
                console.log(marker.position.lng());
            }

            /* ----------------------- Handle Marker DragEnd Event ---------------------- */
            function markerDragEnd(event, index) {
                console.log(map);
                console.log(event.latLng.lat());
                console.log(event.latLng.lng());
            }

            function openClickUrl(url) {
              window.location.href = url;
            }
        </script>
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
    formData.append('last_record_value', last_record_value);
    formData.append('list_type', list_type);

    if (star >= 1) {
      formData.append('star', star);
    }

    $.ajax({
      url: '{{ route("web.getFilteredMapProperties") }}',
      dataType: 'json',
      // dataType: 'html',
      data: formData,
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function(res) {
        $('.count_all_properties').val(res.count);

        initialMarkers = res.initialMarkers;
        initMap();
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY&callback=initMap" async></script>


@endsection