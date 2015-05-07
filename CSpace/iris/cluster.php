<?php
$cpages = $_POST["cpages"];
$n = $_POST["n"];

// DB Connection
require_once("connect.php");

/* create a dom document with encoding utf8 */
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

/* create the root element of the xml tree */
$parameters = $xml->createElement("parameters");
/* append it to the document created */
$parameters = $xml->appendChild($parameters);

$reqType = $xml->createElement("requestType");
$reqType = $parameters->appendChild($reqType);
$cluster = $xml->createTextNode('cluster');
$cluster = $reqType->appendChild($cluster);

$clientID = $xml->createElement("clientID");
$clientID = $parameters->appendChild($clientID);
$clientIDtext = $xml->createTextNode('1');
$clientIDtext = $clientID->appendChild($clientIDtext);

$numClusters = $xml->createElement("numClusters");
$numClusters = $parameters->appendChild($numClusters);
$three = $xml->createTextNode($n);
$three = $numClusters->appendChild($three);

$resourceList = $xml->createElement("resourceList");
$resourceList = $parameters->appendChild($resourceList);

foreach ($cpages as $checked) {
	$resource = $xml->createElement("resource");
	$resource = $resourceList->appendChild($resource);
	$id = $xml->createElement("id");
	$id = $resource->appendChild($id);
	$cvalue = $xml->createTextNode($checked);
	$cvalue = $id->appendChild($cvalue);

	$urlQuery = "SELECT * FROM pages WHERE pageID=".$checked."";
	$result = mysql_query($urlQuery) or die(" ". mysql_error());

	while($row = mysqli_fetch_array($result)) {
		$url = $row['url'];
 	}

	$urlNode = $xml->createElement("url");
	$urlNode = $resource->appendChild($urlNode);
	$urlVal = $xml->createTextNode($url);
	$urlVal = $urlNode->appendChild($urlVal);
}

    echo $xml->saveXML();
?>
