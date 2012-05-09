<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'startup':
			setStartup(1);
			$params = constant("STARTUP_SHUTDOWN");
			
			// startup the server
			$data = startup($params);
			
			// save the online server in session 'hosts'
			setHosts($data);			
			
			// format the results to json response
			$response = array();			
			foreach($data as $key => $value){
				$response[] = array( 'id' => $key, 'ip' => $value);
			}			
			echo json_encode2($response);
			break;
		case 'shutdown':			
			$params = constant("STARTUP_SHUTDOWN");
			$data = shutdown($params);
			setHosts();
			setStartup(0);
			echo json_encode2('');
			break;
	}
}

?>