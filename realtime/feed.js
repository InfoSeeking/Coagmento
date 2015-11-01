var io = null
    , feed = null
    ;

function init(socketio){
  io = socketio;
  // Create a socket.io namespace for only the data feed.
  feed = io.of('feed');
  feed.on('connection', onSocketConnection);
}

function onSocketConnection(socket) {
  console.log('WebSocket: User connected');
  var room;
  /**
   * @param {object} param Must contain projectID
   */
  socket.on('subscribe', function(param){
    // TODO Authenticate and ensure necessary permissions.
    room = 'project/' + param.projectID;
    console.log('WebSocket: User joining room ' + room);
    socket.join(room);
  });

  socket.on('disconnect', function(){
    console.log('WebSocket: User disconnected, leaving room ' + room);
    socket.leave(room);
  });
}

 /**
  * @param {int} userID
  * @param {int} projectID
  * @param {object[]} data An array of feed data.
  */
function publish(param){
  if(!io){
    throw 'Feed is not initialized, cannot publish';
  }
  console.log("Received feed update:", param);
  room = 'project/' + param.projectID;
  feed.to(room).emit('data', param);
}

module.exports = function(socketio){
  init(socketio);
  return {
    publish : publish
  }
}