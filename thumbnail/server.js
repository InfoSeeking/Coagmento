// TODO: For whatever reason, the thumbnail server simply stops running sometimes.
// This is not stable enough for production.

var express = require('express')
	, app = express()
	, http = require('http').Server(app)
	, path = require('path')
	, fs = require('fs')
	, childProcess = require('child_process')
	, phantomjs = require('phantomjs')
	, bodyParser = require('body-parser')
	, async = require('async')
	, lwip = require('lwip')
	, port = 4000
	// The maximum number of thumbnails it will accept per request.
	, maxEntries = 50
	// The maximum number of concurrent screen capture processes possible.
	, concurrency = 10 
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
	var filename = (new Date()).getTime();
	var extra = 1;
	while (true) {
		var file = filename + '_' + extra + '.' + extension;
		var path = base + file;
		try {
			// The following throws an error if the file DNE.
			fs.statSync(path);
			extra++;
		} catch(e) {
			return {
				path: path,
				file: file
			};
		}
	}
}

function openImage(entry, callback) {
	var path = 'public/generated/' + entry.thumbnail.image_large;
	lwip.open(path, 'png', function(err, img) {
		callback(err, entry, img);
	});
}

function resizeImage(entry, img, callback) {
	img.resize(200, 200, 'lanczos', function(err, img) {
		callback(err, entry, img);
	});
}

function saveImage(entry, img, callback) {
	var outFile = getUniqueFilename('public/generated/', 'png');
	img.writeFile(outFile.path, 'png', {}, function(err, img) {
		if (!err) {
			entry.thumbnail.image_small = outFile.file;
		}
		callback(err);
	});
}

// Task for capturing and resizing images.
function queueTask(task, callback) {
	var entry = task.entry;
	if (task.action == 'resize') {
		console.log('Resizing image for ' + entry.url);
		// Waterfall will pass the results of one function to the next.
		async.waterfall([
			async.apply(openImage, entry),
			resizeImage,
			saveImage],
			function(err, result) {
				if (err) entry.thumbnail.status = 'error';
				callback();		
			});
	}
	if (task.action == 'capture') {
		console.log('Capturing image for ' + entry.url);
		var outFile = getUniqueFilename('public/generated/', 'png');

		var childArgs = [
			path.join(__dirname, 'rasterize.js'),
			entry.url,
			outFile.path
		];

		childProcess.execFile(phantomjs.path, childArgs, {timeout: 15000}, function(err) {
			if (err) {
				entry.thumbnail = {
					status: 'error'
				};
				callback(err);
			} else {
				entry.thumbnail = {
					image_large: outFile.file,
					status: 'success'
				};
				callback();
			}
		});
	}
}

function generateThumbnails(req, callback) {
	if (!req.body.hasOwnProperty('data')) {
		callback.call(null, errorStatus('No data field defined'));
		return;
	}

	var data = req.body.data;

	if (data.length == 0) {
		callback.call(null, successStatus([], 'No entries passed'));
		return;
	}

	// Preliminary checks. These are all-or-nothing checks, so we don't partially begin generating.
	for (var i = 0; i < data.length; i++) {
		var entry = data[i];
		if (!entry.hasOwnProperty('url')) {
			callback.call(null, errorStatus('No url defined in entry'));
			return;
		}
	};

	if (data.length > maxEntries) {
		callback.call(errorStatus(
			'Please send a maximum of ' + maxEntries + ' pages.' +
			'This request has ' + data.length + '.'));
		return;
	}

	var queue = async.queue(queueTask, concurrency);
	data.forEach(function(entry){
		queue.push({action: 'capture', entry: entry}, function(err) {
			if (!err) {
				queue.push({action: 'resize', entry: entry});
			}
		});
	});

	queue.drain = function() {
		console.log('Request finished.');
		callback.call(null, successStatus(data));
	};
}

app.use(bodyParser.json());
app.use(express.static('public'));
app.post('/generate', function(req, res) {
	res.setHeader('Access-Control-Allow-Origin', '*');
	console.log('Generating thumbnails for', req.body);
	generateThumbnails(req, function(data){
		res.send(data);	
	});
});

http.listen(port);
console.log('Coagmento Thumbnail Server running on port ' + port);