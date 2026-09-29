@extends('layouts.web.master')

@section('content')
<?php ///Page 2 ?>
  <main class="host-property-main">
  	<section class="property-sec">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 grad-bg">
            <h3>Select all that applies</h3>
          </div>
          <div class="col-md-6">
            <div class="property-cont">
              <!-- <form method="POST" action="" class="login-form row digit-group" enctype="" id="become_a_host_type">
                @csrf -->
                <div class="property-list">
                  @if(count($all_categories) > 0)
                    @foreach($all_categories as $category)
                    @if($category->name != 'Luxury Homes')
                      <label class="custom_radio_b">
                        <input type="checkbox" name="category" value="{{$category->id}}">
                        <span class="checkmark"></span>
                        <div class="property-inner">
                        <h3>{{$category->name}}</h3>
                        <div class="property-img">
                          <img src="{{$category->image}}" alt="Property">
                        </div>
                        </div>
                      </label>
                    @endif
                    @endforeach
                    @else
                    <div style="text-align:center;">No category found. Please select another type.</div>
                  @endif
                </div>
                <div class="property-footer">
                  <div class="btn_group">
                    <a href="{{ route('web.become_a_host.host_type') }}" class="btn secondary_btn">Back</a>
                    <a href="javascript:void(0)" class="btn primary_btn category_next">Next</a>
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
      getCategory();
    })
    function getCategory(){
      if(sessionStorage.getItem("host_category") != null){
        $("input[name=category][value='"+sessionStorage.getItem("host_category")+"']").prop("checked",true);
      }
    }
    var checked = [];
    $(document).on('click', ".category_next", function(e) {
      var i_count = 1;
      $('[name="category"]').each( function (i, data){
        if($(this).prop('checked') == true){
          checked[i_count] = $(this).val();
          i_count++;
        }
      });
      // alert($.type(checked));
      if(checked != ''){
        sessionStorage.setItem("host_category", checked);
        window.location.replace("{{ route('web.become_a_host.host_address') }}");
      }else{
        alert('Please select atleast one category.');
        return false;
      }
    });
    // $(document).on('click', ".category_next", function(e) {
    //   var checked = $('input[name="category"]:checked').val();
    //   if(checked != undefined){
    //     // alert(checked);
    //     sessionStorage.setItem("host_category", checked);
    //     window.location.replace("{{ route('web.become_a_host.host_address') }}");
    //   }else{
    //     alert('Please select any one category.');
    //     return false;
    //   }
    // });

  </script>
@endsection