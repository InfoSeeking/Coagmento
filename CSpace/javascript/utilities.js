var bustcachevar = 1; //bust potential caching of external pages after initial request? (1=yes, 0=no)
var loadedobjects = "";
var rootdomain = "http://"+window.location.hostname;
var bustcacheparameter = "";

// Function to load an external URL in a container
function ajaxpage(url, containerid) {
	var page_request = false;
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
		page_request = new XMLHttpRequest();
	else if (window.ActiveXObject){ // if IE
		try {
			page_request = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e){
			try{
				page_request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){}
		}
	}
	else
		return false;

	//I added this condition for making only ajax requests without showing the result on a certain DIV section
	if (containerid!=null)
	{
		page_request.onreadystatechange=function() {
			loadpage(page_request, containerid)
		}
	}

	if (bustcachevar) //if bust caching of external page
		bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime();
	page_request.open('GET', url+bustcacheparameter, true);
	page_request.send(null);
}

// Function to load a page in a container by making a HTTP request
function loadpage(page_request, containerid){
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1))
		document.getElementById(containerid).innerHTML=page_request.responseText;
}

// Read a cookie's value
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function switchMenu(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}

function getSlots(slotID) {
	var requestURL = 'http://www.coagmento.org/study1/getSlots.php?firstSlot='+slotID;
	req = new phpRequest(requestURL);
	var response = req.execute();
	var s2 = document.getElementById('session2');
	s2.style.display = '';
	s2.innerHTML = response;
}

function doAnalysis() {
	var condition = document.getElementById('condition').selectedIndex;
	var session = document.getElementById('session').selectedIndex;
	var task = document.getElementById('task').selectedIndex;
	var team = document.getElementById('team').value;
	var user = document.getElementById('user').value;
	var demographics = document.getElementById('demographics').value;
	var postTask1 = document.getElementById('postTask1').value;
	var postTask2 = document.getElementById('postTask2').value;
	var postTask3 = document.getElementById('postTask3').value;
	var exit1 = document.getElementById('exit1').value;
	var exit2 = document.getElementById('exit2').value;
	var exit3 = document.getElementById('exit3').value;
	var preTask = document.getElementById('preTask').value;
	var requestURL = 'http://localhost/coagmento.com/study1/doAnalysis.php?condition='+condition+'&session='+session+'&task='+task+'&team='+team+'&user='+user+'&demographics='+demographics+'&postTask1='+postTask1+'&postTask2='+postTask2+'&postTask3='+postTask3+'&exit1='+exit1+'&exit2='+exit2+'&exit3='+exit3+'&preTask='+preTask;
	req = new phpRequest(requestURL);
//	alert(requestURL);
	var response = req.execute();
	var analysis = document.getElementById('analysis');
//	analysis.style.display = '';
	analysis.innerHTML = response;
}

//Start phpRequest Object
function phpRequest(serverScript) {
	//Set some default variables
	this.parms = new Array();
	this.parmsIndex = 0;

	//Set the server url
	this.server = serverScript;

	//Add two methods
	this.execute = phpRequestExecute;
	this.add = phpRequestAdd;
}

function phpRequestAdd(name,value) {
    //Add a new pair object to the params
    this.parms[this.parmsIndex] = new pair(name,value);
    this.parmsIndex++;
}

//var lastURL = "";

function phpRequestExecute() {
    //Set the server to a local variable
    var targetURL = this.server;

    //Try to create our XMLHttpRequest Object
    try {
        var httpRequest = new XMLHttpRequest();
    }
    catch (e) {
        alert('Error creating the connection!');
        return;
    }

    //Make the connection and send our data
    try {
        var txt = "?1";
        for(var i in this.parms) {
            txt = txt+'&'+this.parms[i].name+'='+this.parms[i].value;
        }
        //Two options here, only uncomment one of these
        //GET REQUEST
		var currentURL = targetURL+txt;
//		if (currentURL != lastURL) {
//			lastURL = currentURL;
	        httpRequest.open("GET", currentURL, false, null, null);  
	        httpRequest.send('');			
//		}		
    }
    catch (e) {
//        alert('An error has occured calling the external site: '+e);
        return false;
    }

    //Make sure we received a valid response
    switch(httpRequest.readyState) {
        case 1,2,3:
 //           alert('Bad Ready State: '+httpRequest.status);
            return false;
            break;
        case 4:
            if(httpRequest.status !=200) {
 //               alert('The server respond with a bad status code: '+httpRequest.status);
                return false;
            }
            else {
                var response = httpRequest.responseText;
            }
            break;
    }
    return response;
}

function pair(name,value) {
    this.name = name;
    this.value = value;
}

function hideLayer(layer){
	document.getElementById(layer).style.display="none";
	//Save action
    ajaxpage('sidebarComponents/insertAction.php?action=hideLayer&value='+layer,null);
}

function copyToClipboard(itemID)
{
//    var text = document.getElementById(itemID).value;
//
//    if (window.clipboardData) // IE
//    {
//        window.clipboardData.setData("Text", text);
//    }
//    else
//    {
//        //unsafeWindow.netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
//	  netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
//        var clipboardHelper = Components.classes["@mozilla.org/widget/clipboardhelper;1"].getService(Components.interfaces.nsIClipboardHelper);
//        clipboardHelper.copyString(text);
//    }
//    //Save action
//    ajaxpage('sidebarComponents/insertAction.php?action=copy&value='+itemID,null);
//    alert('Text copied successfully. Now paste the text using CTRL+V');
}
copyToClipboard = function(itemID) {
    var text = document.getElementById(itemID).value;
    if(window.clipboardData) {
      window.clipboardData.clearData();
      window.clipboardData.setData("Text", text);
    } else if(navigator.userAgent.indexOf("Opera") != -1) {
    window.location = text;
    } else if (window.netscape) {
    try {
        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
    } catch (e) {
        alert("You need set 'signed.applets.codebase_principal_support=true' at about:config'");
        return false;
    }
    var clip = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
    if (!clip)
        return;
    var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
    if (!trans)
    return;
    trans.addDataFlavor('text/unicode');
    var str = new Object();
    var len = new Object();
    var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
    var copytext = text;
    str.data = copytext;
    trans.setTransferData("text/unicode",str,copytext.length*2);
    //Save action
    ajaxpage('sidebarComponents/insertAction.php?action=copy&value='+itemID,null);
    alert('Text copied successfully. Now paste the text using CTRL+V');
    var clipid = Components.interfaces.nsIClipboard;
    if (!clip)
    return false;
    clip.setData(trans,null,clipid.kGlobalClipboard);
    }
}


function showSnippet(ID,parentID,snippetID,type) {
 var sText = "<center><table style=\"text-align: center; color: blue; font-weight: bold\" width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\"><tr>";

 sText = sText + "<td class=\"cursorType\" onclick=\"javascript:copyToClipboard('snippetValue"+snippetID+"')\">Copy</td>";
 sText = sText + "<td>- -</td>";  
 sText = sText + "<td class=\"cursorType\" onclick=\"javascript:hideLayer('"+ID+"')\">Close</td></tr></table></center>"; 
 sText = sText + "<p>Notes: "+document.getElementById("note"+snippetID).value.substr(0,200)+"...</p><hr>";

 if (type == "text")
	sText = sText + "<p>Snippet: "+document.getElementById("snippetValue"+snippetID).value.substr(0,750)+"...</p>";
 else
     sText = sText + "<img src=\""+document.getElementById(snippetID).value+"\" style=\"width:330px; height:400px;\">";
 
if (document.layers) { 
   var oLayer; 
   if(parentID){ 
     oLayer = eval('document.' + parentID + '.document.' + ID + '.document'); 
   }else{ 
     oLayer = document.layers[ID].document; 
   } 
   oLayer.open(); 
   oLayer.write(sText); 
   oLayer.close(); 
 } 
 else if (parseInt(navigator.appVersion)>=5&&navigator. 
appName=="Netscape") { 
   document.getElementById(ID).innerHTML = sText; 
 } 
 else if (document.all) document.all[ID].innerHTML = sText 
 
//Save action
  ajaxpage('sidebarComponents/insertAction.php?action=show_snippet&value='+snippetID,null);
  document.getElementById(ID).style.display="block";
}

function showPage(ID,parentID,pageID) {
 var sText = "<center><table style=\"text-align: center; color: blue; font-weight: bold\" width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\"><tr>";

 sText = sText + "<td class=\"cursorType\" onclick=\"javascript:copyToClipboard('pageValue"+pageID+"')\">Copy</td>";
 sText = sText + "<td>- -</td>";  
 sText = sText + "<td class=\"cursorType\" onclick=\"javascript:hideLayer('"+ID+"')\">Close</td></tr></table></center>"; 
 sText = sText + "<p>Notes: "+document.getElementById("note"+pageID).value.substr(0,200)+"...</p><hr>";

 sText = sText + "<p>Bookmark: "+document.getElementById("pageValue"+pageID).value.substr(0,750)+"...</p>";
 
if (document.layers) { 
   var oLayer; 
   if(parentID){ 
     oLayer = eval('document.' + parentID + '.document.' + ID + '.document'); 
   }else{ 
     oLayer = document.layers[ID].document; 
   } 
   oLayer.open(); 
   oLayer.write(sText); 
   oLayer.close(); 
 } 
 else if (parseInt(navigator.appVersion)>=5&&navigator. 
appName=="Netscape") { 
   document.getElementById(ID).innerHTML = sText; 
 } 
 else if (document.all) document.all[ID].innerHTML = sText 
 
//Save action
  ajaxpage('sidebarComponents/insertAction.php?action=show_page&value='+pageID,null);
  document.getElementById(ID).style.display="block";
}


function showRatingForm(ID,parentID,itemID,type,region,webPage) {
 
 var typeAux = "snippet";
 if (type=="pages")
	typeAux = "page";
 else
	if (type=="queries")
		typeAux = "query";
	
 var sText = "How good is this "+typeAux+"? Rate it:<br><br>"
 sText = sText +"<center><table><tr><td><input type=\"radio\" id=\"rating1\" name=\"rating\"  value=\"1\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating2\" name=\"rating\" value=\"2\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating3\" name=\"rating\"  value=\"3\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating4\" name=\"rating\" value=\"4\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating5\" name=\"rating\" value=\"5\"></td></tr>";
 sText = sText + "<tr align=center><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr></table>";
 sText = sText + "<br><br>";
 sText = sText + "<BUTTON onclick=\"javascript:hideLayer('"+ID+"')\"><STRONG>Cancel</STRONG></BUTTON> <BUTTON onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\"><STRONG>Ok</STRONG></BUTTON></center>";

if (document.layers) { 
   var oLayer; 
   if(parentID){ 
     oLayer = eval('document.' + parentID + '.document.' + ID + '.document'); 
   }else{ 
     oLayer = document.layers[ID].document; 
   } 
   oLayer.open(); 
   oLayer.write(sText); 
   oLayer.close(); 
 } 
 else if (parseInt(navigator.appVersion)>=5&&navigator. 
appName=="Netscape") { 
   document.getElementById(ID).innerHTML = sText; 
 } 
 else if (document.all) document.all[ID].innerHTML = sText 
 
//Save action
  ajaxpage('sidebarComponents/insertAction.php?action=showRatingForm_'+type+'&value='+itemID,null);
  document.getElementById(ID).style.display="block";
} 

function saveRating(ID,itemID,type,region,webPage)
{
	hideLayer(ID);

	var buttonGroup = document.getElementsByName('rating');
	var value = 0;
      for (var i=0; i<buttonGroup.length; i++)
      	if (buttonGroup[i].checked)
            	value = buttonGroup[i].value;
	if (value != 0)
	{
		ajaxpage('sidebarComponents/updateRating.php?type='+type+'&itemID='+itemID+'&value='+value+'&webPage='+webPage, region);
		ajaxpage('sidebarComponents/insertAction.php?action=saveRating_'+type+'&value='+itemID,null);
		/*
 			new Ajax.Request('insertAction.php', {
    			method: 'post',
    			parameters: {action: 'saveRating_'+type, value: itemID},
      		onSuccess: function(transport) {
        		eval( transport.responseText ); */
	}
}

//From http://www.netlobo.com/url_query_string_javascript.html
function gup(name) {
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec(window.location.href);
    if(results == null) {
        return "";
    }
    return results[1];
}

function reload(webpage,region) {
	ajaxpage(webpage, region);

	/*var actionBox = document.getElementById(region);
	var scrollHeight = actionBox.scrollHeight;
	actionBox.scrollTop = 5000;	*/		
}

function changeOrder(table,orderBy,region,webPage)
{
	ajaxpage('sidebarComponents/updateOrder.php?table='+table+'&orderBy='+orderBy+'&webPage='+webPage, region);
}

