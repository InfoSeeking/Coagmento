<?php /*
include('user_agent.php'); // Redirecting http://mobile.site.info
// site.com data */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<?php 
  include('func.php');
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

    	<p class="flip" style="float: right;"><!-- <img src="menu.png" /> --> <?php echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/img/'.$avatar.'" width=45 height=45 style="vertical-align:middle;border:3px solid #000;">'; ?><br/><img src="arrow.png"/></p>
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
<h3>Inter-project Analysis</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
?>
<!-- <table class="body" width=100%>
	<tr bgcolor="#EFEFEF">
		<td colspan=2>
			<span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
			Coagmento can identify some of the common themese among your active project. This could help you in exploring patterns in your browsing, searches, collected objects, and even your collaborators.
			</div>
		</td>
	</tr>
</table> -->
<table class="body" width=100%>
	<?php
		require_once("../connect.php");
		
		// Find collaborators that are in multiple projects
		$query1 = "SELECT mem2.*,count(*) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' group BY mem2.userID";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$commonCollab = 0;
		while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
			$num = $line1['num'];
			if ($num>1)
				$commonCollab++;
		}
		
		// Find queries that are in multiple projects
		$query2 = "select count(*) as num from queries as q1,queries as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.query=q2.query and q1.projectID!=q2.projectID group by q1.query,q2.query";
		$results2 = mysql_query($query2) or die(" ". mysql_error());
		$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
		$commonSearches = $line2['num'];
		
		// Find webpages that are in multiple projects
		$query3 = "select * from pages as q1,pages as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.url=q2.url and q1.projectID!=q2.projectID and q1.title!='Coagmento' and q1.url!='about:blank' group by q1.url,q2.url";
		$results3 = mysql_query($query3) or die(" ". mysql_error());
		$commonPages = mysql_num_rows($results3);
		
		// Find bookmarks that are in multiple projects
		$query4 = "select * from pages as q1,pages as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.url=q2.url and q1.projectID!=q2.projectID and q1.title!='Coagmento' and q1.url!='about:blank' and q1.result=1 group by q1.url,q2.url";
		$results4 = mysql_query($query4) or die(" ". mysql_error());
		$commonBookmarks = mysql_num_rows($results4);
	?>
	<tr>
		<td><span style="font-weight:bold">Common Collaborators</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cCollab');">You have <span style="font-weight:bold"><?php echo $commonCollab;?></span> collaborators in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cCollab" style="display:none;text-align:left;font-size:11px;">
			<?php
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
					$num = $line1['num'];
					if ($num>1) {
						$cUserID = $line1['userID'];
						$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
						$resultsU = mysql_query($queryU) or die(" ". mysql_error());
						$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
						$userName = $lineU['firstName'] . " " . $lineU['lastName'];
						$avatar = $lineU['avatar'];
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../../img/$avatar\" width=20 height=20 /> <a href='showCollaborator.php?userID=$cUserID'>$userName</a> <font color=\"gray\"> for projects</font>: ";
						$queryU = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' AND mem2.userID='$cUserID'";
						$resultsU = mysql_query($queryU) or die(" ". mysql_error());
						while ($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
							$cProjectID = $lineU['projectID'];
							$queryP = "SELECT access FROM memberships WHERE projectID='$cProjectID' AND userID='$userID'";
							$resultsP = mysql_query($queryP) or die(" ". mysql_error());
							$lineP = mysql_fetch_array($resultsP, MYSQL_ASSOC);
							$access = $lineP['access'];
							$queryQ = "SELECT title FROM projects WHERE projectID='$cProjectID'";
							$resultsQ = mysql_query($queryQ) or die(" ". mysql_error());
							$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
							echo $lineQ['title'];
							if ($access==1)
								echo " (<a href='collaborators.php?remove=$cUserID&projID=$cProjectID' style='color:#ff0000; text-decoration: none;'>X</a>)";
							echo ", ";
						}
						echo "<br/>";
					}
				}
			?>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
		<tr>
		<td><span style="font-weight:bold">Common Searches</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cSearches');">You have <span style="font-weight:bold"><?php echo $commonSearches;?></span> searches that appear in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cSearches" style="display:none;text-align:left;font-size:11px;">
			<?php
				$query2 = "select q1.query from queries as q1,queries as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.userID=q2.userID and q1.query=q2.query and q1.projectID!=q2.projectID GROUP BY query";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				while($line2 = mysql_fetch_array($results2, MYSQL_ASSOC)) {
					$queryText = $line2['query'];
					echo "$queryText: ";
					$queryU = "SELECT * FROM queries WHERE query='$queryText' AND userID='$userID' GROUP BY projectID";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)){
						$pID = $lineU['projectID'];
						$source = $lineU['source'];
						$url = $lineU['url'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = mysql_query($queryQ) or die(" ". mysql_error());
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						echo $lineQ['title']. " (<a href=\"$url\" style=\"color:green;text-decoration:underline;cursor:pointer;font-size:11px;\" target=_blank>". $source."</a>), ";
					}
					echo "<br/>";
				}
			?>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
		<tr>
		<td><span style="font-weight:bold">Common Webpages</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cPages');">You have <span style="font-weight:bold"><?php echo $commonPages;?></span> webpages that you have visited for multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cPages" style="display:none;text-align:left;font-size:11px;">
			<table>
			<tr><td><span style="font-weight:bold;font-size:11px;">Webpage</span></td><td><span style="font-weight:bold;font-size:11px;">Projects</span></td></tr>
			<?php
				while($line3 = mysql_fetch_array($results3, MYSQL_ASSOC)) {
					$title = $line3['title'];
					$url = $line3['url'];
					echo "<tr><td><a style=\"font-size:11px;\" href=\"$url\" target=_blank>$title</a></td><td><span style=\"font-size:11px;\">";
					$queryU = "SELECT * FROM pages WHERE url='$url' AND userID='$userID' GROUP BY projectID";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
						$pID = $lineU['projectID'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = mysql_query($queryQ) or die(" ". mysql_error());
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						$pTitle = $lineQ['title'];
						echo "$pTitle, ";
					}
					echo "</span></td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td><span style="font-weight:bold">Common Bookmarks</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cBookmarks');">You have <span style="font-weight:bold"><?php echo $commonBookmarks;?></span> bookmarks in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cBookmarks" style="display:none;text-align:left;font-size:11px;">
			<table>
			<tr><td><span style="font-weight:bold;font-size:11px;">Webpage</span></td><td><span style="font-weight:bold;font-size:11px;">Projects</span></td></tr>
			<?php
				while($line4 = mysql_fetch_array($results4, MYSQL_ASSOC)) {
					$title = $line4['title'];
					$url = $line4['url'];
					echo "<tr><td><a style=\"font-size:11px;\" href=\"$url\" target=_blank>$title</a></td><td><span style=\"font-size:11px;\">";
					$queryU = "SELECT * FROM pages WHERE url='$url' AND userID='$userID' GROUP BY projectID";
					$resultsU = mysql_query($queryU) or die(" ". mysql_error());
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
						$pID = $lineU['projectID'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = mysql_query($queryQ) or die(" ". mysql_error());
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						$pTitle = $lineQ['title'];
						echo "$pTitle, ";
					}
					echo "</span></td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
</table>
<?php
	}
?>
</div>

</body>
</html>