<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');

if(isset($_POST['request'])){
	$decoded_request = decode($_POST['request']);	
	$xml = simplexml_load_string($decoded_request);	
	if($xml != null){		
		$agent = new Agent();
		$objects = $agent->getObjects(null,$xml);
		if($objects != null){
			$agent->create($objects[0]);				
		}
		$response = '<?xml version="1.0" encoding="utf-8" ?><request><id>'.$objects[0]->id.'</id></request>';
		if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: create.php '."\r\n"
												.'var POST: '.$_POST['request']."\r\n"
												.'var decoded_request: '.$decoded_request."\r\n"
												.'var response: '.$response."\r\n"
												.'var objects[0] serialize: '.serialize($objects[0])."\r\n");	
		header('Content-type: text/xml');
		echo encode($response);	
	}else{
		header('Content-type: text/xml');
		echo encode(constant('RESPONSE_NAK'));
	}
}
?>