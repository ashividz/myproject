<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
            <span>
                @include('../cre/partials/index')
            </span>
		</div>
		<div class="panel-body">
			<table id="table" class="table table-bordered">
                <thead>
    				<tr>
                        <th>Name</th>
    					<th>Payment Date</th>
    					<th>CRE</th>
    					<th width="45%">Remarks</th>
    					<th width="10%">Status</th>
    				</tr>
                </thead>
                <tbody>

    			@foreach($leads as $lead)
    			<?php 

    				$hot = "<i class='fa fa-fire' title='HOT' style='color:red'></i>";

    				$converted = "<i class='fa fa-check' title='CONVERTED' style='color:green'></i>";
    					

    			?>
    				<tr>
                        <td><a href='{{ $url }}{{ $lead->id }}/viewDispositions' target="_blank">{{ $lead->name }}</a></td>
    					<td>{{ $lead->disposition->callback or "" }}</td>
    					<td>{{ $lead->cre->cre or "" }}</td>
    					
    					<td>
                    @if($lead->disposition)
                            {{ $lead->disposition->remarks }}
                            <em><small>

                            <span class="pull-right">
                               {{ $lead->disposition->name or "" }} @ {{ $lead->disposition->created_at->format('jS M, Y h:i A') }}
                            </span>
                            </small></em>
                    @endif
                        </td>
    					<td style='text-align:center'>
    						@if(isset($lead->patient->fees))
    							@foreach($lead->patient->fees AS $fee)
    								@if($fee->entry_date >= $lead->disposition->callback->format('Y-m-d'))
    									<i class='fa fa-check fa-2x' title='CONVERTED : {{date('M j, Y',strtotime($fee->entry_date))}}' style='color:green'></i>
    								@else
    									<i class='fa fa-certificate' title='Past Payment : {{date('M j, Y',strtotime($fee->entry_date))}}' style='color:blue'></i>
    								@endif	
    							@endforeach
    						@else
    							<i class='fa fa-fire fa-2x' title='HOT' style='color:red'></i>
    						@endif
    					</td>
    				</tr>
    			@endforeach
                </tbody>
				
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table').dataTable({
        "iDisplayLength": 100,
        "bPaginate": false,
        "aaSorting": [[ 1, "desc" ]]
    }); 
}); 
</script>


