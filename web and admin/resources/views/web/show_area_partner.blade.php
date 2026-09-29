@if($records)
  <label for="inputName" class="form-label">Area</label>
  <?php  $errorClass =  !empty($errors->has('area_id')) ? 'is-invalid':''; ?>

  <select name="area" class="form-control area {{$errorClass}}" id='area_partner'>
      @if(!empty($records))
          <option value="">Select Area</option>
          @foreach($records as $key => $area)
              <option value="{{$area->id}}" <?php echo $area_id == $area->id  ? 'selected' : '' ?>>{{$area->name}}</option>
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
  {!! !empty($errors->has('city_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('city_id').'</span></div>' :'' !!}
@endif