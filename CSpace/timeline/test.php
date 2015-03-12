<?php

require_once("../connect.php"); 
$userID = 2;


$sql="SELECT DISTINCT date FROM pages WHERE userID=2"; 
$result=mysql_query($sql); 

$options=""; 

while ($row=mysql_fetch_array($result)) { 

    $date=$row["date"];  
    $options.="<OPTION VALUE=\"$date\">".$date; 
} 
?> 

<SELECT NAME=date> 
<OPTION VALUE=0>Choose 
<?=$options?> 
</SELECT> 

