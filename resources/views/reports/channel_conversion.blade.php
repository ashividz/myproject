<div class="container">  
    <div class="panel panel-default">
        <div class="panel-heading">
      <div class="pull-left">
        @include('partials/daterange')
      </div>
        <h4 style="margin-left:400px">Channel Conversion Report</h4>
        </div>
        <div class="panel-body"><!-- Nav tabs -->
      <table id="table" class="table">
            <thead>
                <tr>
                <th>Source</th>
                    <th>Leads</th>
                <th>Conversion</th>
            <th>%</th>
            <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>

        @foreach($sources AS $source)
                <tr>
<<<<<<< HEAD
                    <td><a class='source_links' href="/report/source/{{$source->source_id}}/leads">{{$source->master->source_name or ""}}</a></td>
=======
                    <td>
                    @if(Auth::user()->hasRole('marketing'))
                        <a class='source_links' href="/report/source/{{$source->source_id}}/leads">{{$source->master->source_name or ""}}</a>
                    @else
                        {{$source->master->source_name or ""}}
                    @endif
                    </td>
>>>>>>> 180ed454bcac3922fbc29fc6372f3d75313f9345
            <td>{{$source->leads}}</td>

<?php $fee = Fee::conversionCountBySource($source->source_id, $start_date, $end_date) ?>

            <td>{{$fee->count()}}</td>
        
        @if($source->leads > 0)
            <td>{{round($fee->count()/$source->leads*100, 2)}}</td>
        @else
            <td></td>
        @endif
            <td>{{$fee->sum('total_amount')}}</td>
            
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
  $('#table').dataTable({
    "bPaginate": false,
    "aaSorting": [[ 4, "desc" ]]
  });

  $('.source_links').click(function(e){
    e.preventDefault();
    var frameSrc = $(this).attr('href');
    var date_selected = $('#daterange').val();
    var lnk = frameSrc +"?daterange="+date_selected;
    window.location = lnk;
    
});
});
</script>