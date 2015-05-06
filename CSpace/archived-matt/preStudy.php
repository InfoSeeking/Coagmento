<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		require_once("connect.php");
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		require_once("connect.php");
		$pageName = "preStudy.php";
		require_once("../counter.php");
?>
		<br/>
		<?php echo "<form action=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" method=post>"; ?>
		<table class="body">
<?php
		if ($_GET['submit']) {
			echo "<tr><td>Thank you for submitting your information. You can continue to your <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('main.php','content');\">CSpace</span> now.</td></tr>\n";
		} // if (isset($_POST['age']))		
		else {
?>
	<tr><th colspan=2><span style="font-weight:bold">Pre-study Questionnaire</span><br/><br/></th></tr>
	<tr><td colspan=2>Please fill in all the fields. You will recieve 100 points for submitting this information.<br/></td></tr>
	<tr><td align=right>1.</td><td>What tools (softwares, web-services) do you use for collecting and managing online information?<br/><textarea name="tools" cols=60 rows=2></textarea></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>2.</td><td>What are the biggest obstacles you have encountered in conducting searches online?<br/><textarea name="obs_search" cols=60 rows=2></textarea></td></tr>
	<tr><td align=right>3.</td><td>What are the biggest obstacles you have encountered in sharing the information you find online?<br/><textarea name="obs_share" cols=60 rows=2></textarea></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>4.</td><td>How often you try to find the same information that you had previously found?<br/><input type="radio" name="often" value="occassionally" /> Occassionally<br/><input type="radio" name="often" value="1-3"/> 1-3 times per day<br/><input type="radio" name="often" value="4-6"/> 4-6 times per day<br/><input type="radio" name="often" value="7-10"/> 7-10 times per day<br/><input type="radio" name="often" value="10+"/> 10+ times per day</td></tr>
	<tr><td align=right>5.</td><td>If you do multi-session searches, how do you resume your searches from the previous sessions? How did you remember where you had left off?<br/><textarea name="resume" cols=60 rows=2></textarea></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>6.</td><td>How do you re-find information that you had found previously?<br/><textarea name="refind" cols=60 rows=2></textarea></td></tr>
	<tr><td align=right>7.</td><td>How do you make sense of all the information that you find online? How do you synthesize across all the information?<br/><textarea name="sense" cols=60 rows=2></textarea><br/>&nbsp;&nbsp;&nbsp;&nbsp;(a) Do you take notes? If so, how (notebook, Word doc, email)?<br/>&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="notes" cols=60 rows=2></textarea><br/>&nbsp;&nbsp;&nbsp;&nbsp;(b) Do you take printouts?<br/>&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="printout" cols=60 rows=2></textarea></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>8.</td><td>Describe your group project.<br/><textarea name="project" cols=60 rows=2></textarea></td></tr>
	<tr><td align=right>9.</td><td>How familiar are you with the topic of this project?<br/>(Not at all familiar) <input type="radio" name="familiar" value="1"/>1 <input type="radio" name="familiar" value="2"/>2 <input type="radio" name="familiar" value="3"/>3 <input type="radio" name="familiar" value="4"/>4 <input type="radio" name="familiar" value="5"/>5 <input type="radio" name="familiar" value="6"/>6 <input type="radio" name="familiar" value="7"/>7 (Very familiar)</td></tr>
	<tr bgcolor=#DEDEDE><td align=right>10.</td><td>How much experience do you have with this kind of assignment?<br/>(Very inexperienced) <input type="radio" name="experience" value="1"/>1 <input type="radio" name="experience" value="2"/>2 <input type="radio" name="experience" value="3"/>3 <input type="radio" name="experience" value="4"/>4 <input type="radio" name="experience" value="5"/>5 <input type="radio" name="experience" value="6"/>6 <input type="radio" name="experience" value="7"/>7 (Very experienced)</td></tr>
	<tr><td align=right>11.</td><td>How difficult does this project seem to you?<br/>(Very easy) <input type="radio" name="difficult" value="1"/>1 <input type="radio" name="difficult" value="2"/>2 <input type="radio" name="difficult" value="3"/>3 <input type="radio" name="difficult" value="4"/>4 <input type="radio" name="difficult" value="5"/>5 <input type="radio" name="difficult" value="6"/>6 <input type="radio" name="difficult" value="7"/>7 (Very difficult)</td></tr>	
	<tr><td colspan="2" align=center><input type="hidden" name="prestudy" value="true"/><input type="submit" value="Submit" /></td></tr>
<?php
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
