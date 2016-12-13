<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">             
            <div class="pull-left">
                @include('partials/daterange')
            </div>
            <h4 style="margin-left:400px">Food Allergy</h4>
    </div>
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#age" aria-controls="age" role="tab" data-toggle="tab">Patients</a></li>
            <li role="presentation"><a href="#active" aria-controls="active" role="tab" data-toggle="tab">Active Patients</a></li>
        </ul>
        <br>

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane  active" id="age">
                
            
                <table id="table-age" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patients Id</th>
                            <th>Patients Name</th>
                            <th>Country</th>
                            <th>Allergy</th>
                            <th>Doctor</th>
                            <th>Nutritionist</th>
                            <th>Gender</th>
                            <th>Medical Problem</th>
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
                             <td>{{$patient->id}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                            <td>{{$patient->lead->country or ""}}</td>
                            <td>{{$patient->allergic or ""}}</td>
                            <td>{{$patient->doctor or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>
                            <td>{{$patient->lead->gender or ""}}</td>
                            <td>{{$patient->medical_problem}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div role="tabpanel" class="tab-pane" id="active">
                
                
                <table id="table-active" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patients Id</th>
                            <th>Patients Name</th>
                            <th>Country</th>
                            <th>Allergy</th>
                            <th>Doctor</th>
                            <th>Nutritionist</th>
                            <th>Gender</th>
                            <th>Medical Problem</th>
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
                            <td>{{$patient->id}}</td>
                            <td><a href="/lead/{{$patient->lead_id}}/viewPersonalDetails" target="_blank">{{$patient->lead->name or ""}}</a></td>
                             <td>{{$patient->lead->country or ""}}</td>
                            <td>{{$patient->allergic or ""}}</td>
                            <td>{{$patient->doctor or ""}}</td>
                            <td>{{$patient->nutritionist or ""}}</td>
                            <td>{{$patient->lead->gender or ""}}</td>
                            <td>{{$patient->medical_problem}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>                
  </div>
</div>
<!-- 
<script type="text/javascript">
  $(document).ready(function(){
    $('#table-region').dataTable({

      "iDisplayLength": 100

    });
});
</script> -->

<script type="text/javascript" src = "https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src = "//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src = "//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src = "//cdn.datatables.net/buttons/1.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table-active').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#table-age').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
             'csv'
        ]
    } );
} );
</script>