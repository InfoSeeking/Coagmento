<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>jQuery Ajax Test</title>
<script type="text/javascript" src="jquery_1.6.1.js"></script>

<script type="text/javascript">
 function foo() {
		 $('#content').load('extern2.php', function() {
  alert('Load was performed.');
});
  }
</script>

<meta name="robots" content="index, follow, noarchive" />
		<link rel="stylesheet" href="style.css" type="text/css" />

        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

</head>
<body>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
<input type="submit" name="formSubmit" value="Submit" />
</form>
       
        <?php
            if(isset($_POST['formSubmit'])) 
            {
	   ?>
                   
       <script type="text/javascript">
	   $(document).ready(function() {
  		   $('#content').load('extern2.php');
	   });
	   </script>
       
		<?
            }
        ?>
    
<div id="content">Initial content in test.html</div>
</body>
</html>