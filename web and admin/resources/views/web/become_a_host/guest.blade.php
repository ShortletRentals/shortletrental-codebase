@extends('layouts.web.master')

@section('content')
<?php ///Page 3 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>What is the description of the accommodation?</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                <div class="property-list-s">
                    <div class="guest-dtl">
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Beds</h4>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" class="sub" data-type="beds"  data-decrease><img src="{{ URL::asset('assets/web/img/minus.png')}}" alt="Minus"></button>
                            <input class="count" type="text" id="beds" value="1" min="1" max="50"  data-value>
                            <button type="button" id="add" class="add" data-type="beds"  data-increase><img src="{{ URL::asset('assets/web/img/plus.png')}}" alt="Plus"></button>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Bedrooms</h4>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" class="sub" data-type="bedrooms" data-decrease><img src="{{ URL::asset('assets/web/img/minus.png')}}" alt="Minus"></button>
                            <input class="count" type="text" id="bedrooms" value="1" min="1" max="50"    data-value>
                            <button type="button" id="add" class="add" data-type="bedrooms" data-increase><img src="{{ URL::asset('assets/web/img/plus.png')}}" alt="Plus"></button>
                          </div>
                        </div>
                      </div>
                  

                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Bathrooms with shower</h4>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" class="sub" data-type="bathrooms_shower" data-decrease><img src="{{ URL::asset('assets/web/img/minus.png')}}" alt="Minus"></button>
                            <input class="count" type="text" id="bathrooms_shower" value="1" min="0" max="50"   data-value>
                            <button type="button" id="add" class="add"  data-type="bathrooms_shower" data-increase><img src="{{ URL::asset('assets/web/img/plus.png')}}" alt="Plus"></button>
                          </div>
                        </div>
                      </div>

                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Bathrooms with bathtub</h4>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" class="sub" data-type="bathrooms"  data-decrease><img src="{{ URL::asset('assets/web/img/minus.png')}}" alt="Minus"></button>
                            <input class="count" type="text" id="bathrooms" value="1" min="0" max="50" data-value>
                            <button type="button" id="add" class="add"  data-type="bathrooms" data-increase><img src="{{ URL::asset('assets/web/img/plus.png')}}" alt="Plus"></button>
                          </div>
                        </div>
                      </div>    


                      <div class="dropdown_cls">
                        <div class="dropdown_list">
                          <h4>Kitchens</h4>
                        </div>
                        <div class="guest_count">
                          <div class="wrap">
                            <button type="button" id="sub" class="sub"  data-type="kitchens"  data-decrease><img src="{{ URL::asset('assets/web/img/minus.png')}}" alt="Minus"></button>
                            <input class="count" type="text" id="kitchens" value="1" min="1" max="50"data-value >
                            <button type="button" id="add" class="add"  data-type="kitchens"  data-increase><img src="{{ URL::asset('assets/web/img/plus.png')}}" alt="Plus"></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_address') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn guest_next">Next</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>  
  </main>
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <!-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>  -->
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY"></script>

  <script>
    /*Add card Start */
    $(document).ready(function(){
      getGuest();
    })
    function getGuest(){
      if(sessionStorage.getItem("host_beds") != null && sessionStorage.getItem("host_beds") != 'undefined'){
        $("#beds").val(sessionStorage.getItem("host_beds"));
        $("#bedrooms").val(sessionStorage.getItem("host_bedrooms"));
        $("#bathrooms").val(sessionStorage.getItem("host_bathrooms"));
        $("#bathrooms_shower").val(sessionStorage.getItem("bathrooms_shower"));
        
        $("#kitchens").val(sessionStorage.getItem("host_kitchens"));
      }
    }

    $(document).on('click', ".guest_next", function(e) {
      var beds = $('#beds').val();
      var bedrooms = $('#bedrooms').val();
      var bathrooms = $('#bathrooms').val();
      var bathrooms_shower = $('#bathrooms_shower').val();
      
      var kitchens = $('#kitchens').val();

      if(beds != '' && bedrooms != '' && bathrooms != '' && bathrooms_shower !='' && kitchens != '' ){
          sessionStorage.setItem("host_beds", beds);
          sessionStorage.setItem("host_bedrooms", bedrooms);
          sessionStorage.setItem("host_bathrooms", bathrooms);
          sessionStorage.setItem("host_bathrooms_shower", bathrooms_shower);
                    
          sessionStorage.setItem("host_kitchens", kitchens);
          //window.location.replace("{{ route('web.become_a_host.host_amenity') }}");
          window.location.replace("{{ route('web.become_a_host.host_max_guest') }}");
      }else{
        alert('Please select all mandetory fields.');
        return false;
      }
    });

  $(function() {
    $('[data-decrease]').click(decrease);
    $('[data-increase]').click(increase);
    $('[data-value]').change(valueChange);
  });

  function decrease() {
    
    var vals=1;
    if($(this).data('type')=='bathrooms_shower'){
      vals=0;
    }

    if($(this).data('type')=='bathrooms'){
      vals=0;
    }

    var value = $(this).parent().find('[data-value]').val();
    if(value > vals) {
      value--;
      $(this).parent().find('[data-value]').val(value);
    }
  }

  function increase() {


    var value = $(this).parent().find('[data-value]').val();
    if(value < 100) {
      value++;
      $(this).parent().find('[data-value]').val(value);
    }
  }

  function valueChange() {
    var value = $(this).val();
    if(value == undefined || isNaN(value) == true || value <= 0) {
      $(this).val(1);
    } else if(value >= 101) {
      $(this).val(100);
    }
  }
  /*Add card End*/
  </script>
@endsection