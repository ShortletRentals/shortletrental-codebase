@extends('layouts.web.master')

@section('content')
    <main>
	    <section class="about_page space-cls">
	        <div class="container">
	        	<div class="about_inner">
              <div class="row">
                <!-- <div class="col-md-5">
                  <div class="about-img">
                      <img src="{{ URL::asset('assets/web/img/about.png')}}" alt="About">
                  </div>
                </div> -->
                <div class="col-md-12">
                  <div class="about-right">
                      <div class="about-cont">
                        <h3 class="inner-title">{{$data->name}}</h3>
                        {!! $data->description !!}
                      </div>
                  </div>
                </div>
              </div>  
            </div>
	        </div>
	    </section>
    </main>
    <div class="button-fix">
    	<a href="#" class="message-right">Message</a>
    </div>
@endsection