<script src="/plugins/socket/socket.io.js"></script>
<script type="text/javascript">
    var socket = io('//amikus:3001');

    new Vue({
        el: 'body',

        data: {
            messages: [],
            notifications : [],
        },

        ready: function(){
            this.getMessages();
            this.getNotifications();

            socket.on("user{{ Auth::id() }}:App\\Events\\NewMessage", function(data){
                this.unreadMessageCount = data.count;
            }.bind(this));

            socket.on("user{{ Auth::id() }}:App\\Events\\NewNotification", function(data){
                this.notifications = data.notifications;
                console.log(data);
            }.bind(this));
        },

        methods: {
            getMessages() {
                this.$http.get("/getMessages", {
                    read: 1
                })
                .success(function( data ) {
                    this.messages = data;
                }.bind(this));
            }, 

            getNotifications() {
                this.$http.get("/getNotifications", {
                    read: 1
                })
                .success(function( data ) {
                    this.notifications = data;
                }.bind(this));
            },

            setReadNotification(id) {
                this.$http.patch("/notification/" + id + "/read")
                .success(function(data){
                    toastr.success("Notification read", "Success");
                    this.notifications = data;
                }).bind(this);
            },

        }

    })
</script>
</body>
</html>