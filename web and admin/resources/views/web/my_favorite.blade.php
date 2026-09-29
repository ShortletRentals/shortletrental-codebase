@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');

    if (isset($auth_user)) {
      $userId = $auth_user->data->id;
    }
?>
@section('content')
	<style type="text/css">
		/*#loader {  
	      position: fixed;  
	      left: 0px;  
	      top: 0px;  
	      width: 100%;  
	      height: 100%;  
	      z-index: 9999;  
	      background: url('assets/web/images/pageloader.gif') 50% 50% no-repeat rgb(249,249,249);  
	  }*/


	</style>
    <main>
			<!-- <div id="loader">
				<div class="loader_image"><img src="{{ URL::asset('assets/web/img/logo.png')}}"></div>
			</div> -->
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
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			@include('layouts.web.leftbar_itms')
	        			<div class="sidebar_r">
	        				<div class="inner-title">
	        					<h2 class="heading-inner-title">My Favorites</h2>
	        				</div>
	        				<div class="my-booking-sec">
					            <div class="row">

					            	<?php if ($data->status == true && count($data->data)) { foreach ($data->data as $key => $value) { if ($value->get_property) { ?>
									            <div class="col-md-3 product_div_{{$value->get_property->id}}">
									                <div class="pro_box">
									                	<a href="{{ url('property-detail').'/'.$value->get_property->id }}">
										                  <div class="pro_img">
										                    <img src="{{$value->get_property->image}}" alt="">
										                  </div>
																		</a>
									                  @if($value->get_property->featured == 'Yes')
										                  <div class="badge-cls">
										                    <span>
										                      <img src="{{ URL::asset('assets/web/img/featured-badge.png')}}" alt="">
										                    </span>
										                  </div>
										                @endif
									                  <!-- <div class="share-option">
									                      <a href="#">
									                        <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="Like">
									                      </a>
									                  </div> -->
									                  <div class="heart_right share-option" onclick="addToWishList(this)" data-id="{{$value->get_property->id}}">
					                            <span class="heart_filled_product_{{$value->get_property->id}}">
					                              <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
					                            </span>
					                          </div>
					                          <a href="{{ url('property-detail').'/'.$value->id }}">
										                  <div class="pro-cont">
										                  	<!-- <a href="{{ url('property-detail').'/'.$value->get_property->id }}"> -->
										                    	<h3>{{$value->get_property->title}}</h3>
																				<!-- </a> -->
										                    <div class="reting-location">
										                      <div class="location-cls">
										                        <div class="location-icon">
										                          <img src="{{ URL::asset('assets/web/img/location_icon.png')}}" alt="">
										                        </div>
										                        <div class="location-cont">
										                          <p>{{$value->get_property->get_property_address[0]->address}}</p>
										                        </div>
										                      </div>
										                      <div class="reting-cls">
										                        <div class="reting-icon">
										                          <img src="{{ URL::asset('assets/web/img/star.png')}}" alt="">
										                        </div>
										                        <div class="location-cont">
										                          <p>{{ number_format($value->get_property->avg_rating,1) }}</p>
										                        </div>
										                      </div>
										                    </div>
										                    <div class="pro-price">
										                      <h2>NGN {{$value->get_property->commission_price}} <span>Night</span></h2>
										                    </div>
										                    <div class="pro-dtl">
										                      <div class="pro-dtl-left">
										                        <div class="pro-dtl-list">
										                          <span class="icon-cls">
										                            <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt="">
										                          </span>
										                          <span>{{$value->get_property->max_guest}}</span>
										                        </div>
										                        <div class="pro-dtl-list">
										                          <span class="icon-cls">
										                            <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
										                          </span>
										                          <span>{{$value->get_property->get_property_bedroom[0]->no_of_bedrooms}}</span>
										                        </div>
										                      </div>
										                      <div class="pro-dtl-right">
										                        <div class="pro-icon-bg">
										                          <span class="pro-ic">
										                            <img src="{{ URL::asset('assets/web/img/superhost.png')}}" alt="">
										                          </span>
										                        </div>
										                      </div>
										                    </div>
										                  </div>
										                </a>
									                </div>
									            </div>
														<?php } ?>
													<?php } ?>
				                <?php } else { ?>
				                    <div class="data_not_found">
													      <img src="{{ URL::asset('assets/web/img/Data_not_found.png')}}" alt="">
													    </div>
				                <?php } ?>
					            </div>
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
  $("#first-load").fadeOut(1000); 
  
  function addToWishList($this) {
    var product_id = $($this).attr('data-id');
    var user_id = "{{$userId ?? ''}}";

    if (user_id != '') {
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

          if (res.status === 1) {
            toastr.success(res.message);
          } else {
          	$('.product_div_'+product_id).remove();
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