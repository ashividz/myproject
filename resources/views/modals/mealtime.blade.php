<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<div>
				<label for='status'>Mealtime </label>
				<div>
					<select id='mealtime' name='mealtime_id'>
						<option value="">Select Mealtime</option>

					@foreach($mealtimes AS $mealtime)
						
						<option value="{{ $mealtime->id }}">{{ $mealtime->name }}</option>

					@endforeach

					</select>					
				</div>
			</div>
			<div style="text-align:center; margin-top:50px;">
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				<input type='hidden' name='patient_herb_id' value='{{ $id }}'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>