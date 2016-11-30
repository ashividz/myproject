
<div class="container1">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title col-md-2">Messages</span>
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/> 
		</div>	
		<div class="panel-body">
			<table class="table table-striped" id="messages">
				<thead>
					<tr>
						<th width="15%">From</th>
						<th width="15%">To</th>
						<th width="20%">Subject</th>
						<th>Body</th>
						<th width="15%">Date</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="message in messages">
						<td>@{{message.from }}</td>
						<td>
                            <div v-for="recipient in message.recipients">
                                @{{ recipient.name }}
                                <div class="pull-right">
                                    <span v-if='recipient.read_at' title="Message read at @{{ recipient.read_at | format_date }}">
                                        <i class="fa fa-eye green"></i>
                                    </span>
                                    <span v-if='recipient.action_at' title="Action taken at @{{ recipient.action_at | format_date }}">
                                        <i class="fa fa-check-square green"></i>
                                    </div>
                                </div>                                    
                            </div>
						</td>
						<td>@{{ message.subject }}</td>
						<td>
                            @{{ message.body }}
                            <span v-if='message.lead' class='view pull-right'>
                                <a href='/lead/@{{ message.lead.id }}/viewDispositions' target='_blank'> @{{ message.lead.name }}
                                    <img class='aTn pull-right' src='/images/cleardot.gif'>
                                </a>
                            </span>
                        </td>
						<td>
                            <div class="pull-right">@{{ message.created_at | format_date }}</div>
                        </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
    var vm = new Vue({
        el: 'body',

        data: {
            messages: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            timer: ''
        },

        ready: function(){
            this.getAllMessages();
            this.timer = setInterval(this.getAllMessages, 500000)
        },

        methods: {

            getAllMessages() {
                this.$http.get("/api/getAllMessages", {'start_date': this.start_date, 'end_date' : this.end_date}).success(function(data){
                    this.messages = data;
                }).bind(this);
            }
        },
        computed: {
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
    Vue.filter('format_date', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM hh:mm A');
    })
    Vue.filter('format_date2', function (value) {
        if (value == null) {
            return null;
        }
      return moment(value).format('D MMM');
    })
    vm.$watch('daterange', function (newval, oldval) {
        this.getAllMessages();
    })
</script>
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

});
</script>