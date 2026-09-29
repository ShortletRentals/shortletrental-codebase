<div class="sidebar_l">
    <div class="filter_main">
      <div class="filter_pop d-md-none">
        <a class="filter_icon" href="javascript:void(0);"><img src="{{ URL::asset('assets/web/img/filter_ic.png')}}" ></a>
      </div>
    </div>
    <div class="side-filter">
      <button type="button" class="btn_cross d-md-none">
        <img src="{{ URL::asset('assets/web/img/close.png')}}" alt="Close">    
      </button>
      <div class="sidebar-link">
        <ul>
          <li><a href="{{ route('web.my_account') }}" class="{{ (Request::is('my_account') ? 'active':'') }}">My Account</a></li>
          <!-- <li><a href="{{ route('web.my_card') }}" class="{{ (Request::is('my_card') ? 'active':'') }}">My Cards</a></li> -->
          <li><a href="{{ route('web.my_bookings') }}" class="{{ (Request::is('bookings') ? 'active':'') }}">My Bookings</a></li>
          <li><a href="{{ route('web.my_reservations') }}" class="{{ (Request::is('reservations') ? 'active':'') }}">My Reservations</a></li>
          <li><a href="{{ route('web.my_favorites') }}" class="{{ (Request::is('favorites') ? 'active':'') }}">My Favorites</a></li>
          <li><a href="{{ route('web.my_notifications') }}" class="{{ (Request::is('notifications') ? 'active':'') }}">Notification</a></li>
          <li><a href="{{ route('web.chat') }}" class="{{ (Request::is('chat') ? 'active':'') }}">Chat</a></li>
          <li><a href="javascript:void(0)" class="logout_web">Logout</a></li>
        </ul>
      </div>
    </div>
</div>

<script type="text/javascript">
  // $(document).on('click', '.logout_web', function() {
  //     if (confirm("{{__('backend.confirm_box_logout')}}") == true) {
  //         window.location.href = "{{route('web.logout')}}";
  //     }
  // })
</script>