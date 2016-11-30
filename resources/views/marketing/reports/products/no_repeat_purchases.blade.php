<div class="container" id="app">
	<div class="panel">
		<div class="panel-heading">
			<h4>No repeat product purchase</h4>
		</div>
		<div class="panel-body">			
			<div>
				<table class="table table-bordered" id="table_no_repeat_purchase">
					<thead>
						<tr>
							<th>#</th>
							<th>Lead id</th>
							<th>Name</th>
							<th>Total Days elapsed</th>
							<th>last order date</th>
							<th>Total herbs orders</th>
						</tr>
					</thead>
					<tbody>
					
					<?php $x=0; ?>
					@foreach($leads as $lead)
						<tr>
							<td>{{++$x}}</td>
							<td>
							<a href='/lead/{{$lead->id}}/cart' target='_blank'> {{$lead->id}}</a></td>
							<td>
							<a href='/lead/{{$lead->id}}/viewDetails' target='_blank'> {{$lead->name}}</a></td>
							<td>{{Carbon::parse($lead->carts->sortByDesc('updated_at')->first()->updated_at)->diffInDays(Carbon::today())}}</td>
							<td>{{$lead->carts->sortByDesc('updated_at')->first()->updated_at}}</td>
							<td>{{$lead->carts->count()}}</td>

						</tr>
					@endforeach
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#table_no_repeat_purchase').dataTable({
    bPaginate : false,
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
            $("td:first", nRow).html(iDisplayIndex +1);
           return nRow;
    },
 });
</script>