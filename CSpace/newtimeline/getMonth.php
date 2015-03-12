<?
  include("functions.inc.php");
?>

<?php

$month = $_GET['month'];

 session_start();
    echo("1");
    if(session_is_registered('myvar'))
    {
        echo("2");
       if($_SESSION['myvar'] == 'myvalue')
       {
           echo("3");
           exit;
       }
    }

?>