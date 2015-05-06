<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="../assets/css/style_timelineview.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Home</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "<br/><br/>Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		require_once("../connect.php");
		$query = "SELECT * FROM users WHERE userID='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$name = $line['firstName'] . " " . $line['lastName'];
		$loginCount = $line['loginCount'];
		$type = $line['type'];
?>
        <table>
        <?php
            if ((preg_match("/HS/", $type)) || (preg_match("/consent/", $type))){
        		// echo "<tr bgcolor=#EFEFEF><td>The Coagmento Beta Testing Study is over and the winners of various prizes have been notified. Thank you for participating.</td></tr>\n";
				echo "<tr bgcolor=#EFEFEF><td>You have signed up for using Coagmento in your school project. If you have also signed the consent form to participate in our beta testing study, you may win $25 iTunes Gift Cards based on the <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('points.php','content');\">points</span> you earn. Check out the <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('studyTerms.php','content');\">details</span>.</td></tr>\n";

                $consent = $type;
                if ($consent!="HS")
                    echo "<tr bgcolor=#EFEFEF><td><span style=\"font-weight:bold\">Important</span>: we still do not have your signed consent form, including the parental permission for participating in this study. Without these two forms, you will not be able to win the prizes, so make sure you get them to us ASAP!</td></tr>\n";
                else {
                    $query = "SELECT * FROM actions WHERE action='demographic' AND userID='$userID'";
                    $results = mysql_query($query) or die(" ". mysql_error());
                    if (mysql_num_rows($results)==0)
                        echo "<tr bgcolor=#EFEFEF><td><span style=\"font-weight:bold\">Important</span>: you still haven't submitted your <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('demographic.php', 'content');\">demographic information</span>. Please do this ASAP to remain qualified for winning the prizes. Click <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('demographic.php', 'content');\">here</span>.</td></tr>\n";
                    $query = "SELECT * FROM actions WHERE action='pre-study' AND userID='$userID'";
                    $results = mysql_query($query) or die(" ". mysql_error());
                    if (mysql_num_rows($results)==0)
                        echo "<tr bgcolor=#EFEFEF><td><span style=\"font-weight:bold\">Important</span>: you still haven't submitted your <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('preStudy.php', 'content');\">pre-project information</span>. Please do this ASAP to remain qualified for winning the prizes. Click <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('preStudy.php', 'content');\">here</span>.</td></tr>\n";
                }

                $query = "SELECT * FROM actions WHERE action='end-study' AND userID='$userID'";
                $results = mysql_query($query) or die(" ". mysql_error());
                if (mysql_num_rows($results)==0)
                    echo "<tr bgcolor=#FFFFCC><td><span style=\"font-weight:bold\">New</span>: Please fill in the <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('endStudy.php', 'content');\">end-study questionnaire</span> to earn <span style=\"font-weight:bold\">500 points</span> and qualify for <span style=\"font-weight:bold\">$25 iTunes Gift Cards</span>.</td></tr>\n";

                echo "<tr><td><hr/></td></tr>\n</table>\n";
            }
            else {
        ?>

            <?php
                $query = "SELECT * FROM actions WHERE action='download' AND userID='$userID' AND value='2.3'";
                $results = mysql_query($query) or die(" ". mysql_error());
                if (mysql_num_rows($results)==0)
                    echo "<img src=\"../img/download.jpg\" height=25px/> <span style=\"color:green;font-weight:bold;\">A new version of Coagmento Firefox plugin is available.</span> Go to the <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('help.php', 'content');\">help page</span> to download it.<br/><br/>\n";
            ?>
        <table class="body" width=100%>
            <tr>
                <td width=60% valign=top>
                    <table class="body" width=100%>
                        <tr><td align=left valign=center><img src="../../img/ring.jpg" height=25px /> <a href='help.php' class='header'>Get Started</a></td></tr>
                        <tr>
                        <td>If you are new to Coagmento, here's a quick-start guide: (1) <a href='createProject.php'>Create a new project</a>, (2) <a href='projects.php?userID=<?php echo $userID;?>'>Select that project as your active project</a>, and (3) <a href='addCollaborator.php'>Invite your friends to join your project</a>. You can only invite those who are also part of Coagmento, so <a href='recommendCoagmento.php'>ask your friends to sign up</a> for their free accounts!
                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan=2><br/></td></tr>
            <tr>
                <td width=60% valign=top>
                    <table class="body" width=100%>
                        <tr><td align=left valign=center><img src="../../img/updates.jpg" height=25px /> <a href='updates.php' class='header'>Updates</a></td></tr>
                        <tr>
                        <?php
                            $query1 = "SELECT lastActionTimestamp FROM users WHERE userID='$userID'";
                            $results1 = mysql_query($query1) or die(" ". mysql_error());
                            $line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
                            $lastActionTimestamp = $line1['lastActionTimestamp'];

                            $query2 = "SELECT count(*) as num FROM memberships,actions WHERE actions.projectid=memberships.projectid AND actions.userid!='$userID' and memberships.userid='$userID' AND actions.timestamp>='$lastActionTimestamp' AND actions.action='page'";
                            $results2 = mysql_query($query2) or die(" ". mysql_error());
                            $line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
                            $numPages = $line2['num'];

                            $query2 = "SELECT count(*) as num FROM memberships,actions WHERE actions.projectid=memberships.projectid AND actions.userid!='$userID' and memberships.userid='$userID' AND actions.timestamp>='$lastActionTimestamp' AND actions.action='query'";
                            $results2 = mysql_query($query2) or die(" ". mysql_error());
                            $line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
                            $numQueries = $line2['num'];

                            $query2 = "SELECT count(*) as num FROM memberships,actions WHERE actions.projectid=memberships.projectid AND actions.userid!='$userID' and memberships.userid='$userID' AND actions.timestamp>='$lastActionTimestamp' AND actions.action='save-snippet'";
                            $results2 = mysql_query($query2) or die(" ". mysql_error());
                            $line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
                            $numSnippets = $line2['num'];

                            $query2 = "SELECT count(distinct actions.projectID) as num FROM memberships,actions WHERE actions.projectid=memberships.projectid AND actions.userid!='$userID' and memberships.userid='$userID' AND actions.timestamp>='$lastActionTimestamp'";
                            $results2 = mysql_query($query2) or die(" ". mysql_error());
                            $line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
                            $numProj = $line2['num'];

                        ?>
                            <td>Since your last login, your collaborators viewed <span style="font-weight:bold;"><?php echo $numPages;?> webpages</span>, ran <span style="font-weight:bold;"><?php echo $numQueries;?> searches</span>, and saved <span style="font-weight:bold;"><?php echo $numSnippets;?> snippets</span>. Click <a href='updates.php'>here</a> for details.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td colspan=2><br/></td></tr>
            <tr>
                <td width=60% valign=top>
                    <table class="body" width=100%>
                        <tr><td align=left valign=center><img src="../../img/help.jpg" height=25px /> <a href='help.php' class='header'>Help</a></td></tr>
                        <tr>
                            <td>
                                Coagmento is a system, which includes a <span style="font-weight:bold;">plugin</span> that you install in your Firefox browser, and <span style="font-weight:bold;">CSpace</span>, your online space to manage your online information gathered using the plugin.<br/>
                                You are in your CSpace. To start using Coagmento, make sure you have the <a href='help.php'>Firefox plugin</a>. More instructions can be found on the <a href='help.php'>help page</a>.
                            </td>
                        </tr>
                        <tr><td><br/></td></tr>
                        <tr>
                            <td>
                                Want to create a new project? Expand the 'Projects' tab on the left. Want to add a collaborator? Expand the 'Collaborators' tab on the left. Optionally, you can also click on the projects or collaborators numbers reported at the top here.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<?php
		}
	}
?>

</body>
</html>
