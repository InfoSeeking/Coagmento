<?php
	session_start();
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html>
    <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>Snippets</title>
	<style type="text/css">
		.cursorType{
		cursor:pointer;
		cursor:hand;
		}
	</style>
	
    </head>
    <body>
<?php
//		//$height = "700px"; //Added on October 21st, 2010
//		if ($_SESSION['condition']==6)
//			$height = "330px";
//			else
//				if ($_SESSION['condition']==5)
//					$height = "350px";
//				else
					$height = "250px";
?>
<div id="snippetsBox" style="height:<?php echo $height?>;overflow:auto;">
<?php 
	require_once("snippetsAux.php");
?>
</div>
</body>
</html>
