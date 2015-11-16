To run, use `jpm run` in commandline. See the Firefox SDK extension instructions for more information.

What is the best way to retain the state information needed to make API requests?
We need to know:
	- Username, whether or not they are logged in
	- Current project

This could be accomplished through realtime (not preferred, as I'd rather not have mission critical logic depend on realtime).

Currently attempting to have a message pipeline.
Toolbar <-> Add-on
Add-on <-> Sidebar
Sidebar <-> Sidebar iframe

But running into some issues. Debugging with Firefox 'Browser Toolbox' seems to give log output for all frames.
