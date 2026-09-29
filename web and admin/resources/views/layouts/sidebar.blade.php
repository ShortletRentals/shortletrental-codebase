<?php 
use App\Models\User;

$user_type = User::where('id',auth()->id())->pluck('user_type')->first();
// dd($user_type);
?>
<!-- sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{ URL::asset('assets/images/logo-fav.png')}}" class="logo-icon" alt="logo icon" style="width: 50px;">
		</div>
		<div>
			<h4 class="logo-text"><img src="{{ URL::asset('assets/images/logo-cont.png')}}"></h4>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
		</div>
	</div>
	<!--navigation-->
	<ul class="metismenu" id="menu">
		@if($user_type != 5)
		<li class="{{ (Request::is('admin/home/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.home') }}" class="">
				<div class="parent-icon"><i class='bx bx-home-circle'></i>
				</div>
				<div class="menu-title">Dashboard</div>
			</a>
		</li>
		@endif
		@can('Property-section')
		<li>
			<a href="javascript:;" class="{{ (Request::is('admin/property/*') ? 'mm-active':'') }} has-arrow">
				<div class="parent-icon"><i class="bx bx-list-ul"></i></div>
				<div class="menu-title">Accommodations</div>
			</a>
			<ul>
				<li> <a href="{{ route('admin.property.index') }}"><i class="bx bx-right-arrow-alt"></i>List of Accommodations</a></li>
				<!-- <li> <a href="{{ route('admin.property.index', 'long') }}"><i class="bx bx-right-arrow-alt"></i>Long-term Rentals</a></li>
				<li> <a href="{{ route('admin.property.index', 'sale') }}"><i class="bx bx-right-arrow-alt"></i>For Sale</a></li> -->
			</ul>
		</li>
		@endcan
		@can('Subadmin-section')
		<!-- <li class="{{ (Request::is('admin/subadmin/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.subadmin') }}">
				<div class="parent-icon"><i class="bx bx-user"></i>
				</div>
				<div class="menu-title">Sub-Admin Manager</div>
			</a>
		</li> -->
		@endcan

		@if($user_type == 1 || $user_type == 2)
			@can('Rate-section')
			<li>
				<a href="javascript:;" class="{{ (Request::is('admin/rate/*') ? 'mm-active':'') }} has-arrow">
					<div class="parent-icon"><i class="bx bx-credit-card"></i></div>
					<div class="menu-title">Rates</div>
				</a>
				<ul>
					<li> <a href="{{ route('admin.rate.index') }}"><i class="bx bx-right-arrow-alt"></i>Rates List</a></li>
				</ul>
			</li>
			@endcan
		@endif

		@can('Influencer-section')
		<li class="{{ (Request::is('admin/influencer/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.influencer.index') }}">
				<div class="parent-icon"><i class="bx bx-user"></i>
				</div>
				<div class="menu-title">Partner Manager</div>
			</a>
		</li>
		@endcan
		
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/customer/*')){ echo 'mm-active'; }elseif(Request::is('admin/guest-chat/*')){ echo 'mm-active'; }elseif(Request::is('admin/chat/*')){ echo 'mm-active'; }elseif(Request::is('admin/subscribe_users/*')){ echo 'mm-active'; } ?> has-arrow">
				<div class="parent-icon"><i class="bx bx-user"></i></div>
				<div class="menu-title">Guest Manager</div>
			</a>
			<ul>
				@can('Customer-section')
					<li> <a href="{{ route('admin.customer.index') }}"><i class="bx bx-right-arrow-alt"></i>List of Guest</a></li>
				@endcan
				@if($user_type == 1 || $user_type == 2)
					@can('Chat-section')
						<li> <a href="{{ route('admin.guest_chat.index') }}"><i class="bx bx-right-arrow-alt"></i>Guest Chat</a></li>
					@endcan
				@endif
				@can('Chat-section')
					<li> <a href="{{ route('admin.chat.index') }}"><i class="bx bx-right-arrow-alt"></i>Chat</a></li>
				@endcan
				@can('Subscribe-section')
					<li> <a href="{{ route('admin.subscribe_users.index') }}"><i class="bx bx-right-arrow-alt"></i>Subscribe Users</a></li>
					<!-- <li> <a href="{{ route('admin.permissions') }}"><i class="bx bx-right-arrow-alt"></i>Permission</a></li> -->
				@endcan
			</ul>
		</li>
		

		
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/booking/*')){ echo 'mm-active'; }elseif(Request::is('admin/discount/*')){ echo 'mm-active'; } ?> has-arrow">
				<div class="parent-icon"><i class="bx bx-book-content"></i></div>
				<div class="menu-title">Bookings Manager</div>
			</a>
			<ul>
				@can('Booking-section')
					<li> <a href="{{ route('admin.booking.index') }}"><i class="bx bx-right-arrow-alt"></i>List of Bookings</a></li>
				@endcan


				@if($user_type != 3 )
				@can('Booking-Reserve-section')
					<li> <a href="{{ route('admin.booking_reserve.index') }}"><i class="bx bx-right-arrow-alt"></i>List of booking reservations</a></li>
					<li> <a href="{{ route('admin.booking_search.index') }}"><i class="bx bx-right-arrow-alt"></i>Availability Search</a></li>
				@endcan
				@endif
				@can('Discount-section')
				<li> <a href="{{ route('admin.discount.index') }}"><i class="bx bx-right-arrow-alt"></i>Discounts</a></li>
				@endcan
			</ul>
		</li>
		
		@can('Host-section')
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/host/*')){ echo 'mm-active'; }elseif(Request::is('admin/commission/*')){ echo 'mm-active'; } ?> has-arrow">
				<div class="parent-icon"><i class="bx bx-key"></i></div>
				<div class="menu-title">Owners Manager</div>
			</a>
			<ul>
				
					<li> <a href="{{ route('admin.host.index') }}"><i class="bx bx-right-arrow-alt"></i>List of Owners</a></li>
				
					<li> <a href="{{ route('admin.become_a_host.index') }}"><i class="bx bx-right-arrow-alt"></i>Become A Host</a></li>
				
				<li> <a href="{{ route('admin.commission.index') }}"><i class="bx bx-right-arrow-alt"></i>Contracts</a></li>
				@if($user_type == 1 || $user_type == 2)
				<li class="{{ (Request::is('admin/appointment/*') ? 'mm-active':'') }}">
					<a href="{{ route('admin.appointment.index') }}">
						<i class="bx bx-right-arrow-alt"></i>
						Appointment Manager
					</a>
				</li>
				@endif
			</ul>
		</li>
		@endcan
		
		@can('Transaction-section')
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/transection/*')){ echo 'mm-active'; }elseif(Request::is('admin/offer/*')){ echo 'mm-active'; } ?> has-arrow">
				<div class="parent-icon"><i class="bx bx-file"></i></div>
				<!-- <div class="menu-title">Reports and exports Manager</div> -->
				<div class="menu-title">Transaction Manager</div>
			</a>
			<ul>
				<li> <a href="{{ route('admin.transection.index') }}"><i class="bx bx-right-arrow-alt"></i>Transaction</a></li>
				<li> <a href="{{ route('admin.sattlement.index') }}"><i class="bx bx-right-arrow-alt"></i>Settlement</a></li>

				@if($user_type == 1 || $user_type == 2)
					<li> <a href="{{ route('admin.bookings_commission.index') }}"><i class="bx bx-right-arrow-alt"></i>Bookings and commission per portal</a></li>
				@endif
			</ul>
		</li>
		@endcan
		
		<li>
			<a href="javascript:;" class="{{ (Request::is('admin/country/*') ? 'mm-active':'') }} has-arrow">
				<div class="parent-icon"><i class='bx bx-flag'></i>
				</div>
				<div class="menu-title">Country & Province</div>
			</a>
			
			<ul>
				@can('Country-section')
					<li> <a href="{{ route('admin.country.index') }}"><i class="bx bx-right-arrow-alt"></i>Country</a></li>
				@endcan
				@can('Province-section')
					<li> <a href="{{ route('admin.province.index') }}"><i class="bx bx-right-arrow-alt"></i>Province</a></li>
				@endcan
				@can('City-section')
					<li> <a href="{{ route('admin.city.index') }}"><i class="bx bx-right-arrow-alt"></i>City</a></li>
				@endcan
				@can('Area-section')
					<li> <a href="{{ route('admin.area.index') }}"><i class="bx bx-right-arrow-alt"></i>Area</a></li>
				@endcan
			</ul>
		</li>
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/building/*')){ echo 'mm-active'; }elseif(Request::is('admin/amenity/*')){ echo 'mm-active'; }elseif(Request::is('admin/category/*')){ echo 'mm-active'; }elseif(Request::is('admin/extra_service/*')){ echo 'mm-active'; } ?> has-arrow">
				<div class="parent-icon"><i class="bx bx-category"></i></div>
				<div class="menu-title">Modules</div>
			</a>
			<ul>
					<li> <a href="{{ route('admin.building.index') }}"><i class="bx bx-right-arrow-alt"></i>Building</a></li>
				
				@can('Category-section')
					<li> <a href="{{ route('admin.category.index') }}"><i class="bx bx-right-arrow-alt"></i>Category</a></li>
				@endcan
				@can('Amenities-section')
					<li> <a href="{{ route('admin.amenity.index') }}"><i class="bx bx-right-arrow-alt"></i>Amenities</a></li>
				@endcan
					<li> <a href="{{ route('admin.extra_service.index') }}"><i class="bx bx-right-arrow-alt"></i>Extra services</a></li>
				
				
					<li> <a href="{{ route('admin.offer.index') }}"><i class="bx bx-right-arrow-alt"></i>Offers</a></li>
				
			</ul>
		</li>
		@can('Category-section')
			<li>
				<a href="javascript:;" class="<?php if(Request::is('admin/blog/*')){ echo 'mm-active'; }elseif(Request::is('admin/blog_category/*')){ echo 'mm-active'; } ?> has-arrow">
					<div class="parent-icon"><i class="bx bx-category"></i></div>
					<div class="menu-title">Blog</div>
				</a>
				<ul>
					<li> <a href="{{ route('admin.blog_category.index') }}"><i class="bx bx-right-arrow-alt"></i>Blog Category</a></li>
					<li> <a href="{{ route('admin.blog.index') }}"><i class="bx bx-right-arrow-alt"></i>List of Blogs</a></li>
				</ul>
			</li>
		@endcan
		@can('Discount-section')
		<!-- <li class="{{ (Request::is('admin/discount/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.discount.index') }}">
				<div class="parent-icon"><i class="bx bx-money"></i>
				</div>
				<div class="menu-title">Discount Manager</div>
			</a>
		</li> -->
		@endcan
		@can('Transaction-section')
		<!-- <li class="{{ (Request::is('admin/transection/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.transection.index') }}">
				<div class="parent-icon"><i class="bx bx-category"></i>
				</div>
				<div class="menu-title">Transaction Manager</div>
			</a>
		</li> -->
		@endcan
		<!-- <li class="{{ (Request::is('admin/offers/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.offer.index') }}">
				<div class="parent-icon"><i class="bx bx-category"></i>
				</div>
				<div class="menu-title">Offers Manager</div>
			</a>
		</li> -->
		@can('Chat-section')
		<!-- <li class="{{ (Request::is('admin/chat/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.chat.index') }}">
				<div class="parent-icon"><i class="bx bx-message"></i>
				</div>
				<div class="menu-title">Chat Manager</div>
			</a>
		</li> -->
		@endcan

		@if($user_type != 1 && $user_type != 2)
			@can('Chat-section')
				<li> 
					<a href="{{ route('admin.chat.index') }}">
						<div class="parent-icon">
							<i class="bx bx-message"></i>
						</div>
						<div class="menu-title">Chat </div>	
					</a>
				</li>
			@endcan
			@can('Content-section')
				<li> 
					<a href="{{ route('admin.content.index') }}">
						<div class="parent-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
							<path fill="currentColor" d="M20 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM4 19V5h7v14H4zm9 0V5h7l.001 14H13z"/>
							<path fill="currentColor" d="M15 7h3v2h-3zm0 4h3v2h-3z"/>
						</svg>
						</div>
						<div class="menu-title">Content </div>
					</a>
				</li>
			@endcan
		@endif

		@can('Setting-section')
		<li>
			<a href="javascript:;" class="<?php if(Request::is('admin/country/*')){ echo 'mm-active'; }elseif(Request::is('admin/commission/*')){ echo 'mm-active'; } ?> {{ (Request::is('admin/country/*') ? 'mm-active':'') }} has-arrow">
				<div class="parent-icon"><i class='bx bx-cog'></i></div>
				<div class="menu-title">Configuration</div>
			</a>
			<ul>
				@can('Setting-section')
					<li> <a href="{{ route('admin.admin-settings.index') }}"><i class="bx bx-right-arrow-alt"></i>Setting</a></li>
				@endcan

				@can('Email-Template-section')
					<li> <a href="{{ route('admin.email_template_lang.index') }}"><i class="bx bx-right-arrow-alt"></i>Email Template</a></li>
				@endcan
				@can('Subadmin-section')
					<li> <a href="{{ route('admin.subadmin.index') }}"><i class="bx bx-right-arrow-alt"></i>Sub-Admin</a></li>
				@endcan
				@can('Notification-section')
					<li> <a href="{{ route('admin.notification.index') }}"><i class="bx bx-right-arrow-alt"></i>Notifications</a></li>
				@endcan
				@can('Content-section')
					<li> <a href="{{ route('admin.content.index') }}"><i class="bx bx-right-arrow-alt"></i>Content</a></li>
				@endcan
				@can('Permissions-section')
					<!-- <li> <a href="{{ route('admin.subscribe_users.index') }}"><i class="bx bx-right-arrow-alt"></i>Subscribe Users</a></li> -->
					<li> <a href="{{ route('admin.permissions') }}"><i class="bx bx-right-arrow-alt"></i>Permission</a></li>
				@endcan
			</ul>
		</li>
		<!-- <li class="{{ (Request::is('admin/settings/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.admin-settings.index') }}">
				<div class="parent-icon"><i class="bx bx-cog"></i>
				</div>
				<div class="menu-title">Setting Manager</div>
			</a>
		</li> -->
		@endcan
		@can('Notification-section')
		<!-- <li class="{{ (Request::is('admin/notification/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.notification.index') }}">
				<div class="parent-icon"><i class="bx bx-bell"></i>
				</div>
				<div class="menu-title">Notification Manager</div>
			</a>
		</li> -->
		@endcan
		@can('Content-section')
		<!-- <li class="{{ (Request::is('admin/content/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.content.index') }}">
				<div class="parent-icon"><i class="bx bx-pencil"></i>
				</div>
				<div class="menu-title">Content Manager</div>
			</a>
		</li> -->
		@endcan
		@can('Email-Template-section')
		<!-- <li class="{{ (Request::is('admin/email_template_lang/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.email_template_lang.index') }}">
				<div class="parent-icon"><i class="bx bx-envelope"></i>
				</div>
				<div class="menu-title">Email Template Manager</div>
			</a>
		</li> -->
		@endcan
		@can('Permissions-section')
		<!-- <li class="{{ (Request::is('admin/permissions/*') ? 'mm-active':'') }}">
			<a href="{{ route('admin.permissions') }}">
				<div class="parent-icon"><i class="bx bx-lock"></i>
				</div>
				<div class="menu-title">Permissions</div>
			</a>
		</li> -->
		@endcan
	</ul>
	<!--end navigation-->
</div>
<!--end sidebar wrapper -->