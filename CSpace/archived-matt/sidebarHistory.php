<?php
	session_start();
?>
<?php
	require_once("connect.php");

	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		if (isset($_GET['selectTab']))
			$selectTab = $_GET['selectTab'];
		else
			$selectTab = 0;
?>
<span class="yui-skin-sam">
<div id="historyContainer" style="overflow:hidden;"></div>

<script type="text/javascript">
	// Create a YUI TabView control
	var hTabs = new YAHOO.widget.TabView();

	(function() {
	    hTabs.addTab(new YAHOO.widget.Tab({
	        label: 'Searches',
	        dataSrc: 'sidebarQueries.php',
	        content: 'Loading searches...',
	        cacheData: false
	    }));

	    hTabs.addTab(new YAHOO.widget.Tab({
	        label: 'Bookmarks',
	        dataSrc: 'sidebarDocs.php',
	        content: 'Loading bookmarks...',
	        cacheData: false
	    }));

	    hTabs.addTab(new YAHOO.widget.Tab({
	        label: 'Objects',
	        dataSrc: 'sidebarSnippets.php',
	        content: 'Loading saved snippets...',
	        cacheData: false
	    }));

	    hTabs.appendTo('historyContainer');
		hTabs.selectTab(<?php echo $selectTab;?>);
	})();

	// Refresh the tabs. However, we need to reload only the active tab.
	function tabsReload(tabIndex, option) {
		var opt = '';
		switch(tabIndex) {
			case 0:
				opt = 'query';
				break;

			case 1:
				opt = 'docs';
				break;

			case 2:
				opt = 'snippets';
				break;
		}
		switch(option) {
			case 'title':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-order');
				req.add('value', 'title');
				var response = req.execute();
				break;

			case 'source':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-order');
				req.add('value', 'source');
				var response = req.execute();
				break;

			case 'date':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-order');
				req.add('value', 'timestamp');
				var response = req.execute();
				break;

			case 'author':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-order');
				req.add('value', 'userID');
				var response = req.execute();
				break;

			case 'showDate':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-show-date');
				req.add('value', 'yes');
				var response = req.execute();
				break;

			case 'showTime':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-show-time');
				req.add('value', 'yes');
				var response = req.execute();
				break;

			case 'latestTop':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-list-order');
				req.add('value', 'latest-top');
				var response = req.execute();
				break;

			case 'showNoDate':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-show-date');
				req.add('value', 'no');
				var response = req.execute();
				break;

			case 'showNoTime':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-show-time');
				req.add('value', 'no');
				var response = req.execute();
				break;

			case 'latestBottom':
				var url = "http://<?php echo $_SERVER['HTTP_HOST']; ?>/CSpace/setOptions.php";
				req = new phpRequest(url);
				req.add('option',opt+'-list-order');
				req.add('value', 'latest-bottom');
				var response = req.execute();
				break;
		}
		hTabs.selectTab(tabIndex);
	}
</script>
</span>
<?php
	} // if (isset($_SESSION['CSpace_userID']))
	else {
		echo "Your session has expired. Please <a href=\"http://www.coagmento.org/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>
