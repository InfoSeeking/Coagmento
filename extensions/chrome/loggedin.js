$(document).ready(function() {


	var homeDir = "http://localhost:8000/auth";
    
    // URLs
    var registerUrl = homeDir+"/register";
    var checkLoggedInUrl = homeDir + "/getLoggedIn.php";
    var loginUrl = homeDir + "/login";
    var logoutUrl = homeDir + "/logout";

    var loggedInHomeUrl ='http://localhost:8000/workspace';
    var projectsUrl = 'http://localhost:8000/workspace/projects';


    function goHome(){
        chrome.tabs.create({url:loggedInHomeUrl}, function(tab){},);
    }

    function gotoProjects(){
        chrome.tabs.create({url:projectsUrl}, function(tab){},);
    }

	// var projectid = $("#projectid").val();
	var projectid = 1;
	if(projectid === ''){
		$('#error').show();
	}
	else {
		chrome.storage.local.set({ "projectid": parseInt(projectid) }, function(){
			//  Data's been saved boys and girls, go on home
			console.log("done");
			$('#suc').show();
								
			});
	}


	function toggleLoggedIn(logged){
        chrome.extension.getBackgroundPage().loggedIn = logged;
    }

    function renderLoggedIn(loggedIn){
    	var red = [255,0,0,255];
    	// var green = [0,255,0,255];
    	var green = [34,139,34,255];
    	if(chrome.extension.getBackgroundPage().loggedIn){
    		chrome.browserAction.setBadgeText({text:' '});
			chrome.browserAction.setBadgeBackgroundColor({color:green});
    	}else{
    		chrome.browserAction.setBadgeText({text:' '});
			chrome.browserAction.setBadgeBackgroundColor({color:red});
    	}
    }




    $( "#opencspace_button" ).click(function() {
        goHome();
    });


    $( "#projects_button" ).click(function() {
        gotoProjects();
    });


	$( "#logout_button" ).click(function() {
        // var xhr = new XMLHttpRequest();
        //     xhr.open("POST", logoutUrl, false);
        //     xhr.setRequestHeader("Content-type", "application/json");
        //     var data2 = {"email":$(usernameInputID).val(),"password":$(passwordInputID).val()};
            // xhr.send(JSON.stringify(data2));
            // var result = xhr.responseText;
            // if(result){
                    toggleLoggedIn(false);
                    renderLoggedIn(false);
                     chrome.browserAction.setPopup({
                                popup:"popup.html"
                         });
                     window.location.href='popup.html';
                // }
    });



   //  $( "#pid" ).click(function() {
			// console.log("clicked");
			// var projectid = $("#projectid").val()
			// if(projectid === ''){
			// 		$('#error').show();
			// 	}
			// else {
			// 		chrome.storage.local.set({ "projectid": parseInt(projectid) }, function(){
			// 				    //  Data's been saved boys and girls, go on home
			// 					console.log("done");
			// 					$('#suc').show()
								
			// 		 });
			// 				//

			// }
   //  	});





});
