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

$reqType = $xml->createElement("requestType");
$reqType = $parameters->appendChild($reqType);
$cluster = $xml->createTextNode('cluster');
$cluster = $reqType->appendChild($cluster);

$numClusters = $xml->createElement("numClusters");
$numClusters = $parameters->appendChild($numClusters);
$three = $xml->createTextNode($n);
$three = $numClusters->appendChild($three); 

$docList = $xml->createElement("docList");
$docList = $parameters->appendChild($docList);

foreach ($cpages as $checked) {
	$doc = $xml->createElement("doc");
	$doc = $docList->appendChild($doc);
	$docID = $xml->createElement("docID");
	$docID = $doc->appendChild($docID);
	$cvalue = $xml->createTextNode($checked);
	$cvalue = $docID->appendChild($cvalue);
}

    echo $xml->saveXML();
    // save xml file
    // $xml->save('clusteringInput.xml');
?> 
