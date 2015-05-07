<?php
$proj=$_GET['projects']; $obj=$_GET['objects'];

$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);

$sql="SELECT * FROM '".$obj."' WHERE userID=2 AND projectID='".$proj."'";

$result = mysql_query($sql);

$sql_name ="SELECT title FROM projects WHERE projectID = '".$proj."'";
$projectname = mysql_query($sql_name);
while($row = mysqli_fetch_array($projectname)) { echo '<b>Project:</b> '; echo $row['title']; };

echo "<table border='1'>
<tr>
<th>Webpage</th>
<th>Source</th>
<th>Query</th>
<th>Time</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['title'] . "</td>";
  echo "<td>" . $row['source'] . "</td>";
  echo "<td>" . $row['query'] . "</td>";
  echo "<td>" . $row['date'] . "</td>";
  echo "</tr>";
  }
echo "</table>";


?>