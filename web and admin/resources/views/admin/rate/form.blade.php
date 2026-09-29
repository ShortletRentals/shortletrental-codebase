@section('css') 
	<link href="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
@endsection
<div class="card">
  	<div class="card-body p-4">
	  	<!-- <h5 class="card-title">Add New</h5>
	  	<hr/> -->
		<input type="hidden" name="rate_id" id="rate_id" value="{{ isset($data) && !empty($data->id) ? $data->id : '' }}">
       	<div class="form-body">
		    <div class="row">
			   	<div class="col-lg-12">
		           	<div class="margin-cls border border-3 p-4 rounded row padding-bottom">
					   <h4 class="dataLabel">GENERAL DATA</h4>
					   <div class="col-md-12 mb-3 properties-cls">
							<input type="checkbox" name="all_properties" id="all_properties" value="Yes" <?php if(isset($data->all_properties) && $data->all_properties == 'Yes'){ echo 'checked'; } ?>>
							<label for="all_properties" class="form-label">All Accomodation</label>
						</div>
						<div class="col-md-6 mb-3 select_acco_div">
							<label for="inputPropertyId" class="form-label">Select Accomodation</label>
							<?php  $errorClass =  !empty($errors->has('property_id')) ? 'is-invalid':''; ?>
			                <select name="property_id" class="form-control property_id {{$errorClass}}" id='property_id'>
								<option value="">Select Accommodation</option>
			                    @if(!empty($properties))
				                    @foreach($properties as $property)
				                        <option value="{{$property->id}}" <?php if(isset($data->property_id) && !empty($data->property_id)){ if($property->id == $data->property_id){ echo 'selected';  } } ?>>{{$property->title.' '.' ( '.$property->code.' )'}}</option>
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
						</div>
						
						<div class="col-md-6 mb-3">
				            <div class="form-group">
			                  <label for="discount_code" class="form-label">Start Date*</label>
			                  <input type="text" name="start_date" id="valid_from" data-parsley-required="true" class="form-control start_date" value="{{ isset($data->start_date) ? date('Y-m-d', strtotime($data->start_date)) : ''  }}" placeholder="From Date*" readonly />
				            </div>
				        </div>
				        <div class="col-md-6 mb-3">
				            <div class="form-group">
				              <label for="discount_code" class="form-label">Last Night*</label>
				              <input type="text" name="end_date" id="valid_upto" data-parsley-required="true" class="form-control end_date" value="{{ isset($data->end_date) ? date('Y-m-d', strtotime($data->end_date)) : ''  }}" placeholder="To Date*" readonly />
				            </div>
				        </div>
				        <div class="col-md-6 mb-3">
				            <div class="form-group">
				              <label for="total_use" class="form-label">Price(NGN)*</label>
				              <input type="text" name="price" id="price" value="{{ isset($data->price) ? $data->price : ''  }}" required='required' min=1 onkeypress='return onlyNumberKey(event)' class="form-control" placeholder="Enter Accommodation Price(NGN)*"/>
				            </div>
				        </div>
						
			            <div class="col-md-12">
						    <div class="d-flex">
								<a href="{{ route('admin.rate.index') }}" class="btn btn-light">Cancel</a>
								@if(isset($data) && !empty($data->id))
							   		<button type="submit" class="btn btn-light ms-auto">Update</button>
							   	@else
									<button type="submit" class="btn btn-light ms-auto">Save</button>
								@endif
						    </div>		  
			            </div>
		            </div>
			   	</div>
			</div>
	   </div><!--end row-->
	</div>
</div>
<script src="{{ URL::asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>

	$(".rate_form").on('submit',function(e){
		e.preventDefault();
		var _this=$(this); 
		var id = $('#rate_id').val();
		// alert(id);
		var formData = new FormData(this);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		if(id != ''){
			var url = '{{ url("admin/rate/update?id=") }}'+id;
		}else{
			var url = '{{ url("admin/rate/store") }}';
		}
		// alert(url);
        $.ajax({
			url:url,
			dataType:'json',
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			type:'POST',
			// beforeSend: function (){before(_this)},
			// hides the loader after completion of request, whether successfull or failor.
			// complete: function (){complete(_this)},
			success:function(result){
				console.log('esult.status'+result.status);
				if(result.status == true){
					toastr.success(result.message);
					setTimeout(function(){
						window.location.replace("{{ route('admin.rate.index') }}");
					}, 1000);
				}else{
					toastr.error(result.message);
				}
          	},
			error:function(jqXHR,textStatus,textStatus){
				if(jqXHR.responseJSON.errors){
					$.each(jqXHR.responseJSON.errors, function( index, value ) {
					toastr.error(value)
					});
				}else{
					toastr.error(jqXHR.responseJSON.message)
				}
			}
        });
        return false;   
    });

	function onlyNumberKey(evt) {  
      // Only ASCII character in that range allowed
      var ASCIICode = (evt.which) ? evt.which : evt.keyCode
      if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
          return false;
      return true;
    }

	$('.property_id').select2();
	function enable_cb() {
		var val = "{{ isset($data) && !empty($data->all_properties) ? $data->all_properties : '' }}";
		if (val == 'Yes') {
			$('.select_acco_div').css('display','none');
		} else {
			$('.select_acco_div').css('display','block');
		}
	}

	$(document).ready(function () {
		enable_cb();
		$('#all_properties').on('click', function(){
			if($(this).is(':checked')) {
				// $('.select_acco_div').css('display','none');
				$('#property_id').prop('disabled','disabled');
				// $('#property_id').css('data-parsley-required','false');
				// document.getElementById("property_id").required = false;
			}else{
				$('#property_id').prop('disabled',false);
				// $('.select_acco_div').css('display','block');
				// $('#property_id').css('data-parsley-required','true');
				// document.getElementById("property_id").required = true;
			}
		});

		$(".start_date").datepicker({
            minDate: "-0D",
            numberOfMonths: 1,
            dateFormat:'yy-mm-dd',
            onSelect: function(selected) {
               $(".end_date").datepicker("option","minDate", selected)
            }
        });
        $(".end_date").datepicker({
            minDate:"-0D",
            numberOfMonths: 1,
            dateFormat:'yy-mm-dd',
            onSelect: function(selected) {
                $(".start_date").datepicker("option","maxDate", selected)
            }
        });

	})
</script>