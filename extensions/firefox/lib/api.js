var XMLHttpRequest = require('sdk/net/xhr').XMLHttpRequest
	config = require('config.js')
	;

function saveBookmark(opt) {
	var content = "url=" + opt.url + "&title=" + opt.title;
	var req = new XMLHttpRequest();
	req.open("POST", config.url + "api/v1/bookmarks", true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // <-- breaks... why?
	req.send(content);
	req.onreadystatechange = function() {
		if(req.readyState == 4) {
			console.log(req.response);
		}
	}
}

exports.saveBookmark = saveBookmark;