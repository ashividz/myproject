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
			<table width="100%">
				<tr>
					<th colspan="4">
						<h2 style="margin:0px 20px 20px 30px;">Assign Herb</h2>
					</th>
				</tr>
				<tr>
					<th>Herb</th>
					<td>
						<select name="herb" id="herb">
							<option value="">Select Herb</option>
						
						@foreach($herbs AS $herb)
							<option value="{{$herb->id}}">{{$herb->name}}</option>
						@endforeach

						</select>
					</td>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th>Quantity</th>
					<td>
						<input type="text" name="quantity" id="quantity">
					</td>
					<th>Unit</th>
					<td>
						<select name="unit" id="unit">
							<option value="">Select Unit</option>
						
						@foreach($units AS $unit)
							<option value="{{$unit->id}}">{{$unit->name}}</option>
						@endforeach

						</select>
					</td>
				</tr>
				<tr>
					<th>Remark</th>
					<td colspan="3">
						<textarea name="remark" cols="60"></textarea>
					</td>
					<th>Mealtimes</th>
					<td>
						<select name="mealtimes[]" id="mealtimes" multiple size='7'>						
						@foreach($mealtimes AS $mealtime)
							<option value="{{$mealtime->id}}">{{$mealtime->name}}</option>
						@endforeach

						</select>
					</td>
				</tr>
				
			</table>
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<input type="hidden" name="id" value="{{ $id }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>