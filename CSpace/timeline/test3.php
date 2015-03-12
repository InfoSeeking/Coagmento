<?php

require_once("../connect.php"); 
$userID = 2;

$sql="SELECT DISTINCT date FROM pages WHERE userID=2"; 
$result=mysql_query($sql); 
$array=array();
$y=array();
$m=array();
$d=array();

while ($row=mysql_fetch_array($result)) { 
	$date = $row['date'];
	if (!in_array($date, $array)){ $array[] = $date; }
}

echo '<form>';

echo 'Date: <select name="date" ONCHANGE="location = this.options[this.selectedIndex].value;">';
for ($i = 0; $i < count($array); ++$i) {
	echo '<option value="#'; echo $array[$i]; echo '">'; echo $array[$i]; echo '</option>';
}
echo '</select>';
echo '</form>';

?>

<div style="height: 800px;"></div>

<a name="2009-04-27">Test</a>