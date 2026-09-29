@extends('layouts.web.master')

@section('content')

<?php 
use App\Models\User;
$auth_user = Session::get('AuthUserData');
$is_guest = Session::get('is_guest');

if (isset($auth_user)) {
  $userId = $auth_user->data->id;
if($is_guest != 1){
$auth_user = User::where('id',$userId)->first();
}else{
$auth_user = $auth_user->data;
}
}
?>
  <main>
        <section class="thankyou-sec">
          <div class="container">
             <div class="thankyou_sec_in">
                <img src="{{ URL::asset('assets/web/img/tick.png')}}" alt="check icon">
                <h1>Thank You !</h1>
           
                @if(isset($data['type']) && $data['type'] = 'rese')
                <p>Your booking has been successful reserved</p>
                @else
                <p>Your booking has been successfully placed</p>
                @endif
                <p>Your Booking No : {{$data['booking_id'] ?? ''}} is successfully placed</p>
                <div class="btn_thank">
                  @if($is_guest == 1)
                  <a href="#" class="btn primary_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">Login / Signup</a>
                  @else
                  @if(isset($data['type']) && $data['type'] = 'rese')
                  <a href="{{ url('/reservations') }}" class="btn primary_btn">Go to My Reservations</a>
                  @else
                  <a href="{{ url('/bookings') }}" class="btn primary_btn">Go to My Booking</a>                  
                  @endif
                  @endif

                </div>
             </div>
          </div>
       </section>
  </main>
@endsection