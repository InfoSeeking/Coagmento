// Connects to realtime service.

var Realtime = (function () {
	var socket, dataHandler;

	function init(onData) {
		if (!Config.get('realtimeEnabled')) {
			console.log("Coagmento realtime disabled.");
			return;
		}
		socket = io(Config.get('realtimeServer') + '/feed');
		socket.emit('subscribe', {
			projectID : Config.get('projectId')
		});
		dataHandler = onData;
		listen();
	}

	function unlisten() {
		socket.off('data', dataHandler);
	}

	function listen() {
		socket.on('data', dataHandler);
	}

	return {
		init: init,
		listen: listen,
		unlisten: unlisten
	};
}());

