<?php
	session_start();
	$shared = $_GET['shared'];
	$_SESSION['CSpace_noteShared'] = $shared;
?>
	<div id="noteArea">
	<input type="hidden" id="noteID" value="-1" />
	<textarea id="note<?php echo $shared;?>" rows=3 cols=30></textarea><br/>
	<input type="button" value="Save Note" onClick="saveNote(<?php echo $shared;?>);" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:green;text-decoration:underline;cursor:pointer;font-size:11px;" onClick="newNote(<?php echo $shared;?>);"><img src="../img/add.jpg" width=16 style="vertical-align:middle;border:0" />New Note</span></font>
	</div>

	<div id="noteList" style="overflow:auto;height:78px;">
<?php
	require_once("noteList.php");
?>
	</div>
