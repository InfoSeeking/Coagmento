<?php
	if ((isset($_SESSION['CSpace_userID']))) {
		require_once("functions.php");
		require_once("../connect.php");
                $userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		$orderBy = $_SESSION['orderByFiles'];
                echo "<table width=100% cellspacing=0>\n";
                echo "<tr>";
                echo "<td align=\"left\"><a alt=\"Refresh\" class=\"cursorType\" onclick=\"javascript:reload('sidebarComponents/files.php','filesBox')\" style=\"font-size:12px; font-weight: bold; color:orange\">Reload</a></td><td align=\"right\"><a alt=\"Upload\" target=\"_content\" href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/files.php\" style=\"font-size:12px; font-weight: bold; color:brown\">Upload</a></td>\n";
                echo "</tr>";
                echo "</table>";
		echo "<div id=\"floatFileLayer\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<div id=\"floatFileLayerDelete\" style=\"position:absolute;  width:150px;  padding:16px;background:#FFFFFF;  border:2px solid #2266AA;  z-index:100; display:none \"></div>";
		echo "<table width=100% cellspacing=0>\n";
		echo "<tr>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','userName asc','filesBox','files.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','userName desc','filesBox','files.php')\"></td>";
		echo "<td align=\"left\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','name asc','filesBox','files.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','name desc','filesBox','files.php')\"></td>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','finalRating asc','filesBox','files.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','finalRating desc','filesBox','files.php')\"></td>";
		echo "<td align=\"center\"><img src=\"assets/images/asc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Asc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','id asc','filesBox','files.php')\"><span style=\"font-size:10px; color:#FFFFFF\">-</span><img src=\"assets/images/desc_sidebar.gif\" height=\"10\" width=\"10\" alt=\"Desc\" class=\"cursorType\" onclick=\"javascript:changeOrder('Files','id desc','filesBox','files.php')\"></td>";
		echo "<td></td>";
		echo "</tr>";
		$query = "SELECT *, (SELECT userName FROM users where users.userID = files.userID) AS userName, (SELECT sum(value) from rating where active = 1 and projectID='$projectID' and idResource = id and type = 'Files' group by idResource)/(SELECT count(*) from rating where active = 1 and projectID='$projectID' and idResource = id and type = 'Files' group by idResource) as finalRating FROM files WHERE projectID='$projectID' AND status=1 order by $orderBy";
                $results = $connection->commit($query);
		$bgColor = '#E8E8E8';
		while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
			$fileID = $line['id'];
			$userName = $line['userName'];
                        $userIDItem = $line['userID'];
                        $fileName = $line['fileName'];
			$finalRating = $line['finalRating'];
			$file = stripslashes($line['name']);

			$url = $line['url'];
			$title = $file;
			$time = $line['time'];
                        $date = strtotime($line['date']);
                        $date = strftime("%m/%d", $date);


                            if (strlen($title)>25) {
				$title = substr($file, 0, 20);
				$title = $title . '..';
                            }


			echo "<tr style=\"background:$bgColor;\"><td><span style=\"font-size:10px\">$userName</span> </td><td><span style=\"font-size:10px\">";
      echo "<font color=blue><a onclick=\"javascript:ajaxpage('sidebarComponents/insertAction.php?action=sidebar-file&value='+$fileID,null)\" href=\"files/".$fileName."\" class=\"tt\" target=_content style=\"font-size:10px\">$title</a></span></td>\n";

			echo "<input type=\"hidden\" id=\"fileValue$fileID\" value=\"$file\">";
                        echo "<input type=\"hidden\" id=\"source$fileID\" value=\"$title\">";
                        echo "<input type=\"hidden\" id=\"url$fileID\" value=\"$url\">";
                        echo "<input type=\"hidden\" id=\"time$fileID\" value=\"$time\">";
			$ratingRepresentation = getRatingRepresentation($finalRating, $fileID,'Files','floatFileLayer','filesBox','files.php');
			echo "<td align=\"center\">$ratingRepresentation</td>";
			echo "<td align=\"center\" onmouseover=\"javascript:showTime('floatFileLayer',null,'$fileID')\" onmouseout=\"javascript:hideLayer('floatFileLayer')\"><span style=\"font-size:10px\">$date</span></td>";
      if ($userID==$userIDItem)
          echo "<td align=\"right\" class=\"cursorType\" onclick=\"javascript:deleteItem('floatFileLayerDelete',null,'$fileID','files','filesBox','files.php')\"><span style=\"font-size:10px; color:red; font-weight: bold \"> <a style=\"font-size:10px; color:$bgColor\"> - </a>X</span></td>";
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
