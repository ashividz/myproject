<div class="container">
 <div class="panel panel-default">
@include('partials/daterange')
            <div class="panel-heading">
               
                <h4>Self Assign Report</h4>
            </div>  
            <div class="panel-body">

                <form id="form" class="form-inline" action="/marketing/leads/churn/save" method="post">
                    <p style='font-weight: bold'>Pushed: {{$leads_in_dialer}} | Self Assigns: {{count($leads)}} | Converted: {{$converted}}</p>
                    <table id="leads" class="table table-bordered">
                        <thead>
                            <tr>
                               
                                
                                <td>CRE</td>
                                <td>Lead</td>
                                <td>Date</td>
                                <td>Disposition</td>
                            </tr>
                        </thead>
                        <tbody>
                        
                    @foreach($leads AS $lead)
                    
                            <tr>
                                <td>
                                    {{$lead->cre}}
                                </td>
                                <td>
                                  <a target='_blank' href='http://amikus/lead/{{$lead->lead->id}}/viewDispositions'>{{$lead->lead->name}}</a>
                                </td>
                                 <td>
                                  {{$lead->lead->cre->created_at}}
                                  
                                </td>
                                 <td>
                                  <span style='color: #444444;font-weight: bold'>{{$lead->lead->disposition->master->disposition}}</span>
                                  <span style='color: #555555;font-size: 12px'>{{$lead->lead->disposition->remarks}}</span>
                                  <br><em><span style='color: #555555;font-size: 12px'>{{$lead->lead->disposition->created_at}}</span></em>
                                </td>
                            </tr>

                    @endforeach

                        </tbody>
                    </table>
                 
                    
                </form>
            </div>          
    </div>
    
</div>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#leads').dataTable({
        "bFilter" : false,
        "bPaginate" : false,
        "aaSorting": [[ 6, "desc" ]]
    });

    $("#form").submit(function(event) {


        event.preventDefault();
        /* stop form from submitting normally */
        
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