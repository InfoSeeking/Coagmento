// TODO:
// 1) Login
// 2) Toggle logged in
// 3) Toggle popup page accordingly
//TODO: Properly log in and log out.  Set userID and projectID
//TODO: Create default project for user upon login/creation, projectID=userID
//TODO: bookmarkID also indexed by stageID?
// TODO: savePQ
// TODO: save click, scroll, type



var domain = global_config['domain'];
var apidomain = global_config['apidomain'];

var loginUrl = domain + "/sidebar/auth/login";
var logoutUrl = domain + "/sidebar/auth/logout";
var homeUrl = domain + "/";
var loggedInHomeUrl = domain + "/auth/login";

var savePageUrl = apidomain + '/pages';
var saveQueryUrl = apidomain+"/queries";
var saveBookmarkUrl = apidomain + "/bookmarks";
var getBookmarksUrl = apidomain + "/bookmarks";
var getPagesUrl = apidomain + "/pages";
var getQueriesUrl = apidomain + "/queries";
var saveSnippetUrl = apidomain + "/snippets";



var saveActionUrl =  domain + "/sidebar/actions";
var saveClickUrl =  domain + "/sidebar/clicks";
var saveKeystrokeUrl =  domain + "/sidebar/keystrokes";
var saveScrollUrl =  domain + "/sidebar/scrolls";
var saveCopyUrl =  domain + "/sidebar/copies";
var savePasteUrl =  domain + "/sidebar/pastes";
var saveMouseUrl =  domain + "/sidebar/scrollsmouseactions";


var contactUrl = "mailto:jl2033@scarletmail.rutgers.edu?Subject=Intent%20Study%20Inquiry";


var previousTabAction = '';
var previousWindowAction = '';
var previousWebNavAction = '';
var previousAction = '';
var previousTabActionData = null;
var previousWindowActionData = null;
var previousWebNavActionData = null;
var previousActionData = null;


var project_id = null;
var user_id = null;
var name = null;
var email = null;
var password = null;
var logged_in = false;

var bookmark_menu = null;
var snippet_menu = null;

var task_timer = null;




var create_notification = function(text){
    chrome.notifications.create(null, {
        type: 'basic',
        title: 'Action done!',
        iconUrl: 'icons/logo-48.png',
        message: text
     }, function(notificationId) {});
}


var bookmark_page = function(info,tab) {
    console.log("Bookmark");
    var url = info.pageUrl
    var notes = "-"
    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "url":url,
            "notes":notes,
            "title":tabs[0].title,
            "project_id":project_id
        }
        console.log("Bookmark - params: " +JSON.stringify(params));
        
        xhr.open("POST", saveBookmarkUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Bookmark ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                create_notification("Bookmark saved!");
                var result = JSON.parse(xhr.responseText);
            }
        }
        xhr.send(JSON.stringify(params));
    });
};

var snip_text = function(info,tab){
    console.log("Snip");
    var title;
    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var title= tabs[0].title;   //title
        var params = {
            "title": title,
            "url":tabs[0].url,
            "text":info.selectionText,
            // TODO: change project ID
            "project_id":0

        }
        console.log("Snip - params: "+JSON.stringify(params));
        xhr.open("POST", saveSnippetUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Snip ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                create_notification("Snippet saved!");
                var result = JSON.parse(xhr.responseText);
            }
        }
        xhr.send(JSON.stringify(params));
        
    });
}



var bookmark_options = {"title": "Bookmark",
        "contexts":["page","selection","link","editable"],
        // "onclick":bookmark_page,
        "id": "bookmark"}

var snippet_options = {
        "title": "Snippet",
        "contexts":["selection"],
        // "onclick":snip_text,
        "id":"snippet"
    }





chrome.contextMenus.onClicked.addListener(function(info, tab) {
    if (info.menuItemId == "snippet") {
        snip_text(info,tab)
    }

    if (info.menuItemId == "bookmark") {
        bookmark_page(info,tab)
    }
});

var create_context_menu = function(){
    bookmark_menu = chrome.contextMenus.create(bookmark_options);
    snippet_menu = chrome.contextMenus.create(snippet_options);
}

var destroy_context_menu = function(){
    chrome.contextMenus.removeAll(function(result){});
}

var show_context_menu = function(){
    chrome.contextMenus.update("bookmark",{visible:true});
    chrome.contextMenus.update("snippet",{visible:true});
}

var hide_context_menu = function(){
    chrome.contextMenus.update("bookmark",{visible:false});
    chrome.contextMenus.update("snippet",{visible:false});
}

bookmark_menu = chrome.contextMenus.create(bookmark_options);
snippet_menu = chrome.contextMenus.create(snippet_options);


function login_state(uid,pid,username,useremail,pwd){
    chrome.storage.local.set({user_id: uid, project_id:pid,name:username,email:useremail,password:pwd}, function() {});
    user_id = uid;
    project_id = pid;
    name = username;
    email = useremail;
    password = pwd;
    logged_in = true;
    chrome.browserAction.setPopup({
        popup:"loggedin.html"
    });
    show_context_menu();
}

function login(email,password){
    var xhr = new XMLHttpRequest();
    xhr.open("POST", loginUrl, false);
    xhr.setRequestHeader("Content-type", "application/json");
    var data = {"email":email,"password":password}

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            var result = JSON.parse(xhr.responseText);
            if(result.logged_in){
                login_state(result.id,result.id,result.name,email,password);
            }
        }
    }
    xhr.send(JSON.stringify(data));
}


function logout_state(){
    chrome.storage.local.remove(['user_id','name','project_id','email','password'], function() {
        user_id = null;
        project_id = null;
        name = null;
        email = null;
        password = null;
        logged_in = false;
        chrome.browserAction.setPopup({
            popup:"login.html"
        });
        hide_context_menu();
    });
}

function logout(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", logoutUrl, false);
    xhr.setRequestHeader("Content-type", "application/json");
    var data = {}
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            var result = JSON.parse(xhr.responseText);
            if(result.logged_in){
                logout_state();
            }
        }

    }

    xhr.send(JSON.stringify(data));
}


function login_check() {
    chrome.storage.local.get(['user_id','project_id','name','email','password'], function(result) {
        if (typeof result.user_id === 'undefined') {
            logout_state();
        } else {
            login(result.email,result.password);
        }  
    });
}


chrome.windows.onCreated.addListener(login_check);
chrome.runtime.onStartup.addListener(login_check);

// TODO Correct this!  Properly save page/query data and properly parse it
function savePQ(url,title,active,tabId,windowId,now,action,details){
    // var data = {
    //     url:url,
    //     title:title,
    //     active:active,
    //     windowId:windowId
    //     // TODO: action, and other columns
    // }
    // var data2 = {
    //     "project_id":project_id,
    //     "text":details.tab.title,
    //     "search_engine":"Google"

    // }
    // // alert(data.action);
    // // if (!details.tab.url.includes("localhost") || (!details.tab.url.includes("chrome://extensions"))){
    //     // if(data2.text.includes("New Tab")){
    //     //     return;
    //     // }
    //     // if(details.tab.url.includes("New Tab")){
    //     //     return;

    //     // }
    //     var xhr = new XMLHttpRequest();
    //     xhr.open("POST", savePageUrl , false);
    //     xhr.setRequestHeader("Content-type", "application/json");
    //     xhr.onreadystatechange = function(result) {
    //         if (xhr.readyState == 4) {
    //             var result = JSON.parse(xhr.responseText);
    //             var xhr2 = new XMLHttpRequest();
    //             xhr2.open("POST", saveQueryUrl, false);
    //             xhr2.setRequestHeader("Content-type", "application/json");
    //             xhr2.onreadystatechange = function(result2){
    //                 // console.log("Page and query saved!");
    //             }
    //         }
    //     }
    //     xhr.send(JSON.stringify(data2));
    //     var result = xhr.responseText;
    // // }
}

// TODO: Fix!
function saveAction(action,value,actionJSON,now){
    // if(actionJSON.tab){
    //     if(actionJSON.tab.url){

            var data = {
                'action':action,
                'value':""+value,
                "project_id":project_id,
                "user_id":user_id,
                "json":JSON.stringify(actionJSON),
                "created_at_local_ms":now,
                "created_at_local":now/1000
            }
            if(action.indexOf("tabs.")!==-1){
                previousTabAction = action;
                previousTabActionData = data;
            }else if(action.indexOf("windows.")!==-1){
                previousWindowAction = action;
                previousWindowActionData = data;
            }else if(action.indexOf("webNavigation.")!==-1){
                previousWebNavAction = action;
                previousWebNavActionData = data;
            }
            previousAction = action;
            previousActionData = data;
            //if we have localhost or chrome extension tab break out
            // if(data.url.includes("localhost") || data.url.includes("chrome://extensions")){
            //     return;
            // }
            // else if(data.url.includes("New Tab") || data.title.includes("New Tab")){

            //     return;
            // }
            // else if(data.url.includes("newtab")){
            //     return;
            // }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", saveActionUrl, false);
            xhr.setRequestHeader("Content-type", "application/json");
            xhr.onreadystatechange = function(result) {
                // console.log(result);
                if (xhr.readyState == 4) {
                    console.log("Action saved!");
                }
            }
            // console.log(data);
            xhr.send(JSON.stringify(data));
            var result = xhr.responseText;
    //     }

    // }
}
// // TODO ACTIONS
// // tab change: onactivated+onhighlighted - use onActivated
// // close current tab: onRemoved+onactivated+onHighlighted - use onActivated
// // close different tab: onRemoved
// // Remove tab from window: onactivated+ondetached+onhighlighted - use onActivated (assumes most recent onActivated is the currently viewed tab.  If activated tab is detached, there's 2 onactivated events.  If an inactive tab is dragged, only one onActivated event)
// // Attach tab to window: onAttached, onDetached, onHighlighted,onActivated - use onActivated
// // Move tab in current window: onMoved
// // "click to open in new tab": onCreated.  onActivated depends on whether immediately set to new tab.  there's an active boolean in the onCreated action. usually active=false
// // "open in new window": onCreated.  onActivated depends on whether immediately set to new tab.  there's an active boolean in the onCreated action. usually active=true
// // does onCommitted or onVisited happen when there is a click to open in new tab?

// // TODO: Window switching is important, not just onActivated.  onActivated doesn't capture that.
// // TODO: get windowId for most tab actions?
// // TODO: may need an active_tab (boolean) column in pages/queries
// // TODO: "click to open Google search result in new tab": a bunch of foadifg occurs.  This isn't captured by onCreated.  Is this captured by something else?



var saveTabActivated = function(activeInfo){
    if(logged_in){
        var now = new Date();
        chrome.tabs.get(activeInfo.tabId, function(tab){

            if(chrome.runtime.lastError){
                // console.warn("Error in saveTabActivated: " + chrome.runtime.lastError.message);
            }else{
                if(tab){
                    Url = (tab.hasOwnProperty('url')?tab.url:"");
                    title = (tab.hasOwnProperty('title')?tab.title:"");
                    active = tab.active;
                    tabId = (tab.hasOwnProperty('id')?tab.id:-1);
                    windowId = tab.windowId;
                    activeInfo.tab = tab;

                    chrome.tabs.executeScript(
                        tabId,
                        { code: "document.referrer;" },
                        function(result) {
                            activeInfo.referrerInfo = result;
                            saveAction("tabs.onActivated",activeInfo.tabId,activeInfo,now);
                            savePQ(Url,title,active,tabId,windowId,now,"tabs.onActivated",activeInfo);
                        }
                    );
                }

            }
        
    });
    }
    
}


var saveTabAttached = function(tabId, attachInfo){
    if(logged_in){
        var now = new Date();
        attachInfo.tabId = tabId;
        saveAction("tabs.onAttached",tabId,attachInfo,now);    
    }
    
}


var saveTabCreated = function(tab){
    if (logged_in){
        var now = new Date();
        var tabId = tab.id;
        var currentTab = null;
        chrome.tabs.query({active: true, currentWindow: true}, function(arrayOfTabs) {
            currentTab = arrayOfTabs;
            chrome.tabs.executeScript(
                tabId,
                { code: "document.referrer;" },
                function(result) {
                    tab.referrerInfo = result;
                    saveAction("tabs.onCreated",tab.id,{currentTab:currentTab,newTab:tab},now);
                }
            );

        });
        chrome.tabs.getCurrent(function (result){
        });
    }
    
}

var saveTabDetached = function(tabId, detachInfo){
    if(logged_in){
        var now = new Date();
        detachInfo.tabId = tabId;
        saveAction("tabs.onDetached",tabId,detachInfo,now);    
    }
    
}

var saveTabHighlighted = function(highlightInfo){
    if(logged_in){
        var now = new Date();
        saveAction("tabs.onHighlighted",highlightInfo.tabIds.join(),highlightInfo,now);    
    }
    
}

var saveTabMoved = function(tabId, moveInfo){
    if(logged_in){
        var now = new Date();
        moveInfo.tabId = tabId;
        saveAction("tabs.onMoved",tabId,moveInfo,now);
    }
}

var saveTabRemoved = function(tabId, removeInfo){
    if(logged_in){
        var now = new Date();
        removeInfo.tabId = tabId;
        saveAction("tabs.onRemoved",tabId,removeInfo,now);
    }
}

var saveTabReplaced = function(addedTabId, removedTabId){
    if(logged_in){
        var now = new Date();
        var tabId = addedTabId;
        chrome.tabs.executeScript(
            tabId,
            { code: "document.referrer;" },
            function(result) {
                saveAction("tabs.onReplaced",addedTabId,{addedTabId:addedTabId,removedTabId:removedTabId,referrerInfo:result},now);
            }
        );
    }
}


var saveTabUpdated = function(tabId, changeInfo, tab){
    if(logged_in){
        var now = new Date();
        var action = "tabs.onUpdated";
        var value = tabId;
        changeInfo.tabId = tabId;
        changeInfo.tab = tab;
        if ('status' in changeInfo && changeInfo.status === 'complete') {
            chrome.tabs.executeScript(tabId, 
                { file: "external/js/jquery-3.2.1.min.js" }
                , 
                    function() {
                        if (chrome.runtime.lastError) {
                            console.log("tabs.onUpdated (tabs.executeScript-jquery): "+chrome.runtime.lastError.message);
                        }

                        var config = {
                            domain: 'http://localhost:8000',
                            apidomain: 'http://localhost:8000/sidebar'
                        };
                        chrome.tabs.executeScript(tabId, { 
                            allFrames: true, 
                            code: "var config = "+JSON.stringify(config),
                            // file: "payload.js" 
                        },

                        function() {
                            if (chrome.runtime.lastError) {
                                console.log("tabs.onUpdated (tabs.executeScript-payload): "+chrome.runtime.lastError.message);
                            }else{
                                chrome.tabs.executeScript(tabId, 
                                    {file: 'payload.js'},
                                    function(){
                                        if (chrome.runtime.lastError) {
                                            console.log("tabs.onUpdated (tabs.executeScript-payload): "+chrome.runtime.lastError.message);
                                        }
                                    }
                                );    
                            }
                        }
                        );
                    }
                )
        }

        if(('status' in changeInfo && changeInfo.status == 'complete')&& !('url' in changeInfo)){
            chrome.tabs.get(changeInfo.tabId, function(tab){
                Url = (tab.hasOwnProperty('url')?tab.url:"");
                title = (tab.hasOwnProperty('title')?tab.title:"");
                active = tab.active;
                tabId = (tab.hasOwnProperty('id')?tab.id:-1);
                windowId = tab.windowId;

                chrome.tabs.executeScript(
                    tabId,
                    { code: "document.referrer;" },
                    function(result) {
                        changeInfo.referrerInfo = result;

                        saveAction("tabs.onUpdated",value,changeInfo,now);
                        savePQ(Url,title,active,tabId,windowId,now,"tabs.onUpdated",changeInfo);
                    }
                );
            });
        }
    }
}

var saveWindowCreated = function(windowInfo){
    if(logged_in){
        var now = new Date();
        saveAction("windows.onCreated",windowInfo.id,windowInfo,now);
    }
}

var saveWindowRemoved = function(windowId){
    if(logged_in){
        var now = new Date();
        saveAction("windows.onRemoved",windowId,{windowId:windowId},now);
    }
}

var saveWindowFocusChanged = function(windowId){
    if(logged_in){
        var now = new Date();
        saveAction("windows.onFocusChanged",windowId,{windowId:windowId},now);
    }
}

var refreshContents = function(){
    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "project_id":0
        }
        
        xhr.open("GET", getBookmarksUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Bookmark ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                var result = JSON.parse(xhr.responseText);
                chrome.runtime.sendMessage({type: "bookmark_data",data:result}, function(response) {
                    console.log(response)
                });
            }
        }
        xhr.send(JSON.stringify(params));
    });

    // var xhr = new XMLHttpRequest();
    // chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
    //     var params = {
    //         "project_id":0
    //     }
    //     console.log("Page retrieve - params: " +JSON.stringify(params));
        
    //     xhr.open("GET", getPagesUrl, false);
    //     xhr.setRequestHeader("Content-type", "application/json");
    //     xhr.onreadystatechange = function() {
    //         console.log("Page ready state:"+xhr.readyState);
    //         if (xhr.readyState == 4) {
    //             create_notification("Pages retrieved!");
    //             var result = JSON.parse(xhr.responseText);
    //             console.log("Pages retrieved!");
    //             chrome.runtime.sendMessage({type: "page_data",data:result}, function(response) {
    //                 console.log(response)
    //             });
    //         }
    //     }
    //     xhr.send(JSON.stringify(params));
    // });

    // var xhr = new XMLHttpRequest();
    // chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
    //     var params = {
    //         "project_id":0
    //     }
    //     console.log("Query retrieve - params: " +JSON.stringify(params));
        
    //     xhr.open("GET", getQueriesUrl, false);
    //     xhr.setRequestHeader("Content-type", "application/json");
    //     xhr.onreadystatechange = function() {
    //         console.log("Query ready state:"+xhr.readyState);
    //         if (xhr.readyState == 4) {
    //             create_notification("Queries retrieved!");
    //             var result = JSON.parse(xhr.responseText);
    //             console.log("Queries retrieved!");
    //             chrome.runtime.sendMessage({type: "query_data",data:result}, function(response) {
    //                 console.log(response)
    //             });
    //         }
    //     }
    //     xhr.send(JSON.stringify(params));
    // });
}




var saveWebNavigationCommitted = function(details){
    if(logged_in){
        var now = new Date();
        if (details.transitionType != 'auto_subframe'){
            // if (details.transitionType.indexOf('auto') == -1){
            chrome.tabs.get(details.tabId, function(tab){
                if(chrome.runtime.lastError){
                    // console.warn("Error in saveWebNavigationCommitted: " + chrome.runtime.lastError.message);
                }else{
                    Url = (tab.hasOwnProperty('url')?tab.url:"");
                    title = (tab.hasOwnProperty('title')?tab.title:"");
                    active = tab.active;
                    tabId = (tab.hasOwnProperty('id')?tab.id:-1);
                    windowId = tab.windowId;
                    details.tab = tab;
                    chrome.tabs.executeScript(
                        tabId,
                        { code: "document.referrer;" },
                        function(result) {
                            details.referrerInfo = result;
                            saveAction("webNavigation.onCommitted",details.tabId,details,now);
                            refreshContents();

                            savePQ(Url,title,active,tabId,windowId,now,"webNavigation.onCommitted",details);
                        }
                    );
                }
                
            });
        }
    }
}

// // Get URL, insert action as savePQ
chrome.tabs.onActivated.addListener(saveTabActivated);
chrome.tabs.onAttached.addListener(saveTabAttached);
// IMPORTANT
// // TODO: No PQ action here? see if another action accompanies a "open link in new tab" URL
chrome.tabs.onCreated.addListener(saveTabCreated);
// TODO: No PQ action here? see if highlight also changes.
chrome.tabs.onDetached.addListener(saveTabDetached);
chrome.tabs.onHighlighted.addListener(saveTabHighlighted);
// TODO: 2) When move action is executed on an inactive, is there any other action that fires? Such as onHighlighted or onActivated?
chrome.tabs.onMoved.addListener(saveTabMoved);
// TODO: Other highlighted/activated actions when an active tab is closed?
chrome.tabs.onRemoved.addListener(saveTabRemoved);
// IMPORTANT
chrome.tabs.onReplaced.addListener(saveTabReplaced);
// Status types: either "loading" or "complete"
// Note: only use onCommitted
// TODO: Use only onCommitted?  Or this too?
chrome.tabs.onUpdated.addListener(saveTabUpdated);
// TODO: Any tab IDs I should record here?
// TODO: Any highlighted/change actions in addition that are typically fired?
chrome.windows.onCreated.addListener(saveWindowCreated);
// TODO: Any tab IDs I should record here?
// TODO: Any highlighted/change actions in addition that are typically fired?
chrome.windows.onRemoved.addListener(saveWindowRemoved);
// TODO: get currently active tab ID
// TODO: Any highlighted/change actions in addition that are typically fired?
// TODO: Why is the windowID sometimes -1? Is that when focus is going away from Chrome?  Might be useful...
chrome.windows.onFocusChanged.addListener(saveWindowFocusChanged);
// TODO: Multiple calls per page sometimes?
chrome.webNavigation.onCommitted.addListener(saveWebNavigationCommitted);
// Note: can't use omnibox. Too limited for our purposes




    // function renderLoggedIn(loggedIn){
    //  var red = [255,0,0,255];
    //  // var green = [0,255,0,255];
    //  var green = [34,139,34,255];
    //  if(chrome.extension.getBackgroundPage().loggedIn){
    //      chrome.browserAction.setBadgeText({text:' '});
    //         chrome.browserAction.setBadgeBackgroundColor({color:green});
    //  }else{
    //      chrome.browserAction.setBadgeText({text:' '});
    //         chrome.browserAction.setBadgeBackgroundColor({color:red});
    //  }
    // }