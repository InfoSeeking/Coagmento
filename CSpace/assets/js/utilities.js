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

//function switchMenu(obj) {
//	var el = document.getElementById(obj);
//	if ( el.style.display != "none" ) {
//		el.style.display = 'none';
//	}
//	else {
//		el.style.display = '';
//	}
//}

function addTag() {
	var tag = document.getElementById('tag').value;
	var page = 'tags.php?tag='+tag;
	ajaxpage(page,'content');
}

/* function inviteCollab() {
	var inviteEmail = document.getElementById('inviteEmail').value;
	var sureInvite = document.getElementById('sureInvite');
	sureInvite.innerHTML = '<font color="green">Are you sure you want to add <span style="font-weight:bold">'+inviteEmail+ '</span> to this project?</font><br/><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage(\'addCollaborator.php?targetUserName='+inviteEmail+'\',\'content\');">Yes</span>&nbsp;&nbsp;&nbsp;<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="cancelInvite();">No</span>';
} */

function inviteCollab() {
	var inviteEmail = document.getElementById('inviteEmail').value;
	var sureInvite = document.getElementById('sureInvite');
	sureInvite.innerHTML = 'Are you sure you want to add <span style="font-weight:bold">'+inviteEmail+ '</span> to this project?<br/><a href="'+rootdomain+'/CSpace/addCollaborator.php?targetUserName='+inviteEmail+'">Yes</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cancelInvite();">No</a>';
}

function cancelInvite() {
	var sureInvite = document.getElementById('sureInvite');
	sureInvite.innerHTML = '';
}


function recommendCoagmento() {
	var inviteEmail = document.getElementById('inviteEmail').value;
	var message = document.getElementById('message').value;
	var userMessage = escape(message);
	var sureInvite = document.getElementById('sureInvite');
 	sureInvite.innerHTML = 'Are you sure you want to recommend Coagmento to <span style="font-weight:bold">'+inviteEmail+ '</span>?<br/><a href="recommendCoagmento.php?inviteEmail='+inviteEmail+'&message='+userMessage+'">Yes</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cancelInvite();">No</a>';
}


/* function createProj() {
	var projTitle = document.getElementById('projTitle').value;
	var projDesc = document.getElementById('projDesc').value;
	var projPrivacy = 0;
	if (document.getElementById('public').checked)
		projPrivacy = 0;
	else
		projPrivacy = 1;
	var sureCreate = document.getElementById('sureCreate');
	sureCreate.innerHTML = '<font color="green">Are you sure you want to create project <span style="font-weight:bold">'+projTitle+ '</span></font>?<br/><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage(\'createProject.php?title='+projTitle+'&description='+projDesc+'&privacy='+projPrivacy+'\',\'content\');">Yes</span>&nbsp;&nbsp;&nbsp;<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="cancelCreateProj();">No</span>';
} */

function createProj() {
	var projTitle = document.getElementById('projTitle').value;
	var projDesc = document.getElementById('projDesc').value;
	var projPrivacy = 0;
	if (document.getElementById('public').checked)
		projPrivacy = 0;
	else
		projPrivacy = 1;
	var sureCreate = document.getElementById('sureCreate');
	sureCreate.innerHTML = 'Are you sure you want to create project <span style="font-weight:bold">'+projTitle+ '</span>?<br/><a href="'+rootdomain+'/CSpace/createProject.php?title='+projTitle+'&description='+projDesc+'&privacy='+projPrivacy+'">Yes</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cancelCreateProj();">No</a>';
}

function cancelCreateProj() {
	var sureInvite = document.getElementById('sureCreate');
	sureInvite.innerHTML = '';
}

function editProj() {
	var projID = document.getElementById('projectID').value;
	var projTitle = document.getElementById('projTitle').value;
	var projDesc = document.getElementById('projDesc').value;
	var projPrivacy = 0;
	if (document.getElementById('public').checked)
		projPrivacy = 0;
	else
		projPrivacy = 1;
	var sureCreate = document.getElementById('sureCreate');
	var page = rootdoain+'/CSpace/timelineview/editProject.php?submit=true&projectID='+projID+'&title='+projTitle+'&description='+projDesc+'&privacy='+projPrivacy;
	window.location.href = page;

}


function deleteProj(projID, projTitle) {
	var sureDelete = document.getElementById('sureDelete');
	sureDelete.innerHTML = '<font color="green">Are you sure you want to leave project <span style="font-weight:bold">'+projTitle+ '</span>?</font><br/><a href="projects.php?projectID='+projID+'">Yes</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cancelDeleteProj();">No</a>';
}

function joinProj(projID, projTitle) {
	var sureJoin = document.getElementById('sureJoin');
	sureJoin.innerHTML = '<font color="green">Are you sure you want to join project <span style="font-weight:bold">'+projTitle+ '</span>?</font><br/><a href="showPublicProjs.php?projectID='+projID+'">Yes</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="cancelJoinProj();">No</a>';
}

function cancelDeleteProj() {
	var sureDelete = document.getElementById('sureDelete');
	sureDelete.innerHTML = '';
}

function cancelJoinProj() {
	var sureDelete = document.getElementById('sureJoin');
	sureDelete.innerHTML = '';
}

function updateProfile() {
	var password = document.getElementById('password').value;
	var cpassword = document.getElementById('cpassword').value;
	if (password != cpassword) {
		alert('Password and confirm password do not match.');
	}
	else {
		var fname = document.getElementById('fname').value;
		var lname = document.getElementById('lname').value;
		var organization = document.getElementById('organization').value;
		var email = document.getElementById('email').value;
		var website = document.getElementById('website').value;
		if (password)
			var url = 'profile.php?password='+password+'&fname='+fname+'&lname='+lname+'&organization='+organization+'&email='+email+'&website='+website;
		else
			var url = 'profile.php?fname='+fname+'&lname='+lname+'&organization='+organization+'&email='+email+'&website='+website;
		/* ajaxpage(url,'content'); */
		window.location.href = url;
	}
}

function checkUncheckAll(theElement) {
	var theForm = theElement.form, z = 0;
	for(z=0; z<theForm.length;z++)
	{
    	if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
		{
	  		theForm[z].checked = theElement.checked;
	  	}
    }
}

function makeSelection() {
	flag = document.getElementById("selection").value;
	switch(flag) {
		case "all":
			var theForm = document.getElementById("form1");
			for(z=0; z<theForm.length;z++)
			{
		    	if (theForm[z].type == 'checkbox')
				{
			  		theForm[z].checked = 1;
			  	}
		    }
			break;
		case "none":
			var theForm = document.getElementById("form1");
			for(z=0; z<theForm.length;z++)
			{
		    	if (theForm[z].type == 'checkbox')
				{
			  		theForm[z].checked = 0;
			  	}
		    }
			break;
		case "invert":
			var theForm = document.getElementById("form1");
			for(z=0; z<theForm.length;z++)
			{
		    	if (theForm[z].type == 'checkbox')
				{
			  		if (theForm[z].checked && theForm[z].value!="all")
			 			theForm[z].checked = 0;
					else if (!theForm[z].checked && theForm[z].value!="all")
						theForm[z].checked = 1;
			  	}
		    }
			break;
	}
}

function newNote(shared) {
	var nID = 'note'+shared;
	var note = document.getElementById(nID);
	note.value = '';
	var idField = document.getElementById('noteID');
	idField.value = '-1';
	note.focus();
}

function saveNote(shared) {
	var nID = 'note'+shared;
	var note = document.getElementById(nID).value;
	var noteID = document.getElementById('noteID').value;
	if (note) {
		if (noteID!=-1)
			ajaxpage('noteList.php?shared='+shared+'&note='+note+'&noteID='+noteID,'noteList');
		else
			ajaxpage('noteList.php?shared='+shared+'&note='+note,'noteList');
	}
	else
		ajaxpage('noteList.php?shared='+shared,'noteList');
}

function showNote(shared, noteID, noteText) {
	var nID = 'note'+shared;
	var note = document.getElementById(nID);
	note.value = noteText;
	var idField = document.getElementById('noteID');
	idField.value = noteID;
	note.focus();
}

function deleteNote(shared, noteID) {
	var nID = 'note'+shared;
	var note = document.getElementById(nID);
	note.value = '';
	ajaxpage('noteList.php?shared='+shared+'&delete=yes&noteID='+noteID);
}

function filterData() {
	var objSelected = document.getElementById('objects').value;
	var projSelected = document.getElementById('projectID').value;
	var sessionSelected = document.getElementById('session').value;
//	ajaxpage('showProgress.php','content');
	ajaxpage('services/data.php?projectID='+projSelected+'&session='+sessionSelected+'&objects='+objSelected, 'content');
}

function filterAllData() {
	var objSelected = document.getElementById('objects').value;
	var projSelected = document.getElementById('projectID').value;
	var sessionSelected = document.getElementById('session').value;
	ajaxpage('services/allData.php?projectID='+projSelected+'&session='+sessionSelected+'&objects='+objSelected, 'content');
}

function search(projID,objs,sess) {
	var searchString = document.getElementById('searchString').value;
//	ajaxpage('showProgress.php','content');
	ajaxpage('services/data.php?projectID='+projID+'&objects='+objs+'&session='+sess+'&searchString='+searchString, 'content');
}

function searchAll(projID,objs,sess) {
	var searchString = document.getElementById('searchString').value;
	ajaxpage('services/allData.php?projectID='+projID+'&objects='+objs+'&session='+sess+'&searchString='+searchString, 'content');
}

function handleDragDropEvent(oEvent) {
/*
	var url = gBrowser.selectedBrowser.currentURI.spec;
	url = encodeURIComponent(url);
	var title = document.title;
*/
	var requestURL = rootdomain+'/CSpace/getCurrentPage.php';
	req = new phpRequest(requestURL);
	var response = req.execute();
	var res = response.split(";:;");
	var url = res[0];
	var title = res[1];
    var snippet = oEvent.dataTransfer.getData("text/plain");
    if ((snippet.match("jpg")) || (snippet.match("JPG")) || (snippet.match("gif")) || (snippet.match("GIF")) || (snippet.match("png")) || (snippet.match("PNG")))
    	var type = 'image';
    else
    	var type = 'text';
    window.open(rootdomain+'/CSpace/services/saveSnippet.php?'+'&URL='+url+'&snippet='+snippet+'&title='+title+'&type='+type,'Save an object','resizable=yes,scrollbars=yes,width=640,height=480,left=600');
}

function addAction (action, value) {
	req = new phpRequest(rootdomain+"/CSpace/timelineview/addAction.php");
	req.add('action', action);
	req.add('value', value);
	var response = req.execute();
}

function hideLayer(layer){
	document.getElementById(layer).style.display="none";
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
// var sText = "<center><table style=\"text-align: center; color: blue; font-weight: bold\" width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\"><tr><td>";
var sText = "";
 sText = sText + "<p>Notes: "+document.getElementById("note"+snippetID).value.substr(0,200)+"...</p><hr>";
 sText = sText + "<p>Source: "+document.getElementById("source"+snippetID).value.substr(0,200)+"...</p><hr>";
 sText = sText + "<p>URL: "+document.getElementById("url"+snippetID).value.substr(0,20)+"...</p><hr>";
 if (type == "text")
	sText = sText + "<p>Snippet: "+document.getElementById("snippetValue"+snippetID).value.substr(0,250)+"...</p>";
 else
     sText = sText + "<img src=\""+document.getElementById(snippetID).value+"\" style=\"width:330px; height:400px;\">";
//sText = "</td></tr></table>";
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
  //ajaxpage('sidebarComponents/insertAction.php?action=preview_snippet&value='+snippetID,null);
  var dd = document.getElementById(ID);
  AssignPosition(dd);
  document.getElementById(ID).style.display="block";
}

function showPage(ID,parentID,pageID) {
// var sText = "<center><table style=\"text-align: center; color: blue; font-weight: bold\" width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\"><tr><td>";
var sText = "";
 sText = sText + "<p>Title: "+document.getElementById("title"+pageID).value.substr(0,200)+"...</p><hr>";
 sText = sText + "<p>Notes: "+document.getElementById("note"+pageID).value.substr(0,200)+"...</p><hr>";
 sText = sText + "<p>URL: "+document.getElementById("pageValue"+pageID).value.substr(0,20)+"...</p><hr>";

 //sText = "</td></tr></table>";

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
 // ajaxpage('sidebarComponents/insertAction.php?action=show_page&value='+pageID,null);
  var dd = document.getElementById(ID);
  AssignPosition(dd);
  document.getElementById(ID).style.display="block";
}

function showTime(ID,parentID,itemID) {
    var currentY = cY;
// var sText = "<center><table style=\"text-align: center; color: blue; font-weight: bold\" width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\"><tr><td>";
var sText = "<p>Time: "+document.getElementById("time"+itemID).value+"</p>";

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
  //ajaxpage('sidebarComponents/insertAction.php?action=preview_snippet&value='+snippetID,null);
  var dd = document.getElementById(ID);
  AssignPositionFixedOnClick(dd,65,currentY-15);
  document.getElementById(ID).style.display="block";
}

function showRatingForm(ID,parentID,itemID,type,region,webPage) {
 var currentY = cY;
 var typeAux = "snippet";
 if (type=="pages")
	typeAux = "page";
 else
	if (type=="queries")
		typeAux = "query";

 var sText = "<center><p>How good is this "+typeAux+"? Rate it:</p><br><br>"
 sText = sText +"<table><tr><td><input type=\"radio\" id=\"rating1\" onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\" name=\"rating\"  value=\"1\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating2\" name=\"rating\" onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\" value=\"2\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating3\" name=\"rating\" onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\" value=\"3\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating4\" name=\"rating\" onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\" value=\"4\"></td>";
 sText = sText + "<td><input type=\"radio\" id=\"rating5\" name=\"rating\" onclick=\"javascript:saveRating('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\" value=\"5\"></td></tr>";
 sText = sText + "<tr align=center><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr></table>";
 sText = sText + "<br><br>";
 sText = sText + "<BUTTON onclick=\"javascript:hideLayer('"+ID+"')\"><STRONG>Cancel</STRONG></BUTTON></center>";

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
  var dd = document.getElementById(ID);
  AssignPositionFixedOnClick(dd,110,currentY-15);
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
		//ajaxpage('sidebarComponents/insertAction.php?action=saveRating_'+type+'&value='+itemID,null);
		/*
 			new Ajax.Request('insertAction.php', {
    			method: 'post',
    			parameters: {action: 'saveRating_'+type, value: itemID},
      		onSuccess: function(transport) {
        		eval( transport.responseText ); */
	}
}

function saveRatingSimple(itemID,type, value)
{
		ajaxpage('updateRating.php?type='+type+'&itemID='+itemID+'&value='+value+'&webPage=',null);
}

function deleteItem(ID,parentID,itemID,type,region,webPage)
{
    var currentY = cY;
     var typeAux = "snippet";
     if (type=="pages")
            typeAux = "page";
     else
            if (type=="queries")
                    typeAux = "query";

     var sText = "<center><p>Are you sure you want to delete this resource?</p><br><br>"
     sText = sText + "<BUTTON onclick=\"javascript:deleteItemAux('"+ID+"','"+itemID+"','"+type+"','"+region+"','"+webPage+"')\"><STRONG>YES</STRONG></BUTTON> <BUTTON onclick=\"javascript:hideLayer('"+ID+"')\"><STRONG>NO</STRONG></BUTTON></center>";

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
  var dd = document.getElementById(ID);
    AssignPositionFixedOnClick(dd,140,currentY-30);
  ajaxpage('sidebarComponents/insertAction.php?action=showDeleteForm_'+type+'&value='+itemID,null);
  document.getElementById(ID).style.display="block";

}

function deleteItemAux(ID,itemID,type,region,webPage)
{
        hideLayer(ID);
        ajaxpage('sidebarComponents/deleteItem.php?type='+type+'&itemID='+itemID+'&webPage='+webPage, region);

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

function getSlots(slotID) {
	var requestURL = rootdomain+'/study1/getSlots.php?firstSlot='+slotID;
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



var cX = 0; var cY = 0; var rX = 0; var rY = 0;
function UpdateCursorPosition(e){ cX = e.pageX; cY = e.pageY;}
function UpdateCursorPositionDocAll(e){ cX = event.clientX; cY = event.clientY;}
if(document.all) { document.onmousemove = UpdateCursorPositionDocAll; }
else { document.onmousemove = UpdateCursorPosition; }
function AssignPosition(d) {
    if(self.pageYOffset) {
            rX = self.pageXOffset;
            rY = self.pageYOffset;
            }
    else if(document.documentElement && document.documentElement.scrollTop) {
            rX = document.documentElement.scrollLeft;
            rY = document.documentElement.scrollTop;
            }
    else if(document.body) {
            rX = document.body.scrollLeft;
            rY = document.body.scrollTop;
            }
    if(document.all) {
            cX += rX;
            cY += rY;
            }
    d.style.left = (cX+10) + "px";
    d.style.top = (cY+10) + "px";
}

function AssignPositionFixedOnClick(d,axyX, axyY) {
    d.style.left = (axyX) + "px";
    d.style.top = (axyY-15) + "px";
}


//from http://www.java2s.com/Code/JavaScript/Development/Cookiesetdeletegetvalueandcreate.htm
function getCookieVal (offset) {
  var endstr = document.cookie.indexOf (";", offset);
  if (endstr == -1) { endstr = document.cookie.length; }
  return unescape(document.cookie.substring(offset, endstr));
  }

//from http://www.java2s.com/Code/JavaScript/Development/Cookiesetdeletegetvalueandcreate.htm
function getCookie (name) {
  var arg = name + "=";
  var alen = arg.length;
  var clen = document.cookie.length;
  var i = 0;
  while (i < clen) {
    var j = i + alen;
    if (document.cookie.substring(i, j) == arg) {
      return getCookieVal (j);
      }
    i = document.cookie.indexOf(" ", i) + 1;
    if (i == 0) break;
    }
  return null;
  }
