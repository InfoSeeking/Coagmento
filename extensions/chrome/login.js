$(document).ready(function(){    
    // URLs
    var background = chrome.extension.getBackgroundPage();
    var homeDir = background.domain;
    var loginUrl = background.loginUrl;

    // Inputs
    var usernameInputID = '#username';
    var passwordInputID = '#password';
    
    // TODO Code.  Plus:
    // 1) Checked logged in URL
    // 2) set logged in background variable
    // TODO: Properly update project ID


    function login_state_popup(uid,pid,username,useremail,pwd){
        chrome.storage.local.set({user_id: uid, project_id:pid,name:username,email:useremail,password:pwd}, function() {});
        background.user_id = uid;
        background.project_id = pid;
        background.name = username;
        background.email = useremail;
        background.password = pwd;
        background.logged_in = true;

        chrome.browserAction.setPopup({
            popup:"loggedin.html"
        });
        background.show_context_menu();
    }
    

    function login_popup(email,password){
        var xhr = new XMLHttpRequest();
        xhr.open("POST", loginUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        var data = {"email":email,"password":password}

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);

                if(result.logged_in){
                    login_state_popup(result.id,result.project_id,result.name,email,password);
                    window.location.href='loggedin.html';
                }
            }
        }
        xhr.send(JSON.stringify(data));
    }

    function login_click(){
        event.preventDefault();
        login_popup($(usernameInputID).val(),$(passwordInputID).val());
    }

    // TODO: set logged in background variable
    $( "#login_button" ).click(login_click);
});
