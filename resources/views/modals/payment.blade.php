<style type="text/css">
	table th {
		padding: 5px;
	}
	table td {
		padding: 5px;
	}
	#modal-container {
		width: 800px !important;
	}
</style>
<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<div class="container">
			<form action='#' style='display:none'>
				<table>
					<tr>
						<th colspan="4">
							<h2 style="margin:0px 20px 20px 30px;">Payment Fee Details</h2>
						</th>
					</tr>
					<tr>
						<th>Name :</th>
						<td>{{$fee->patient->lead->name}}</td>
						<th>Receipt No : </th>
						<td>
							<input type="text" name="receipt_no" value="{{$fee->receipt_no}}">
						</td>
					</tr>
					<tr>
						<th>Package Duration :</th>
						<td>
							<select name="valid_months">
								<option value="">Select Package Duration</option>
								<option value="1" {{$fee->valid_months==1? 'selected':''}}>1</option>
								<option value="3" {{$fee->valid_months==3? 'selected':''}}>3</option>
								<option value="6" {{$fee->valid_months==6? 'selected':''}}>6</option>
								<option value="12" {{$fee->valid_months==12? 'selected':''}}>12</option>
							</select>
						</td>
						<th>Entry Date</th>
						<td><input type="text" name="entry_date" id="entry_date" value="{{date('d-m-Y', strtotime($fee->entry_date))}}"></td>
					</tr>
					<tr>
						<th>Start Date : </th>
						<td><input type="text" name="start_date" id="start_date" value="{{date('d-m-Y', strtotime($fee->start_date))}}"></td>
						<th>End Date</th> 
						<td><input type="text" name="end_date" id="end_date" value="{{date('d-m-Y', strtotime($fee->end_date))}}"></td>
					</tr>
					<tr>
						<th>Amount : </th>
						<td><input type="text" name="total_amount" value="{{$fee->total_amount}}"></td>
						<th>Discount : </th> 
						<td><input type="text" name="discount" value="{{$fee->discount}}"></td>
					</tr>
					<tr>
						<th>Source :</th>
						<td>
							<select name="source_id">
								<option value="">Select Source</option>
							
						@foreach($sources AS $source)
								<option value="{{$source->id}}" {{$source->id==$fee->source_id? 'selected':''}}>{{$source->source_name}}</option>
						@endforeach

							</select>
						</td>
						<th>CRE </th>
						<td>
							<select name="cre" id="cre">
								<option value="">Select CRE</option>
							
						@foreach($cres AS $cre)
								<option value="{{$cre->name}}" {{$cre->name==$fee->cre? 'selected':''}}>{{$cre->name}}</option>
						@endforeach

							</select>
						</td>
					</tr>
					
				</table>

				<div style="text-align:center; margin-top:10px;">

					<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
					<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
					<br/>
					<input type='hidden' name='id' value='{{ $fee->id }}'/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() 
	{     
	   
	   	$( "#entry_date" ).datetimepicker({
	   		timepicker: false,
	   		maxDate: new Date,
	   		format:'d-m-Y'
	   	});

	   	$( "#start_date" ).datetimepicker({
	   		timepicker: false,
	   		format:'d-m-Y'
	   	});
	   	
	   	$( "#end_date" ).datetimepicker({
	   		timepicker: false,
	   		format:'d-m-Y'
	   	});
	});
</script>