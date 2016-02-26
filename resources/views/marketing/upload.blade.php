<div class="container">
	
	<div class="col-md-5">
			<form class="form-horizontal well" id="form" action="" method="post" enctype="multipart/form-data">			
			<fieldset>
				<legend>Upload CSV/Excel file</legend>
				<div class="control-group">
					<div>
						<label>CSV/Excel File:</label>
					</div>
					<div class="controls">
						<input type="file" name="file" id="file" class="input-large" required>
					</div>
				</div>
				<hr>
				<div class="control-group">
					<div class="controls">
					<button type="submit" id="upload" name="upload" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>
			</fieldset>
		</form>
	</div>
	<div class="col-md-7">
		<fieldset class="well">
			<h4>Table Format</h4>
			<table class="table table-bordered">
				<tr>
					<td>Name</td>
					<td>Phone</td>
					<td>Email</td>
					<td>Country</td>
					<td>State</td>
					<td>City</td>
					<td>Source</td>
					<td>Query</td>
					<td>CRE</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<table id="table" class="table table-bordered">
		
	</table>
</div>
<script type="text/javascript">
$(document).ready(function() 
{ 
	$("#form").submit(function(event) {

		var formData = new FormData($(this)[0]);
	    event.preventDefault();
	    /* stop form from submitting normally */

	    
	    var url = $("#form").attr('action'); // the script where you handle the form input.
        $.ajax(
        {
           	type: "POST",
           	url: url,
           	data: formData,
        	async: false,
           	success: function(data)
           	{
               
               $('#table').empty().append(data);
           	},
	        cache: false,
	        contentType: false,
	        processData: false
        });
        return false; // avoid to execute the actual submit of the form.
	});	
});			
</script>
<style type="text/css">
	table td {
		background-color: #f9f9f9;
		font-weight: 600;
	}
</style>