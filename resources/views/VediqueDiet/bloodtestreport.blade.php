<div class="container">  
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-left">
        @include('partials/daterange')
      </div>
      <h4>appointment</h4>
    </div>
    <div class="panel-body">
    <!-- Nav tabs -->
      
      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
          <br>

          <table id="example" class="table">
            <thead>
              <tr>
               <th>#</th>
               <th>Name</th>
               <th>Phone</th>
               <th>email</th>
               <th>Bloodtest</th>
               
               <th>Created_at</th>
              </tr>
            </thead>
            <tbody>
          
          <?php $i = 0 ?>
          @foreach($users  AS $user)


            <?php $i++ ?>
              <tr>
                <td>{{$i}}</td>
               
                 <td>{{$user->name or " "}}</td>
                 <td>{{$user->phone or " "}}</td>
                 <td>{{$user->email or " "}}</td>
                 <td><img src="data:image/png;base64 , {{$user->image}}"/></td>
                 <td>{{$user->created_at or " "}}</td>
          @endforeach   
          
            </tbody>
          </table>
        </div>

        <!-- Nutritionist Summary Report -->
        
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
    $('#referreri').DataTable( {
        "iDisplayLength": 100,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );
</script>
<script type="text/javascript">
$(document).ready(function() 
{
  $(document).ready(function() {
    $('#example').DataTable( {
        "iDisplayLength": 1000,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    } );
} );

  $(".editable_voice").editable("/lead/saveVoice", { 
      loadurl   : "/api/getVoices",
      type      : "select",
      submit    : '<button class="btn btn-primary" type="submit">Ok</button>',
      cancel    : '<button class="btn btn-danger" type="cancel">Cancel</button>',
      placeholder: '<span class="placeholder">(Edit)</span>',
  });

  $( "#downloadCSV" ).bind( "click", function() 
  {
    var csv_value = $('#example').table2CSV({
                delivery: 'value'
            });
    downloadFile('leads.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
    $("#csv_text").val(csv_value);  
  });

  $( "#downloadSummary" ).bind( "click", function() 
  {
    var csv_value = $('#summary').table2CSV({
                delivery: 'value'
            });
    downloadFile('summary.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
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
  h4 {
    margin-left: 250px; 
  }
</style>

