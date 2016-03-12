<div class="container">
 <div class="panel panel-default">
<div >
                    @include('reports.partials.daterange_user')
                </div>
            <div class="panel-heading">
               
                <h4>Source Leads</h4>
            </div>  
            <div class="panel-body">

                <form id="form" class="form-inline" action="/marketing/leads/churn/save" method="post">
                    <table id="leads" class="table table-bordered">
                        <thead>
                            <tr>
                                <td><input type="checkbox" id="checkAll"></td>
                                <td>Name</td>
                                <td>CRE</td>
                                <td>CRE Assigned Date</td>
                                <td>Source</td>
                                <td>Status</td>
                                <td 0.>Last Call</td>
                                <td >Callback</td>
                                <td>Last Call (Days) </td>
                            </tr>
                        </thead>
                        <tbody>

                    @foreach($leads AS $lead)

                            <tr>
                                <td>
                                    <input class='checkbox' type='checkbox' name='check[]' value="{{$lead->id}}">
                                </td>
                                <td>
                                    <a href="/lead/{{$lead->id}}/viewDetails" target="_blank">{{ trim($lead->name) <> "" ? $lead->name : "No Name"}}</a>
                                </td>
                                <td>
                                   {{ $lead->cre->cre}} 
                                </td>
                                <td>
                                   {{date('jS M Y', strtotime($lead->cre->created_at))}}
                                </td>

                                <td>
                                    {{$lead->source->master->source_name or ""}}
                                </td>
                                <td>
                                    {{$lead->status->name or ""}}
                                </td>
                                <td>
                                    @if($lead->disposition)
                                        <b>{{$lead->disposition->master->disposition}} : </b>
                                        {{$lead->disposition->remarks}}

                                        <small class="pull-right">
                                            <em>[
                                            {{date('jS M Y, h:i A', strtotime($lead->disposition->created_at))}}
                                            ]</em>
                                        </small>                                        

                                    @endif

                                </td>
                                <td>
                                    @if($lead->disposition)
                                        <small>
                                            {!! $lead->disposition->callback ? "<br><b>Callback : </b>" . date('jS M Y, h:i A', strtotime($lead->disposition->callback)) : "" !!}
                                        </small>
                                    @endif

                                </td>
                                <td style="text-align:center">
                                    @if($lead->disposition)
                                        {{floor((strtotime(date('Y/m/d')) - strtotime($lead->disposition->created_at))/(60*60*24)) + 1}}
                                    @endif
                                </td>
                            </tr>

                    @endforeach

                        </tbody>
                    </table>
                    <div class="form-control">
                        
                        <select name="cre" id="cre" required>
                            <option value="">Select User</option>

                        @foreach($users AS $user)
                            <option value="{{$user->name}}">{{$user->name}}</option>
                        @endforeach 

                        </select>
                        <button class="btn btn-primary">Churn Leads</button>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>          
    </div>
    
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({
        "bInfo" : true,
        "bPaginate" : false,
        "aaSorting": [[ 6, "desc" ]]
    });

    $("#form").submit(function(event) {


        event.preventDefault();
        /* stop form from submitting normally */
        alert('uhgu');
        var url = $("#form").attr('action'); // the script where you handle the form input.
         $('#alert').show();
        if($('input[name="check[]"]:checked').length == 0)
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
           data: $("#form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               $('#alert').empty().append(data);
               setTimeout(function()
                {
                    $('#alert').slideUp('slow').fadeOut(function() 
                    {
                        location.reload();
                     });
                }, 5000);
           }
        });
        return false; // avoid to execute the actual submit of the form.
    }); 

    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
});
</script>