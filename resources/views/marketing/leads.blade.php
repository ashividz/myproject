<div class="panel panel-default">
	<div class="panel-heading">
		<!-- <a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x"></i></a> -->
		@include('../partials/daterange')
	</div>
	<div class="panel-body">
		<table id="table-leads" class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>		
					<th>Name</th>
					<th>Lead id</th>
					<th>State</th>
					<th>Country</th>
					<th>Lead Assign Date</th>
					<th>CRE</th>
					<th>Lead Source</th>
					<th>Status</th>
					<th width="30%">Disposition</th>
					<th>latest fee daterange</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			@foreach($leads as $lead)
				<tr>
					<td>{{ $i++ }}</td>
					<td><a href='/lead/{{ $lead->id }}/viewDetails' target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name"}}</a></td>
					<td>{{$lead->id or ""}}</td>
					<td>{{$lead->state or ""}}</td>
					<td>{{$lead->country or ""}}</td>
					<td style="width: 130px;">{{ $lead->cre->created_at or "" }}</td>
					<td>{{ $lead->cre->cre or "" }}</td>

					<td style="min-width: 100px;">{{ $lead->source->master->source_name or "" }}</td>

					<td style="width: 120px;"><div style="display:none">{{$lead->status_id}} {{$lead->status->name or ""}}</div><div class="status" data-score="{{ $lead->status_id }}" number="{{$lead->status_id > 5 ? $lead->status_id : 5}}"></div></td>

					<td>[{{ $lead->dispositions()->count()}}]<b> {{ $lead->disposition->master->disposition_code or ""}}</b> :{{ $lead->disposition->remarks or ""}}<small class="pull-right"><em>[{{$lead->disposition->created_at or ""}}]</em></small></td>

					<td>{{($lead->patient && $lead->patient->fee) ? date('Y-m-d',strtotime($lead->patient->fee->entry_date))  :''}}</td>

					<td style="text-align:center"><div class="info" data-html="true" data-toggle="popover" title="INFO" data-content="<b>Created By</b> : {{$lead->created_by}}<p><b>Created at </b>: {{ date('M j, Y h:i A',strtotime($lead->created_at)) }}" data-placement="left"><i class="fa fa-info-circle"></i></div></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

<style type="text/css">
	.status {
		width: 120px;
	}
</style>
		
<script type="text/javascript">
$(document).ready(function() 
{
	$('#leads').dataTable({
		"iDisplayLength": 100	
	});

	$(".info").popover({ trigger: "hover" });
	$(".date").popover({ trigger: "hover" });

	$( "#downloadCSV" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-leads').table2CSV({
                delivery: 'value'
            });
    	downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  	function downloadFile(fileName, urlData){
    	var aLink = document.createElement('a');
    	var evt = document.createEvent("HTMLEvents");
   	 	evt.initEvent("click");
    	aLink.download = fileName;
    	aLink.href = urlData ;
    	aLink.dispatchEvent(evt);
	}
});
</script>
<script type="text/javascript">
	$('.status').raty({ 
		readOnly: true,
		hints : [
@foreach($statuses as $status)
		'{{$status->name}}',
@endforeach
		],
		score: function() {
            return $(this).attr('data-score');
        },
        number: function() {
            return $(this).attr('number');
        },
	});
</script>
<script>
$(document).ready(function() 
{
    
    $('#table_yuwow_usage').dataTable({
        bPaginate : false
    });
});
</script>
</script>
<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src = "//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table-leads').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );
</script>