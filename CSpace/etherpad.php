<?php 	session_start();
        if ((isset($_SESSION['CSpace_userID'])))
            {
                $userName = $_SESSION['userName'];
                $title = project.$_SESSION['CSpace_projectID'];
                if ($_SESSION['CSpace_projectID']=="")
                { echo
                        "<script>alert('In order to open the editor, you must first select a project from your CSpace'); window.location.href='http://".$_SERVER['HTTP_HOST']."/CSpace/projects.php';</script>";

                } else {
                            require_once("connect.php");
                            $userID = $_SESSION['CSpace_userID'];
                            require_once("utilityFunctions.php")
                            addPoints($userID,20);

                            $timestamp = time();
                            $datetime = getdate();
                            $date = date('Y-m-d', $datetime[0]);
                            $time = date('H:i:s', $datetime[0]);
                            $projectID = $_SESSION['CSpace_projectID'];
                            $userID = $_SESSION['CSpace_userID'];
                            $ip=$_SERVER['REMOTE_ADDR'];
                            $action = 'editor';
                            $value = $title;

                            $query = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', '$action', '$value','$ip')";
                      	    $results = mysql_query($query) or die(" ". mysql_error());

                            header("Location: http://coagmentopad.rutgers.edu/$title?nickname=$userName");
                            exit;
                       }
            }
            else {
                    echo "Your session has expired. Please <a href=\"http://www.coagmento.org/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";

                 }

?>
