@if($records)
  <label for="inputName" class="form-label">City</label>
  <?php  $errorClass =  !empty($errors->has('city_id')) ? 'is-invalid':''; ?>

  <select name="city_id" class="form-control city_id1 {{$errorClass}}" id='city_id1' onchange="getAreaData(this)">
      @if(!empty($records))
          <option value="">Select city</option>
          @foreach($records as $key => $city)
              <option value="{{$city->id}}" <?php echo $city_id == $city->id  ? 'selected' : '' ?>>{{$city->name}}</option>
          @endforeach
      @else
      @endif
  </select>
  @error('country_id')
      <span class="invalid-feedback" role="alert">
      <label>{{ $message }}</label>
      </span>
  @enderror


  {!! !empty($errors->has('province_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('province_id').'</span></div>' :'' !!}
@endif
<script>
  function getAreaData($this) {
    var country_id = $('.country_id').val();
    var province_id = $('.province_id1').val();
    var city_id = $($this).val();

    if (province_id) {
      $.ajax({
        url:'{{url("area/show_area")}}/'+country_id+'/'+province_id+'/'+city_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_areaDiv').html(result);
        }
      });
    }
  }
</script>