<script type="text/javascript" src="/js/modals/payment.js"></script>
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
    	
				<table id="leads" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Package</th>
							<th>Entry Date</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Amount</th>
							<th>Source</th>
							<th>Cre</th>
							<th>Edit</th>
							<th>Audited</th>
						</tr>
					</thead>
					<tbody>	

@foreach($fees AS $fee)	

<?php
	$cres = "";
	$payments = "";
	foreach ($fee->patient->lead->cres as $cre) {
		$cres .= $cre->cre." : ".date('jS M, Y', strtotime($cre->created_at))."<br>";
	}
	foreach ($fee->patient->fees as $payment) {
		$payments .= money_format("%i",$payment->total_amount)." : ".date('jS M, Y', strtotime($payment->created_at))."<br>";
	}
?>
				
						<tr>
							<td>{{$i++}}
							</td>
							<td>
								<a href="/lead/{{$fee->patient->lead->id or ""}}/viewDetails" target="_blank">{{$fee->patient->lead->name or ""}}</a>
								<div data-html="true" data-toggle="popover" data-content="{!!$payments!!}"><i class="fa fa-info-circle pull-right"></i>
								</div>
							</td>
							<td>
								{{$fee->valid_months or ""}}
							</td>
							<td>
								{{isset($fee->entry_date) ? $fee->entry_date->format('jS M, Y') : ""}}
							</td>
							<td>
								{{isset($fee->start_date) ? $fee->start_date->format('jS M, Y') : ""}}
							</td>
							<td>
								{{isset($fee->end_date) ? $fee->end_date->format('jS M, Y') : ""}}
							</td>
							<td>
								{{isset($fee->total_amount) ? money_format("%i", $fee->total_amount) : ""}}
							</td>
							<td>
								<div class='editable_source' id='{{ $fee->id }}'>
									{{$fee->source->source_name or ""}}
								</div>
								<div data-html="true" data-toggle="popover" data-content="@foreach($fee->patient->lead->sources as $source){{$source->master->source_name or ''}} : {{$source->created_at->format('jS M, Y')}}<p>@endforeach"><i class="fa fa-info-circle pull-right"></i></div>
							</td>
							<td>
								<div class='editable_cre' id='{{ $fee->id }}'>
									{{$fee->cre or ""}}
								</div>
								<div data-html="true" data-toggle="popover" data-content="{!!$cres!!}" data-placement="left"><i class="fa fa-info-circle pull-right"></i></div>
							</td>
							<td align="center">
								@if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('finance'))
									<span class="fee glyphicon glyphicon-edit grey" value="{{$fee->id}}"></span>
								@endif
							</td>
						@if($fee->audit)
							<td style="text-align:center" title="{{$fee->audited_by}} on {{$fee->audited_at}}">

							@if($fee->audit == 1)
								<i class="green fa fa-check"></i>
							@else
								<div class='editable_audit' id='{{ $fee->id }}'>
									<i class='fa fa-close red'></i>
								</div>
							@endif
							</td>
						@else
							<td style="text-align:center">
								<div class='editable_audit' id='{{ $fee->id }}'>
									Click to Edit
								</div>
							</td>
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
<script type="text/javascript">
$(document).ready(function() 
{
	$(".editable_source").editable("/finance/saveSource", { 
      	loadurl   : "/api/getSources",
      	type      : "select",
      	submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      	cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      	placeholder: '<span class="placeholder">(Edit)</span>',
  	});

  	$(".editable_cre").editable("/finance/saveCre", { 
      	loadurl   : "/api/getCres",
      	type      : "select",
      	submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      	cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      	placeholder: '<span class="placeholder">(Edit)</span>',
  	});

  	$(".editable_audit").editable("/finance/saveAudit", { 
      	data   : " {'1':'Correct','2':'Incorrect'}",
      	type      : "select",
      	submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      	cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      	placeholder: '<span class="placeholder">(Edit)</span>',
  	});
});
</script>
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
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
    $('.fa-info-circle').click(function() {
    	$('.popover').not(this).popover('hide'); //all but this
    });
});
</script>
<style type="text/css">
.popover {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1010;
  display: none;
  max-width: 600px;
  padding: 1px;
  text-align: left;
  white-space: normal;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 6px;
     -moz-border-radius: 6px;
          border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
     -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
     -moz-background-clip: padding;
          background-clip: padding-box;
}
</style>