<div class="container">  
	<div class="panel panel-default">
		<div class="panel-heading">
      		<div class="pull-right">
        		@include('partials/daterange')
      		</div>
      		<h4>Daily Performance</h4>
		</div>
		<div class="panel-body">
    	<!-- Nav tabs -->
      		<ul class="nav nav-tabs" role="tablist">
        		<li role="presentation" class="active"><a href="#leads" aria-controls="leads" role="tab" data-toggle="tab">Leads</a></li>
        		<li role="presentation"><a href="#sources" aria-controls="sources" role="tab" data-toggle="tab">Sources</a></li>
        		<li role="presentation"><a href="#queries" aria-controls="queries" role="tab" data-toggle="tab">Queries</a></li>
      		</ul>

      	<!-- Tab panes -->
      		<div class="tab-content">

      		<!-- Leads -->
        		<div role="tabpanel" class="tab-pane active" id="leads">
          			<a name="download" id="downloadLeadsCsv" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Leads Csv</a>

  	    			<table id="table-leads" class="table table-bordered blocked">
		  	    		<thead>
		  	    			<tr>
		  	    				<th style="min-width:80px">Date</th>

		  	    			@foreach($sources as $source)
		  	    				<th title="{{$source->source}}">{{substr($source->source, 0, 4)}}</th>
		  	    			@endforeach

		  	    				<th>Total</th>

		  	    			</tr>
		  	    		</thead>
		  	    		<tbody>

		  	    		@foreach ($days as $day) 
		  	    			<tr>
		  	    				<td><b>{{date('m-d-Y',strtotime($day->date))}}</b></td>
							
							@foreach($sources as $source)

								<td>{{$day->leads->find($source->id)->count or "0"}}</td>

		  	    			@endforeach

		  	    				<td style="background-color:#e9e9e9"><b>{{$day->leads->sum('count')}}</b></td>
							</tr>
						@endforeach
		  	    		</tbody>
		  	    		<tfoot>
		  	    			<tr style="background-color:#e9e9e9">
		  	    				<td><b>Total</b></td>

		  	    			@foreach($sources as $source)
								<td><b>{{$days->leads->find($source->id)->count or "0"}}</b></td>
		  	    			@endforeach
		  	    				<td><b>{{$days->leads->sum('count')}}</b></td>
		  	    			</tr>
		  	    		</tfoot>
		  	  		</table>
		  	  	</div>

		  	<!-- Sources -->
        		<div role="tabpanel" class="tab-pane" id="sources">
          			<a name="download" id="downloadSourcesCsv" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Sources Csv</a>

  	    			<table id="table-sources" class="table table-bordered blocked">
		  	    		<thead>
		  	    			<tr>
		  	    				<th style="min-width:80px">Date</th>

		  	    			@foreach($sources as $source)
		  	    				<th title="{{$source->source}}">{{substr($source->source, 0, 4)}}</th>
		  	    			@endforeach

		  	    				<th>Total</th>

		  	    			</tr>
		  	    		</thead>
		  	    		<tbody>

		  	    		@foreach ($days as $day) 
		  	    			<tr>
		  	    				<td><b>{{date('m-d-Y',strtotime($day->date))}}</b></td>
							
							@foreach($sources as $source)

								<td>{{$day->sources->find($source->id)->count or "0"}}</td>

		  	    			@endforeach

		  	    				<td style="background-color:#e9e9e9"><b>{{$day->sources->sum('count')}}</b></td>
							</tr>
						@endforeach
		  	    		</tbody>
		  	    		<tfoot>
		  	    			<tr style="background-color:#e9e9e9">
		  	    				<td><b>Total</b></td>

		  	    			@foreach($sources as $source)
								<td><b>{{$days->sources->find($source->id)->count or "0"}}</b></td>
		  	    			@endforeach
		  	    				<td><b>{{$days->sources->sum('count')}}</b></td>
		  	    			</tr>
		  	    		</tfoot>
		  	  		</table>
		  	  	</div>

		  	<!-- Queries -->
		  	  	<div role="tabpanel" class="tab-pane" id="queries">
		  	  		<a name="download" id="downloadQueriesCsv" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Queries Csv</a>

  	    			<table id="table-queries" class="table table-bordered blocked">
		  	    		<thead>
		  	    			<tr>
		  	    				<th style="min-width:80px">Date</th>

		  	    			@foreach($sources as $source)
		  	    				<th title="{{$source->source}}">{{substr($source->source, 0, 4)}}</th>
		  	    			@endforeach

		  	    				<th>Total</th>

		  	    			</tr>
		  	    		</thead>
		  	    		<tbody>

		  	    		@foreach ($days as $day) 
		  	    			<tr>
		  	    				<td><b>{{date('m-d-Y',strtotime($day->date))}}</b></td>
							
							@foreach($sources as $source)

								<td>{{$day->queries->find($source->id)->count or "0"}}</td>

		  	    			@endforeach

		  	    				<td style="background-color:#e9e9e9"><b>{{$day->queries->sum('count')}}</b></td>
							</tr>
						@endforeach
		  	    		</tbody>
		  	    		<tfoot>
		  	    			<tr style="background-color:#e9e9e9">
		  	    				<td><b>Total</b></td>

		  	    			@foreach($sources as $source)
								<td><b>{{$days->queries->find($source->id)->count or "0"}}</b></td>
		  	    			@endforeach
		  	    				<td><b>{{$days->queries->sum('count')}}</b></td>
		  	    			</tr>
		  	    		</tfoot>
		  	  		</table>
		  	  	</div>
		  	</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() 
{
	$( "#downloadLeadsCsv" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-leads').table2CSV({
                delivery: 'value'
        });
    	downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  	$( "#downloadSourcesCsv" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-sources').table2CSV({
                delivery: 'value'
        });
    	downloadFile('sources.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    	$("#csv_text").val(csv_value);  
  	});

  	$( "#downloadQueriesCsv" ).bind( "click", function() 
  	{
    	var csv_value = $('#table-queries').table2CSV({
                delivery: 'value'
        });
    	downloadFile('queries.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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