<?php
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

if ($iphone || $android || $palmpre || $ipod || $berry == true)
{
header('Location: http://'.$_SERVER['HTTP_HOST'].'/CSpace/coverflowview/test2/index.php');
//OR
echo "<script>window.location='http://coagmento.org/CSpace/coverflowview/test2/index.php'</script>";
}
?>
