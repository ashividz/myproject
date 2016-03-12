<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<div>
				<label for='status'>Cre: </label>
				<div>
					<select id='cre' name='cre'>
						<option value="">Select CRE</option>

					@foreach($cres AS $cre)
						
						<option value="{{ $cre->name }}">{{ $cre->name }}</option>

					@endforeach

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