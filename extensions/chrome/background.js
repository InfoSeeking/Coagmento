// TODO:
// 1) Login
// 2) Toggle logged in
// 3) Toggle popup page accordingly
//TODO: Properly log in and log out.  Set userID and projectID
//TODO: Create default project for user upon login/creation, projectID=userID
//TODO: bookmarkID also indexed by stageID?
// TODO: savePQ
// TODO: save click, scroll, type



var domain = 'http://localhost:8000';
var apidomain = 'http://localhost:8000/api/v1';

var loginUrl = domain + "/sidebar/auth/login";
var logoutUrl = domain + "/sidebar/auth/logout";
var homeUrl = domain + "/";
var loggedInHomeUrl = domain + "/workspace";

var savePageUrl = apidomain + '/pages';
var saveQueryUrl = apidomain+"/queries";
var saveBookmarkUrl = apidomain + "/bookmarks";
var saveSnippetUrl = apidomain + "/snippets";


var contactUrl = "mailto:mmitsui@scarletmail.rutgers.edu?Subject=Intent%20Study%20Inquiry";


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






// var bookmark_page = function(info,tab) {
//     var url = info.pageUrl
//     var notes = "-"
//     var xhr = new XMLHttpRequest();
//     chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
//         var params = {
//             "url":url,
//             "notes":notes,
//             "title":tabs[0].title,
//             "project_id":project_id
//         }
//         console.log("PARAMS are" +JSON.stringify(params))
//         xhr.open("POST", saveBookmarkUrl, false);
//         xhr.setRequestHeader("Content-type", "application/json");
        
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState == 4) {
//                 var result = JSON.parse(xhr.responseText);
//                 console.log("After Bookmark"+result);
//             }else{
//                 console.log("Bookmark ready state:"+xhr.readyState);
//             }
//         }
//         xhr.send(JSON.stringify(params));

//     });
// };

// var snip_text = function(info,tab){
//     console.log("SELECTION" + JSON.stringify(info));
//     var title;
//     var xhr = new XMLHttpRequest();
//     chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
//         console.log(tabs);
//         var title= tabs[0].title;   //title
//         var params = {
//             "title": title,
//             "url":tabs[0].url,
//             "text":info.selectionText,
//             // TODO: change project ID
//             "project_id":0

//         }
//         console.log("PARAMS:"+JSON.stringify(params));
//         xhr.open("POST", saveSnippetUrl, false);
//         xhr.setRequestHeader("Content-type", "application/json");
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState == 4) {
//                 var result = JSON.parse(xhr.responseText);
//                 console.log("AFTER SNIPPET"+result);
//             }else{
//                 console.log("Snippet ready state:"+xhr.readyState);
//             }
//         }
//         xhr.send(JSON.stringify(params));
        
//     });
// }



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


function makeQuestionnaireNotification(){
    var options =
    {
        type: "basic",
        title: "IMPORTANT",
        message: "You have a new form to complete!"
    }
    chrome.notifications.create(null,options);
    //      chrome.notifications.onClicked.addListener(function(notificationId) {
    //              window.location.href("inbetween.html")
    //      });
}

chrome.windows.onCreated.addListener(login_check);
chrome.runtime.onStartup.addListener(login_check);

// function renderLoggedIn(loggedIn){
//         var red = [255,0,0,255];
//         // var green = [0,255,0,255];
//         var green = [34,139,34,255];
//         if(loggedIn){
//             chrome.browserAction.setBadgeText({text:' '});
//             chrome.browserAction.setBadgeBackgroundColor({color:green});
//         }else{
//             chrome.browserAction.setBadgeText({text:' '});
//             chrome.browserAction.setBadgeBackgroundColor({color:red});
//         }
//     }



// TODO: WTF is this
// function savePQ(url,title,active,tabId,windowId,now,action,details){
//     console.log("DETAILS" + JSON.stringify(details));
//     console.log("Title is" + title);
//     var data = {
//         url:url,
//         title:title,
//         active:active,
//         windowId:windowId
//         // TODO: action, and other columns
//     }
//     console.log("DETAILS TITLE" + details.tab.title);
//     var data2 = {
//         "project_id":project_id,
//         "text":details.tab.title,
//         "search_engine":"Google"

//     }
//     // alert(data.action);
//     console.log("DETAILS" + JSON.stringify(details));
//     if (!details.tab.url.includes("localhost") || (!details.tab.url.includes("chrome://extensions"))){
//         console.log("DAT TEXT" + data2.text);
//         if(data2.text.includes("New Tab")){
//             console.log("yes");
//             return;
//         }
//         if(details.tab.url.includes("New Tab")){
//             return;

//         }
//         console.log("making request");
//         var xhr = new XMLHttpRequest();
//         var url2 = domain + "/queries"
//         xhr.open("POST", url2 , false);
//         xhr.setRequestHeader("Content-type", "application/json");
//         xhr.send(JSON.stringify(data2));
//         var result = xhr.responseText;
//         console.log("RESULt" + result);
//     }
//     console.log("URL" + url);
// }

// function saveAction(action,value,actionJSON,now){
//     console.log("AXTION JSON"+ JSON.stringify(actionJSON))
//     if(actionJSON.tab){
//         if(actionJSON.tab.url){
//             var data = {
//                 "title":actionJSON.tab.title,
//                 "project_id":project_id,
//                 "url":actionJSON.tab.url
//             }

//             if(action.indexOf("tabs.")!==-1){
//                 previousTabAction = action;
//                 previousTabActionData = data;
//             }else if(action.indexOf("windows.")!==-1){
//                 previousWindowAction = action;
//                 previousWindowActionData = data;
//             }else if(action.indexOf("webNavigation.")!==-1){
//                 previousWebNavAction = action;
//                 previousWebNavActionData = data;
//             }
//             previousAction = action;
//             previousActionData = data;
//             //if we have localhost or chrome extension tab break out
//             if(data.url.includes("localhost") || data.url.includes("chrome://extensions")){
//                 return;
//             }
//             else if(data.url.includes("New Tab") || data.title.includes("New Tab")){

//                 return;
//             }
//             else if(data.url.includes("newtab")){
//                 return;
//             }
//             var xhr = new XMLHttpRequest();

//             xhr.open("POST", actionSaveUrl, false);
//             xhr.setRequestHeader("Content-type", "application/json");
//             xhr.send(JSON.stringify(data));
//             var result = xhr.responseText;
//         };

//     }
// }
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



// var saveTabActivated = function(activeInfo){
//     var now = new Date();
//     chrome.tabs.get(activeInfo.tabId, function(tab){
//         if(tab){
//             Url = (tab.hasOwnProperty('url')?tab.url:"");
//             title = (tab.hasOwnProperty('title')?tab.title:"");
//             active = tab.active;
//             tabId = (tab.hasOwnProperty('id')?tab.id:-1);
//             windowId = tab.windowId;
//             activeInfo.tab = tab;

//             chrome.tabs.executeScript(
//                 tabId,
//                 { code: "document.referrer;" },
//                 function(result) {
//                     activeInfo.referrerInfo = result;
//                     saveAction("tabs.onActivated",activeInfo.tabId,activeInfo,now);
//                     savePQ(Url,title,active,tabId,windowId,now,"tabs.onActivated",activeInfo);
//                 }
//             );
//         }else{
//             // alert(chrome.runtime.lastError);
//         }
//     });
// }


// var saveTabAttached = function(tabId, attachInfo){
//     var now = new Date();
//     attachInfo.tabId = tabId;
//     saveAction("tabs.onAttached",tabId,attachInfo,now);
// }


// var saveTabCreated = function(tab){
//     var now = new Date();
//     var tabId = tab.id;
//     var currentTab = null;
//     chrome.tabs.query({active: true, currentWindow: true}, function(arrayOfTabs) {
//         currentTab = arrayOfTabs;
//         chrome.tabs.executeScript(
//             tabId,
//             { code: "document.referrer;" },
//             function(result) {
//                 tab.referrerInfo = result;
//                 saveAction("tabs.onCreated",tab.id,{currentTab:currentTab,newTab:tab},now);
//             }
//         );

//     });
//     chrome.tabs.getCurrent(function (result){
//     });
// }

// var saveTabDetached = function(tabId, detachInfo){
//     var now = new Date();
//     detachInfo.tabId = tabId;
//     saveAction("tabs.onDetached",tabId,detachInfo,now);
// }

// var saveTabHighlighted = function(highlightInfo){
//     var now = new Date();
//     saveAction("tabs.onHighlighted",highlightInfo.tabIds.join(),highlightInfo,now);
// }

// var saveTabMoved = function(tabId, moveInfo){
//     var now = new Date();
//     moveInfo.tabId = tabId;
//     saveAction("tabs.onMoved",tabId,moveInfo,now);
// }

// var saveTabRemoved = function(tabId, removeInfo){
//     var now = new Date();
//     removeInfo.tabId = tabId;
//     saveAction("tabs.onRemoved",tabId,removeInfo,now);
// }

// var saveTabReplaced = function(addedTabId, removedTabId){
//     var now = new Date();
//     var tabId = addedTabId;

//     chrome.tabs.executeScript(
//         tabId,
//         { code: "document.referrer;" },
//         function(result) {
//             saveAction("tabs.onReplaced",addedTabId,{addedTabId:addedTabId,removedTabId:removedTabId,referrerInfo:result},now);
//         }
//     );

// }


// var saveTabUpdated = function(tabId, changeInfo, tab){
//     var now = new Date();
//     var action = "tabs.onUpdated";
//     var value = tabId;
//     changeInfo.tabId = tabId;
//     changeInfo.tab = tab;

//     if ('status' in changeInfo && changeInfo.status === 'complete') {
//         chrome.tabs.executeScript(tabId, 
//             { file: "external/js/jquery-3.2.1.min.js" }
//             , function() {
//                 if (chrome.runtime.lastError) {
//                     console.log("tabs.onUpdated (tabs.executeScript-jquery): "+chrome.runtime.lastError.message);
//                 }
//                 chrome.tabs.executeScript(tabId, { 
//                         allFrames: true, 
//                         file: "payload.js" 
//                     },
//                     function(){
//                         if (chrome.runtime.lastError) {
//                             console.log("tabs.onUpdated (tabs.executeScript-payload): "+chrome.runtime.lastError.message);
//                         }
//                     }
//                 );
//         });
//     }


//     console.log("UPDATED");
//     if(('status' in changeInfo && changeInfo.status == 'complete')&& !('url' in changeInfo)){
//         chrome.tabs.get(changeInfo.tabId, function(tab){
//             Url = (tab.hasOwnProperty('url')?tab.url:"");
//             title = (tab.hasOwnProperty('title')?tab.title:"");
//             active = tab.active;
//             tabId = (tab.hasOwnProperty('id')?tab.id:-1);
//             windowId = tab.windowId;

//             chrome.tabs.executeScript(
//                 tabId,
//                 { code: "document.referrer;" },
//                 function(result) {
//                     changeInfo.referrerInfo = result;

//                     saveAction("tabs.onUpdated",value,changeInfo,now);
//                     savePQ(Url,title,active,tabId,windowId,now,"tabs.onUpdated",changeInfo);
//                 }
//             );
//         });
//     }
// }

// var saveWindowCreated = function(windowInfo){
//     var now = new Date();
//     saveAction("windows.onCreated",windowInfo.id,windowInfo,now);
// }

// var saveWindowRemoved = function(windowId){
//     var now = new Date();
//     saveAction("windows.onRemoved",windowId,{windowId:windowId},now);
// }

// var saveWindowFocusChanged = function(windowId){
//     var now = new Date();
//     saveAction("windows.onFocusChanged",windowId,{windowId:windowId},now);
// }

// var saveWebNavigationCommitted = function(details){
//     var now = new Date();

//     if (details.transitionType != 'auto_subframe'){
//         // if (details.transitionType.indexOf('auto') == -1){
//         chrome.tabs.get(details.tabId, function(tab){
//             Url = (tab.hasOwnProperty('url')?tab.url:"");
//             title = (tab.hasOwnProperty('title')?tab.title:"");
//             active = tab.active;
//             tabId = (tab.hasOwnProperty('id')?tab.id:-1);
//             windowId = tab.windowId;
//             details.tab = tab;

//             chrome.tabs.executeScript(
//                 tabId,
//                 { code: "document.referrer;" },
//                 function(result) {
//                     details.referrerInfo = result;
//                     saveAction("webNavigation.onCommitted",details.tabId,details,now);
//                     savePQ(Url,title,active,tabId,windowId,now,"webNavigation.onCommitted",details);
//                 }
//             );

//         });
//     }

// }

// // Get URL, insert action as savePQ
// chrome.tabs.onActivated.addListener(saveTabActivated);
// chrome.tabs.onAttached.addListener(saveTabAttached);
// // IMPORTANT
// // // TODO: No PQ action here? see if another action accompanies a "open link in new tab" URL
// chrome.tabs.onCreated.addListener(saveTabCreated);
// // TODO: No PQ action here? see if highlight also changes.
// chrome.tabs.onDetached.addListener(saveTabDetached);
// chrome.tabs.onHighlighted.addListener(saveTabHighlighted);
// // TODO: 2) When move action is executed on an inactive, is there any other action that fires? Such as onHighlighted or onActivated?
// chrome.tabs.onMoved.addListener(saveTabMoved);
// // TODO: Other highlighted/activated actions when an active tab is closed?
// chrome.tabs.onRemoved.addListener(saveTabRemoved);
// // IMPORTANT
// chrome.tabs.onReplaced.addListener(saveTabReplaced);
// // Status types: either "loading" or "complete"
// // Note: only use onCommitted
// // TODO: Use only onCommitted?  Or this too?
// chrome.tabs.onUpdated.addListener(saveTabUpdated);
// // TODO: Any tab IDs I should record here?
// // TODO: Any highlighted/change actions in addition that are typically fired?
// chrome.windows.onCreated.addListener(saveWindowCreated);
// // TODO: Any tab IDs I should record here?
// // TODO: Any highlighted/change actions in addition that are typically fired?
// chrome.windows.onRemoved.addListener(saveWindowRemoved);
// // TODO: get currently active tab ID
// // TODO: Any highlighted/change actions in addition that are typically fired?
// // TODO: Why is the windowID sometimes -1? Is that when focus is going away from Chrome?  Might be useful...
// chrome.windows.onFocusChanged.addListener(saveWindowFocusChanged);
// // TODO: Multiple calls per page sometimes?
// chrome.webNavigation.onCommitted.addListener(saveWebNavigationCommitted);
// // Note: can't use omnibox. Too limited for our purposes




   //  function renderLoggedIn(loggedIn){
   //   var red = [255,0,0,255];
   //   // var green = [0,255,0,255];
   //   var green = [34,139,34,255];
   //   if(chrome.extension.getBackgroundPage().loggedIn){
   //       chrome.browserAction.setBadgeText({text:' '});
            // chrome.browserAction.setBadgeBackgroundColor({color:green});
   //   }else{
   //       chrome.browserAction.setBadgeText({text:' '});
            // chrome.browserAction.setBadgeBackgroundColor({color:red});
   //   }
   //  }