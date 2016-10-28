<div id="">
<h2>Source Lead Download</h2>
    <form class="form-inline" id='form_block' action="" method="POST" role="form" id="formx">
        
        <div class="form-group">
          <select name="source" id="source">
            <option value="">Select Source</option>

          @foreach($sources AS $source)

                <option value="{{ $source->id }}" >{{ $source->source_name }}</option>

          @endforeach 

          </select>
        </div>
        
        <div class="form-group">
            <input type="text" name="daterange" id="daterange" size="25" value="{{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}" readonly/>
            <a class='btn btn-primary' href='' id='downloadlnk'>Download</a>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<script type="text/javascript">
$(document).ready(function () 
{
   $('#downloadlnk').click(function(e)
    {    
        e.preventDefault();
        var lnk = '/download/sourceLeads?daterange=' + $('#daterange').val()+'&source='+$('#source').val();
        window.location = lnk;
    });


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
      $('#formx').submit();
  });
});
</script>
<style type="text/css">
    #form_block
    {

        padding: 30px;
    }
    #downloadlnk
    {
        margin-left: 10px;
        padding: 5px 10px;
    }
</style>