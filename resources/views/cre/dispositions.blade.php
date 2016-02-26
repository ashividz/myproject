@include('cre.partials.index')
<div style="margin:20px">
	<table id="leads" class="table table-striped table-bordered table-hover table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th width="15%">Date</th>
				<th width="15%">Name</th>
				<th>Remarks</th>
				<th width="20%">Disposition</th>
				<th width="15%">Callback</th>
			</tr>
		</thead>
		<tbody>
<?php
	$i = 0;
?>

	@foreach ($dispositions as $disposition)
		<?php $i++ ; ?>
		<tr>
			<td>{{ $i }}</td>
			<td>{{ date('M j, Y h:i a', strtotime($disposition->created_at)) }} </td>
			<td><a href='{{ $url }}?clinic={{ $disposition->clinic }}&enquiry_no={{ $disposition->enquiry_no }}' target='_blank'>{{ $disposition->name }} </a>
			<td>{{ $disposition->remarks }}</td>
			<td>{{ $disposition->disposition }}</td>
			<td>{{ $disposition->callback ? date('M j, Y h:i a', strtotime($disposition->callback)) : ""}}</td>
		</tr>
	
	@endforeach

	</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100,
	});
});
</script>