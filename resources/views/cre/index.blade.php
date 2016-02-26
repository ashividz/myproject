<style type="text/css">
	table td {
		text-align: center;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
  		<div class="pull-right">
    		@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl'))
				@include('../partials/users')
			@endif
		</div>
		<h4>Dashboard</h4>
	</div>	
	<div class="panel-body">
		<div class="container">
			<div class="col-md-7">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th></th>
							<th>New Leads</th>			
							<th>CRM Dispositions</th>			
							<th>Hot Pipelines</th>
							<th>Hot Pipelines Created</th>
							<th>Conversions</th>
							<th>Amount</th>
							<th>Reference Sourced</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Today</th>
							<td>
								{{LeadCre::leadCount($name)}}
							</td>				
							<td>
								{{CallDisposition::getCount($name)}}
							</td>
							<td>
								{{CallDisposition::getHotCount($name)}}
							</td>
							<td>
								
							</td>
							<td>
								{{Fee::conversionCount($name)}}

								@if(Fee::conversionCount($name) <> 0)
									({{LeadCre::leadCount($name)/Fee::conversionCount($name)}})
								@endif
							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name))}}
							</td>
							<td>
								{{LeadSource::referenceCount($name)}}
							</td>
						</tr>
						<tr>
							<th>Yesterday</th>
							<td>
								{{LeadCre::leadCount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
							</td>				
							<td>
								{{CallDisposition::getCount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
							</td>
							<td>
								{{CallDisposition::getHotCount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
							</td>
							<td></td>
							<td>
								{{Fee::conversionCount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days"))))}}
							</td>
							<td>
								{{LeadSource::referenceCount($name, date('Y/m/d 0:0:0',strtotime("-1 days")), date('Y/m/d 23:59:59',strtotime("-1 days")))}}
							</td>
						</tr>
						<tr>
							<th>1 Week</th>
							<td>
								{{LeadCre::leadCount($name, date('Y/m/d 0:0:0', strtotime("-8 days"))) }}
							</td>			
							<td>
								{{CallDisposition::getCount($name, date("Y/m/d 0:0:0", strtotime("-8 days")))}}
							</td>
							<td>
								{{CallDisposition::getHotCount($name, date("Y/m/d 0:0:0", strtotime("-8 days")))}}
							</td>
							<td></td>
							<td>
								{{Fee::conversionCount($name, date('Y/m/d 0:0:0',strtotime("-8 days")))}}
							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name, date('Y/m/d 0:0:0',strtotime("-8 days"))))}}
							</td>
							<td>
								{{LeadSource::referenceCount($name, date('Y/m/d 0:0:0',strtotime("-8 days")))}}
							</td>
						</tr>
						<tr>
							<th>MTD</th>
							<td>
								{{LeadCre::leadCount($name, date('Y/m/01 0:0:0'))}}
							</td>			
							<td>
								{{CallDisposition::getCount($name, date("Y-m-01 0:0:0"))}}
							</td>						
							<td>
								{{CallDisposition::getHotCount($name, date("Y/m/01 0:0:0"))}}
							</td>
							<td></td>
							<td>
								{{Fee::conversionCount($name, date("Y-m-01 0:0:0"))}}
							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name, date("Y-m-01 0:0:0")))}}
							</td>
							<td>
								{{LeadSource::referenceCount($name, date("Y-m-01 0:0:0"))}}
							</td>
						</tr>			
						<tr>
							<th>Trending</th>
							<td>
								{{round(LeadCre::leadCount($name, date('Y/m/01 0:0:0'))*date("t")/date('d'))}}
							</td>			
							<td>
								{{round(CallDisposition::getCount($name, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
							</td>						
							<td>
								{{round(CallDisposition::getHotCount($name, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
							</td>
							<td></td>
							<td>
								{{round(Fee::conversionCount($name, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
							</td>
							<td>
								{{round(LeadSource::referenceCount($name, date("Y-m-01 0:0:0"))*date("t")/date('d'))}}
							</td>
						</tr>
						<tr>
							<th>Previous Month</th>
							<td>
								{{LeadCre::leadCount($name, date("Y-m-01 0:0:0", strtotime("-1 months"))), date("Y-m-t 23:59:59", strtotime("-1 months"))}}
							</td>			
							<td>
								{{CallDisposition::getCount($name, date("Y-m-01 0:0:0", strtotime("-1 months"))), date("Y-m-t 23:59:59", strtotime("-1 months"))}}
							</td>						
							<td>
								{{CallDisposition::getHotCount($name, date("Y-m-01 0:0:0", strtotime("-1 months"))), date("Y-m-t 23:59:59", strtotime("-1 months"))}}
							</td>
							<td></td>
							<td>
								{{Fee::conversionCount($name, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}

							</td>
							<td>					
								₹ {{money_format('%!i', Fee::amount($name, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months"))))}}
							</td>
							<td>
								{{LeadSource::referenceCount($name, date("Y-m-01 0:0:0", strtotime("-1 months")), date("Y-m-t 23:59:59", strtotime("-1 months")))}}
							</td>
						</tr>
					</tbody>	
				</table>
			</div>
			<div class="col-md-5">
			@if($statuses->leads > 0)
				<div id="container"></div>
			@endif
			</div>	
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {

    $('#container').highcharts({
        chart: {
            type: 'funnel',
            marginRight: 100
        },
        title: {
            text: 'Leads Pipeline Funnel',
            x: -50
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b> ({point.y:,.0f})',
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    softConnector: true
                },
                neckWidth: '30%',
                neckHeight: '25%'

                //-- Other available options
                // height: pixels or percent
                // width: pixels or percent
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Unique leads',
            data: [
            @foreach($statuses as $status)
            	['{{$status->name}}', {{$status->count}}],
            @endforeach
            ]
        }]
    });
});
</script>