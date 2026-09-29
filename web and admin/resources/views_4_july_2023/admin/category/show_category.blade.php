@if($records)
<div class="form-group">
  <label for="inputName" class="form-label">Category*</label>
  <select name="category[]" id="category" class="form-control category_id" data-placeholder="Select Category" data-dropdown-css-class="select2-primary" data-parsley-required="true" multiple>
      @if(!empty($records))
        @foreach($records as $key => $province)
          <option value="{{$province->id}}" <?php if(in_array($province->id, $category_id)){ echo 'selected'; } ?>>{{$province->name}}</option>
        @endforeach
      @else
      @endif
  </select>
</div>
@endif

<script>
  $(document).ready(function(){
    $('.category_id').select2();
  })

</script>