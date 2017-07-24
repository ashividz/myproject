<div id="orders">
    <input type="text" id="daterange" v-model="daterange" size="25" readonly/>  
    <a id="downloadCSV" class="btn btn-primary pull-right" style="margin-bottom:2em;">download orders</a>
    <table id="order-table" class="table table-bordered">
        
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction Id</th>
                <th>Name</th>
                <th>Location</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Status Message</th>
                <th>CRE</th>
            </tr>            
        </thead>
        <tbody>
            <tr v-for="order in orders" v-bind:class="{ 'success' : order.payment_status == 'C',  'fail' : order.payment_status == 'F',  'incomplete' : order.payment_status == 'P' }">
                <td>@{{ order.order_date }}</td>
                <td>@{{ order.transaction_id }}</td>
                <td>
                     @{{ order.firstname + " " + order.lastname }}
                    <span class="pull-right" v-if="order.lead_id">
                        <a href="/lead/@{{ order.lead_id}}" target="_blank">
                            <button>
                            <i class="fa fa-arrows-alt"></i>
                            </button>
                        </a>
                    </span>
                        
                </td>
                <td>@{{ order.country + ", " + order.city }}</td>
                <td>@{{ order.payment_method }}</td>
                <td>@{{ order.currency + " " + order.total_amount }}</td>
                <td>@{{ order.message }}</td>
                <td>@{{ order.cre }}</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    var vm = new Vue({
        el: '#orders',

        data: {
            orders: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            timer: '',
        },

        ready: function(){
            this.getOrders();
            this.timer = setInterval(this.getOrders, 30000)
        },

        methods: {

            getOrders() {
                $.getJSON("/api/newonlinePayments", {'start_date': this.start_date, 'end_date' : this.end_date}, function(orders){
                    this.orders = orders;
                    console.log(orders);
                }.bind(this));
            }
        },
        computed: {
            /*daterange() {
                return moment(this.start_date).format('YYYY-MM-DD') + ' - ' + moment(this.end_date).format('YYYY-MM-DD');
            },*/

            start_date() {
                var range = this.daterange.split(" - ");
                return moment(range[0]).format('YYYY-MM-DD') + ' 0:0:0';
            },

            end_date() {
                var range = this.daterange.split(" - ");
                return moment(range[1]).format('YYYY-MM-DD') + ' 23:59:59';
            }
        },
        beforeDestroy() {
            clearIntervall(this.timer)
        }
    })

    vm.$watch('daterange', function (newval, oldval) {
        this.getOrders();
    })
</script>
<!--<script type="text/javascript">

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
    var start_date = '{{ $start_date }}';
    var end_date = '{{ $end_date }}';
    $.getJSON("/api/onlinePayments", {'start_date': start_date, 'end_date' : end_date}, function(result){
        $('#alert').hide();
        $("#orders tbody").empty();
        var i = 0;
        $.each(result, function(i, field) {  
            i++;
            if (field.payment_status == 'F') {
                status = 'fail';
            }
            else if (field.payment_status == 'C') {
                status = 'success'
            }
            else {
                status = "incomplete";
            };   
            $("#orders").append("<tr class='" + status + "'>");
            $("#orders tr:last").append("<td>" + i + "</td>");
            $("#orders tr:last").append("<td>" + field.order_date + "</td>");
            $("#orders tr:last").append("<td>" + field.transaction_id + "</td>");
            $("#orders tr:last").append("<td><a href='/lead/"+field.lead_id+ "/viewDetails' target='_blank'>" + field.firstname + " " + field.lastname + "</a></td>");
            $("#orders tr:last").append("<td>" + field.country + "</td>");
            $("#orders tr:last").append("<td>" + field.city + "</td>");
            $("#orders tr:last").append("<td>" + field.payment_method + "</td>");
            $("#orders tr:last").append("<td>" + field.currency + " " + field.total_amount + "</td>");
            $("#orders tr:last").append("<td>" + field.message + "</td>");
            $("#orders tr:last").append("<td>" + field.cre + "</td>");
            $("#orders").append("</tr>");
        });
    })
    .fail(function(jqXHR, textStatus, errorThrown) { 
        $('#alert').show();
        $('#alert').empty().append('getJSON request failed! ' + textStatus);
    })
}
</script>
-->
<style type="text/css">
    tr.success {
        background-color: rgb(108, 208, 147);
    }
    tr.fail {
        background-color: rgb(236, 106, 106);
    }
    tr.incomplete {
        background-color: rgb(240, 240, 140);
    }
</style>

<script type="text/javascript">
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
        format: 'YYYY-MM-DD' 
        }
    );   
    $('#daterange').on('apply.daterangepicker', function(ev, picker) 
    {   
        $('#daterange').trigger('change'); 
    });

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
