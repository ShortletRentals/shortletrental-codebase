@if($records)
<div class="form-group">
  <label for="inputName" class="form-label">City*</label>
  <?php  $errorClass =  !empty($errors->has('city_id')) ? 'is-invalid':''; ?>

  <select name="city_id" class="form-control city_id {{$errorClass}}" id='city_id' required>
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
</div>
@endif