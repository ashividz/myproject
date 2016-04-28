Unread Notification count = @{{ unreadNotificationCount }}
<script src="/plugins/socket/socket.io.js"></script>
<script type="text/javascript">
    var socket = io('{{ env('APP_URL') }}:3001');

    new Vue({
        el: 'body',

        data: {
            unread: null,
            notifications : [],
            unreadNotificationCount: 0,
        },

        ready: function(){
            this.getUnreadMessageCount();

            socket.on("user{{ Auth::id() }}:App\\Events\\NewMessage", function(data){
                this.unread = data.count;
                console.log(data);
            }.bind(this));

            socket.on("user{{ Auth::id() }}:App\\Events\\NewNotification", function(data){
                this.notifications = data.notifications;
                this.unreadNotificationCount = data.unreadNotificationCount;
                console.log(data);
            }.bind(this));
        },

        methods: {
            getUnreadMessageCount() {
                var url = "/api/getUnreadMessageCount";

                $.getJSON(url)
                .done(function( data ) {
                    this.unread = data;
                }.bind(this));
            }   
        }

    })
</script>
</body>
</html>