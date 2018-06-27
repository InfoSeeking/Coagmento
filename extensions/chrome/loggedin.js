$(document).ready(function() {

    // TODO
    // 1) gotoEtherpad
    // 2) logout: Better result for sidebar
    // 3) handle task1, task2, 2 different projects

    // Background variables
    var background = chrome.extension.getBackgroundPage();
    var email;
    var password;
    var user_id;
    var project_id;
    var user_name;

    // URLs
    var homeDir = background.domain;
    var logoutUrl = background.logoutUrl;
    var loggedInHomeUrl = background.loggedInHomeUrl;

    // TODO
    // 1) Add timer
    

    
    function goHome(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }


    // TODO: Insert Etherpad URL
    function gotoEtherpad(){
        // chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }

    // if(background.task_timer!=null){
    //     clearInterval(background.task_timer);
    // }
    // var countDownDate = new Date().getTime()+1000 * 60*20;

    // background.task_timer = setInterval(function() {
    //     var now = new Date().getTime();
        
    //     // Find the distance between now an the count down date
    //     var distance = countDownDate - now;
        
    //     // Time calculations for days, hours, minutes and seconds
    //     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    //     var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
    //     // Output the result in an element with id="demo"
    //     document.getElementById("timer_text").innerHTML = minutes + "m " + seconds + "s ";
        
    //     // If the count down is over, write some text 
    //     if (distance < 0) {
    //         clearInterval(x);
    //         document.getElementById("timer_text").innerHTML = "EXPIRED";
    //     }
    // }, 1000);


    function logout_state_popup(){
        chrome.storage.local.remove(['user_id','name','project_id','email','password'], function() {
            background.user_id = null;
            background.project_id = null;
            background.name = null;
            background.email = null;
            background.password = null;
            background.logged_in = false;
            chrome.browserAction.setPopup({
                popup:"login.html"
            });
            background.hide_context_menu();
        });
    }

    function logout_popup(){
        var xhr = new XMLHttpRequest();
        xhr.open("GET", logoutUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        var data = {}

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                var result = JSON.parse(xhr.responseText);
                if(!result.logged_in){
                    logout_state_popup();
                    window.location.href='login.html';    
                }
            }
        }
        xhr.send(JSON.stringify(data));
    }


    function logout_click(){
        logout_popup();
    }

    chrome.storage.local.get(['project_id','user_id','name','email','password'], function(result) { 
        user_id = result.user_id;
        project_id = result.project_id;
        name = result.name;
        email = result.email;
        password = result.password;
        background.user_id = user_id;
        background.project_id = project_id;
        background.name = name;
        background.email = email;
        background.password = password;
        background.logged_in = true;
        $('#name').text(user_id);
    });

    

    $( "#opencspace_button" ).click(function() {
        goHome();
    });


    $( "#etherpad_button" ).click(function() {
        gotoEtherpad();
    });


	$( "#logout_button" ).click(function() {
        logout_click();
    });




    // $('#bookmarks_table').bootstrapTable({
    //     columns: [{
    //     field: 'id',
    //     title: 'Item ID'
    //     }, {
    //     field: 'name',
    //     title: 'Item Name'
    //     }, {
    //     field: 'price',
    //     title: 'Item Price'
    //     }],
    //     data: [{
    //     id: 1,
    //     name: 'Item 1',
    //     price: '<button class="btn btn-primary">Hello</button>'
    //     }, {
    //     id: 2,
    //     name: 'Item 2',
    //     price: '$2'
    //     },
    //     {
    //     id: 2,
    //     name: 'Item 2',
    //     price: '$2'
    //     },
    //     {
    //     id: 3,
    //     name: 'Item 3',
    //     price: '$2'
    //     },
    //     {
    //     id: 4,
    //     name: 'Item 4',
    //     price: '$2'
    //     },
    //     {
    //     id: 5,
    //     name: 'Item 2',
    //     price: '$2'
    //     },
    //     ]
    // });

    $('#queries_table').bootstrapTable({
        columns: [{
        field: 'id',
        title: 'Item ID'
        }, {
        field: 'name',
        title: 'Item Name'
        }, {
        field: 'price',
        title: 'Item Price'
        }],
        data: [{
        id: 1,
        name: 'Item 1',
        price: '$1'
        }, {
        id: 2,
        name: 'Item 2',
        price: '$2'
        },
        {
        id: 2,
        name: 'Item 2',
        price: '$2'
        },
        {
        id: 3,
        name: 'Item 3',
        price: '$2'
        },
        {
        id: 4,
        name: 'Item 4',
        price: '$2'
        },
        {
        id: 5,
        name: 'Item 2',
        price: '$2'
        },
        ]
    });


    $('#pages_table').bootstrapTable({
        columns: [{
        field: 'title',
        title: 'Title'
        }, {
        field: 'name',
        title: 'Item Name'
        }, {
        field: 'price',
        title: 'Item Price'
        }],
        data: [{
        id: 1,
        name: 'Item 1',
        price: '<button class="btn btn-primary">Hello</button>'
        }, {
        id: 2,
        name: 'Item 2',
        price: '$2'
        },
        {
        id: 2,
        name: 'Item 2',
        price: '$2'
        },
        {
        id: 3,
        name: 'Item 3',
        price: '$2'
        },
        {
        id: 4,
        name: 'Item 4',
        price: '$2'
        },
        {
        id: 5,
        name: 'Item 2',
        price: '$2'
        },
        ]
    });


    var render_bookmarks = function(data){
        var bookmark_data = {};
        bookmark_data.columns = [
        {
        field: 'time',
        title: 'Time'
        },
        {
        field: 'title',
        title: 'Title'
        }, 
        {
        field: 'unsave',
        title: 'Unsave?'
        }
        ]
        bookmark_data.data = [];
        console.log("DATA");
        console.log(data);

        if(data.length==0){
            $("#bookmarks_no").show();
            $("#bookmarks_yes").hide();

        }else{
            $("#bookmarks_no").hide();
            $("#bookmarks_yes").show();

            $.each(data.result, function( index, value ) {
                bookmark_data.data.push(
                    {
                        time:value.created_at,
                        title:value.title,
                        unsave:'<button name="delete_bookmarks_button" data-bookmark-id='+value.id+'>Delete</button>'
                    }
                );
            });
        }
        
        console.log("bookmark_data");
        console.log(bookmark_data)
        $('#bookmarks_table').bootstrapTable(bookmark_data);
        $('button[name="delete_bookmarks_button"]').click(function(){
            var bookmark_id = $(this).data('bookmark-id');
            deleteBookmark(bookmark_id);
        })
    }


    var deleteBookmark = function(id){
        var xhr = new XMLHttpRequest();
        var url = background.getBookmarksUrl + "/" + id;
        var params = {}
        xhr.open("DELETE", url, false);
        // xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                var result = JSON.parse(xhr.responseText);
                refresh_bookmarks_popup();
            }
        }
        xhr.send(null);


    }

    chrome.runtime.onMessage.addListener(
        function(request, sender, sendResponse) {
            if (request.type == "bookmark_data") {
                render_bookmarks(request.data);
                sendResponse("Bookmarks table saved");
            } else {
                sendResponse("Not a valid command");
            }
            // Note: Returning true is required here!
            //  ref: http://stackoverflow.com/questions/20077487/chrome-extension-message-passing-response-not-sent
            return true; 
    });





    var refresh_bookmarks_popup = function(){
        var xhr = new XMLHttpRequest();
        chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        var params = {
            "project_id":0
        }
        
        xhr.open("GET", background.getBookmarksUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Bookmark ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                var result = JSON.parse(xhr.responseText);
                render_bookmarks(result);
            }
        }
        xhr.send(JSON.stringify(params));
    });

    }

    refresh_bookmarks_popup();
    

});
