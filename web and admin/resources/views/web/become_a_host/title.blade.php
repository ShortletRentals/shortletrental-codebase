@extends('layouts.web.master')

@section('content')
<?php ///Page 5 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>Create Accommodation Title</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                <div class="create_title_cls">
                  <textarea id="title" name="title" rows="7" class="form-control" placeholder="Enter Accommodation Title"></textarea>
                </div>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_max_guest') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn title_next">Next</a>
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
      if(sessionStorage.getItem("host_title") != null){
        $("#title").val(sessionStorage.getItem("host_title"));
      }
    }

    $(document).on('click', ".title_next", function(e) {
      var title = $('#title').val();

      if(title != ''){
          sessionStorage.setItem("host_title", title);
          window.location.replace("{{ route('web.become_a_host.host_description') }}");
      }else{
        alert('Please enter accommodation title.');
        return false;
      }
    });

  $(function() {
    $('[data-decrease]').click(decrease);
    $('[data-increase]').click(increase);
    $('[data-value]').change(valueChange);
  });

  function decrease() {
    var value = $(this).parent().find('[data-value]').val();
    if(value > 1) {
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