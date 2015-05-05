<html>
<body>

<style type="text/css">
	#yearDiv {
		float: left;
		position: relative;
		padding: 10px;
	}
	#monthDiv {
		float: left;
		position: relative;
		padding: 10px;
	}
</style>
<?
  include("functions.inc.php");
?>

<?php 
require_once("../connect.php"); 
$userID = 2;

$sql="SELECT DISTINCT date FROM pages WHERE userID=2 ORDER BY date DESC"; 
$result=mysql_query($sql); 

$options=""; 
$y=array();

while ($row=mysql_fetch_array($result)) { 
    $date=$row["date"];  
	$year = date("Y",strtotime($date));
	
	if (!in_array($year, $y)){ 
		$y[] = $year;
		$options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';  
	}

} 
?> 

<div id="yearDiv">
<form name='yearForm'>
<SELECT id='year' name='year' onChange="filterYear()">
<option value=" " disabled="disabled" selected="selected">Choose year</option>
<?=$options?> 
</SELECT> 
<!-- <input type='button' onclick='filterYear()' value='Query MySQL' /> -->
</div>

<div id='monthDiv'></div>
<div id='dayDiv'></div>
</body>
</html>