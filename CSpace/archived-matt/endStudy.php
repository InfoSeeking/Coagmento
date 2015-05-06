<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		require_once("connect.php");
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		$pageName = "endStudy.php";
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
	<tr><th colspan=3><span style="font-weight:bold">End-study Questionnaire</span><br/><br/></th></tr>
	<tr><td colspan=3>Please rate the following statements on the scale of <span style="font-weight:bold;">1 (Strongly Disagree) to 7 (Strongly Agree)</span>. Select 'N/A' if a statement doesn't apply or you didn't use that feature. You will receive 500 points for submitting this information.<br/></td></tr>
	<tr><td align=right>1.</td><td>It was easy to save relevant information (documents and snippets) using the toolbar functions.</td><td width=250px><input type="radio" name="e1q1" value="0" /> N/A   <input type="radio" name="e1q1" value="1" /> 1 <input type="radio" name="e1q1" value="2" /> 2 <input type="radio" name="e1q1" value="3" /> 3 <input type="radio" name="e1q1" value="4" /> 4 <input type="radio" name="e1q1" value="5" /> 5 <input type="radio" name="e1q1" value="6" /> 6 <input type="radio" name="e1q1" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>2.</td><td>Making annotations on webpages was useful.</td><td> <input type="radio" name="e1q2" value="0" /> N/A   <input type="radio" name="e1q2" value="1" /> 1 <input type="radio" name="e1q2" value="2" /> 2 <input type="radio" name="e1q2" value="3" /> 3 <input type="radio" name="e1q2" value="4" /> 4 <input type="radio" name="e1q2" value="5" /> 5 <input type="radio" name="e1q2" value="6" /> 6 <input type="radio" name="e1q2" value="7" /> 7 </tr>
	<tr><td align=right>3.</td><td>Display of the project name in the toolbar was useful.</td></td><td> <input type="radio" name="e1q3" value="0" /> N/A   <input type="radio" name="e1q3" value="1" /> 1 <input type="radio" name="e1q3" value="2" /> 2 <input type="radio" name="e1q3" value="3" /> 3 <input type="radio" name="e1q3" value="4" /> 4 <input type="radio" name="e1q3" value="5" /> 5 <input type="radio" name="e1q3" value="6" /> 6 <input type="radio" name="e1q3" value="7" /> 7 </tr>
	<tr bgcolor=#DEDEDE><td align=right>4.</td><td>Display of various statistics about a displayed webpage (view count, snippets, and annotations) in the toolbar was useful.</td><td> <input type="radio" name="e1q4" value="0" /> N/A   <input type="radio" name="e1q4" value="1" /> 1 <input type="radio" name="e1q4" value="2" /> 2 <input type="radio" name="e1q4" value="3" /> 3 <input type="radio" name="e1q4" value="4" /> 4 <input type="radio" name="e1q4" value="5" /> 5 <input type="radio" name="e1q4" value="6" /> 6 <input type="radio" name="e1q4" value="7" /> 7 </tr>
	<tr><td align=right>5.</td><td>Display of the history (searches done, and documents and snippets saved) in the sidebar was useful.</td><td> <input type="radio" name="e1q5" value="0" /> N/A   <input type="radio" name="e1q5" value="1" /> 1 <input type="radio" name="e1q5" value="2" /> 2 <input type="radio" name="e1q5" value="3" /> 3 <input type="radio" name="e1q5" value="4" /> 4 <input type="radio" name="e1q5" value="5" /> 5 <input type="radio" name="e1q5" value="6" /> 6 <input type="radio" name="e1q5" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>6.</td><td>Ability to write notes using the sidebar was useful.</td><td> <input type="radio" name="e1q6" value="0" /> N/A   <input type="radio" name="e1q6" value="1" /> 1 <input type="radio" name="e1q6" value="2" /> 2 <input type="radio" name="e1q6" value="3" /> 3 <input type="radio" name="e1q6" value="4" /> 4 <input type="radio" name="e1q6" value="5" /> 5 <input type="radio" name="e1q6" value="6" /> 6 <input type="radio" name="e1q6" value="7" /> 7 </td></tr>
	<tr><td align=right>7.</td><td>Writing a note using the sidebar was easy.</td><td> <input type="radio" name="e1q7" value="0" /> N/A   <input type="radio" name="e1q7" value="1" /> 1 <input type="radio" name="e1q7" value="2" /> 2 <input type="radio" name="e1q7" value="3" /> 3 <input type="radio" name="e1q7" value="4" /> 4 <input type="radio" name="e1q7" value="5" /> 5 <input type="radio" name="e1q7" value="6" /> 6 <input type="radio" name="e1q7" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>8.</td><td>Log information about my activities (visited and saved pages, snippets, and annotations records) was useful.</td><td> <input type="radio" name="e1q8" value="0" /> N/A   <input type="radio" name="e1q8" value="1" /> 1 <input type="radio" name="e1q8" value="2" /> 2 <input type="radio" name="e1q8" value="3" /> 3 <input type="radio" name="e1q8" value="4" /> 4 <input type="radio" name="e1q8" value="5" /> 5 <input type="radio" name="e1q8" value="6" /> 6 <input type="radio" name="e1q8" value="7" /> 7 </td></tr>
	<tr><td align=right>9.</td><td>Creating a new project was easy.</td><td> <input type="radio" name="e1q9" value="0" /> N/A   <input type="radio" name="e1q9" value="1" /> 1 <input type="radio" name="e1q9" value="2" /> 2 <input type="radio" name="e1q9" value="3" /> 3 <input type="radio" name="e1q9" value="4" /> 4 <input type="radio" name="e1q9" value="5" /> 5 <input type="radio" name="e1q9" value="6" /> 6 <input type="radio" name="e1q9" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>10.</td><td>Adding a collaborator was easy.</td><td> <input type="radio" name="e1q10" value="0" /> N/A   <input type="radio" name="e1q10" value="1" /> 1 <input type="radio" name="e1q10" value="2" /> 2 <input type="radio" name="e1q10" value="3" /> 3 <input type="radio" name="e1q10" value="4" /> 4 <input type="radio" name="e1q10" value="5" /> 5 <input type="radio" name="e1q10" value="6" /> 6 <input type="radio" name="e1q10" value="7" /> 7 </td></tr>
	<tr><td align=right>11.</td><td>It was easy to learn to use this system.</td><td> <input type="radio" name="e1q11" value="0" /> N/A   <input type="radio" name="e1q11" value="1" /> 1 <input type="radio" name="e1q11" value="2" /> 2 <input type="radio" name="e1q11" value="3" /> 3 <input type="radio" name="e1q11" value="4" /> 4 <input type="radio" name="e1q11" value="5" /> 5 <input type="radio" name="e1q11" value="6" /> 6 <input type="radio" name="e1q11" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>12.</td><td>I believe I became productive quickly using this system.</td><td> <input type="radio" name="e1q12" value="0" /> N/A   <input type="radio" name="e1q12" value="1" /> 1 <input type="radio" name="e1q12" value="2" /> 2 <input type="radio" name="e1q12" value="3" /> 3 <input type="radio" name="e1q12" value="4" /> 4 <input type="radio" name="e1q12" value="5" /> 5 <input type="radio" name="e1q12" value="6" /> 6 <input type="radio" name="e1q12" value="7" /> 7 </td></tr>
	<tr><td align=right>13.</td><td>It is easy to find the information I need.</td><td> <input type="radio" name="e1q13" value="0" /> N/A   <input type="radio" name="e1q13" value="1" /> 1 <input type="radio" name="e1q13" value="2" /> 2 <input type="radio" name="e1q13" value="3" /> 3 <input type="radio" name="e1q13" value="4" /> 4 <input type="radio" name="e1q13" value="5" /> 5 <input type="radio" name="e1q13" value="6" /> 6 <input type="radio" name="e1q13" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>14.</td><td>The organization of information on the system screens (toolbar, sidebar) is clear.</td><td> <input type="radio" name="e1q14" value="0" /> N/A   <input type="radio" name="e1q14" value="1" /> 1 <input type="radio" name="e1q14" value="2" /> 2 <input type="radio" name="e1q14" value="3" /> 3 <input type="radio" name="e1q14" value="4" /> 4 <input type="radio" name="e1q14" value="5" /> 5 <input type="radio" name="e1q14" value="6" /> 6 <input type="radio" name="e1q14" value="7" /> 7 </td></tr>
	<tr><td align=right>15.</td><td>This system has all the functions and capabilities I expect it to have.</td><td> <input type="radio" name="e1q15" value="0" /> N/A   <input type="radio" name="e1q15" value="1" /> 1 <input type="radio" name="e1q15" value="2" /> 2 <input type="radio" name="e1q15" value="3" /> 3 <input type="radio" name="e1q15" value="4" /> 4 <input type="radio" name="e1q15" value="5" /> 5 <input type="radio" name="e1q15" value="6" /> 6 <input type="radio" name="e1q15" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>16.</td><td>I am able to efficiently complete my work using this system.</td><td> <input type="radio" name="e1q16" value="0" /> N/A   <input type="radio" name="e1q16" value="1" /> 1 <input type="radio" name="e1q16" value="2" /> 2 <input type="radio" name="e1q16" value="3" /> 3 <input type="radio" name="e1q16" value="4" /> 4 <input type="radio" name="e1q16" value="5" /> 5 <input type="radio" name="e1q16" value="6" /> 6 <input type="radio" name="e1q16" value="7" /> 7 </td></tr>
	<tr><td align=right>17.</td><td>Overall, I am satisfied with how easy it is to use this system.</td><td> <input type="radio" name="e1q17" value="0" /> N/A   <input type="radio" name="e1q17" value="1" /> 1 <input type="radio" name="e1q17" value="2" /> 2 <input type="radio" name="e1q17" value="3" /> 3 <input type="radio" name="e1q17" value="4" /> 4 <input type="radio" name="e1q17" value="5" /> 5 <input type="radio" name="e1q17" value="6" /> 6 <input type="radio" name="e1q17" value="7" /> 7 </td></tr>
	<tr bgcolor=#DEDEDE><td align=right>18.</td><td>Overall, I am satisfied with this system.</td><td> <input type="radio" name="e1q18" value="0" /> N/A   <input type="radio" name="e1q18" value="1" /> 1 <input type="radio" name="e1q18" value="2" /> 2 <input type="radio" name="e1q18" value="3" /> 3 <input type="radio" name="e1q18" value="4" /> 4 <input type="radio" name="e1q18" value="5" /> 5 <input type="radio" name="e1q18" value="6" /> 6 <input type="radio" name="e1q18" value="7" /> 7 </td></tr>
	<tr><td align=right>19.</td><td colspan=2><table><tr><td>List at least two aspects of this system that you liked the most.<br/><textarea name="e1like" cols=40 rows=2></textarea></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>List at least two aspects of this system that you disliked the most.<br/><textarea name="e1dislike" cols=40 rows=2></textarea></td></tr></table></td></tr>

	<tr bgcolor=#DEDEDE><td align=right>20.</td><td colspan=2><table><tr><td colspan=3 align="center">Using the system was...</td></tr><tr><td>Uninteresting</td><td><input type="radio" name="e1q21" value="1" /> 1 <input type="radio" name="e1q21" value="2" /> 2 <input type="radio" name="e1q21" value="3" /> 3 <input type="radio" name="e1q21" value="4" /> 4 <input type="radio" name="e1q21" value="5" /> 5 <input type="radio" name="e1q21" value="6" /> 6 <input type="radio" name="e1q21" value="7" /> 7</td><td>&nbsp;&nbsp;Interesting</td></tr>
	<tr><td>Attention was not focused</td><td><input type="radio" name="e1q23" value="1" /> 1 <input type="radio" name="e1q23" value="2" /> 2 <input type="radio" name="e1q23" value="3" /> 3 <input type="radio" name="e1q23" value="4" /> 4 <input type="radio" name="e1q23" value="5" /> 5 <input type="radio" name="e1q23" value="6" /> 6 <input type="radio" name="e1q23" value="7" /> 7</td><td>&nbsp;&nbsp;Attention was focused</td></tr>

	<tr><td>Dull</td><td><input type="radio" name="e1q25" value="1" /> 1 <input type="radio" name="e1q25" value="2" /> 2 <input type="radio" name="e1q25" value="3" /> 3 <input type="radio" name="e1q25" value="4" /> 4 <input type="radio" name="e1q25" value="5" /> 5 <input type="radio" name="e1q25" value="6" /> 6 <input type="radio" name="e1q25" value="7" /> 7</td><td>&nbsp;&nbsp;Exciting</td></tr>

	<tr><td>Not Fun</td><td><input type="radio" name="e1q27" value="1" /> 1 <input type="radio" name="e1q27" value="2" /> 2 <input type="radio" name="e1q27" value="3" /> 3 <input type="radio" name="e1q27" value="4" /> 4 <input type="radio" name="e1q27" value="5" /> 5 <input type="radio" name="e1q27" value="6" /> 6 <input type="radio" name="e1q27" value="7" /> 7</td><td>&nbsp;&nbsp;Fun</td></tr>
	</table>
	</td>
	</tr>

	<tr><td>21.</td><td colspan=2 valign=top>
		<table>
		<tr><td colspan=3 align="center">How did you feel while collaborating with this system...</td></tr>
		<tr><td>Not absorbed intensely</td><td><input type="radio" name="e1q22" value="1" /> 1 <input type="radio" name="e1q22" value="2" /> 2 <input type="radio" name="e1q22" value="3" /> 3 <input type="radio" name="e1q22" value="4" /> 4 <input type="radio" name="e1q22" value="5" /> 5 <input type="radio" name="e1q22" value="6" /> 6 <input type="radio" name="e1q22" value="7" /> 7</td><td>&nbsp;&nbsp;Absorbed intensely</td></tr>
		<tr><td>Not Enjoyable</td><td><input type="radio" name="e1q24" value="1" /> 1 <input type="radio" name="e1q24" value="2" /> 2 <input type="radio" name="e1q24" value="3" /> 3 <input type="radio" name="e1q24" value="4" /> 4 <input type="radio" name="e1q24" value="5" /> 5 <input type="radio" name="e1q24" value="6" /> 6 <input type="radio" name="e1q24" value="7" /> 7</td><td>&nbsp;&nbsp;Enjoyable</td></tr>
		<tr><td>Did not concentrate fully</td><td><input type="radio" name="e1q26" value="1" /> 1 <input type="radio" name="e1q26" value="2" /> 2 <input type="radio" name="e1q26" value="3" /> 3 <input type="radio" name="e1q26" value="4" /> 4 <input type="radio" name="e1q26" value="5" /> 5 <input type="radio" name="e1q26" value="6" /> 6 <input type="radio" name="e1q26" value="7" /> 7</td><td>&nbsp;&nbsp;Concentrated fully</td></tr>
		<tr><td>Not deeply engrossed</td><td><input type="radio" name="e1q28" value="1" /> 1 <input type="radio" name="e1q28" value="2" /> 2 <input type="radio" name="e1q28" value="3" /> 3 <input type="radio" name="e1q28" value="4" /> 4 <input type="radio" name="e1q28" value="5" /> 5 <input type="radio" name="e1q28" value="6" /> 6 <input type="radio" name="e1q28" value="7" /> 7</td><td>&nbsp;&nbsp;Deeply engrossed</td></tr>
		</table>
		</td>
	</tr>

	<tr bgcolor=#DEDEDE><td>22.</td><td>How familiar were you about the topic of your project prior starting working on it?</td><td>(Not familiar) <input type="radio" name="familiar" value="1" /> 1 <input type="radio" name="familiar" value="2" /> 2 <input type="radio" name="familiar" value="3" /> 3 <input type="radio" name="familiar" value="4" /> 4 <input type="radio" name="familiar" value="5" /> 5 <input type="radio" name="familiar" value="6" /> 6 <input type="radio" name="familiar" value="7" /> 7 (Very familiar)</td></tr>

	<tr><td>23.</td><td>How interested/motivated were you about the topic of your project?</td><td>(Not at all) <input type="radio" name="motivation" value="1" /> 1 <input type="radio" name="motivation" value="2" /> 2 <input type="radio" name="motivation" value="3" /> 3 <input type="radio" name="motivation" value="4" /> 4 <input type="radio" name="motivation" value="5" /> 5 <input type="radio" name="motivation" value="6" /> 6 <input type="radio" name="motivation" value="7" /> 7 (Very much)</td></tr>

	<tr bgcolor=#DEDEDE><td>24.</td><td>How easy did you find working on this project?</td><td>(Very difficult) <input type="radio" name="easy" value="1" /> 1 <input type="radio" name="easy" value="2" /> 2 <input type="radio" name="easy" value="3" /> 3 <input type="radio" name="easy" value="4" /> 4 <input type="radio" name="easy" value="5" /> 5 <input type="radio" name="easy" value="6" /> 6 <input type="radio" name="easy" value="7" /> 7 (Very easy)</td></tr>

	<tr><td>25.</td><td colspan=2 valign=top>Please tell us how we can improve Coagmento. Any additional comments are highly appreciated.<br/><textarea cols=60 rows=4 name="comments"></textarea></td></tr>
	<tr><td colspan="3" align=center><input type="hidden" name="endstudy" value="true"/><input type="submit" value="Submit" onclick="alert('Thank you for submitting your responses!');" /></td></tr>
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
