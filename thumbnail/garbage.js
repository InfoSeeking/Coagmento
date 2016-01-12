// Garbage collection deletes files older than a specified amount.
var config = require('./config')
	, async = require('async')
	, fs = require('fs')
	, inProgress = false
	, failed = []
	, deleted = []
	;

function checkFile(filename, callback) {
	var path = config.thumbnail_directory + filename;
	fs.stat(path, function(err, stats){
		var created = stats.birthtime.getTime();
		var now = Date.now();

		// Check if it is older than the specified time to live (in minutes).
		var diff = (now - created) / (1000 * 60);
		if (diff > config.screenshot_ttl) {
			fs.unlink(path, function(err){
				if (err) failed.push(filename);
				else deleted.push(filename);
				callback();
			});
		} else {
			callback();
		}
	});
}

function readDirectory(err, files) {
	if (err) {
		console.error('Could not open thumbnail directory', err);
		return;
	}
	async.each(files, checkFile, onFinish);
}

function onFinish(err) {
	console.log((new Date()).toISOString(),
		'Garbage collection deleted ' + deleted.length + ' files.');
	
	if (failed.length > 0) {
		console.log('Attempted to delete the following but could not:');
		failed.forEach(function(filename){
			console.log('=> ' + filename);
		});
	}
	inProgress = false;
}

function run() {
	if (inProgress) {
		console.log('Garbage collection in progress');
		return false;
	}
	inProgress = true;
	failed = [];
	deleted = [];
	fs.readdir(config.thumbnail_directory, readDirectory);
}

module.exports = {
	run : run
};