<div class="container">  
    <div class="panel panel-default">
        <div class="panel-heading">             
                <div class="pull-left">
                    @include('partials/daterange')
                </div>
                <h4 style="margin-left:400px">Not Converted Leads</h4>
        </div>
        <div class="panel-body">
           
            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane  active" id="age">
                     <table id="table-age" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lead Id</th>
                                <th>Patients Name</th>
                                <th>Mobile</th>
                                <th>Phone</th>
                                <th>Email ID</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>                          
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($leads AS $lead)
                           
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$lead->id or ""}}</td>
                                    <td>{{$lead->name or ""}}</td>
                                    <td>{{$lead->mobile or ""}}</td>
                                    <td>{{$lead->phone or ""}}</td>
                                    <td>{{$lead->email or ""}}</td>
                                    <td>{{$lead->city or ""}}</td>
                                    <td>{{$lead->state or ""}}</td>
                                    <td>{{$lead->country or ""}}</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div> 
</div> 


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