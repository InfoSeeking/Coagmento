<?php
/**
 * connection settings
 * replace with your own hostname, database, username and password
 */
$hostname_conn = "localhost";
$database_conn = "coagmento-org";
$username_conn = "root";
$password_conn = "";
$conn = mysql_pconnect($hostname_conn, $username_conn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conn, $conn);
mysql_query("SET NAMES 'utf8'");
?>
