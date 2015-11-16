var self = require('sdk/self')
	, config = require('config')
	, ui = require('sdk/ui')
	, { Toolbar } = require('sdk/ui/toolbar')
	, { Sidebar } = require('sdk/ui/sidebar')
	, { Frame } = require('sdk/ui/frame')
	, api = require('lib/api')
	, tabutils = require("sdk/tabs/utils")
	, windowutils = require("sdk/window/utils")
	, sidebarWorker = null
	;

var sidebar = ui.Sidebar({
	id: 'coagmento-sidebar',
	title: 'Coagmento Sidebar',
	url: require('sdk/self').data.url('sidebar.html'),
	onReady: initializeSidebarWorker,
});

sidebar.show();

var frame = ui.Frame({
	url: require('sdk/self').data.url('toolbar.html'),
	onMessage: onToolbarMessage
});

var toolbar = ui.Toolbar({
	title: 'Coagmento Toolbar',
	items: [frame]
});

// Listen for save bookmark requests from the frame.
function onToolbarMessage(e) {
	console.log('Extension receiving from toolbar', e.data);
	if(e.data.action == 'save-bookmark') {
		// Forward to sidebar if possible.
		sendSidebarMessage(e.data);
	}
}

function initializeSidebarWorker(worker) {
	sidebarWorker = worker;
	console.log(sidebarWorker);
	sidebarWorker.port.on('message', onSidebarMessage);
	sidebarWorker.port.emit('message', 'hello!');
	sidebarWorker.port.emit('message', {a: 2});
	this.worker = worker;
}

function sendSidebarMessage(data) {
	if (sidebarWorker) {
		sidebarWorker.port.emit('message', data);
	} else {
		console.log('Sidebar not yet initialized, message dropped: ', data);
	}
}

function onSidebarMessage(data) {
	console.log('Add-on: recieved message from sidebar', data);
}

function getCurrentURL(){
    var res = tabutils.getTabURL(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return encodeURIComponent(res);
}

function getCurrentTitle(){
    var res = tabutils.getTabTitle(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return encodeURIComponent(res);
}
