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


    function gotoEtherpad(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }



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
