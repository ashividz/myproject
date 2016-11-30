<?php
	$leads = 0;
	$patients = 0;
	$amount = 0;
?>
<div class="panel panel-default">
			<div class="panel-heading" style="text-align:center">
	      		<div class="pull-left">
		    		@include('cre.partials.index')
				</div>
				<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
				<h4>Performance</h4>
			</div>	
			<div class="panel-body">
				<table id="leads" class="table table-bordered blocked">
					<thead>
						<tr>
							
					@foreach($sources AS $source)
							<th title="{{$source->source_name}}" colspan="4" style="text-align:center">{{$source->source}}</th>
					@endforeach
							<th colspan="4">Total</th>
						</tr>
					</thead>
					<tbody>

						<tr>
					@foreach($sources AS $source)
<?php 
	$leads += $source->count ;
	$patients += $source->patients;
	$amount += $source->amount;
?>
							<td>{{$source->count}}</td>
							<td><b>{{$patients}}</b></td>
							<td style="min-width:60px">{{$source->leads > 0 ? number_format($source->patients/$leads*100, 2) : "0"}} %</td>
							<td style="background-color:#ddd; min-width:80px">{{money_format('%i',$source->amount)}}</td>
					@endforeach
							<td>{{$leads}}</td>
							<td><b>{{$patients}}</b></td>
							<td style="min-width:60px"> {{$leads > 0 ? number_format($patients/$leads*100, 2) : "0"}}%</td>
							<td style="background-color:#ddd; min-width:100px"><b>{{money_format('%i',$amount)}}</b></td>
						</tr>

					</tbody>
				</table>
			</div>			
	</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#leads').table2CSV({
    			separator : ',',
                delivery: 'value'
            });
    downloadFile('cre.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
</script>