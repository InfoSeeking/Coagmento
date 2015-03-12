<?php
	$results1 = mysql_query($query1) or die(" ". mysql_error());
	$numRecords = mysql_num_rows($results1);
	$maxPage = floor($numRecords/$maxPerPage);
	if ($numRecords%$maxPerPage>0)
		$maxPage++;
	
	# The hyperlinks created here contains information about what to display
	# This includes information about page number as well as the field to sort
	# Print 'previous' link only if we're not on page one
	if ($pageNum > 1) {
	    $page = $pageNum - 1;
	    $prev = " <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('$pageToGo?page=$page', '$container');\">[<]</span> ";
	    $first = "&nbsp;<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('$pageToGo?page=$page', '$container');\">[<<]</span>&nbsp;";
	} 
	else {
	  $prev  = '<span style="font-size:10px;"> [<] </span>';     # If we're on first page, don't hyperlink [Prev]
	  $first = '<span style="font-size:10px;"> [<<] </span>'; 	# Also, don't hyperlink [First Page]
	}
	
	# Print 'next' link only if we're not on the last page
	if ($pageNum < $maxPage) {
	  $page = $pageNum + 1;
	  $next = "&nbsp;<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('$pageToGo?page=$page', '$container');\">[>]</span>&nbsp;";
	  $last = "<span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('$pageToGo?page=$page', '$container');\">[>>]</span>";
	} 
	else {
	  $next = '<span style="font-size:10px;"> [>] </span>';      # If we're on last page, don't hyperlink [Next]
	  $last = '<span style="font-size:10px;"> [>>] </span>'; 	# Also, don't hyperlink [Last Page]
	}
	
	# Print the page navigation link
	echo $first . $prev . "<span style=\"font-size:10px;\"> Page $pageNum of $maxPage</span> " . $next . $last;
?>
