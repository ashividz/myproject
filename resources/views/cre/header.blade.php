<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="description" content="@yield('description')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSS -->
        <link rel="stylesheet" href="/css/font-awesome.css">
        <link rel="stylesheet" href="/css/bootstrap/bootstrap.css">
        <link rel="stylesheet" href="/css/theme/mws-style.css">
		<link rel="stylesheet" type="text/css" href="/css/icons/icol16.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/icons/icol32.css" media="screen">


        <link rel="stylesheet" href="/css/theme/mws-theme.css">
        <link rel="stylesheet" type="text/css" href="/css/fonts/icomoon/style.css" media="screen">


  		


        <!-- JS --
        <script src="/js/angular/angular.min.js"></script>
		<script src="/js/angular/angular-sanitize.js"></script>
		<script src="/js/angular/angular-route.js"></script>
		<script src="/js/angular/angular-resource.js"></script> -->

        <script src="/js/libs/jquery-1.8.3.min.js"></script>
        <script src="/js/core/mws.js"></script>

        <!-- -->

  		<script src="/js/moment.min.js"></script> 
  		
  		<script src="/js/daterangepicker.js"></script>
  		<link rel="stylesheet" type="text/css" href="/css/daterangepicker.css">

  		<!-- dataTable -->
  		<script src="/js/jquery/jquery.dataTables.js"></script> 
  		<link rel="stylesheet" type="text/css" href="/css/jquery/jquery.dataTables.css">

        

    </head>
    <body>
    	<div id="mws-header" class="clearfix">
			<!-- Logo Container -->
			<div id="mws-logo-container">
				<!-- Logo Wrapper, images put within this wrapper will always be vertically centered -->
				<div id="mws-logo-wrap">
					<img src="/images/logo.jpg" alt="mws admin" />
				</div>
			</div>

			
		 
			<!-- User Tools (notifications, logout, profile, change password) -->
			<div id="mws-user-tools" class="clearfix">
		 
				<!-- Notifications -->
				<div id="mws-user-notif" class="mws-dropdown-menu">
					<a href="#" data-toggle="dropdown" class="mws-dropdown-trigger"><i class="icon-exclamation-sign"></i></a>
		 
					<!-- Unread notification count -->
					<span class="mws-dropdown-notif">0</span>
		 
					<!-- Notifications dropdown -->
					<div class="mws-dropdown-box">
						<div class="mws-dropdown-content">
							<ul class="mws-notifications">
								<!-- Here goes all the messages -->
							</ul>
							<div class="mws-dropdown-viewall">
								<a href="#">View All Notifications</a>
							</div>
						</div>
					</div>
				</div>
		 
				<!-- Messages -->
				<div id="mws-user-message" class="mws-dropdown-menu">
					<a href="#" data-toggle="dropdown" class="mws-dropdown-trigger"><i class="icon-envelope"></i></a>
		 
					<!-- Unred messages count -->
					<span class="mws-dropdown-notif">0</span>
		 
					<!-- Messages dropdown -->
					<div class="mws-dropdown-box">
						<div class="mws-dropdown-content">
							<ul class="mws-messages">
								<!-- Here goes all the messages -->
							</ul>
							<div class="mws-dropdown-viewall">
								<a href="#">View All Messages</a>
							</div>
						</div>
					</div>
				</div>
		 
				<!-- User Information and functions section -->
				<div id="mws-user-info" class="mws-inset">
		 
					<!-- User Photo -->
					<div id="mws-user-photo">
						<img src="/images/profile.jpg" alt="User Photo" />
					</div>
		 
					<!-- Username and Functions -->
					<div id="mws-user-functions">
						<div id="mws-username">Hello, {{ Auth::user()->name }}</div>
						<ul>
							<li><a href="#">Profile</a></li>
							<li><a href="{{ url('/password/reset') }}">Change Password</a></li>
							<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

