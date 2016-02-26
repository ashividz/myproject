@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))

<div class="container">
	<div class="panel">
		<div class="panel-heading">			
		</div>
		<div class="panel-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Lead Id</th>
						<th>Patient Id</th>
						<th>Name</th>
						<th>Entry Date</th>
						<th>CRE</th>
						<th>Source</th>
						<th>Status</th>
						<th>Last Disposition</th>
					</tr>
				</thead>
				<tbody>					
					<tr>
						<td>{{$lead->id}}</td>
						<td>{{$lead->patient->id or 'NA'}}</td>
						<td><a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{$lead->name}}</a></td>
						<td>{{$lead->created_at}}</td>
						<td>{{$lead->cre->cre or "NA"}} ({{$lead->cres->count()}})</td>
						<td>{{$lead->source->master->source_name or ""}} ({{$lead->sources->count()}})</td>
						<td>{{$lead->status->master->status or "NA"}}</td>
						<td>{{$lead->disposition->remarks or "NA"}} ({{$lead->dispositions->count()}})</td>
					</tr>
				</tbody>
			</table>
			<div class="container">				
				<form class="form-inline" method="post">
					<div class="form-group">
						Remark : <textarea name="remark" cols="30"></textarea>
					</div>					
					<div class="form-group">
						<button class="btn btn-danger">Apply DNC</button>
						<input type="hidden" name="id" value="{{ $lead->id }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif