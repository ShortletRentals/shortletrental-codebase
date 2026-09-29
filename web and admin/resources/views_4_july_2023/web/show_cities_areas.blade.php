<select name="area_id" class="form-control area_id" id='area_id'>
  @if(!empty($records))
      <option value="">Select</option>
      @foreach($records as $key => $area)
          <option value="{{$area->id}}" {{$area->id == $area_id ? 'selected' : ''}} >{{$area->city_name}}/{{$area->name}}</option>
      @endforeach
  @else
  @endif
</select>