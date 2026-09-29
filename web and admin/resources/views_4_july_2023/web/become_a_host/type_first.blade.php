@extends('layouts.web.master')

@section('content')
<?php ///Page 1 ?>
  <main class="host-property-main">
  	<section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
            <h3>What kind of space will guest have?</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <!-- <form method="POST" action="" class="login-form row digit-group" enctype="" id="become_a_host_type">
                @csrf -->
                <div class="property-list">
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Aparthotel">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                    <h3>Aparthotel</h3>
                    <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-1.png')}}" alt="Property">
                    </div>
                    </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Apartment">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Apartment</h3>
                      <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-2.png')}}" alt="Property">
                      </div>
                    </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Boat">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Boat</h3>
                      <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-3.png')}}" alt="Property">
                      </div>
                    </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Bungalow">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Bungalow</h3>
                      <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-4.png')}}" alt="Property">
                      </div>
                    </div>
                  </label>
                  <!-- <label class="custom_radio_b">
                    <input type="radio" name="type" value="Chalet">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Chalet</h3>
                      <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-5.png')}}" alt="Property">
                      </div>
                    </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Cottage">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Cottage</h3>
                      <div class="property-img">
                      <img src="{{ URL::asset('assets/web/img/property-5.png')}}" alt="Property">
                      </div>
                    </div>
                  </label> -->
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Country_house">
                    <span class="checkmark"></span>
                    <div class="property-inner">
                      <h3>Country house</h3>
                      <div class="property-img">
                        <img src="{{ URL::asset('assets/web/img/property-7.png')}}" alt="Property">
                      </div>
                    </div>
                  </label>
                  <!-- <label class="custom_radio_b">
                    <input type="radio" name="type" value="Farm_stay">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Farm stay</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-8.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Garage">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Garage</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-9.png')}}" alt="Property">
                        </div>
                      </div>
                  </label> -->
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="House">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>House</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-10.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                  <!-- <label class="custom_radio_b">
                    <input type="radio" name="type" value="Mobile_home">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Mobile home</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-11.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Rent_by_room">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Rent by room</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>

                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Residence">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Residence</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label> -->
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Studio">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Studio</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Townhouse">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Townhouse</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                  <!-- <label class="custom_radio_b">
                    <input type="radio" name="type" value="Trullo">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Trullo</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label> -->
                  <label class="custom_radio_b">
                    <input type="radio" name="type" value="Villa">
                    <span class="checkmark"></span>
                      <div class="property-inner">
                        <h3>Villa</h3>
                        <div class="property-img">
                          <img src="{{ URL::asset('assets/web/img/property-12.png')}}" alt="Property">
                        </div>
                      </div>
                  </label>
                </div>
                <div class="property-footer">
                  <div class="btn_group">
                    <a href="{{ route('web.become_a_host') }}" class="btn secondary_btn">Back</a>
                    <a href="javascript:void(0)" class="btn primary_btn type_next">Next</a>
                    <!-- <input type="submit" name="" class="btn primary_btn" value="Next"> -->
                  </div>
                </div>
              <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
	  </section>  
  </main>
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script>
    /*Add card Start */
    // $('#become_a_host_type').parsley();

    $(document).ready(function(){
      getType();
    })
    function getType(){
      // alert(sessionStorage.getItem("host_type"));
      localStorage.removeItem("host_type");
      // localStorage.clear();
      if(sessionStorage.getItem("host_type") != null){
        $("input[name=type][value='"+sessionStorage.getItem("host_type")+"']").prop("checked",true);
      }
    }
    $(document).on('click', ".type_next", function(e) {
      var type = $('input[name="type"]:checked').val();
      if(type != undefined){
        // alert(checked);
        // alert(sessionStorage.getItem("host_type"));
        // if(sessionStorage.getItem("host_type") == null){
          // alert('inn');
          sessionStorage.setItem("host_type", type);
          // alert(type);
          window.location.replace("{{ url('host_category') }}"+'/'+type);
        // }
        // $.ajax({
        //   // url: '{{ url("web/become_a_host_address/") }}'+checked,
        //   url: '{{ route("web.become_a_host.host_address") }}',
        //   // dataType: 'json',
        //   data: {'type':checked},
        //   type: 'POST',
        //   // cache: false,
        //   // contentType: false,
        //   // processData: false,
        //   success: function(res) {
        //     console.log('--status'+res.message);
        //     if (res.status === true) {
        //       // toastr.success(res.message);
        //       // $('#AddCard').modal('hide');
        //       setInterval(function () {  
        //         window.location.replace("{{ route('web.become_a_host.host_address') }}");
        //       }, 2000); 
        //     } else {
        //       toastr.error(res.message);
        //     }
        //   },
        //   error: function(jqXHR, textStatus, textStatus) {
        //     if (jqXHR.responseJSON.errors) {
        //       $.each(jqXHR.responseJSON.errors, function(index, value) {
        //         toastr.error(value)
        //       });
        //     } else {
        //       toastr.error(jqXHR.responseJSON.message)
        //     }
        //   }
        // });
      }else{
        alert('Please select any one type.');
        return false;
      }
    });

    // $(document).on('submit', "#become_a_host_type", function(e) {
    //   e.preventDefault();
    //   var _this = $(this);
    //   $('#group_loader').fadeIn();
    //   var formData = new FormData(this);
    //   $.ajax({
    //     url: '{{ route("web.store_become_a_host_type") }}',
    //     dataType: 'json',
    //     data: formData,
    //     type: 'POST',
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     success: function(res) {
    //       console.log('--status'+res.message);
    //       if (res.status === true) {
    //         // toastr.success(res.message);
    //         // $('#AddCard').modal('hide');
    //         setInterval(function () {  
    //           window.location.replace("{{ route('web.become_a_host.host_address') }}");
    //         }, 2000); 
    //       } else {
    //         toastr.error(res.message);
    //       }
    //     },
    //     error: function(jqXHR, textStatus, textStatus) {
    //       if (jqXHR.responseJSON.errors) {
    //         $.each(jqXHR.responseJSON.errors, function(index, value) {
    //           toastr.error(value)
    //         });
    //       } else {
    //         toastr.error(jqXHR.responseJSON.message)
    //       }
    //     }
    //   });
    //   return false;
    // });
  /*Add card End*/

  </script>
@endsection