<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Nutrihealth</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSS -->
        <link rel="stylesheet" href="/plugins/font-awesome/font-awesome.min.css">
        <link rel="stylesheet" href="/plugins/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="/css/theme/mws-style.css">
		<link rel="stylesheet" type="text/css" href="/css/icons/icol16.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/css/icons/icol32.css" media="screen">


        <link rel="stylesheet" href="/css/theme/mws-theme.css">
        <link rel="stylesheet" type="text/css" href="/css/fonts/icomoon/style.css" media="screen">

        
  		<script type="text/javascript" src="/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/plugins/bootstrap/bootstrap.min.js"></script>

        <!-- jQuery Ui -->
        <script type="text/javascript" src="/plugins/jquery-ui/jquery-ui.js"></script>
        <link rel="stylesheet" href="/plugins/jquery-ui/jquery-ui.css">
        <!-- JS --
        <script src="/js/angular/angular.min.js"></script>
		<script src="/js/angular/angular-sanitize.js"></script>
		<script src="/js/angular/angular-route.js"></script>
		<script src="/js/angular/angular-resource.js"></script> -->

        
        <script src="/js/core/mws.js"></script>

        <!-- -->

  		<script src="/js/moment.min.js"></script> 
  		
  		<script src="/js/daterangepicker.js"></script>
  		<link rel="stylesheet" type="text/css" href="/css/daterangepicker.css">

  		<!-- dataTable -->
  		<script src="/js/jquery/jquery.dataTables.js"></script> 
  		<link rel="stylesheet" type="text/css" href="/css/jquery/jquery.dataTables.css">

  		<link rel="stylesheet" href="/css/main.css">
  		<link rel="stylesheet" href="/css/menu.css">

  		<!-- jQuery Raty A Star Rating Plugin-->
        <script src="/js/jquery/jquery.raty.js"></script> 
        <link rel="stylesheet" href="/css/jquery/jquery.raty.css">

        <!-- jQuery DateTime Picker Plugin-->
        <script src="/js/jquery/jquery.datetimepicker.js"></script> 
        <link rel="stylesheet" href="/css/jquery/jquery.datetimepicker.css">

        <!-- jQuery JEditable Plugin-->
        <script src="/js/jquery/jquery.jeditable.js"></script>
        <script src="/js/jquery/jquery.jeditable.datepicker.js"></script> 

        <!-- jQuery SimpleModal Plugin-->
        <script type="text/javascript" src="/js/jquery/jquery.simplemodal.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/modal.css">

		<!-- jQuery WebUI Popover Plugin-->
		<link rel="stylesheet" type="text/css" href="/css/jquery.webui-popover.css">
		<script type="text/javascript" src="/js/jquery.webui-popover.js"></script>

		<!-- HTML Table to CSV Plugin-->
		<script src="/js/table2CSV.js"></script> 

		<!-- HighCharts Plugin-->
		<script type="text/javascript" src="/js/highcharts/highcharts.js"></script>
		<script type="text/javascript" src="/js/highcharts/highcharts-3d.js"></script>
		<script type="text/javascript" src="/js/highcharts/modules/data.js"></script>
		<script type="text/javascript" src="/js/highcharts/highcharts-more.js"></script>
		<script type="text/javascript" src="/js/highcharts/modules/funnel.js"></script>


		<!-- Jquery Player 

		<script type="text/javascript" src="/js/jquery.jplayer.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jplayer.blue.monday.min.css">-->
		<!-- Bootstrap Switch -->
		<script type="text/javascript" src="/js/bootstrap-switch.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap-switch.min.css">

		<!-- Select 2 -->
		<script type="text/javascript" src="/js/select2.full.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/select2.min.css">

        <!-- VueJS -->
        <script src="/plugins/vue/vue.min.js"></script>
        <script src="/plugins/vue/vue-resource.min.js"></script>
    </head>
    <body>
        <div id="alert" class="alert alert-{{Session::get('status')=='success'?'success':'danger'}}">

            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            @if(Session::has('message')) 
                <h5>{!! Session::get('message') !!}</h5>
            @elseif(count($errors) > 0)
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

<style type="text/css">
    #alert, #loader {
        position: fixed;
        margin-top: 150px;
        width: 500px;
        left: 30%;
        z-index: 9999;
        text-align: center;
    }
</style>
<script type="text/javascript">
    @if (Session::has('message') || count($errors) > 0)
        $('#alert').show();
    @if(Session::get('status')=='success')
        setTimeout(function()
        {
            $('#alert').slideUp('slow').fadeOut(function(){});
        }, 3000);
    @endif
        
    @else
        $("#alert").hide();
    @endif
</script>
    <div style="">
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
					<span class="mws-dropdown-notif">
                        @{{ unreadNotificationCount }}               
                    </span>
		 
					<!-- Notifications dropdown -->
					<div class="mws-dropdown-box">
						<div class="mws-dropdown-content">
							<ul class="mws-notifications">
								<!-- Here goes all the messages -->
							</ul>
							<div class="mws-dropdown-viewall">
                                <div v-for="notification in notifications" style="background-color: #f9f9f9; border: 1px solid #ddd">                                    
                                    
                                    <span v-if="notification.lead_id">
                                        <a href="/lead/@{{ notification.lead_id}}" target="_blank">
                                            @{{ notification.subject }}
                                            (@{{ notification.lead_id}})
                                            <em class="pull-right">
                                                @{{ notification.created_at }}
                                            </em>

                                        </a>
                                    </span>
                                    <span v-else>
                                        @{{ notification.subject }}
                                        <em class="pull-right">
                                            @{{ 
                                            notification.created_at }}
                                        </em>                                    
                                    </span>
                                </div>
								<a href="notifications">View All Notifications</a>
							</div>
						</div>
					</div>
				</div>
		 
				<!-- Messages -->
				<div id="mws-user-message" class="mws-dropdown-menu">
					<a href="#" data-toggle="dropdown" class="mws-dropdown-trigger"><i class="icon-envelope"></i></a>
		 
					<!-- Unred messages count -->
					<span class="mws-dropdown-notif">@{{ unreadMessageCount }}</span>
		 
					<!-- Messages dropdown -->
					<div class="mws-dropdown-box">
						<div class="mws-dropdown-content">
							<ul class="mws-messages">
								<!-- Here goes all the messages -->
								
							</ul>
							<div class="mws-dropdown-viewall">
								<a href="/message/inbox">Inbox</a>
								<a href="/message/compose">Compose</a>
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
						<div id="mws-username">Hello, {{ Auth::user()->employee->name }}</div>
						<ul>
							<li><a href="{{ url('/profile') }}">Profile</a></li>
							<li><a href="{{ url('/password/change') }}">Change Password</a></li>
							<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
			@include('partials/menu')		
    </div>
<script>
@if (count($errors) > 0 || Session::has('status'))
	$('#alert').show().slideUp().delay().slideDown();
@endif
</script>