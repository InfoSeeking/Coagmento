// A stripped down version of the rasterize example shown here:
// https://github.com/ariya/phantomjs/blob/master/examples/rasterize.js
//
// This script is only to be run in the phantomjs environment.

var page = require('webpage').create(),
    system = require('system'),
    address, output, size, rendering = false;

// This function is either called after the page.open is finished
// or if the maxLoadTime runs out. Either way it produces a thumbnail
// and exits.
function renderPageAndExit() {
    if (rendering) return;
    rendering = true;
    window.setTimeout(function () {
        page.render(output);
        phantom.exit();
    }, 200);
}

if (system.args.length != 4) {
    console.log('Usage: rasterize.js URL filename maxLoadTime');
    phantom.exit(1);
} else {
    address = system.args[1];
    output = system.args[2];
    maxLoadTime = parseInt(system.args[3]);
    page.viewportSize = { width: 600, height: 600 };
    page.clipRect = { top: 0, left: 0, width: 600, height: 600 };
    page.open(address, function (status) {
        if (status !== 'success') {
            phantom.exit(1);
        } else {
            renderPageAndExit();
        }
    });

    page.onLoadStarted = function() {
        window.setTimeout(renderPageAndExit, maxLoadTime);
    };
}
