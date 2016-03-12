<ul id="sidenav">
	<li{!! (($section == "partials.personal") ? " class='selected'" : "") !!}>
		<a href="/employee/{{$employee->id}}/personalDetails">Personal Details</a>
	</li>
	<li{!! (($section == "partials.contact") ? " class='selected'" : "") !!}>
		<a href="/employee/{{$employee->id}}/contactDetails">Contact Details</a>
	</li>
	<li{!! (($section == "partials.supervisor") ? " class='selected'" : "") !!}>
		<a href="/employee/{{$employee->id}}/supervisors">Report To</a>
	</li>
 	<li{!! (($section == "partials.dispositions") ? " class='selected'" : "") !!}>
 		<a href="/lead//viewDispositions">Leave Details</a>
 	</li> 
</ul>