<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>jQuery Ajax Test</title>
<script type="text/javascript" src="jquery_1.6.1.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
  // select all the links with class="lnk", when one of them is clicked, get its "href" value
  // load the content from that URL and place it into the tag with id="content"
  $('a.lnk').click(function() {
    var url = $(this).attr('href');
    $('#content').load(url);
    return false;
  });
});
--> </script> 

<script type="text/javascript">
 function foo(bar) {
	  if(bar == "all-all-all-all-") {
		 $('#content').load('extern.php', function() {
  alert('Load was performed.');
});
	  }
  }
</script>

<meta name="robots" content="index, follow, noarchive" />
		<link rel="stylesheet" href="style.css" type="text/css" />

		<!-- This includes the ImageFlow CSS and JavaScript -->
		<link rel="stylesheet" href="imageflow.packed.css" type="text/css" />
		<script type="text/javascript" src="imageflow.packed.js"></script>

		<style type="text/css">
			p.flip {
			z-index: 1000;
			position:absolute;
			right: 0;
			color: #fff;
			}
            div.panel,p.flip
            {
            margin:0px;
            text-align:center;
            }
            div.panel
            {
            height:110px;
            display:none;
            background: #000;
			border-bottom: 1px solid #333;
			margin: 0;
			width: 100%;
			position:absolute;
			z-index: 999;
			padding-top: 10px;
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
		$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		  });
		});
		</script>
</head>
<body>
	<?php 
    // Connecting to database
    $con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }
    
    mysql_select_db("shahonli_coagmento", $con);
    $userID=2;
    
    $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND pages.projectID='8'";
    $pageResult = mysql_query($getPage) or die(" ". mysql_error());
    ?>

    <p class="flip" style="display: block; padding: 10px;"><img src="menu.png"/></p>
    <div class="panel">
    	<h2>Coverflow View</h2>
    
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
	     ?>
       
		 <script type="text/javascript">
            $(document).ready(function() {
                if(<?php echo json_encode($str) ?> == 'all-all-all-all-') {
                    $('#content').load('extern.php');
                }
            });
        </script>

		<?
            }
        ?>
    	</div>
    </div>
    
    
<!-- <a href="extern.php" title="Get extern" class="lnk">Get extern</a> -->
<div id="content"></div>
</body>
</html>