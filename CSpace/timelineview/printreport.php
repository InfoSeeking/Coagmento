<?php /*
include('user_agent.php'); // Redirecting http://mobile.site.info
// site.com data */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<LINK REL=StyleSheet HREF="../assets/css/style_timelineview.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<?php
  include('../func.php');
?>

<script type="text/javascript">
$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
});
</script>
</head>

<body>

<div id="topbar">
	<div class="left" style="float: left; "> <!-- min-width: 790px; width: 60%; -->
        <h2><a href="index.php">Coagmento CSpace</a></h2><br/>
    </div>

        	<div style="float: left;">
    				<?php
					session_start();
					require_once('../connect.php');
					$userID = $_SESSION['CSpace_userID'];
					$projectID = $_SESSION['CSpace_projectID'];
					$query = "SELECT * FROM users WHERE userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$userName = $line['firstName'] . " " . $line['lastName'];
					$avatar = $line['avatar'];
					$lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
					$points = $line['points'];
					$query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$projectNums = $line['num'];
					$query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$collabNums = $line['num'];
					/* <td><img src=\"../../img/$avatar\" width=45 height=45 style=\"vertical-align:middle;border:0\" /></td> */
					echo "<div class='top_links' style='border-left: 1px solid #ccc; padding-left: 15px;'><table style='font-size: 12px;'><tr><td valign=\"middle\">&nbsp;&nbsp;Welcome, <span style=\"font-weight:bold\">$userName</span> to your <a href='main.php'>CSpace</a>.<br/>&nbsp;&nbsp;Current login: $lastLogin<br/>&nbsp;&nbsp;Points earned: <a href='points.php'>$points</a></td><td valign=\"middle\">&nbsp;&nbsp;</td><td valign=\"middle\">&nbsp;&nbsp;You have <a href='projects.php?userID=$userID'>$projectNums projects</a> and <a href='collaborators.php?userID=1'>$collabNums collaborators</a>.<br/>&nbsp;&nbsp;<span id=\"currProj\"></span><br/>&nbsp;&nbsp;<a href='projects.php?userID=$userID'>Select a different project.</a></td></tr></table></div>";
				?>
                </div>

    <div class="right" style="position: fixed; top: 25px; right: 20px;">

    	<p class="flip" style="float: right;"><!-- <img src="../assets/img/menu_dark.png" /> --> <?php echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/img/'.$avatar.'" width=45 height=45 style="vertical-align:middle;border:3px solid #000;">'; ?><br/><img src="../assets/img/arrow.png"/></p>
        <div style="clear:both;"></div>
        <div class="panel">
        	<table>
            	<tr>
                	<td valign="top" width="150">
                    	<b>Collaborators</b><br/>
                        <a href="../addCollaborator.php">Add</a>
                        <a href="../currentCollaborators.php">View</a><br/>

                        <b>Projects</b>
                        <a href="../createProject.php">Create</a>
                        <a href="../projects.php">Select</a>
                        <a href="../showPublicProjs.php">Join</a>
                    </td>
                	<td valign="top" width="150">
                    	<b>Sharing</b>
                        <a href="../showRecommendations.php">Recommendations</a>
                        <a href="../interProject.php">Inter-project</a><br/>

                   		<b>Workspace</b>
                        <a href="../etherpad.php">Editor</a>
                        <a href="../files.php">Files</a>
                        <a href="../printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                    	<b>Settings</b>
                        <a href="../profile.php">Profile</a>
                        <a href="../settings.php">Options</a>
                    </td>
                </tr>
                <!-- <tr height="10">
                	<td></td>
                </tr> -->
                <!-- <tr>
                	<td colspan=3 valign="top">
                    	<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">CSpace</a>&nbsp;&nbsp;
                		<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">Log out</a>
                    </td>
				</tr> -->
            </table>
        </div>
    </div>

</div>

<div id="container">
<h3>Print Reports</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
?>
<table class="body" width=100%>
	<?php
		require_once("../connect.php");
		$query = "SELECT title FROM projects WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$query1 = "SELECT * FROM queries WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$numSearches = mysql_num_rows($results1);
		$query2 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND status=1";
		$results2 = mysql_query($query2) or die(" ". mysql_error());
		$numPages = mysql_num_rows($results2);
		$query3 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND result=1 AND status=1";
		$results3 = mysql_query($query3) or die(" ". mysql_error());
		$numBookmarks = mysql_num_rows($results3);
		$query4 = "SELECT * FROM snippets WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results4 = mysql_query($query4) or die(" ". mysql_error());
		$numSnippets = mysql_num_rows($results4);
		$query5 = "SELECT * FROM annotations WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results5 = mysql_query($query5) or die(" ". mysql_error());
		$numAnnotations = mysql_num_rows($results5);
	?>
	<tr><td>Displaying objects for project <span style="font-weight:bold"><?php echo $title?></span></td><td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=all', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print All</span></td></tr>
	<tr><td colspan=2><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wSearches');">Show/hide <span style="font-weight:bold"> <?php echo $numSearches;?> searches</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=queries', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Searches</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wSearches" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
					$queryText = $line1['query'];
					$source = $line1['source'];
					$url = $line1['url'];
					$date = $line1['date'];
					$cUserID = $line1['userID'];
					$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
					$userName = $lineU['username'];
					echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$queryText</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wPages');">Show/hide <span style="font-weight:bold"> <?php echo $numPages;?> webpages</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=pages', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Webpages</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wPages" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				$query2 = "SELECT * FROM pages WHERE projectID='$projectID' AND status=1 GROUP BY url";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				while ($line2 = mysql_fetch_array($results2, MYSQL_ASSOC)) {
					$url = $line2['url'];
					$pTitle = $line2['title'];
					$source = $line2['source'];
					$date = $line2['date'];
					$cUserID = $line2['userID'];
					$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
					$userName = $lineU['username'];
					echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wBookmarks');">Show/hide <span style="font-weight:bold"> <?php echo $numBookmarks;?> bookmarks</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=bookmarks', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Bookmarks</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wBookmarks" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				$query3 = "SELECT * FROM pages WHERE projectID='$projectID' AND result=1 AND status=1 AND status=1 GROUP BY url";
				$results3 = mysql_query($query3) or die(" ". mysql_error());
				while ($line3 = mysql_fetch_array($results3, MYSQL_ASSOC)) {
					$url = $line3['url'];
					$pTitle = $line3['title'];
					$source = $line3['source'];
					$date = $line3['date'];
					$cUserID = $line3['userID'];
					$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
					$userName = $lineU['username'];
					echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wSnippets');">Show/hide <span style="font-weight:bold"> <?php echo $numSnippets;?> snippets</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=snippets', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Snippets</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wSnippets" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				while ($line4 = mysql_fetch_array($results4, MYSQL_ASSOC)) {
					$url = $line4['url'];
					$cUserID = $line4['userID'];
					$date = $line4['date'];
					$snippet = stripslashes($line4['snippet']);
					$note = stripslashes($line4['note']);
					$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
					$userName = $lineU['username'];
					echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
					echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$snippet<br/><span style=\"color:gray;\">$note</span></td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wAnnotations');">Show/hide <span style="font-weight:bold"> <?php echo $numAnnotations;?> annotations</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="window.open('printObjects.php?objects=annotations', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Annotations</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wAnnotations" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				while ($line5 = mysql_fetch_array($results5, MYSQL_ASSOC)) {
					$url = $line5['url'];
					$cUserID = $line5['userID'];
					$date = $line5['date'];
					$note = stripslashes($line5['note']);
					$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
					$userName = $lineU['username'];
					echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
					echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$note</td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
</table>
<?php
	}
?>
</div>

</body>
</html>
