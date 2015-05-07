<?php
	require_once("../connect.php");
	$userID = 2;
?>



<script type="text/javascript">
function showUser(projects, objects)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getuser2.php?proj="+projects+"obj="+objects,true);
xmlhttp.send();
}
</script>



<form>

<b>TIMELINE</b> &nbsp;&nbsp;

<select name="projects" id="projects">
<?php
	$query = "SELECT * FROM memberships WHERE userID='$userID'";
	$results = $connection->commit($query);
	while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {	
		$projID = $line['projectID'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$title = $line1['title'];
		echo "<option value=\"$projID\" ";
		if ($projID==$projectID)
			echo "SELECTED";
			echo ">$title</option>\n";
	}
?>
</select>

<select name="objects" id="objects">
<option value="pages">Webpages</option>
<option value="result">Bookmarks</option>
<option value="queries">Searches</option>
<option value="snippets">Snippets</option>
<option value="annotations">Annotations</option>
</select>

<input type="submit" value="Submit" onclick="showUser(document.getElementById('projects').value, document.getElementById('objects').value)">

</form>

<!-- join pages and projects table (by projectid) to get project names and page details -->
<br />
<div id="txtHint"><b>Choose a project and object type!</b></div>

