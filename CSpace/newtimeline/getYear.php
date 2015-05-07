<?
  session_start();
  include("../services/functions.inc.php");
  $year = $_GET['year'];
  session_register('myvar');
  $_SESSION['myvar'] == $year;

require_once("../connect.php");
$userID = 2;

	$sql="SELECT DISTINCT pages.date FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2";
	$result=mysql_query($sql);

	$buttons="";
	$m=array();
	$month_name="";

	while ($row=mysqli_fetch_array($result)) {
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

		if($yr == $year) {
			if (!in_array($month, $m)){
				$m[] = $month;
				$buttons.="<OPTION VALUE=".$month.">".$month_name; echo'</OPTION>';
			}
		}

		$i++;
	}

?>

<SELECT id='month' name='month' onChange="filterMonth()">
<option value=" " disabled="disabled" selected="selected">Choose month</option>
<?=$buttons?>
</SELECT>
</form>
