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
	session_start();
	include('../services/func.php');
  require_once('../../connect.php');
  $userID=2;
?>

<?php

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

<?php
include('../header.php');
?>

<div id="container">
    <div id="box_left"></div>

    <div id="box_right">Press Submit to get started. Click a thumbnail for details.</div>
</div>

</body>
</html>
