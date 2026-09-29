@yield('css')
<!--plugins-->

<style type="text/css">
	.scrolling-element {
    will-change: transform;
}
</style>
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
<script type="text/javascript" src="{{ URL::asset('assets/web/js/jquery.slim.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/web/js/lightbox-plus-jquery.min.js') }}"></script>
<!-- <script type="text/javascript" src="{{ URL::asset('assets/web/js/moment.min.js')}}"></script> -->
<!-- <script type="text/javascript" src="{{ URL::asset('assets/web/js/daterangepicker.js')}}"></script> -->