<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title">Messages </span> 
			<div class="new-message">
				<span class="new-message-count"></span> new
			</div>
		</div>	
		<div class="panel-body">
			<table class="table" id="messages">
				<tbody>

				</tbody>
			</table>
		</div>
	</div>
</div>
<style type="text/css">
	.new-message {
		display: inline;
		margin-left: 10px;
		background-color: #c00;
		padding: 5px 10px;
		color: #fff;
		border-radius: 2px
	}

	.table tr:hover {
		background-color: #F5891A;
		color: #f9f9f9;
		cursor: pointer;
	}
	table tr.unread {
		font-weight: 800;
		background-color: #ffffcc;
	}
	table tr.read {
		background-color: #f4f4f4;
	}
</style>
<script type="text/javascript">

function autoReloadMessages()
{
  getMessages();

  setTimeout(function(){autoReloadMessages();}, 30000);
}

$(document).ready(function () {
    //$('#messages').dataTable();
    getMessages();
    setTimeout(function(){autoReloadMessages();}, 30000);
});


function getMessages() {
    var url = "/api/getMessages"
   	$.getJSON(url)
    	.success(function( data ) {
        $("#messages tbody").empty();
        var i = 0;

        	//console.log(data);
        $.each(data, function(i, field) { 
            i++;
            console.log(field.read_at);
            if (field.read_at == null) {
                status = 'unread';
            }
            else {
                status = 'read';
            };
            var subject = field.subject == null ? '(No subject)' : field.subject;

            $("#messages").append("<tr class='" + status + "' id='" + field.id + "'>");
            $("#messages tr:last").append("<td>" + i + "</td>");
            $("#messages tr:last").append("<td>" + field.from + "</td>");
            $("#messages tr:last").append("<td>" + subject + "</td>");
            $("#messages tr:last").append("<td>" + field.body + "</td>");

            if(field.lead == null) {
            	$("#messages tr:last").append("<td></td>");
            }
            else {
            	$("#messages tr:last").append("<td><div class='view'><a href='/lead/" + field.lead.id +"/viewDispositions' target='_blank'>" + field.lead.name + "<img class='aTn pull-right' src='/images/cleardot.gif'></a></div></td>");
            }
            
            var created_at = new Date(field.created_at);
            if (moment(created_at).isSame(moment(), 'day')) {
                created_at = moment(created_at).format("hh:mm a");
            } else {
                created_at = moment(created_at).format("MM MMM, hh:mm a");   
            }
            

            $("#messages tr:last").append("<td><div class='pull-right'>" + created_at + "</div></td>");;
            $("#messages").append("</tr>");
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) { alert('getJSON request failed! ' + textStatus); })
}
</script>
<script type="text/javascript">

	//Table row click
	$("#messages").on('click', 'tr', function() {
		var id = this.id;
		var url = "/message/toggle"; 
        $.ajax(
        {
           type: "POST",
           url: url,
           data: {id : id, "_token" : "{{ csrf_token()}}" }, // send Source Id.
           success: function(data)
           	{               
				$('#' + id).toggleClass("unread");
				$('#' + id + ' td.new_message').toggleClass("new-message");
				getUnreadMessageCount();
           	}
        });
	});
</script>
<style type="text/css">
    .table tr {
        border-bottom: solid 1px #dedede;
    }
    .view {
        border: solid #ddd 1px;
        border-radius: 4px;
        padding: 2px 15px 2px 10px;
        background-color: #dedede;
    }
    .view a {        
        color: #333;
    }
    .view a, a:visited {
        text-decoration: none;
    }
    .view .aTn {
        background: no-repeat url(/images/smartmail.png) -67px -100px;
        width: 7px;
        height: 7px;
        opacity: .55;
        margin-top: 2px;
    }
</style>