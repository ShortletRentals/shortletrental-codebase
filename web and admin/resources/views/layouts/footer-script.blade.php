<?php
	use App\User;
	$login_user_data = auth()->user();
?>

<script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{ URL::asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('assets/web/js/lightbox-plus-jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/chartjs/chart.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-knob/excanvas.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-knob/jquery.knob.js')}}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery-ui.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
<script src="{{ asset('js/parsley.min.js') }}"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY"></script>

  <script>
    $(function() {
      $(".knob").knob();
    });
  </script>
<!--app JS-->
<script src="{{ URL::asset('assets/js/app.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $(".alert").delay(5000).slideUp(300);
  });
  // get address start
var geocoder;
  var map;
  var marker;
  var infowindow = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
    // initialize();
  // autoload(25.204849, 55.270783);


    var autocomplete;
    function initialize() {

    autocomplete = new google.maps.places.Autocomplete((document.getElementById('address')),{ types: [] });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
            // place variable will have all the information you are looking for.
            $('#latitude').val(place.geometry['location'].lat());
            $('#longitude').val(place.geometry['location'].lng());
      codeAddress();
    });
    }
  function autoload(latitude,longitude) {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    google.maps.event.addListener(map, 'click', function() {
      infowindow.close();
    });

    marker = new google.maps.Marker({
            map: map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: {lat:latitude, lng: longitude}
          });
          marker.addListener('click', toggleBounce);
  }

  function toggleBounce()
  {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
  function geocodePosition(pos) {
    geocoder.geocode({
      latLng: pos
    }, function(responses) {
      if (responses && responses.length > 0) {
        marker.formatted_address = responses[0].formatted_address;
      } else {
        marker.formatted_address = 'Cannot determine address at this location.';
      }
      $('#address').val(marker.formatted_address);
      $('#latitude').val(marker.getPosition().lat());
      $('#longitude').val(marker.getPosition().lng());
      infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
      infowindow.open(map, marker);
    });
  }

  function codeAddress() {
    var address = document.getElementById('address').value;
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        if (marker) {
          marker.setMap(null);
          if (infowindow) infowindow.close();
        }
        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: results[0].geometry.location
      });
      google.maps.event.addListener(marker, 'dragend', function() {
        geocodePosition(marker.getPosition());
      });
      google.maps.event.addListener(marker, 'click', function() {
        if (marker.formatted_address) {
          infowindow.setContent(marker.formatted_address + "<br>coordinates2: " + marker.getPosition().toUrlValue(6));
          $('#address').val(marker.formatted_address);
        } else {
          infowindow.setContent(address + "<br>coordinates3: " + marker.getPosition().toUrlValue(6));
          $('#address').val(address);
        }
        $('#latitude').val(marker.getPosition().lat());
        $('#longitude').val(marker.getPosition().lng());
        infowindow.open(map, marker);
      });
        google.maps.event.trigger(marker, 'click');
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }

// get address end  
  $(document).on('change','.statusAction',function(){
      var id = $(this).attr('id');
      var value = $(this).val();
      let statusMsg = ""
      if(value == '1') {
          statusMsg = 'Are you sure you want to active?';
      } else if(value == '2') {
          statusMsg = 'Are you sure you want to inactive?';
      }else if(value == '0') {
          statusMsg = 'Are you sure you want to inactive?';
      }
      if(window.confirm(statusMsg)) {
          var path = $(this).data('path');               
          $('.loader').show();
          $.ajax({
              url:path,
              method: 'get',
              data: {'id':id,'value':value},
              success: function(result){
              tables.ajax.reload();  
              sweetalert(result.type,result.message);
              $('.loader').hide();
              }
          });        
      }else{
          var oldValue = $(this).attr('data-value');
          $(this).val(oldValue);
          return false;
      }
  });
</script>