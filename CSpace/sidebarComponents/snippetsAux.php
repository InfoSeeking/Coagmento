<?php
	if ((isset($_SESSION['CSpace_userID']))) {
		require_once("functions.php");
		require_once("../connect.php");
                $userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		$orderBy = $_SESSION['orderBySnippets'];
                echo "<a alt=\"Refresh\" class=\"cursorType\" onclick=\"javascript:reload('sidebarComponents/snippets.php','snippetsBox')\" style=\"font-size:12px; font-weight: bold; color:orange\">Reload</a>\n";
		echo "<div id=\"floatSnippetLayer\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<div id=\"floatSnippetLayerDelete\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<table width=100% cellspacing=0>\n";
		echo "<tr>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','userName asc','snippetsBox','snippets.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','userName desc','snippetsBox','snippets.php')\"></td>";
		echo "<td align=\"left\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','title asc','snippetsBox','snippets.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','title desc','snippetsBox','snippets.php')\"></td>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','finalRating asc','snippetsBox','snippets.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','finalRating desc','snippetsBox','snippets.php')\"></td>";
		echo "<td align=\"center\"><img src=\"images/asc.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','snippetID asc','snippetsBox','snippets.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"images/desc.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Snippets','snippetID desc','snippetsBox','snippets.php')\"></td>";
		//echo "<td></td>";
		echo "</tr>";
		$query = "SELECT *, (SELECT userName FROM users where users.userID = snippets.userID) AS userName, (SELECT sum(value) from rating where active = 1 and projectID='$projectID' and idResource = snippetID and type = 'snippets' group by idResource)/(SELECT count(*) from rating where active = 1 and projectID='$projectID' and idResource = snippetID and type = 'snippets' group by idResource) as finalRating FROM snippets WHERE projectID='$projectID' AND status=1 order by $orderBy";
		$results = mysql_query($query) or die(" ". mysql_error());
		$bgColor = '#E8E8E8';
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$snippetID = $line['snippetID'];
			$userName = $line['userName'];
                        $userIDItem = $line['userID'];
			$finalRating = $line['finalRating'];
			$note = $line['note'];
			$snippet = stripslashes($line['snippet']);

			$url = $line['url'];
			$title = stripslashes($line['title']);
			$type = $line['type'];
			$time = $line['time'];
                        $date = strtotime($line['date']);
                        $date = strftime("%m/%d", $date);
                        $noteAux = substr($note, 0, 20);

                        if ($noteAux!="")
                            $title = $noteAux . '..';
                        else
                        {
                            if (!$title)
                            	$title = $url;

                            if (strlen($title)>25) {
				$title = substr($title, 0, 20);
				$title = $title . '..';
                            }
                        }
				
			echo "<tr style=\"background:$bgColor;\"><td><span style=\"font-size:10px\">$userName</span> </td><td><span style=\"font-size:10px\">";
                        //echo "<a alt=\"View\" class=\"cursorType\" onclick=\"javascript:showSnippet('floatSnippetLayer',null,'$snippetID','$type')\" style=\"font-size:10px; color:blue\">$title</a></span></td>\n";
                        $viewSnipetOnWindow = "window.open('sidebarComponents/viewSnippet.php?value=$snippetID&action=show_snippet','Snippet View','statusbar=0,menubar=0,resizable=yes,scrollbars=yes,width=600,height=550,left=600')";
                        echo "<a alt=\"View\" class=\"cursorType\" onclick=\"javascript:$viewSnipetOnWindow\" onmouseover=\"javascript:showSnippet('floatSnippetLayer',null,'$snippetID','$type')\" onmouseout=\"javascript:hideLayer('floatSnippetLayer')\" style=\"font-size:10px; color:blue\">$title</a></span></td>\n";
//			if ($url)
//				echo "<font color=blue><a alt=\"View\" class=\"cursorType\" onclick=\"javascript:showSnippet('floatSnippetLayer',null,'$snippetID','$type')\" style=\"font-size:10px\">$title</a></span></td>\n";
//			else
//				echo "<font color=blue><a alt=\"View\" class=\"cursorType\" onclick=\"javascript:showSnippet('floatSnippetLayer',null,'$snippetID','$type')\" style=\"font-size:10px\">$snippet</a></span></td>\n";
			
			//$fullSnippet = "[Source: " . $url . "] || ".$snippet;
			
			echo "<input type=\"hidden\" id=\"snippetValue$snippetID\" value=\"$snippet\">";
			echo "<input type=\"hidden\" id=\"note$snippetID\" value=\"$note\">";
                        echo "<input type=\"hidden\" id=\"source$snippetID\" value=\"$title\">";
                        echo "<input type=\"hidden\" id=\"url$snippetID\" value=\"$url\">";
                        echo "<input type=\"hidden\" id=\"time$snippetID\" value=\"$time\">";
			$ratingRepresentation = getRatingRepresentation($finalRating, $snippetID,'snippets','floatSnippetLayer','snippetsBox','snippets.php');
			echo "<td align=\"center\">$ratingRepresentation</td>";
			echo "<td align=\"right\" onmouseover=\"javascript:showTime('floatSnippetLayer',null,'$snippetID')\" onmouseout=\"javascript:hideLayer('floatSnippetLayer')\"><span style=\"font-size:10px\">$date</span></td>";
                        if ($userID==$userIDItem)
                            echo "<td align=\"right\" class=\"cursorType\" onclick=\"javascript:deleteItem('floatSnippetLayerDelete',null,'$snippetID','snippets','snippetsBox','snippets.php')\"><span style=\"font-size:10px; color:red; font-weight: bold \"> <a style=\"font-size:10px; color:$bgColor\"> - </a>X</span></td>";
                        else
                            echo "<td></td>";

                        /*echo "<td align=\"right\">";
                        if ($url)
                                echo "<font color=blue><a href=\"$url\" class=\"tt\" target=_content style=\"font-size:10px\"><img src=\"images/link.gif\" height=\"18\" width=\"18\" alt=\"Go\" class=\"cursorType\" /></a>\n";
			else
				echo "<img src=\"images/blank.gif\" height=\"18\" width=\"18\">";
			
			echo "<span style=\"font-size:10px; color:$bgColor\">-</span><img src=\"images/copy.gif\" height=\"18\" width=\"18\" alt=\"Copy\" class=\"cursorType\" onclick=\"javascript:copyToClipboard('snippetValue$snippetID')\"></td>"; 
                        */
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