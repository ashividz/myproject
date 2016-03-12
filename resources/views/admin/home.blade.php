@include('../partials/header')
@include('../partials/menu')

<!-- Start Main Wrapper -->
<div id="mws-wrapper">
	<!-- Necessary markup, do not remove -->
	<div id="mws-sidebar-stitch"></div>
 
	<!-- Sidebar Wrapper --
	<div id="mws-sidebar">
 
		<!-- Hidden Nav Collapse Button --
		<div id="mws-nav-collapse">
			<span></span>
			<span></span>
			<span></span>
		</div>
 
		<!-- Searchbox --
		<div id="mws-searchbox" class="mws-inset">
			<form action="typography.html">
				<input type="text" class="mws-search-input" placeholder="Search..." />
				<button type="submit" class="mws-search-submit"><i class="icon-search"></i></button>
			</form>
		</div>
 
		<!-- Main Navigation --
		<div id="mws-navigation">
			
		@include('cre/navigation')

		</div>
	</div> -->
 
	<!-- Main Container Start -->
	<div id="mws-container" class="clearfix">

		<!-- Inner Container Start -->
		<div class="container1">

			@include($menu . "/". $section)
			
		</div>
		<!-- Inner Container End -->
 
		<!-- Footer -->
		<div id="mws-footer">
			@include('partials/footer')
		</div>
	</div>
	<!-- Main Container End -->
 
</div>
<style type="text/css">
	#mws-sidebar {
		margin-top: 37px;
		position: fixed;
	}
	#mws-container {
		margin-top: 37px;
	}
	#mws-datarange {
		padding: 15px 50px;
		float: left;
		position: relative;
	}
	#daterange {
		border:none;
		background:none;
		background-color: rgba(64, 78, 108, 1);
		color:#fff;
		padding-left:30px;
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.5);
	}
</style>
<!--
<div id="mws-stylesheet-holder">
	<style type="text/css">
		body 
		{
			margin: 0;
		}, 
		#mws-container
		{
			background-image:url('/images/core/bg/diamonds.png');
		}

		#mws-sidebar
		{
			height: auto;
			position: fixed;
		}, 
		#mws-sidebar-bg, 
		#mws-header
		{
			
		}, 
		.mws-panel .mws-panel-header, 
		#mws-login, 
		#mws-login .mws-login-lock, 
		.ui-accordion .ui-accordion-header, 
		.ui-tabs .ui-tabs-nav, 
		.ui-datepicker, 
		.fc-event-skin, 
		.ui-dialog .ui-dialog-titlebar, 
		.jGrowl .jGrowl-notification, .jGrowl .jGrowl-closer, 
		#mws-user-tools .mws-dropdown-menu .mws-dropdown-box, 
		#mws-user-tools .mws-dropdown-menu.open .mws-dropdown-trigger
		{
			background-color:#35353a;
		}

		#mws-header
		{
			border-color:#c5d52b;
			height: 62px;
		}

		.mws-panel .mws-panel-header span, 
		#mws-navigation ul li.active a, 
		#mws-navigation ul li.active span, 
		#mws-user-tools #mws-username, 
		#mws-navigation ul li .mws-nav-tooltip, 
		#mws-user-tools #mws-user-info #mws-user-functions #mws-username, 
		.ui-dialog .ui-dialog-title, 
		.ui-state-default, 
		.ui-state-active, 
		.ui-state-hover, 
		.ui-state-focus, 
		.ui-state-default a, 
		.ui-state-active a, 
		.ui-state-hover a, 
		.ui-state-focus a
		{
			color:#c5d52b;
			text-shadow:0 0 6px rgba(197, 213, 42, 0.5);
		}

		#mws-searchbox .mws-search-submit, 
		.mws-panel .mws-panel-header .mws-collapse-button span, 
		.dataTables_wrapper .dataTables_paginate .paginate_disabled_previous, 
		.dataTables_wrapper .dataTables_paginate .paginate_enabled_previous, 
		.dataTables_wrapper .dataTables_paginate .paginate_disabled_next, 
		.dataTables_wrapper .dataTables_paginate .paginate_enabled_next, 
		.dataTables_wrapper .dataTables_paginate .paginate_active, 
		.mws-table tbody tr.odd:hover td, 
		.mws-table tbody tr.even:hover td, 
		.ui-slider-horizontal .ui-slider-range, 
		.ui-slider-vertical .ui-slider-range, 
		.ui-progressbar .ui-progressbar-value, 
		.ui-datepicker td.ui-datepicker-current-day, 
		.ui-datepicker .ui-datepicker-prev, 
		.ui-datepicker .ui-datepicker-next, 
		.ui-accordion-header .ui-accordion-header-icon, 
		.ui-dialog-titlebar-close
		{
			background-color:#c5d52b;
		}
	</style>
</div>-->
	
	