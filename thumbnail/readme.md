Consider:
- Making statSync asynchronous
- Ensuring that uniqueFilename does not lead to race conditions
- Adding a garbage collector to delete old thumbnails
- Handling duplicate urls in the same request

Problems:
- It seemed to quietly crash once, but I haven't reproduced it yet.
- For whatever reason it produces the same images for websites sometimes.

I'm somewhat considering switching to something else.