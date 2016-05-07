@include('lead.dialerpush.daterange')

<div class="container">
    <div class="panel panel-default">
            <div class="panel-heading">
               <h4 style='display: inline-block;margin-right: 20px;'>Lead Push Status</h4>
                
               
            </div>  
            <div class="panel-body">
            <form id="push-form" class="form-inline" action="/dialer/push/leads" method="post">

            {!! csrf_field() !!}                     
            <table id="leads1" class="table table-bordered">
                        <thead>
                                <th>#</th>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Lead</th>
                                <th>Created At</th>
                                <th>CRE</th>
                                <th>Status</th>
                                <th width="30%">Last Disposition </th>
                                <th>Source</th>
                        </thead>
                        <tbody>
                            @foreach($leads AS $lead)
                                 <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            <input type='checkbox' class='checkbox'  name='id[]' value="{{ $lead->id }}">
                                            <input type="hidden" name="phone[]" value="{{ $lead->phone }}">
                                             @if($lead->cre)
                                            <input type="hidden" name="cre_name[]" value="{{$lead->cre->cre}}">
                                            @endif
                                        </td>
                                        <td>
                                           <a href="/lead/{{$lead->id}}/viewDispositions" target="_blank">{{$lead->name}}</a> 
                                            <span class="pull-right">{{ $lead->country }}</span>
                                        </td>
                                        <td>
                                            {{ $lead->created_at->format('jS M, Y') }}
                                        </td>
                                        <td>
                                    @if($lead->cre)
                                            {{ $lead->cre->cre }} 
                                            <span class="pull-right">
                                                <small>{{ $lead->cre->created_at }}</small>
                                            </span>
                                    @endif
                                        </td>
                                        <td>
                                           {{ $lead->status->name or "" }}
                                        </td>
                                        <td>
                                    @if($lead->disposition) 
                                            <b>{{ $lead->disposition->master->disposition_code or "" }} : </b>
                                            <i>{{ $lead->disposition->remarks }}</i> 
                                            <span class="pull-right">
                                                <small>{{ $lead->disposition->name }} @ {{ $lead->disposition->created_at }} </small>
                                            </span>
                                    @endif
                                        </td>
                                        <td>
                                           {{ $lead->source->master->source_name or "" }}
                                        </td>
                                    </tr>

                            @endforeach
   
                        </tbody>
                    </table>
                     <button class="btn btn-primary">Push Leads</button>

                </form>

            </div>          
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({
      "iDisplayLength" : 100,
      "bPaginate": false,
        "bInfo" : true
    });

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    $("#push-form").submit(function(event) {


        event.preventDefault();
        /* stop form from submitting normally */

        var url = $("#push-form").attr('action'); // the script where you handle the form input.
        $('#alert').show();
        if($('input[name="id[]"]:checked').length == 0)
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
                        //location.reload();
                     });
                }, 100000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
    }); 
});

</script>