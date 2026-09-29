@extends('layouts.web.master')

@section('content')
<?php ///Page 4 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
            <h3>We let guests know what your place has to offer?</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                <div class="multi-select">

                @if(count($amenities) > 0)
                  @foreach($amenities as $amenity)
                  <label class="custom_checkbox">
                    <input type="checkbox" name="amenity" value="{{$amenity->id}}">
                    <span class="checkmark"></span>
                    <div class="property-bx">
                      <div class="pr-icon">
                        <img src="{{ $amenity->image }}" alt="{{$amenity->name}}">
                      </div>
                      <div class="pr-name">
                        <h5>{{$amenity->name}}</h5>
                      </div>
                    </div>
                  </label>
                  @endforeach
                @endif
                </div>
                <div class="col-md-12 mt-3">
                  <label for="inputPostalCode" class="form-label">Standout Amenities</label>
                  <input name="standout_amenities" class="form-control standout_amenities" id="standout_amenities" type="text" placeholder="Standout Amenities">
                </div>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_guest') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn amenity_next">Next</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>  
  </main>

  <script>
    /*Add card Start */
    $(document).ready(function(){
      getAmenity();
    })
    function getAmenity(){
      // if(sessionStorage.getItem("host_beds") != null){
      //   $("#beds").val(sessionStorage.getItem("host_beds"));
      // }

      if(sessionStorage.getItem("host_amenity") != null){
        // $("#beds").val(sessionStorage.getItem("host_beds"));
      }
      $("#standout_amenities").val(sessionStorage.getItem("standout_amenities"));

      // $('[name="amenity"]').each( function (i, data){
      //   if($(this).prop('checked') == true){
      //     amenity[i] = $(this).val();
      //     // alert($(this).val());
      //     // alert(i);
      //   }
      // });
    }

    var amenity = [];
    var amenity1 = [];
    $(document).on('click', ".amenity_next", function(e) {
      var standout_amenities = $('#standout_amenities').val();
      var i_count = 1;
      $('[name="amenity"]').each( function (i, data){
        if($(this).prop('checked') == true){
          amenity[i_count] = $(this).val();
          // amenity = amenity.replace(/^,|,$/g,'');
          // alert($(this).val());
          // alert(i);
          // alert(amenity1);
          i_count++;
          // amenity = amenity[i_count].replace(/^,/, '');
          // amenity = amenity.substring(1);
          // alert(amenity);
        }
      });
      // alert($.type(amenity));
      if(amenity != ''){
          sessionStorage.setItem("host_amenity", amenity);
          sessionStorage.setItem("standout_amenities", standout_amenities);
          window.location.replace("{{ route('web.become_a_host.host_max_guest') }}");
      }else{
        alert('Please select atleast one amenity.');
        return false;
      }
    });

  /*Add card End*/
  </script>
@endsection