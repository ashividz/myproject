
<div class="container1" id="messages">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title col-md-2">Messages</span>
            <input type="text" id="daterange" v-model="daterange" size="25" readonly/>
            <select v-model='user' id="user">
                <option v-for='user in users'>@{{ user.name }}</option>
            </select>     
        </div>  
        <div class="panel-body">
            <table class="table table-striped" id="messages">
                <thead>
                    <tr>
                        <th width="15%">From</th>
                        <th width="15%">To</th>
                        <th width="15%">Subject</th>
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
                                <span v-on:click='deleteRecipient(recipient)'>
                                    <i class="fa fa-close red"></i>
                                </span>
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
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    var vm1 = new Vue({
        el: 'messages',

        data: {
            messages: [],
            daterange: '{{ Carbon::now()->format('Y-m-d') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            timer: '',
            loading: false,
            users: [],
        },

        ready: function(){
            this.getUsers();
            this.getAllMessages();
            //this.timer = setInterval(this.getAllMessages, 500000)
        },

        methods: {
            getUsers() {
                this.$http.get("/api/getUsers").success(function(data){
                    this.users = data;
                }).bind(this);
            },

            getAllMessages() {
                this.loading = true;
                this.$http.get("/api/getAllMessages", { 'start_date': this.start_date, 'end_date' : this.end_date, 'user' : this.user }).success(function(data){
                    this.messages = data;
                    this.loading = false;
                }).bind(this);
            },
            deleteRecipient(recipient) {
                
                this.$http.post("/message/recipient/delete", { 'id' : recipient.id }).success(function(data){
                    this.getAllMessages()
                    alert(data);
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
    vm1.$watch('daterange', function (newval, oldval) {
        this.getAllMessages();
    })
    vm1.$watch('user', function (newval, oldval) {
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
<style type="text/css">
.roles {
    display: inline-block;
    margin-left: 40px;
}
.roles input {
    margin-right: 20px;
}
</style>