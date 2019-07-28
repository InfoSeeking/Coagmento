//var backgroundPage = chrome.extension.getBackgroundPage();
// chrome.tabs.query({ active: true, lastFocusedWindow: true }, function(tabs) {
//     var tab = tabs[0];
//     var domain = tab.url;
// });

var domain = "http://localhost:8000";//"https://problemhelp.comminfo.rutgers.edu";//
//var apidomain = config['apidomain'];

var saveKeystrokeUrl = domain+'/sidebar/keystrokes';
var saveClickUrl = domain+'/sidebar/clicks';
var saveScrollUrl = domain+'/sidebar/scrolls';
var saveWheelUrl = domain+'/sidebar/wheels';
var saveCopyUrl = domain+'/sidebar/copies';
var savePasteUrl = domain+'/sidebar/pastes';
var saveMouseUrl = domain+'/sidebar/mouseactions';

var keystroke_buffer = {};
var modifier_buffer = {};
var click_buffer = {};
var scroll_buffer = {};
var wheel_buffer = {};
var copy_buffer = {};
var paste_buffer = {};
var mouse_buffer = {};
var timers = [];

// var trokeUrl = apidomain+'/keystrokes';
// var saveClickUrl = apidomain+'/clicks';
// var saveScrollUrl = apidomain+'/scrolls';
// var saveCopyUrl = apidomain+'/copies';
// var savePasteUrl = apidomain+'/pastes';
// var saveMouseUrl = apidomain+'/mouseactions';

function clearTimers(){
    for(var i=0; i < timers.length; i+=1) {
        clearTimeout(timers[i]);
    }
    timers = [];
}

clearTimers();

function getLocalDate(timestamp){
    var currentTime = new Date(timestamp);
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate();
    var year = currentTime.getFullYear();
    var localDate = year + "/" + month + "/" + day;
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var localTime = hours + ":" + minutes + ":" + seconds;
    var localTimestamp = currentTime.getTime();
    return {'localDate':localDate,'localTime':localTime,'':localTimestamp}
}

function defaultCallback(responseText){
    console.log(responseText);
}

function sendXMLHTTP(from,params,url){
	// if(from != "saveScrolls"){
	// 	return;
	// }
	console.log("SendXMLHTTP"+from);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json");
	xhr.onreadystatechange = function() {
        console.log(xhr.responseText);
        console.log("FROM: "+ from);
		if (xhr.readyState == 4) {
			console.log("success");
		}
	}
  //console.log(JSON.stringify(params));
	xhr.send(JSON.stringify(params));
}

function saveMouse(mouse_buffer){
    var data = {'mouse_actions':mouse_buffer};
    console.log(data);
    sendXMLHTTP("saveMouse",data,saveMouseUrl);
}

function saveKeys(keystroke_buffer){
	var data = {'keys':keystroke_buffer}
  console.log(data);
  sendXMLHTTP("saveKeys",data,saveKeystrokeUrl);
}
// function saveKeys(keystroke_buffer){
//  var data = {'keys':keystroke_buffer};
//   console.log(data);
//   sendXMLHTTP("saveKeys",data,saveKeystrokeUrl);
// }

function saveClicks(click_buffer){
    var data = {'clicks':click_buffer};
    console.log(data);
    sendXMLHTTP("saveClicks",data,saveClickUrl);
}

function saveScrolls(scroll_buffer){
	var data = {'scrolls':scroll_buffer};
  console.log(data);
  sendXMLHTTP("saveScrolls",data,saveScrollUrl);
}

function saveWheels(wheel_buffer){
	var data = {'wheels':wheel_buffer};
  console.log(data);
  sendXMLHTTP("saveWheels",data,saveWheelUrl);
}

function saveCopy(copy_buffer){
	var data = {'copies':copy_buffer};
  console.log(data);
  sendXMLHTTP("saveCopy",data,saveCopyUrl);
}

function savePaste(paste_buffer){
    var data = {'pastes':paste_buffer};
    console.log(data);
    sendXMLHTTP("savePaste",data,savePasteUrl);
}

function bufferClear(){
  if(Object.keys(keystroke_buffer).length > 0){
      saveKeys(keystroke_buffer);
      keystroke_buffer = {};
      clearTimers();
  }

    if(Object.keys(click_buffer).length > 0){
        saveClicks(click_buffer);
        click_buffer = {};
        clearTimers();
    }


    if(Object.keys(scroll_buffer).length > 0){
        saveScrolls(scroll_buffer);
        scroll_buffer = {};
        clearTimers();
    }

    if(Object.keys(wheel_buffer).length > 0){
        saveWheels(wheel_buffer);
        wheel_buffer = {};
        clearTimers();
    }


    if(Object.keys(copy_buffer).length > 0){
        saveCopy(copy_buffer);
        copy_buffer = {};
        clearTimers();
    }


    if(Object.keys(paste_buffer).length > 0){
        savePaste(paste_buffer);
        paste_buffer = {};
        clearTimers();
    }

    if(Object.keys(mouse_buffer).length > 0){
        saveMouse(mouse_buffer);
        mouse_buffer = {};
        clearTimers();
    }
}

function setBufferClear(){
    if(timers.length >0){
        return;
    }else{
        timers.push(setTimeout(bufferClear, 5000));
    }
}

function keyboardEventStart(event)
{
  var time = new Date().getTime();
  setBufferClear();

  var key_modifier = "";
  if(event.altKey){
      if(key_modifier.length > 0){
          key_modifier = modifier + "-"
      }
      key_modifier = key_modifier + "alt"
  }
  if(event.shiftKey){
      if(key_modifier.length > 0){
          key_modifier = key_modifier + "-"
      }
      key_modifier = key_modifier + "shift"
  }
  if(event.ctrlKey){
      if(key_modifier.length > 0){
          key_modifier = key_modifier + "-"
      }
      key_modifier = key_modifier + "ctrl"
  }
  if(event.metaKey){
      if(key_modifier.length > 0){
          key_modifier = key_modifier + "-"
      }
      key_modifier = key_modifier + "meta"
  }

  var datum = {
    'modifier':key_modifier,
    'code':event.code,
    'key':event.key,
    'repeat':event.repeat,
    'which':event.which,
    'type':event.type
  };

  if(time in keystroke_buffer){
      keystroke_buffer[time].push(datum);
  }else{
      keystroke_buffer[time] = [datum];
  }
}


function saveClick(event)
{
         var time = new Date().getTime();
         setBufferClear();

         click_buffer[time] = {
             'type':event.type,
             'clientX':event.clientX,
             'clientY':event.clientY,
             'pageX':event.pageX,
             'pageY':event.pageY,
             'screenX':window.screenX,
             'screenY':window.screenY,
             'scrollX':window.scrollX,
             'scrollY':window.scrollY,
             'layerX':event.layerX,
             'layerY':event.layerY,
             'movementX':event.movementX,
             'movementY':event.movementY,
             'offsetX':event.offsetX,
             'offsetY':event.offsetY,

             'altKey':event.altKey,
             'metaKey':event.metaKey,
             'ctrlKey':event.ctrlKey,
             'buttons':event.buttons,
             //'modifierState':event.getModifierState(),
             'fromElement':event.fromElement,
             'toElement':event.toElement,
             'relatedTarget':event.relatedTarget,
             //'view':event.view,
             'sourceCapabilities':event.sourceCapabilities
         }

         // if(time in click_buffer){
         //     click_buffer[time].push(datum);
         // }else{
         //      click_buffer[time] = [datum];
         // }
}

function scrollStart(event){
	//console.log("scrollStart");
        var time = new Date().getTime();
        setBufferClear();

        var datum = {
        'screenX':window.screenX,
        'screenY':window.screenY,
        'scrollX':window.scrollX,
        'scrollY':window.scrollY,
        'eventInfo':JSON.stringify(event)
        };

        //console.log(JSON.stringify(event));

        if(time in scroll_buffer){
            scroll_buffer[time].push(datum);
        }else{
            scroll_buffer[time] = [datum];
        }

}

function saveWheelEvent(event){
	//console.log("saveWheelEvent");
        var time = new Date().getTime();
        setBufferClear();

        var datum = {
        'clientX':event.clientX,
        'clientY':event.clientY,
        'pageX':event.pageX,
        'pageY':event.pageY,
        'screenX':window.screenX,
        'screenY':window.screenY,
        'scrollX':window.scrollX,
        'scrollY':window.scrollY,
        'layerX':event.layerX,
        'layerY':event.layerY,
        'movementX':event.movementX,
        'movementY':event.movementY,
        'offsetX':event.offsetX,
        'offsetY':event.offsetY,
        'deltaMode':event.deltaMode,
        'deltaX':event.deltaX,
        'deltaY':event.deltaY,
        'deltaZ':event.deltaZ
        };

        if(time in wheel_buffer){
            wheel_buffer[time].push(datum);
        }else{
            wheel_buffer[time] = [datum];
        }

}

function mouseEventStart(event){
	//console.log("mouseEventStart");
        var time = new Date().getTime();
        setBufferClear();

        datum = {
          'type':event.type,
          'clientX':event.clientX,
          'clientY':event.clientY,
          'pageX':event.pageX,
          'pageY':event.pageY,
          'screenX':window.screenX,
          'screenY':window.screenY,
          'scrollX':window.scrollX,
          'scrollY':window.scrollY,
          'layerX':event.layerX,
          'layerY':event.layerY,
          'movementX':event.movementX,
          'movementY':event.movementY,
          'offsetX':event.offsetX,
          'offsetY':event.offsetY,

          'altKey':event.altKey,
          'metaKey':event.metaKey,
          'ctrlKey':event.ctrlKey,
          //'modifierState':event.getModifierState(),
          'fromElement':event.fromElement,
          'toElement':event.toElement,
          'relatedTarget':event.relatedTarget,
          //'view':event.view,
          'sourceCapabilities':event.sourceCapabilities
        }

        if(time in mouse_buffer){
           mouse_buffer[time].push(datum);
        }else{
            mouse_buffer[time] = [datum];
        }

        //mouse_buffer[time] = datum;

}

document.addEventListener('click', function (e){saveClick(e);}, false);
document.addEventListener('dblclick', function (e){saveClick(e);}, false);
document.addEventListener('wheel', function (e){saveWheelEvent(e);}, false);
document.addEventListener('scroll', function (e){scrollStart(e);}, false);

document.addEventListener('mouseenter', function (e){mouseEventStart(e);}, false);
document.addEventListener('mouseleave', function (e){mouseEventStart(e);}, false);
document.addEventListener('mousedown', function (e){mouseEventStart(e);}, false);
document.addEventListener('mouseup', function (e){mouseEventStart(e);}, false);
document.addEventListener('mousemove', function (e){mouseEventStart(e);}, false);
document.addEventListener('mouseout', function (e){mouseEventStart(e);}, false);

document.addEventListener('keydown', function (e){keyboardEventStart(e);}, false);
document.addEventListener('keypress', function (e){keyboardEventStart(e);}, false);
document.addEventListener('keyup', function (e){keyboardEventStart(e);}, false);



/*var lastsnippet = '';

document.addEventListener('copy', function (e) {
        var time = new Date().getTime();
        setBufferClear();
        var snippet = window.getSelection().toString();
        lastsnippet = {'snippet':snippet,'title':document.title,'url':window.location.href};
        copy_buffer[time] = lastsnippet;

});


document.addEventListener('paste', function (e) {
        var time = new Date().getTime();
        setBufferClear();
        paste_buffer[time] = lastsnippet;

});*/
