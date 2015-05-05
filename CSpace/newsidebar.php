<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
    {
			$projectID = $base->getProjectID();
      $projectTitle = $_SESSION['CSpace_projectTitle'];
	  }
		else {
			$query = "SELECT projects.projectID, title FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
      $projectTitle = $line['$title'];
		}

    if ($projectTitle=="")
        $projectTitle="Default";

    if (($projectID > 0)&&($projectTitle != ""))
    {
        require_once "chat/src/phpfreechat.class.php"; // adjust to your own path
        //echo $projectID." - ".$projectTitle;
        $params["serverid"] = md5(__FILE__);
        /*$params["container_type"] = "Mysql";
        $params["container_cfg_mysql_host"] = "localhost";
        $params["container_cfg_mysql_database"] = "shahonli_coagmento";
        $params["container_cfg_mysql_username"] = "shahonli_super";
        $params["container_cfg_mysql_password"] = "superman-2010!";*/
        $params["nick"] = $_SESSION['userName'].$userID; //$_POST['nickname'];
        $params["title"] = "Coagmento";
        $params["display_ping"] = FALSE;
        $params["displaytabclosebutton"] = FALSE;
        $params["showwhosonline"] = FALSE;
        $params["btn_sh_whosonline"] = TRUE;
        $params["displaytabimage"]= FALSE;
        $params["height"]= "180px";
        $params["startwithsound"] = TRUE;
        $params["max_text_len"] = 5000;
        $params["timeout"] = 10000;
        $params["date_format"] = "m/d/Y";
        $params["time_format"] = "H:i";
        $params["short_url_width"] = 20;
        $params["showsmileys"] = FALSE;
        //$params["connect_at_startup"] = FALSE;
        //$params["frozen_channels"] = array();
        //$params["frozen_channels"] = array($projectTitle.$projectID);
        $params["channels"] = array($projectTitle.$projectID);
        $params["dyn_params"] = array("nick","frozen_channels");
        $params["max_channels"] = 1;
        //$params['frozen_nick'] = true;
        $params["max_msg"] = 0;
        $params["max_nick_len"]   = 20;
        $params['admins'] = array('admin'  => 'soportechatSummer2011');
        $params['skip_proxies'] = array('censor','noflood');
        $chat = new phpFreeChat($params);
    }
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

        <link rel="Coagmento icon" type="image/x-icon" href="../img/favicon.ico">
        <link rel="stylesheet" type="text/css" href="assets/ajaxtabs/ajaxtabs.css" />
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/tabview/assets/skins/sam/tabview.css" />
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/connection/connection-min.js"></script>
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/element/element-min.js"></script>
	<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/tabview/tabview-min.js"></script>

	<script type="text/javascript" src="js/utilities.js"></script>
	<script type="text/javascript" src="assets/ajaxtabs/ajaxtabs.js"></script>

	<script type="text/javascript">

/***********************************************
* Ajax Tabs Content script v2.2- � Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

    setInterval ("reload('sidebarComponents/snippets.php','snippetsBox')", 60000);
		setInterval ("reload('sidebarComponents/bookmarks.php','pagesBox')", 60000);
		setInterval ("reload('sidebarComponents/searches.php','queriesBox')", 60000);
    setInterval ("reload('sidebarComponents/files.php','filesBox')", 60000);
		setInterval ("refresh()", 5000);
		setInterval ("getNotifications()", 10000);

 /*******************************************************/
                //Verify from where the sidebar was loaded. If the trigger was the login page, then the main content page is reloaded in order to update the toolbar.
                if (gup("flagLogin")=="true")
                     window._content.location = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/"; //Due to Firefox security policies, it cannot load pages tha are out of the domain. It is possible, however, changing the properties

		var projID = 0;

		function initialize() {
			// Get the active project
			req = new phpRequest("http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/checkStatus.php");
			req.add('version','201');
			req.add('object','chat');
			var response = req.execute();
			var res = response.split(":");
			if (res[0]>0)
				projID = res[1];
		}

		function loadAll() {
//			ajaxpage('sidebarChat.php','chat');
			//ajaxpage('collabOnline.php', 'collabOnline');
			//var chatMessages = document.getElementById('chatMessages');
			//chatMessages.scrollTop = chatMessages.scrollHeight;
		}

		function refresh() {
			ajaxpage("currentProj.php", 'currentProj');
			req = new phpRequest("http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/checkStatus.php");
			req.add('version','201');
			req.add('object','chat');
			var response = req.execute();
			var res = response.split(":");
			if (res[0]>0) {
				if (projID!=res[1])
                                    location.reload(true);
			}
			/*else {
				// Status for chat
				req = new phpRequest("http://www.coagmento.org/CSpace/objectStatus.php");
				req.add('version','201');
				req.add('object','chat');
				var response = req.execute();
				if (response==1) {
					//ajaxpage('chatList.php','chatMessages');
					//var chatMessages = document.getElementById('chatMessages');
					//chatMessages.scrollTop = chatMessages.scrollHeight;
				}
			}	*/
		}

		function getNotifications() {
			ajaxpage('notifications.php','notifications');
		}

		function addAction (action, value) {
			req = new phpRequest("http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/addAction.php");
			req.add('action', action);
			req.add('value', value);
			var response = req.execute();
		}
	</script>
	<title>Coagmento Sidebar</title>
        <link rel="stylesheet" href="assets/css/stylesSidebarFusion.css" type="text/css" />
	<style type="text/css">
                    .cursorType{
                            cursor:pointer;
                            cursor:hand;
                    }

        </style>
    </head>

<body onload="initialize();refresh();getNotifications();" background=#FFFFFF>
          <?php
	?>
<table class="body">
	<tr>
		<td>
				<span style="font-size:10px;"><div id="currentProj"></div></span>
			<ul class="acc2" id="acc2">

				<?php
					$query1 = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-chat'";
					$results1 = $connection->commit($query1);
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$value = $line1['value'];
					if (!$value || $value=='on') {
				?>
				<li>
					<h4><img src="../img/chat.jpg" width=36 style="vertical-align:middle;border:0" /> Chat<br/><span style="color:gray;font-size:10px;">Chat with collaborators of the active project.</span></h4>
					<div class="acc-section2">
						<div id="chat" class="acc-content2">
							<?php
                if (($projectID > 0)&&($projectTitle != ""))
                {
                     $chat->printChat();

                }
                 else
                 {
                     echo "In order to use the chat you must select a project first.";
                 }
							?>
						</div>
					</div>
				</li>
				<?php
					}
				?>

				<?php
					$query1 = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-history'";
					$results1 = $connection->commit($query1);
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$value = $line1['value'];
					if (!$value || $value=='on') {
				?>
				<li>
					<h4><img src="../img/history.jpg" width=32 style="vertical-align:middle;border:0" />&nbsp; History<br/><span style="color:gray;font-size:10px;">See personal/shared history and objects.</span></h4>
					<div class="acc-section2">
						<div id="history" class="acc-content2">
							<?php
								//require_once("sidebarHistory.php");
							?>
               <ul id="tabs" class="shadetabs">
                      <li><a href="sidebarComponents/snippets.php" rel="tabscontainer" class="selected">Snipppets</a></li>
                      <li><a href="sidebarComponents/bookmarks.php" rel="tabscontainer">Bookmarks</a></li>
                      <li><a href="sidebarComponents/searches.php" rel="tabsycontainer">Searches</a></li>
                      <li><a href="sidebarComponents/files.php" rel="tabsycontainer">Files</a></li>
<!--                  <li><a href="sidebarComponents/sidebarNotepad" rel="tabsycontainer">Notepad</a></li>-->

              </ul>
              <div id="tabsdivcontainer" style="border:1px solid gray; width:285px; margin-bottom: 1em; padding: 10px">  </div>
              <script type="text/javascript">
                  var tabs=new ddajaxtabs("tabs", "tabsdivcontainer");
                  tabs.setpersist(true);
                  tabs.setselectedClassTarget("link"); //"link" or "linkparent"
                  tabs.init();
              </script>
						</div>
					</div>

                                </li>


				<?php
					}
				?>

				<?php
					$query1 = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notepad'";
					$results1 = $connection->commit($query1);
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$value = $line1['value'];
					if (!$value || $value=='on') {
				?>
				<li>
					<h4><img src="../img/notepad.jpg" width=30 style="vertical-align:middle;border:0" />&nbsp;&nbsp; Notepad<br/><span style="color:gray;font-size:10px;">Write/share notes for the active project.</span></h4>
					<div class="acc-section2">
						<div id="notepad" class="acc-content2">
							<?php
								require_once("sidebarNotepad.php");
							?>
						</div>
					</div>
				</li>
				<?php
					}
				?>

				<?php
					$query1 = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notifications'";
					$results1 = $connection->commit($query1);
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$value = $line1['value'];
					if (!$value || $value=='on') {
				?>
				<li>
					<h4><img src="../img/notification.jpg" width=38 style="vertical-align:middle;border:0" /> Notifications<br/><span style="color:gray;font-size:10px;">Recent actions of your collaborators.</span></h4>
					<div class="acc-section2">
						<div id="notifications" class="acc-content2">
							No notifications available.
						</div>
					</div>
				</li>
				<?php
					}
				?>
			</ul>
		</td>
	</tr>
</table>

<script type="text/javascript" src="assets/js/script.js"></script>

<script type="text/javascript">

var parentAccordion=new TINY.accordion.slider("parentAccordion");
parentAccordion.init("acc2","h4",0,-1);

var nestedAccordion=new TINY.accordion.slider("nestedAccordion");
nestedAccordion.init("nested","h4",1,-1,"acc-selected");

</script>
<?php

	} // if (isset($_SESSION['CSpace_userID']))
	else {
		echo "Your session has expired. Please <a href=\"http://".$_SERVER['HTTP_HOST']."/loginOnSideBar.php\" target=_self style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</a> again.\n";
	}
?>

</body>
</html>
