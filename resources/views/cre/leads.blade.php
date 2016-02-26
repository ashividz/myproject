@include('cre.partials.index')
<div class="container1">
	<table id="leads" class="table table-striped">
		<thead>
			<tr>
                <th>Name</th>
				<th>Lead Entry Date</th>
				<th>Lead Assign Date</th>
				<th>Lead Source</th>
				<th>Status</th>
				<th>CRM Disposition</th>
			</tr>
		</thead>
		<tbody>

	@foreach ($leads as $lead)

			<tr><td><a href="{{$url}}{{$lead->id}}/viewDispositions" target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name" }}</a></td>
		    	<td>{{ $lead->created_at }}</td>
		    	<td>{{ $lead->cre ? $lead->cre->created_at:'' }}</td>

		@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
		    	<td>{{ $lead->source->master->source_name or "" }}</td>
		@else
				<td>{{ $lead->source->master->channel->name or "" }}</td>
		@endif
		
		    	<td><div style="display:none">{{$lead->status_id}} {{$lead->status->name or ""}}</div><div class="status" data-score="{{ $lead->status_id }}"></div></td>
		    	
		    	<td width='30%'>
		    		[ {{ $lead->dispositions->where('name', $name)->count() . "/" . $lead->dispositions()->count()}} ] 
		    		<b>{{ $lead->disposition->master->disposition or "NA" }} : </b> 
		    		{{ $lead->disposition->remarks or "" }}<p>
		    		<small>

		    			{{ isset($lead->disposition) ? date('M j, Y h:i A',strtotime($lead->disposition->created_at))  : ""}}
		    		</small>
		    	</td>

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
		"bInfo" : true,
        "aaSorting": [[ 2, "desc" ]]
	});
});
</script>

<script type="text/javascript">
	$('.status').raty({ 
		readOnly: true,
		hints : ['New', 'Explanined', 'Follow Up', 'Hot', 'Converted'],
		score: function() {
            return $(this).attr('data-score');
        },
		starOn  : '/images/raty/star-on.png',
		starOff  : '/images/raty/star-off.png',
	});
</script>