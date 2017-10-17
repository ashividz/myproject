<div class="container">
    <div class="panel panel-default">
            <div class="panel-heading">
               <h4 style='display: inline-block;margin-right: 20px;'>Lead Push Status</h4>
                
               
            </div>  
            <div class="panel-body">
            <form id="push-form" class="form-horizontal" action="/marketing/dialer_push" method="post">

                {!! csrf_field() !!}  
                <div class="form-group">
                    <input type="text" name="daterange" id="daterange" size="25" value="{{ date('M j, Y', strtotime($start_date)) }} - {{ date('M j, Y', strtotime($end_date)) }}" readonly/>
                </div>                   
               <div class="form-group">
                    <label for="list_id" class="col-sm-2">Data Type</label>
                    <div class="col-sm-5">
                      <input type="checkbox" name="new" value="female"> new
                      
                    </div>
                </div>
                <div class="form-group">
                    <label for="list_id" class="col-sm-2">List Id</label>
                    <div class="col-sm-5">
                      <input type="text" name="list_id" value="SALES16082016" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="dialerUserName" class="col-sm-2">Dialer user name</label>
                    <div class="col-sm-5">
                      <input type="text" name="dialerUserName" value="admin"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="dialerUserName" class="col-sm-2">Dialer Password</label>
                    <div class="col-sm-5">
                        <input type="password" name="dialerPassword"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="campname" class="col-sm-2">Campaign Name</label>
                    <div class="col-sm-5">
                        <input type="text" name="campname" value="Sales_Outbound"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="skillname" class="col-sm-2">Skill Name</label> 
                    <div class="col-sm-5">
                        <input type="text" name="skillname" value="ENGLISH"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastFollowUp" class="col-sm-2">Last Follow up day</label> 
                    <div class="col-sm-5">
                        <input type="number" name="lastFollowUp" value="7"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="onlyNotInt" class="col-sm-2">Include only not interested data</label> 
                    <div class="col-sm-5">
                        <input type="checkbox" name="onlyNotInt" value="1" />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-7">
                        <button class="btn btn-primary">Push Leads</button>
                    </div>
                </div>

                <!-- <button id='checkstat' class="btn btn-primary">Check</button> -->                
                <input type='hidden' name='username' value='{{ $username or "" }}' />
                <!-- <h5 id='jobstat'>{{ ($job)? 'Job already running..': ''}}</h5> -->

            </form>

            <div>
            {{$msg}}
            </div>
            </div>          
    </div>
</div>
<script type="text/javascript">
var timer; 
function jobCheck()
{
  isRunning();

  timer = setTimeout(function(){jobCheck();}, 6000);
}

function isRunning() {
    var start_date = '{{ $start_date }}';
    var end_date = '{{ $end_date }}';
    $.getJSON("/api/isPredictiveJobRunning", {}, function(result){
      
        if(result=="1")
        {
          $('#jobstat').html("Job running..<img width='25' src='/images/loading1.gif' />");
        }
        else
        {
          $('#jobstat').html('Job Finished! Lead Pushed '+result);
          clearTimeout(timer);
        }
         })
      
    .fail(function(jqXHR, textStatus, errorThrown) { 
        $('#alert').show();
        $('#alert').empty().append('getJSON request failed! ' + textStatus);
    })
}

$(document).ready(function() 
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

           $('#conversionDate').daterangepicker(
    {   
        singleDatePicker: true,
        showDropdowns: true,
        ranges: 
        {
            'Today': [new Date(), new Date()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), new Date()],
            'Last 30 Days': [moment().subtract(29, 'days'), new Date()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }, 
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#conversionDate').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#conversionDate').trigger('change'); 
    });


        $("#checkstat").click(function(e){
          e.preventDefault();
          
            jobCheck();
        });

  $('#daterange').on('apply.daterangepicker', function(ev, picker) 
  {    
      $('#push-form1').submit();
  });

   $("#push-form1").submit(function(event) {
        event.preventDefault();
        var url = $("#push-form").attr('action');
         $.ajax(
        {
           type: "POST",
           url: url,
           data: $("#push-form").serialize(), // serializes the form's elements.
           success: function(data)
           {
               jobCheck();
           }
        });
        jobCheck();
        return false;
  }); 
  
});

</script>