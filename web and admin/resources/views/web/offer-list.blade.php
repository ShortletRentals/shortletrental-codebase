@extends('layouts.web.master')
@section('content')
<main>
	<section class="special-offer space-cls">
        <div class="container">
	        <div class="inner_title">
	            <div class="title-left">
	              	<h3>Special Offers</h3>
	            </div>
	        </div>
          	<div class="offer_listing">
				<div class="row">
					<?php foreach ($offers as $key => $value) { ?>
					<div class="col-md-3">
		                <div class="offer-list">
			                <div class="offer-left">
			                    <div class="offer_img">
			                        <img src="{{ $value->image }}" alt="">
			                    </div>
			                </div>
			                <div class="offer-right">
			                    <div class="offer-cont">
			                      	<h3>#{{ $value->getDiscountData->code  ?? ''}}</h3>
									<?php $acc_title = ''; ?>
									@if (count($value->getOfferAccommodation))
										<h5>{{$value->getOfferAccommodation[0]->getProperty->title}}</h5>
										<?php $acc_title = $value->getOfferAccommodation[0]->getProperty->title; ?>
									@endif
									<h6>{{$value->title}}</h6>
									@if (strlen($value->description) >= 50)
										<p>{{ substr($value->description, 0, 35). " ... "}}</p>
									@else
										<p>{{ $value->description }}</p>
									@endif
			                    </div>
								<div class="view-offer">
									<a href="#" class="offer-btn offer_detail" data-bs-toggle="modal" data-id="{{ $value->id }}" data-code="{{ $value->getDiscountData->code ?? '' }}" data-title="{{ $value->title }}" data-acc_title="{{ $acc_title }}" data-description="{{ $value->description }}" data-image="{{ $value->image }}" data-bs-target="#ViewOffer">View Details</a>
								</div>
			                </div>
		                </div>
	      			</div>
            		<?php } ?>
	  			</div>
       		</div>
       	</div>
    </section>  		
</main>
  <div class="button-fix">
    <a href="#" class="message-right">Message</a>
  </div>
  	<div class="modal_main_cls">
	  	<div class="modal fade login-sec" id="ViewOffer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog">
	        <div class="modal-dialog modal-dialog-centered">
	          	<div class="modal-content">
	            	<div class="modal-header">
		              	<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
		                	<span aria-hidden="true">×</span>
		              	</button>
	            	</div>
		            <div class="modal-body">
	            		<div class="offerDtl">
	            			<div class="offerImg">
	            				<div class="offer_img offer_img_on_modal">
			                        <img src="http://localhost/shortletrental/uploads/offer/DEC2022/1670491298-offer.png" alt="">
			                    </div>
	            			</div>
	            			<div class="offerCont">
	            				<div class="offer-cont offer-cont-modal">
			                      	<h3>#FLAT15%</h3>
			                      	<h6>Test</h6>
			                        <p>Lorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text ofLorem Ipsum is simply dummy text of</p>
			                        <div class="view-offer">
				                        <span href="#" class="offer-code">lglv546</span>
			    	                </div>
			                    </div>
	            			</div>
	            		</div>
		            </div>
	          </div>
	        </div>
	    </div>
	</div>
<script>
	$('.offer_detail').on('click', function(){
		var id = $(this).data('id');
		var code = $(this).data('code');
		var title = $(this).data('title');
		var acc_title = $(this).data('acc_title');
		var description = $(this).data('description');
		var image = $(this).data('image');
		
		$('.offer_img_on_modal').html('<img src="'+image+'" alt="">');
		$('.offer-cont-modal').html('<h3 class="code_class" id="code_class">#'+code+'</h3><h5>'+acc_title+'</h5><h6>'+title+'</h6><p>'+description+'</p>');
		// $('.offer-cont').html('<h3 class="code_class" id="code_class">#'+code+'</h3><h5>'+acc_title+'</h5><h6>'+title+'</h6>'+description+'<div class="view-offer"><button class="offer-code" onclick="myFunction()">'+code+'</button></div>');
	});

	function myFunction() {
		// Get the text field
		var copyText = document.getElementById("code_class");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		alert("Copied the text: " + copyText.value);
	}
</script>
@endsection