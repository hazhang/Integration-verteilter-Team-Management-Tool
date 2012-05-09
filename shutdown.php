<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$debug = constant('DEBUG');
$startup = isStartup();
if($startup && isset($_POST['request'])){
	$decoded_request = decode($_POST['request']);
	$remote_agent_ip = $_SERVER['REMOTE_ADDR'];
	$remote_agent_id = getAgentID($decoded_request);
	if($remote_agent_id != null){
		setHosts(array($remote_agent_id.'' => null));
		if(false) fwrite(fopen("tmp.txt", 'a'), 'file: shutdown.php '."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var decoded_request: '.$decoded_request."\r\n"
															.'var remote_agent_id: '.$remote_agent_id."\r\n"
															.'var remote_agent_ip: '.$remote_agent_ip."\r\n");	
		//$response = base64_encode(encode($startuppedHosts));
		$response = encode(constant('STARTUP_SHUTDOWN_RESPONSE'));
		header('Content-type: text/xml');
		echo($response);
	}	
}
?>