// A stripped down version of the rasterize example shown here:
// https://github.com/ariya/phantomjs/blob/master/examples/rasterize.js
//
// This script is only to be run in the phantomjs environment.

var page = require('webpage').create(),
    system = require('system'),
    address, output, size;

if (system.args.length != 3) {
    console.log('Usage: rasterize.js URL filename');
    phantom.exit(1);
} else {
    address = system.args[1];
    output = system.args[2];
    page.viewportSize = { width: 600, height: 600 };
    page.clipRect = { top: 0, left: 0, width: 600, height: 600 };
    page.open(address, function (status) {
        if (status !== 'success') {
            phantom.exit(1);
        } else {
            window.setTimeout(function () {
                page.render(output);
                phantom.exit();
            }, 200);
        }
    });
}
