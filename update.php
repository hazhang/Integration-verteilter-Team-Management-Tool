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
			$agent->update($objects[0]);
			if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: update.php '."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var decoded_request: '.$decoded_request."\r\n"
															.'var objects[0] serialize: '.serialize($objects[0])."\r\n");		
		}		
		header('Content-type: text/xml');
		echo encode(constant('RESPONSE_OK'));	
	}else{
		header('Content-type: text/xml');
		echo encode(constant('RESPONSE_NAK'));
	}
}
?>