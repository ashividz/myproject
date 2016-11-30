var https = require('https'),  
    fs =    require('fs');        

var options = {
    key:    fs.readFileSync('/etc/apache2/ssl/amikus/apache.key'),
    cert:   fs.readFileSync('/etc/apache2/ssl/amikus/apache.crt')
};
var server = https.createServer(options);
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