<?php
  session_start();
  include("../services/functions.inc.php");

  $month = $_GET['month'];


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
