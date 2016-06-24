@extends('message.index')
@section('main')
<div class="container1" id="inbox">
	<div class="panel panel-default">
		<div class="panel-heading">
			<span class="panel-title">Inbox</span> 
			<div class="new-message">
				<span class="new-message-count"></span> new
			</div>
		</div>	
		<div class="panel-body">
			<table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th width="15%">From</th>
                        <th>Subject</th>
                        <th>Body</th>
                        <th width="15%">Date</th>
                        <th width="10%">Action Taken</th>
                    </tr>
                </thead>
				<tbody>
                    <tr v-for='message in messages' id='@{{ message.id }}' v-bind:class="{ 'unread': !message.read_at }">
                        <td v-on:click='setReadMessage(message.id)'>
                            <i v-if='!message.read_at' class="fa fa-envelope"></i>
                        </td>
                        <td v-on:click='setReadMessage(message.id)'>@{{ message.from }}</td>
                        <td v-on:click='setReadMessage(message.id)'>@{{ message.subject }}</td>
                        <td v-on:click='setReadMessage(message.id)'>
                            @{{ message.body }}
                            <span v-if='message.lead' class='view pull-right'>
                                <a href='/lead/@{{ message.lead.id }}/viewDispositions' target='_blank'> @{{ message.lead.name }}
                                    <img class='aTn pull-right' src='/images/cleardot.gif'>
                                </a>
                            </span>
                        </td>
                        <td>
                            <div>@{{ message.created_at | format_date }}</div>
                        </td>
                        <td>
                            <div v-if="message.action_at">
                                <i class="fa fa-check-square-o green"></i>
                            </div>
                            <div v-else>
                                <input type="checkbox" checked="@{{ message.action_at }}" v-on:click='setMessageAction(message.id)'/>
                            </div>
                        </td>
                    </tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style type="text/css">
table thead tr {
    color: #111;
    font-weight: 800;
} 
</style>
<script>
    new Vue({
        el: '#inbox',

        data: {
            messages: [],
            daterange: '{{ Carbon::now()->format('Y-m-01') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            timer: '',
        },

        ready: function(){
            this.getMessages();
            this.timer = setInterval(this.getMessages, 100000);
            this.$watch('action', function(val) {
                alert(val);
            })
        },
        methods: {

            getMessages() {
                this.$http.get("/getMessages", {'start_date': this.start_date, 'end_date' : this.end_date})
                .success(function(data){
                    this.messages = data;
                }).bind(this);
            },

            setReadMessage(id) {
                //alert(id);
                this.$http.post('/api/message/setRead', {id : id})
                .success(function (data, status, request) {
                    this.getMessages();
                    this.getUnreadMessageCount();
                })
                .error(function (data, status, request) {
                   
                });
            },

            setMessageAction(id) {
                this.$http.post('/api/Message/setAction', {id : id})
                .success(function (data, status, request) {
                    this.getMessages();
                    this.getUnreadMessageCount();
                })
                .error(function (data, status, request) {
                   
                });
            },

        },
        beforeDestroy() {
            clearIntervall(this.timer)
        },
    })    
</script>
@endsection