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
{{$notificaiton_count}}