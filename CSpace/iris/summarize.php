<?php
$cpages = $_POST["cpages"];
$n = $_POST["n"];

/* create a dom document with encoding utf8 */
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

/* create the root element of the xml tree */
$parameters = $xml->createElement("parameters");
/* append it to the document created */
$parameters = $xml->appendChild($parameters);

$clientID = $xml->createElement("clientID");
$clientID = $parameters->appendChild($clientID);
$clientIDtext = $xml->createTextNode('1');
$clientIDtext = $clientID->appendChild($clientIDtext);

$reqType = $xml->createElement("requestType");
$reqType = $parameters->appendChild($reqType);
$pipe = $xml->createTextNode('pipe');
$pipe = $reqType->appendChild($pipe);

$command = $xml->createElement("command");
$command = $parameters->appendChild($command);
$parameters2 = $xml->createElement("parameters");
$parameters2 = $command->appendChild($parameters2);

$reqType2 = $xml->createElement("requestType");
$reqType2 = $parameters2->appendChild($reqType2);
$filter = $xml->createTextNode('filter');
$filter = $reqType2->appendChild($filter);

$clientID = $xml->createElement("clientID");
$clientID = $parameters2->appendChild($clientID);
$clientIDtext = $xml->createTextNode('2');
$clientIDtext = $clientID->appendChild($clientIDtext);

$wordList = $xml->createElement("wordList");
$wordList = $parameters2->appendChild($wordList);
$wordListWords = $xml->createTextNode('a about above according across actually adj after afterwards again against ago all almost alone along already also although always am among amongst amoungst amount an and another any anyhow anyone anything anyway anywhere are aren around as at b back be became because become becomes becoming been before beforehand begin beginning behind being below beside besides between beyond bill billion both bottom but by c call can cannot cant caption co con could couldnt cry d de describe detail did didn dlrs do does doesn don done down due during e each eg eight eighty either eleven else elsewhere empty end ending enough etc even ever every everyone everything everywhere except f few fifteen fifty fify fill find fire first five for former formerly forty found four from front full further g get give go h had has hasn hasnt have haven he hence her here hereafter hereby herein hereupon hers herself him himself his how however hundred i ie if in inc indeed instead interest into is isn it its itself j k l last later latter latterly least less let like likely ltd m made make makes many may maybe me meantime meanwhile might mill million mine miss mln more moreover most mostly move mr mrs much must my myself n name namely neither never nevertheless next nine ninety no nobody none nonetheless noone nor not nothing now nowhere o of off often on once one only onto or other others otherwise our ours ourselves out over overall own p pct per perhaps please put q r rather re recent recently reuters reuter s said same says see seem seemed seeming seems serious seven seventy several she should shouldn show since sincere six sixty so some somehow someone something sometime sometimes somewhere still stop such t take taking ten than that the their them themselves then thence there thereafter thereby therefore therein thereupon these they thick thin third thirty this those though thousand three through throughout thru thus to together too top toward towards trillion twelve twenty two u un under unless unlike unlikely until up upon us used using v very via w was wasn we well were weren what whatever when whence whenever where whereafter whereas whereby wherein whereupon wherever whether which while whither who whoever whole whom whomever whose why will with within without won would wouldn x y year yes yet you your yours yourself yourselves z nbsp lt gt quot raquo laquo');
$wordListWords = $wordList->appendChild($wordListWords);

$resourceList = $xml->createElement("resourceList");
$resourceList = $parameters2->appendChild($resourceList);

foreach ($cpages as $checked) {
	$resource = $xml->createElement("resource");
	$resource = $resourceList->appendChild($resource);
	$id = $xml->createElement("id");
	$id = $resource->appendChild($id);
	$cvalue = $xml->createTextNode($checked);
	$cvalue = $id->appendChild($cvalue);

	// DB Connection
	require_once('connect.php');

	$urlQuery = "SELECT * FROM pages WHERE pageID=".$checked."";
	$result = mysql_query($urlQuery) or die(" ". mysql_error());

	while($row = mysql_fetch_array($result)) {
		$url = $row['url'];
 	}

	$urlNode = $xml->createElement("url");
	$urlNode = $resource->appendChild($urlNode);
	$urlVal = $xml->createTextNode($url);
	$urlVal = $urlNode->appendChild($urlVal);
}

$command2 = $xml->createElement("command");
$command2 = $parameters->appendChild($command2);
$parameters3 = $xml->createElement("parameters");
$parameters3 = $command2->appendChild($parameters3);

$reqType3 = $xml->createElement("requestType");
$reqType3 = $parameters3->appendChild($reqType3);
$extract = $xml->createTextNode('extract');
$extract = $reqType3->appendChild($extract);

$clientID2 = $xml->createElement("clientID");
$clientID2 = $parameters3->appendChild($clientID2);
$clientIDtext2 = $xml->createTextNode('3');
$clientIDtext2 = $clientID2->appendChild($clientIDtext2);

$numWords = $xml->createElement("numWords");
$numWords = $parameters3->appendChild($numWords);
$numWordsnum = $xml->createTextNode($n);
$numWordsnum = $numWords->appendChild($numWordsnum);

    /* get the xml printed */
    echo $xml->saveXML();
    mysqli_close($con);
?> 