<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>

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
	include('func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Points</h3>

<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $base->getUserID();
		$query = "SELECT points FROM users WHERE userID='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$totalPoints = $line['points'];
		$query = "SELECT count(distinct value) as num FROM actions WHERE userID='$userID' AND action='download'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p1 = $line['num']*100;

		$query = "SELECT * FROM actions WHERE userID='$userID' AND action='demographic'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		if (mysql_num_rows($results)!=0)
			$p2 = 100;
		else
			$p2 = 0;

		$query = "SELECT * FROM actions WHERE userID='$userID' AND action='pre-study'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		if (mysql_num_rows($results)!=0)
			$p3 = 100;
		else
			$p3 = 0;

		$query = "SELECT count(distinct action,value) as num FROM actions WHERE userID='$userID' AND (action='mid-study' OR action='end-study')";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p4 = $line['num']*500;


		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='page'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p6 = $line['num'];

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='save'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p7 = $line['num']*10;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='recommend'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p8 = $line['num']*10;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='add-annotation'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p9 = $line['num']*10;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='save-snippet'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p10 = $line['num']*10;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='login'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p11 = $line['num']*10;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='create-project'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p12 = $line['num']*100;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='add-collaborator'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p13 = $line['num']*100;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='recommend-coagmento'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p14 = $line['num']*100;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='join-coagmento'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p15 = $line['num']*200;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='chat'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p16 = $line['num']*5;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND (action='sidebar-page' OR action='sidebar-query' OR action='sidebar-query-snapshot' OR action='sidebar-snippet')";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p17 = $line['num']*5;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='create-note'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p18 = $line['num']*20;

		$query = "SELECT count(*) as num FROM actions WHERE userID='$userID' AND action='print-count'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$p19 = $line['num']*100;
?>
<table class="body" width=100%>
	<tr><td colspan=3>Everytime you use Coagmento (toolbar, sidebar, or CSpace), you earn points. These points are explained below along with the points you have gained so far.</td></tr>
	<tr><td colspan=3><br/></td></tr>
	<tr><td style="font-weight:bold;" align=center>Action</td><td style="font-weight:bold;" align=center>Award</td><td style="font-weight:bold;" align=center>Earned</td></tr>
	<tr bgcolor="#EFEFEF"><td>Fill in demographic information (once only)</td><td align=right>100</td><td align=right><?php echo $p2;?></td></tr>
	<tr><td>Pre-project questionnaire (once only)</td><td align=right>100</td><td align=right><?php echo $p3;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>End study questionnaires</td><td align=right>500</td><td align=right><?php echo $p4;?></td></tr>
	<tr><td>Login (connect to) Coagmento (max once per hour)</td><td align=right>10</td><td align=right><?php echo $p11;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Visit a webpage when connected to Coagmento</td><td align=right>1</td><td align=right><?php echo $p6;?></td></tr>
	<tr><td>Bookmark a webpage</td><td align=right>10</td><td align=right><?php echo $p7;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Recommend a webpage</td><td align=right>10</td><td align=right><?php echo $p8;?></td></tr>
	<tr><td>Annotate on a webpage</td><td align=right>10</td><td align=right><?php echo $p9;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Collect a snippet/object</td><td align=right>10</td><td align=right><?php echo $p10;?></td></tr>
	<tr><td>Create a new project</td><td align=right>100</td><td align=right><?php echo $p12;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Add a collaborator</td><td align=right>100</td><td align=right><?php echo $p13;?></td></tr>
	<tr><td>Recommend Coagmento to a non-user</td><td align=right>100</td><td align=right><?php echo $p14;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Recommender joins Coagmento</td><td align=right>200</td><td align=right><?php echo $p15;?></td></tr>
	<tr><td>Chat message</td><td align=right>5</td><td align=right><?php echo $p16;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Reuse an item from your history</td><td align=right>5</td><td align=right><?php echo $p17;?></td></tr>
	<tr><td>Write a note</td><td align=right>20</td><td align=right><?php echo $p18;?></td></tr>
	<tr bgcolor="#EFEFEF"><td>Print a report (max one per day)</td><td align=right>100</td><td align=right><?php echo $p19;?></td></tr>
	<tr><td colspan=3><hr/></td></tr>
	<tr><td colspan=2 style="font-weight:bold;">Total points earned</td><td style="font-weight:bold;" align=right><?php echo $totalPoints;?></td></tr>
</table>
<?php
	}
?>

</body>
</html>
