
<!-- <script type="text/javascript">
(function(){
	ImageFlow.initialize();
})(jQuery)
</script> -->


<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$project_id = $pieces[0];
$object_type = $pieces[1];
$year = $pieces[2];
$month = $pieces[3];
$checked = $pieces[4];

// Connecting to database
$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);
$userID=2;

// Set project name to project ID
$sql="SELECT DISTINCT * FROM projects WHERE (title='".$project_id."')";
$result = mysql_query($sql) or die(" ". mysql_error());

while($row = mysql_fetch_array($result))
{
		$project_id = $row['projectID'];
}

// Declare projects for user
$getProjects="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult = mysql_query($getProjects) or die(" ". mysql_error());
$project_sql = '';

while($row = mysql_fetch_array($projectsResult))
{
	$project_sql .= "projectID = ".$row['projectID']." OR ";
}

$project_sql = substr($project_sql,0,-4);

// Declare projects for user for queries
$getProjects_queries="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_queries = mysql_query($getProjects_queries) or die(" ". mysql_error());
$project_sql_queries = '';

while($row = mysql_fetch_array($projectsResult_queries))
{
	$project_sql_queries .= "queries.projectID = ".$row['projectID']." OR ";
}

$project_sql_queries = substr($project_sql_queries,0,-4);

// Declare projects for user for snippets
$getProjects_snippets="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_snippets = mysql_query($getProjects_snippets) or die(" ". mysql_error());
$project_sql_snippets = '';

while($row = mysql_fetch_array($projectsResult_snippets))
{
	$project_sql_snippets .= "snippets.projectID = ".$row['projectID']." OR ";
}

$project_sql_snippets = substr($project_sql_snippets,0,-4);

// Declare projects for user for annotations
$getProjects_annotations="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_annotations = mysql_query($getProjects_annotations) or die(" ". mysql_error());
$project_sql_annotations = '';

while($row = mysql_fetch_array($projectsResult_annotations))
{
	$project_sql_annotations .= "annotations.projectID = ".$row['projectID']." OR ";
}

$project_sql_annotations = substr($project_sql_annotations,0,-4);


?>

<!-- <script type="text/javascript">
(function(){
   ImageFlow.initialize();
})(jQuery)
</script> -->

<img src="img/img1.png" longdesc="img/img1.png" width="400" height="300" alt="Image 1" />
<img src="img/img2.png" longdesc="img/img2.png" width="300" height="400" alt="Image 2" />



<?	
mysql_close($con); 
?>