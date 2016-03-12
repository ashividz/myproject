<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">
				@include('/partials/daterange') 
			</div>
			<h4>Program Date</h4> 
		</div>
		<div class="panel-body">
			<table id="example" class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th width="5%">#</th>
		                <th width="15%">Name</th>
				        <th width="10%">Nutritionist</th>
		                <th width="10%">CRE</th>
		                <th width="15%">Start Date</th>
		                <th width="15%">End Date</th>
		                <th width="20%">Score</th>
		           </tr>
		        </thead>

		        <tbody>
		    @foreach($patients as $patient) 
		            <tr>
		                <td>{{$i++}}</td>
		                <td><a href="/quality/patient/{{$patient->id}}/survey" target="_blank">{{$patient->lead->name}}</a></td>
		                <td>{{$patient->nutritionist}}</td>
		                <td>{{$patient->lead->cre->cre or ""}}</td>
		                <td>{{date('M j, Y',strtotime($patient->start_date))}}</td>
		                <td>{{date('M j, Y',strtotime($patient->end_date))}}</td>
		                <td>
		                	<ul>
				        @foreach($patient->surveys as $survey)
				                <li><a href="/patient/{{$patient->id}}/survey" target="_blank">{{$survey->score or ""}}</a><small><em><span class="pull-right">[{{date('M j, Y h:i A',strtotime($survey->created_at))}}]</span></em></small></li>
				        @endforeach
		        			</ul>
		                </td>
			   		</tr>
			@endforeach
		        </tbody>
		    </table>
		</div>
	</div>