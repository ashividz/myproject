	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2">
			<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h1 class="panel-title">{{$employee->name}}</h1>
			  	</div>
			  	<div class="panel-body">
			  		<div class="imageHolder">
			  			<img src="http://localhost/orangehrm/symfony/web/index.php/pim/viewPhoto/empNumber/1">
			  		</div>			  	
			  	</div>
			</div>
			<div id="sidebar" style="width:100%">
			  	@include('hr.partials.sidenavbar')
			</div>
		</div>
		<div class="col-lg-10 col-md-10 col-sm-10" id="form-element">
			@yield('main')
		</div>
	</div>