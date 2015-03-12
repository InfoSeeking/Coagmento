<?php
	session_start();
	ob_start();
	require_once("header.php");
	require_once("connect.php");
	$pageName = "CSpace/download.php";
	require_once("../counter.php");
		
	// If the user tried to login	
	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
?>
		<br/>
		<center>
		<table class="body" width=90%>
			<tr bgcolor=#CCFFAA><td><strong>What's new with CSpace?</strong> (Last update: 05/06/2009)</td></tr>
			<tr><td>
			<ul>
				<li>Rearranged tabs in the sidebar. Chat now remains open all the time.</li>
				<li>Objects in the tabs are displayed in chronological order.</li>
				<li>Status of your collaborators (for a given project) is now shown in the sidebar.</li>
				<li>A link to this page (download) added. Next to this link will be the latest version of Coagmento Firefox extension.</li>
			</ul></td></tr>
			<tr bgcolor=#CCFFAA><td><strong>Downloads</strong></td></tr>
			<tr><td><a href="../downloads/coagmento_1.0a.xpi">Coagmento Firefox extension 1.0a</a> (Release: 05/05/2009)</td></tr>
		</table>
		</center>
		<br/><br/>
<?php
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	} 		
	require_once("footer.php");
?>
  <!-- end #footer --></div>
<!-- end #container --></div>

</body>
</html>
