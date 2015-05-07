<?php
$cpages = $_POST["cpages"];
$first = $cpages[0];

array_splice($cpages, 0, 1);

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

$resource = $xml->createElement("resource");
$resource = $resourceList->appendChild($resource);
$id = $xml->createElement("id");
$id = $resource->appendChild($id);
$cvalue = $xml->createTextNode($first);
$cvalue = $id->appendChild($cvalue);

// DB Connection
require_once("connect.php");

$urlQuery = "SELECT * FROM pages WHERE pageID=".$first."";
$result = mysql_query($urlQuery) or die(" ". mysql_error());

while($row = mysqli_fetch_array($result)) {
	$url = $row['url'];
}

$urlNode = $xml->createElement("url");
$urlNode = $resource->appendChild($urlNode);
$urlVal = $xml->createTextNode($url);
$urlVal = $urlNode->appendChild($urlVal);

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
$numWordsnum = $xml->createTextNode('100');
$numWordsnum = $numWords->appendChild($numWordsnum);

$command3 = $xml->createElement("command");
$command3 = $parameters->appendChild($command3);
$parameters4 = $xml->createElement("parameters");
$parameters4 = $command3->appendChild($parameters4);

$reqType4 = $xml->createElement("requestType");
$reqType4 = $parameters4->appendChild($reqType4);
$merge = $xml->createTextNode('merge');
$merge = $reqType4->appendChild($merge);

$clientID3 = $xml->createElement("clientID");
$clientID3 = $parameters4->appendChild($clientID3);
$clientIDtext3 = $xml->createTextNode('4');
$clientIDtext3 = $clientID3->appendChild($clientIDtext3);

$resourceList2 = $xml->createElement("resourceList");
$resourceList2 = $parameters4->appendChild($resourceList2);

foreach ($cpages as $checked) {
	$resource2 = $xml->createElement("resource");
	$resource2 = $resourceList2->appendChild($resource2);
	$id2 = $xml->createElement("id");
	$id2 = $resource2->appendChild($id2);
	$cvalue2 = $xml->createTextNode($checked);
	$cvalue2 = $id2->appendChild($cvalue2);

	$urlQuery2 = "SELECT * FROM pages WHERE pageID=".$checked."";
	$result2 = mysql_query($urlQuery2) or die(" ". mysql_error());

	while($row = mysqli_fetch_array($result2)) {
		$url2 = $row['url'];
 	}

	$urlNode2 = $xml->createElement("url");
	$urlNode2 = $resource2->appendChild($urlNode2);
	$urlVal2 = $xml->createTextNode($url2);
	$urlVal2 = $urlNode2->appendChild($urlVal2);
}

$command4 = $xml->createElement("command");
$command4 = $parameters->appendChild($command4);
$parameters5 = $xml->createElement("parameters");
$parameters5 = $command4->appendChild($parameters5);

$reqType5 = $xml->createElement("requestType");
$reqType5 = $parameters5->appendChild($reqType5);
$rank = $xml->createTextNode('rank');
$rank = $reqType5->appendChild($rank);

$clientID4 = $xml->createElement("clientID");
$clientID4 = $parameters5->appendChild($clientID4);
$clientIDtext4 = $xml->createTextNode('4');
$clientIDtext4 = $clientID4->appendChild($clientIDtext4);

    /* get the xml printed */
    echo $xml->saveXML();
    mysqli_close($con);
?> 