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
    

    
    function goHome(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }


    // function gotoEtherpad(){
    //     chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    // }

    if(background.task_timer!=null){
        clearInterval(background.task_timer);
    }
    var countDownDate = new Date().getTime()+1000 * 60*20;
    background.task_timer = setInterval(function() {
        var now = new Date().getTime();
        
        // Find the distance between now an the count down date
        var distance = countDownDate - now;
        
        // Time calculations for days, hours, minutes and seconds
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Output the result in an element with id="demo"
        document.getElementById("timer_text").innerHTML = minutes + "m " + seconds + "s ";
        
        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer_text").innerHTML = "EXPIRED";
        }
    }, 1000);


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
        $('#name').text(name);
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


});
