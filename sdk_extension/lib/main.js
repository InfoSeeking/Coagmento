// Some parts of the function below were taken from http://stackoverflow.com/questions/1018705/how-to-detect-timeout-on-an-ajax-xmlhttprequest-call-in-the-browser

/*
 
 .. function:: loadxhtml(url, data, reqtype, mode)
 
 cubicweb loadxhtml plugin to make jquery handle xhtml response
 
 fetches `url` and replaces this's content with the result
 
 Its arguments are:
 
 * `url`
 
 * `mode`, how the replacement should be done (default is 'replace')
 Possible values are :
 - 'replace' to replace the node's content with the generated HTML
 - 'swap' to replace the node itself with the generated HTML
 - 'append' to append the generated HTML to the node's content
 
 */

var globalURL = 'http://coagmento.org/';
//var globalURL = 'http://localhost:8888/';

var sidebar_id = 'coagmento-sidebar';

var buttons = require('sdk/ui/button/action');
var tabs = require("sdk/tabs");
var tabutils = require("sdk/tabs/utils");
var windowutils = require("sdk/window/utils");
var XMLHttpRequest = require("sdk/net/xhr").XMLHttpRequest;

var isVersionCorrect = true;
var connectionFlag = false;
var loggedIn = false;
var isExclusive = false;



/*
 *
 * SIDEBAR
 *
 */
var sidebar = require("sdk/ui/sidebar").Sidebar({
                                                id: sidebar_id,
                                                title: 'Coagmento Sidebar',
                                                url: require("sdk/self").data.url("sidebar.html")
                                                }
                                                );




/*
 *
 * TOOLBAR
 *
 */
var { ActionButton } = require('sdk/ui/button/action');
var { Toolbar } = require("sdk/ui/toolbar");
var { Frame } = require("sdk/ui/frame");
var { setTimeout, clearTimeout } = require("sdk/timers");
var windows = require("sdk/windows").browserWindows;
var selection = require("sdk/selection");

var login_button = ActionButton({
                            id: "coagmento-CSpaceLogin-Button",
                            label: "Connect/Disconnect with Coagmento",
                            icon: "./icons/icon-16.png",
                            onClick:changeConnectionStatus,
                            });

var home_button = ActionButton({
                        id: "coagmento-Home-Button",
                        label: "Coagmento home/CSpace",
                        icon: "./icons/icon-16.png",
                        onClick:function(state){loadURL('http://www.coagmento.org/CSpace/workspace/');},
                        });

var bookmark_button = ActionButton({
                        id: "coagmento-Save-Button",
                        label: "Save/remove the current page",
                        icon: "./icons/icon-16.png",
                        onClick:save,
                        });

var recommend_button = ActionButton({
                            id: "coagmento-Recommend-Button",
                            label: "Recommend this page to others",
                            icon: "./icons/icon-16.png",
                            onClick:recommend,
                            });

var annotate_button = ActionButton({
                        id: "coagmento-Annotate-Button",
                        label: "Make notes on the current page",
                        icon: "./icons/icon-16.png",
                        onClick:annotate,
                        });

var snip_button = ActionButton({
                        id: "coagmento-Snip-Button",
                        label: "Snip highlighted text from the current page",
                        icon: "./icons/icon-16.png",
                        onClick:snip,
                        });

var resources_button = ActionButton({
                            id: "coagmento-Resources-Button",
                            label: "Shared resources",
                            icon: "./icons/icon-16.png",
                            onClick:function(state){sidebar.show();},
                            });

var editor_button = ActionButton({
                        id: "coagmento-Etherpad-Button",
                        label: "Collaborative editor",
                        icon: "./icons/icon-16.png",
                        onClick:function(state){loadURL('http://www.coagmento.org/CSpace/workspace/etherpad.php');},
                        });


var coagmento_toolbar = Toolbar({
                      title: "Coagmento Toolbar",
                      items: [login_button,home_button,bookmark_button,recommend_button,annotate_button,snip_button,resources_button,editor_button]
                      });




/*
 *
 *
 * TABBED BROWSER
 *
 *
 */

tabs.on('ready', function(tab) {
        if (isVersionCorrect)
        {
        savePQ();
        checkCurrentPage();
        }
        });

var oldValue = tabutils.getTabTitle(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));


/*
 * function getCurrentURL()
 *
 * If the page web address, calls savePQ.
 *
 * Arguments:
 *
 * None
 *
 */
function onChange(){
    var currTitle = tabutils.getTabTitle(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    if(oldValue!==currTitle)
    {
        oldValue=currTitle;
        savePQ();
    }
}


/*
 *
 * UTILS
 *
 */

/*
 * function getCurrentURL()
 *
 * Gets the current version
 *
 * Arguments:
 *
 * None
 *
 */
function getVersion()
{
    return '308';
}

/*
 * function getCurrentURL()
 *
 * Gets the URL of the current tab
 *
 * Arguments:
 *
 * None
 *
 */
function getCurrentURL(){
    var res = tabutils.getTabURL(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return encodeURIComponent(res);
}

/*
 * function getCurrentTitle()
 *
 * Gets the title of the current tab
 *
 * Arguments:
 *
 * None
 *
 */
function getCurrentTitle(){
    var res = tabutils.getTabTitle(tabutils.getActiveTab(windowutils.getMostRecentBrowserWindow()));
    return encodeURIComponent(res);
}

/*
 * function loadURLPopup(url,text)
 *
 * Opens a popup window with current page as 'page' GET argument
 *
 * Arguments:
 *
 * `targetURL`: the url to load
 *
 * `title`: The title of the popup window
 *
 */
function loadURLPopup(targetURL,title){
    console.log("POPUP");
    windows.open(targetURL,title,'resizable=yes,scrollbars=yes,width=640,height=480,left=600');
}


/*
 * function disableButtons(value)
 *
 * Disable or enable buttons
 *
 * Arguments:
 *
 * `value`: true or false.  'disabled' is set to value
 *
 */
function disableButtons(value)
{
    login_button.disabled = value;
    bookmark_button.disabled = value;
    recommend_button.disabled = value;
    annotate_button.disabled = value;
    snip_button.disabled = value;
    home_button.disabled = value;
    resources_button.disabled = value;
    editor_button.disabled = value;
//    document.getElementById('coagmento-Views-Status-Button').disabled = value;
//    document.getElementById('coagmento-Notes-Status-Button').disabled = value;
//    document.getElementById('coagmento-Snippets-Status-Button').disabled = value;
//    document.getElementById('coagmento-Project-Status-Button').disabled = value;
}


/*
 * function loadURL(url)
 *
 * Function to load a URL
 *
 * Arguments:
 *
 * `url`: The url to load
 *
 */
function loadURL(url) {
    // Set the browser window's location to the incoming URL
    tabs.activeTab.url=url;
    //    window._content.document.location = url;
    // Make sure that we get the focus
    //    window.content.focus();
}


/*
 * function checkConnectivity(url)
 *
 * Check connectivity to the server and toggle flags and buttons accordingly
 *
 * Arguments:
 *
 * None
 *
 */
function checkConnectivity()
{
    if (isVersionCorrect)
    {
        var xmlHttpTimeout;
        if (isExclusive==false)
        {
            isExclusive = true;
            
            /*var Request = require("sdk/request").Request;
            var latestTweetRequest = Request({
                                             url: "https://api.twitter.com/1/statuses/user_timeline.json?screen_name=mozhacks&count=1",
                                             onComplete: function (response) {
                                             var tweet = response.json[0];
                                             console.log("User: " + tweet.user.screen_name);
                                             console.log("Tweet: " + tweet.text);
                                             }
                                             });
            */

             var xmlHttpConnection = new XMLHttpRequest();
            xmlHttpConnection.open('GET', 'http://www.coagmento.org/CSpace/checkConnectionStatus.php', true);
            xmlHttpConnection.onreadystatechange=function(){
                if (xmlHttpConnection.readyState == 4 && xmlHttpConnection.status == 200) {
                    var serverResponse = xmlHttpConnection.responseText;
                    if (serverResponse!=0)
                    {
                        if (serverResponse==1) //If response == 1 then session active
                            loggedIn = true;
                        else                  //If response == 1 then NO session active
                            loggedIn = false;
                        xmlHttpConnection.abort();
                        clearTimeout(xmlHttpTimeout);
                        login_button.disabled = false;
                        //                        document.getElementById('coagmento-ServerConnection-Status').label = " -- Server Connectivity: OK -- ";
                        connectionFlag = true;
                        updateToolbarButtons();
                        isExclusive = false;
                    }
                    else
                    {
                        clearTimeout(xmlHttpTimeout);
                        serverDown();
                        xmlHttpConnection.abort();
                    }
                }
            }
            
            xmlHttpConnection.send(null);
            xmlHttpTimeout = setTimeout(function (){
                                        serverDown();
                                        xmlHttpConnection.abort();
                                        clearTimeout(xmlHttpTimeout);
                                        },5000);
            
            
        }
        
        
    }
}

/*
 * function tabSelected(event)
 *
 * Updates login status by checking connetivity to the server and updating the
 * toolbar buttons
 *
 * Arguments:
 *
 * None
 *
 */
function updateLoginStatus()
{
    if (isVersionCorrect)
    {
        checkConnectivity();
        updateToolbarButtons();
    }
}

/*
 * function updateToolbarButtons()
 *
 * Updates toolbar buttons
 *
 * Arguments:
 *
 * None
 *
 */
function updateToolbarButtons()
{
    if (isVersionCorrect)
    {
        if (connectionFlag)
        {
            if (loggedIn)
            {
                //loggedIn = true;
                disableButtons(false);
                login_button.label = "Disconnect";
            }
            else
            {
                disableButtons(true);
                login_button.label = "Connect";
                cleanStatus();
            }
            
            login_button.disabled = false;
            
        }
    }
}




/*
 *
 * function changeConnectionStatus()
 *
 * Connects/disconnects to/from Coagmento.
 *
 * Arguments:
 *
 * None
 *
 */
function changeConnectionStatus()
{
    if (login_button.label == "Disconnect")
    {
        if(confirm('Are you sure you want to logout?'))
        {
            req = new phpRequest(globalURL+"/CSpace/logout.php");
            var response = req.execute();
            sidebar.hide();
//            var broadcaster = top.document.getElementById('viewSidebar');
//            if (broadcaster.hasAttribute('checked'))
//                sidebar.hide();
            updateLoginStatus();
        }
    }
    else
    {
        sidebar.hide();
        sidebar.show();
    }
}



/*
 *
 * function save()
 *
 * saves or removes bookmark
 *
 * Arguments:
 *
 * None
 *
 */
function save() {
    
    if (isVersionCorrect)
    {
        if (loggedIn) {
			var url = getCurrentURL();
			var title = getCurrentTitle();
			
			if (bookmark_button.label == "Bookmark") {
                var targetURL = globalURL+'CSpace/services/saveResult.php?'+'page='+url+'&title='+title+'&save=1';
                loadURLPopup(targetURL,'Bookmark');
                bookmark_button.label = "Remove";
			} // if (button.label == "Bookmark")
			else {
				req = new phpRequest(globalURL+"/CSpace/services/saveResult.php");
				req.add('page', url);
				req.add('title', title);
				req.add('save','0');
				var response = req.execute();
				bookmark_button.label = "Bookmark";
			} // else with if (button.label == "Bookmark")
        }
        else
            alert("Your session has expired. Please log in again.");
    }
} // function save()




/*
 *
 * function recommend()
 *
 * opens a popup window to recommend a URL to a collaborator
 *
 * Arguments:
 *
 * None
 *
 */
function recommend() {
    
    if (isVersionCorrect)
    {
        if (loggedIn) {
			var url = getCurrentURL();
			var title = getCurrentTitle();
            var targetURL = globalURL+'CSpace/services/recommend.php?'+'page='+url+'&title='+title;
            loadURLPopup(targetURL,'Recommend');
        }
        else
            alert("Your session has expired. Please log in again.");
    }
} // function recommend()




/*
 *
 * function annotate()
 *
 * opens a popup window to annotate page
 *
 * Arguments:
 *
 * None
 *
 */
function annotate() {
    
    if (isVersionCorrect)
    {
        if (loggedIn) {
			var url = getCurrentURL();
			var title = getCurrentTitle();
            var targetURL = globalURL+'CSpace/services/annotations.php?'+'page='+url+'&title='+title;
            loadURLPopup(targetURL,'Annotations');

        }
        else
            alert("Your session has expired. Please log in again.");
    }
} // function annotate()





/*
 *
 * function save()
 *
 * Collects highlighted passage as snippet and opens popup window for user to rate 
 * and additionally annotate snippet
 *
 * Arguments:
 *
 * None
 *
 */
// Function to collect highlighted passage from the page as a snippet.
function snip() {

    if (isVersionCorrect)
    {
        if (loggedIn) {
            var snippet = selection.text;
			var url = getCurrentURL();
			var title = getCurrentTitle();
            targetURL = globalURL+'CSpace/services/saveSnippet.php?'+'URL='+url+'&snippet='+snippet+'&title='+title;
            loadURLPopup(targetURL,'Snippet');
        }
        else
            alert("Your session has expired. Please log in again.");
    }
}





/*
 *
 * PHP REQUESTS/LOAD URLs
 *
 */

/*
 * function pair(name,value)
 *
 * Pair a parameter name and its value.
 *
 * Arguments:
 *
 * `name`: The key of the parameter
 *
 * `value`: The parameter value
 */
function pair(name,value) {
    this.name = name;
    this.value = value;
}

/*
 * function phpRequestExecute(url,text)
 *
 * Start phpRequest Object
 *
 * Arguments:
 *
 * `serverScript`: the URL to execute
 *
 */
function phpRequest(serverScript) {
	//Set some default variables
	this.parms = new Array();
	this.parmsIndex = 0;
    
	//Set the server url
	this.server = serverScript;
    
	//Add two methods
	this.execute = phpRequestExecute;
	this.add = phpRequestAdd;
}

// Add parameters to for creating an HTTP request with PHP.
function phpRequestAdd(name,value) {
    //Add a new pair object to the params
    this.parms[this.parmsIndex] = new pair(name,value);
    this.parmsIndex++;
}

/*
 * function phpRequestExecute(url,text)
 *
 * Execute an HTTP request
 *
 * Arguments:
 *
 * None
 *
 */
function phpRequestExecute() {
    //Set the server to a local variable
    var targetURL = this.server;
    
    //Try to create our XMLHttpRequest Object
    try {
        var httpRequest = new XMLHttpRequest();
    }
    catch (e) {
        //        alert('Error creating the connection!');
        return;
    }
    
    //Make the connection and send our data
    try {
        var txt = "?1";
        for(var i in this.parms) {
            txt = txt+'&'+this.parms[i].name+'='+this.parms[i].value;
        }
        //Two options here, only uncomment one of these
        //GET REQUEST
		var currentURL = targetURL+txt;
        httpRequest.open("GET", currentURL, false, null, null);
        httpRequest.send('');
    }
    catch (e) {
        //        alert('An error has occured calling the external site: '+e);
        return false;
    }
    
    //Make sure we received a valid response
    switch(httpRequest.readyState) {
        case 1,2,3:
            //           alert('Bad Ready State: '+httpRequest.status);
            return false;
            break;
        case 4:
            if(httpRequest.status !=200) {
                //               alert('The server respond with a bad status code: '+httpRequest.status);
                return false;
            }
            else {
                var response = httpRequest.responseText;
            }
            break;
    }
    return response;
}











/*
 * function showSnippets(url,text)
 *
 * Shows a user's snippets in a popup window
 *
 * Arguments:
 *
 * None
 *
 */
function showSnippets() {
//    if (isVersionCorrect)
//    {
//        var page = window.content.document.location;
//        page = encodeURIComponent(page);
//        var title = encodeURIComponent(document.title);
//        url = 'http://www.coagmento.org/CSpace/snippets.php?1&page='+page+'&title='+title;
//        window.open(url,'Snippets','resizable=yes,scrollbars=yes,width=640,height=480,left=600');
//    }
}





/*
 * function setMood(value,label)
 *
 * Changes the user's mood.
 *
 * Arguments:
 *
 * `value`: The numeric value of the mood.
 *
 * `label`: The string name for the mood.
 *
 * Valid arguments are: (5,'Definitely my day'), (4,'Good'), (3,'So so'), (2,'Not 
 * Good'), (1,'Not my day')
 *
 */
//Set Mood original XUL:
//<button id="coagmento-Mood-Menupopup" type="menu" label="How do you feel now?" disabled="true" style="font-size: 11px; font-weight:bold">
//<menupopup>
//<menuitem label="Definitely my day" value="5" oncommand="setMood(5,'Definitely my day')"/>
//<menuitem label="Good" value="4" oncommand="setMood(4,'Good')"/>
//<menuitem label="So so" value="3" oncommand="setMood(3,'So so')"/>
//<menuitem label="Not Good" value="2" oncommand="setMood(2,'Not Good')"/>
//<menuitem label="Not my day" value="1" oncommand="setMood(1,'Not my day')"/>
//</menupopup>
//</button>
function setMood(value, label)
{
//    if (isVersionCorrect)
//    {
//        if (loggedIn) {
//            //document.getElementById('coagmento-Mood-Menupopup').label = "How do you feel now? " + label;
//            var xmlHttpTimeoutChangeMood;
//            var xmlHttpConnectionChangeMood = new XMLHttpRequest();
//            xmlHttpConnectionChangeMood.open('GET', 'http://www.coagmento.org/CSpace/changeMood.php?value='+value, true);
//            xmlHttpConnectionChangeMood.onreadystatechange=function(){
//                if (xmlHttpConnectionChangeMood.readyState == 4 && xmlHttpConnectionChangeMood.status == 200) {
//                    clearTimeout(xmlHttpTimeoutChangeMood);
//                }
//            }
//            
//            xmlHttpConnectionChangeMood.send(null);
//            xmlHttpTimeoutChangeMood = setTimeout(function(){
//                                                  xmlHttpTimeoutChangeMood.abort();
//                                                  clearTimeout(xmlHttpConnectionChangeMood);
//                                                  }
//                                                  ,3000);
//        }
//    }
}


/*
 * function setStatus()
 *
 * Set the counts
 *
 * Arguments:
 *
 * `res`: the counts, as an array of strings
 *
 */
function setStatus(res)
{
//    var button = document.getElementById("coagmento-Views-Status-Button");
//    button.label = res[2];
//    var button = document.getElementById("coagmento-Notes-Status-Button");
//    button.label = res[3];
//    var button = document.getElementById("coagmento-Snippets-Status-Button");
//    button.label = res[4];
//    var button = document.getElementById("coagmento-Project-Status-Button");
//    button.label = res[5];
}

/*
 * function cleanStatus()
 *
 * Clean count status in toolbar
 *
 * Arguments:
 *
 * None
 *
 */
function cleanStatus()
{
//    var button = document.getElementById("coagmento-Views-Status-Button");
//    button.label = "";
//    var button = document.getElementById("coagmento-Notes-Status-Button");
//    button.label = "";
//    var button = document.getElementById("coagmento-Snippets-Status-Button");
//    button.label = "";
//    var button = document.getElementById("coagmento-Project-Status-Button");
//    button.label = "";
}






// CHECK IF THE STATUS OF THE CURRENT PAGE, IF BOOKMARKED OR NOT
function checkCurrentPage()
{
    if(isVersionCorrect)
    {
        if (loggedIn)
        {
			var url = getCurrentURL();
			var title = getCurrentTitle();
            var req = new phpRequest("http://www.coagmento.org/CSpace/pageStatus.php");
            req.add('URL',url);
            req.add('title', title);
            req.add('version', getVersion());
            var response = req.execute();
            var res = response.split(";");
            if (res[0]>0)
            {
                if (res[1]==1)
                    bookmark_button.label = "Remove";
                else
                    bookmark_button.label = "Bookmark";
                setStatus(res);
            }
            else
            {
                if(isVersionCorrect)
                {
                    isVersionCorrect = false;
                    disableButtons(true);
                    home_button.disabled = false;
                    login_button.disabled = true;
                    if(confirm("There is a new version available of Coagmento toolbar, do you want to Download it now?. If no, please visit your CSpace later to download it (Click the HOME button on the Coagmento Toolbar). Coagmento will not work until you get the latest version"))
                    {
                        window._content.document.location = res[6];
                    }
                }
            }
        }
    }
}



/*
 * function serverDown()
 *
 * Check whether server is down
 *
 * Arguments:
 *
 * None
 *
 */
// Some parts of the function below were taken from http://stackoverflow.com/questions/1018705/how-to-detect-timeout-on-an-ajax-xmlhttprequest-call-in-the-browser
function serverDown()
{
    connectionFlag = false;
    disableButtons(true);
    login_button.label = "Connect";
    cleanStatus();
    login_button.disabled = true;
    sidebar.hide();
//    var broadcaster = top.document.getElementById('viewSidebar');
//    if (broadcaster.hasAttribute('checked'))
//        toggleSidebar('viewSidebar',false);
    isExclusive = false;
}


/*
 * function savePQ()
 *
 * Saves the current page/query.  Automatically triggered on page/tab change
 *
 * Arguments:
 *
 * None
 *
 */
function savePQ()
{
    if (isVersionCorrect)
    {
        // Create the request for saving the page (and query) and execute it
        updateLoginStatus();
        if (loggedIn)
        {
			var url = getCurrentURL();
			var title = getCurrentTitle();

            var xmlHttpTimeoutSavePQ;
            var xmlHttpConnectionSavePQ = new XMLHttpRequest();
            xmlHttpConnectionSavePQ.open('GET', 'http://www.coagmento.org/CSpace/savePQ.php?URL='+url+'&title='+title, true);
            xmlHttpConnectionSavePQ.onreadystatechange=function(){
                if (xmlHttpConnectionSavePQ.readyState == 4 && xmlHttpConnectionSavePQ.status == 200) {
                    clearTimeout(xmlHttpTimeoutSavePQ);
                }
            }
            
            xmlHttpConnectionSavePQ.send(null);
            xmlHttpTimeoutSavePQ = setTimeout(function(){
                                              xmlHttpConnectionSavePQ.abort();
                                              clearTimeout(xmlHttpTimeoutSavePQ);
                                              }
                                              ,3000);
            
        }
        
        
    }
}


/*
 * function tabSelected(event)
 *
 * Triggers when the user switches tabs; saves current page and checks current page
 *
 * Arguments:
 *
 * `event`: The triggering event
 *
 */
function tabSelected(event) {
    if (isVersionCorrect)
    {
        savePQ();
        checkCurrentPage();
    }
}


var container = tabutils.getTabContainer(windowutils.getMostRecentBrowserWindow());
container.addEventListener("TabSelect", tabSelected, false);
container.addEventListener('DOMSubtreeModified',function(){setTimeout(onChange,1);},false);
savePQ();
checkCurrentPage();


