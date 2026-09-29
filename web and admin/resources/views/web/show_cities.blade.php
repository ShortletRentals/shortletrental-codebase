<select name="city_id" class="form-control city_id" id='city_id'>
  @if(!empty($records))
      <option value="">Select</option>
      @foreach($records as $key => $city)
          <option value="{{$city->id}}" {{$city->id == $city_id ? 'selected' : ''}} >{{$city->name}}</option>
      @endforeach
  @else
  @endif
</select>