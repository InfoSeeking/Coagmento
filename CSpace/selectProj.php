<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {

               function redirect($loc){
                    echo "<script>window.document.location='".$loc."';</script>";
                }


?>
<?php
	require_once("connect.php");
    require_once("insertAction.php");
		require_once('./core/Base.class.php');
		require_once("./core/Connection.class.php");
		require_once("./core/Util.class.php");
		$base = Base::getInstance();
		$connection = Connection::getInstance();

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

	$query = "SELECT * FROM memberships WHERE projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$cUserID = $line['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$uName = $line1['firstName'] . " " . $line1['lastName'];

	}
        Util::getInstance()->saveAction("switch_project","$projectID",$base);

        redirect('http://'.$_SERVER['HTTP_HOST'].'/CSpace/projects.php');
        //refresh();
        //echo "<script>location.reload(true);</script>";
        }
?>
