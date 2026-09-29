@if($allRecords)
<label for="inputAmenities" class="form-label">Select Amenities*</label>
<select name="property_amenities[]" id="property_amenities" data-parsley-required="true" class="form-control property_amenities amenity_select2" data-placeholder="Select Amenities"  multiple>
  @if(count($allRecords) > 0)
  @foreach($allRecords as $amenity)
    <option value="{{$amenity->id}}">{{$amenity->name}}</option>
  @endforeach
  @endif
</select>
@endif
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
	$('.amenity_select2').select2();
</script>