<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>PHP form select box example</title>

<style>
label,a
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px;
}
.details {
	font-family: arial;
	float: left;
	padding-left: 20px;
	padding-right: 20px;
}
.form {
	float: left;
}
</style>

<script type="text/javascript">
function filterData(str)
{
if (str=="")
  {
  document.getElementById("box_right").innerHTML="Click a thumbnail to see details.";
  return;
  }
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
    document.getElementById("bottom").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("bottom").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;"><img src="../assets/img/loading.gif"/></a>'; }
  }
xmlhttp.open("GET","filterData.php?q="+str,true);
xmlhttp.send();
}
</script>
</head>

<body>
<?php
	require_once("../connect.php");
	$userID = 2;
?>

<?php
	if(isset($_POST['formSubmit']))
	{
		$varProjects = $_POST['projects'];
		$varObjects = $_POST['objects'];

		echo '<div class="details">';
		echo 'Viewing <b>';
		echo $varObjects;
		echo '</b> from project <b>';
		echo $varProjects;
		echo '</b>';

		echo '</div>';

		echo 'filterData('.$varProjects.')';

		/*if(empty($varCountry))
		{
			$errorMessage = "<li>You forgot to select a country!</li>";
		}*/
	}
?>

filterData('<? $varProjects ?>');

<div class="form">
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

	<select name="projects">
	<?php
        echo '<option value="">Project</option>';
        $query = "SELECT * FROM memberships WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
            $projID = $line['projectID'];
            $query1 = "SELECT * FROM projects WHERE projectID='$projID'";
            $results1 = mysql_query($query1) or die(" ". mysql_error());
            $line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
            $title = $line1['title'];
            echo "<option value=\"$title\" ";
            if ($projID==$projectID)
                echo "SELECTED";
                echo ">$title</option>\n";
        }
    ?>
    </select>

	<select id="objects" name="objects">
      <option value="">Objects</option>
      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
    </select>

	<input type="submit" name="formSubmit" value="Submit" />
</form>
</div><br/><br/>

<div class="bottom">
</div>

</body>
</html>
