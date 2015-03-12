<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Coagmento</title>
<link rel="Coagmento icon" href="../img/favicon.ico">
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<script language="javascript">
	var state = 'none';

	function showhide(layer_ref) {

	if (state == 'block') {
	state = 'none';
	}
	else {
	state = 'block';
	}
	if (document.all) { //IS IE 4 or 5 (or 6 beta)
	eval( "document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
	document.layers[layer_ref].display = state;
	}
	if (document.getElementById &&!document.all) {
	hza = document.getElementById(layer_ref);
	hza.style.display = state;
	}
	}
	
	function checkUncheckAll(theElement) 
	{
		var theForm = theElement.form, z = 0;
		for(z=0; z<theForm.length;z++)
		{
	    	if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
			{
		  		theForm[z].checked = theElement.checked;
		  	}
	    }
	}

	function makeSelection()
	{
		flag = document.getElementById("selection").value;
		switch(flag)
		{
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
</script>
<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=450,height=450,scrollbars=yes');
return false;
}
//-->
</SCRIPT>
</head>

<body class="body">
<center>
<div id="container">
  <div id="mainContent">
  <table align="center">
  	<tr>
    <td valign="bottom" align="left">
    	<span class="menu">Coagmento</span>  
<!--       	<div class="submenu"><font color="#555555">'cause two (or more) heads are better than one!</font></div> -->
    </td>
    </tr>
  </table>
  </div> <!-- end #maincontainer -->
</div><!-- end #container -->
