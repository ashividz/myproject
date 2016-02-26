<div id="">
  <form class="form-inline" action="" method="POST" role="form" id="formx">
        
        <div class="form-group">
          <select name="user" id="user">
            <option>Select User</option>

          @foreach($users AS $user) 

                @if($user->name == $name)
                    <option value="{{$user->name}}" selected>{{$user->name}}</option>
                @else
                    <option value="{{$user->name}}">{{$user->name}}</option>
                @endif

          @endforeach 

          </select>
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
    $('select[name="user"]').change(function()
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

<div class="container">
    <div class="panel panel-default">
            <div class="panel-heading">
               <h4 style='display: inline-block;margin-right: 20px;'>Lead Push Status</h4>
                
               
            </div>  
            <div class="panel-body">
            <form id="push-form" class="form-inline" action="/dialer/push/leads" method="post">
            <table id="leads" class="table table-striped">
                        <thead>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Lead</th>
                                <th>CRE Assigned on</th>
                                <th>Status</th>
                                <th>Disposition </th>
                                <th>Callback</th>
                                <th>Source</th>
                                <th>Push Status </th>
                        </thead>
                        <tbody>
                            @foreach($push_stats AS $push_stat)
                                 <tr>
                                        <td>
                                            <input class='checkbox' type='checkbox' name='lead_ids[]' value="{{$push_stat['lead_id']}}">
                                            <input type="hidden" name="push[]" value="{{$push_stat['push']}}">
                                            <input type="hidden" name="phone[]" value="{{$push_stat['phone']}}">
                                            <input type="hidden" name="cre_name[]" value="{{$push_stat['cre_name']}}">
                                             <input type="hidden" name="dispo_date[]" value="{{$push_stat['dispo_date']}}">
                                             <input type="hidden" name="dispo_remark[]" value="{{$push_stat['dispo_remark']}}">
                                             <input type="hidden" name="callback[]" value="{{$push_stat['callback']}}">
                                             <input type="hidden" name="username[]" value="{{$push_stat['username']}}">
                                             <input type="hidden" name="source[]" value="{{$push_stat['source']}}">
                                             <input type="hidden" name="lead_status[]" value="{{$push_stat['lead_status']}}">
                                             <input type="hidden" name="lead_name[]" value="{{$push_stat['lead_name']}}">
                                             <input type="hidden" name="cre_assign_date[]" value="{{$push_stat['cre_assign_date']}}">
                                            
                                        </td>
                                         <td>
                                           <a href="/lead/{{$push_stat['lead_id']}}/viewDispositions" target="_blank">{{$push_stat['lead_name']}}</a>
                                        </td>
                                        <td>
                                           {{date('jS M, Y', strtotime($push_stat['cre_assign_date'])) }}
                                        </td>
                                        <td>
                                           {{$push_stat['lead_status']}}
                                        </td>
                                        <td>
                                          @if($push_stat['dispo_date']) {{date('jS M, Y', strtotime($push_stat['dispo_date'])) }} @endif<br><b>{{$push_stat['dispo_remark']}}</b>
                                        </td>
                                        <td>
                                           @if($push_stat['callback']) {{date('jS M, Y', strtotime($push_stat['callback'])) }} @endif
                                        </td>
                                        <td>
                                           {{$push_stat['source']}}
                                        </td>
                                       
                                        <td>
                                           {{$push_stat['output']}}
                                        </td>
                                         
                                  </tr>

                            @endforeach
   
                        </tbody>
                    </table>
                     <button class="btn btn-primary">Push Leads</button>
                    {!! csrf_field() !!}

                     
                     

                </form>
                    
                

            </div>          
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({      
        "bInfo" : true,
        "iDisplayLength" : 100
    });

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $("#push-form").submit(function(event) {


        event.preventDefault();
        /* stop form from submitting normally */

        var url = $("#push-form").attr('action'); // the script where you handle the form input.
        $('#alert').show();
        if($('input[name="lead_ids[]"]:checked').length == 0)
            {
                $('#alert').empty().append("<p>No Lead Selected To Push..</p>");
                setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut();
                }, 2000);
            
            return false;
            }
        
        $('#alert').empty().append("<p>Processing Please Wait..</p>");
        $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#push-form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data);

               $('#alert').empty().append(data);
               setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 10000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
    }); 
});

</script>