var self = require('sdk/self')
	, config = require('config')
	, ui = require('sdk/ui')
	, { Toolbar } = require('sdk/ui/toolbar')
	, { Sidebar } = require('sdk/ui/sidebar')
	, { Frame } = require('sdk/ui/frame')
	, api = require('lib/api')
	, tabutils = require("sdk/tabs/utils")
	, windowutils = require("sdk/window/utils")
	, selection = require("sdk/selection")
	, selectionText = null
	, sidebarWorker = null
	;

var sidebar = ui.Sidebar({
	id: 'coagmento-sidebar',
	title: 'Coagmento Sidebar',
	url: require('sdk/self').data.url('sidebar.html'),

	onReady: function(worker) {
		// I'm not sure why, but for some reason sending messages
		// only seems to work with the worker received in the Ready event
		// while receiving messages only seems to work with the Attach event.
		sidebarWorker = worker;
		// Sending a message here also breaks sending messages subsequently...
	},
	onAttach: function(worker) {
		worker.port.on('message', onSidebarMessage);
	}
});

sidebar.show();

var toolbarFrame = ui.Frame({
	url: require('sdk/self').data.url('toolbar.html'),
	onMessage: onToolbarMessage
});

var toolbar = ui.Toolbar({
	title: 'Coagmento Toolbar',
	items: [toolbarFrame]
});

// Listen for save bookmark requests from the frame.
function onToolbarMessage(e) {
	console.log('Extension receiving from toolbar', e.data);
	var data = e.data;
	switch (data.action) {
		case 'save-bookmark':
		data.url = getCurrentURL();
		data.title = getCurrentTitle();
		sendSidebarMessage(e.data);
		break;

		case 'save-snippet':
		data.url = getCurrentURL();
		data.title = getCurrentTitle();
		if (selectionText) data.text = selectionText;
		sendSidebarMessage(data);
		break;

		case 'login':
		sidebar.show();
		sendSidebarMessage(data);
		break;

		case 'view-workspace':
		setCurrentUrl('workspace');
		break;

		default:
		// Forward to sidebar.
		sendSidebarMessage(data);
		break;
	}
}

function sendSidebarMessage(data) {
	console.log('Add-on: Sending to sidebar', data);
	if (sidebarWorker) {
		sidebarWorker.port.emit('message', data);
	} else {
		console.log('Sidebar not yet initialized, message dropped: ', data);
	}
}

function onSidebarMessage(data) {
	console.log('Add-on: recieved message from sidebar', data);
	toolbarFrame.postMessage(data, toolbarFrame.url);
}

function setCurrentUrl(path) {
	var tab = tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow());
	tabutils.setTabURL(tab, config.url + path);
}
function getCurrentURL(){
    var res = tabutils.getTabURL(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return res;
}

function getCurrentTitle(){
    var res = tabutils.getTabTitle(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return res;
}


selection.on('select', function(){
	selectionText = selection.text;
});