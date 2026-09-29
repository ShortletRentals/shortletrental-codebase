@if($records)
<div class="form-group province_div">
  <label for="inputName" class="form-label">Province*</label>

  <select name="province_id" class="form-control province_id_area" onchange="getCityData(this)" id="province_id_area" required>
      @if(!empty($records))
        <option value="">Select province</option>
        @foreach($records as $key => $province)
            <option value="{{$province->id}}" >{{$province->name}}</option>
        @endforeach
      @else
      @endif
  </select>

</div>
@endif

<script>

  function getCityData($this) {
    var country_id = $('.country_id_area').val();
    var province_id = $($this).val();
    // var province_id = $('#province_id_area option:selected').val();
    var city_id = "";

    if (province_id) {
      $.ajax({
          url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
          dataType: 'html',
          success:function(result)
          {
              $('.show_cityDiv').html(result);
          }
      });
    }
  }
  // $(document).on('change', '.province_id_area',function(){
  //   var country_id = $('#country_id_area').val();
  //   var province_id = $('#province_id_area').val();
  //   var city_id = "";
  //   alert(country_id);
  //   alert(province_id);
  //   if (province_id) {
  //     $.ajax({
  //         url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
  //         dataType: 'html',
  //         success:function(result)
  //         {
  //             $('.show_cityDiv').html(result);
  //         }
  //     });
  //   }
  // })

	// function getCity1() {
	// 	var country_id = $('#country_id_area').val();
	// 	var province_id = $('#province_id_area').val();
	// 	var city_id = "<?php if (isset($data) && $data->city_id) { echo $data->city_id; } ?>";

	// 	if (province_id) {
	// 		$.ajax({
	// 			url:'{{url("admin/area/show_city")}}/'+country_id+'/'+province_id+'/'+city_id,
	// 			dataType: 'html',
	// 			success:function(result)
	// 			{
	// 				$('.show_cityDiv').html(result);
	// 			}
	// 		});
	// 	}
	// }
</script>