<?php
	session_start();
?>
<div id="chatBox" style="height:210px;overflow:hidden;">
	<div id="chatOptions">
<?php
	require_once("connect.php");
	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		// Find out the preferences set by this user for this project.
		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='chat-status'";
		$results = mysql_query($query) or die("1 ". mysql_error());
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$chatStatus = $line['value'];
		if ($chatStatus=='off')
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-status','on');\">Show status</span>|";
		else
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-status','off');\">Hide status</span>|";

		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='chat-show-date'";
		$results = mysql_query($query) or die("2 ". mysql_error());
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$showDate = $line['value'];
		if ($showDate=='yes')
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-show-date','no');\">Hide date</span>|";
		else
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-show-date','yes');\">Show date</span>|";

		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='chat-show-time'";
		$results = mysql_query($query) or die("3 ". mysql_error());
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$showTime = $line['value'];
		if ($showTime=='yes')
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-show-time','no');\">Hide time</span>";
		else
			echo "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"chatOption('chat-show-time','yes');\">Show time</span>";


		echo "</div>\n";
		echo "<table width=100%><tr><td><div id=\"collabOnline\"></div><hr/></td>";
		echo "</tr></table>";
		echo "<div id=\"chatMessages\" style=\"height:150px;overflow:auto;\">\n";
		require_once("chatList.php");
		echo "<script type=\"text/javascript\">\n";
		echo "var chatMessages = document.getElementById('chatMessages')\n";
		echo "chatMessages.scrollTop = chatMessages.scrollHeight;\n";
		echo "</script>\n";

		echo "<div id=\"chatText\">\n<input type=\"text\" size=26 id=\"cText\" onKeyDown=\"if (event.keyCode == 13) document.getElementById('cSend').click();\" /> <input type=\"button\" id=\"cSend\" value=\"Send\" onClick=\"sendMessage();\" \>\n</div>\n";
		echo "</div>\n";
	} // if (isset($_SESSION['CSpace_userID']))
	else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
//	
?>
<script type="text/javascript">
	var cText =	document.getElementById('cText');
	cText.focus();
	function chatOption(option, value) {
		var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/services/setOptions.php";
		req = new phpRequest(url);
		req.add('option', option);
		req.add('value', value);
		var response = req.execute();
		ajaxpage('collabOnline.php', 'collabOnline');
		ajaxpage('sidebarChat.php','chat');
		var chatWindow = document.getElementById('chatMessages');
		chatWindow.scrollTop = chatWindow.scrollHeight;
	}

	function sendMessage() {
		var cText =	document.getElementById('cText');
		var chatMessage = cText.value;
		cText.value = '';
		if (chatMessage!='') {
			var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/services/chatSubmit.php";
			req = new phpRequest(url);
			req.add('message', chatMessage);
			var response = req.execute();
/*
			var txtNode = document.createTextNode(chatMessage);
			var chatList = document.getElementById('chatMessages');
			chatList.appendChild(txtNode);
*/
			ajaxpage('chatList.php','chatMessages');
		}
		var chatWindow = document.getElementById('chatMessages');
		chatWindow.scrollTop = chatWindow.scrollHeight;
	}

</script>
