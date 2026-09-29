@if($allRecords)
<div class="form-group">
  <label for="inputName" class="form-label">Province*</label>
  <select name="province_id" class="form-control province_id" onchange="getCity()" id='province_id' required>
      @if(!empty($allRecords))
        <option value="">Select province</option>
        @foreach($allRecords as $key => $province)
            <option value="{{$province->id}}" >{{$province->name}}</option>
        @endforeach
      @else
      @endif
  </select>
</div>
@endif