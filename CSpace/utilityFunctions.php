<?php
function extractQuery($referrer)
{
	$ref = $referrer;
	$queryString = false;
	$se_stuff = array();

	$se_stuff[] = array("google.com", "q", "Google");
	$se_stuff[] = array("google.co.uk", "q", "Google");
	$se_stuff[] = array("ask.com", "q", "Ask.com");
	$se_stuff[] = array("ask.co.uk", "ask", "Ask.co.uk");
	$se_stuff[] = array("comcast.net", "?cat=Web&con=betaa&q", "Comcast");
	$se_stuff[] = array("yahoo", "p", "Yahoo");
	$se_stuff[] = array("yahoo.co.uk", "p", "Yahoo");
	$se_stuff[] = array("aol.com", "query", "AOL");
	$se_stuff[] = array("msn.com", "q", "MSN");
	$se_stuff[] = array("live.com", "q", "Live");
	$se_stuff[] = array("bing.com", "q", "Bing");
	$se_stuff[] = array("netscape.com", "query", "Netscape");
	$se_stuff[] = array("netzero.net", "query", "NetZero");
	$se_stuff[] = array("altavista.com", "q", "Altavista");
	$se_stuff[] = array("mywebsearch.com", "searchfor", "Mywebsearch");
	$se_stuff[] = array("alltheweb.com", "q", "Alltheweb");
	$se_stuff[] = array("cnn.com", "query", "CNN");
	$se_stuff[] = array("myspace.com", "q", "MySpace");
	$se_stuff[] = array("wikipedia.org", "search", "Wikipedia");
	$se_stuff[] = array("en.wikipedia.org", "search", "Wikipedia");
	$se_stuff[] = array("searchme.com", "q", "Searchme");

	for($i=0, $size = sizeof($se_stuff); $i < $size; $i++)
	{
		if (stristr($ref,$se_stuff[$i][0]) )
		{
			$symbol = $se_stuff[$i][1];
			$temp1 = explode("$symbol=", $ref, 2);
			$temp2 = explode("&", $temp1[1]);
			$string = $temp2[0];
			$queryString = urldecode($string);
		}
	}
	return $queryString;
} // end extractQuery

function addPoints($userID,$points){
	require_once("./core/Connection.class.php");
	$connection = Connection::getInstance();
	$connection->commit("UPDATE users SET points=points+$points WHERE userID='$userID'";
}

function assign_rand_value($num)
{
// accepts 1 - 36

  $num = intval($num);
  if($num>=27){
    return $num-27;
  }else{
    return chr($num+96)
  }

  // Assumes 1-36 is input
  //Returns a-z,then 0-9
}

function get_rand_id($length)
{
  if($length>0)
  {
  $rand_id="";
   for($i=1; $i<=$length; $i++)
   {
   mt_srand((double)microtime() * 1000000);
   $num = mt_rand(1,36);
   $rand_id .= assign_rand_value($num);
   }
  }
return $rand_id;
}

?>
