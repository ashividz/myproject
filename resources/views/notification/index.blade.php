@extends('partials.master')

@section('content')
<div class="col-md-6 col-md-offset-3" id="notification">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title">Notifications (@{{ notifications.length }})</span> 
        </div>  
        <div class="panel-body">
            <table class="table" id="notifications">
                <thead>
                    <tr>
                        <th></th>
                        <th>Message</th>

                        <th>Date</th>
                        <th width="10%">Read</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='notification in notifications' id='@{{ notification.id }}' v-bind:class="{ 'unread': !notification.read_at }">
                        <td>
                            <i v-if='!notification.read_at' class="fa fa-envelope"></i>
                        </td>
                        <td>
                            <span>
                                @{{ notification.type.message }} 
                                <small>by</small>
                                <em>@{{ notification.creator.employee.name }} </em>
                            </span>
                            <span class='view pull-right'>
                                <a href='@{{ notification.type.object.link + notification.object }}' target='_blank'> @{{ notification.object }}
                                    <img class='aTn pull-right' src='/images/cleardot.gif'>
                                </a>
                            </span>                            
                        </td>
                        <td>
                            <div>@{{ notification.created_at }}</div>
                        </td>
                        <td>
                            <div v-if="notification.read_at">
                                <i class="fa fa-check-square-o green"></i>
                            </div>
                            <div v-else>
                                <input type="checkbox" checked="@{{ notification.action_at }}" v-on:click='setReadNotification(notification.id)'/>
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
        el: '#notification',

        data: {
            notifications: [],
        },

        ready: function(){
            this.getNotifications();
            this.$watch('action', function(val) {
                alert(val);
            })
        },
        methods: {

            getNotifications() {
                this.$http.get("/getNotifications")
                .success(function(data){
                    this.notifications = data;
                }).bind(this);
            },

            setReadNotification(id) {
                this.$http.patch("/notification/" + id + "/read")
                .success(function(data){
                    toastr.success("Notification read", "Success");
                    this.notifications = data;
                }).bind(this);
            }
        }
    })    
</script>
@endsection