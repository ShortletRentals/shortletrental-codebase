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
<!--start header -->
<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>
			<div class="search-bar flex-grow-1">
				<div class="position-relative search-bar-box">
					<!-- <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
					<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span> -->
				</div>
			</div>
			<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center">
					<li class="nav-item mobile-search-icon">
						<a class="nav-link" href="#">	<i class='bx bx-search'></i>
						</a>
					</li>
					<!-- <li class="nav-item dropdown dropdown-large">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<div class="row row-cols-3 g-3 p-3">
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-group'></i>
									</div>
									<div class="app-title">Teams</div>
								</div>
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-atom'></i>
									</div>
									<div class="app-title">Projects</div>
								</div>
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-shield'></i>
									</div>
									<div class="app-title">Tasks</div>
								</div>
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-notification'></i>
									</div>
									<div class="app-title">Feeds</div>
								</div>
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-file'></i>
									</div>
									<div class="app-title">Files</div>
								</div>
								<div class="col text-center">
									<div class="app-box mx-auto"><i class='bx bx-filter-alt'></i>
									</div>
									<div class="app-title">Alerts</div>
								</div>
							</div>
						</div>
					</li> -->

					<li class="nav-item dropdown dropdown-large ">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative onShowNotification" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count"><span class="show_notification_count">{{$notificaiton_count}}</span></span>
							<i class='bx bx-bell'></i>
						</a>
						<div class="dropdown-menu dropdown-menu-end show_notification">
							
							
						</div>
					</li>

					<li class="nav-item dropdown dropdown-large">

						@can('Chat-section')
							<a class="nav-link position-relative" href="{{ route('admin.chat.index') }}" role="button" > <i class='bx bx-comment'></i> </a>
						@endcan
						<!-- <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">1</span>
							<i class='bx bx-comment'></i>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="javascript:;">
								<div class="msg-header">
									<p class="msg-header-title">Messages</p>
									<p class="msg-header-clear ms-auto">Marks all as read</p>
								</div>
							</a>
							<div class="header-message-list">
								<a class="dropdown-item" href="javascript:;">
									<div class="d-flex align-items-center">
										<div class="user-online">
											<img src="{{ URL::asset('assets/images/avatars/avatar-1.png')}}" class="msg-avatar" alt="user avatar">
										</div>
										<div class="flex-grow-1">
											<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
										ago</span></h6>
											<p class="msg-info">The standard chunk of lorem</p>
										</div>
									</div>
								</a>
							</div>
							<a href="javascript:;">
								<div class="text-center msg-footer">View All Messages</div>
							</a>
						</div> -->
					</li>
				</ul>
			</div>
			<div class="user-box dropdown">
				<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{Auth::user()->image}}" class="user-img" alt="user avatar">
					<div class="user-info ps-3">
						<p class="user-name mb-0">{{$login_user_data->name}}</p>
						<p class="designattion mb-0">{{($login_user_data->user_type == 1) ? "Admin" : "Other"}}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bx bx-user"></i><span>Profile</span></a>
					</li>
					<!-- <li><a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a> -->
					</li>
					<li><a class="dropdown-item" href="{{ route('admin.change_password') }}"><i class='bx bx-key'></i><span>Change Password</span></a>
					</li>
					<!-- <li><a class="dropdown-item" href="javascript:;"><i class='bx bx-dollar-circle'></i><span>Earnings</span></a> -->
					</li>
					<!-- <li><a class="dropdown-item" href="javascript:;"><i class='bx bx-download'></i><span>Downloads</span></a>
					</li> -->
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
					</li>
					<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!--end header -->
<script>
setInterval(function () {
	$('.show_notification_count').load("{{route('admin.show_notification_count.index')}}");
}, 3000);



	$(document).on('click','.onShowNotification',function(){
		$('.show_notification').load("{{route('admin.show_notification.index')}}");
	});

</script>