<?php
include_once("models.php");
include_once("model_agent.php");

define("STARTUP_SHUTDOWN", '<?xml version="1.0" encoding="utf-8" ?><request><agent><id>zhasta16</id></agent></request>');
define("STARTUP_SHUTDOWN_RESPONSE", '<?xml version="1.0" encoding="utf-8" ?><response><agent><id>zhasta16</id></agent></response>');
define("RESPONSE_OK", '<?xml version="1.0" encoding="utf-8" ?><response><OK/></response>');
define("RESPONSES", '<?xml version="1.0" encoding="utf-8" ?><responses></responses>');

$xml = simplexml_load_string(constant('RESPONSES'));

$newContact = new Contact();
$newContact->id = "5";
$newContact->street = "mannheim";
$newContact->links = array(
							'1324',
							'456',
							'678'
							);

//$agent = new Agent("IGT_G16");
//echo $newContact->toXML();

$xml = $newContact->addToXML($xml);
$newContact->id = "56";
$xml = $newContact->addToXML($xml);


//echo $xml->asXML();

$sxe = new SimpleXMLElement($xml->asXML());
$nodes = $sxe->xpath("/first::*/id/parent::*");
//var_dump($nodes); 


$typ = 'Project';
var_dump(new $typ());

?>