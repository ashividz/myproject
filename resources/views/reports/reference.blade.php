<div class="container">  
	<div class="panel panel-default">
		<div class="panel-heading">
      <div class="pull-left">
        @include('partials/daterange')
      </div>
      <h4>References</h4>
		</div>
		<div class="panel-body">
    <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">Sourced By Summary</a></li>
        <li role="presentation"><a href="#referrer" aria-controls="referrer" role="tab" data-toggle="tab">Referrer Summary</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
          <a name="download" id="downloadCSV" class="btn btn-primary pull-right" style="margin:10px" download="filename.csv">Download Leads Csv</a>

  	    	<table id="example" class="table">
  	    		<thead>
  	    			<tr>
  	    				<th width="5%"></th>
  			            <th>Date</th>
  			            <th>Sourced By</th>
  			    		    <th>Name</th>
  			    		    <th>Referrer</th>
  			            <th>Voice</th>
                    <th>CRE</th>
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
  	    				<td><a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{trim($lead->name) <> "" ? $lead->name : "No Name"}}</a></td>
  	    				<td><a href="/lead/{{$lead->referrer_id}}/viewReferences" target="_blank">{{$lead->referrer_name}}</a></td>

            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))  
                <td><div class='editable_voice' id='{{ $lead->source->id }}'>{{$lead->source->voice->name or ""}}</div></td> 
            @else
                <td>{{$lead->source->voice->name or ""}}</td>
            @endif
                <td>{{$lead->cre->cre or ""}}</td>
                <td>{{$lead->patient->fee->entry_date or ""}}</td>
                
  	    			</tr>

  	    	@endforeach		
  	    	
  	    		</tbody>
  	    	</table>
        </div>

        <!-- Nutritionist Summary Report -->
        <div role="tabpanel" class="tab-pane fade" id="summary">        
          <div class="container">
          <a name="download" id="downloadSummary" class="btn btn-primary pull-right" style="margin:10px" download="summary.csv">Download Summary Csv</a>
            <p>
            <table id="summary" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>References</th>
                  <th>Leads Conversions</th>
                  <th>Percentage</th>
                  <th>Conversions in Date Range</th>
                  <th>Sourced and converted in same Date Range</th>
                </tr>
              </thead>
              <tbody>

            @foreach($summaries AS $summary)  
                <tr>
                  <td>{{$summary->sourced_by}}</td>
                  <td>{{$summary->leads}}</td>
                  <td>{{$summary->conversions}}</td>

                @if($summary->conversions <> 0)                    
                  <td>{{round($summary->conversions/$summary->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
<?php 
  $fee = Fee::conversions($start_date, $end_date, 10, $summary->sourced_by);
?>
                  <td>{{$fee->count()}}</td>
                  <td>{{$summary->sameDateRangeConversion }}</td>
                  
                </tr>

            <?php 
              $total_leads += $summary->leads;
              $total_conversions += $summary->conversions;
              $total_conversions_same_range  += $total_conversions_same_range;
            ?>

            @endforeach

              </tbody>
              <tfoot>
                <tr style="background-color:#f2f2f2;">
                  <th>Total</th>
                  <th>{{$total_leads}}</th>
                  <th>{{$total_conversions}}</th>
                  <th>{{$total_leads > 0 ? round($total_conversions/$total_leads*100, 2) : '0'}} %</th>
                  <th>{{Fee::conversions($start_date, $end_date, 10)->count()}}</th>
                  <th>{{$total_conversions_same_range}}</th>
                </tr>
              </tfoot>
            </table>       
          </div>
        </div>

        <!-- Referrer Summary Report -->
        <div role="tabpanel" class="tab-pane fade" id="referrer">        
          <div class="container">
            <p>
            <table id="referrer" class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>References</th>
                  <th>Conversions</th>
                  <th>Percentage</th>
                </tr>
              </thead>
              <tbody>

            @foreach($referrers AS $referrer)  
                <tr>
                  <td><a href="/lead/{{$referrer->id}}/viewReferences" target="_blank">{{$referrer->name}}</a></td>
                  <td>{{$referrer->leads}}</td>
                  <td>{{$referrer->conversions}}</td>

                @if($referrer->conversions <> 0)                    
                  <td>{{round($referrer->conversions/$referrer->leads*100, 2)}} %</td>
                @else
                  <td></td>
                @endif
                  
                </tr>

            @endforeach

              </tbody>
            </table>       
          </div>
        </div>

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