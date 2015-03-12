<?php
	session_start();
	if ((isset($_SESSION['CSpace_userID']))) {
		$type = $_GET['type'];
		$itemID = $_GET['itemID'];
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		//$value = $_GET['value'];
		require_once("../connect.php");
                $query1 = "";

                //It can be reduced to one line using the variable type to refer to each table, but just to make clear this it is presented with a condition.
                if ($type=="snippets")
                    $query1 = "UPDATE snippets SET `status`='0' WHERE `snippetID`='$itemID' AND `userID`='$userID' AND `projectID`='$projectID'";
                else 
                     if ($type=="pages")
                        $query1 = "UPDATE pages SET `result`='0' WHERE `pageID`='$itemID' AND `userID`='$userID' AND `projectID`='$projectID'";
                     else
                         if ($type=="queries")
                            $query1 = "UPDATE queries SET `status`='0' WHERE `queryID`='$itemID' AND `userID`='$userID' AND `projectID`='$projectID'";
                         else
                             if ($type=="files")
                                $query1 = "UPDATE files SET `status`='0' WHERE `id`='$itemID' AND `userID`='$userID' AND `projectID`='$projectID'";
                             
                if ($query1 != "")
                {
                    $results = mysql_query($query1) or die(" ". mysql_error());
                    date_default_timezone_set('America/New_York');
                    $timestamp = time();
                    $datetime = getdate();
                    $date = date('Y-m-d', $datetime[0]);
                    $time = date('H:i:s', $datetime[0]);
                    $webPage = $_GET['webPage'];
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $aquery = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', 'delete_$type', '$itemID','$ip')";
                    $results2 = mysql_query($aquery) or die(" ". mysql_error());
                    if ($webPage!="")
                        require_once($webPage);
                }
	}
?>