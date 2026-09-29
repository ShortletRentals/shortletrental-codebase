@extends('layouts.web.master')

@section('content')
<?php ///Page 7 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>Do you have any of these at your place?</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                <div class="multi-select">

                @if(count($extra_services) > 0)
                  @foreach($extra_services as $service)
                  <label class="custom_checkbox">
                    <input type="checkbox" name="extra_service" class="extra_service" value="{{$service->id}}">
                    <span class="checkmark"></span>
                    <div class="property-bx">
                      <div class="pr-icon">
                        <img src="{{ $service->image }}" alt="{{$service->name}}">
                      </div>
                      <div class="pr-name">
                        <h5>{{$service->name}}</h5>
                      </div>
                    </div>
                  </label>
                  @endforeach
                @endif
                </div>
                <div class="pro_cont">
                  <h3>Some important things to know</h3>
                  <div class="footer-link">
                    <ul class="mb-0">
                      <li>
                        <a href="{{ route('web.privacy-policy') }}" target="_blank">Privacy policy</a>
                      </li>
                      <li>
                        <a href="{{ route('web.help-center') }}" target="_blank">Help Center</a>
                      </li>
                      <li>
                        <a href="{{ route('web.cancellation-policy') }}" target="_blank">Cancellation Policy</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_description') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn service_next">Next</a>
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
    /*Add card Start */
    $(document).ready(function(){
      getService();
    })
    function getService(){
      // if(sessionStorage.getItem("host_beds") != null){
      //   $("#beds").val(sessionStorage.getItem("host_beds"));
      // }

      if(sessionStorage.getItem("host_service") != null){
        // $("#beds").val(sessionStorage.getItem("host_beds"));
      }
    }

    var extra_service = [];
    $(document).on('click', ".service_next", function(e) {
      var i_count = 1;
      if($('input[type=checkbox]:checked').length >= 1){
        $('[name="extra_service"]').each( function (i, data){
          if($(this).prop('checked') == true){
            extra_service[i_count] = $(this).val();
            i_count++;
          }
        });
      }
      // alert(extra_service);
      if(extra_service != ''){
        sessionStorage.setItem("host_services", extra_service);
        window.location.replace("{{ route('web.become_a_host.host_price') }}");
      }else{
        sessionStorage.setItem("host_services", '');
        window.location.replace("{{ route('web.become_a_host.host_price') }}");
        // alert('Please select atleast one service.');
        // return false;
      }

      // alert(amenity);
      // if(extra_service != ''){
      //     sessionStorage.setItem("host_services", extra_service);
      //     window.location.replace("{{ route('web.become_a_host.host_price') }}");
      // }else{
      //   alert('Please select atleast one service.');
      //   return false;
      // }
    });
  /*Add card End*/
  </script>
@endsection