<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {

               function redirect($loc){
                    echo "<script>window.document.location='".$loc."';</script>";
                }
//
//                function refresh(){
//                    echo "<script>location.reload(true);</script>";
//                }


?>
<!--        <script type="text/javascript">
                window._content.location = "http://www.coagmento.org/CSpace/index.php?project";
        </script>
                <table class="body" width=100%>
                <tr><td align="right"><img src="../img/projects.jpg" height=50 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:20px">Projects</span></td></tr>
                <tr bgcolor="#EFEFEF">
                        <td>
                                <span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
                        </td>
                </tr>
                <tr>
                        <td>
                                <div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
                                You have selected this project as your active project. All your actions will now be recorded under this project (given you have activated Coagmento from the toolbar).
                                </div>
                        </td>
                </tr>
                <tr><td><br/></td></tr>-->
<?php
	require_once("connect.php");
    require_once("insertAction.php");
	$projectID = $_GET['projectID'];
    $projectTitle = $_GET['projectTitle'];
	$_SESSION['CSpace_projectID'] = $projectID;
    $_SESSION['CSpace_projectTitle'] = $projectTitle;
    //setcookie('CSpace_projectID', $projectID);
	//echo "<tr><td><table class=\"style    1\">";
	$query = "SELECT * FROM projects WHERE projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$title = $line['title'];
	$description = $line['description'];
	$startDate = $line['startDate'];
	$startTime = $line['startTime'];

     	// Update the selected project information for this user in the options table
	$userID = $_SESSION['CSpace_userID'];
	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='selected-project'";
	$results = mysql_query($query) or die(" ". mysql_error());
	if (mysql_num_rows($results)==0) {
		$query = "INSERT INTO options VALUES('','$userID','$projectID','selected-project','$projectID')";
	}
	else {
		$query = "UPDATE options SET value='$projectID' WHERE userID='$userID' AND `option`='selected-project'";
	}
	$results = mysql_query($query) or die(" ". mysql_error());

//	echo "<tr><td>Your active project is now <span style=\"font-weight:bold\">$title</span></td></tr>\n";
//	echo "<tr><td><font color=\"gray\">$description</font></td></tr>\n";
//	echo "<tr><td>Started on: $startDate, $startTime</td></tr>\n";
//	echo "<tr><td>Collaborators: ";
	$query = "SELECT * FROM memberships WHERE projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$cUserID = $line['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$uName = $line1['firstName'] . " " . $line1['lastName'];
		//echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showCollaborator.php?userID=$cUserID','content');\">$uName</span>, ";
	}
        insertAction("switch_project",$projectID);
        //header("Location: http://www.coagmento.org/CSpace/index.php?project");
	//echo "</td></tr>\n";
	//echo "</table>\n";
        //echo "HOLA MUNDO";
        redirect('http://'.$_SERVER['HTTP_HOST'].'/CSpace/projects.php');
        //refresh();
        //echo "<script>location.reload(true);</script>";
        }
?>
