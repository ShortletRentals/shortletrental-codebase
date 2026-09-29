@extends('layouts.web.master')

@section('content')
    <main>
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			@include('layouts.web.leftbar_itms')
	        			<div class="sidebar_r">
	        				<div class="inner-title">
	        					<h2 class="heading-inner-title">My Cards</h2>
	        				</div>

	        				<div class="my-card-sec">
	        					<div class="row">
	        						@if(isset($data) && $data->status == true)
		        						@foreach($data->data as $card_val)
			        						<div class="col-md-4">
			        							<div class="mycard-cls">
			        								<div class="card-title">
			        									<div class="card-left">
			        										<h5>Mastercard</h5>
			        									</div>
			        									<div class="card-right">
			        										<div class="card_option">
			        											<label class="custom_radio_b">
											                      <input type="radio" name="radio" onclick="makeDefault({{$card_val->id}})" {{$card_val->defalult_card == 1 ? 'checked' : ''}}>
											                      <span class="checkmark"></span>
											                    </label>
											                    <div class="remove-card">
											                    	<a href="javascript:void(0);" onclick="deletecard({{$card_val->id}})"><span><img src="{{ URL::asset('assets/web/img/delete.png')}}" alt="Delete"></span></a>
											                    </div>
											                    <div class="remove-card">
											                    	<a href="javascript:void(0)" class="edit_card" onclick="editCardDetails(this)" data-id="{{$card_val->id}}" data-card_number="{{$card_val->card_number}}" data-month="{{$card_val->month}}" data-year="{{$card_val->year}}" data-cvv="{{$card_val->cvv}}" data-card_holder_name="{{$card_val->card_holder_name}}" > <span><img src="{{ URL::asset('assets/web/img/edit.png')}}" alt="Edit"></span></a>
											                    </div>
			        										</div>
			        									</div>
			        								</div>
			        								<div class="card-number">
		    											<!-- <h2>0000 1111 2222 3333</h2> -->
		    											<h2>{{ trim(chunk_split($card_val->card_number, 4, ' ')) }}</h2>
		    										</div>
		    										<div class="card_valid">
		    											<div class="card_left_valid">
		    												<h5>Valid Thru</h5>
		    												<h3>{{ $card_val->month }}/{{ $card_val->year }}</h3>
		    											</div>
		    											<div class="card_right_valid">
		    												<span class="cvv-no">{{ $card_val->cvv }}</span>
		    											</div>
		    										</div>
		    										<div class="card_type_name">
		    											<div class="card-holder-name">
		    												<h4>{{ ucwords($card_val->card_holder_name) }}</h4>
		    											</div>
		    											<div class="card-type">
	    													<img src="{{ URL::asset('assets/web/img/mastercard_icon.png')}}" alt="">
		    											</div>
		    										</div>
			        							</div>
			        						</div>
		        						@endforeach
	        						@else
	        							<div class="col-md-4">No card founds.</div>
	        						@endif

	        					</div>
	        					<div class="add-new-card d-flex">
	        						<a href="#" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#AddCard">Add New Card</a>
	        					</div>
	        				</div>

	        			</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>

    <script type="text/javascript">

    	function editCardDetails($this) {
    		var card_id = $($this).data('id');
    		var card_number = $($this).data('card_number');
    		var month = $($this).data('month');
    		var year = $($this).data('year');
    		var cvv = $($this).data('cvv');
    		var card_holder_name = $($this).data('card_holder_name');

    		$('.card_id').val(card_id);
    		$('.card_holder_name').val(card_holder_name);
    		$('.card_number').val(card_number);
    		$('.month').val(month);
    		$('.year').val(year);
    		$('.cvv').val(cvv);

    		$('#EditCard').modal('show');
    	}

		function deletecard(card_id) {
			var formData = new FormData(); // Currently empty
		    var token = "{{ csrf_token() }}";
		    formData.append('_token', token);
		    formData.append('card_id', card_id);

			if (card_id) {

				if (confirm("Are you sure you want to delete this card?") == true) {
					$.ajax({
				        url: '{{ route("web.deleteCard") }}',
				        dataType: 'json',
				        data: formData,
				        type: 'POST',
				        cache: false,
				        contentType: false,
				        processData: false,
				        success: function(res) {

				          if (res.status === true) {
				            toastr.success(res.message);
				            window.location.reload();

				          } else {
				            toastr.error(res.message);
				          }
				        },
				        error: function(jqXHR, textStatus, textStatus) {
				          if (jqXHR.responseJSON.errors) {
				            $.each(jqXHR.responseJSON.errors, function(index, value) {
				              toastr.error(value)
				            });
				          } else {
				            toastr.error(jqXHR.responseJSON.message)
				          }
				        }
				    });
			    }
			} else {
				toastr.error('Invalid selection.')
			}
		}

		function makeDefault(card_id) {
			var formData = new FormData(); // Currently empty
		    var token = "{{ csrf_token() }}";
		    formData.append('_token', token);
		    formData.append('card_id', card_id);

			if (card_id) {

				if (confirm("Are you sure you want to change this card as default?") == true) {
					$.ajax({
				        url: '{{ route("web.defaultCard") }}',
				        dataType: 'json',
				        data: formData,
				        type: 'POST',
				        cache: false,
				        contentType: false,
				        processData: false,
				        success: function(res) {

				          if (res.status === true) {
				            toastr.success(res.message);
				            // window.location.reload();

				          } else {
				            toastr.error(res.message);
				          }
				        },
				        error: function(jqXHR, textStatus, textStatus) {
				          if (jqXHR.responseJSON.errors) {
				            $.each(jqXHR.responseJSON.errors, function(index, value) {
				              toastr.error(value)
				            });
				          } else {
				            toastr.error(jqXHR.responseJSON.message)
				          }
				        }
				    });
			    }
			} else {
				toastr.error('Invalid selection.')
			}
		}
    </script>
@endsection