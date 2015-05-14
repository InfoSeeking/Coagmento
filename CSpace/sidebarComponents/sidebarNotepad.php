<?php
	session_start();
	require_once("core/Base.class.php");
?>
<span class="yui-skin-sam">
<?php
	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];

		$base = Base::getInstance();
		$projectID = $base->getProjectID();

		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];

		$projectID = $_SESSION['CSpace_projectID'];
		if(isset($_GET['shared']))
			$shared = $_GET['shared'];
		else
			$shared = 0;
?>
<div id="notepadContainer" style="overflow:hidden;"></div>
	<script type="text/javascript">
	// Create a YUI TabView control
	var tabView = new YAHOO.widget.TabView();

	(function() {
	    tabView.addTab(new YAHOO.widget.Tab({
	        label: 'Personal',
	        dataSrc: 'notes.php?shared=0',
	        content: 'Loading personal notes...',
	        cacheData: false
	    }));

	    tabView.addTab(new YAHOO.widget.Tab({
	        label: 'Shared',
	        dataSrc: 'notes.php?shared=1',
	        content: 'Loading shared notes...',
	        cacheData: false
	    }));

	    tabView.appendTo('notepadContainer');
	    tabView.selectTab(<?php echo $shared;?>);
	})();
	</script>
</span>
<?php
	} // if (isset($_SESSION['CSpace_userID']))
	else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>
