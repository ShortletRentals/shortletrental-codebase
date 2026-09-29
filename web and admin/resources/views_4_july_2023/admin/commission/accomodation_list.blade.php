<label for="inputPropertyId" class="form-label">Select Accomodation</label>
<?php  $errorClass =  !empty($errors->has('property_id')) ? 'is-invalid':''; ?>
<select name="property_id[]" class="form-control property_id_onload {{$errorClass}}" id='property_id_onload' multiple>
    @if(!empty($properties))
        @foreach($properties as $property)
            <option value="{{$property->id}}" <?php if(isset($selected_commission_properties)){ if(in_array($property->id,$selected_commission_properties)){ echo 'selected'; } } ?> class="others">{{$property->title}}</option>
        @endforeach
    @else
    @endif
</select>
@error('property_id')
    <span class="invalid-feedback" role="alert">
    <label>{{ $message }}</label>
    </span>
@enderror
{!! !empty($errors->has('property_id')) ?'<div class="invalid-feedback"><span>'.$errors->first('property_id').'</span></div>' :'' !!}

<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script type="text/javascript">
	$('.property_id_onload').select2();
</script>