<?php
$host = "localhost";
$username = "root";
$password = "";
// // $username = "www-coagmento";
// $password = "znuq7H,";
$database = "coagmento-org";
$dbh = mysql_connect($host,$username) or die("Cannot connect to the database: ". mysql_error());
$db_selected = mysql_select_db($database) or die ('Cannot connect to the database: ' . mysql_error());
?>
