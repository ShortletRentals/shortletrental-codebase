<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/dashtreme/demo/vertical/dashboard-analytics.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 12 Sep 2022 05:30:40 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
	<link href="assets/plugins/highcharts/css/highcharts-white.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	
	<title>Dashtreme - Multipurpose Bootstrap5 Admin Template</title>
</head>

<body class="bg-theme bg-theme9">
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">Dashtreme</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
					<ul>
						<li> <a href="index.html"><i class="bx bx-right-arrow-alt"></i>Default</a>
						</li>
						<li> <a href="dashboard-eCommerce.html"><i class="bx bx-right-arrow-alt"></i>eCommerce</a>
						</li>
						<li> <a href="dashboard-sales.html"><i class="bx bx-right-arrow-alt"></i>Sales</a>
						</li>
						<li> <a href="dashboard-analytics.html"><i class="bx bx-right-arrow-alt"></i>Analytics</a>
						</li>
						<li> <a href="dashboard-alternate.html"><i class="bx bx-right-arrow-alt"></i>Alternate</a>
						</li>
						<li> <a href="dashboard-digital-marketing.html"><i class="bx bx-right-arrow-alt"></i>Digital Marketing</a>
						</li>
						<li> <a href="dashboard-human-resources.html"><i class="bx bx-right-arrow-alt"></i>Human Resources</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-category"></i>
						</div>
						<div class="menu-title">Application</div>
					</a>
					<ul>
						<li> <a href="app-emailbox.html"><i class="bx bx-right-arrow-alt"></i>Email</a>
						</li>
						<li> <a href="app-chat-box.html"><i class="bx bx-right-arrow-alt"></i>Chat Box</a>
						</li>
						<li> <a href="app-file-manager.html"><i class="bx bx-right-arrow-alt"></i>File Manager</a>
						</li>
						<li> <a href="app-contact-list.html"><i class="bx bx-right-arrow-alt"></i>Contatcs</a>
						</li>
						<li> <a href="app-to-do.html"><i class="bx bx-right-arrow-alt"></i>Todo List</a>
						</li>
						<li> <a href="app-invoice.html"><i class="bx bx-right-arrow-alt"></i>Invoice</a>
						</li>
						<li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Calendar</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">UI Elements</li>
				<li>
					<a href="widgets.html">
						<div class="parent-icon"><i class='bx bx-cookie'></i>
						</div>
						<div class="menu-title">Widgets</div>
					</a>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-cart'></i>
						</div>
						<div class="menu-title">eCommerce</div>
					</a>
					<ul>
						<li> <a href="ecommerce-products.html"><i class="bx bx-right-arrow-alt"></i>Products</a>
						</li>
						<li> <a href="ecommerce-products-details.html"><i class="bx bx-right-arrow-alt"></i>Product Details</a>
						</li>
						<li> <a href="ecommerce-add-new-products.html"><i class="bx bx-right-arrow-alt"></i>Add New Products</a>
						</li>
						<li> <a href="ecommerce-orders.html"><i class="bx bx-right-arrow-alt"></i>Orders</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
						</div>
						<div class="menu-title">Components</div>
					</a>
					<ul>
						<li> <a href="component-alerts.html"><i class="bx bx-right-arrow-alt"></i>Alerts</a>
						</li>
						<li> <a href="component-accordions.html"><i class="bx bx-right-arrow-alt"></i>Accordions</a>
						</li>
						<li> <a href="component-badges.html"><i class="bx bx-right-arrow-alt"></i>Badges</a>
						</li>
						<li> <a href="component-buttons.html"><i class="bx bx-right-arrow-alt"></i>Buttons</a>
						</li>
						<li> <a href="component-cards.html"><i class="bx bx-right-arrow-alt"></i>Cards</a>
						</li>
						<li> <a href="component-carousels.html"><i class="bx bx-right-arrow-alt"></i>Carousels</a>
						</li>
						<li> <a href="component-list-groups.html"><i class="bx bx-right-arrow-alt"></i>List Groups</a>
						</li>
						<li> <a href="component-media-object.html"><i class="bx bx-right-arrow-alt"></i>Media Objects</a>
						</li>
						<li> <a href="component-modals.html"><i class="bx bx-right-arrow-alt"></i>Modals</a>
						</li>
						<li> <a href="component-navs-tabs.html"><i class="bx bx-right-arrow-alt"></i>Navs & Tabs</a>
						</li>
						<li> <a href="component-navbar.html"><i class="bx bx-right-arrow-alt"></i>Navbar</a>
						</li>
						<li> <a href="component-paginations.html"><i class="bx bx-right-arrow-alt"></i>Pagination</a>
						</li>
						<li> <a href="component-popovers-tooltips.html"><i class="bx bx-right-arrow-alt"></i>Popovers & Tooltips</a>
						</li>
						<li> <a href="component-progress-bars.html"><i class="bx bx-right-arrow-alt"></i>Progress</a>
						</li>
						<li> <a href="component-spinners.html"><i class="bx bx-right-arrow-alt"></i>Spinners</a>
						</li>
						<li> <a href="component-notifications.html"><i class="bx bx-right-arrow-alt"></i>Notifications</a>
						</li>
						<li> <a href="component-avtars-chips.html"><i class="bx bx-right-arrow-alt"></i>Avatrs & Chips</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-repeat"></i>
						</div>
						<div class="menu-title">Content</div>
					</a>
					<ul>
						<li> <a href="content-grid-system.html"><i class="bx bx-right-arrow-alt"></i>Grid System</a>
						</li>
						<li> <a href="content-typography.html"><i class="bx bx-right-arrow-alt"></i>Typography</a>
						</li>
						<li> <a href="content-text-utilities.html"><i class="bx bx-right-arrow-alt"></i>Text Utilities</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"> <i class="bx bx-donate-blood"></i>
						</div>
						<div class="menu-title">Icons</div>
					</a>
					<ul>
						<li> <a href="icons-line-icons.html"><i class="bx bx-right-arrow-alt"></i>Line Icons</a>
						</li>
						<li> <a href="icons-boxicons.html"><i class="bx bx-right-arrow-alt"></i>Boxicons</a>
						</li>
						<li> <a href="icons-feather-icons.html"><i class="bx bx-right-arrow-alt"></i>Feather Icons</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Forms & Tables</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-message-square-edit'></i>
						</div>
						<div class="menu-title">Forms</div>
					</a>
					<ul>
						<li> <a href="form-elements.html"><i class="bx bx-right-arrow-alt"></i>Form Elements</a>
						</li>
						<li> <a href="form-input-group.html"><i class="bx bx-right-arrow-alt"></i>Input Groups</a>
						</li>
						<li> <a href="form-layouts.html"><i class="bx bx-right-arrow-alt"></i>Forms Layouts</a>
						</li>
						<li> <a href="form-validations.html"><i class="bx bx-right-arrow-alt"></i>Form Validation</a>
						</li>
						<li> <a href="form-wizard.html"><i class="bx bx-right-arrow-alt"></i>Form Wizard</a>
						</li>
						<li> <a href="form-text-editor.html"><i class="bx bx-right-arrow-alt"></i>Text Editor</a>
						</li>
						<li> <a href="form-file-upload.html"><i class="bx bx-right-arrow-alt"></i>File Upload</a>
						</li>
						<li> <a href="form-date-time-pickes.html"><i class="bx bx-right-arrow-alt"></i>Date Pickers</a>
						</li>
						<li> <a href="form-select2.html"><i class="bx bx-right-arrow-alt"></i>Select2</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-grid-alt"></i>
						</div>
						<div class="menu-title">Tables</div>
					</a>
					<ul>
						<li> <a href="table-basic-table.html"><i class="bx bx-right-arrow-alt"></i>Basic Table</a>
						</li>
						<li> <a href="table-datatable.html"><i class="bx bx-right-arrow-alt"></i>Data Table</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Pages</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-lock"></i>
						</div>
						<div class="menu-title">Authentication</div>
					</a>
					<ul>
						<li> <a href="authentication-signin.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign In</a>
						</li>
						<li> <a href="authentication-signup.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign Up</a>
						</li>
						<li> <a href="authentication-signin-with-header-footer.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign In with Header & Footer</a>
						</li>
						<li> <a href="authentication-signup-with-header-footer.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign Up with Header & Footer</a>
						</li>
						<li> <a href="authentication-forgot-password.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Forgot Password</a>
						</li>
						<li> <a href="authentication-reset-password.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Reset Password</a>
						</li>
						<li> <a href="authentication-lock-screen.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Lock Screen</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="user-profile.html">
						<div class="parent-icon"><i class="bx bx-user-circle"></i>
						</div>
						<div class="menu-title">User Profile</div>
					</a>
				</li>
				<li>
					<a href="timeline.html">
						<div class="parent-icon"> <i class="bx bx-video-recording"></i>
						</div>
						<div class="menu-title">Timeline</div>
					</a>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-error"></i>
						</div>
						<div class="menu-title">Errors</div>
					</a>
					<ul>
						<li> <a href="errors-404-error.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>404 Error</a>
						</li>
						<li> <a href="errors-500-error.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>500 Error</a>
						</li>
						<li> <a href="errors-coming-soon.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Coming Soon</a>
						</li>
						<li> <a href="error-blank-page.html" target="_blank"><i class="bx bx-right-arrow-alt"></i>Blank Page</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="faq.html">
						<div class="parent-icon"><i class="bx bx-help-circle"></i>
						</div>
						<div class="menu-title">FAQ</div>
					</a>
				</li>
				<li>
					<a href="pricing-table.html">
						<div class="parent-icon"><i class="bx bx-diamond"></i>
						</div>
						<div class="menu-title">Pricing</div>
					</a>
				</li>
				<li class="menu-label">Charts & Maps</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-line-chart"></i>
						</div>
						<div class="menu-title">Charts</div>
					</a>
					<ul>
						<li> <a href="charts-apex-chart.html"><i class="bx bx-right-arrow-alt"></i>Apex</a>
						</li>
						<li> <a href="charts-chartjs.html"><i class="bx bx-right-arrow-alt"></i>Chartjs</a>
						</li>
						<li> <a href="charts-highcharts.html"><i class="bx bx-right-arrow-alt"></i>Highcharts</a>
						</li>
					</ul>
				</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-map-alt"></i>
						</div>
						<div class="menu-title">Maps</div>
					</a>
					<ul>
						<li> <a href="map-google-maps.html"><i class="bx bx-right-arrow-alt"></i>Google Maps</a>
						</li>
						<li> <a href="map-vector-maps.html"><i class="bx bx-right-arrow-alt"></i>Vector Maps</a>
						</li>
					</ul>
				</li>
				<li class="menu-label">Others</li>
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-menu"></i>
						</div>
						<div class="menu-title">Menu Levels</div>
					</a>
					<ul>
						<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level One</a>
							<ul>
								<li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Two</a>
									<ul>
										<li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Three</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<a href="https://codervent.com/dashtreme/documentation/index.html" target="_blank">
						<div class="parent-icon"><i class="bx bx-folder"></i>
						</div>
						<div class="menu-title">Documentation</div>
					</a>
				</li>
				<li>
					<a href="https://themeforest.net/user/codervent" target="_blank">
						<div class="parent-icon"><i class="bx bx-support"></i>
						</div>
						<div class="menu-title">Support</div>
					</a>
				</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="search-bar flex-grow-1">
						<div class="position-relative search-bar-box">
							<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
							<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
						</div>
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#">	<i class='bx bx-search'></i>
								</a>
							</li>
							<li class="nav-item dropdown dropdown-large">
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
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
													<p class="msg-info">5 new user registered</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-cart-alt"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-file"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
												ago</span></h6>
													<p class="msg-info">The pdf files generated</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-send"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">5.1 min avarage time response</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-home-circle"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class="bx bx-message-detail"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">New customer comments recived</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class='bx bx-user-pin'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify"><i class='bx bx-door-open'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
												ago</span></h6>
													<p class="msg-info">45% less alerts last 4 weeks</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
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
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
								<p class="user-name mb-0">Pauline Seitz</p>
								<p class="designattion mb-0">Web Designer</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-dollar-circle'></i><span>Earnings</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-download'></i><span>Downloads</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content ">		
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 dashboard_card">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div id="renderWidgetReservasEntrantes" class="twigWidgetBloques">
									    <div class="top">
									        <div class="selectWidget"> 
											    <select class="resizeselect selectFilterWidget" id="filterWidgetReservasEntrantes" style="width: 93.7969px;">
								                    <option value="hoy">Today</option>
								                    <option value="Ult7d" selected="">Last 7 days</option>
								                    <option value="Ult30d">Last 30 days</option>
								            	</select>
										    </div>
									    </div>
									    <div class="contentBloque">
									        <div class="cabeceraBloque">
									            <div class="blockClock">
									            	<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M9.98903 0C4.46903 0 -0.000976562 4.48 -0.000976562 10C-0.000976562 15.52 4.46903 20 9.98903 20C15.519 20 19.999 15.52 19.999 10C19.999 4.48 15.519 0 9.98903 0ZM9.99903 18C5.57903 18 1.99903 14.42 1.99903 10C1.99903 5.58 5.57903 2 9.99903 2C14.419 2 17.999 5.58 17.999 10C17.999 14.42 14.419 18 9.99903 18ZM10.499 5H8.99903V11L14.249 14.15L14.999 12.92L10.499 10.25V5Z" fill="white"></path>
													</svg>
												</div>
									            <p>Incoming bookings (2)</p>
									        </div> 
									        <div class="bodyBloque noHidden">
									            <div class="bloque">
									                <a href="" class="insideBloque">
									                    <div class="division greyBarra"></div>
							                            <div class="cantidad greyNumber">
							                                0
							                            </div>
							                            <div class="informacion">
							                                <p class="title">To be assigned</p> 
							                                <p class="descripcion">Unavailable dates and Airbnb request to book</p>
							                            </div>
							                        </a>
									            </div>
									            <div class="bloque">
									                <a href="" target="_blank" class="insideBloque">
							                            <div class="division greyBarra"></div>
							                            <div class="cantidad greyNumber">
							                                0
							                            </div>
							                            <div class="informacion">
							                                <p class="title">Instant Booking</p> 
							                                <p class="descripcion">HomeAway confirmed bookings</p>
							                            </div>
							                        </a>
							                    </div>
									            <div class="bloque">
									                <a href="" target="_blank" class="insideBloque">
							                            <div class="division redBarra"></div>
							                            <div class="cantidad redNumber">
							                                2
							                            </div>
							                            <div class="informacion">
							                                <p class="title">To be confirmed</p> 
							                                <p class="descripcion">New pre-bookings</p>
							                            </div>
							                        </a>
							                    </div>
		                                    	<div class="bloque">
									                <a href="" target="_blank" class="insideBloque">
							                            <div class="division greyBarra"></div>
							                            <div class="cantidad greyNumber">
							                                0
							                            </div>
							                            <div class="informacion">
							                                <p class="title">Information requests</p> 
							                                <p class="descripcion">Requests to review</p>
							                            </div>
							                        </a>
							                    </div>
	                         	           </div>
									    </div>
									</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div id="renderWidgetAccionesPendientes" class="twigWidgetBloques">
								    <div class="top">
								        <div class="selectWidget">     
										    <select class="resizeselect selectFilterWidget" id="filterWidgetAccionesPendientes" style="width: 93.7969px;">
										        <option value="hoy">Today</option>
										        <option value="Ult7d" selected="">Last 7 days</option>
										        <option value="Ult30d">Last 30 days</option>
											</select>
										</div>
								    </div>
								    <div class="contentBloque">
								        <div class="cabeceraBloque">
								            <div class="blockClock">
								            	<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M10.0003 20C11.1003 20 12.0003 19.1 12.0003 18H8.00028C8.00028 19.1 8.90028 20 10.0003 20ZM16.0003 14V9C16.0003 5.93 14.3703 3.36 11.5003 2.68V2C11.5003 1.17 10.8303 0.5 10.0003 0.5C9.17028 0.5 8.50028 1.17 8.50028 2V2.68C5.64028 3.36 4.00028 5.92 4.00028 9V14L2.00028 16V17H18.0003V16L16.0003 14ZM14.0003 15H6.00028V9C6.00028 6.52 7.51028 4.5 10.0003 4.5C12.4903 4.5 14.0003 6.52 14.0003 9V15ZM5.58028 2.08L4.15028 0.65C1.75027 2.48 0.170274 5.3 0.0302734 8.5H2.03028C2.18028 5.85 3.54028 3.53 5.58028 2.08ZM17.9703 8.5H19.9703C19.8203 5.3 18.2403 2.48 15.8503 0.65L14.4303 2.08C16.4503 3.53 17.8203 5.85 17.9703 8.5Z" fill="white"></path>
												</svg>
											</div>
								            <p>Pending actions (41)</p>
								        </div> 
        								<div class="bodyBloque hidden">
                                            <div class="bloque">
                                            	<a href="" class="insideBloque">
						                            <div class="division redBarra"></div>
						                            <div class="cantidad redNumber">
						                                28
						                            </div>
						                            <div class="informacion">
						                                <p class="title">Pending Payments</p> 
						                                <p class="descripcion">5.258.000,00 NGN to receive</p>
						                            </div>
						                        </a>
                  			  				</div>
		                                    <div class="bloque">                         
		                                        <a href="" class="insideBloque">
						                            <div class="division redBarra"></div>
						                            <div class="cantidad redNumber">
						                                10
						                            </div>
						                            <div class="informacion">
						                                <p class="title">Unread messages</p> 
						                                <p class="descripcion">In bookings and requests</p>
						                            </div>
						                        </a>
						                    </div>
		                                    <div class="bloque">
		                                        <a href="" class="insideBloque">
						                            <div class="division redBarra"></div>
						                            <div class="cantidad redNumber">
						                                3
						                            </div>
						                            <div class="informacion">
						                                <p class="title">Check-in to be validated</p> 
						                                <p class="descripcion">Check-in online requests</p>
						                            </div>
						                        </a>
						                    </div>
						                </div>
								    </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div id="renderWidgetProximosCheckins" class="twigWidgetBloques">
								    <div class="top">
								        <div class="selectWidget">       
										    <select class="resizeselect selectFilterWidget" id="filterWidgetProximosCheckins" style="width: 97.2812px;">
										        <option value="hoy">Today</option>
										        <option value="Pro7d" selected="">Next 7 days</option>
										        <option value="Pro30d">Next 30 days</option>
										    </select>
										</div>
								    </div>
								    <div class="contentbloque">
								        <div class="cabeceraBloque">
								            <div class="iconBlock">
								            	<svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.402 3.96667L7.93939 5.355L10.6557 7.93333H-0.000488281V9.91667H10.6557L7.93939 12.495L9.402 13.8833L14.6256 8.925L9.402 3.96667ZM18.8045 15.8667H10.4467V17.85H18.8045C19.9537 17.85 20.8939 16.9575 20.8939 15.8667V1.98333C20.8939 0.8925 19.9537 0 18.8045 0H10.4467V1.98333H18.8045V15.8667Z" fill="white"></path>
												</svg>
											</div>
								            <p>Upcoming check-ins (36)</p>
								        </div>
								        <div class="bodyBloque hidden">
											<div id="wlvProximosCheckins" class="WAjaxListView ">
												<table class="wListView" style="display:" cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
											        <tbody>
											            <tr>
											                <td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16237751&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
															    <div class="genericRow">
															        <div class="contentRow">
															            <span class="clientName columnRight">Precious  Oleh </span><span class="dateCheckin genericFormat columnLeft">enter 29/09</span>
															        </div>
															        <div class="contentRow">
															            <span class="propertyName genericFormat columnRight">4 bedroom apartment_Ologolo Lekki_ North Unboxed</span><span class="timeCheckin genericFormat columnLeft">00:00</span>
															        </div>
															    </div>
															</td>
											            </tr>
											            <tr>
											            	<td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16270524&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
															    <div class="genericRow">
															        <div class="contentRow">
															            <span class="clientName columnRight">99 edge Apartments  </span><span class="dateCheckin genericFormat columnLeft">enter 29/09</span>
															        </div>
															        <div class="contentRow">
															            <span class="propertyName genericFormat columnRight">1 Bedroom Apt - Lekki Phase 1-Tobi Adejuwon (inver</span><span class="timeCheckin genericFormat columnLeft">00:00</span>
															        </div>   
															    </div>
															</td>
											            </tr>
											            <tr>
											                <td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16298669&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
															    <div class="genericRow">
															        <div class="contentRow">
															            <span class="clientName columnRight">99 edge Apartments  </span><span class="dateCheckin genericFormat columnLeft">enter 29/09</span>
															        </div>
															        <div class="contentRow">
															            <span class="propertyName genericFormat columnRight">1 Bedroom Apt lekki phase 1- Tobi Adejuwon (invert</span><span class="timeCheckin genericFormat columnLeft">00:00</span>
															        </div>
															    </div>
															</td>
											            </tr>
											            <tr>
												            <td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16333171&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
															    <div class="genericRow">
															        <div class="contentRow">
															            <span class="clientName columnRight">Enoma Agbonifo </span><span class="dateCheckin genericFormat columnLeft">enter 29/09</span>
															        </div>
															        <div class="contentRow">
															            <span class="propertyName genericFormat columnRight">3 Bed Apt 3- Lekki phase 1- Enoma Agbonifo</span><span class="timeCheckin genericFormat columnLeft">00:00</span>
															        </div>
															    </div>
															</td>
											            </tr>
											        </tbody>
											    </table>
								      	 	</div> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="row">
					<div class="col-12 col-lg-12 col-xl-12 col-xxl-6 d-flex">
						<div class="card radius-10 w-100">
						  <div class="card-body">
							<div class="row row-cols-1 row-cols-md-2 g-3 align-items-center">
							  <div class="col-lg-7 col-xl-7 col-xxl-8">
								<div class="chart-js-container4 p-4">
								   <div class="piechart-legend">
									  <h2 class="mb-1">68%</h2>
									  <h6 class="mb-0">Total Traffic</h6>
								   </div>
								  <canvas id="chart6"></canvas>
								</div>
							  </div>
							  <div class="col-lg-5 col-xl-5 col-xxl-4">
								<div class="">
								  <ul class="list-group list-group-flush">
									<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
										<i class='bx bxs-circle text-white'></i>Organic (12%)</span>
									</li>
									<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
										<i class='bx bxs-circle text-white text-opacity-75'></i><span>Direct (22%)</span>
									</li>
									<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
										<i class='bx bxs-circle text-white text-opacity-50'></i><span>Referral (34%)</span>
									</li>
									<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
										<i class='bx bxs-circle text-white text-opacity-25'></i><span>Others (18%)</span>
									</li>
									<li class="list-group-item border-0 d-flex align-items-center gap-2 bg-transparent">
										<i class='bx bxs-circle text-white text-light-1'></i><span>Social (37%)</span>
									</li>
								  </ul>
								 </div>
							  </div>
							</div>
						  </div>
						</div>
					   </div>
					   <div class="col-12 col-lg-12 col-xl-12 col-xxl-6 d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
								<div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-3">
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class="bx bxl-facebook-square"></i>
													</div>
													<h4 class="my-1">84K</h4>
													<p class="mb-0 text-light-70">Facebook Users</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class="bx bxl-twitter"></i>
													</div>
													<h4 class="my-1">34M</h4>
													<p class="mb-0 text-light-70">Twitter Followers</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class="bx bxl-linkedin-square"></i>
													</div>
													<h4 class="my-1">56K</h4>
													<p class="mb-0 text-light-70">Linkedin Followers</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class="bx bxl-youtube"></i>
													</div>
													<h4 class="my-1">38M</h4>
													<p class="mb-0 text-light-70">YouTube Subscribers</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class="bx bxl-dropbox"></i>
													</div>
													<h4 class="my-1">28K</h4>
													<p class="mb-0 text-light-70">Dropbox Users</p>
												</div>
											</div>
										</div>
									</div>
									<div class="col">
										<div class="card radius-10 mb-0 shadow-none border bg-transparent">
											<div class="card-body">
												<div class="text-center">
													<div class="widgets-icons rounded-circle mx-auto bg-light text-white mb-3"><i class='bx bxl-dribbble'></i>
													</div>
													<h4 class="my-1">49K</h4>
													<p class="mb-0 text-light-70">Dribbble Users</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="row row-cols-1 row-cols-md-1">
					<div class="col col-lg-8">
						<div class="card radius-10">
							<div class="card-body">
								<div id="geographic-map"></div>
							</div>
						</div>
					</div>
					<div class="col col-lg-4">
						<div class="card radius-10 overflow-hidden">
							<div class="card-header p-3">
								<div class="d-lg-flex align-items-center">
									<div>
										<h5 class="mb-0">Top Countries</h5>
									</div>
									<div class="ms-auto">
										<h3 class="mb-0"><span class="font-14">Total Visits:</span> 15K</h3>
									</div>
								</div>
							</div>
							<div class="dashboard-top-countries mb-3 p-3">
								<ul class="list-group list-group-flush radius-10">
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-in"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">India</h6>
											</div>
										</div>
										<div class="ms-auto">647</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-us"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">United States</h6>
											</div>
										</div>
										<div class="ms-auto">435</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-vn"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Vietnam</h6>
											</div>
										</div>
										<div class="ms-auto">287</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-au"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Australia</h6>
											</div>
										</div>
										<div class="ms-auto">432</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-dz"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Angola</h6>
											</div>
										</div>
										<div class="ms-auto">345</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-ax"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Aland Islands</h6>
											</div>
										</div>
										<div class="ms-auto">134</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-ar"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Argentina</h6>
											</div>
										</div>
										<div class="ms-auto">147</div>
									</li>
									<li class="list-group-item d-flex align-items-center radius-10 mb-2 border">
										<div class="d-flex align-items-center">
											<div class="font-20"><i class="flag-icon flag-icon-be"></i>
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-0">Belgium</h6>
											</div>
										</div>
										<div class="ms-auto">210</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
					<div class="col d-flex">
						<div class="card radius-10 w-100">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-0">Browser Statistics</h5>
									</div>
									<div class="dropdown options ms-auto">
										<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										  <i class='bx bx-dots-horizontal-rounded'></i>
										</div>
										<ul class="dropdown-menu">
										  <li><a class="dropdown-item" href="javascript:;">Action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
										</ul>
									  </div>
								 </div>
							</div>
							<div class="card-body">
								<div class="chart-js-container3">
									<canvas id="chart7"></canvas>
								</div>
							</div>
						</div>
					</div>
					<div class="col d-flex">
						<div class="card radius-10 w-100 overflow-hidden">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-0">Device Sessions</h5>
									</div>
									<div class="dropdown options ms-auto">
										<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										  <i class='bx bx-dots-horizontal-rounded'></i>
										</div>
										<ul class="dropdown-menu">
										  <li><a class="dropdown-item" href="javascript:;">Action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
										</ul>
									  </div>
								 </div>
							</div>
							<div class="card-body">
								<div class="chart-js-container2">
									<canvas id="chart8"></canvas>
								  </div>
							</div>
							<ul class="list-group list-group-flush">
								<li class="list-group-item d-flex justify-content-between align-items-center border-top bg-transparent">
								  Desktop
								  <span class="badge bg-white text-dark rounded-pill">558</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
								  Mobile
								  <span class="badge bg-white bg-opacity-50 rounded-pill">204</span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
								  Tablet
								  <span class="badge bg-white bg-opacity-25 rounded-pill">108</span>
								</li>
							  </ul>
						</div>
					</div>
					<div class="col d-flex">
						<div class="card radius-10 w-100">
						<div class="card-header">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-0">Social Traffic</h5>
									</div>
									<div class="dropdown options ms-auto">
										<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
										  <i class='bx bx-dots-horizontal-rounded'></i>
										</div>
										<ul class="dropdown-menu">
										  <li><a class="dropdown-item" href="javascript:;">Action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
										  <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
										</ul>
									  </div>
								 </div>
							   </div>
							<div class="card-body">
								<div class="d-flex mt-2 mb-4">
									<h2 class="mb-0 font-weight-bold">89,421</h2>
									<p class="mb-0 ms-1 font-14 align-self-end">Total Visits</p>
								</div>
								<div class="progress radius-10" style="height: 10px">
									<div class="progress-bar bg-white" role="progressbar" style="width: 35%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-white bg-opacity-75" role="progressbar" style="width: 20%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-white bg-opacity-50" role="progressbar" style="width: 15%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-white bg-opacity-25" role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-light" role="progressbar" style="width: 10%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table mb-0">
										<tbody>
											<tr>
												<td class="px-0">
													<div class="d-flex align-items-center">
														<div><i class="bx bxs-checkbox me-2 font-22 text-white"></i>
														</div>
														<div>Facebook</div>
													</div>
												</td>
												<td>46 Visits</td>
												<td class="px-0 text-right">33%</td>
											</tr>
											<tr>
												<td class="px-0">
													<div class="d-flex align-items-center">
														<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-75"></i>
														</div>
														<div>YouTube</div>
													</div>
												</td>
												<td>12 Visits</td>
												<td class="px-0 text-right">17%</td>
											</tr>
											<tr>
												<td class="px-0">
													<div class="d-flex align-items-center">
														<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-50"></i>
														</div>
														<div>Linkedin</div>
													</div>
												</td>
												<td>29 Visits</td>
												<td class="px-0 text-right">21%</td>
											</tr>
											<tr>
												<td class="px-0">
													<div class="d-flex align-items-center">
														<div><i class="bx bxs-checkbox me-2 font-22 text-opacity-25"></i>
														</div>
														<div>Twitter</div>
													</div>
												</td>
												<td>34 Visits</td>
												<td class="px-0 text-right">23%</td>
											</tr>
											<tr>
												<td class="px-0">
													<div class="d-flex align-items-center">
														<div><i class="bx bxs-checkbox me-2 font-22 text-light-1"></i>
														</div>
														<div>Dribbble</div>
													</div>
												</td>
												<td>28 Visits</td>
												<td class="px-0 text-right">19%</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="card radius-10">
					<div class="card-body">
						<div class="table-responsive lead-table">
							<table class="table mb-0 align-middle">
								<thead class="table-light">
									<tr>
										<th>Potential Leads</th>
										<th>Diposit</th>
										<th>Progress</th>
										<th>Last Update</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-1.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">Ronald Waters</h6>
													<p class="mb-0 font-13 text-secondary">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$89,620</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 66%"></div>
											</div>
										</td>
										<td>14 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">In Progress</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-2.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">David Buckley</h6>
													<p class="mb-0 font-13">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$38,520</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 76%"></div>
											</div>
										</td>
										<td>15 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">Cancelled</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-3.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">James Caviness</h6>
													<p class="mb-0 font-13">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$63,820</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 100%"></div>
											</div>
										</td>
										<td>16 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">Completed</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-4.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">John Roman</h6>
													<p class="mb-0 font-13">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$97,420</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 58%"></div>
											</div>
										</td>
										<td>18 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">In Progress</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-7.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">Johnny Seitz</h6>
													<p class="mb-0 font-13">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$48,360</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 66%"></div>
											</div>
										</td>
										<td>22 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">Cancelled</div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
												</div>
												<div class="">
													<img src="assets/images/avatars/avatar-8.png" class="rounded-circle" width="40" height="40" alt="">
												</div>
												<div class="ms-2">
													<h6 class="mb-0 font-14">Pauline Bird</h6>
													<p class="mb-0 font-13">Lead Designers</p>
												</div>
											</div>
										</td>
										<td>$74,620</td>
										<td class=" w-25">
											<div class="progress radius-10" style="height:4.5px;">
												<div class="progress-bar" role="progressbar" style="width: 100%"></div>
											</div>
										</td>
										<td>24 Oct 2020</td>
										<td>
											<div class="badge rounded-pill bg-light w-100">Completed</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2021. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!--start switcher-->
	<div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<p class="mb-0">Gaussian Texture</p>
			<hr>
			<ul class="switcher">
				<li id="theme1"></li>
				<li id="theme2"></li>
				<li id="theme3"></li>
				<li id="theme4"></li>
				<li id="theme5"></li>
				<li id="theme6"></li>
			</ul>
			<hr>
			<p class="mb-0">Gradient Background</p>
			<hr>
			<ul class="switcher">
				<li id="theme7"></li>
				<li id="theme8"></li>
				<li id="theme9"></li>
				<li id="theme10"></li>
				<li id="theme11"></li>
				<li id="theme12"></li>
				<li id="theme13"></li>
				<li id="theme14"></li>
				<li id="theme15"></li>
			  </ul>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<!-- <script src="assets/js/jquery.min.js"></script> -->
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!-- Vector map JavaScript -->
	<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
	<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	
	<script src="assets/plugins/chartjs/chart.min.js"></script>
	<script src="assets/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
	<script src="assets/js/dashboard-analytics.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
	<script>
		new PerfectScrollbar('.dashboard-top-countries');
	</script>
</body>


<!-- Mirrored from codervent.com/dashtreme/demo/vertical/dashboard-analytics.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 12 Sep 2022 05:30:41 GMT -->
</html>