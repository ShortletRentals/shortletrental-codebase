@extends('layouts.web.master')

@section('content')
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>Price per night</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list property-price-sec">
                <div class="per-night-price">
                  <div class="form-group">
                    <input type="number" name="price" id="price" class="form-control" placeholder="Enter Price" /*pattern="[0-9\/]*"*/ min="1200" max="5000">
                    <!-- <input type="text" name="price" id="price" class="form-control" placeholder="Enter Price" /*pattern="[0-9\/]*"*/ min="1200" max="5000" onkeypress="return onlyNumberKey(event)"> -->
                    <span>per night</span>
                  </div>
                  <p class="suggest_price">keep in mind that places like yours range from  NGN 1200.00 - 5000.00</p>


                  <div class="form-group mt-3">
                    <label for="partyRate">What is your party rate</label>
                    <input type="number" name="party_rate_commission" id="party_rate_commission" class="form-control party_rate_commission" placeholder="Party rate">
                  </div>
                </div>

              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_extra_services') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn price_next">Next</a>
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
    function onlyNumberKey(evt) {  
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
        return true;
    }
    $(document).ready(function(){
      getPrice();
    })
    function getPrice(){
      if(sessionStorage.getItem("host_price") != null){
        localStorage.removeItem('host_price');
        $("#price").val(sessionStorage.getItem("host_price"));
        $("#party_rate_commission").val(sessionStorage.getItem("party_rate_commission"));
      }
    }

    $(document).on('click', ".price_next", function(e) {
      var price = $('#price').val();
      var party_rate_commission = $('#party_rate_commission').val();
      
      if(price != ''){
        sessionStorage.setItem("host_price", price);
        sessionStorage.setItem("party_rate_commission", party_rate_commission);
        window.location.replace("{{ route('web.become_a_host.host_images') }}");
      }else{
        alert('Please enter the price.');
      }
    });
  /*Add card End*/

  $(document).ready(function(){
    var country = sessionStorage.getItem("host_country_id");
    var province = sessionStorage.getItem("host_province_id");
    var city = sessionStorage.getItem("host_city_id");
    var area = sessionStorage.getItem("host_area");
    $.ajax({
      url:"{{ route('web.get_property_price') }}",
      method: 'post',
      dataType:'json',
      data: {_token: "{{ csrf_token() }}",'country':country,'province':province,'city':city,'area':area},
      success: function(res){
        if(res.status == true){
          $('.suggest_price').html('keep in mind that places like yours range from  NGN '+res.min_price+' - '+res.max_price);
          // toastr.success(res.message);
        }else{
          $('.suggest_price').html('');
          // toastr.error(res.message);
        }
      }
    });
  });
  </script>
@endsection