@extends('layouts.web.master')
<?php
    $auth_user = Session::get('AuthUserData');

    if (isset($auth_user)) {
      $userId = $auth_user->data->id;
    }
?>
@section('content')
    <main>
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			@include('layouts.web.leftbar_itms')
	        			<div class="sidebar_r">
	        				
	        				<div class="inner-title d-flex align-items-center">
	        					<h2 class="heading-inner-title mb-0">My Notification</h2>
	        					<div class="ms-auto notification-drop">
	        						<a href="javascript:avoid(0)" class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                          <img src="{{ URL::asset('assets/web/img/more_dots.png')}}" alt="">
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteNotification(this)">Clear All</a></li>
                          <li><a class="dropdown-item" href="javascript:void(0);" onclick="readNotification(this)">Mark All As Read</a></li>
                      </ul>
	        					</div>
	        				</div>
	        				<div class="notification-sec">
	        					<div class="notification_wrap">

                        <?php if ($data->status == true && count($data->data)) { foreach ($data->data as $key => $value) { ?>
                            <div class="{{$value->is_read == '0' ? 'unread_notification' : ''}} noti_space notification_div_{{ $value->id }}" data-id="{{ $value->id }}" onclick="readNotification(this)">
                                <?php
                                  $url = url('my_account');

                                  if ($value->notification_for == 'Booking') {
                                    $url = url('bookings');
                                  }

                                  if ($value->notification_for == 'Customer') {
                                    $url = url('notifications');
                                  }
                                ?>
                                <a href="{{ $url }}"><p>{{$value->message}}</p></a>
                                <div class="time_remove">
                                  <div class="noti_time">
                                    <span>{{ date('d M Y', strtotime($value->created_at)) }}</span>
                                  </div>
                                  <div class="noti_remove">
                                    <span><a href="javascript:void(0)" onclick="deleteNotification(this)" data-id="{{ $value->id }}"><img src="{{ URL::asset('assets/web/img/delete.png')}}"></a></span>
                                  </div>
                                </div>
                            </div>
                          <?php } ?>
                        <?php } else { ?>
                          <span>No record found!</span>
                        <?php } ?>
                    </div>
	        				</div>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>
@endsection
@section('script')
<script type="text/javascript">

  function deleteNotification($this) {
    var notification_id = $($this).attr('data-id');
    var user_id = "{{$userId ?? ''}}";

    if (user_id != '') {

      if (confirm("Are you sure you want to delete notification?") == true) {
        //call ajax for Delete Notification
        var formData = new FormData(); // Currently empty
        var token = "{{ csrf_token() }}";
        formData.append('_token', token);
        formData.append('userId', user_id);

        if (notification_id) {
          formData.append('notification_id', notification_id);
        }

        $.ajax({
          url: '{{ route("web.clearAllNotification") }}',
          dataType: 'json',
          data: formData,
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function(res) {

            if (res.status === 1) {
              toastr.success(res.message);
            } else {

              if (notification_id) {
                $('.notification_div_'+notification_id).remove();

              } else {
                $('.noti_space').remove();
              }
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
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }

  function readNotification($this) {
    var notification_id = $($this).attr('data-id');
    var user_id = "{{$userId ?? ''}}";

    if (user_id != '') {
      //call ajax for Delete Notification
      var formData = new FormData(); // Currently empty
      var token = "{{ csrf_token() }}";
      formData.append('_token', token);
      formData.append('userId', user_id);

      if (notification_id) {
        formData.append('notification_id', notification_id);
      }

      $.ajax({
        url: '{{ route("web.readAllNotification") }}',
        dataType: 'json',
        data: formData,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(res) {

          if (res.status === true) {
            toastr.success(res.message);

            if (notification_id) {
              // $('.notification_div_'+notification_id).remove();
              $('.notification_div_'+notification_id).removeClass('unread_notification');
            } else {
              $('.noti_space').removeClass('unread_notification');
            }
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

    } else {
      //Open Login Popup
      $('#exampleModal').modal('show');
    }
  }
</script>
@endsection