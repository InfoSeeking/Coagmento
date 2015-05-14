<?php

function getRatingRepresentation($finalRating,$itemID,$itemType,$layer,$region,$webPage)
{
	$result = "<div class=\"cursorType\" onclick=\"javascript:showRatingForm('$layer',null,'$itemID','$itemType','$region','$webPage')\">";

	if ($finalRating == null)
		$finalRating = 0;

	for ( $i = 0; $i < (int)$finalRating; $i++)
		$result = $result . "<img src=\"assets/img/fullstar.gif\" height=\"10\" width=\"10\" alt=\"Rate\">";
	$rest = 5-$finalRating;
	$decimal = $finalRating - (int)$finalRating;
	if (($decimal>=0.1) and ($decimal<=0.9))
		$result = $result . "<img src=\"assets/img/halfstar.gif\" height=\"10\" width=\"10\" alt=\"Rate\">";

	for ( $i = 0; $i < (int)$rest; $i++)
		$result = $result . "<img src=\"assets/img/emptystar.gif\" height=\"10\" width=\"10\" alt=\"Rate\" >";
	$result = $result . "</div>";

	return $result;
}

?>
