@extends('master')

@section('content')

<!-- Start Main Wrapper -->
<div id="mws-wrapper">
	<!-- Necessary markup, do not remove -->
	<div id="mws-sidebar-stitch"></div>

 
	<!-- Main Container Start -->
	<div id="mws-container" class="clearfix">
		<!-- Date Select -->



		<!-- Inner Container Start -->
		<div class="inner">

			@if ($section)
				@include($menu . "/". $section)
			@elseif($menu)
				@include($menu)
			@endif
		</div>
		<!-- Inner Container End -->
 
		
	</div>
	<!-- Main Container End -->
 
</div>
@endsection
