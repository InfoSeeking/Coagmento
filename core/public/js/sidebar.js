// A class for communicating with the outer extension window.
var Sidebar = (function() {
	function sendToParent(json) {
		window.parent.postMessage(json, '*');
	}
	function onParentMessage(callback) {
		window.addEventListener('message', function(evt) {
			callback.call(window, evt.data);
		}, false);
	}
	return {
		sendToParent: sendToParent,
		onParentMessage: onParentMessage
	}
}());