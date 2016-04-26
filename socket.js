var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redis = new Redis();

server.listen(3001, function() {
    console.log('Server is running!');
});

io.on('connection', function(socket) {});

redis.psubscribe('*', function(err, count) {});

redis.on('pmessage', function(subscribed, channel, message) {
    console.log("Message");
    console.log(channel, message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});