<?php
# Find the pointer to self
$self = $_SERVER['PHP_SELF'];
$results1 = $connection->commit($query1);
$numRecords = mysqli_num_rows($results1);
$maxPage = floor($numRecords/$maxPerPage);
if ($numRecords%$maxPerPage!=0)
	$maxPage++;

# The hyperlinks created here contains information about what to display
# This includes information about page number as well as the field to sort
# Print 'previous' link only if we're not on page one
if ($pageNum > 1)
{
    $page = $pageNum - 1;
    $prev = " <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('services/data.php?session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$page&orderby=$orderBy&searchString=$searchString', 'content');\">[<]</span> ";
    $first = " <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('services/data.php?session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=1&orderby=$orderBy&searchString=$searchString', 'content');\">[<<]</span> ";
}
else
{
  $prev  = ' [<] ';       # If we're on first page, don't hyperlink [Prev]
  $first = ' [<<] '; # Also, don't hyperlink [First Page]
}

# Print 'next' link only if we're not on the last page
if ($pageNum < $maxPage)
{
  $page = $pageNum + 1;
  $next = " <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('services/data.php?session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$page&orderby=$orderBy&searchString=$searchString', 'content');\">[>]</span> ";
  $last = " <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('services/data.php?session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$maxPage&orderby=$orderBy&searchString=$searchString', 'content');\">[>>]</a> ";
}
else
{
  $next = ' [>] ';      # If we're on last page, don't hyperlink [Next]
  $last = ' [>>] '; # Also, don't hyperlink [Last Page]
}

# Print the page navigation link
echo $first . $prev . " Page <strong>$pageNum</strong> of <strong>$maxPage</strong>" . $next . $last;
?>
