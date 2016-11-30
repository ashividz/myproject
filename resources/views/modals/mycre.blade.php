<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>

			<div>For My CRE : {{$lead->cre->cre}}</div>
			<p>&nbsp;</p>
			<div>
				<label for='text'>Message: </label>
				<div>
					<textarea id="content" name="content" cols="30">Please call your client {{$lead->name}} at the earliest.</textarea>

				</div>
			</div>
			<div style="text-align:center; margin-top:50px;">
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				<input type='hidden' name='subject' value='Call For My CRE'/>
				<input type='hidden' name='to' value='{{ $lead->cre->cre }}'/>
				<input type='hidden' name='lead_id' value='{{ $lead->id }}'/>
				<input type='hidden' name='type_id' value='2'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>