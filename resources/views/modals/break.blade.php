<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<div>
				<label for='end_date'>Start Date : </label>
				<div>
					<input type="date" id="start_date" name="start_date" value="{{ $fee->start_date }}" disabled>		
				</div>
			</div>
			<p>
			<div>
				<label for='end_date'>End Date : </label>
				<div>
					<input type="date" id="end_date" name="end_date" value="{{$fee->end_date}}">				
				</div>
			</div>
			<p>
			<div>
				<label for='Remark'>Remark : </label>
				<div>
					<select name="remark">
						<option value="Break Adjustment">Break Adjustment</option>
						<option value="Dissatisfaction">Dissatisfaction</option>
						<option value="Transfer">Transfer</option>
						<option value="Maintenance">Maintenance</option>
						<option value="Reference Sceme">Reference Sceme</option>
						<option value="Others">Others</option>
					</select>
				</div>
			</div>
			<div style="text-align:center; margin-top:50px;">
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				<input type='hidden' name='id' value='{{ $id }}'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>