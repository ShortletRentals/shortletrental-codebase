<div class="row">
  <?php if (count($data['properties'])) { foreach ($data['properties'] as $key => $value) { ?>
      <div class="col_5">
        <div class="pro_box">
          <div class="pro_img_main">
            <a href="{{ url('property-detail').'/'.$value->id }}">
              <div class="inner_img_sld owl-carousel">
                <div class="item">
                  <div class="pro_img">
                    <img src="{{$value->image}}" alt="">
                  </div>
                </div>
                <?php if ($value->getPropertyImages) { foreach ($value->getPropertyImages as $k => $v) { ?>
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
            <span class="heart_filled_product_{{$value->id}}  {{$value->is_fav == 1 ? '' : 'd-none'}}">
              <img src="{{ URL::asset('assets/web/img/heart-2.png')}}" alt="">
            </span>
          </div>
          <a href="{{ url('property-detail').'/'.$value->id }}">
            <div class="pro-cont">
              <h3>{{$value->title}}</h3>
              <div class="reting-location">
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
              <div class="pro-price">
                <h2>NGN {{$value->price}} <span>Night</span></h2>
              </div>
              <div class="pro-dtl">
                <div class="pro-dtl-left">
                  <div class="pro-dtl-list">
                    <span class="icon-cls">
                      <img src="{{ URL::asset('assets/web/img/account-user.png')}}" alt="">
                    </span>
                    <span>{{$value->max_guest}}</span>
                  </div>
                  <div class="pro-dtl-list">
                    <span class="icon-cls">
                      <img src="{{ URL::asset('assets/web/img/bed.png')}}" alt="">
                    </span>
                    <span>{{ isset($value->getPropertyBedroom[0]) && !empty($value->getPropertyBedroom[0]->no_of_bedrooms) ? $value->getPropertyBedroom[0]->no_of_bedrooms : ''}}</span>
                  </div>
                </div>
                @if(isset($value->getUser) && $value->getUser->is_super_host == 'Yes')
                <div class="pro-dtl-right">
                  <div class="pro-icon-bg">
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


<script type="text/javascript">
$(document).ready(function() {
  var owl = $('.inner_img_sld.owl-carousel');
  owl.owlCarousel({
      margin: 0,
      nav:false,
      dots:true,
      autoplay: true,
      loop: false,
      responsive: {
        320: {
          items: 1
        },
        420: {
          items: 1
        },
        577: {
          items: 1
        },
        992: {
          items: 1
        }
      }
  });
});
</script>