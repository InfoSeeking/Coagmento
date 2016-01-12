module.exports = {
	thumbnail_directory: 'public/generated/',
	server_port: 4000,
	// How old should a generated image be before deleting?
	screenshot_ttl: 60,
	// How frequently should we run the garbage collection (in minutes)?
	garbage_collection_delay: 1,
};
