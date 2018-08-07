<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
				<h4>VediqueDiet Leads</h4>
			</div>	
			<div class="panel-body">			
				<!-- <form id="form" class="form-inline" action="/marketing/leads/churn/save" method="post"> -->
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<td>#</td>
								<td>phone</td>
                                <td>Email</td>
								<td>Created_at</td>
								<td>Source</td>
								
							</tr>
						</thead>
						<tbody>
					
					@foreach($leads AS $lead)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$lead->phone or " "}}</td>
						<td>{{$lead->email or " "}}</td>								
						<td>{{$lead->created_at or " "}}</td>	
						<td>Vedique Diet</td>	
					</tr>
					@endforeach

						</tbody>
					</table>
					
				<!-- </form> -->
			</div>			
	</div>
	
</div>
