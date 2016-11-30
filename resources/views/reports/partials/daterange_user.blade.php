<div id="">
  <form class="form-inline" action="" method="POST" role="form" id="formx">
        
        <div class="form-group">
         <select name="source" id="source">
            <option value='0'>Select Source</option>
         @foreach($sources AS $source)

                @if($source->id == $source_selected)
                    <option value="{{$source->id}}" selected>{{$source->source_name}}</option>
                @else
                    <option value="{{$source->id}}">{{$source->source_name}}</option>
                @endif

          @endforeach 
 
          </select>
          <select name="user" id="user">
            <option value=''>Select CRE</option>

          @foreach($users AS $user)

                @if($user->name == $name)
                    <option value="{{$user->name}}" selected>{{$user->name}}</option>
                @else
                    <option value="{{$user->name}}">{{$user->name}}</option>
                @endif

          @endforeach 
 
          </select>
          <select name="status" id="status">
            <option value=''>Select Status</option>

          @foreach($statuses AS $status)

                @if($status->id == $status_id)
                    <option value="{{$status->id}}" selected>{{$status->name}}</option>
                @else
                    <option value="{{$status->id}}">{{$status->name}}</option>
                @endif

          @endforeach 

          </select>
          <!--<select name="disposition" id="disposition">
            <option value=''>Select Disposition</option>

          @foreach($dispositions AS $disposition)

                @if($disposition->id == $disposition_id)
                    <option value="{{$disposition->id}}" selected>{{$disposition->disposition}}</option>
                @else
                    <option value="{{$disposition->id}}">{{$disposition->disposition}}</option>
                @endif

          @endforeach 

          </select>-->
        </div>
        
        <div class="form-group">
            <input type="text" name="daterange" id="daterange" size="25" value="{{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}" readonly/>
        </div>

         <div class="form-group">
         <input type="text" name="limit" id="limit" size="5" value="{{$limit}}" />
           
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
<script type="text/javascript">
$(document).ready(function () 
{
    $('select[name="source"]').change(function()
    {    
        $('#formx').submit();
    });

    $('select[name="user"]').change(function()
    {    
        $('#formx').submit();
    });

    $('select[name="status"]').change(function()
    {    
        $('#formx').submit();
    });

    $('select[name="disposition"]').change(function()
    {    
        $('#formx').submit();
    });

 $('input[name="limit"]').on('blur',function()
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