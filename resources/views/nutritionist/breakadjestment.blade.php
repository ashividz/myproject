<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">             
            <div class="pull-left">
                @include('partials/daterange')
            </div>
            <h4 style="margin-left:400px">Break</h4>
    </div>
    <div class="panel-body">
        <!-- Nav tabs -->
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
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Last Diet</th>
                            <th>Doctor</th>
                            <th>Nutritionist</th>
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($coustomers AS $customer)
                        <tr>
                            <td>{{$i++}}</td>
                             <td>{{$customer->patient->id}}</td>
                            <td><a href="/lead/{{$customer->id}}/viewPersonalDetails" target="_blank">{{$customer->name or ""}}</a></td>
                            <td>{{$customer->country or ""}}</td>
                            <td>{{$customer->patient->fee->start_date or ""}}</td>
                            <td>{{$customer->patient->fee->end_date or ""}}</td>
                            <td>{{$customer->patient->diet->date_assign or ""}}</td>
                            <td>{{$customer->patient->doctor or ""}}</td>
                            <td>{{$customer->patient->nutritionist or ""}}</td>
                            <td>{{$customer->gender or ""}}</td>
                            
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