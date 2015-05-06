<?php

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$pieces = explode("/", $userAgent);
$os = $pieces[0];
$version = $pieces[1];
$model = $pieces[2];
$width = $pieces[3];
$height = $pieces[4];

if(($os == 'Android' && $width <= 500) || ($os == 'iOS' && $width <= 500)) {
	echo $width;

	header('Location: mobile.php');
	//OR
	echo "<script>window.location='coverflow/index.php'</script>";
}
else {
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

	if ($iphone || $android || $palmpre || $ipod || $berry == true)
	{
	header('Location: mobile.php');
	//OR
	echo "<script>window.location='coverflow/index.php'</script>";
	}
}

?>
