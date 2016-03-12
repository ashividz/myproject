@include('../partials/daterange')

<table id="leads" class="table table-bordered">
	<thead>
		<tr>
			<th>#</th>		
			<th>Name</th>
			<th>Lead Entry Date</th>
			<th>Lead Assign Date</th>
			<th>CRE</th>
			<th>Lead Source</th>
			<th>Status</th>
			<th width="20%">Disposition</th>
			<th>Created By</th>
		</tr>
	</thead>
	<tbody>
	<?php $i = 0; ?>
	@foreach($leads as $lead)
	<?php $i++; ?>
		<tr>
			<td>{{ $i }}</td>
			<td>
				<a href='{{ $url }}{{ $lead->id }}/viewDetails' target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name"}}</a>
			</td>
			<td>
				{{ date('M j, Y h:i A',strtotime($lead->created_at)) }}
			</td>
			<td>
				@if( isset($lead->cre) )
					{{ date('M j, Y h:i A',strtotime($lead->cre->created_at)) }}
				@endif
			</td>
			<td>
				{{ $lead->cre->cre or "" }}
			</td>
			<td>
				{{ $lead->source->source_name or "" }}
			</td>
			<td>
				{{ $lead->status->master->status or ""}}
			</td>
			<td>
				@if(isset($lead->disposition))
					[{{ $lead->dispositions()->count()}}]
					<b>{{ $lead->disposition->master->disposition or ""}}</b> :
					{{ $lead->disposition->remarks or ""}}
					<small class="pull-right">
						<em>
							[{{date('M j, Y h:i A',strtotime($lead->disposition->created_at))}}]
						</em>
					</small>
				@endif
			</td>
			<td>
				{{ $lead->created_by or ""}}
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100	
	});
});
</script>