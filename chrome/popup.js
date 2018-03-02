$(document).ready(function(){

    
    var signedinYesID = '#signedin_yes';
    var signedinNoID = '#signedin_no';
    var firstNameID = '#first_name';
    var lastNameID = '#last_name';
    var loginErrorTextID = '#login_error_text';
    var usernameInputID = '#username';
    var passwordInputID = '#password';
    var homeDir = "http://localhost:8000/auth";
    
    // URLs
    var registerUrl = homeDir+"/register";
    var checkLoggedInUrl = homeDir + "/getLoggedIn.php";
    var loginUrl = homeDir + "/login";
    var logoutUrl = homeDir + "/logout";
    var sendCredentialsUrl = homeDir + "/sendCredentials.php";
    var homeUrl = homeDir + "/instruments/getHome.php";
    var contactUrl = "mailto:mmitsui@scarletmail.rutgers.edu?Subject=Intent%20Study%20Inquiry";


    function goHome(){
        chrome.tabs.create({url:homeUrl}, function(tab){
        },
        );
    }

    function sendContactEmail(){
        chrome.tabs.create({url:contactUrl}, function(tab){
            setTimeout(function(){
                chrome.tabs.remove(tab.id);
            },500);
        },
        );
    }

    function goToRegistration(){
        chrome.tabs.create({url:registerUrl}, function(tab){
        },
        );
    }


    function toggleLoggedIn(logged){
        chrome.extension.getBackgroundPage().loggedIn = logged;
    }

    function renderLoggedIn(loggedIn){
    	var red = [255,0,0,255];
    	// var green = [0,255,0,255];
    	var green = [34,139,34,255];
    	if(loggedIn){
    		chrome.browserAction.setBadgeText({text:' '});
			chrome.browserAction.setBadgeBackgroundColor({color:green});
    	}else{
    		chrome.browserAction.setBadgeText({text:' '});
			chrome.browserAction.setBadgeBackgroundColor({color:red});
    	}
    }

    // TODO: Is AJAX call here okay? set logged in background variable
    function handleLoggedIn(msg){
	/**
        msg = JSON.parse(msg);
        if(msg.loggedin){
            toggleLoggedIn(msg.loggedin)
            renderLoggedIn(msg.loggedin);
            $(signedinYesID).show();
            $(signedinNoID).hide();
            $(firstNameID).text(msg.firstName);
            $(lastNameID).text(msg.lastName);
        }else{
            $(signedinYesID).show();
            $(signedinNoID).hide();
            

            $.ajax({
            type: "POST",
            url: loginUrl,
            data:{email:$(usernameInputID).val(),password:$(passwordInputID).val(),browser:"chrome",extensionID:chrome.runtime.id},
            success: function(msg){
                console.log(msg)
            },
            error: function(msg){
                toggleLoggedIn(true);
                renderLoggedIn(true);
            },
            });
            
        }
    }*/
	}

    // TODO: set logged in background variable
   /* $.ajax({
        type: "POST",
        url: checkLoggedInUrl,
        data : {extensionID:chrome.runtime.id},
        dataType: "text",
        success: handleLoggedIn,
        error: function(msg){
            $(signedinNoID).show();
            $(signedinYesID).hide();
            toggleLoggedIn(true);
            renderLoggedIn(true);
        }
    });  **/

    // TODO: set logged in background variable
    $( "#login_button" ).click(function() {

			var xhr = new XMLHttpRequest();
			xhr.open("POST", loginUrl, false);
			xhr.setRequestHeader("Content-type", "application/json");
			var data2 = {"email":$(usernameInputID).val(),"password":$(passwordInputID).val()}
			xhr.send(JSON.stringify(data2));
			var result = xhr.responseText;
			if(result){
					console.log("TRUE")
					toggleLoggedIn(true)
					renderLoggedIn(true)
					 chrome.browserAction.setPopup({
       							popup:"loggedIn.html"
   						 });
				}
		

    });


    // TODO: set logged in background variable
    $( "#logout_button" ).click(function() {
        $.ajax({
            type: "POST",
            url: logoutUrl,
            data:{browser:"chrome"},
            success: function(msg){
                msg = JSON.parse(msg);
                if(msg.success){
                    $(usernameInputID).val('');
                    $(passwordInputID).val('');
                    $(signedinNoID).show();
                    $(signedinYesID).hide();
                    toggleLoggedIn(false);
                    renderLoggedIn(false);
                }

            },
        });
    });


    $( "#credentials_button" ).click(function() {
        $.ajax({
            type: "POST",
            url: sendCredentialsUrl,
            data:{username:$(usernameInputID).val()},
            success: function(msg){

                msg = JSON.parse(msg);
                if(msg.success){
                    $(loginErrorTextID).text('E-mail sent!  Please check your inbox.');
                }else{
                	$(loginErrorTextID).text(msg.errortext);
                }
            },
        });
    });


    $( "#contact_us_signedin_link,#contact_us_signedout_link" ).click(function() {
        sendContactEmail();
    });


    $( "#register_link" ).click(function() {
        goToRegistration();
    });


    $( "#homepage_button" ).click(function() {
        goHome();
    });

});
