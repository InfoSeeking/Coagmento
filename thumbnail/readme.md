# Coagmento Thumbnail Generator #
A small NodeJS server built using [PhantomJS](http://phantomjs.org/).

It offers an HTTP API which the core Coagmento application calls periodically to generate
screenshots from website URLs. Both a large and small version of a screenshot are saved.

This server is not meant for storage. Saved images are deleted periodically from this server. 
Therefore, they are downloaded from the core Coagmento application soon after generating.

See the [wiki](https://github.com/InfoSeeking/Coagmento/wiki/Coagmento-Thumbnail-Generator-Installation) for installation instructions and other information.

## Example HTTP request and response ##
A request simply sends a list of URLs.
```javascript
{
	"data" : [
	{"url" : "http://google.com"},
	{"url" : "http://news.ycombinator.com"},
	{"url" : "http://youtube.com"}
	]
}
```

The response returns the names of the generated files.
```javascript
{
	"status": "success",
	"results": [
	{
		"url": "http://google.com",
		"thumbnail": {
			"status": "success",
			"image_large": "large_1_1452630392360_1.png",
			"image_small": "small_5_1452630399349_1.png"
		}
	},
	{
		"url": "http://news.ycombinator.com",
		"thumbnail": {
			"status": "success",
			"image_large": "large_2_1452630397486_1.png",
			"image_small": "small_4_1452630399293_1.png"
		}
	},
	{
		"url": "http://youtube.com",
		"thumbnail": {
			"status": "success",
			"image_large": "large_3_1452630397500_1.png",
			"image_small": "small_6_1452630403646_1.png"
		}
	}
	]
}
```

Thereafter, the generated images can be found in the servers publically visible directories
(default is public/generated) and downloaded. Note, these should be downloaded soon after
they are generated, since they are only kept on the server temporarily.

## Consider ##
- Making statSync asynchronous
- Ensuring that uniqueFilename does not lead to race conditions
- Handling duplicate urls in the same request

## Problems ##
- The server seemed to quietly crash once, but I haven't reproduced it yet. 
This may have been my mistake.
