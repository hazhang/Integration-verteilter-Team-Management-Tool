<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');

if(isset($_POST['request'])){
	$decoded_request = decode($_POST['request']);
	$filter = getFilter($decoded_request);	
	$response = '<?xml version="1.0" encoding="utf-8" ?><response>';
	
	$agent = new Agent();
	$objects = $agent->getObjects($filter,null);
	if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: list.php'."\r\n"
													.'var POST: '.$_POST['request']."\r\n"
													.'var decoded_request: '.$decoded_request."\r\n"
													.'var filter: '.$filter."\r\n"
													.'var response: '.$response."\r\n");
	foreach($objects as $obj){
		$xmlStr = $obj->toXML();
		$response = $response . $xmlStr;
	}
	$response = $response . '</response>';
	header('Content-type: text/xml');
	echo encode($response);
	
}
?>