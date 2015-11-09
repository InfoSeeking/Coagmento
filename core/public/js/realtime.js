// Connects to realtime service.

var Realtime = (function () {
	var socket, dataHandler, listeningStatus;

	function init(onData) {
		if (!Config.get('realtimeEnabled')) {
			console.log("Coagmento realtime disabled.");
			return false;
		}
		socket = io(Config.get('realtimeServer') + '/feed');
		socket.emit('subscribe', {
			projectID : Config.get('projectId')
		});
		dataHandler = onData;
		listen();
		return true;
	}

	function unlisten() {
		socket.off('data', dataHandler);
		listeningStatus = false;
	}

	function listen() {
		socket.on('data', dataHandler);
		listeningStatus = true;
	}

	function getListeningStatus() {
		return listeningStatus;
	}

	return {
		init: init,
		listen: listen,
		unlisten: unlisten,
		getListeningStatus: getListeningStatus
	};
}());

