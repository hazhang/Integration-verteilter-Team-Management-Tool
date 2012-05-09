<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');
$startup = isStartup();
$hosts = getHosts();
if($startup && isset($_POST['request'])){
	$request = decode($_POST['request']);
	$remote_agent_ip = $_SERVER['REMOTE_ADDR'];
	$remote_agent_id = getAgentID($request);
	if($remote_agent_id != null){
		setHosts(array($remote_agent_id.'' => $remote_agent_ip));		
		$response = encode(constant('STARTUP_SHUTDOWN_RESPONSE'));
		header('Content-type: text/xml');
		echo($response);
	}	
}

?>
