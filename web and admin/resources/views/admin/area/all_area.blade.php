@if($allRecords)
<div class="form-group">
  <label for="inputName" class="form-label">Area</label>
  <select name="area" class="form-control area" id='area'>
    @if(!empty($allRecords))
      <option value="">Select Area</option>
      @foreach($allRecords as $key => $area)
        <option value="{{$area->id}}">{{$area->name}}</option>
      @endforeach
    @else
    @endif
  </select>
</div>
@endif
