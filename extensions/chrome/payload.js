// var domain = config['domain'];
// var apidomain = config['apidomain'];

// var saveKeystrokeUrl = apidomain+'/keystrokes';
// var saveClickUrl = apidomain+'/clicks';
// var saveScrollUrl = apidomain+'/scrolls';
// var saveCopyUrl = apidomain+'/copies';
// var savePasteUrl = apidomain+'/pastes';
// var saveMouseUrl = apidomain+'/mouseactions';

// var keystroke_buffer = {};
// var modifier_buffer = {};
// var click_buffer = {};
// var scroll_buffer = {};
// var copy_buffer = {};
// var paste_buffer = {};
// var mouse_buffer = {};
// var timers = [];

// function clearTimers(){
//     for(var i=0; i < timers.length; i+=1) { 
//         clearTimeout(timers[i]);
//     }
//     timers = [];
// }

// clearTimers();

// function getLocalDate(timestamp){
//     var currentTime = new Date(timestamp);
//     var month = currentTime.getMonth() + 1;
//     var day = currentTime.getDate();
//     var year = currentTime.getFullYear();
//     var localDate = year + "%2F" + month + "%2F" + day;
//     var hours = currentTime.getHours();
//     var minutes = currentTime.getMinutes();
//     var seconds = currentTime.getSeconds();
//     var localTime = hours + "%3A" + minutes + "%3A" + seconds;
//     var localTimestamp = currentTime.getTime();
//     return {'localDate':localDate,'localTime':localTime,'':localTimestamp}
// }

// function defaultCallback(responseText){
//     console.log(responseText);
// }


// function sendXMLHTTP(from,params,url){
// 	// if(from != "saveScrolls"){
// 	// 	return;
// 	// }
// 	console.log("SendXMLHTTP"+from);
// 	var xhr = new XMLHttpRequest();
// 	xhr.open("POST", url, true);
// 	xhr.setRequestHeader("Content-type", "application/json");
// 	xhr.onreadystatechange = function() {
// 		console.log("Payload XMLHttp");
//         console.log(xhr.responseText);
//         console.log("FROM: "+ from);
// 		if (xhr.readyState == 4) {
// 			console.log();
// 		}
// 	}
// 	xhr.send(JSON.stringify(params));
// }



// function saveKeys(keystroke_buffer,modifier_buffer){
// 	var data = {'keys':keystroke_buffer,'modifiers':modifier_buffer}
//     sendXMLHTTP("saveKeys",data,saveKeystrokeUrl);
//     // , defaultCallback);     
// }


// function saveClicks(click_buffer){
//     var data = {'clicks':click_buffer}
//     sendXMLHTTP("saveClicks",data,saveClickUrl);
// }


// function saveScrolls(scroll_buffer){
// 	var data = {'scrolls':scroll_buffer}
//     sendXMLHTTP("saveScrolls",data,saveScrollUrl);
// }


// function saveCopy(copy_buffer){
// 	var data = {'copies':copy_buffer}
//     sendXMLHTTP("saveCopy",data,saveCopyUrl);
// }


// function savePaste(paste_buffer){
//     var data = {'pastes':paste_buffer}
//     sendXMLHTTP("savePaste",data,savePasteUrl);
// }


// function saveMouse(mouse_buffer){
//     var data = {'mouse_actions':mouse_buffer}
//     sendXMLHTTP("saveMouse",data,saveMouseUrl);
// }



// function bufferClear(){
//     if(Object.keys(keystroke_buffer).length > 0){
//         saveKeys(keystroke_buffer,modifier_buffer);
//         keystroke_buffer = {};
//         modifier_buffer = {};
//         clearTimers();
//     }

//     if(Object.keys(click_buffer).length > 0){
//         saveClicks(click_buffer);
//         click_buffer = {};
//         clearTimers();
//     }


//     if(Object.keys(scroll_buffer).length > 0){
//         saveScrolls(scroll_buffer);
//         scroll_buffer = {};
//         clearTimers();
//     }


//     if(Object.keys(copy_buffer).length > 0){
//         saveCopy(copy_buffer);
//         copy_buffer = {};
//         clearTimers();
//     }


//     if(Object.keys(paste_buffer).length > 0){
//         savePaste(paste_buffer);
//         paste_buffer = {};
//         clearTimers();
//     }

//     if(Object.keys(mouse_buffer).length > 0){
//         saveMouse(mouse_buffer);
//         mouse_buffer = {};
//         clearTimers();
//     }
// }

// function setBufferClear(){
//     if(timers.length >0){
//         return;
//     }else{
//         timers.push(setTimeout(bufferClear, 5000));
//     }
// }

// document.addEventListener('keypress', function (e) {
//     var time = new Date().getTime();
//     setBufferClear();

//     e = e || window.event;
//     var key = e.which;   
//     var modifier = "";
        
//     if(event.altKey){
//         if(modifier.length > 0){
//             modifier = modifier + "-"
//         }
//         modifier = modifier + "alt"
//     }
//     if(event.shiftKey){
//         if(modifier.length > 0){
//             modifier = modifier + "-"
//         }
//         modifier = modifier + "shift"
//     }
//     if(event.ctrlKey){
//         if(modifier.length > 0){
//             modifier = modifier + "-"
//         }
//         modifier = modifier + "ctrl"
//     }
//     if(event.metaKey){
//         if(modifier.length > 0){
//             modifier = modifier + "-"
//         }
//         modifier = modifier + "meta"
//     }

//     if(time in keystroke_buffer){
//         keystroke_buffer[time].push(key);
//     }else{
//         keystroke_buffer[time] = [key];
//     } 

//     if(time in modifier_buffer){
//         modifier_buffer[time].push(modifier);
//     }else{
//         modifier_buffer[time] = [modifier];
//     }  
// });


// var lastsnippet = '';

// document.addEventListener('copy', function (e) {
//         var time = new Date().getTime();
//         setBufferClear();
//         var snippet = window.getSelection().toString();
//         lastsnippet = {'snippet':snippet,'title':document.title,'url':window.location.href};    
//         copy_buffer[time] = lastsnippet;

// });



// document.addEventListener('paste', function (e) {  
//         var time = new Date().getTime();
//         setBufferClear();
//         paste_buffer[time] = lastsnippet;

// });

// function saveClick(event,type)
// {
//         var time = new Date().getTime();
//         setBufferClear();
//         click_buffer[time] = {'type':type,
//             'clientX':event.clientX,
//             'clientY':event.clientY,
//             'pageX':event.pageX,
//             'pageY':event.pageY,
//             'screenX':window.screenX,
//             'screenY':window.screenY,
//             'scrollX':window.scrollX,
//             'scrollY':window.scrollY,
//         }
// }

// function scrollStart(event){
// 	console.log("scrollStart");
//         var time = new Date().getTime();
//         setBufferClear();

//         var datum = {
//         'screenX':window.screenX,
//         'screenY':window.screenY,
//         'scrollX':window.scrollX,
//         'scrollY':window.scrollY,
//         };

//         if(time in scroll_buffer){
//             scroll_buffer[time].push(datum);
//         }else{
//             scroll_buffer[time] = [datum];
//         }
 
// }


// function mouseEventStart(eventName,event){
// 	console.log("mouseEventStart");
//         var time = new Date().getTime();
//         setBufferClear();

//         var datum = {
//         'type':eventName,
//         'screenX':window.screenX,
//         'screenY':window.screenY,
//         'scrollX':window.scrollX,
//         'scrollY':window.scrollY,
//         'clientX':event.clientX,
//         'clientY':event.clientY,
//         'pageX':event.pageX,
//         'pageY':event.pageY,
//         }

//         if(time in mouse_buffer){
//             mouse_buffer[time].push(datum);
//         }else{
//             mouse_buffer[time] = [datum];
//         }

// }





// document.addEventListener('click', function (e){saveClick(e,'click');}, false);
// document.addEventListener('dblclick', function (e){saveClick(e,'dblclick');}, false);
// document.addEventListener('scroll', function (e){scrollStart(e);}, false);
// document.addEventListener('mouseenter', function (e){mouseEventStart('mouseenter',e);}, false);
// document.addEventListener('mouseleave', function (e){mouseEventStart('mouseleave',e);}, false);
// document.addEventListener('mousedown', function (e){mouseEventStart('mousedown',e);}, false);
// document.addEventListener('mouseup', function (e){mouseEventStart('mouseup',e);}, false);
// document.addEventListener('mousemove', function (e){mouseEventStart('mousemove',e);}, false);
// document.addEventListener('mouseout', function (e){mouseEventStart('mouseout',e);}, false);
// console.log("Payload loaded!")