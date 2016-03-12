<div class="container">  
	<div class="panel panel-default">
		<div class="panel-heading">             
            <div class="pull-left">
                @include('partials/daterange')
            </div>
            <h4 style="margin-left:400px">Patient Age</h4>
		</div>
		<div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#age" aria-controls="age" role="tab" data-toggle="tab">Patients</a></li>
            <li role="presentation"><a href="#active" aria-controls="active" role="tab" data-toggle="tab">Active Patients</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane  active" id="age">
                <a name="download" id="downloadAge" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x"></i></a>
            
                <table id="table-age" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Nutritionist</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Age</th>
                            <th>DOB1*</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($patients AS $patient)
<?php
    $from = new DateTime($patient->lead->dob);
    $to = new DateTime('today');
    $dob = $from->diff($to)->y;

?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                            <td>{{$patient->fee->start_date or ""}}</td>
                            <td>{{$patient->fee->end_date or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>
                            <td>{{$patient->lead->gender or ""}}</td>
                            <td>{{$patient->lead->dob}}</td>
                            <td>{{$dob}}</td>
                            <td>{{$patient->lead->dob1}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="active">
                <a name="download" id="downloadActive" class="btn btn-primary pull-right" style="margin:10px"><i class="fa fa-download fa-2x"></i></a>
                
                <table id="table-active" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Nutritionist</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Age</th>
                            <th>DOB1*</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($activePatients AS $patient)
<?php
    $from = new DateTime($patient->lead->dob);
    $to = new DateTime('today');
    $age = $from->diff($to)->y;

?>
                        <tr>
                            <td>{{$j++}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                            <td>{{$patient->fee->start_date or ""}}</td>
                            <td>{{$patient->fee->end_date or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>

                        @if($patient->lead->gender <>'')
                            <td class="green">{{$patient->lead->gender}}</td>
                        @else
                            <td class="red"></td>
                        @endif

                            <td>{{$patient->lead->dob}}</td>

                        @if($age == '' || $age == 0 || $age == 2016)
                            <td class="red"></td>
                        @else   
                            <td class="green">{{$age}}</td>
                        @endif

                            <td>{{$patient->lead->dob1}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>                
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table-age').dataTable({

        "sPaginationType": "full_numbers",
        "iDisplayLength": 200,
        "bPaginate": false
    });

    $( "#downloadAge" ).bind( "click", function() 
    {
        var csv_value = $('#table-age').table2CSV({
                delivery: 'value'
            });
        downloadFile('ages.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
        $("#csv_text").val(csv_value);  
    });

    $( "#downloadActive" ).bind( "click", function() 
    {
        var csv_value = $('#table-active').table2CSV({
                delivery: 'value'
            });
        downloadFile('active.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
    table td.red {
        background-color: red;
        text-align: center;
    }
    table td.green {
        background-color: green;
        text-align: center;
    }
</style>