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
                            @foreach($leads AS $lead)
                                 <tr>
                                        <td>
                                            <input class='checkbox' type='checkbox' name='lead_ids[]' value="{{$lead['lead_id']}}">
                                            <input type="hidden" name="push[]" value="{{$lead['push']}}">
                                            <input type="hidden" name="phone[]" value="{{$lead['phone']}}">
                                            <input type="hidden" name="cre_name[]" value="{{$lead['cre_name']}}">
                                             <input type="hidden" name="dispo_date[]" value="{{$lead['dispo_date']}}">
                                             <input type="hidden" name="dispo_remark[]" value="{{$lead['dispo_remark']}}">
                                             <input type="hidden" name="callback[]" value="{{$lead['callback']}}">
                                             <input type="hidden" name="username[]" value="{{$lead['username']}}">
                                             <input type="hidden" name="source[]" value="{{$lead['source']}}">
                                             <input type="hidden" name="lead_status[]" value="{{$lead['lead_status']}}">
                                             <input type="hidden" name="lead_name[]" value="{{$lead['lead_name']}}">
                                             <input type="hidden" name="cre_assign_date[]" value="{{$lead['cre_assign_date']}}">
                                            
                                        </td>
                                         <td>
                                           <a href="/lead/{{$lead['lead_id']}}/viewDispositions" target="_blank">{{$lead['lead_name']}}</a>
                                        </td>
                                        <td>
                                           {{date('jS M, Y', strtotime($lead['cre_assign_date'])) }}
                                        </td>
                                        <td>
                                           {{$lead['lead_status']}}
                                        </td>
                                        <td>
                                          @if($lead['dispo_date']) {{date('jS M, Y', strtotime($lead['dispo_date'])) }} @endif<br><b>{{$lead['dispo_remark']}}</b>
                                        </td>
                                        <td>
                                           @if($lead['callback']) {{date('jS M, Y', strtotime($lead['callback'])) }} @endif
                                        </td>
                                        <td>
                                           {{$lead['source']}}
                                        </td>
                                       
                                        <td>
                                           {{$lead['output']}}
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