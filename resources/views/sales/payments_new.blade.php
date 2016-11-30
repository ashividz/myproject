<?php 
    $daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
    $start_date = isset($daterange[0]) ? date('Y/m/d 0:0:0', strtotime($daterange[0])) : date("Y/m/d 0:0:0");
?>
@include('partials/daterange', array('start_date' => $start_date, 'ajax' => 1))    
    <a id="downloadCSV" class="btn btn-primary pull-right" style="margin-bottom:2em;">download orders</a>

    <table id="orders" class="table table-bordered">
        
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Transaction Id</th>
                <th>Name</th>
                <th>Country</th>
                <th>City</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Status Message</th>
                <th>CRE</th>
            </tr>
            
        </thead>
    </table>
<div id='wait' style='display: none'><i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw margin-bottom"></i>
</div>
<script type="text/javascript">

function AutoReload()
{
  getOrders();

  setTimeout(function(){AutoReload();}, 30000);
}

$(document).ready(function () {
    //$('#orders').dataTable();
    getOrders();
    setTimeout(function(){AutoReload();}, 30000);
});




function getOrders() {
    $('#wait').fadeIn();
    var start_date = '{{ $start_date }}';
    var end_date = '{{ $end_date }}';
    $.getJSON("/api/onlinePaymentsNew", {'start_date': start_date, 'end_date' : end_date}, function(result){
        $('#alert').hide();
        $("#orders tbody").empty();
        var i = 0;
        $.each(result, function(i, field) {  
            i++;
            if (field.post_status == 'failed') {
                status = 'fail';
            }
            else if (field.post_status == 'completed' || field.post_status == 'processing'  ) {
                status = 'success'
            }
            else {
                status = "incomplete";
            }

            if(field.lead_id)
                $lead_link = " <span class='pull-right'> <a href='/lead/"+field.lead_id+"' target='_blank'><button><i class='fa fa-arrows-alt'></i></button></a></span>";
            else
               $lead_link = "";   
            $("#orders").append("<tr class='" + status + "'>");
            $("#orders tr:last").append("<td>" + i + "</td>");
            $("#orders tr:last").append("<td>" + field.order_date + "</td>");
            $("#orders tr:last").append("<td>" + field.transaction_id + "</td>");
            $("#orders tr:last").append("<td>" + field.firstname + " " + field.lastname + $lead_link+"</td>");
            $("#orders tr:last").append("<td>" + field.country + "</td>");
            $("#orders tr:last").append("<td>" + field.city + "</td>");
            $("#orders tr:last").append("<td><span style='text-transform: uppercase'>" + field.payment_method + "</span></td>");
            $("#orders tr:last").append("<td>" + field.currency + " " + field.total_amount + "</td>");
            $("#orders tr:last").append("<td><b>" + field.post_status +"</b><br><span style='font-size: 11px'>"+field.note+"</span>"+ "</td>");
            $("#orders tr:last").append("<td>" + field.cre + "</td>");
            $("#orders").append("</tr>");
        });
    $('#wait').fadeOut();
    })
   
}
</script>

<style type="text/css">
    #orders tr.success td {
        background-color: rgb(108, 208, 147);
    }
    .fail {
        background-color: rgb(236, 106, 106);
    }
    .incomplete {
        background-color: rgb(240, 240, 140);
    }
</style>

<script type="text/javascript">
$(document).ready(function() 
{
    

    $( "#downloadCSV" ).bind( "click", function() 
    {
        var csv_value = $('#orders').table2CSV({
                delivery: 'value'
            });
        downloadFile('orders.csv','data:text/csv;charset=UTF-8,' + encodeURIComponent(csv_value));
        $("#csv_text").val(csv_value);  
    });

    function downloadFile(fileName, urlData){
        var aLink = document.createElement('a');
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("click");
        aLink.download = fileName;
        aLink.href = urlData ;
        aLink.dispatchEvent(evt);
    }
});
</script>                   
</div>
