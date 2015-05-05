<div id="topbar">
	<!-- logo -->
	<div class="left" style="float: left; ">
        <h2><a href="index.php?displayMode=timeline&projects=all&objects=all&years=all&months=all&formSubmit=Submit">Coagmento CSpace</a></h2><br/>
        <p id="getToolbar">Get Toolbar: <a href="../getToolbar.php">Firefox</a> <a href="https://chrome.google.com/webstore/search/coagmento" target="_blank">Chrome</a></p>
    </div>


    <!-- user info -->
    <div style="float: left;">
		<?php
        session_start();
        require_once('../connect.php');
        $userID = $_SESSION['CSpace_userID'];
        $projectID = $_SESSION['CSpace_projectID'];

		if (!(isset($_SESSION['CSpace_projectID'])) || $projectID==0) {
			$query = "select projects.projectID from projects,memberships where memberships.userID=$userID and projects.projectID=memberships.projectID and projects.title='Default' and memberships.access=1";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}

        $query = "SELECT * FROM users WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$projectID = $line1['projectID'];
		$title = $line1['title'];
        /* <td><img src=\"../../img/$avatar\" width=45 height=45 style=\"vertical-align:middle;border:0\" /></td> */
        echo "<div class='top_links' style='border-left: 1px solid #ccc; padding-left: 15px;'><table style='font-size: 12px;'><tr><td valign=\"middle\">&nbsp;&nbsp;Welcome, <span style=\"font-weight:bold\">$userName</span> to your <a href='index.php?displayMode=timeline&projects=all&objects=all&years=all&months=all&formSubmit=Submit'>CSpace</a><br/>&nbsp;&nbsp;Current login: $lastLogin<br/>&nbsp;&nbsp;Points earned: <a href='points.php'>$points</a></td><td valign=\"middle\">&nbsp;&nbsp;</td><td valign=\"top\">&nbsp;&nbsp;You have <a href='projects.php?userID=$userID'>$projectNums projects</a> and <a href='collaborators.php?userID=1'>$collabNums collaborators</a><br/>&nbsp;&nbsp;Current project: <strong>$title</strong><br/>&nbsp;&nbsp;<a href='projects.php?userID=$userID'>Select a different project</a></td></tr></table></div>";
        ?>
    </div>

    <!-- menu -->
    <div class="right" style="position: fixed; top: 25px; right: 20px;">
    	<p class="flip" style="float: right;"><?php echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/img/'.$avatar.'" width=45 height=45 style="vertical-align:middle;border:3px solid #000;">'; ?><br/><img src="assets/img/arrow.png"/></p>
        <div style="clear:both;"></div>
        <div class="panel">
        	<table>
            	<tr>
                	<td valign="top" width="150">
                    	<b>Collaborators</b><br/>
                        <a href="addCollaborator.php">Add</a>
                        <a href="collaborators.php">View</a><br/>

                        <b>Projects</b>
                        <a href="createProject.php">Create</a>
                        <a href="projects.php">Select</a>
                        <a href="showPublicProjs.php">Join</a>
                    </td>
                	<td valign="top" width="150">
                    	<b>Sharing</b>
                        <a href="showRecommendations.php">Recommendations</a>
                        <a href="interProject.php">Inter-project</a><br/>

                   		<b>Workspace</b>
                        <a href="etherpad.php">Editor</a>
                        <a href="files.php">Files</a>
                        <a href="printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                    	<b>Settings</b>
                        <a href="profile.php">Profile</a>
                        <a href="settings.php">Settings</a><br/>

                    	<a href="help.php"><font color=green>Help</font></a><br/>
											<a href="../login.php?logout=true"><font color=red>Log out</font></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
