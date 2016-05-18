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
    Vue.http.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    var vm1 = new Vue({
        el: 'body',

        data: {
            messages: [],
            daterange: '{{ Carbon::now()->format('Y-m-01') }} - {{ Carbon::now()->format('Y-m-d') }}',
            start_date: '',
            end_date: '',
            timer: '',
            unreadMessageCount: 0,
            unreadNotificationCount: 0,
        },

        ready: function(){
            this.getMessages();
            this.timer = setInterval(this.getMessages, 100000);
            this.getUnreadMessageCount();
        },
        methods: {

            getMessages() {
                this.$http.get("/api/getMessages", {'start_date': this.start_date, 'end_date' : this.end_date})
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

            getUnreadMessageCount() {
                var url = "/api/getUnreadMessageCount";

                $.getJSON(url)
                .done(function( data ) {
                    this.unreadMessageCount = data;
                }.bind(this));
            }
        },
        beforeDestroy() {
            clearIntervall(this.timer)
        },
    })    

    vm1.$watch('action', function(val) {
        alert(val);
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
</script>
@endsection