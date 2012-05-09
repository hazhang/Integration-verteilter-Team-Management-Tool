<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');

if(isset($_POST['request'])){
	$decoded_request = decode($_POST['request']);
	$objectID = getObjectID($decoded_request);
	$agent = new Agent();
	$parsedID = $agent->parseID($objectID);
	if($parsedID != null && $parsedID['agentID'] == constant('AGENTID')){		
		$object = $agent->delete($objectID);
		header('Content-type: text/xml');
		echo encode(constant('RESPONSE_OK'));
	}else{
		header('Content-type: text/xml');
		echo encode(constant('RESPONSE_NAK'));
	}
}
?>