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



    

    // Login: 1) set background variables. 2) set popup 3) prepare context menu
    function login_state_popup(uid,pid,username,useremail,pwd,stage_data){
        chrome.storage.local.set({user_id: uid,project_id:pid,name:username,email:useremail,password:pwd}, function() {
            background.user_id = uid;
            background.project_id = pid;
            background.name = username;
            background.email = useremail;
            background.password = pwd;
            background.logged_in_extension = true;
            background.stage_data = stage_data;
            chrome.browserAction.setPopup({
                popup:"loggedin.html"
            });
            background.show_context_menu();
        });
        
    }
    
    // 1) Verify login 2) Set login state
    function login_popup(email,password){
        console.log(email);
        console.log(password);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", loginUrl, false);
        xhr.setRequestHeader("Content-type", "application/json");
        var data = {"email":email,"password":password}

        console.log("LOGINURL");
        console.log(loginUrl);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                console.log("LOGGEDIN");
                console.log(xhr.responseText);
                var result = JSON.parse(xhr.responseText);
                if(result.logged_in){
                    login_state_popup(result.id,result.project_id,result.name,email,password,result.stage_data);
                    window.location.href='loggedin.html';
                    $('#login_error_text').html("");
                    $('#login_error_text').hide();
                }else{
                    $('#login_error_text').html("Something was wrong with your credentials.  Please check them and try again.");
                    $('#login_error_text').show();
                }
            }else{
                $('#login_error_text').html("Something was wrong with the server.  Please contact the study facilitators or try again later.");
                $('#login_error_text').hide();
            }
        }
        xhr.send(JSON.stringify(data));
    }

    // Click
    function login_click(){
        event.preventDefault();
        login_popup($(usernameInputID).val(),$(passwordInputID).val());
    }

    
    $( "#login_button" ).click(login_click);

    // TODO: set logged in background variable.  Is this correct?
    if(background.logged_in_extension != background.logged_in_browser){
        background.update_login_state();
    }
});
