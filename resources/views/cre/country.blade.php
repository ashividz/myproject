<?php
	$ncr = 0;
	$pan = 0;
	$int = 0;
	$oth = 0;
	$cnt = 0;
	$amount = 0;
?>	
	<div class="panel panel-default">
			<div class="panel-heading"  style="text-align:center">
	      		<div class="pull-left">
		    		@include('cre.partials.index')
				</div>
				<h4>Country Wise Performance</h4>
			</div>	
			<div class="panel-body">

		@if(!$patients->isEmpty())

				<div class="col-md-6">
					<table id="leads" class="table table-bordered">
						<thead>
							<tr>
								<th>Country</th>
								<th>State</th>
								<th>Count</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>	

				@foreach($patients AS $patient)

	<?php
		
		if ($patient->region_name == "Delhi NCR") {
			$ncr += $patient->cnt;
		}
		elseif ($patient->country_name == "India") {
			$pan += $patient->cnt;
		}	
		elseif ($patient->country_name <> "") {
			$int += $patient->cnt;
		}
		else {
			$oth += $patient->cnt;
		}

		$cnt += $patient->cnt;
		$amount += $patient->amount;

	?>

							<tr>
								<td>{{$patient->country_name}}</td>
								<td>{{$patient->region_name}}</td>
								<td>{{$patient->cnt}}</td>
								<td>₹ {{money_format($patient->amount, 2)}}</td>
							</tr>

				@endforeach			

						</tbody>
						<tfoot>
							<th></th>
							<th></th>
							<th>{{$cnt}}</th>
							<th>₹ {{money_format($amount, 2)}}</th>
						</tfoot>
					</table>
				</div>

				<div class="col-md-6">
					<div  class="pull-right" id="chart"></div>
				</div>

			@endif

			</div>			
	</div>

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
                data: [{
                    name: "Delhi NCR",
                    y: {{$ncr}}
                }, {
                    name: "Pan India",
                    y: {{$pan}},
                    sliced: true,
                    selected: true
                }, {
                    name: "International",
                    y: {{$int}}
                }, {
                    name: "Others",
                    y: {{$oth}}
                }]
            }]
        });
    });
});
</script>