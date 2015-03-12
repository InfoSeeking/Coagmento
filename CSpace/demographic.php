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
		$pageName = "demographic.php";
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
	<tr><th colspan=3><span style="font-weight:bold">Demographic Questionnaire</span><br/><br/></th></tr>
	<tr><td colspan=3>Please fill in all the fields. You will receive 100 points for submitting your demographic information.<br/></td></tr>
	<tr><td align=right>1.</td><td>Age</td><td><input type="text" size=4 name="age"/></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>2.</td><td>Gender</td><td><input type="radio" name="gender" value="male"/> Male <input type="radio" name="sex" value="female"/> Female</td></tr>
	<tr><td align=right>3.</td><td>Which operating system do you<br/> use most frequently?</td><td><input type="radio" name="os" value="mac" /> Mac<br/><input type="radio" name="os" value="windows"/> Windows<br/><input type="radio" name="os" value="linux"/> Linux<br/><input type="radio" name="os" value="other"/> Other</td></tr>
	<tr bgcolor=#DEDEDE><td align=right>4.</td><td>Which browser do you<br/> use most frequently?</td><td><input type="radio" name="browser" value="chrome" /> Chrome<br/><input type="radio" name="browser" value="firefox" /> Firefox<br/><input type="radio" name="browser" value="ie"/> Internet Explorer<br/><input type="radio" name="browser" value="safari"/> Safari<br/><input type="radio" name="browser" value="other"/> Other</td></tr>
	<tr><td align=right>5.</td><td>How would you describe<br/>your search experience?</td><td> (Very inexperienced) <input type="radio" name="experience" value="1"/>1 <input type="radio" name="experience" value="2"/>2 <input type="radio" name="experience" value="3"/>3 <input type="radio" name="experience" value="4"/>4 <input type="radio" name="experience" value="5"/>5 <input type="radio" name="experience" value="6"/>6 <input type="radio" name="experience" value="7"/>7 (Very experienced)</td></tr>
	<tr bgcolor=#DEDEDE><td align=right>6.</td><td>How often do you<br/>search the Web?</td><td><input type="radio" name="often" value="occassionally" /> Occasionally<br/><input type="radio" name="often" value="1-3"/> 1-3 searches per day<br/><input type="radio" name="often" value="4-6"/> 4-6 searches per day<br/><input type="radio" name="often" value="7-10"/> 7-10 searches per day<br/><input type="radio" name="often" value="10+"/> 10+ searches per day</td></tr>
	<tr><td align=right>7.</td><td>How much do you<br/>use text messaging?</td><td><input type="radio" name="text" value="occassionally" /> Occasionally<br/><input type="radio" name="text" value="1-3"/> 1-3 messages per day<br/><input type="radio" name="text" value="4-6"/> 4-6 messages per day<br/><input type="radio" name="text" value="7-10"/> 7-10 messages per day<br/><input type="radio" name="text" value="10+"/> 10+ messages per day</td></tr>
	<tr bgcolor=#DEDEDE><td align=right>8.</td><td>How often do you work<br/>on a project with others?</td><td><input type="radio" name="project" value="daily" /> Daily<br/><input type="radio" name="project" value="weekly"/> Weekly<br/><input type="radio" name="project" value="monthly"/> Monthly<br/><input type="radio" name="project" value="less"/> Less than monthly</td></tr>
	<tr><td align=right>9.</td><td>How many collaborative projects did you<br/>work on during the past year?</td><td><input type="text" name="collabnum" size=3 /></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>10.</td><td>How much did you enjoy<br/>working on these collaborative projects?</td><td> (Not at all) <input type="radio" name="enjoy" value="1"/>1 <input type="radio" name="enjoy" value="2"/>2 <input type="radio" name="enjoy" value="3"/>3 <input type="radio" name="enjoy" value="4"/>4 <input type="radio" name="enjoy" value="5"/>5 <input type="radio" name="enjoy" value="6"/>6 <input type="radio" name="enjoy" value="7"/>7 (Very much)</td></tr>
	<tr><td align=right>11.</td><td>How successful were<br/>these collaborative projects?</td><td> (Not at all) <input type="radio" name="success" value="1"/>1 <input type="radio" name="success" value="2"/>2 <input type="radio" name="success" value="3"/>3 <input type="radio" name="success" value="4"/>4 <input type="radio" name="success" value="5"/>5 <input type="radio" name="success" value="6"/>6 <input type="radio" name="success" value="7"/>7 (Very much)</td></tr>
	<tr bgcolor=#DEDEDE><td align=right>12.</td><td>Which search engine do you use most frequently?</td><td><input type="text" name="engine" size=30/></td></tr>
	<tr><td align=right>13.</td><td>Which chat programs do you use or have used?</td><td><input type="checkbox" name="aim" value="aim"/>AIM <input type="checkbox" name="yahoo" value="yahoo"/>Yahoo! <input type="checkbox" name="msn" value="msn"/>MSN/Live <input type="checkbox" name="google" value="google"/>Google <input type="checkbox" name="facebook" value="facebook"/>Facebook <input type="checkbox" name="other" value="other"/>Other <input type=text name="other" size=10/></td></tr>
	<tr bgcolor=#DEDEDE><td align=right>14.</td><td>Do you have a smartphone? If yes, which one?</td><td><input type="text" name="smartphone" size=30/></td></tr>
	<tr><td colspan=3><br/></td></tr>
	<tr><td colspan="3" align=center><input type="hidden" name="demographic" value="true"/><input type="submit" value="Submit" /></td></tr>
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
