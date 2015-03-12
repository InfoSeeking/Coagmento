<style type="text/css">
#topbar {
	color: #fff;
	background: #000;
	width: 100%;
	height: 40px;
	position: fixed;
	top: 0; left: 0;
	padding-top: 20px;
	padding-left: 20px;
	z-index: 100;
}
#txtHint {
	position: relative;
	margin-top: 50px;
	padding-left: 20px;
}
.table_link {
	position: fixed;
	z-index: 200;
	top: 20; right: 20;
}
.table_link a {
	color: #690;
}
table {
	border: 0;
	width: 97%;
}
td {
	height: 100px;
}
td.monthyear {
	background: #aeaeae;
	width: 20%
}
td.popular {
	background: #6c6c6c;
}
td.noresults {
	background: #000;
	color: #fff;
	width: 20%;
}
</style>

<?php
	require_once("../connect.php");
	$userID = 2;
?>



<script type="text/javascript">
function showProject(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="Currently in timeline view. Choose a project to proceed.";
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
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("txtHint").innerHTML = 'Loading..'; }
  }
xmlhttp.open("GET","getProject.php?q="+str,true);
xmlhttp.send();
}
</script>


<div id="topbar">
<form>

<b>TIMELINE</b> &nbsp;&nbsp;

<select name="projects" onChange="showProject(this.value)">
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
		echo "<option value=\"$projID\" ";
		if ($projID==$projectID)
			echo "SELECTED";
			echo ">$title</option>\n";
	}
?>
<!-- location = this.options[this.selectedIndex].value; -->
</select>

<select name="month" ONCHANGE="">
	<option value="">Month</option>
    <option value="#04">April</option>
    <option value="#05">May</option>
    <option value="#11">November</option>
</select>

<select year="year" ONCHANGE="">
	<option value="">Year</option>
	<option value="#2009">2009</option>
    <option value="#2011">2011</option>
</select>


<? /* require_once("../connect.php"); 
$userID = 2;

$sql="SELECT DISTINCT date FROM pages WHERE userID=2"; 
$result=mysql_query($sql); 
$array=array();

while ($row=mysql_fetch_array($result)) { 
	$date = $row['date'];
	if (!in_array($date, $array)){ $array[] = $date; }
}

echo '<select name="date" ONCHANGE="location = this.options[this.selectedIndex].value;">';
echo '<option value="">Session</option>';
for ($i = 0; $i < count($array); ++$i) {
	echo '<option value="#'; echo $array[$i]; echo '">'; echo $array[$i]; echo '</option>';
}
echo '</select>'; */ ?>
            
</form>
<!-- <div class="table_link"><a href="tableview.php">Table View</a></div> -->
</div>

<!-- join pages and projects table (by projectid) to get project names and page details -->
<br />
<div id="txtHint">Currently in timeline view. Choose a project to proceed.</div>

