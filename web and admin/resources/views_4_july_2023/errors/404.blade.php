@extends('layouts.web.master')

@section('content')
	<div id="main">
		<div class="error_page">
			<div class="container">
		    	<!-- <h1>404</h1>
		    	<h4>Page not Found</h4> -->
			    <div class="error_inner_page">
				    	<img src="{{ URL::asset('images/404.png')}}" alt="404">
				    	<!-- <p>We're sorry, the page you requested could not be found. Please go back to the homepage.</p> -->
				    	<div class="backBtn">
				    		<a href="{{ url('/') }}" class="btn primary_btn">Back to Home</a>
				    	</div>
			    </div>
		  </div>
		</div>
	</div>
@endsection