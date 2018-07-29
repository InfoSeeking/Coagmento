$(document).ready(function() {

    // TODO
    // 1) Display query text for query segment questionnaire
    // 2) logout: Better result for sidebar
    // 3) handle task1, task2, 2 different projects

    // Background variables
    var background = chrome.extension.getBackgroundPage();

    // URLs
    var homeDir = background.domain;
    var logoutUrl = background.logoutUrl;
    var loggedInHomeUrl = background.loggedInHomeUrl;
    var etherpadUrl = background.etherpadUrl;

    
    // Done
    function goHome(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }


    // Done
    function gotoEtherpad(){
        chrome.tabs.create({url:etherpadUrl+'/user'+background.user_id+'_project'+background.project_id}, function(tab){},);
    }

    // TODO
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


    // Done
    function logout_state_popup(){
        chrome.storage.local.remove(['user_id','name','project_id','email','password'], function() {
            background.user_id = null;
            background.project_id = null;
            background.name = null;
            background.email = null;
            background.password = null;
            background.logged_in_extension = false;
            background.stage_data = false;
            chrome.browserAction.setPopup({
                popup:"login.html"
            });
            background.hide_context_menu();
        });
    }

    // Done
    function logout_popup(){
        var xhr = new XMLHttpRequest();
        xhr.open("GET", logoutUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        var data = {}

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("LOGOUT");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                if(!result.logged_in){
                    logout_state_popup();
                    window.location.href='login.html';    
                }
            }
        }
        xhr.send(JSON.stringify(data));
    }


    // Done
    function logout_click(){
        logout_popup();
    }
    
    




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

    // $('#queries_table').bootstrapTable({
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
    //     price: '$1'
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


    // $('#pages_table').bootstrapTable({
    //     columns: [{
    //     field: 'title',
    //     title: 'Title'
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


    // Done
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
        
        // console.log("bookmark_data");
        // console.log(bookmark_data)
        $('#bookmarks_table').bootstrapTable(bookmark_data);
        $('button[name="delete_bookmarks_button"]').click(function(){
            var bookmark_id = $(this).data('bookmark-id');
            deleteBookmark(bookmark_id);
        })
    }



    var render_pages = function(data){
        var page_data = {};
        page_data.columns = [
        {
        field: 'time',
        title: 'Time'
        },
        {
        field: 'title',
        title: 'Title'
        }
        ]
        page_data.data = [];
        // console.log("DATA");
        // console.log(data);

        if(data.length==0){
            $("#pages_no").show();
            $("#pages_yes").hide();

        }else{
            $("#pages_no").hide();
            $("#pages_yes").show();

            $.each(data.result, function( index, value ) {
                page_data.data.push(
                    {
                        time:value.created_at,
                        title:value.title,
                    }
                );
            });
        }
        
        
        $('#pages_table').bootstrapTable(page_data);
    }




    var render_queries = function(data){
        var query_data = {};
        query_data.columns = [
        {
        field: 'time',
        title: 'Time'
        },
        {
        field: 'query',
        title: 'Query'
        }, 
        {
        field: 'source',
        title: 'Source'
        }, 
        
        ]
        query_data.data = [];
        // console.log("DATA");
        // console.log(data);

        if(data.length==0){
            $("#queries_no").show();
            $("#queries_yes").hide();

        }else{
            $("#queries_no").hide();
            $("#queries_yes").show();

            $.each(data.result, function( index, value ) {
                query_data.data.push(
                    {
                        time:value.created_at,
                        query:value.query,
                        source:value.source,
                    }
                );
            });
        }
        
        // console.log("bookmark_data");
        // console.log(bookmark_data)
        $('#queries_table').bootstrapTable(query_data);
    }



    // Done
    var deleteBookmark = function(id){
        var xhr = new XMLHttpRequest();
        var url = background.getBookmarksUrl + "/" + id;
        var params = {}
        xhr.open("DELETE", url, true);
        // xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("Delete Bookmark");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                refresh_bookmarks_popup();
            }
        }
        xhr.send(null);
    }


    // Done
    var update_timer_popup = function(timed){
        if(timed == 1){
            if(background.task_timer!=null){
                clearInterval(background.task_timer);
                background.task_timer = null;
            }

            if(background.task_timer==null){
                // console.log("START TIME");
                // console.log(background.stage_data);
                // console.log(background.stage_data.time_start.date);
                // console.log("TIME LIMIT");
                // console.log(background.stage_data.time_limit)
                // console.log("CURRENT TIME");
                // console.log(new Date().getTime());

                // console.log(background.stage_data.time_start.date + " " + background.stage_data.time_start.timezone);
                var countDownDate = Date.parse(background.stage_data.time_start.date + " " + background.stage_data.time_start.timezone);
                countDownDate = Math.round( countDownDate / 1000);
                countDownDate = countDownDate+background.stage_data.time_limit;
                // console.log("COUNTDOWN TIME");
                // console.log(countDownDate);

                

                background.task_timer = setInterval(function() {
                    var now = new Date().getTime();
                    now = Math.round( now / 1000);
                    // console.log("NOW");
                    // console.log(now);
                    // console.log("COUNTDOWNDATE");
                    // console.log(countDownDate);
                    
                    // Find the distance between now an the count down date
                    var distance = countDownDate - now;
                    distance = distance * 1000;
                    
                    // Time calculations for days, hours, minutes and seconds
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    // Output the result in an element with id="demo"
                    

                    // console.log("BADGE COLOR")
                    if(minutes < 5){
                        chrome.browserAction.setBadgeBackgroundColor({color: "red"});    
                    }else{
                        chrome.browserAction.setBadgeBackgroundColor({color: "green"});    
                    }


                    // TODO: Uncomment
                    // If the count down is over, write some text 
                    if (distance < 0) {
                        clearInterval(background.task_timer);
                        chrome.browserAction.setBadgeText({text:""});
                        document.getElementById("timer_text").innerHTML = "EXPIRED";
                        chrome.tabs.create({url:background.gotoNextStage}, function(tab){},);
                        return;
                    }

                    document.getElementById("timer_text").innerHTML = minutes + "m " + seconds + "s ";

                    
                    // console.log("BADGE TEXT")
                    if(minutes <= 0){
                        chrome.browserAction.setBadgeText({text:seconds+"s"});
                    }else{
                        chrome.browserAction.setBadgeText({text:minutes+"m"});
                    }
                    

                    
                    
                    
                }, 1000);
            }
            
        }else{
            if(background.task_timer!=null){
                clearInterval(background.task_timer);
                background.task_timer=null;
            }
            chrome.browserAction.setBadgeText({text:""});
        }
        
    }


    // Done
    var update_stage_state = function(new_stage_id,new_timed,new_time_limit,new_start_time,new_time_zone){
        // console.log("UPDATE STAGE STATE");
        // console.log(new_stage_id);
        // console.log(new_timed);
        // console.log(new_time_limit);
        // console.log(new_start_time);
        // console.log(new_time_zone);

        if(new_stage_id == null){
            $('#task_display').hide();
            $('#default_display').show();
            return;
        }

        background.stage_data.stage_id = new_stage_id;
        background.stage_data.timed = new_timed;
        background.stage_data.time_limit = new_time_limit;
        background.stage_data.time_start.date = new_start_time;
        background.stage_data.time_start.timezone = new_time_zone;

        if(new_timed){
            $('#task_display').show();
            $('#default_display').hide();
        }else{
            $('#task_display').hide();
            $('#default_display').show();
        }
        
        update_timer_popup(background.stage_data.timed);
    }

    





    // Done
    var refresh_bookmarks_popup = function(){
        var xhr = new XMLHttpRequest();
        chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        
        // console.log("refresh_bookmarks_popup URL");
        console.log(background.apidomain+"/projects/"+background.project_id+"/bookmarks");
        xhr.open("GET", background.apidomain+"/projects/"+background.project_id+"/bookmarks", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("refresh_bookmarks_popup response");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                render_bookmarks(result);
            }
        }
        xhr.send();
        });

    }



    var refresh_pages_popup = function(){
        var xhr = new XMLHttpRequest();
        chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        
        // console.log("refresh_bookmarks_popup URL");
        console.log(background.apidomain+"/pages?"+background.project_id);
        xhr.open("GET", background.apidomain+"/pages?"+background.project_id, true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("refresh_pages_popup response");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                render_pages(result);
            }
        }
        xhr.send();
        });

    }


    var refresh_queries_popup = function(){
        var xhr = new XMLHttpRequest();
        chrome.tabs.query({active: true, currentWindow: true}, function(tabs){
        
        // console.log("refresh_bookmarks_popup URL");
        console.log(background.apidomain+"/queries?"+background.project_id);
        xhr.open("GET", background.apidomain+"/queries?"+background.project_id, true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("refresh_queries_popup response");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                render_queries(result);
            }
        }
        xhr.send();
        });

    }





    // Done
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
           data: $("#query_segment_form").serialize()+"&user_id="+background.user_id, // serializes the form's elements.
           success: function(data)
           {
               console.log(data); // show response from the php script.
               if(data.success){
                    $("#query_segment_form")[0].reset();
                    $("#questionnaire_container").hide();
                    $("#query_questionnaire_success").show().fadeOut(2000);
                    background.current_querysegmentid_submitted=true;
               }
               
           },
           error: function(data)
           {
                $("#query_questionnaire_error").show().fadeOut(2000);
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


    // TODO
    chrome.runtime.onMessage.addListener(
        function(request, sender, sendResponse) {
            console.log("MESSAGE REQUEST TYPE: "+request.type);
            console.log("MESSAGE REQUEST DATA: "+request.data);
            console.log(request.data);
            if (request.type == "bookmark_data") {
                render_bookmarks(request.data);
                sendResponse("Bookmarks table saved");
            } 
            // else if (request.type == 'update_projectid'){
            //     project_id = request.data.project_id;
            //     sendResponse("Updated project ID");
            // }
            else if(request.type == 'stage_data'){
                // stage_id, whether it is timed, the start time for the user, and the time limit
                // update_stage_state(request.data.stage_id,request.data.timed,request.data.time_limit,
                //  request.data.time_start.date,request.data.time_start.timezone)
                update_stage_state(request.data.stage_id,request.data.timed,request.data.time_limit,
                    request.data.time_start.date,request.data.time_start.timezone);
                sendResponse("Updated stage ID");
            }
            else if(request.type == 'new_querysegment'){
                // $('#query_id').val(request.data.old_id);
                $('#query_id').val(background.current_querysegmentid);
                $('#query').html(request.data.query);
                $('#questionnaire_container').show();
                sendResponse("Updated Query Segment");
            }
            else if(request.type == 'new_page'){
                // stage_id, whether it is timed, the start time for the user, and the time limit
                refresh_pages_popup();
                refresh_queries_popup();
                sendResponse("Pages table updated");
            }
            else {
                sendResponse("Not a valid command");
            }
            // Note: Returning true is required here!
            //  ref: http://stackoverflow.com/questions/20077487/chrome-extension-message-passing-response-not-sent
            return true; 
    });

    // TODO
    chrome.storage.local.get(['project_id','user_id','name','email','password'], function(result) { 
        console.log("RESULT");
        console.log(result);
        background.user_id = result.user_id;
        background.project_id = result.project_id;
        background.name = result.name;
        background.email = result.email;
        background.password = result.password;
        background.logged_in_extension = true;
        $('#name').text(result.name);

    });

    // TODO
    if(background.logged_in_extension != background.logged_in_browser){
        background.update_login_state();
    }

    if(background.current_querysegmentid!=null && !background.current_querysegmentid_submitted){
        $('#questionnaire_container').show();
        $('#query_id').val(background.current_querysegmentid);
        $('#query').html(background.current_query);
    }else{
        $('#questionnaire_container').hide();
        $('#query_id').val(null);
        $('#query').html('');
    }
    refresh_bookmarks_popup();
    refresh_pages_popup();
    refresh_queries_popup();
    background.update_login_state();
    

    // function change_stage_state(callback){
    //     var xhr = new XMLHttpRequest();
    //     xhr.open("GET", getCurrentStage, false);
    //     xhr.setRequestHeader("Content-type", "application/json");
    //     xhr.onreadystatechange = function() {
    //         // console.log(result);
    //         if (xhr.readyState == 4) {
    //             console.log(xhr.responseText);
    //             callback(JSON.parse(xhr.responseText));
    //             }
    //     }
    //     xhr.send();
    // }


    // function notify_stage(data){
    //     console.log("STAGE CHANGE");
    //     console.log(data);

    //     chrome.runtime.sendMessage({type: "stage_data",data:data}, function(response) {
    //         stage_data = data;
    //             console.log(response)
    //             update_timer_background(data.timed);
    //     });
    // }


    // Done
    $( "#opencspace_button" ).click(function() {
        goHome();
    });

    $( "#opencspace_button_loggedout" ).click(function() {
        goHome();
    });


    // Done
    $( "#etherpad_button" ).click(function() {
        gotoEtherpad();
    });

    // Done
    $( "#logout_button" ).click(function() {
        logout_click();
    });


    if(background.stage_data != null){
        console.log("NOT NULL");
        update_stage_state(background.stage_data.stage_id,background.stage_data.timed,background.stage_data.time_limit,
            background.stage_data.time_start.date,background.stage_data.time_start.timezone);
    }else{
        var xhr = new XMLHttpRequest();
        xhr.open("GET", background.getCurrentStage, false);
        xhr.setRequestHeader("Content-type", "application/json");
        console.log("GET CURRENT STAGE");
        xhr.onreadystatechange = function() {
            // console.log(result);
            if (xhr.readyState == 4) {


                console.log("GOT CURRENT STAGE");
                console.log(xhr.responseText);
                background.stage_data = JSON.parse(xhr.responseText);
                update_stage_state(background.stage_data.stage_id,background.stage_data.timed,background.stage_data.time_limit,
                    background.stage_data.time_start.date,background.stage_data.time_start.timezone);
                // callback(JSON.parse(xhr.responseText));
                }
        }
        xhr.send();
        // update_stage_state(null,null,null,null,null);
    }
    
    

});
