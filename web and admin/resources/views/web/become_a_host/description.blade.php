@extends('layouts.web.master')

@section('content')
<?php ///Page 6 ?>
  <main class="host-property-main">
    <section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
              <h3>What is the description of the accommodation? </h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <div class="property-list">
                  <div class="create_title_cls">
                      <textarea id="description" name="description" rows="7" class="form-control" placeholder="Create your description"></textarea>
                  </div>
              </div>
              <div class="property-footer">
                <div class="btn_group">
                  <a href="{{ route('web.become_a_host.host_title') }}" class="btn secondary_btn">Back</a>
                  <a href="javascript:void(0)" class="btn primary_btn description_next">Next</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>  
  </main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.18.0/ckeditor.js" integrity="sha512-woYV6V3QV/oH8txWu19WqPPEtGu+dXM87N9YXP6ocsbCAH1Au9WDZ15cnk62n6/tVOmOo0rIYwx05raKdA4qyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function(){
	CKEDITOR.replace('description',{

	});
});
</script>
  <script>
    /*Add card Start */
    $(document).ready(function(){
      getGuest();
    })
    function getGuest(){
      // alert(sessionStorage.getItem("host_description"));
      if(sessionStorage.getItem("host_description") != null){
        $("#description").html(sessionStorage.getItem("host_description"));
      }
    }

    $(document).on('click', ".description_next", function(e) {
      // alert($('#description').html());
      // var description = $('#description').val();
      var description = CKEDITOR.instances['description'].getData(); 
      // alert(description+' description');

      // if(description != ''){
          sessionStorage.setItem("host_description", description);
          window.location.replace("{{ route('web.become_a_host.host_extra_services') }}");
      // }else{
      //   alert('Please enter description.');
      //   return false;
      // }
    });

  /*Add card End*/
  </script>
@endsection