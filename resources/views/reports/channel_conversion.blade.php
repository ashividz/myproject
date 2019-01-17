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
                <th>Date-conversion</th>
            <th>%</th>
            <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>

        @foreach($sources AS $source)
                <tr>
                    <td>
                    @if(Auth::user()->hasRole('marketing') || Auth::user()->hasRole('sales') || Auth::user()->hasRole('sales_tl'))
                        <a class='source_links' href="/report/source/{{$source->source_id}}/leads">{{$source->master->source_name or ""}}</a>
                    @else
                        {{$source->master->source_name or ""}}
                    @endif
                    </td>
            <td>{{$source->leads}}</td>

            <?php $fee   =    Fee::conversionCountBySource($source->source_id, $start_date, $end_date) ?>
            <?php $cfee  =    Fee::conversionCountByDate($source->source_id, $start_date, $end_date) ?>
            <?php $productfee     =    App\Models\ProductFee::conversionCountBySource($source->source_id, $start_date, $end_date) ?>
            <?php $productcfee     =    App\Models\ProductFee::conversionCountByDate($source->source_id, $start_date, $end_date) ?>
            @if($source->source_id == 112)
                <td>{{$productfee->count()}}</td>
                 <td>{{$productcfee->count()}}</td>
            @else
                 <td>{{$fee->count()}}</td>
                 <td>{{$cfee->count()}}</td>
            @endif
            @if($source->leads > 0)
                @if($source->source_id == 112)
                     <td>{{round($productfee->count()/$source->leads*100, 2)}}</td>
                @else
                    <td>{{round($fee->count()/$source->leads*100, 2)}}</td>
                @endif
            @else
                <td></td>
            @endif
            <?php
              $amount = 0;

              if($source->source_id == 112)
              {
                foreach ($productfee as $f) {
                if($f->currency_id == 2)
                {
                  $amount = $amount + ($f->total_amount)*65;
                }
                else{
                  $amount = $amount + $f->total_amount;
                }
              }
              }
              else
              {
                 foreach ($fee as $f) {
                if($f->currency_id == 2)
                {
                  $amount = $amount + ($f->total_amount)*65;
                }
                else{
                  $amount = $amount + $f->total_amount;
                }
              }
              }
             
            ?>
            <td>{{$amount}}</td>

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
