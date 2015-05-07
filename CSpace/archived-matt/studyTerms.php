<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "<br/><br/>Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		require_once("connect.php");
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		require_once("connect.php");
		$pageName = "studyTerms.php";
		require_once("../counter.php");
		
?>
		<br/>
		<?php echo "<form action=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" method=post>"; ?>
		<table class="body">
<?php
		if ($_GET['submit']) {
			echo "<tr><td><br/><br/>We are glad you decided to take part in this study. Frankly, it makes sense if you were planning on using Coagmento anyway since all you have to do for this study is to use Coagmento regularly! Alright, well, we expect you to do a few more things, such as filling in demographic information and some other questionnaires from time to time. Don't worry, it's really easy, you don't have to do it at a specific time or place, and it won't take more than a few moments of your time. Specifically, in order to qualify for the monthly drawing of the prizes, you need to (1) use Coagmento at least once a week and earn at least 500 points per week, (2) earn at least 5000 points by the end of the month, and (3) work on at least one collaborative project (involving at least one collaborator besides yourself) in the given month. The greater the usage you have beyond that requirement, the more chances you have for getting your name picked for a prize (iPod Nano)!<br/><br/>You can continue to your <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('main.php','content');\">CSpace</span> now, where you will find what information you still need to fill in to continue to be eligible for this study.</td></tr>\n";
		} // if (isset($_POST['age']))		
		else {
?>
	<tr><td style="font-weight:bold;"><span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('main.php','content');">CSpace</span> > Coagmento Study</td><td align="right"></td></tr>
	<tr><td colspan="2"><hr/></td></tr>
	<tr><td colspan=2><br/>You have signed up for using Coagmento while working on your group project at your school. Awesome! So now what?<br/><br/></td></tr>
	<tr><td colspan=2>First off, make sure you <span style="font-weight:bold">return your consent forms</span> signed by you and your parents. Why do this? Because then you'll have a chance to not only play with Coagmento while working on your project, but also score some cool prizes like <span style="font-weight:bold">$25 iTunes Gift Cards</span> (and there may be more)!<br/><br/></td></tr>
	<tr><td colspan=2>Once we have your consent forms back, we'll ask you for a few details about you. Don't worry, it'll only take a few minutes, and then we won't bother you for a couple of weeks.<br/><br/></td></tr>
	<tr><td colspan=2>After this initial signing up and filling in details part, you are free to work on your project as you wish with Coagmento installed in your Firefox browser (Mac or PC). For everything you do using Coagmento, like visiting webpages, searching on the Internet, and saving or sharing information, you earn points. The more points you earn, the more chances of you winning those prizes.<br/><br/></td></tr>
	<tr><td colspan=2>Coagmento provides you with tools to keep track of your progress (what you find, collect, and share), chat with your group members, and even write a report - all within your Firefox browser itself!<br/><br/></td></tr>
	<tr><td colspan=2>A couple of times during the one month period of your project, we'll ask you to fill in brief surveys. But that's pretty much it. When you're done with your project, your teacher will have access to your final project report for grading, and we'll declare the winners (two students per class).<br/><br/></td></tr>
	<tr><td colspan=2>Questions? Ask your teacher, your librarian <a href="mailto:pread@somersschools.org?subject=Coagmento inquiry">Ms. Pamela Read</a>, or <a href="mailto:support@coagmento.org?subject=Coagmento inquiry">send us (The Coagmento Team) an email</a>. Good luck and have fun!<br/><br/></td></tr>
	<tr><th colspan=2>Back to your <span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('main.php','content');">CSpace</span><br/><br/></th></tr>
	<?php
/*
		$query = "SELECT * FROM users WHERE userID='$userID'";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$type = $line['type'];
		if (preg_match("/subject/", $type)) {
			echo "<tr><td colspan=2><span style=\"font-weight:bold;color:green\">You have already enrolled for this study. Good for you!</span></td></tr>\n";
		}			
		else {
			echo "<tr><td colspan=2 align=center><input type=\"hidden\" name=\"consent\" value=\"true\"/><input type=\"submit\" value=\"I give my consent to participate in the study\"/></td></tr>\n";
		}
*/
	} // else with if (isset($_POST['age']))
} // else with if (!isset($_SESSION['CSpace_userID']))	
?>
	</table>
	</form>
	<br/>
<br/>
</center>
</body>
</html>
