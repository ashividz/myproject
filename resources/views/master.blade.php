@include('layouts.head')


<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Start Left menu area -->
    @include('layouts.sidemenu')
    <!-- End Left menu area -->
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        	<!-- Logo start -->
            @include('layouts.logo')
            <!-- Logo end -->
        	<!-- Top bar start -->
            @include('layouts.topbar')
            <!-- Top bar end -->
            <!-- User Profile start -->
            @include('layouts.userprofileicon')
            <!-- User Profile end -->
            <!-- Settings start -->
            @include('layouts.settings')
            <!-- Settings end -->
            <!-- Mobile Menu start -->
            @include('layouts.mobilemenu')
            <!-- Mobile Menu end -->
            <!-- Breadcums start -->
            @include('layouts.breadcum')
            <!-- breadcums end -->
            
    </div>
        @yield('content')
        @include('layouts.footer')
        @include('layouts.footerscript')