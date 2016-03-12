<div class="container">
	<div class="panel panel-default">
			<div class="panel-heading">
	      		<div class="pull-right">
	      			@include('partials/users')
				</div>
				<h4>Patients</h4>
			</div>	
			<div class="panel-body">

				<table id="leads" class="table table-bordered">
					<thead>
						<tr>
							<td>#</td>
							<td>Name</td>
							<td>Start Date</td>
							<td>End Date</td>
							<td>Nutritionist</td>
							<td>Herbs</td>
							<td>Notes</td>
						</tr>
					</thead>
					<tbody>

				@foreach($patients AS $patient)
<?php
	$herbs = "";
	$tags = ""	;	
	$notes = "";
	foreach($patient->herbs AS $herb)
	{
		$herbs .= "<p>".$herb->herb->name." : ".$herb->quantity." ".$herb->unit->name." ".$herb->remark." - <small><em>[".date('jS M, Y', strtotime($herb->created_at))."]</em></small><p>";
	}
	foreach($patient->tags as $tag) {
		$tags .= $tag->name . "<p>";
	}
	foreach($patient->notes as $note) {
		$notes .= $note->text . " : <b>". $note->created_by."</b><em> (".$note->created_at.")</em><p>";
	}
	
?>
						<tr>
							<td>
								{{$i++}}
							</td>
							<td><a href="/patient/{{$patient->id}}/herbs" target="_blank">{{$patient->lead->name}}</a><div class="pull-right" data-html="true" data-toggle="popover" title="Tags" data-content="{!!$tags==''?'No Tag':$tags!!}"><a href="/patient/{{$patient->id}}/tags" target="_blank"><i class="fa fa-tags fa-2x {{$patient->tags->isEmpty() ? 'red': 'green'}}"></i></a></div></td>
							<td>
								{{date('jS M, Y', strtotime($patient->fee->start_date))}}
							</td>
							<td>
								{{date('jS M, Y', strtotime($patient->fee->end_date))}}
							</td>
							<td>
								{{$patient->nutritionist or ""}}
							</td>
							<td align="center"><div data-html="true" data-toggle="popover" title="Herbs" data-content="{!!$herbs==''?'No Herb':$herbs!!}" data-placement="left"><a href="/patient/{{$patient->id}}/herbs" target="_blank"><i class="fa fa-stethoscope fa-2x {{$patient->herbs->isEmpty() ? 'red': 'green'}}"></i></a></div></td>

							<td align="center"><div data-html="true" data-toggle="popover" title="Notes" data-content="{!!$notes==''?'No Note':$notes!!}" data-placement="left"><a href="/patient/{{$patient->id}}/notes" target="_blank"><i class="fa fa-sticky-note-o fa-2x {{$patient->notes->isEmpty() ? 'red': 'green'}}"></i></a></div></td>
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
	$('#leads').dataTable({
		"iDisplayLength": 100
	});

	$("#form").submit(function(event) {


	    event.preventDefault();
	    /* stop form from submitting normally */

        var url = $("#form").attr('action'); // the script where you handle the form input.
        $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data);
               $('#alert').show();
               $('#alert').empty().append(data);
               setTimeout(function()
               	{
			     	$('#alert').slideUp('slow').fadeOut(function() 
			     	{
			         	location.reload();
			         });
				}, 10000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
	});	
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" }); 
});
</script>
<style type="text/css">
	.popover {
		text-align: left;
	    max-width: 1250px;
	}
</style>