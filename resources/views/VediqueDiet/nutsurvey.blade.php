<div class="container">	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="pull-left">
				@include('/partials/daterange') 
			</div>
			<h4>Survey Results</h4> 
		</div>
		<div class="panel-body">
    		<!-- Nav tabs -->
      		<ul class="nav nav-tabs" role="tablist">
        		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
      		</ul>

      		<!-- Tab panes -->
     		<div class="tab-content">
        		<div role="tabpanel" class="tab-pane active" id="home">
          			<!--<a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Results Csv</a> -->
          			<table id="example" class="table table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Patient id</th>
            <th>Name</th>
	        <th>Nutritionist</th>
	        <th>Doctor</th>
            <th>Rating</th>
            <th>Score</th>
            <th width="30%">Feedback</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
   @foreach($patients AS $patient)
						@if($patient->name)
							<tr>
								<td>{{$i++}}</td>
                                <td><a href="/patient/{{$patient->patient_id}}/diet" target="_blank">{{$patient->patient_id or ' '}}</a></td>
								<td>{{$patient->name or ""}}</td>
								<td>{{$patient->nutritionist or " "}}</td>
								<td>{{$patient->doctor or " "}}</td>
								<td><span class="rating" data-score={{$patient->ratings}}></span></td>
								<td>{{$patient->score or " "}}</td>
								<td>{{$patient->feedback}}</td>
								<td>{{date('M j, Y',strtotime($patient->created_at))}}</td>
							</tr>
						@endif
			@endforeach
    </tbody>
</table>

<script type="text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.0/jquery.raty.min.js"></script>
<script type="text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.0/jquery.raty.min.css"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/s/dt/dt-1.10.10,r-2.0.0/datatables.min.css"></script>
<script type="text/javascript" src = "https://cdn.datatables.net/s/dt/dt-1.10.10,r-2.0.0/datatables.min.js"></script>
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
    var table = $('#example').DataTable({
    	"iDisplayLength": 100,
    	 dom: 'Bfrtip',
        buttons: [
            'csv'
        ],
        columnDefs: [
            { targets: 4, width: '100px' }
        ],
        createdRow: function(row, data, dataIndex){
            // Initialize custom control
            initRating(row);
        },
        responsive: true
    });
});




//
// Initializes jQuery Raty control
//
function initRating(container) {
    $('span.rating', container).raty({
        half: true,
        starHalf: 'https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.0/images/star-half.png',
        starOff: 'https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.0/images/star-off.png',
        starOn: 'https://cdnjs.cloudflare.com/ajax/libs/raty/2.7.0/images/star-on.png',
        score: function(){
            return $(this).data('score');
        },
        click: function(score){
            $(this).data('score', score);
        }
    });
}
</script>