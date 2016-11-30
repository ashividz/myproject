<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<div>
				<label for='status'>*Status: </label>
				<div>
					<select id='status' name='status'>
						<option value="">Select</option>

					@foreach($actions AS $action)
						
						<option value="{{ $action->id }}">{{ $action->name }}</option>

					@endforeach

					</select>					
				</div>
			</div>
			<label for='remark'>Remark</label>
			<textarea id='remark' class='modal-input' name='remark' />
			<label>&nbsp;</label>
			<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
			<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			<input type='hidden' name='id' value='{{ $id }}'/>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
	</div>