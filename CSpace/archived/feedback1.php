<?php
	session_start();
	require_once("header.php");
	require_once("connect.php");
	$pageName = "CSpace/feedback1.php";
	require_once("../counter.php");
?>
<?php
	echo "<br/><table class=\"body\" cellpadding=2 cellspacing=2>\n";	

	$userID = $_COOKIE['CSpace_userID'];
	$query1 = "SELECT count(*) as num FROM users WHERE userID='$userID'";
	$results1 = mysql_query($query1) or die(" ". mysql_error());
	$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	$num = $line1['num'];
	
	if ($num==1) {			
?>
	<form action="feedback1Submit.php" method=post>
		<tr><th colspan=3>Please rate the following statements on the scale of 1 (Strongly Disagree) to 7 (Strongly Agree).</th></tr>
		<tr bgcolor=#DDDDDD><td align=right>1.</td><td>It was easy to save relevant information (documents and snippets) using the <strong>toolbar</strong> functions.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q1" value="1" />1 <input type="radio" name="f1q1" value="2" />2 <input type="radio" name="f1q1" value="3" />3 <input type="radio" name="f1q1" value="4" />4 <input type="radio" name="f1q1" value="5" />5 <input type="radio" name="f1q1" value="6" />6 <input type="radio" name="f1q1" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr><td align=right>2.</td><td>Making annotations on webpages was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q2" value="1" />1 <input type="radio" name="f1q2" value="2" />2 <input type="radio" name="f1q2" value="3" />3 <input type="radio" name="f1q2" value="4" />4 <input type="radio" name="f1q2" value="5" />5 <input type="radio" name="f1q2" value="6" />6 <input type="radio" name="f1q2" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>3.</td><td>Display of the project name in the <strong>toolbar</strong> was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q3" value="1" />1 <input type="radio" name="f1q3" value="2" />2 <input type="radio" name="f1q3" value="3" />3 <input type="radio" name="f1q3" value="4" />4 <input type="radio" name="f1q3" value="5" />5 <input type="radio" name="f1q3" value="6" />6 <input type="radio" name="f1q3" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr><td align=right>4.</td><td>Display of various statistics about a displayed webpage (view count, snippets, and annotations) in the <strong>toolbar</strong> was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q4" value="1" />1 <input type="radio" name="f1q4" value="2" />2 <input type="radio" name="f1q4" value="3" />3 <input type="radio" name="f1q4" value="4" />4 <input type="radio" name="f1q4" value="5" />5 <input type="radio" name="f1q4" value="6" />6 <input type="radio" name="f1q4" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>5.</td><td>Display of my personal history (queries used, and documents and snippets saved) in the <strong>sidebar</strong> was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q5" value="1" />1 <input type="radio" name="f1q5" value="2" />2 <input type="radio" name="f1q5" value="3" />3 <input type="radio" name="f1q5" value="4" />4 <input type="radio" name="f1q5" value="5" />5 <input type="radio" name="f1q5" value="6" />6 <input type="radio" name="f1q5" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr><td align=right>6.</td><td>Ability to write notes using the <strong>sidebar</strong> was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q6" value="1" />1 <input type="radio" name="f1q6" value="2" />2 <input type="radio" name="f1q6" value="3" />3 <input type="radio" name="f1q6" value="4" />4 <input type="radio" name="f1q6" value="5" />5 <input type="radio" name="f1q6" value="6" />6 <input type="radio" name="f1q6" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>7.</td><td>Writing a note using the <strong>sidebar</strong> was easy.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q7" value="1" />1 <input type="radio" name="f1q7" value="2" />2 <input type="radio" name="f1q7" value="3" />3 <input type="radio" name="f1q7" value="4" />4 <input type="radio" name="f1q7" value="5" />5 <input type="radio" name="f1q7" value="6" />6 <input type="radio" name="f1q7" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr><td align=right>8.</td><td><strong>Log information</strong> about my activities (visited and saved pages, snippets, and annotations records) was useful.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q8" value="1" />1 <input type="radio" name="f1q8" value="2" />2 <input type="radio" name="f1q8" value="3" />3 <input type="radio" name="f1q8" value="4" />4 <input type="radio" name="f1q8" value="5" />5 <input type="radio" name="f1q8" value="6" />6 <input type="radio" name="f1q8" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>9.</td><td>Creating a new <strong>project</strong> was easy.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q9" value="1" />1 <input type="radio" name="f1q9" value="2" />2 <input type="radio" name="f1q9" value="3" />3 <input type="radio" name="f1q9" value="4" />4 <input type="radio" name="f1q9" value="5" />5 <input type="radio" name="f1q9" value="6" />6 <input type="radio" name="f1q9" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr><td align=right>10.</td><td>It was easy to learn to use this system.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q10" value="1" />1 <input type="radio" name="f1q10" value="2" />2 <input type="radio" name="f1q10" value="3" />3 <input type="radio" name="f1q10" value="4" />4 <input type="radio" name="f1q10" value="5" />5 <input type="radio" name="f1q10" value="6" />6 <input type="radio" name="f1q10" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>11.</td><td>I believe I became productive quickly using this system.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q11" value="1" />1 <input type="radio" name="f1q11" value="2" />2 <input type="radio" name="f1q11" value="3" />3 <input type="radio" name="f1q11" value="4" />4 <input type="radio" name="f1q11" value="5" />5 <input type="radio" name="f1q11" value="6" />6 <input type="radio" name="f1q11" value="7" />7 (Strongly Agree)</td></tr>

		<tr><td align=right>12.</td><td>It is easy to find the information (logs and other objects, histories, etc.) I need.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q12" value="1" />1 <input type="radio" name="f1q12" value="2" />2 <input type="radio" name="f1q12" value="3" />3 <input type="radio" name="f1q12" value="4" />4 <input type="radio" name="f1q12" value="5" />5 <input type="radio" name="f1q12" value="6" />6 <input type="radio" name="f1q12" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>13.</td><td>The organization of information on the system screens (toolbar, sidebar) is clear.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q13" value="1" />1 <input type="radio" name="f1q13" value="2" />2 <input type="radio" name="f1q13" value="3" />3 <input type="radio" name="f1q13" value="4" />4 <input type="radio" name="f1q13" value="5" />5 <input type="radio" name="f1q13" value="6" />6 <input type="radio" name="f1q13" value="7" />7 (Strongly Agree)</td></tr>

		<tr><td align=right>14.</td><td>This system has all the functions and capabilities I expect it to have.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q14" value="1" />1 <input type="radio" name="f1q14" value="2" />2 <input type="radio" name="f1q14" value="3" />3 <input type="radio" name="f1q14" value="4" />4 <input type="radio" name="f1q14" value="5" />5 <input type="radio" name="f1q14" value="6" />6 <input type="radio" name="f1q14" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>15.</td><td>I am able to efficiently complete my work using this system.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q15" value="1" />1 <input type="radio" name="f1q15" value="2" />2 <input type="radio" name="f1q15" value="3" />3 <input type="radio" name="f1q15" value="4" />4 <input type="radio" name="f1q15" value="5" />5 <input type="radio" name="f1q15" value="6" />6 <input type="radio" name="f1q15" value="7" />7 (Strongly Agree)</td></tr>	

		<tr><td align=right>16.</td><td>Overall, I am satisfied with how easy it is to use this system.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q16" value="1" />1 <input type="radio" name="f1q16" value="2" />2 <input type="radio" name="f1q16" value="3" />3 <input type="radio" name="f1q16" value="4" />4 <input type="radio" name="f1q16" value="5" />5 <input type="radio" name="f1q16" value="6" />6 <input type="radio" name="f1q16" value="7" />7 (Strongly Agree)</td></tr>
		
		<tr bgcolor=#DDDDDD><td align=right>17.</td><td>Overall, I am satisfied with this system.</td><td align=center width=500px> (Strongly Disagree) <input type="radio" name="f1q17" value="1" />1 <input type="radio" name="f1q17" value="2" />2 <input type="radio" name="f1q17" value="3" />3 <input type="radio" name="f1q17" value="4" />4 <input type="radio" name="f1q17" value="5" />5 <input type="radio" name="f1q17" value="6" />6 <input type="radio" name="f1q17" value="7" />7 (Strongly Agree)</td></tr>

		<tr><td align=right>18.</td><td>List at least two aspects of this system that you liked the most.</td><td><textarea cols=60 rows=3 name="f1like"></textarea></td></tr>
	
		<tr bgcolor=#DDDDDD><td align=right>19.</td><td>List at least two aspects of this system that you disliked the most.</td><td><textarea cols=60 rows=3 name="f1dislike"></textarea></td></tr>
								
		<tr><td colspan=3><br/></td></tr>
		<tr><td colspan=3 align=center><input type="submit" value="Submit"/></td></tr>
	</form>
<?php
	}
	else {
		echo "<tr><td>Something went wrong. Please <a href=\"index.php\">try again</a>.</td></tr>\n";
	}
	echo "</table>\n";
?>

</body>
</html>
