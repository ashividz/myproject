<script type="text/javascript" src="/js/modals/break.js"></script>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">			
			<div class="pull-right">
				@include('cre.partials.index')
			</div>
			<h4>Payments</h4>
		</div>
		<div class="panel-body">
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Receipt No</th>
						<th>Entry Date</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Amount</th>
						<th>Source</th>
					</tr>
				</thead>
				<tbody>
					
			@foreach($fees AS $fee)

					<tr>
						<td>
							<a href="/lead/{{$fee->patient->lead->id}}/viewDetails" target="_blank">{{$fee->patient->lead->name or "No Name"}}</a>
						</td>
						<td>
							{{$fee->receipt_no}}
						</td>
						<td>
							{{date('jS M, Y h:i A', strtotime($fee->entry_date))}}
						</td>
						<td>
							{{date('jS M, Y', strtotime($fee->start_date))}}
						</td>
						<td>
							{{date('jS M, Y', strtotime($fee->end_date))}}
						</td>

						<td>
							₹ {{money_format($fee->total_amount, 2)}}
						</td>

						<td>
                        
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance') || Auth::user()->hasRole('marketing') )
                                {{ $fee->source->source_name or ""}}
                                <div class="pull-right">
                                    <span class="break glyphicon glyphicon-edit orange" id="{{$fee->id}}"></span>
                                </div>
                        @else
                                {{ $fee->source->channel->name or "" }}
                        @endif

						</td>
					</tr>


			@endforeach

				</tbody>

			@if($amount > 0)
				<tfoot>
					<th colspan="5">
						<h4 class="pull-right">Total : </h4>
					</td>
					<td colspan="2">
						<h4>₹ {{money_format($amount, 2)}}</h4>
					</td>
				</tfoot>
			@endif

			</table>
		</div>
	</div>
</div>