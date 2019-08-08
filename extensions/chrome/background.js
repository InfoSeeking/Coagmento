// TODO:
// 1) Login
// 2) Toggle logged in
// 3) Toggle popup page accordingly
//TODO: Properly log in and log out.  Set userID and projectID
//TODO: Create default project for user upon login/creation, projectID=userID
//TODO: bookmarkID also indexed by stageID?
// TODO: savePQ
// TODO: save click, scroll, type

var domain = "http://localhost:8000";//global_config['domain'];
var apidomain = "http://localhost:8000/api/v1"//global_config['apidomain'];
var etherpadUrl = "http://localhost:8000";//global_config['etherpaddomain'];

var loginUrl = domain + "/sidebar/auth/login";
var logoutUrl = domain + "/sidebar/auth/logout";
var homeUrl = domain + "/";
var loggedInHomeUrl = domain + "/auth/login";

var savePageUrl = apidomain + '/pages';
var saveQueryUrl = apidomain+"/queries";
var savePageQueryUrl = apidomain+"/pagesqueries";
var saveBookmarkUrl = apidomain + "/bookmarks";
var saveSnippetUrl = apidomain + "/snippets";
var getBookmarksUrl = apidomain + "/bookmarks";
var getPagesUrl = apidomain + "/pages";
var getQueriesUrl = apidomain + "/queries";
var getProjectUrl = apidomain + "/currentproject";

var saveActionUrl =  domain + "/sidebar/actions";
var saveClickUrl =  domain + "/sidebar/clicks";
var saveKeystrokeUrl =  domain + "/sidebar/keystrokes";
var saveScrollUrl =  domain + "/sidebar/scrolls";
var saveScrollUrl =  domain + "/sidebar/wheels";
var saveCopyUrl =  domain + "/sidebar/copies";
var savePasteUrl =  domain + "/sidebar/pastes";
var saveMouseUrl =  domain + "/sidebar/scrollsmouseactions";

var getCurrentStage = domain + "/api/v1/stages/current";
var gotoNextStage = domain + "/stages/next";
var querySegmentQuestionnaireUrl = domain + "/api/v1/queryquestionnaire";

var checkLoggedInUrl = domain + "/auth/loggedin";

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
var stage_id = null;
var name = null;
var email = null;
var password = null;
var logged_in_extension = false;
var logged_in_browser = false;

var bookmark_menu = null;

var task_timer = null;
var stage_data = null;
var new_querysegmentid = null;
var current_querysegmentid = null;
var current_querysegmentid_submitted = true;
var current_query = null;

var timed = null;
var time_limit = null;
var start_time = null;
var time_zone = null;
var current_promptnumber = null;
var new_prompt = null;


// function startup_script(){
//     chrome.browserAction.setBadgeText({text:""});
//     login_check();
// }


// Done
function create_notification(text,title){
    chrome.notifications.create(null, {
        type: 'basic',
        title: title,
        iconUrl: 'icons/logo-48.png',
        message: text
     }, function(notificationId) {});
}

// Done
function bookmark_page(info,tab) {
    var url = info.pageUrl
    var notes = "-"
    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "url":url,
            "notes":notes,
            "title":tabs[0].title,
            "project_id": project_id
        }
        xhr.open("POST", saveBookmarkUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                create_notification("Your bookmark has been saved","Bookmarks");
                // console.log("Bookmark saved");
                // console.log(xhr.responseText);
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
           "project_id": project_id

        }
        console.log("Snip - params: "+JSON.stringify(params));
        xhr.open("POST", saveSnippetUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Snip ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                create_notification("Snippet saved!","Snippets");
                var result = JSON.parse(xhr.responseText);
            }
        }
        xhr.send(JSON.stringify(params));

    });
}


// Done
var bookmark_options = {
        "title": "Bookmark",
        "contexts":["page","selection","link","editable"],
        //"onclick":bookmark_page,
        "id": "bookmark"
      }

var snippet_options = {
        "title": "Snippet",
        "contexts":["selection"],
        //"onclick":snip_text,
        "id":"snippet"
    }


// Done
var create_context_menu = function(){
    bookmark_menu = chrome.contextMenus.create(bookmark_options);
    snippet_menu = chrome.contextMenus.create(snippet_options);

}

// Done
var destroy_context_menu = function(){
    chrome.contextMenus.removeAll(function(result){});
}

// Done
var show_context_menu = function(){
    chrome.contextMenus.update("bookmark",{visible:true});
    chrome.contextMenus.update("snippet",{visible:true});
}

// Done
var hide_context_menu = function(){
    chrome.contextMenus.update("bookmark",{visible:false});
    chrome.contextMenus.update("snippet",{visible:false});
}

// Done
function login_state_extension(uid,pid,username,useremail,pwd,stg_data){
    chrome.storage.local.set({user_id: uid, project_id:pid,name:username,email:useremail,password:pwd}, function() {});
    user_id = uid;
    project_id = pid;
    name = username;
    email = useremail;
    password = pwd;
    logged_in_extension = true;
    stage_data = stg_data;
    // console.log("LOGGED IN!");
    chrome.browserAction.setPopup({
        popup:"loggedin.html"
    });
    show_context_menu();
}

// Done
function login_extension(email,password){
    var xhr = new XMLHttpRequest();
    xhr.open("POST", loginUrl, false);
    xhr.setRequestHeader("Content-type", "application/json");
    var data = {"email":email,"password":password}

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            var result = JSON.parse(xhr.responseText);
            if(result.logged_in){
                // TODO: Proper project ID
                login_state_extension(result.id,result.project_id,result.name,email,password,result.stage_data);
            }
        }
    }
    xhr.send(JSON.stringify(data));
}

// Done
function logout_state_extension(){
    chrome.storage.local.remove(['user_id','name','project_id','email','password'], function() {
        user_id = null;
        project_id = null;
        name = null;
        email = null;
        password = null;
        logged_in_extension = false;
        stage_data = null;
        chrome.browserAction.setPopup({
            popup:"login.html"
        });
        hide_context_menu();
    });
}

// Done
function logout_extension(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", logoutUrl, false);
    xhr.setRequestHeader("Content-type", "application/json");
    var data = {}
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            var result = JSON.parse(xhr.responseText);
            if(!result.logged_in){
                logout_state_extension();
            }
        }
    }
    xhr.send(JSON.stringify(data));
}


var login_check_browser = function(callback1,callback2){
         var xhr = new XMLHttpRequest();
         xhr.open("GET", checkLoggedInUrl, false);
         xhr.setRequestHeader("Content-type", "application/json");
         xhr.onreadystatechange = function() {
             if (xhr.readyState == 4) {
                //console.log("CHECK LOGGED IN RESPONSE");
                 //console.log(xhr.responseText);
                 if(xhr.responseText==""){
                     logged_in_browser = false;
                 }else{
                     logged_in_browser = true;
                 }
                 // var result = JSON.parse(xhr.responseText);
             }
             callback1(callback2);
         }
         xhr.send();
}

function login_check_extension(callback) {
    chrome.storage.local.get(['user_id','project_id','name','email','password'], function(result) {
        if (typeof result.user_id === 'undefined') {
            logged_in_extension = false;
        } else {
            logged_in_extension = true;
        }

        callback();
        // if (typeof result.user_id === 'undefined') {
        //     logout_state();
        // } else {
        //     login(result.email,result.password);
        // }
    });
}

function resolve_login(){
    console.log(logged_in_browser);
    console.log(logged_in_extension);
    if(logged_in_browser && logged_in_extension){
        chrome.storage.local.get(['user_id','project_id','name','email','password'], function(result) {
            login_extension(result.email,result.password);
        });
    }else if(!logged_in_browser && !logged_in_extension){
        logout_extension();
    }else{
        if(logged_in_browser && !logged_in_extension){
            var xhr = new XMLHttpRequest();
            xhr.open("GET", checkLoggedInUrl, false);
            xhr.setRequestHeader("Content-type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    console.log("ULS: CHECK LOGGED IN RESPONSE");
                    //console.log(xhr.responseText);
                    var result = JSON.parse(xhr.responseText);
                    if(!result.logged_in){
                        logged_in_browser = false;
                        logged_in_extension = false;
                    }else{
                        login_state_extension(result.id,result.project_id,result.name,email,password);
                        logged_in_extension = true;
                    }
                }
            }
            xhr.send();
        }else if(!logged_in_browser && logged_in_extension){
            chrome.storage.local.get(['user_id','project_id','name','email','password'], function(result) {
                login_extension(result.email,result.password);
                logged_in_browser = true;
            });
        }
    }
}

// TODO: Check timestamps
function update_login_state(){
    console.log("ULS: CHECK LOGIN STATE");
    login_check_browser(login_check_extension,resolve_login);
}



// TODO Correct this!  Properly save page/query data and properly parse it
function savePQ (url,title,active,tabId,windowId,now,action,details){
    // $localTime = $_POST['localTime'];
    // $localDate = $_POST['localDate'];
    // $localTimestamp = $_POST['localTimestamp'];
    // $details_string = mysql_escape_string($_POST['details']);
    // $url = mysql_escape_string($_POST['url']);
    // $title = mysql_escape_string($_POST['title']);
    // $active_tab = intval($_POST['active']=='true');
    // $tabID = $_POST['tabId'];
    // $windowID= $_POST['windowId'];
    // $is_coagmento = intval(substr($_POST['url'], 0, strlen('http://coagmento.org')) === 'http://coagmento.org');

    var data = {
        "url":url,
        "title":title,
        "active":active,
        "created_at_local_ms":now,
        "tabId":tabId,
        "windowId":windowId,
        "created_at_local":now/1000,
        "details":JSON.stringify(details),
        "user_id":1,//user_id,
        "project_id":1,//project_id,
        "stage_id":1,//stage_data.id,
        "action":action
    }
    console.log("PQ DATA");
    console.log(data);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", savePageQueryUrl , true);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.onreadystatechange = function(result) {
        if (xhr.readyState == 4) {
            console.log("SAVE PAGE QUERY");
            console.log(xhr.responseText)
            var result = JSON.parse(xhr.responseText);
            console.log(result);
            chrome.runtime.sendMessage({type: "new_page",data:result}, function(response) {
               console.log(response);
            });

            if(result.new_prompt){
            // if(result.new_querysegment){
                // if(current_querysegmentid != null){
                    current_querysegmentid_submitted = false;
                    var query = result.new_query
                    create_notification("You are starting a new search segment.  Please open your extension to answer a questionnaire.","Complete Questionnaire");

                    chrome.runtime.Message({type: "new_querysegment",data:{old_id:current_querysegmentid,query:query}}, function(response) {
                    console.log(response);
                    });
                    // chrome.runtime.sendMessage({type: "new_querysegment",data:{old_id:current_querysegmentid,query:query}}, function(response) {
                    // console.log(response);
                    // });
                    current_promptnumber = result.prompt_number;
                    new_prompt = true;
                    current_querysegmentid = result.new_querysegmentid;
                    current_query = result.new_query;
                    new_querysegmentid = result.new_querysegmentid;

                // }
            }
        }
    }
    xhr.send(JSON.stringify(data));

    // var data = {
    //             'action':action,
    //             'value':""+value,
    //             "project_id":project_id,
    //             "user_id":user_id,
    //             "json":JSON.stringify(actionJSON),
    //             "created_at_local_ms":now,
    //             "created_at_local":now/1000
    //         }

    // $querySegmentID = 'NULL';
    // $details = json_decode($_POST['details'],true);


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
    // }
}



function change_stage_state(callback){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", getCurrentStage, false);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.onreadystatechange = function() {
        // console.log(result);
        if (xhr.readyState == 4) {
            //console.log(xhr.responseText);
            callback(JSON.parse(xhr.responseText));
            }
    }
    xhr.send();
}


function notify_stage(data){
    console.log("STAGE CHANGE");
    console.log(data);
    stage_data = data;
    chrome.runtime.sendMessage({type: "stage_data",data:data}, function(response) {
            console.log(response)
            update_timer_background(data.timed);
    });
}
// TODO: Fix!
function saveAction(action,value,json,actionJSON,now){
    // if(actionJSON.tab){
    //     if(actionJSON.tab.url){
        console.log("SAVING ACTION");
            var data = {
                'action':action,
                'value':""+value,
                "project_id":project_id,
                "user_id":user_id,
                "json":JSON.stringify(json),
                "action_json":JSON.stringify(actionJSON),
                "created_at_local_ms":now,
                "created_at_local":now/1000
            }
            console.log(data.action_json);
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
            xhr.open("POST", saveActionUrl, true);
            xhr.setRequestHeader("Content-type", "application/json");
            xhr.onreadystatechange = function() {
                //console.log("ACTION");
                //console.log(result);
                if (xhr.readyState == 4) {
                    console.log("Action saved!");
                }
            }
            console.log(data);
            xhr.send(JSON.stringify(data));
            var result = xhr.responseText;
    //     }

    // }
}
// // TODO ACTIONS
// // tab change: onactivated+oned - use onActivated
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

function saveMoreTabInfo(tab) {
   if (!tab) { return; }
   else {
     chrome.tabs.getZoom(tab.tabId, function(zoomFactor){
       tab.zoomFactor = zoomFactor;
     });

     chrome.windows.get(tab.windowId, function (window){
       tab.windowInfo = window;
     });
     return tab;
   }
}

function saveTabActivated(activeInfo) {
  var now = new Date().getTime();
  if(true)
  {
    chrome.tabs.get(activeInfo.tabId,function(tab) {
      tab = saveMoreTabInfo(tab);
      saveAction("tabs.onActivated",activeInfo.tabId,tab,activeInfo,now);
      savePQ(tab.url,tab.title,tab.active,tab.id,tab.windowId,now,"tabs.onActivated",activeInfo);
    });
  }
}

function saveTabAttached(tabId, attachInfo) {
    if(true)
  {
    var now = new Date().getTime();;
    chrome.tabs.get(tabId,function(tab){
      tab = saveMoreTabInfo(tab);
      saveAction("tabs.onAttached",tabId,tab,attachInfo,now);
       });
  }
}

function saveTabCreated(tab) {
  if(true)
  {
    var now = new Date().getTime();;
    tab = saveMoreTabInfo(tab);
    saveAction("tabs.onCreated",tab.id,null,tab,now);
  }
}

function saveTabDetached(tabId, detachInfo) {
  if(true)
  {
    var now = new Date().getTime();;
    chrome.tabs.get(tabId,function(tab){
      tab = saveMoreTabInfo(tab);
      saveAction("tabs.onDetached",tabId,tab,detachInfo,now);
    });
  }
}

function saveTabHighlighted(highlightInfo){
  if(true){
    var now = new Date().getTime();;
    var tabs = [];
    for (i = 0; i < highlightInfo.tabIds.length; i++) {
      var id = highlightInfo.tabIds[i];
      chrome.tabs.get(id,function(tab){
        tab = saveMoreTabInfo(tab);
        tabs.push(tab);
      });
    }
    saveAction("tabs.onHighlighted",highlightInfo.tabIds.join(),tabs.join(),highlightInfo,now);
  }
}

function saveTabMoved(tabId, moveInfo) {
  if(true){
      var now = new Date().getTime();;
      chrome.tabs.get(tabId,function(tab){
        tab = saveMoreTabInfo(tab);
        ("tabs.onMoved",tabId,tab,moveInfo,now);
    });
  }
}

function saveTabRemoved(tabId, removeInfo) {
  if(true){
    var now = new Date().getTime();;
    saveAction("tabs.onRemoved",tabId,null,removeInfo,now);
  }
}

function saveTabReplaced(addedTabId, removedTabId) {
  if(true) {
    var now = new Date().getTime();;
    chrome.tabs.get(addedTabId,function(tab){
      tab = saveMoreTabInfo(tab);
    });
    saveAction("tabs.onReplaced",addedTabId,tab,{addedTabId:addedTabId,removedTabId:removedTabId,referrerInfo:result},now);
  }
}

function saveTabUpdated(tabId, changeInfo, tab) {
    if(true && changeInfo.status === 'complete'){
      var now = new Date().getTime();;
      tab = saveMoreTabInfo(tab);
      saveAction("tabs.onUpdated",tabId,tab,changeInfo,now);
      //savePQ(tab.url,tab.title,tab.active,tab.id,tab.windowId,now,"tabs.onUpdated",changeInfo);
    }
  }


function saveTabZoomed(zoomChangeInfo) {
  if(true){
    var now = new Date().getTime();;
    chrome.tabs.get(zoomChangeInfo.tabId,function(tab){
      tab = saveMoreTabInfo(tab);
      saveAction("tabs.onUpdated",tabId,tab,zoomChangeInfo,now);
    });
  }
}


function saveWindowCreated(window){
  if(true){
    var now = new Date().getTime();;
    saveAction("windows.onCreated",window.id,window,null,now);
  }
}

function saveWindowRemoved(windowId){
    if(true){
      var now = new Date().getTime();;
      saveAction("windows.onRemoved",windowId,null,null,now);
  }
}

function saveWindowFocusChanged(windowId){
    if(true){
      var now = new Date().getTime();;
      chrome.windows.get(windowId, function(window){
        saveAction("windows.onFocusChanged",windowId,window,null,now);
    });
  }
}




// TODO: retrieving pages and queries
var refreshContents = function(){
    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "project_id":project_id
        }

        xhr.open("GET", getBookmarksUrl, true);
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

    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "project_id":project_id
        }
        console.log("Page retrieve - params: " +JSON.stringify(params));

        xhr.open("GET", apidomain+"/pages?"+project_id, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Page ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                // create_notification("Pages retrieved!", "Pages");
                var result = JSON.parse(xhr.responseText);
                console.log("Pages retrieved!");
                chrome.runtime.sendMessage({type: "page_data",data:result}, function(response) {
                    console.log(response)
                });
            }
        }
        xhr.send(JSON.stringify(params));
    });

    var xhr = new XMLHttpRequest();
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "project_id":project_id
        }
        console.log("Query retrieve - params: " +JSON.stringify(params));

        xhr.open("GET", apidomain+"/queries?"+project_id, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Query ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                // create_notification("Queries retrieved!","Queries");
                var result = JSON.parse(xhr.responseText);
                console.log("Queries retrieved!");
                chrome.runtime.sendMessage({type: "query_data",data:result}, function(response) {
                    console.log(response)
                });
            }
        }
        xhr.send(JSON.stringify(params));
    });
}



// Done
var update_timer_background = function(timed){
        // console.log('update timer');
        // if(timed == 1){

        //     if(task_timer!=null){
        //         clearInterval(task_timer);
        //         task_timer = null;
        //     }

        //     if(task_timer==null){

        //         var countDownDate = Date.parse(stage_data.time_start.date + " " + stage_data.time_start.timezone);
        //         countDownDate = Math.round( countDownDate / 1000);
        //         countDownDate = countDownDate+stage_data.time_limit;

        //         task_timer = setInterval(function() {
        //             var now = new Date().getTime();.getTime();
        //             now = Math.round( now / 1000);

        //             // Find the distance between now an the count down date
        //             var distance = countDownDate - now;
        //             distance = distance * 1000;

        //             // Time calculations for days, hours, minutes and seconds
        //             var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        //             var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        //             // Output the result in an element with id="demo"


        //             if(minutes < 5){
        //                 chrome.browserAction.setBadgeBackgroundColor({color: "red"});
        //             }else{
        //                 chrome.browserAction.setBadgeBackgroundColor({color: "green"});
        //             }




        //             // document.getElementById("timer_text").innerHTML = minutes + "m " + seconds + "s ";

        //             if(minutes <= 0){
        //                 chrome.browserAction.setBadgeText({text:seconds+"s"});
        //             }else{
        //                 chrome.browserAction.setBadgeText({text:minutes+"m"});
        //             }
        //             // if(m > 0){
        //             //     chrome.browserAction.setBadgeText(minutes+"m");
        //             // }else if (s > 0){
        //             //     chrome.browserAction.setBadgeText(seconds+"s");
        //             // }

        //             // TODO: Uncomment
        //             // If the count down is over, write some text
        //             if (distance < 0) {
        //                 clearInterval(task_timer);
        //                 chrome.browserAction.setBadgeText({text:""});
        //                 // document.getElementById("timer_text").innerHTML = "EXPIRED";
        //                 chrome.tabs.create({url:gotoNextStage}, function(tab){},);
        //                 return;
        //             }
        //         }, 1000);
        //     }

        // }else{
        //     if(task_timer!=null){
        //         clearInterval(task_timer);
        //         task_timer=null
        //     }
        //     chrome.browserAction.setBadgeText({text:""});
        // }

    }


var saveWebNavigationCommitted = function(details){
    if(true){
        var now = new Date().getTime();;
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
                    saveAction("webNavigation.onCommitted",details.tabId,tab,details,now);
                    savePQ(Url,title,active,tabId,windowId,now,"webNavigation.onCommitted",details);

                }

            });
        }

        // TODO: Change condition
        if(true){
            var xhr = new XMLHttpRequest();

            xhr.open("GET", getProjectUrl, false);
            xhr.setRequestHeader("Content-type", "application/json");
            xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        var result = JSON.parse(xhr.responseText);
                        console.log("PROJECT ID");
                        console.log(xhr.responseText);
                        var project_id = result.project_id;
                        chrome.storage.local.set({project_id:project_id}, function() {});
                        // chrome.runtime.sendMessage({type: "update_projectid",data:{project_id:project_id}}, function(response) {
                        //     console.log(response)
                        // });

                    }
            }
            // TODO: fix
            //xhr.send(null);
            //change_stage_state(notify_stage);

        }
    }
}



// Done
chrome.contextMenus.onClicked.addListener(function(info, tab) {
    if (info.menuItemId == "snippet") {
        snip_text(info,tab)
    }

    if (info.menuItemId == "bookmark") {
        bookmark_page(info,tab)
    }
});

create_context_menu();
update_login_state();
chrome.windows.onCreated.addListener(update_login_state);
chrome.runtime.onStartup.addListener(update_login_state);
// // // Get URL, insert action as savePQ
chrome.tabs.onActivated.addListener(saveTabActivated);
chrome.tabs.onAttached.addListener(saveTabAttached);
// // IMPORTANT
// // // TODO: No PQ action here? see if another action accompanies a "open link in new tab" URL
chrome.tabs.onCreated.addListener(saveTabCreated);
// // TODO: No PQ action here? see if highlight also changes.
chrome.tabs.onDetached.addListener(saveTabDetached);
chrome.tabs.onHighlighted.addListener(saveTabHighlighted);
// // TODO: 2) When move action is executed on an inactive, is there any other action that fires? Such as onHighlighted or onActivated?
chrome.tabs.onMoved.addListener(saveTabMoved);
// // TODO: Other highlighted/activated actions when an active tab is closed?
chrome.tabs.onRemoved.addListener(saveTabRemoved);
// // IMPORTANT
chrome.tabs.onReplaced.addListener(saveTabReplaced);
// // Status types: either "loading" or "complete"
// // Note: only use onCommitted
// // TODO: Use only onCommitted?  Or this too?
chrome.tabs.onUpdated.addListener(saveTabUpdated);
// // TODO: Any tab IDs I should record here?
// // TODO: Any highlighted/change actions in addition that are typically fired?
chrome.windows.onCreated.addListener(saveWindowCreated);
// // TODO: Any tab IDs I should record here?
// // TODO: Any highlighted/change actions in addition that are typically fired?
chrome.windows.onRemoved.addListener(saveWindowRemoved);
// // TODO: get currently active tab ID
// // TODO: Any highlighted/change actions in addition that are typically fired?
// // TODO: Why is the windowID sometimes -1? Is that when focus is going away from Chrome?  Might be useful...
chrome.windows.onFocusChanged.addListener(saveWindowFocusChanged);
// // TODO: Multiple calls per page sometimes?
chrome.webNavigation.onCommitted.addListener(saveWebNavigationCommitted);
// // Note: can't use omnibox. Too limited for our purposes




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
