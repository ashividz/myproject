
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			@include('partials/daterange')
            <div class="pull-right">
                <a name="download" id="download" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x"></i></a>
                
            </div>
		</div>	
		<div class="panel-body">
			<table id="table" class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th>Patient</th>
                        <th>Nutritionist</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Occupation</th>
						<th>Organization</th>
                        <th>Tags</th>
					</tr>
				</thead>
				<tbody>
			@foreach($patients as $patient)
<?php
    $tags = '';
    foreach ($patient->tags as $tag) {
        $tags .= "<span class='tag'>".$tag->name."</span>"; 
    }

?>

					<tr>
						<td>{{$i++}}</td>
						<td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name}}</a></td>
                        <td>{{$patient->nutritionist}}</td>
						<td>{{$patient->fee->start_date->format('Y-m-d')}}</td>
						<td>{{$patient->fee->end_date->format('Y-m-d')}}</td>
						<td>{{$patient->lead->profession}}</td>
						<td>{{$patient->lead->company}}</td>
                        <td>{!!$tags!!}</td>
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
    $("#table").dataTable({
        bPaginate:false
    })

    $( "#download" ).bind( "click", function() 
    {
        var csv_value = $('#table').table2CSV({
                delivery: 'value'
            });
        downloadFile('occupation.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
<style type="text/css">
    .tag {
        border: #888 1px solid;
        margin-right: 10px;
        padding: 2px;
        background-color: #5697CC;
        color: #fff;
        background-image: -webkit-linear-gradient(top, #5d9ed2, #4386bc); 
    }
</style>