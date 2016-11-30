<div id="">
	<form class="form-inline" action="" method="POST" role="form" id="formx">
        
        <div class="form-group">
          <select name="user" id="user">
            <option value="">Select All</option>

          @foreach($users AS $user)

                <option value="{{ $user->name }}" {{ $user->name == $name ? 'selected' :'' }}>{{ $user->name }}</option>

          @endforeach 

          </select>
        </div>
        
        <div class="form-group">
            <input type="text" name="daterange" id="daterange" size="25" value="{{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}" readonly/>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<script type="text/javascript">
$(document).ready(function () 
{
    $('select[name="user"]').change(function()
    {    
        $('#formx').submit();
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