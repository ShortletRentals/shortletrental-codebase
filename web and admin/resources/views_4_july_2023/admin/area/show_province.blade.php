@if($records)
<div class="form-group">
  <label for="inputName" class="form-label">Province*</label>
  <select name="province_id" class="form-control province_id" onchange="getCity()" id='province_id' required>
      @if(!empty($records))
        <option value="">Select province</option>
        @foreach($records as $key => $province)
            <option value="{{$province->id}}" <?php echo $province_id == $province->id  ? 'selected' : '' ?>>{{$province->name}}</option>
        @endforeach
      @else
      @endif
  </select>
</div>
@endif