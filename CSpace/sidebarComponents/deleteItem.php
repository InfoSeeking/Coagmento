<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

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
                    $results = $connection->commit($query1);
                    date_default_timezone_set('America/New_York');
                    $timestamp = time();
                    $datetime = getdate();
                    $date = date('Y-m-d', $datetime[0]);
                    $time = date('H:i:s', $datetime[0]);
                    $webPage = $_GET['webPage'];
                    $ip=$base->getIP();
										Util::getInstance()->saveAction("delete_$type","$itemID",$base);
                    
                    if ($webPage!="")
                        require_once($webPage);
                }
	}
?>
