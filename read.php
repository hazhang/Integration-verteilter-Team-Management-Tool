<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');

if(isset($_POST['request'])){
	$decoded_request = decode($_POST['request']);
	//fwrite(fopen("tmp.txt", 'w'), 'read.php'.$decoded_request);
	$objectID = getObjectID($decoded_request);
	$agent = new Agent();
	$parsedID = $agent->parseID($objectID);	
	if($parsedID['agentID'] == constant('AGENTID')){
		$response = '<?xml version="1.0" encoding="utf-8" ?><response>';
		$object = $agent->read($objectID);		
		$response .= $object->toXML() . '</response>';
		if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: read.php'."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var decoded_request: '.$decoded_request."\r\n"
															.'var objectID: '.$objectID."\r\n"
															.'var request: '.$request."\r\n"
															.'var agentID: '.$parsedID['agentID']."\r\n"
															.'var response: '.$response."\r\n"
															.'var object serialize: '.serialize($object)."\r\n");
		header('Content-type: text/xml');
		echo encode($response);
		
	}else{
		header('Content-type: text/xml');
		echo urlencode(encode(constant('RESPONSE_NAK')));
	}
}
?>