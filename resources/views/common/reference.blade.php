<div class="container">

<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Csv</a>
	<div class="panel panel-default">
		<div class="panel-heading">
			@include('partials/daterange')
		</div>
		<div class="panel-body">
	    	<table id="example" class="table">
	    		<thead>
	    			<tr>
	    				<th width="5%"></th>
			            <th width="15%">Date</th>
			            <th width="15%">Sourced By</th>
			    		<th width="25%">Name</th>
			    		<th width="25%">Referrer</th>
                  <th width="15%">Voice</th>
			            <th width="15%">CRE</th>
			            <th width="15%">Conversion</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    	
	    	<?php $i = 0 ?>
	    	@foreach($leads AS $lead)
	    		<?php $i++ ?>
	    			<tr>
	    				<td>{{$i}}</td>
	    				<td>{{date('jS M, Y', strtotime($lead->sourced_date))}}</td>
	    				<td>{{$lead->sourced_by}}</td>
	    				<td><a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{$lead->name}}</a></td>
	    				<td><a href="/lead/{{$lead->referrer_id}}/viewReferences" target="_blank">{{$lead->referrer_name}}</a></td>
              <td>{{$lead->cre->cre or ""}}</td>
	    				<td>{{$lead->voice or ""}}</td>
	    				<td>{{isset($lead->patient->fees)? date('jS M, Y', strtotime($lead->patient->fees->last()->entry_date)) : ""}}</td>
	    			</tr>

	    	@endforeach		
	    	
	    		</tbody>
	    	</table>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#example').dataTable({

	    "sPaginationType": "full_numbers",
	    "iDisplayLength": 100,
	    "bPaginate": false
  	}); 

  
  $("#form").submit(function(event) 
  {
    event.preventDefault(); /* stop form from submitting normally */

    var oTable = $('#example').dataTable();
    var url = $("#form").attr('action'); // the script where you handle the form input.
      $.ajax(
      {
         type: "POST",
         url: url,
         data: $("#form").serialize(), // serializes the form's elements.
         dataType: 'json',
         beforeSend: function () { $("#loader").modal('show'); $("#progress").addClass('animate');},
         complete: function () { $("#loader").modal('hide'); },
         success: function(s)
         {
            oTable.fnClearTable();

            $.each(s, function(i, field)
            {
              oTable.fnAddData([
                i+1,
                moment(field.created_at).format("MMM D, YYYY"),
                field.sourced_by,
                "<a href='http://nutri1/modules/lead/?clinic=" + field.clinic  + "&enquiry_no=" + field.enquiry_no  + "&phone_number=" + field.phone  + "' target='_blank'>" + field.name  + "</a> ( <em>" + field.clinic + " - " + field.enquiry_no + " </em>)",               	
                "<a href='http://nutri1/modules/lead/?clinic=" + field.clinic  + "&enquiry_no=" + field.referrer_enquiry_no  + "&phone_number=" + field.referrer_phone  + "' target='_blank'>" + field.referrer  + "</a> ( <em>" + field.referrer_clinic + " - " + field.referrer_enquiry_no + " </em>)", 
                field.cre,
                field.conversion_date
                 
              ]);
            });
         }
      });
      return false; // avoid to execute the actual submit of the form.
  }); 

  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#example').table2CSV({
                delivery: 'value'
            });
    downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
});
</script>