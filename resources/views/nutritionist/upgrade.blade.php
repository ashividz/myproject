
<div class="container">
	<div>
		<form id="form_all" method="post" action="">
			<input class="form-input" type="checkbox" id="all" name="all"> Show all patients
			
          <div class="pull-right">
            <a name="download" id="downloadCSV" class="btn btn-primary" download="filename.csv">Download Csv</a>            
            <input type="hidden" name="csv_text" id="csv_text">
          </div>
		</form>
	</div>
	<table class="table table-striped" id="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Patient</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Days Left</th>
				<th>Status</th>
				<th>Sourced By</th>
				<th>Add Upgrade Lead</th>
			</tr>
		</thead>
		<tbody>
<?php
	$i = 0;
?>
		@foreach ($patients as $patient)
			<tr>
				<td></td>
				<td>
				<a href='/lead/{{$patient->lead_id}}/viewDetails' target='_blank'> {{$patient->lead->name or "NA"}}</a></td>
				
				<td> {{$patient->fee->start_date or ""}} </td>
				<td> {{$patient->fee->end_date or ""}} </td>
				<td> {{$patient->days or ""}} </td>
				<td> {{$patient->lead->status->master->status or ""}} </td>
				<td> {{$patient->lead->sources->last()->sourced_by or ""}} </td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
<script type="text/javascript">
$('#alert').hide();
$('.clickable').click(function (event) {
	event.preventDefault();
	if (confirm("Do you want to add a Upgrade Lead?") == true) 
	{
	  	var url = this.href; // the script where you handle the form input.
	    $.ajax(
	    {
	       type: "POST",
	       url: url,
	       success: function(data)
	       {
	       		
	       }
	    });		
	}
	else
	{
		return false;
	} 	
 });

$(".editable_remark").editable("actions/upgrade.php", { 
      indicator : "<img src='/assets/images/indicator.gif'>",
      type   : 'text',
      cssclass : 'editable_upgrade',
      submitdata: { _method: "put" },
      select : true,
      submit : 'Add',
      cancel : 'Cancel',
      tooltip: 'Click to Add',
      callback : function(value, settings) {
         window.location.reload();
    }
  });
	$("#all").click(function() {
      $("#form_all").submit();
    });

    $( "#downloadCSV" ).bind( "click", function() 
	{
	    var csv_value = $('#table').table2CSV({
	                delivery: 'value'
	            });
	    downloadFile('upgrade_list.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
	    $("#csv_text").val(csv_value);  
	});

  	function downloadFile(fileName, urlData){
	    var aLink = document.createElement('a');
	    var evt = document.createEvent("HTMLEvents");
	    evt.initEvent("click");
	    aLink.download = fileName;
	    aLink.href = urlData ;
	    aLink.dispatchEvent(evt);
	  }
</script>

<style type="text/css">
	.editable_upgrade input {
		width: 200px !important;
	}
</style>