@if($allRecords)
<label for="inputName" class="form-label">Building/Urbanization*</label>
<select name="building" id="building" data-parsley-required="true" class="form-control" placeholder="Select Building/Urbanization" data-dropdown-css-class="select2-primary">
  <option value="">--Select Building/Urbanization--</option>
  @if(count($allRecords) > 0)
  @foreach($allRecords as $building)
    <option value="{{$building->id}}" >{{ $building->name }}</option>
  @endforeach
  @endif
</select>
@endif