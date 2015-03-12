<style type="text/css">
	#topbar {
	color: #000;
	background: #fff;
	width: 100%;
	height: 40px;
	position: fixed;
	top: 0; left: 0;
	padding-top: 20px;
	padding-left: 20px;
	z-index: 100;
	border-bottom: 3px solid #000;
	}
	#container {
	position: relative;
	margin-top: 100px;
	padding-left: 20px;
	}
	.header {
	font-family: Arial;
	font-weight: bold;
	}
	#box_left {
	width: 60%;
	float: left;
	}
	#box_right {
	width: 35%;
	background: #ccc;
	height: 600px;
	float: left;
	position: fixed;
	top: 110px; left: 60%;
	}
	img.thumbnail_small {
	margin: 10px 10px 10px 10px;
	border: 3px solid #ccc;
	}	
	.thumbnail_info {
	font-family: arial;
	}
	.thumbnail_info a {
	color: #06F;
	font-family: arial;
	text-decoration: none;
	}
</style>

<script type="text/javascript">
function showDetails(str)
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
    document.getElementById("box_right").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_right").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;">Loading..</a>'; }
  }
xmlhttp.open("GET","getDetails.php?q="+str,true);
xmlhttp.send();
}
</script>

<?php 
  include('db.php');
  include('func.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chained Select Boxes using PHP, MySQL and jQuery</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('#wait_1').hide();
	$('#drop_1').change(function(){
	  $('#wait_1').show();
	  $('#result_1').hide();
      $.get("func.php", {
		func: "drop_1",
		drop_var: $('#drop_1').val()
      }, function(response){
        $('#result_1').fadeOut();
        setTimeout("finishAjax('result_1', '"+escape(response)+"')", 400);
      });
    	return false;
	});
});

function finishAjax(id, response) {
  $('#wait_1').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
}
function finishAjax_tier_three(id, response) {
  $('#wait_2').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
}
</script>
</head>

<body>
<div id="topbar">
<form action="" method="post">
  
  	<span class="header">Timeline</span>
    
    <select name="drop_1" id="drop_1">
    
      <option value="" selected="selected" disabled="disabled">Select a Category</option>
      
      <?php getTierOne(); ?>
    
    </select> 
    
    <span id="wait_1" style="display: none;">
    <img alt="Please Wait" src="ajax-loader.gif"/>
    </span>
    <span id="result_1" style="display: none;"></span>
    <span id="wait_2" style="display: none;">
    <img alt="Please Wait" src="ajax-loader.gif"/>
    </span>
    <span id="result_2" style="display: none;"></span> 
  
</form>
</div>

<div id="container">
<?php if(isset($_POST['submit'])){
	$drop = $_POST['drop_1'];
	$drop_2 = $_POST['drop_2'];
	$drop_3 = $_POST['drop_3'];
	$selected_date = "".$drop."-".$drop_2."-".$drop_3."";
	echo "<span class='header'>".$selected_date."</span>";
	echo '<br/><br/>';
	
	include_once('db.php');
	
	$query = "SELECT * from pages, thumbnails where pages.thumbnailID = thumbnails.thumbnailID and pages.userID=2 AND pages.date='".$selected_date."'";
	$results = mysql_query($query) or die(" ". mysql_error());
	
	echo '<div id="box_left">';
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$page = $line['pageID'];
		$thumb = $line['fileName'];
		
		echo '<a href="#" value="'.$page.'" onClick="showDetails('.$page.')">';
		echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/';
		echo $thumb;
		echo '" width="100" height="100" class="thumbnail_small" />';
		echo '</a>';
	}
	echo '</div>';
	
	echo '<div id="box_right"></div>';
	
	/* // Original Query
	$sql="SELECT * FROM pages WHERE userID=2 AND date = '".$selected_date."'";
	$result = mysql_query($sql);
	
	// Original Table
	echo "<table border='1'>
	<tr>
	<th>Webpage</th>
	<th>Source</th>
	<th>Query</th>
	<th>Time</th>
	</tr>";
	
	while($row = mysql_fetch_array($result))
	  {
	  echo "<tr>";
	  echo "<td>" . $row['title'] . "</td>";
	  echo "<td>" . $row['source'] . "</td>";
	  echo "<td>" . $row['query'] . "</td>";
	  echo "<td><a name=".$row[date].">" . $row['date'] . "</a></td>";
	  echo "</tr>";
	  }
	echo "</table>"; */
	
}
echo '</div>';

?>
</body>
</html>
