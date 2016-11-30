<ul id="sidenav">
  	<li{!! (($section == "partials.personal") ? " class='selected'" : "") !!}>
  		<a href="/hr/employee/{{$employee->id}}/personalDetails">Personal Details</a>
  	</li>
  	<li{!! (($section == "partials.contact") ? " class='selected'" : "") !!}>
  		<a href="/hr/employee/{{$employee->id}}/contactDetails">Contact Details</a>
  	</li>
  	<li{!! (($section == "partials.details") ? " class='selected'" : "") !!}>
  		<a href="/lead//viewDetails">Job Details</a>
  	</li>
  	<li{!! (($section == "partials.references") ? " class='selected'" : "") !!}>
  		<a href="/lead//viewReferences">Report To</a>
  	</li>
 	<li{!! (($section == "partials.dispositions") ? " class='selected'" : "") !!}>
 		<a href="/lead//viewDispositions">Leave Details</a>
 	</li> 
</ul>