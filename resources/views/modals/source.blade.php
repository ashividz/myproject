<div style='display:none'>
	<div class='modal-top'></div>
	<div class='modal-form'>
		<div class='modal-loading' style='display:none'></div>
		<div class='modal-message' style='display:none'></div>
		<form action='#' style='display:none'>
			<table width="100%" class="">
				<tbody>
					<tr>
						<td>
							<label for='source'>* Source : </label>
						</td>
						<td>
							<div>
								<select id='source' name='source'>
									<option value="">Select Source</option>

								@foreach($sources AS $source)
									
									<option value="{{ $source->id }}">{{ $source->source_name }}</option>

								@endforeach

								</select>					
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<label for='voice'>Voice : </label>
						</td>
						<td>
							<div>
								<select id='voice' name='voice'>
								</select>					
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<label for='referrer_id'>Referrer Lead Id : </label>
						</td>
						<td>
							<input type="text" name="referrer_id" placeholder="">
						</td>
					</tr>
					<tr>
						<td>
							<label for='sourced_by'>Sourced By : </label>
						</td>
						<td>
							<input type="text" name="sourced_by" placeholder="Sourced By">
						</td>
					</tr>
					<tr>
						<td>
							<label for='remark'>Remark : </label>
						</td>
						<td>
							<p>&nbsp;</p>
							<textarea name="remark" placeholder="Remark" cols="30"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
			<div style="text-align:center; margin-top:50px;">
				<button type='submit' class='modal-send modal-button' tabindex='1006'>Submit</button>
				<button type='submit' class='modal-cancel modal-button simplemodal-close' tabindex='1007'>Cancel</button>
				<br/>
				<input type='hidden' name='id' value='{{ $id }}'/>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</form>
	</div>
<script type="text/javascript">
$(document).ready(function() 
{ 
	//Fetch Lead Voices   
    $("#voice").append("<option value=''> Select Voice </option>");
    $.getJSON("/api/getVoiceList",function(result){
        $.each(result, function(i, field){
            $("#voice").append("<option value='" + field.id + "'> " + field.name + "</option>");
        });
    });
})
</script>