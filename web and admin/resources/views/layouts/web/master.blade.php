 <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>@yield('title','ShortletRental')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('metadata')
    <link rel="icon" href="{{ URL::asset('assets/images/favicon.ico')}}" type="image/png" />
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/main.css?v='.rand(0,1000))}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/responsive.css?v='.rand(0,1000))}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/custom.css?v='.rand(0,1000))}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/web/css/lightbox.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" media="all" href="{{ URL::asset('assets/web/css/daterangepicker.css')}}" /> -->
    @include('layouts.web.head')
  </head>
  <body>
    @include('layouts.web.header')
    @yield('content')
    @yield('script')
    @if(!Request::is('become_a_host_type') && !Request::is('host_category/*') && !Request::is('host_address') && !Request::is('host_guest') && !Request::is('host_amenity') && !Request::is('host_title') && !Request::is('host_description') && !Request::is('host_extra_services') && !Request::is('host_price') && !Request::is('host_images') && !Request::is('host_profile') && !Request::is('host_max_guest') )
      @include('layouts.web.footer')
    @endif
    @include('layouts.web.footer_script')
   <script>
    setTimeout(function () {
      var origConsoleError = console.error;
      console.error = function () { };
    }, 5000);
</script>
  </body>
</html>
