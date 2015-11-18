var express = require('express')
	, app = express()
	, http = require('http').Server(app)
	, path = require('path')
	, fs = require('fs')
	, childProcess = require('child_process')
	, phantomjs = require('phantomjs')
	, bodyParser = require('body-parser')
	, port = 4000
	, maxEntries = 50
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

function getUniqueFilename(base, extension) {
	base += (new Date()).getTime();
	var extra = 1;
	while (true) {
		var filename = base + '_' + extra + '.' + extension;
		try {
			// The following throws an error if the file DNE.
			fs.statSync(filename);
			extra++;
		} catch(e) {
			return filename;
		}
	}
}

function generateThumbnails(req) {
	if (!req.body.hasOwnProperty('data')) return errorStatus('No data field defined');
	var data = req.body.data;
	//var urlToFilenames = [];

	// Preliminary checks. These are all-or-nothing checks, so we don't partially begin generating.
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];
		if (!entry.hasOwnProperty('url')) return errorStatus('No url defined in entry');
	};

	if (data.length > maxEntries) {
		return errorStatus(
			'Please send a maximum of ' + maxEntries + ' pages.' +
			'This request has ' + data.length + '.');
	}
	
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];
		console.log('Generating thumbnail for ', entry.url);
		console.log(phantomjs.path);
		var outFile = getUniqueFilename('public/generated/large/', 'png');

		var childArgs = [
			path.join(__dirname, 'rasterize.js'),
			entry.url,
			outFile
		];
		try {
			childProcess.execFileSync(phantomjs.path, childArgs, {timeout: 10000});
			entry['thumbnail'] = {
				image_large: outFile,
				status: 'success'
			};
		} catch(e) {
			entry['thumbnail'] = {
				status: 'error'
			};
		}
	}
	return successStatus(data);
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