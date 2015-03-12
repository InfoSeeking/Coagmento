<?php
require("core/Connection.class.php");
$cxn = Connection::getInstance();
?>
<div style="float:left;width:50%">
<h2>People who are assigned to groups</h2>
<table>
<tr><th>First Name</th><th>Last Name</th><th>Username</th><th>User ID</th><th>Project ID</th></tr>
<?php
$q = "select r.firstName, r.lastName, u.username, u.userID, u.projectID FROM recruits r, users u WHERE u.projectID!=0 AND r.userID=u.userID";
$res = $cxn->commit($q);
while($row = mysql_fetch_assoc($res)){
  printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $row["firstName"], $row["lastName"], $row["username"], $row["userID"], $row["projectID"]);
}
?>
</table>
</div>
<?php
if(mysql_num_rows($res) == 0){
  printf("There are no users assigned");
}
?>
<div style="float:left; margin-left: 1%; width:49%">
<h2>People who need to be assigned to groups</h2>
<table>
<tr><th>First Name</th><th>Last Name</th><th>Username</th><th>User ID</th><th>Project ID</th></tr>
<?php
$q = "select r.firstName, r.lastName, u.username, u.userID, u.projectID FROM recruits r, users u WHERE u.projectID=0 AND r.userID=u.userID";
$res = $cxn->commit($q);
while($row = mysql_fetch_assoc($res)){
  printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>", $row["firstName"], $row["lastName"], $row["username"], $row["userID"], $row["projectID"]);
}
?>
</table>
<?php
if(mysql_num_rows($res) == 0){
  printf("Everybody is assigned");
}
?>
</div>
