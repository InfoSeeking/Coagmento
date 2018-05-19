var feedback_timer = null;
var advanced_shown = false;
var contactUrl = "mailto:mmitsui@scarletmail.rutgers.edu?Subject=Intent%20Study%20Inquiry";

function sendContactEmail(){
    chrome.tabs.create({url:contactUrl}, function(tab){
                       setTimeout(function(){
                                  chrome.tabs.remove(tab.id);
                                  },500);
                       },
    );
    
}

function feedback(msg, cl){
  $("#feedback").html(msg).show().removeClass("error").removeClass("success");
  if (cl) $("#feedback").addClass(cl);
  window.clearTimeout(feedback_timer);
  feedback_timer = window.setTimeout(function(){
    $("#feedback").fadeOut()
  }, 10000);
}

function updateFields(){
  $("[name=username]").val(data.username);
  $("[name=password]").val(data.password);
}

function tryAuth(username, pass, callback){
  $.ajax({
    url: "http://peopleanalytics.org/ExplorationStudy/api/login.php",
    method : "post",
    data : {
      username : username,
      password: pass,
    },
    dataType: "text",
    success : function(resp){
      if (resp.charAt(0) == 'S')
        callback.call(window, resp.substring(1), "success");
      else
        callback.call(window, resp.substring(1), "error");
    },
    error: function(resp){
      callback.call(window, "Unknown error has occured", "error");
    }
  });
}

$("form[name=auth]").on("submit", function(e){
  e.preventDefault();
  var username = $(this).find("[name=username]").val();
  var password = $(this).find("[name=password]").val();
  data.username = username;
  data.password = password;

  tryAuth(username, password, function(resp, status){
    if(status == "error"){
      feedback(resp, "error");
      $("[name=password]").val("");
    } else {      
        feedback(resp + " You may begin browsing. This window will close", "success");
        //set chrome storage
        chrome.storage.sync.set({
          username: username,
          password: password
        });
        updateFields();
        window.setTimeout(function() {
          window.close();
        }, 5000);
    }
  });
});

chrome.storage.sync.get({
  username: "",
  password: ""
}, function(resp){
  data = resp;
  updateFields();
})
