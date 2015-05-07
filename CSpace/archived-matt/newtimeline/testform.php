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
}
.form {
	float: left;
	padding-left: 20px;
}
#box_bottom {
	font-family: arial;
	padding-left: 20px;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
function filterData(str)
{
if (str=="")
  {
  document.getElementById("box_bottom").innerHTML="HELLLLOOO";
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
    document.getElementById("box_bottom").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_bottom").innerHTML = '<img src="../assets/img/loading.gif"/>'; }
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
		$varYears = $_POST['years'];
		$varMonths = $_POST['months'];

		$str = $varProjects.'-'.$varObjects.'-'.$varYears.'-'.$varMonths;

		echo '<div class="details">';
		echo 'Viewing <b>';
		echo $varObjects;
		echo '</b> from project <b>';
		echo $varProjects;
		echo '</b> from <b>';
		switch ($varMonths) {
			case "all":
				echo "all";
				break;
			case 01:
				echo "Jan";
				break;
			case 02:
				echo "Feb";
				break;
			case 03:
				echo "Mar";
				break;
			case 04:
				echo "Apr";
				break;
			case 05:
				echo "May";
				break;
			case 06:
				echo "Jun";
				break;
			case 07:
				echo "Jul";
				break;
			case 08:
				echo "Aug";
				break;
			case 09:
				echo "Sept";
				break;
			case 10:
				echo "Oct";
				break;
			case 11:
				echo "Nov";
				break;
			case 12:
				echo "Dec";
				break;
		}
		echo ' ';
		echo $varYears;
		echo '</b>';

		echo '</div>';

		echo '<script type="text/javascript">';
		echo 'filterData("'.$str.'")';
		echo '</script>';

		/*if(empty($varCountry))
		{
			$errorMessage = "<li>You forgot to select a country!</li>";
		}*/
	}
?>

<div class="form">
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

	<select name="projects">
	<?php
		echo '<option value="all">All Projects</option>';
        $query = "SELECT * FROM memberships WHERE userID='$userID'";
        $results = $connection->commit($query);
        while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
            $projID = $line['projectID'];
            $query1 = "SELECT * FROM projects WHERE projectID='$projID'";
            $results1 = $connection->commit($query1);
            $line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
            $title = $line1['title'];
            echo "<option value=\"$title\" ";
            if ($projID==$projectID)
                echo "SELECTED";
                echo ">$title</option>\n";
        }
    ?>
    </select>

	<select id="objects" name="objects">
      <option value="all">All Objects</option>
      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
    </select>

    <select id="years" name="years">
      <option value="all">All Years</option>
	  <?
      $sql_year="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
      $result_year=mysql_query($sql_year);

      $options="";
      $y=array();

      while ($row=mysqli_fetch_array($result_year)) {
          $date=$row["date"];
          $year = date("Y",strtotime($date));

          if (!in_array($year, $y)){
              $y[] = $year;
              $options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';
          }

      }
      echo $options;
      ?>
    </select>

    <select id="months" name="months">
      <option value="all">All Months</option>
      <?
	  $sql_month="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet')";
	  $result_month=mysql_query($sql_month);

	  $m=array();

	  while ($row2=mysqli_fetch_array($result_month)) {
		  $date2=$row2["date"];
		  $month = date("m",strtotime($date2));

		  if (!in_array($month, $m)){
		  	  if($month == 01 || $month == 02 || $month == 03 || $month == 04 || $month == 05 || $month == 06 || $month == 07 || $month == 08 || $month == 09 || $month == 10 || $month == 11 || $month == 12) {
			  	$m[] = $month;
			  }
		  }
	  }

	  sort($m);

	  for($i = 0; $i < count($m); ++$i) {
		  echo "<option value=".$m[$i].">";
		  if($m[$i]==01) { echo "Jan"; }
		  elseif($m[$i]==02) { echo "Feb"; }
		  elseif($m[$i]==03) { echo "Mar"; }
		  elseif($m[$i]==04) { echo "Apr"; }
		  elseif($m[$i]==05) { echo "May"; }
		  elseif($m[$i]==06) { echo "Jun"; }
		  elseif($m[$i]==07) { echo "Jul"; }
		  elseif($m[$i]==08) { echo "Aug"; }
		  elseif($m[$i]==09) { echo "Sept"; }
		  elseif($m[$i]==10) { echo "Oct"; }
		  elseif($m[$i]==11) { echo "Nov"; }
		  elseif($m[$i]==12) { echo "Dec"; }
		  echo "</option>";
	  }
	  ?>
    </select>

	<input type="submit" name="formSubmit" value="Submit" />
</form>
</div><br/><br/>

<div id="box_bottom">
</div>

</body>
</html>
