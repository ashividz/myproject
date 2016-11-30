<div class="container1">
	<div class="panel panel-default">		
		<div class="panel-heading">			
			<div class="pull-right">
				@include('partials/daterange')
			</div>
		</div>
		<div class="panel-body">
			<form id="form" method="post" class="form-inline" action="/service/diets/send">
				<table id="diets" class="table table-bordered">
					<thead>
						<tr>
							<th><input type="checkbox" id="checkAll"></th>
							<th>Patient</th>
							<th>Sent On</th>
							<th>Diet Date</th>
							<th>Nutritionist</th>
							<th width="3%">Preferred Time</th>
							<th>Breakfast</th>
							<th>Lunch</th>
							<th>Evening</th>
							<th>Dinner</th>
							<th>Herbs</th>
							<th>Remark</th>
							<th>Email</th>
							<th>SMS</th>
						</tr>
					</thead>
					<tbody>
				@foreach($diets as $diet)
						<tr>
							<td>
								<input class='checkbox' type='checkbox' name='check[{{$diet->id}}]' value="{{$diet->id}}">
							</td>
							<td>
								<a href="/patient/{{$diet->patient_id}}/diet" target="_blank">{{$diet->patient->lead->name or 'No Name'}}</a>
								<div class="pull-right">
									<em><small></small>[{{$diet->patient->lead->country}}]</em></em>
								</div>
							</td>
							<td>
								{{$diet->date->format('jS M, Y h:i A')}}
							</td>
							<td>
								{{date('jS M, Y', strtotime($diet->date_assign))}}
							</td>
							<td>
								{{$diet->nutritionist or ''}}
							</td>
							<td>
								{{$diet->patient->suit->trial_plan or ''}}
							</td>
							<td>
								<div data-toggle="popover" title="Breakfast" data-content="{{$diet->breakfast}}">
									{{$diet->breakfast? substr($diet->breakfast, 0, 10).'...':''}}
								</div>
							</td>
							<td>
								<div data-toggle="popover" title="Lunch" data-content="{{$diet->lunch}}">
									{{$diet->lunch? substr($diet->lunch, 0, 10).'...':''}}
								</div>
							</td>
							<td>
								<div data-toggle="popover" title="Evening" data-content="{{$diet->evening}}">
									{{$diet->evening? substr($diet->evening, 0, 10).'...':''}}
								</div>
							</td>
							<td>
								<div data-toggle="popover" title="Dinner" data-content="{{$diet->dinner}}" data-placement="left">
									{{$diet->dinner? substr($diet->dinner, 0, 10).'...':''}}
								</div>
							</td>
							<td>
								<div data-toggle="popover" title="Herbs" data-content="{{$diet->herbs}}" data-placement="left">
									{{$diet->herbs? substr($diet->herbs, 0, 10).'...':''}}
								</div>
							</td>
							<td>
								<div data-toggle="popover" title="Remark" data-content="{{$diet->rem_dev}}" data-placement="left">
									{{$diet->rem_dev? substr($diet->rem_dev, 0, 10).'...':''}}
								</div>
							</td>							
							<td style="text-align:center">
								<i class="fa {{$diet->email?'fa-check-square green':'fa-close red'}}"></i>
							</td>							
							<td style="text-align:center" title="{{$diet->sms_response}}">
								<i class="fa {{$diet->sms_response?'fa-check-square green':'fa-close red'}}"></i>
							</td>
						</tr>
						
				@endforeach		
					</tbody>
					<tfoot>
						<tr>
							<td colspan="14">
								<div class="form-group">
									<button class="btn btn-primary" name="email">Send Email</button>
								</div>
								<div class="form-group">
									Send SMS
									<input type="checkbox" name="sms" checked>
								</div>
								<input type="hidden" name="_token" value="{{ csrf_token() }}"></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<style type="text/css">
	.popover {
		max-width: 1024px;	
	}
</style>
<script type="text/javascript" src="/js/form-ajax.js"></script>
<script>
$(document).ready(function(){
	$('#diets').dataTable({
		bPaginate : false
	});

    $('[data-toggle="popover"]').popover({trigger : 'hover'}); 

    $("#checkAll").change(function () {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));
	});
});
</script>