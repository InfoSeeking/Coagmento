<?php
//**************************************
//     Page load dropdown results     //
//**************************************
function getTierOne()
{
	
	$sql="SELECT DISTINCT pages.date FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2 ORDER BY pages.date DESC"; 
	$result=mysql_query($sql); 

	$options=""; 
	$y=array();

	while ($row=mysql_fetch_array($result)) { 
    	$date=$row["date"];  
		$year = date("Y",strtotime($date));
	
		if (!in_array($year, $y)){ 
			$y[] = $year;
			$options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';  
		}

	} 
	echo $options;

}

//**************************************
//     First selection results     //
//**************************************
if($_GET['func'] == "drop_1" && isset($_GET['func'])) { 
   drop_1($_GET['drop_var']); 
}

function drop_1($drop_var)
{  
	global $var;
	$var = $drop_var;
    include_once('db.php');
	$sql="SELECT DISTINCT pages.date FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2"; 
	$result=mysql_query($sql);
	
	$buttons=""; 
	$m=array();
	$month_name="";
	
	echo '<select name="drop_2" id="drop_2">
	      <option value=" " disabled="disabled" selected="selected">Choose one</option>';
	
	while ($row=mysql_fetch_array($result)) { 
		$date=$row["date"];  
		$yr = date("Y",strtotime($date));
		$month = date("m",strtotime($date));
		
		if($month==01) { $month_name="Jan"; }
		elseif($month==02) { $month_name="Feb"; }
		elseif($month==03) { $month_name="Mar"; }
		elseif($month==04) { $month_name="Apr"; }
		elseif($month==05) { $month_name="May"; }
		elseif($month==06) { $month_name="Jun"; }
		elseif($month==07) { $month_name="Jul"; }
		elseif($month==08) { $month_name="Aug"; }
		elseif($month==09) { $month_name="Sept"; }
		elseif($month==10) { $month_name="Oct"; }
		elseif($month==11) { $month_name="Nov"; }
		else{ $month_name="Dec"; }
		
		if($yr == $drop_var) {
			if (!in_array($month, $m)){ 
				$m[] = $month;
				$buttons.="<OPTION VALUE=".$month.">".$month_name; echo'</OPTION>';
			}
		}
		
		$i++;
	} 
		
	echo $buttons;
	
	echo '</select>';
	
	echo "<script type=\"text/javascript\">
$('#wait_2').hide();
	$('#drop_2').change(function(){
	  $('#wait_2').show();
	  $('#result_2').hide();
      $.get(\"func.php\", {
		func: \"drop_2\",
		drop_var: $('#drop_2').val()
      }, function(response){
        $('#result_2').fadeOut();
        setTimeout(\"finishAjax_tier_three('result_2', '\"+escape(response)+\"')\", 400);
      });
    	return false;
	});
</script>";
}


//**************************************
//     Second selection results     //
//**************************************
if($_GET['func'] == "drop_2" && isset($_GET['func'])) { 
   drop_2($_GET['drop_var']); 
}

function drop_2($drop_var)
{  
    include_once('db.php');
	$sql="SELECT DISTINCT pages.date FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2"; 
	$result=mysql_query($sql);
	
	$buttons=""; 
	$d=array();
	
	echo '<select name="drop_3" id="drop_3">
	      <option value=" " disabled="disabled" selected="selected">Choose one</option>';
	
	while ($row=mysql_fetch_array($result)) { 
		$date=$row["date"];  
		$yr = date("Y",strtotime($date));
		$month = date("m",strtotime($date));
		$day = date("d",strtotime($date));
		
		if($month == $drop_var) {
			if (!in_array($day, $d)){ 
				$d[] = $day;
			}
		}
	} 
	
	sort($d);
	
	for($i = 0; $i < count($d); ++$i) {
		$buttons.="<OPTION VALUE=".$d[$i].">".$d[$i]; echo'</OPTION>';
	}
	
	echo $buttons;
	
	echo '</select>';
	
	global $var;
	echo $var;
	
    echo '<input type="submit" name="submit" value="Submit" />';
}

?>