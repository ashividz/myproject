	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials.daterange')
				</div>
				<h4>Country Wise Performance</h4>
			</div>	
			<div class="panel-body">
				<table id="leads" class="table table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Receipt No</th>
							<th>Name</th>
							<th>Entry Date</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Amount</th>
							<th>Source</th>
							<th>Cre</th>
						</tr>
					</thead>
					<tbody>	

		@foreach($fees AS $fee)	

		<? $i++; ?>
						
						<tr>
							<td>
								{{$i}}
							</td>
							<td>
								{{$fee->receipt_no}}
							</td>
							<td>
								<a href="/lead/{{$fee->patient->lead->id}}/viewDetails" target="_blank">{{$fee->patient->lead->name or ""}}</a>
							</td>
							<td>
								{{date('jS M, Y h:i A', strtotime($fee->entry_date))}}
							</td>
							<td>
								{{date('jS M, Y', strtotime($fee->start_date))}}
							</td>
							<td>
								{{date('jS M, Y', strtotime($fee->end_date))}}
							</td>
							<td>
								{{$fee->total_amount}}
							</td>
							<td>
								<div class='editable_source' id='{{ $fee->id }}'>
									{{$fee->source->source_name or ""}}
								</div>
							</td>
							<td>
								<div class='editable_cre' id='{{ $fee->id }}'>
									{{$fee->cre or ""}}
								</div>
							</td>	
						</tr>

		@endforeach	

					</tbody>
				</table>	
			</div>			
	</div>
<style type="text/css">
	.placeholder { color: gray }

</style>
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
});
</script>