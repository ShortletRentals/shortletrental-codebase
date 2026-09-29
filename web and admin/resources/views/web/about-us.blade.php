@extends('layouts.web.master')

@section('content')
    <main>
      <div class="sld_s" style="background-image: url(assets/web/img/nosotros.jpg);">
        <div class="sld_s_con">
          <div class="container">
            <h2>About Shortletrentals</h2>
          </div>
        </div>
      </div>
	    <section class="about_page space-cls">
	        <div class="container">
	        	<div class="about_inner">
              <div class="row">
                <div class="col-md-5">
                  <div class="about-img">
                      <img src="{{ URL::asset('assets/web/img/about.png')}}" alt="About">
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="about-right">
                      <div class="about-cont">
                        <h3 class="inner-title">About US</h3>
                        <p>Shortlet Rentals is a multinational company that operates an online marketplace for lodging, primarily short lets for vacation rentals, and tourism activities.</p>
                        <p>Based in both Lagos, Nigeria, & Dallas Texas, the platform is accessible only via website.</p>
                        <p>We connect homeowners, referred to as “Hosts”, with families and vacationers, referred to as “guests”, looking for something more than a hotel for their trip. The platform offers guests an array of rental property types ranging from studio apartments to up to five bedroom homes. It also offers guest party houses, penthouses and Luxury accommodations with amazing features.</p>
                        <p>We ensure all properties on the platform are vetted and verified to ensure listings, pictures and descriptions are the same as real life.</p>
                        <p>We also ensure our hosts get their payout in their local currency to ensure quick and easy access to their funds.</p>
                        <p>All bookings on the platform are guaranteed with our “Book with Confidence” guarantee.</p>
                        <p>Check out our Help center page to learn more.</p>
                        <h3 class="heading-inner-title">We make finding the perfect short let easy with only 3 steps:</h3>
                        <ul>
                          <li>Browse - Browse through the amazing and verified listings,</li>
                          <li>Book - Book with confidence,</li>
                          <li>Go - Be on the move to your new rental homes</li>
                        </ul>
                        <div class="get-started d-flex">
                          <a href="#" class="btn primary_btn">Get Started</a>
                        </div>
                      </div>
                  </div>
                </div>
              </div>  
            </div>
	        </div>
          <!-- <div class="about-history-sec space-cls">
            <div class="container">
              <div class="his-inner-bg" style="background-image: url('{{ URL::asset('assets/web/img/history.png')}}');">
                <h3 class="inner-title">Our History</h3>
                <div class="row">
                  <div class="col-md-3">
                    <div class="his-box">
                      <h4>Lorem Ipsum</h4>
                      <p>Lorem Ipsum is simply dummy text of the printing and type setting industry.</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="his-box">
                      <h4>Lorem Ipsum</h4>
                      <p>Lorem Ipsum is simply dummy text of the printing and type setting industry.</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="his-box">
                      <h4>Lorem Ipsum</h4>
                      <p>Lorem Ipsum is simply dummy text of the printing and type setting industry.</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="his-box">
                      <h4>Lorem Ipsum</h4>
                      <p>Lorem Ipsum is simply dummy text of the printing and type setting industry.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
	    </section>
    </main>
    <div class="button-fix">
    	<a href="#" class="message-right">Message</a>
    </div>
@endsection