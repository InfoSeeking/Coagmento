var express = require('express')
	, app = express()
	, http = require('http').Server(app)
	, phantomjs = require('phantomjs')
	, bodyParser = require('body-parser')
	, port = 4000
	;

function errorStatus(message, error_code) {
	return {
		'status': 'error',
		'message': message,
		'error_code': error_code || 400
	};
}

function successStatus(results, message) {
	return {
		'status': 'success',
		'results': results || null,
		'message': message || null
	};
}

function generateThumbnails(req) {
	if (!req.body.hasOwnProperty('data')) return errorStatus('No data field defined');
	var data = req.body.data;

	// Preliminary checks. These are all-or-nothing checks, so we don't partially begin generating.
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];
		if (!entry.hasOwnProperty('url')) return errorStatus('No url defined in entry');
	};
	
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];
		console.log('Generating thumbnail for ', entry);
		console.log(phantomjs.path);
	};
}

app.use(bodyParser.json());
app.use(express.static('public'));
app.post('/generate', function(req, res) {
	res.setHeader('Access-Control-Allow-Origin', '*');
	console.log('Generating thumbnails for', req.body);
	res.send(generateThumbnails(req));
});

http.listen(port);
console.log('Coagmento Thumbnail Server running on port ' + port);