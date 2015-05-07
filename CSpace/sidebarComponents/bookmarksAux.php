<?php
	if ((isset($_SESSION['CSpace_userID']))) {
		require_once("functions.php");
		require_once("../connect.php");
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		$orderBy = $_SESSION['orderByPages'];
                echo "<a alt=\"Refresh\" class=\"cursorType\" onclick=\"javascript:reload('sidebarComponents/bookmarks.php','pagesBox')\" style=\"font-size:12px; font-weight: bold; color:orange\">Reload</a>\n";
                echo "<div id=\"floatBookmarkLayer\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
                echo "<div id=\"floatBookmarkLayerDelete\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<table width=100% cellspacing=0>\n";
		echo "<tr>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','userName asc','pagesBox','bookmarks.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','userName desc','pagesBox','bookmarks.php')\"></td>";
		echo "<td align=\"left\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','title asc','pagesBox','bookmarks.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','title desc','pagesBox','bookmarks.php')\"></td>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','finalRating asc','pagesBox','bookmarks.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','finalRating desc','pagesBox','bookmarks.php')\"></td>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','pageID asc','pagesBox','bookmarks.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Pages','pageID desc','pagesBox','bookmarks.php')\"></td>";
		//echo "<td></td>";
		echo "</tr>";
		$query = "SELECT *, (SELECT DISTINCT userName FROM users where users.userID = pages.userID) AS userName, (SELECT sum(value) from rating where active = 1 and projectID='$projectID' and idResource = pageID and type = 'pages' group by idResource)/(SELECT count(*) from rating where active = 1 and projectID='$projectID' and idResource = pageID and type = 'pages' group by idResource) as finalRating FROM pages WHERE projectID='$projectID' AND result=1 order by $orderBy";
                $results = $connection->commit($query);
		$bgColor = '#E8E8E8';
		while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
			$pageID = $line['pageID'];
			$userName = $line['userName'];
                        $userIDItem = $line['userID'];
			$finalRating = $line['finalRating'];
			$note = $line['note'];
			$title = stripslashes($line['title']);
			$titleAux = substr($title, 0, 15);
			$time = $line['time'];
                        $date = strtotime($line['date']);
                        $date = strftime("%m/%d", $date);
			$url = $line['url'];
                        $noteAux = substr($note, 0, 15);

                        if ($noteAux!="")
                            $titleAux = $noteAux . '..';

			echo "<tr style=\"background:$bgColor;\"><td><span style=\"font-size:10px\">$userName</span> </td><td><span style=\"font-size:10px\">";
			if ($url)
				echo "<font color=blue><a href=\"$url\" onclick=\"javascript:ajaxpage('sidebarComponents/insertAction.php?action=sidebar-page&value='+$pageID,null)\" onmouseover=\"javascript:showPage('floatBookmarkLayer',null,'$pageID')\" onmouseout=\"javascript:hideLayer('floatBookmarkLayer')\" class=\"tt\" target=_content style=\"font-size:10px\">$titleAux</a></span></td>\n";
			else
				echo "<p onmouseover=\"javascript:showPage('floatBookmarkLayer',null,'$pageID')\" onmouseout=\"javascript:hideLayer('floatBookmarkLayer')\">$titleAux</p></span></td>\n";

			echo "<input type=\"hidden\" id=\"note$pageID\" value=\"$note\">";
			echo "<input type=\"hidden\" id=\"pageValue$pageID\" value=\"$url\">";
                        echo "<input type=\"hidden\" id=\"title$pageID\" value=\"$titleAux\">";
                        echo "<input type=\"hidden\" id=\"time$pageID\" value=\"$time\">";
			$ratingRepresentation = getRatingRepresentation($finalRating,$pageID,'pages','floatBookmarkLayer','pagesBox','bookmarks.php');
			echo "<td align=\"center\">$ratingRepresentation</td>";
			echo "<td align=\"right\" onmouseover=\"javascript:showTime('floatBookmarkLayer',null,'$pageID')\" onmouseout=\"javascript:hideLayer('floatBookmarkLayer')\"><span style=\"font-size:10px\">$date</span></td>";
                        if ($userID==$userIDItem)
                            echo "<td align=\"right\" class=\"cursorType\" onclick=\"javascript:deleteItem('floatBookmarkLayerDelete',null,'$pageID','pages','pagesBox','bookmarks.php')\"><span style=\"font-size:10px; color:red; font-weight: bold \"> <a style=\"font-size:10px; color:$bgColor\"> - </a>X</span></td>";
                        else
                            echo "<td></td>";

			echo "</tr>";

			if ($bgColor == '#E8E8E8')
				$bgColor = '#FFFFFF';
			else
				$bgColor = '#E8E8E8';
		}
		echo "</table>\n";
		
		}
	else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/loginOnSideBar.php\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>
