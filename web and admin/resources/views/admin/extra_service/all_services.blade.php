@if($allRecords)
<label for="inputExtraServices" class="form-label">Select Extra Services</label>
<select name="property_extra_services[]" id="property_extra_services" class="form-control property_extra_services extra_select2" data-placeholder="Select Extra Services" multiple>
  @if(count($allRecords) > 0)
  @foreach($allRecords as $service)
    <option value="{{$service->id}}" >{{$service->name}}</option>
  @endforeach
  @endif
</select>
@endif
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>
	$('.extra_select2').select2();
</script>