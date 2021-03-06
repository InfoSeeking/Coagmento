var config = require('./config')
	, express = require('express')
	, app = express()
	, http = require('http').Server(app)
	, path = require('path')
	, fs = require('fs')
	, childProcess = require('child_process')
	, phantomjs = require('phantomjs')
	, bodyParser = require('body-parser')
	, async = require('async')
	, easyimg = require('easyimage')
	, port = config.server_port
	// The maximum number of thumbnails it will accept per request.
	, maxEntries = 50
	// The maximum number of concurrent screen capture processes possible.
	, concurrency = 5
	, maxNumRequests = 1
	, iid = 0
	, garbageCollection = require('./garbage')
	// The lock only allows maxNumRequests requests to be served at one time.
	, currentNumRequests = 0
	// The timeout (ms) before the phantomjs process is killed.
	, captureTimeout = 30000
	// The maximum time phantomjs will load the page for before attempting to render.
	// If this time is reached before the page is finished loading, this could result
	// in a partially rendered thumbnail. This is still better than no thumbnail.
	, maxPageLoadTime = 5000
	// Some simple log data.
	, log = {
		numServiced: 0,
		numRejected: 0,
		numScreenCaps: 0,
		numFailures: 0
	}
	;

function printLog() {
	console.log('----- Log -----');
	console.log('# Serviced = ' + log.numServiced);
	console.log('# Rejected = ' + log.numRejected);
	console.log('# ScreenCaps = ' + log.numScreenCaps);
	console.log('# Failures = ' + log.numFailures);
}

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

function getUniqueFilename(base, prefix, extension) {
	var filename = (new Date()).getTime();
	var extra = 1;
	iid++;
	while (true) {
		var file = prefix + '_' +  iid + '_' + filename + '_' + extra + '.' + extension;
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

// Task for capturing and resizing images.
function queueTask(task, callback) {
	var entry = task.entry;
	if (task.action == 'resize') {
		console.log('Resizing image for ' + entry.url);
		var outFile = getUniqueFilename(config.thumbnail_directory, 'small', 'png');
		easyimg.resize({
			src: config.thumbnail_directory + entry.thumbnail.image_large,
			dst: outFile.path,
			width: 200,
			height: 200
		}).then(function(file){
			entry.thumbnail.image_small = outFile.file;
			callback();
		}, function(err){
			console.log('Image resize error', err);
			entry.thumbnail.status = 'error';
			callback(err);
		});
	}
	if (task.action == 'capture') {
		console.log('Capturing image for ' + entry.url);
		var outFile = getUniqueFilename(config.thumbnail_directory, 'large', 'png');

		var childArgs = [
			path.join(__dirname, 'rasterize.js'),
			entry.url,
			outFile.path,
			maxPageLoadTime
		];

		// TODO: adding the timeout lead to more failures?
		childProcess.execFile(phantomjs.path, childArgs, {timeout: captureTimeout}, function(err) {
			if (err) {
				console.log('PhantomJS error', err);
				log.numFailures++;
				entry.thumbnail = {
					status: 'error'
				};
				callback(err);
			} else {
				log.numScreenCaps++;
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
		callback.call(null, errorStatus(
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
	console.log('Recieved request');
	
	if (currentNumRequests == maxNumRequests) {
		log.numRejected++;
		console.log('Maximum number of servicable requests reached - return error.');
		res.send(errorStatus('Thumbnail server currently servicing requests.'));
		return;
	}
	console.log('Generating thumbnails for', req.body);
	currentNumRequests++;
	generateThumbnails(req, function(data){
		currentNumRequests--;
		res.send(data);
		log.numServiced++;
	});
});

app.get('/log', function(req, res) {
	res.setHeader('Access-Control-Allow-Origin', '*');
	printLog();
	res.send(log);
});

http.listen(port);
console.log('Coagmento Thumbnail Server running on port ' + port);

console.log('Garbage collection scheduled for every ' + config.garbage_collection_delay + ' minutes');
setInterval(garbageCollection.run, config.garbage_collection_delay * 60 * 1000);