To run, use `jpm run` in commandline. See the Firefox SDK extension instructions for more information.

What is the best way to retain the state information needed to make API requests?
We need to know:
	- Username, whether or not they are logged in
	- Current project

The message pipeline is as follows:
- Toolbar <-> Add-on
- Add-on <-> Sidebar
- Sidebar <-> Sidebar iframe

Next up:
- Add authentication pages exclusive to sidebar (including middleware)
- Add config with server url
- Clean and comment
- Add integrated feed
- Add unit tests