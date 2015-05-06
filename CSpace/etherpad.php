<?php
  session_start();
  require_once('./core/Base.class.php');
  require_once("./core/Connection.class.php");
  require_once("./core/Util.class.php");
  require_once("services/utilityFunctions.php");
  $base = Base::getInstance();
  $connection = Connection::getInstance();
  if ((isset($_SESSION['CSpace_userID'])))
    {
        $userName = $_SESSION['userName'];
        $title = "project".$base->getProjectID();
        if ($_SESSION['CSpace_projectID']=="")
        { echo
                "<script>alert('In order to open the editor, you must first select a project from your CSpace'); window.location.href='http://".$_SERVER['HTTP_HOST']."/CSpace/projects.php';</script>";
        } else {
          $userID = $base->getUserID();
          $projectID = $base->getProjectID();
          $ip=$base->getIP();

          addPoints($userID,20);

          $timestamp = $base->getTimestamp();
  				$date = $base->getDate();
  				$time = $base->getTime();

          $action = 'editor';
          $value = $title;

          Util::getInstance()->saveAction("$action","$value",$base);

          header("Location: http://coagmentopad.rutgers.edu/$title?nickname=$userName");
          exit;
       }
    }
    else {
            echo "Your session has expired. Please <a href=\"http://www.coagmento.org/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";

         }

?>
