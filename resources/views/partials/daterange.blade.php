<?php
  
    $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
    $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');
?>
<form action="" method="POST" role="form" id="form-daterange">
  <div class="form-group">
      <input type="text" name="daterange" id="daterange" size="25" value="{{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}" readonly/>
  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
<script type="text/javascript">
$(document).ready(function () 
{
  
  $('#daterange').daterangepicker(
    { 
      ranges: 
      {
         'Today': [new Date(), new Date()],
         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
         'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
         'This Month': [moment().startOf('month'), moment().endOf('month')],
         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }, 
      format: 'MMM D, YYYY' 
    }
  );

  $('#daterange').on('apply.daterangepicker', function(ev, picker) 
  {    
      $('#form-daterange').submit();
  });
});
</script>