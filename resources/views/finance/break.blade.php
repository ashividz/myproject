<script type="text/javascript" src="/js/modals/break.js"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title">Search</h1>
		</div>
		<div class="panel-body">
			<div class="col-md-4">
				<form method="POST" action="/finance/breakAdjust" class="form-inline">
					<input name="patient" class="form-control">
					<button type="submit" class="btn btn-primary">Search</button>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">	
				</form>
				<div class="searchFor">
					@if(isset($search))
						<b>Search For :</b> {{$search}}
					@endif
				</div>
			</div>
			<div class="col-md-8">
		
			@if(isset($patients))
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Lead Id</th>
							<th>Enquiry No</th>
							<th>Registration No</th>
						</tr>
					</thead>
					<tbody>
				@foreach($patients AS $patient)
						<tr>
							<td></td>
							<td>
								<a href="/finance/breakAdjust/patient/{{$patient->id}}"> {{$patient->lead->name or ""}}</a>
							</td>				
							<td>{{$patient->lead_id or ""}}</td>
							<td>{{$patient->clinic }} - {{$patient->enquiry_no }}</td>
							<td>{{$patient->registration_no or ""}}</td>	
						</tr>
				@endforeach
					</tbody>
					
				</table>			
			
			@endif

			@if(isset($patient) && !isset($patients))
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Lead Id</th>
							<th>Enquiry No</th>
							<th>Registration No</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td>
								<a href="/finance/breakAdjust/patient/{{$patient->id}}"> {{$patient->lead->name or ""}}</a>
							</td>				
							<td>{{$patient->lead_id or ""}}</td>
							<td>{{$patient->clinic }} - {{$patient->enquiry_no }}</td>
							<td>{{$patient->registration_no or ""}}</td>	
						</tr>
					</tbody>
					
				</table>		
				
			@endif

			</div>
		</div>
	</div>

@if(isset($patient->fees))
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title">Fees</h1>
		</div>
		<div class="panel-body">		
				<table class="table">
					<thead>
						<tr>
							<th>Entry Date</th>
							<th>Receipt No</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Amount</th>
							<th>Remark</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
				@foreach($patient->fees AS $fee)
						<tr>
							<td>{{ date('jS M, Y', strtotime($fee->entry_date)) }}</td>
							<td>{{ $fee->receipt_no }}</td>	
							<td>{{ date('jS M, Y', strtotime($fee->start_date)) }}</td>	
							<td>{{ date('jS M, Y', strtotime($fee->end_date)) }}</td>
							<td>₹ {{ $fee->total_amount }}</td>
							<td>{{ $fee->remark }}</td>
							<td>
								<span class="break glyphicon glyphicon-edit orange" id="{{$fee->id}}"></span>
							</td>		
						</tr>
				@endforeach
					</tbody>
					
				</table>
				
		
			</div>
		</div>
	</div>
@endif
</div>