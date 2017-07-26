<div class="panel panel-default">
	<div class="panel-heading">
  		<div class="pull-right">
  			@include('partials.daterange')
		</div>
		<h4>Payment Details</h4>
	</div>
	<div class="panel-body"><!-- Nav tabs -->
      	<ul class="nav nav-tabs" role="tablist">
        	<li role="presentation" class="active">
        		<a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a>
        	</li>
	        <li role="presentation">
	        	<a href="#packages" aria-controls="packages" role="tab" data-toggle="tab">Summary Packages</a>
	        </li>
	    </ul>

      	<!-- Tab panes -->
      	<div class="tab-content">
        	<div role="tabpanel" class="tab-pane active" id="home">

		<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>

				<table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th>Patient Id</th>
							<th>Name</th>
							<th>Country</th>
							<th>State</th>
							<th>City</th>
							<th>Duration</th>
							<th>Entry Date</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Amount</th>
							<th>Source</th>
							<th>Cre</th>
							<th>Nutritionist</th>
							<th>Audit</th>
						</tr>
					</thead>
					<tbody>

@foreach($fees AS $fee)
						<?php
							$amount = 0;
							if($fee->currency_id ==2)
							{
								$amount = $fee->total_amount*65;
							}
							else {
								$amount = $fee->total_amount;
							}
						?>
						<tr>
							<td>{{$fee->patient_id or ""}}</td>
							<td><a href="/lead/{{$fee->patient->lead->id or ""}}/viewDetails" target="_blank">{{$fee->patient->lead->name or ""}}</a></td>
							<td>{{$fee->patient->lead->country or ""}}</td>
							<td>{{$fee->patient->lead->region->region_name or ""}}</td>
							<td>{{$fee->patient->lead->city or ""}}</td>
							<td>{{$fee->valid_months or ""}}</td>
							<td>{{isset($fee->entry_date) ? date('Y-m-d', strtotime($fee->entry_date)) : ""}}</td>
							<td>{{$fee->start_date->format('Y-m-d')}}</td>
							<td>{{$fee->end_date->format('Y-m-d')}}</td>
							<td>{{$amount}}</td>
							<td>{{$fee->source->source_name or ""}}</td>
							<td>{{$fee->cre or ""}}</td>
							<td>{{$fee->patient->nutritionist or ""}}</td>

						@if($fee->audit==1)
							<td>{{$fee->audit}}</td>
						@elseif($fee->audit==2)
							<td>{{$fee->audit}}</td>
						@else
							<td>N</td>
						@endif

						</tr>

@endforeach

					</tbody>
				</table>
			</div>

			<!-- Nutritionist Summary Report -->
			<div role="tabpanel" class="tab-pane fade" id="packages">
  				<div class="container">
  					<p>&nbsp;</p>
  					<div class="col-md-4">
	  					<table class="table table-bordered">
	  						<thead>
	  							<tr>
	  								<th>Packages (Month)</th>
	  								<th>Count</th>
	  								<th>Amount</th>
	  							</tr>
	  						</thead>
	  						<tbody>

	  					@foreach($packages AS $package)
	  							<tr>
	  								<td style="text-align:center">{{$package->name}}</td>
	  								<td style="text-align:center">{{$package->count}}</td>
	  								<td style="text-align:right">{{money_format("%i", $package->amount)}}</td>
	  							</tr>
	  					@endforeach

	  						</tbody>
							<tfoot>
								<tr>
									<th style="text-align:right">Total</th>
									<th style="text-align:center">{{$fees->count()}}</th>
									<th style="text-align:right">{{money_format("%i", $fees->sum('total_amount'))}}</th>
								</tr>
							</tfoot>
	  					</table>
  					</div>
  					<div class="col-md-8">
  						<div id="chart"></div>
  					</div>
  				</div>

			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.1placeholder { color: gray }

</style>
<script type="text/javascript">
$(function () {

    $(document).ready(function () {

        // Build the chart
        $('#chart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
	            options3d: {
	                enabled: true,
	                alpha: 45,
	                beta: 0
	            }
            },
            title: {
                text: 'Conversions Country Wise'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y}) : {point.percentage:.1f} %',
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: "Brands",
                colorByPoint: true,
                data: [

                @foreach($packages AS $package)
                	{
                		name : {{$package->name}},
                		y : {{$package->count}}
                	},
                @endforeach

                ]
            }]
        });
    });
});
</script>
<script type="text/javascript">
$(document).ready(function()
{
  	$('#table').dataTable({

	    "bPaginate": false,
	    "aaSorting": [[ 4, "desc" ]]
  	});

  	$( "#downloadCSV" ).bind( "click", function()
  	{
    	var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
    	downloadFile('payments.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
