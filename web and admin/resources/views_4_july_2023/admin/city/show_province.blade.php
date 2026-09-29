@if($records)
<div class="form-group">
  <label for="inputName" class="form-label">Province</label>
  <?php  $errorClass =  !empty($errors->has('province_id')) ? 'is-invalid':''; ?>

  <select name="province_id" class="form-control province_id {{$errorClass}}" id='province_id' required>
      @if(!empty($records))
        <option value="">Select</option>
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