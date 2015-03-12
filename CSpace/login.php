<?php
	require_once("connect.php");
	
	$userName = $_POST['userName'];
	$password = sha1($_POST['password']);
	
	$fout = fopen("temp.txt", 'w');
	$query = "SELECT * FROM users WHERE userName='$userName' AND password='$password'";
	fwrite($fout, $query."\n");
	fclose($fout);
	$result = mysql_fetch_array(mysql_query($query));
	
	//start outputting the XML
	$output = "<loginsuccess>";

	//if the query returned true, the output <loginsuccess>yes</loginsuccess> else output <loginsuccess>no</loginsuccess>
	if(!$result)
	{
		$output .= "no";
	}
	else
	{
		$output .= "yes";	
	}
	$output .= "</loginsuccess>";

	//output all the XML
	print ($output);	
?>
