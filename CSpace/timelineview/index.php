<?php /*
include('user_agent.php'); // Redirecting http://mobile.site.info
// site.com data */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento CSpace Timeline View</title>

<style type="text/css">
	body {
	font-family: arial;
	background: url('../img/bg.png') no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	}
	#topbar {
	color: #000;
	background: #fff;
	/* background: url('top.png'); */
	width: 100%;
	min-width: 800px;
	height: 70px;
	position: fixed;
	top: 0; left: 0;
	padding-top: 20px;
	padding-left: 20px;
	z-index: 100;
	border-bottom: 1px solid #000;
	}
	#container {
	position: relative;
	padding-left: 20px;
	margin-top: 110px;
	}
	#box_left {
	width: 55%;
	float: left;
	/* margin-top: -25px; */
	}
	#box_right {
	width: 35%;
	height: 84%;
	float: left;
	position: fixed;
	top: 110px; left: 60%;
	overflow: auto;
	}
	#box_right h2 a {
	color: #000;
	font-size: 20px;
	}
	#box_right h2 a:hover {
	color: #ccc;
	}
	#box_right table {
	width: 100%;
	}
	#box_right table td {
	background-color: rgba(204,204,204,0.5);
	padding: 5px;
	font-size: 14px;
	color: #111 !important;
	}
	#box_right table td.thumb {
	background: transparent !important;
	}
	h2 {
	font-family: arial;
	display: inline;
	margin: 0;
	float: left;
	/* text-shadow: 0px -1px 0px rgba(0,0,0,.5); */
	}
	h2 a {
	color: #000;
	text-decoration: none;
	}
	h2 a:hover {
	color: #ccc;
	}
	a img {
	display: inline-block;
	width: 100px;
	height: 100px;
	border: 0;
	}
	.thumbnail_small {
	margin: 10px 10px 10px 10px;
	display: inline-block;
	width: 100px;
	height: 100px;
	border: solid 1px #ccc;
	}
	.thumbnail_small2 {
	margin: 10px 10px 10px 10px;
	/*border: 2px solid #95ba23;*/
	display: inline-block;
	width: 100px;
	height: 100px;
	border: solid 1px #95ba23;
	}
	#box_left a:hover {
	border: solid 1px #545454 !important;
	}
	/*#box_left a:hover {
	outline: 1px solid #545454 !important;
	}*/
	.thumbnail_info {
	font-family: arial;
	}
	.thumbnail_info a {
	color: #06F;
	font-family: arial;
	text-decoration: none;
	}
	.form {
	float: left;
	padding-left: 20px;
	padding-top: 3px;
	}
	.details {
	float: left;
	padding-top: 6px;
	padding-left: 3px;
	font-size: 12px;
	}
	.contain {
	border-left: 1px solid #ccc;
	padding-left: 20px;
	}
	.year h2 {
	font-size: 24px;
	font-family: arial;
	margin: 0;
	width: 100%;
	/* text-shadow: 0px -1px 0px rgba(0,0,0,.5); */
	}
	.month h3 {
	font-family: arial;
	margin: 0;
	/* text-shadow: 0px -1px 0px rgba(0,0,0,.5); */
	}
	.day {
	color: #333;
	font-size: 14px;
	}
	div.panel,p.flip
	{
	margin:0px;
	padding:5px;
	text-align:center;
	}
	div.panel
	{
	height:160px;
	display:none;
	background: #fff;
	padding: 20px;
	border:solid 1px #c3c3c3;
	}
	div.panel a {
	display: block;
	margin: 0;
	padding: 0;
	font-size: 12px;
	color: #333;
	border-left: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	text-decoration: none;
	}
	div.panel a:hover {
	color: #ccc;
	}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
function filterData(str)
{
if (str=="")
  {
  document.getElementById("box_left").innerHTML="";
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
    document.getElementById("box_left").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_left").innerHTML = '<img src="../assets/img/loading.gif"/>'; }
  }
xmlhttp.open("GET","filterData.php?q="+str,true);
xmlhttp.send();
}

function showDetails(str)
{
if (str=="")
  {
  document.getElementById("box_right").innerHTML="";
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
	else { document.getElementById("box_right").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;"><img src="../assets/img/loading.gif"/></div>'; }
  }
xmlhttp.open("GET","getDetails.php?q="+str,true);
xmlhttp.send();
}
</script>

<?php
  include('../func.php');
  require_once('../../connect.php');
  $userID=2;
?>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		require_once("../../connect.php");
	}
?>

<script type="text/javascript">
$(document).ready(function () {
	$('.thumbnail_small').live('click', function(){
		$(this).css('border-color','#717171');
		$('#box_left .thumbnail_small').not(this).css('border-color','#ccc');
		$('#box_left .thumbnail_small2').not(this).css('border-color','#95ba23');
	});
	$('.thumbnail_small2').live('click', function(){
		$(this).css('border-color','#717171');
		$('#box_left .thumbnail_small').not(this).css('border-color','#ccc');
		$('#box_left .thumbnail_small2').not(this).css('border-color','#95ba23');
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
});
</script>

</head>

<body>

<div id="topbar">
	<div class="left" style="float: left; min-width: 790px; width: 60%;">
        <h2><a href="index.php">Timeline View</a></h2>

        <div class="form">
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

        <select name="projects">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_POST['formSubmit']))
            {?>

            <? if($_POST['projects'] == 'all') { echo '<option value="all" selected="selected">All Projects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_POST['projects']; ?>" selected="selected"><?php echo $_POST['projects']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? } ?>
            <?php } ?>

            <?php
                echo '<option value="all">All Projects</option>';
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

            <!-- Sticky dropdown -->
            <?php
            if(isset($_POST['formSubmit']))
            {?>
                <? switch ($_POST['objects']) {
                    case 'pages':
                        echo '<option value="pages" selected="selected">Webpages</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'saved':
                        echo '<option value="saved" selected="selected">Bookmarks</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'queries':
                        echo '<option value="queries" selected="selected">Searches</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'snippets':
                        echo '<option value="snippets" selected="selected">Snippets</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'annotations':
                        echo '<option value="annotations" selected="selected">Annotations</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Objects</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                } ?>
            <? } ?>

            <option value="all">All Objects</option>
            <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
            <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
            <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
            <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
            <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
        </select>

        <select id="years" name="years">

            <!-- Sticky dropdown -->
            <?php
            if(isset($_POST['formSubmit']))
            {?>

            <? if($_POST['years'] == 'all') { echo '<option value="all" selected="selected">All Years</option>'; echo '<option value="" disabled="disabled"> ---------- </option>'; }
            else {?>
                <option value="<?php echo $_POST['years']; ?>" selected="selected"><?php echo $_POST['years']; ?></option>
                <option value="" disabled="disabled"> ---------- </option>
            <? } ?>
            <?php } ?>

            <option value="all">All Years</option>
            <?
            $sql_year="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
            $result_year=mysql_query($sql_year);

            $options="";
            $y=array();

            while ($row=mysql_fetch_array($result_year)) {
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

            <!-- Sticky dropdown -->
            <?php
            if(isset($_POST['formSubmit']))
            {?>

            <? switch ($_POST['months']) {
                    case '01':
                        echo '<option value="01" selected="selected">Jan</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '02':
                        echo '<option value="02" selected="selected">Feb</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '03':
                        echo '<option value="03" selected="selected">Mar</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '04':
                        echo '<option value="04" selected="selected">Apr</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '05':
                        echo '<option value="05" selected="selected">May</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '06':
                        echo '<option value="06" selected="selected">Jun</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '07':
                        echo '<option value="07" selected="selected">Jul</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '08':
                        echo '<option value="08" selected="selected">Aug</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '09':
                        echo '<option value="09" selected="selected">Sept</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '10':
                        echo '<option value="10" selected="selected">Oct</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '11':
                        echo '<option value="11" selected="selected">Nov</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case '12':
                        echo '<option value="12" selected="selected">Dec</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                    case 'all':
                        echo '<option value="all" selected="selected">All Months</option>'; echo '<option value="" disabled="disabled"> ---------- </option>';
                        break;
                } ?>
            <? } ?>

            <option value="all">All Months</option>
            <?
            $sql_month="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet')";
            $result_month=mysql_query($sql_month);

            $m=array();

            while ($row2=mysql_fetch_array($result_month)) {
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

        <input type="checkbox" name="userOnly" value="Yes" <?php if (isset($_POST['userOnly']) == 'Yes') { echo 'checked="checked"'; }?> /> <span style="font-size: 12px;">My stuff only</span>

        <input type="submit" name="formSubmit" value="Submit" />
        </form>
        </div>

        <div style="clear:both;"></div>

        <?php
            if(isset($_POST['formSubmit']))
            {
                $varProjects = $_POST['projects'];
                $varObjects = $_POST['objects'];
                $varYears = $_POST['years'];
                $varMonths = $_POST['months'];
				$userOnly = $_POST['userOnly'];

                $str = $varProjects.'-'.$varObjects.'-'.$varYears.'-'.$varMonths.'-'.$userOnly;

                echo '<div class="details">';
                echo 'Viewing ';
                // Objects
                switch ($varObjects) {
                    case "all":
                        echo "<b>All Objects</b>";
                        break;
                    case "pages":
                        echo "<b>Webpages</b>";
                        break;
                    case "saved":
                        echo "<b>Bookmarks</b>";
                        break;
                    case "queries":
                        echo "<b>Searches</b>";
                        break;
                    case "snippets":
                        echo "<b>Snippets</b>";
                        break;
                    case "annotations":
                        echo "<b>Annotations</b>";
                        break;
                }

                echo ' from ';

                //  Projects
                if($varProjects == "all") {
                    echo "<b>All Projects</b>";
                }
                else {
                    echo "<b>".$varProjects."</b>";
                }

                echo ' from ';

                // Months
                switch ($varMonths) {
                    case "all":
                        echo "<b>All Months</b>";
                        break;
                    case 01:
                        echo "<b>Jan</b>";
                        break;
                    case 02:
                        echo "<b>Feb</b>";
                        break;
                    case 03:
                        echo "<b>Mar</b>";
                        break;
                    case 04:
                        echo "<b>Apr</b>";
                        break;
                    case 05:
                        echo "<b>May</b>";
                        break;
                    case 06:
                        echo "<b>Jun</b>";
                        break;
                    case 07:
                        echo "<b>Jul</b>";
                        break;
                    case 08:
                        echo "<b>Aug</b>";
                        break;
                    case 09:
                        echo "<b>Sept</b>";
                        break;
                    case 10:
                        echo "<b>Oct</b>";
                        break;
                    case 11:
                        echo "<b>Nov</b>";
                        break;
                    case 12:
                        echo "<b>Dec</b>";
                        break;
                }

                echo ' ';

                // Years
                if($varYears == "all") {
                    echo "<b>All Years</b>";
                }
                else {
                    echo "<b>".$varYears."</b>";
                }
                echo '</div>';

                echo '<script type="text/javascript">';
                echo 'filterData("'.$str.'")';
                echo '</script>';
            }
        ?>
    </div>

    <div class="right" style="position: fixed; top: 25px; right: 20px;">
    	<p class="flip" style="float: right;"><img src="../assets/img/menu_dark.png" /></p>
        <div class="panel">
        	<table>
            	<tr>
                	<td valign="top" width="150">
                    	<b>Collaborators</b><br/>
                        <a href="../addCollaborator.php">Add</a>
                        <a href="../currentCollaborators.php">View</a><br/>

                        <b>Projects</b>
                        <a href="../createProject.php">Create</a>
                        <a href="../projects.php">Select</a>
                        <a href="../showPublicProjs.php">Join</a>
                    </td>
                	<td valign="top" width="150">
                    	<b>Sharing</b>
                        <a href="../showRecommendations.php">Recommendations</a>
                        <a href="../interProject.php">Inter-project</a><br/>

                   		<b>Workspace</b>
                        <a href="../etherpad.php">Editor</a>
                        <a href="../files.php">Files</a>
                        <a href="../printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                    	<b>Settings</b>
                        <a href="../profile.php">Profile</a>
                        <a href="../settings.php">Options</a>
                    </td>
                </tr>
                <tr height="10">
                	<td></td>
                </tr>
                <tr>
                	<td colspan=3 valign="top">
                    	<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">CSpace</a>&nbsp;&nbsp;
                		<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">Log out</a>
                    </td>
				</tr>
            </table>
        </div>
    </div>

</div>

<div id="container">
    <div id="box_left"></div>

    <div id="box_right">Press Submit to get started. Click a thumbnail for details.</div>
</div>

</body>
</html>
