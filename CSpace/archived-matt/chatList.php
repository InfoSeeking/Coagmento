<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	// Find out the preferences set by this user for this project.
	$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='chat-show-date'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$showDate = $line['value'];
	$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='chat-show-time'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$showTime = $line['value'];

	echo "<table width=100%>\n";
	$query = "SELECT * FROM chat WHERE projectID='$projectID' ORDER BY timestamp";
	$results = $connection->commit($query);
	while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
		$chatID = $line['chatID'];
		$cUserID = $line['userID'];
		if ($userID==$cUserID)
			$color = '#FF7400';
		else
			$color = '#008C00';
		$userName = $line['username'];
		$message = stripslashes($line['message']);
		$date = $line['date'];
		$time = $line['time'];

		echo "<tr><td><span style=\"font-size:11px;color:$color;\">$userName:</span>";
		if ($showDate=='yes')
			echo "<span style=\"font-size:9px;color:green\">($date)</span>";
		if ($showTime=='yes')
			echo "<span style=\"font-size:9px;color:green\">($time)</span>";
		echo " <span style=\"font-size:11px\"> $message</span></td></tr>\n";
	}
	echo "</table>\n</div>\n";
	echo "<input type=\"hidden\" id=\"maxChatID\" value=\"$chatID\" />\n";
?>
