<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title">Emails Sent</span>
			<div class="pull-right">
				@include('partials/daterange')
			</div>
		</div>	
		<div class="panel-body"><!-- Nav tabs -->
	      	<ul class="nav nav-tabs" role="tablist">
	        	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
	        	<li role="presentation"><a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">Summary</a></li>
	      	</ul>

      	<!-- Tab panes -->
      		<div class="tab-content">
        		<div role="tabpanel" class="tab-pane active" id="home">
          			<a name="download" id="downloadEmail" class="pull-right" style="margin:10px; cursor:pointer;" download="filename.csv"><i class="fa fa-download fa-2x"></i></a>
					<table class="table table-striped" id="table-email">
						<thead>
							<tr>
								<th></th>
								<th>Date</th>
								<th>To</th>
								<th>From</th>
								<th>Subject</th>
								<th>SMS</th>
								
							</tr>
						</thead>
						<tbody>
					@foreach($emails AS $email)
							<tr>
								<td>{{$i++}}</td>
								<td>{{date('d-m-Y', strtotime($email->created_at))}}</td>
								<td><a href="/lead/{{$email->lead->id or ''}}/emails" target="_blank">{{$email->lead->name or ""}}</a></td>
								<td>{{$email->user->employee->name or ""}}</td>
								<td>{{$email->template->subject or ""}}</td>
								<td title="{{$email->sms_response}}"><i class="fa {{$email->sms_response ? 'fa-check green' : 'fa-close red'}}"></i></td>
							</tr>
					@endforeach
						</tbody>
					</table>
				</div>

				<div role="tabpanel" class="tab-pane" id="summary">
          			<a name="download" id="downloadSummary" class="pull-right" style="margin:10px; cursor:pointer;"><i class="fa fa-download fa-2x"></i></a>
					<table class="table table-striped" id="table-summary">
						<thead>
							<tr>
								<th>Name</th>
								<th>Count</th>
								
							</tr>
						</thead>
						<tbody>
					@foreach($summaries AS $summary)
							<tr>
								<td>{{$summary->name or ""}}</td>
								<td>{{$summary->count}}</td>
							</tr>
					@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div><script type="text/javascript">
$(document).ready(function() 
{
	$('#table-email').dataTable({

	    "sPaginationType": "full_numbers",
	    "iDisplayLength": 100,
	    "bPaginate": false
  	});

  	$( "#downloadEmail" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-email').table2CSV({
                delivery: 'value'
            });
    	downloadFile('emails.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  	$( "#downloadSummary" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-summary').table2CSV({
                delivery: 'value'
            });
    	downloadFile('summary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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