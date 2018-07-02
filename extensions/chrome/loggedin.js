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
    var stage_id;
    var timed;
    var time_limit;
    var start_time;
    var time_zone;

    // URLs
    var homeDir = background.domain;
    var logoutUrl = background.logoutUrl;
    var loggedInHomeUrl = background.loggedInHomeUrl;
    var etherpadUrl = background.etherpadUrl;

    // TODO
    // 1) Add timer
    

    if(background.logged_in_extension != background.logged_in_browser){
        background.update_login_state();
    }else{
        console.log(background.logged_in_extension);
        console.log(background.logged_in_browser);
    }
    
    function goHome(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }


    // TODO: Insert Etherpad URL
    function gotoEtherpad(){
        chrome.tabs.create({url:etherpadUrl+'/user_'+user_id+'_project'+project_id}, function(tab){},);
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
        // console.log("DATA");
        // console.log(data);

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


    var update_timer_popup = function(timed){
        console.log('update timer popup');
        if(timed == 1){

            if(background.task_timer!=null){
                clearInterval(background.task_timer);
                background.task_timer = null;
            }

            if(background.task_timer==null){
                console.log("START TIME");
                console.log(start_time);
                console.log("TIME LIMIT");
                console.log(time_limit)
                console.log("CURRENT TIME");
                console.log(new Date().getTime());

                var countDownDate = Date.parse(start_time + " " + time_zone);

                console.log("COUNTDOWN TIME");
                console.log(countDownDate);

                countDownDate = Math.round( countDownDate / 1000)
                
                var countDownDate = countDownDate+time_limit;

                background.task_timer = setInterval(function() {
                var now = new Date().getTime();
                
                // Find the distance between now an the count down date
                var distance = countDownDate - now;
                
                // Time calculations for days, hours, minutes and seconds
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Output the result in an element with id="demo"
                document.getElementById("timer_text").innerHTML = minutes + "m " + seconds + "s ";

                chrome.browserAction.setBadgeBackgroundColor({color: "red"});
                console.log("BADGE COLOR")
                chrome.browserAction.setBadgeText({text:minutes+"m"});
                console.log("BADGE TEXT")
                // if(m > 0){
                //     chrome.browserAction.setBadgeText(minutes+"m");
                // }else if (s > 0){
                //     chrome.browserAction.setBadgeText(seconds+"s");
                // }
                
                // If the count down is over, write some text 
                if (false) {
                // if (distance < 0) {
                    clearInterval(background.task_timer);
                    chrome.browserAction.setBadgeText({text:""});
                    document.getElementById("timer_text").innerHTML = "EXPIRED";
                    chrome.tabs.create({url:background.gotoNextStage}, function(tab){},);
                }
                }, 1000);
            }
            
        }else{
            if(background.task_timer!=null){
                clearInterval(background.task_timer);
                background.task_timer=null
            }
            chrome.browserAction.setBadgeText({text:""});
        }
        
    }


    var update_stage_state = function(new_stage_id,new_timed,new_time_limit,new_start_time,new_time_zone){
        console.log("UPDATE STAGE STATE");
        console.log(new_timed);

        if(new_stage_id == null){
            $('#task_display').hide();
            $('#default_display').show();
            return;
        }
        stage_id = new_stage_id
        timed = new_timed
        time_limit = new_time_limit
        start_time = new_start_time
        time_zone = new_time_zone
        if(new_timed){
            console.log("SHOW TASK DISPLAY");
            $('#task_display').show();
            $('#default_display').hide();
        }else{
            console.log("SHOW DEFAULT DISPLAY");
            $('#task_display').hide();
            $('#default_display').show();
        }
        // background.updsate_timer_background(timed);
        update_timer_popup(timed);
        // stage_id = request.data.id;
        //         timed = request.data.timed;
        //         time_limit = request.data.time_limit;
        //         start_time = request.data.time_start.date;
        //         time_zone = request.data.time_start.timezone;
        

    }

    chrome.runtime.onMessage.addListener(
        function(request, sender, sendResponse) {
            console.log("MESSAGE REQUEST TYPE: "+request.type);
            console.log("MESSAGE REQUEST DATA: "+request.data);
            console.log(request.data);
            if (request.type == "bookmark_data") {
                render_bookmarks(request.data);
                sendResponse("Bookmarks table saved");
            } else if (request.type == 'update_projectid'){
                project_id = request.data.project_id;
            }else if(request.type == 'stage_data'){
                // stage_id, whether it is timed, the start time for the user, and the time limit
                update_stage_state(request.data.stage_id,request.data.timed,request.data.time_limit,request.data.time_start.date,request.data.time_start.timezone)
                
                


            }
            else {
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
            "project_id":project_id
        }
        
        console.log("PARAMS");
        console.log(params);
        xhr.open("GET", background.getBookmarksUrl+"?"+$.param(params), false);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            console.log("Bookmark ready state:"+xhr.readyState);
            if (xhr.readyState == 4) {
                var result = JSON.parse(xhr.responseText);
                console.log("RESPONSE");
                console.log(result)
                render_bookmarks(result);
            }
        }
        xhr.send();
    });

    }

    $('#query_submit').click(function(){
        event.preventDefault();
        // var xhr = new XMLHttpRequest();
        
        // var formData = new FormData($('#query_segment_form'));
        // formData.append('user_id',user_id);
        // var params = $('#query_segment_form').serializeArray();
        // params['user_id']=user_id;


        $.ajax({
           type: "POST",
           url: background.querySegmentQuestionnaireUrl,
           data: $("#query_segment_form").serialize()+"&user_id="+user_id, // serializes the form's elements.
           success: function(data)
           {
               console.log(data); // show response from the php script.
               if(data.success){
                    $("#query_segment_form")[0].reset();
               }
               
           },
           error: function(data)
           {
               console.log(data.responseText); // show response from the php script.
           }

         });

        
        // console.log("QUERY SEGMENT PARAMS");
        // for (var [key, value] of formData.entries()) { 
        // console.log(key, value);
        // }
        // xhr.open("POST", background.querySegmentQuestionnaireUrl, true);
        // // xhr.setRequestHeader("Content-type", "application/json");
        // xhr.onreadystatechange = function() {
        //     console.log("Bookmark ready state:"+xhr.readyState);
        //     if (xhr.readyState == 4) {
        //         console.log("QUERY SEGMENT RESPONSE");
        //         console.log(xhr.responseText)
        //         render_bookmarks(result);
        //     }
        // }
        // xhr.send(formData);
    
    });



    refresh_bookmarks_popup();
    background.update_login_state();

    if(background.stage_data != null){
        stage_id = background.stage_data.id;
        timed = background.stage_data.timed;
        time_limit = background.stage_data.time_limit;
        start_time = background.stage_data.time_start.date;
        time_zone = background.stage_data.time_start.timezone;    
    }
    

    update_stage_state(stage_id,timed,time_limit,start_time,time_zone);
    

});
