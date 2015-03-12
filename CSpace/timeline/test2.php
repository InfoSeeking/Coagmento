
 <style>
p { background:#dad;
font-weight:bold;
font-size:16px; }
</style>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
  <button>Toggle 'em</button>

<p>Hiya</p>
<p>Such interesting text, eh?</p>
<script>
$("button").click(function () {
$("p").toggle("slow");
});    
</script>


<?php

require_once("../connect.php"); 
$userID = 2;

// Thumbnail Query
$sql_thumb="SELECT * FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2 AND projectID=8 order by date desc";
$result_thumb = mysql_query($sql_thumb) or die(" ". mysql_error());



while ($row = mysql_fetch_array($result_thumb, MYSQL_ASSOC)) {
	$title = $row['title'];
	$url = $row['url'];
	$date = $row['date'];
	$month = date("m",strtotime($date));
	$year = date("Y",strtotime($date));
	$thumb = $row['fileName'];


echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/';
	echo $thumb;
	echo '" width="55" height="55" />';
}



$sql="SELECT DISTINCT date FROM pages WHERE userID=2"; 
$result=mysql_query($sql); 
$array=array();
$y=array();
$m=array();
$d=array();

while ($row=mysql_fetch_array($result)) { 
	$date = $row['date'];
	if (!in_array($date, $array)){ $array[] = $date; }
	
	$date = explode("-", $row['date']);				
	$year = $date[0];
	$month = $date[1];
	$day = $date[2];

	if (!in_array($year, $y)){ $y[] = $year; }
	if (!in_array($month, $m)){ $m[] = $month; }
	if (!in_array($day, $d)){ $d[] = $day; }
}

echo '<form>';

echo 'Date: <select name="date">';
for ($i = 0; $i < count($array); ++$i) {
	echo '<option value="'; echo $array[$i]; echo '">'; echo $array[$i]; echo '</option>';
}
echo '</select>';
echo '</form>';
	
sort($y);
sort($m);
sort($d);
	
echo '<form>';

echo 'Year: <select name="year">';
for ($i = 0; $i < count($y); ++$i) {
	echo '<option value="'; echo $y[$i]; echo '">'; echo $y[$i]; echo '</option>';
}
echo '</select>&nbsp;&nbsp;';

echo 'Month: <select name="month">';
for ($i = 0; $i < count($m); ++$i) {
	echo '<option value="'; echo $m[$i]; echo '">'; echo $m[$i]; echo '</option>';
}
echo '</select>&nbsp;&nbsp;';

echo 'Day: <select name="day">';
for ($i = 0; $i < count($d); ++$i) {
	echo '<option value="'; echo $d[$i]; echo '">'; echo $d[$i]; echo '</option>';
}
echo '</select>';

echo '</form>&nbsp;&nbsp;';
    
?>