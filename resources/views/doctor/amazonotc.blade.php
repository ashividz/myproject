<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">             
            <div class="pull-left">
                @include('partials/daterange')
            </div>
            <h4 style="margin-left:400px">Amazon OTC</h4>
    </div>
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#age" aria-controls="age" role="tab" data-toggle="tab">Patients</a></li>
            
        </ul>
        <br>

        <!-- Tab panes -->
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane  active" id="age">
                
            
                <table id="table-age" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Amazon Lead Id</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Doctor</th>
                            <th>Source</th>
                            <th>Created_at</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($patients AS $patient)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$patient->id}}</td>
                            <td><a href="/amazon/{{$patient->id}}/disposition" target="_blank">{{$patient->name or ""}}</a></td>
                            <td>{{$patient->amount}}</td>
                            <td>{{$patient->doctor}}</td>
                            <td>{{$patient->source}}</td>
                            <td>{{$patient->created_at}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
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