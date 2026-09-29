@if($allRecords)
<div class="form-group">
  <label for="inputName" class="form-label">City*</label>
  <select name="city_id" class="form-control city_id" id='city_id' required>
    @if(!empty($allRecords))
      <option value="">Select city</option>
      @foreach($allRecords as $key => $city)
        <option value="{{$city->id}}" <?php echo $city_id == $city->id  ? 'selected' : '' ?>>{{$city->name}}</option>
      @endforeach
    @else
    @endif
  </select>
</div>
@endif