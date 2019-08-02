var config = require('./config')
    , express = require('express')
    , app = express()
    , http = require('http').Server(app)
    , io = require('socket.io')(http)
    , bodyParser = require('body-parser') // Middleware for express.
    , feed = require('./feed')(io)
    , whitelist = ['http://localhost:8000'] // From where to accept HTTP requests. TODO: update.
    , port = config.server_port
    ;

// Listen for published data via http requests.
// TODO: Change accepting content type to be application/json.
app.use(bodyParser.json());
app.post('/publish', function(req, res){
  var origin = req.headers.referer;
  if(whitelist.indexOf(origin) != -1){
    console.log('Allowing');
    res.setHeader('Access-Control-Allow-Origin', origin);
  } else {
    console.log('Disallowing request for origin', origin);
  }
  feed.publish(req.body);
  res.send('{"status" : "ok"}');
});

http.listen(port);

console.log('Welcome to Coagmento Realtime Server');
console.log('Listening for socket connections on port ' + port);
