<?php
	use App\User;
	$login_user_data = auth()->user();

	if(isset($login_user_data) && !empty($login_user_data)){
		$data = getNotificationList($login_user_data->id, $login_user_data->type);
		$notificaiton_list = $data['notificationData'];
		$notificaiton_count = $data['count'];
	}else{
		$notificaiton_list = [];
		$notificaiton_count = 0;
	}
	// dd($notificaiton_list);
?>

							
							<div class="msg-header">
								<p class="msg-header-title">Notifications</p>
								<a href="javascript:;" onclick="readAllNotification()">
									<p class="msg-header-clear ms-auto">Marks all as read</p>
								</a>
								<a href="javascript:;" onclick="clearAllNotification()">
									<p class="msg-header-clear ms-auto">Clear all</p>
								</a>
							</div>
							<div class="header-notifications-list">
								<?php if ($notificaiton_list && count($notificaiton_list)) { 
									foreach ($notificaiton_list as $key => $value) {
									$time_ago = $value->created_at->diffForHumans();
								?>
								<!-- <div class="notify"><i class="bx bx-group"></i></div> -->
								<div class="dropdown-item">
									<div class="d-flex align-items-center">
										<div class="flex-grow-1">
											@if($value->notification_for == 'Become_a_host_register')
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.become_a_host.index') }}" onclick="readNotification(this)">
											@elseif($value->notification_for == 'guest_register')
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.customer.index') }}" onclick="readNotification(this)">
											@elseif($value->notification_for == 'Guest-Chat')
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.guest_chat.index') }}" onclick="readNotification(this)">
											@elseif($value->notification_for == 'chat')
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.chat.index') }}" onclick="readNotification(this)">

											@elseif($value->notification_for == 'Reserve Booking')
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.booking_reserve.index') }}" onclick="readNotification(this)">
											@else
											<a href="javascript:void(0)" data-id="{{$value->id}}" data-url="{{ route('admin.booking.index') }}" onclick="readNotification(this)">
											@endif
												<h6 class="msg-name">{{ $value->title }}</h6>
												<p class="msg-info">{{ $value->message }}</p>
												<span class="msg-time">{{$time_ago}}</span>
											</a>
										</div>
										<div class="noti_remove">
											<span><a href="javascript:void(0)" onclick="deleteNotification(this)" data-id="{{$value->id}}"><img src="{{ asset('assets/web/img/delete.png') }}"></a></span>
										</div>
									</div>
								</div>
									
								<?php } } else { ?>
									<div class="notification_wrap" style="text-align:center;">
										<p class="m-b-0">No data found!</p>
									</div>
								<?php } ?>
							</div>
							<a href="javascript:;">
								<div class="text-center msg-footer">View All Notifications</div>
							</a>
				

            <script>
	function readNotification($this) {
		var notificationId = $($this).attr('data-id');
		var redirectUrl = $($this).attr('data-url');

		if (notificationId) {
			$.ajax({
				type: 'GET',
				data: {_method: 'get', _token: "{{ csrf_token() }}"},
				dataType:'json',
				url: "{!! url('admin/notifications/readNotification' )!!}" + "/" + notificationId,
				success:function(res){

					if(res.status === 1){ 
						window.location.href = redirectUrl;
						// window.location.reload();
					} else {
						toastr.error(res.message);
					}
				},   
				error:function(jqXHR,textStatus,textStatus){
					toastr.error(jqXHR.statusText)
				}
			});
		}
	}

  	function deleteNotification($this) {
		var notificationId = $($this).attr('data-id');
		// var redirectUrl = $($this).attr('data-url');

		if (notificationId) {
		$.ajax({
			type: 'get',
			data: {_method: 'get', _token: "{{ csrf_token() }}"},
			dataType:'json',
			url: "{!! url('admin/notifications/deleteNotification' )!!}" + "/" + notificationId,
			success:function(res){
				if(res.status === 1){ 
				//   window.location.href = redirectUrl;
					window.location.reload();
				} else {
					toastr.error(res.message);
				}
			},   
			error:function(jqXHR,textStatus,textStatus){
				console.log(jqXHR);
				toastr.error(jqXHR.statusText)
			}
		});
		}
  	}

	function clearAllNotification() {
		var userId = "<?php echo $login_user_data->id ?>";
		$.ajax({
			type: 'get',
			data: {_method: 'get', _token: "{{ csrf_token() }}", userId: userId},
			dataType:'json',
			url: "{!! url('admin/notifications/clearAllNotification' )!!}",
			success:function(res){
				if(res.status === 1){ 
					window.location.reload();
				} else {
					toastr.error(res.message);
				}
			},   
			error:function(jqXHR,textStatus,textStatus){
				console.log(jqXHR);
				toastr.error(jqXHR.statusText)
			}
		});
	}

  function readAllNotification() {
    var userId = "<?php echo $login_user_data->id ?>";
    $.ajax({
        type: 'get',
        data: {_method: 'get', _token: "{{ csrf_token() }}", userId: userId},
        dataType:'json',
        url: "{!! url('admin/notifications/readAllNotification' )!!}",
        success:function(res){
          if(res.status === 1){ 
            window.location.reload();
          } else {
            toastr.error(res.message);
          }
        },   
        error:function(jqXHR,textStatus,textStatus){
          console.log(jqXHR);
          toastr.error(jqXHR.statusText)
        }
    });
  }
</script>