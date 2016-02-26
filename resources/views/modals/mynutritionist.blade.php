<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<table width="100%" class="table">
				<tr>
					<td>
						<input type="radio" name="recipient" id="cre" value="cre" onchange="selectRecipient(this)"> CRE <small><em>({{$lead->cre->cre or  ""}})</em></small><br>

					@if($lead->patient)
						<input type="radio" name="recipient" id="nutritionist"> Nutritionist <small><em>({{$lead->patient->nutritionist or  ""}})</em></small><br>
						<input type="radio" name="recipient" id="secondary_nutritionist"> Secondary Nutritionist <small><em>({{$lead->patient->secondary_nutritionist or  ""}})</em></small><br>
						<input type="radio" name="recipient" id="doctor"> Doctor <small><em>({{$lead->patient->doctor or  ""}})</em></small>
					@endif

					</td>
				</tr>
				<tr>
					<td>
						<select id="recipient" multiple="multiple" name="recipients[]" style="width:500px" placeholder="Recipients">
				
						@foreach($users as $user)
							<option value="{{$user->name}}">{{$user->name}}</option>
						@endforeach
					
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<input class="form-control" type="text" id="subject" name="subject" placeholder="Subject" value="" style="width:500px">
					</td>
				</tr>
				<tr>
					<td>
						<textarea name="body" id="body" placeholder="Message" style="width:500px">Please call your client {{$lead->name}} at the earliest.</textarea>
					</td>
				</tr>
			</table>
			<div style="text-align:center; margin-top:50px;">
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				
				<input type='hidden' name='lead_id' value='{{ $lead->id }}'/>
				<input type='hidden' name='type_id' value='1'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>
<script>
	var select = $("#recipient");
    select.select2();

	$("#nutritionist").change(function() {
    if(this.checked) {
        $("#recipient").val(['{{$lead->patient->nutritionist or ""}}', '{{$lead->patient->nutritionist or ""}}']).select2();
        $("#subject").val("Call For My Nutritionist");
    }});

    $("#secondary_nutritionist").change(function() {
    if(this.checked) {
        $("#recipient").val(['{{$lead->patient->secondary_nutritionist or ""}}', '{{$lead->patient->secondary_nutritionist or ""}}']).select2();
        $("#subject").val("Call For My Secondary Nutritionist");
    }});

    $("#doctor").change(function() {
    if(this.checked) {
    	$("#recipient").val(['{{$lead->patient->doctor or ""}}', '{{$lead->patient->doctor or ""}}']).select2();

        $("#subject").val("Call For My Doctor");
    }});
    $("#cre").change(function() {
    if(this.checked) {
    	$("#recipient").val(['{{$lead->cre->cre or ""}}', '{{$lead->cre->cre or ""}}']).select2();
        $("#subject").val("Call For My CRE");
    }});
</script>
<style type="text/css">
	.table>tbody>tr>td {
		border-top: none;
	}
</style>