<div class="container">  
	<div class="panel panel-default">
		<div class="panel-heading">
      <div class="pull-left">
        @include('partials/daterange')
      </div>
        <h4 style="margin-left:400px">Channel Conversion Report</h4>
		</div>
		<div class="panel-body"><!-- Nav tabs -->
      <table id="table" class="table">
    		<thead>
    			<tr>
		        <th>Source</th>
		    		<th>Leads</th>
		        <th>Conversion</th>
            <th>%</th>
            <th>Amount (₹)</th>
    			</tr>
    		</thead>
    		<tbody>

    	@foreach($leads AS $lead)
    			<tr>
    				<td>{{$lead->source_name}}</td>
            <td>{{$lead->leads}}</td>

<?php $fee = Fee::conversionCountBySource($lead->source, $start_date, $end_date) ?>

            <td>{{$fee->count()}}</td>
        
        @if($lead->leads > 0)
            <td>{{round($fee->count()/$lead->leads*100, 2)}}</td>
        @else
            <td></td>
        @endif
            <td>{{$fee->sum('total_amount')}}</td>
            
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
    "bPaginate": false,
    "aaSorting": [[ 4, "desc" ]]
  });
});
</script>