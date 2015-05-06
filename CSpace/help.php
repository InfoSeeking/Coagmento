<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('links_header.php');
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('services/func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Help</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "<br/><br/>Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>
<script type="text/javascript" src="js/utilities.js"></script>
<table class="body" width=100%>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hStart');" style="cursor:pointer;"><span style="font-weight:bold">Getting started with Coagmento</span> (click here to show/hide)</div></td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>
		<div id="hStart" style="text-align:left;font-size:12px;">
		<ol>
			<li><a href="../getToolbar.php">Download</a> the latest Coagmento plugin for Firefox. Save the file with .xpi extension.</li>
			<li>Drag and drop this .xpi file to an open window of Firefox.</li>
			<li>Restart Firefox to make the plugin active.</li>
			<li>Open the Coagmento toolbar in Firefox (View->Toolbars->Coagmento Toolbar).</li>
			<li>Click on 'Connect' button to login to Coagmento. Once logged in, clicking on 'Home' will take you to your CSpace - your online space in Coagmento.</li>
			<li>Once logged in, you can start using Coagmento's many functionalities, including bookmarking a page, collecting snippets, and making annotations on webpages, using the toolbar.</li>
			<li>With you connected with Coagmento, it will record all the webpages that you go to and the searches you do. Coagmento never records your passwords or any other information you ever fill in any form.</li>
			<li>You can also open the Coagmento sidebar in Firefox (View->Sidebar->Coagmento Sidebar), which keeps track of your history, lets you chat with your teammates, and provide a notepad to take personal or shared notes for the project you are working on.</li>
			<li>Whenever you are logged in to Coagmento, you have an active project. By default, this is called 'Default'. You can create new projects using your CSpace. Your active project at a given point is shown in the toolbar. You can change your active project by visiting your CSpace (simply click on 'Home' button on the toolbar at any time).</li>
		</ol>
		</div>
	</td></tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hProj');" style="cursor:pointer;"><span style="font-weight:bold">Working with projects</span> (click here to show/hide)</div></td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
			<div id="hProj" style="display:none;text-align:left;font-size:12px;">
			Coagmento allows you to have as many projects as you like. You should always have an active project while working with Coagmento as all your actions with Coagmento (sites visited, bookmarked, searched done, etc.) needs to be recorded under a project. By default, your active project is called 'Default'. If you have your Coagmento toolbar and/or sidebar open, they will show you what your active project is.<br/>
			You can create a new project by clicking on your profile picture in the top-right corner and selecting 'Create' under 'Projects' from there. To see the list of your projects, click 'Select' instead. On this page, you can click on a project name to see more information about that project. Here, you can also select that project to be your active project. Make sure to refresh you CSpace after changing your active project.
			</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hCollab');" style="cursor:pointer;"><span style="font-weight:bold">Working with collaborators</span> (click here to show/hide)</div></td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
		<div id="hCollab" style="display:none;text-align:left;font-size:12px;">
		<p>You can add other users of Coagmento as your collaborators to your existing projects. To do so, first make sure you have the right project selected as your active project. Then click on your profile picture in the top-right corner and click 'Add' under 'Collaborators'. Enter his/her username and voila! You will have a collaborator in that project! Make sure to refresh your CSpace.</p>
		<p>Once someone becomes your collaborator for a project, he/she can see things you (and others) have done for that project. This includes your browsing and searching history, annotations on webpages, snippets, and shared notes. Remember, just because someone is your collaborator, it doesn't mean he/she can have access to everything about you. Anything that you do with Coagmento goes under a project, which means you decide when you want to work on a certain project that you share with others.</p>
		<p>Whatever you do, you always have access to data about/by you that you can delete if you want. Coagmento respects your privacy and provides many ways to protect it.</p>
		</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hData');" style="cursor:pointer;"><span style="font-weight:bold">Working with collected data</span> (click here to show/hide)</td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
		<div id="hData" style="display:none;text-align:left;font-size:12px;">
		<p>When you are working on a project that goes beyond a single sitting or session, you may want to keep track of things you have already done for the project and know where you should go from there. Coagmento makes it easy for you to do this. Once you connect to Coagmento, it starts recording the websites you go to and the searches you do on Google etc., under the currently active project (shown on the toolbar as well). Remember, Coagmento DOES NOT record passwords or any other sensitive information. In fact, the only keyboard entries it records are the searches done on major search engines (Bing, Google, Yahoo!, Wikipedia).</p>
		<p>When you are ready to come back, you can visit your CSpace by clicking 'Coagmento CSpace' at the top of any Coagmento webpage. This will bring up visual listing of a variety of data (webpages, searches, snippets, annotations, etc.) collected by/about you. You can search into this data, filter it by project, sessions, and objects. You can also delete the data you don't want to save.</p>
		</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hReport');" style="cursor:pointer;"><span style="font-weight:bold">Creating reports</span> (click here to show/hide)</td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
		<div id="hReport" style="display:none;text-align:left;font-size:12px;">
		<p>Coagmento lets you prepare research reports based on your browsing, searching, and data collection (snippets, annotations, etc.). You can do so by accessing the 'Editor' from 'Workspace' menu (click on your profile picture in the top-right corner). Or you can click on 'Print reports' and decide what you want to print (bookmarks, searches, snippets, annotations, or all).</p>
		</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hSetting');" style="cursor:pointer;"><span style="font-weight:bold">Updating profile and changing settings</span> (click here to show/hide)</div></td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
			<div id="hSetting" style="display:none;text-align:left;font-size:12px;">
			<p>You can update your profile by clicking 'Profile' in 'Settings' menu. Here, you can change your password, profile picture, and other details about you.</p>
			<p>There are also some other things that you can configure using 'Options' in the same menu.</p>
			</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><div onclick="switchMenu('hLeave');" style="cursor:pointer;"><span style="font-weight:bold">Leaving Coagmento</span> (click here to show/hide)</div></td></tr>
	<tr><td>&nbsp;&nbsp;</td>
		<td>
			<div id="hLeave" style="display:none;text-align:left;font-size:12px;">
			You can uninstall the Firefox plugin by selecting 'Uninstall' from Firefox's menu Tools->Add-ons.<br/>
<!-- 			If you want to delete the data collected about/by you (other than the log data) using Coagmento, login to your CSpace and expand 'Data & Information' panel on the left. There, you can see the data about you and then delete whatever you want. -->
			</div>
		</td>
	</tr>
	<tr><td colspan=2><hr/></td></tr>
</table>
<?php
	}
?>

</body>
</html>
