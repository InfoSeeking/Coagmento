<?php
	if ((isset($_SESSION['CSpace_userID']))) {
		require_once("functions.php");
		require_once("../connect.php");
                $userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		$orderBy = $_SESSION['orderByQueries'];
                echo "<a alt=\"Refresh\" class=\"cursorType\" onclick=\"javascript:reload('sidebarComponents/searches.php','queriesBox')\" style=\"font-size:12px; font-weight: bold; color:orange\">Reload</a>\n";
                echo "<div id=\"floatQueryLayer\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
                echo "<div id=\"floatQueryLayerDelete\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<table width=100% cellspacing=0>\n";
		echo "<tr>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','userName asc','queriesBox','searches.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','userName desc','queriesBox','searches.php')\"></td>";
		echo "<td align=\"left\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','query asc','queriesBox','searches.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','query desc','queriesBox','searches.php')\"></td>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','finalRating asc','queriesBox','searches.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','finalRating desc','queriesBox','searches.php')\"></td>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','queryID asc','queriesBox','searches.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Queries','queryID desc','queriesBox','searches.php')\"></td>";
		//echo "<td></td>";
		echo "</tr>";
		$query = "SELECT *, (SELECT DISTINCT userName FROM users where users.userID = queries.userID) AS userName, (SELECT sum(value) from rating where active = 1 and projectID='$projectID' and idResource = queryID and type = 'queries' group by idResource)/(SELECT count(*) from rating where active = 1 and projectID='$projectID' and idResource = queryID and type = 'queries' group by idResource) as finalRating FROM queries WHERE status = 1 AND projectID='$projectID' order by $orderBy";
		$results = mysql_query($query) or die(" ". mysql_error());
		$bgColor = '#E8E8E8';
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$queryID = $line['queryID'];
			$userName = $line['userName'];
                        $userIDItem = $line['userID'];
                        $source= $line['source'];
			$finalRating = $line['finalRating'];
			$queryVal = stripslashes($line['query']);
			$time = $line['time'];
                        $date = strtotime($line['date']);
                        $date = strftime("%m/%d", $date);
			$url = $line['url'];
			$queryAux = substr($queryVal, 0, 11)."-".substr($source, 0, 4);
				
			echo "<tr style=\"background:$bgColor;\"><td><span style=\"font-size:10px\">$userName</span> </td><td><span style=\"font-size:10px\">";
			if ($url)
				echo "<font color=blue><a onclick=\"javascript:ajaxpage('sidebarComponents/insertAction.php?action=sidebar-query&value='+$queryID,null)\" href=\"$url\" class=\"tt\" target=_content style=\"font-size:10px\">$queryAux</a></span></td>\n";
			else
				echo "$queryAux</span></td>\n";

			echo "<input type=\"hidden\" id=\"queryValue$queryID\" value=\"$queryVal\">";
                        echo "<input type=\"hidden\" id=\"time$queryID\" value=\"$time\">";
			$ratingRepresentation = getRatingRepresentation($finalRating,$queryID,'queries','floatQueryLayer','queriesBox','searches.php');
			echo "<td align=\"center\">$ratingRepresentation</td>";
			echo "<td align=\"right\" onmouseover=\"javascript:showTime('floatQueryLayer',null,'$queryID')\" onmouseout=\"javascript:hideLayer('floatQueryLayer')\"><span style=\"font-size:10px\">$date</span></td>";
			//echo "<td align=\"right\"><img src=\"images/copy.gif\" height=\"18\" width=\"18\" alt=\"Copy\" class=\"cursorType\" onclick=\"javascript:copyToClipboard('queryValue$queryID')\"></td>";

                        if ($userID==$userIDItem)
                            echo "<td align=\"right\" class=\"cursorType\" onclick=\"javascript:deleteItem('floatQueryLayerDelete',null,'$queryID','queries','queriesBox','searches.php')\"><span style=\"font-size:10px; color:red; font-weight: bold \"> <a style=\"font-size:10px; color:$bgColor\"> - </a>X</span></td>";
                        else
                            echo "<td></td>";

			echo "</tr>";

			if ($bgColor == '#E8E8E8')
				$bgColor = '#FFFFFF';
			else
				$bgColor = '#E8E8E8';
		}
		echo "</table>\n";
		mysql_close($dbh);
		}
	else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/loginOnSideBar.php\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>