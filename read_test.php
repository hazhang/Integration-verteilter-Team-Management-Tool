<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$agent = new Agent();
$object = $agent->read('zhasta16@4d26e4a4ec832');

echo $object->toXML();
?>