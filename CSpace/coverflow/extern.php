<script type="text/javascript">
domReady(function()
{
    var instanceOne = new ImageFlow();
    // override default options
    instanceOne.init({ ImageFlowID:'myImageFlow', reflections: false, reflectionP: 0.0, opacity: true, imagesM: 1.5, scrollbarP: 0.5, imageCursor: 'pointer', buttons: true, onClick: $.noop });
});
</script>

<?php
session_start();

// Connecting to database
require_once('../connect.php');


if (!isset($_SESSION['CSpace_userID'])) {
    echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
}
else {
    $userID = $_SESSION['CSpace_userID'];

    $q=$_GET["q"];
    if ($q != "")
    {
        $pieces = explode("-", $q);
        $projects = $pieces[0];
        $object_type = $pieces[1];
        $year = $pieces[2];
        $month = $pieces[3];
        $checked = $pieces[4];
    }

    $projectID = "";
    $userID = $_SESSION['CSpace_userID'];

    // Set project name to project ID
    $sql="SELECT DISTINCT * FROM projects WHERE (title='".$projects."')";
    $result = mysql_query($sql) or die(" ". mysql_error());
    $line = mysql_fetch_array($result);
    $projectID = $line['projectID'];

    // Project filter

    if ($projects == "all")
        $projectFilter = "projectID in (SELECT projectID from memberships where userID = $userID and access = 1)";
    else
        $projectFilter = "projectID = ".$projectID."";

    // "My stuff only" filter

    if($checked == 'Yes')
        $userFilter = "and userID = ".$_SESSION['CSpace_userID'];

//     switch ($object_type)
//     {

//         case "all" :
//         {

//             // all-all-all-all & proj-all-all-all
//             if($year == 'all' && $month == 'all') {
//                 $sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') AND value NOT LIKE '%http%' ORDER BY date DESC";
//                 $result = mysql_query($sql) or die(" ". mysql_error());
//                 $hasResult = FALSE; // Check if there are any results

//                 // $compareDate = '';
//                 // $compareYear = '';
//                 // $compareMonth = '';
//                 // $compareDay = '';
//                 // $setDate = false;

//                 // $entered_first = false;
//                 // $contain = false;

//                 while($row = mysql_fetch_array($result))
//                 {
//                     $type = $row['action'];
//                     $val = $row['value'];


//                     // if ($hasThumb!=NULL) {

//                             $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
//                             $pageResult = $connection->commit($getPage);
//                             $line = mysql_fetch_array($pageResult);

//                             $value = $line['pageID'];
//                             $thumb = $line['fileName'];
//                             $pass_var = "page-".$value;
//                             $hasThumb = $line['thumbnailID'];

//                             //This is all the XHTML ImageFlow needs
//                             echo "<div id='myImageFlow' class='imageflow'>";

//                             if ($hasThumb!=NULL) {

//                             while($line = mysql_fetch_array($pageResult)) {
//                             $thumb = $line['fileName'];
//                             $title = $line['title'];
//                             $link = $line['url'];
//                             $value = $line['pageID'];
//                             $comp_date = $line['date'];

//                             $pass_var = "page-".$value;

//                             echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' width='320' height='240' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";

//                             }
//                         }

//                         echo "</div>";

//                 }
//             }
//         }
//     }
// }

    $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ORDER BY date DESC";
    $pageResult = $connection->commit($getPage);

    //This is all the XHTML ImageFlow needs
    echo "<div id='myImageFlow' class='imageflow'>";

    while($line = mysql_fetch_array($pageResult)) {
        $thumb = $line['fileName'];
        $title = $line['title'];
    	$link = $line['url'];
        $value = $line['pageID'];
        $comp_date = $line['date'];

        $pass_var = "page-".$value;      

        echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' width='320' height='240' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";

    }

    echo "</div>";
}

mysql_close($con);
?>
