<div class="container">
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">
		    	<h1 class="panel-title">{{$employee->name}}</h1>
		  	</div>
		  	<div class="panel-body">
		  		<div class="imageHolder">
		  			<img src="">
		  		</div>			  	
		  	</div>
		</div>
		<div id="sidebar" style="width:100%">
		  	@include('employee.partials.sidenavbar')
		</div>
	</div>
		
	<div class="col-lg-10 col-md-10 col-sm-10">
		@yield('main')
	</div>
</div>