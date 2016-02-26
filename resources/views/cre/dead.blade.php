@include('cre.partials.index')

<style type="text/css">
	@media screen and (min-width: 768px) {
	
	#myModal .modal-dialog  {width:900px;}

	small {
		font-size: 10px;
		font-style: italic;
	}
}
</style>
<div style="margin:20px">
	<table id="leads" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>CRE</th>
				<th>Name</th>
				<th>Lead Entry Date</th>
				<th>Lead Assign Date</th>
				<th>Status</th>
				<th>CRM Disposition</th>
			</tr>
		</thead>
		<tbody>
<?php $i = 0; ?>
	@foreach ($leads as $lead)

		<?php 
			$i++; 
			$count = 0;
		?>

		@foreach($lead->dispositions AS $disposition)
			@if($name == $disposition->name)
				<?php $count++; ?>
			@endif
		@endforeach

		@if($lead->cre->cre == $name)
			<tr>
		    	<td>{{ $i }}</td>
		    	<td>{{ $lead->cre->cre}}</td>
		    	<td><a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name" }}</a></td>
		    	<td>{{ date('M j, Y h:i A',strtotime($lead->created_at)) }}</td>
		    	<td>{{ date('M j, Y',strtotime($lead->cre->created_at)) }}</td>
		    	<td>{{ $lead->status->master->status or "" }}</td>
		    	
		    	<td width='30%'>
		    		[ {{ $count . "/" . $lead->dispositions()->count()}} ] 
		    		<b>{{ $lead->disposition->master->disposition or "NA" }} : </b> 
		    		{{ $lead->disposition->remarks or "" }}<p>
		    		<small>

		    			{{ isset($lead->disposition) ? date('M j, Y h:i A',strtotime($lead->disposition->created_at))  : ""}}
		    		</small>
		    	</td>

		    </tr>
		@endif
	@endforeach
<?php
	$url = "http://nutri1/modules/lead/";
	$i = 0;
	$count = 0;
	/*foreach ( $dashboard->getLeadsPipelineByCRE($cre, $start_date, $end_date) as $key => $value) 
	{
		//$row = $dialer->getCallCount($value['phone'], $value['mobile'], $value['start_date']);
		//$count = $row['count'];

		$i++;
		echo "<tr>" . PHP_EOL;
		echo "<td>" . $i . "</td>" . PHP_EOL;
		echo "<td><a href='" . $url . "?clinic=" . $value['clinic'] . "&enquiry_no=" . $value['enquiry_no'] . "' target='_blank'>" . $value['name'] . "</a></td>" . PHP_EOL;
		echo "<td>" . date('M j, Y',strtotime($value['start_date'])) . "</td>" . PHP_EOL;
		echo "<td>" . date('M j, Y',strtotime($value['entry_date'])) . "</td>" . PHP_EOL;		
		echo "<td>" . $value['source'] . "</td>" . PHP_EOL;
		echo "<td>" . $value['status'] . "</td>" . PHP_EOL;
		echo "<td width='30%'><b> [";
		echo $value['calls_count'] . "] ";
		echo $value['disposition'] ? $value['disposition'] . "</b> : " . $value['remarks'] : "";
		//echo $value['callback'] ? "<p><b>Callback </b> : " . date('jS M, Y h:i A', strtotime($value['callback'])) : "";
		//echo $value['calls'] ? "<p style='text-align:right'><i class='fa fa-toggle-right'></i> <a data-toggle='modal' href='/modules/common/controls/dispositionDetails.php?clinic=" . $value['clinic'] . "&enquiry_no=" . $value['enquiry_no'] . "&start_date=" . $value['start_date'] . "' data-target='#myModal'>" . $value['calls'] . " disposition" : "";
		//echo $value['calls'] > 1 ? "s" : "";
		//echo"</a>";
		echo $value['disposition_date'] ? " <small>(" . date('M j, Y',strtotime($value['disposition_date'])) . ")</small>" : "";
		echo "</td>" . PHP_EOL;
		//echo "<td width='5%' style='text-align:center'><a data-toggle='modal' href='/modules/common/controls/dispositionDetails.php?clinic=" . $value['clinic'] . "&enquiry_no=" . $value['enquiry_no'] . "&start_date=" . $value['start_date'] . "' data-target='#myModal'>" . $value['calls'] . "</a></td>" . PHP_EOL;
		//echo "<td width='5%' style='text-align:center; valign:middle;'><a data-toggle='modal' href='/modules/common/controls/callDetails.php?phone=" . $value['phone'] . "&mobile=" . $value['mobile'] . "&start_date=" . $value['start_date'] . "' data-target='#myModal'>" . $count . "</a></td>" . PHP_EOL;
		echo "</tr>" . PHP_EOL;
	}*/
?>			
		</tbody>
	</table>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Call Details</h4>

            </div>
            <div class="modal-body"><div class=""></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100,
		"bInfo" : true,
	});
});
</script>