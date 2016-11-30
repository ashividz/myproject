<?php  
    $date = isset($date) ? $date : date('Y-m-d');
?>
<div id="">
  <form action="" method="POST" role="form" id="form-date">
        <div class="form-group">
            <input type="text" name="date" id="date" size="25" value="{{date('m/d/Y',strtotime($date))}}" readonly/>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<script type="text/javascript">
$(function() {
    $('#date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,         
    });
    $('#date').on('apply.daterangepicker', function(ev, picker) 
    {    
      $('#form-date').submit();
    });
});
</script>