@if($records)
<div class="">
  <label for="inputName" class="form-label">Province</label>
  <?php  $errorClass =  !empty($errors->has('province_id')) ? 'is-invalid':''; ?>

  <select name="province_id" class="form-control province_id1 {{$errorClass}}" id='province_id1' onchange="getCityData(this)">
      @if(!empty($records))
        <option value="">Select province</option>
        @foreach($records as $key => $province)
            <option value="{{$province->id}}" <?php echo $province_id == $province->id  ? 'selected' : '' ?>>{{$province->name}}</option>
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
</div>
@endif
<script>
  function getCityData($this) {
    var country_id = $('.country_id_area').val();
    if(country_id=='')
    {
      var country_id = $('.country_id').val();
      
    }

    
    var province_id = $($this).val();
    var city_id = "";

    if (province_id) {
      $.ajax({
        url:'{{url("area/show_city_befour")}}/'+country_id+'/'+province_id+'/'+city_id,
        dataType: 'html',
        success:function(result)
        {
          $('.show_cityDiv').html(result);
        }
      });
    }
  }
</script>