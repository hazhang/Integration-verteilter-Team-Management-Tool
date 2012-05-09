<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$agent = new Agent();
$debug = constant('DEBUG');
$startupHosts = getHosts();

if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'search':
			error_reporting(0);
			$filter = $_POST['request'];
			if($filter == null)
				$param = '<?xml version="1.0" encoding="utf-8" ?><request><filter/></request>';
			else
				$param = '<?xml version="1.0" encoding="utf-8" ?><request><filter>'.$filter.'</filter></request>';
			$request = 'request='.urlencode(encode($param));
			
			// get all online hosts from session
			$hosts = array();
			foreach($startupHosts as $key => $value){
				$hosts[$key] = 'http://'.$value."/list.php";
			}
			
			// send the request to hosts
			$responses = multi_request($hosts, $request);
			
			$results = array();
			$agent = new Agent();
			foreach($responses as $key => $value){
				$decoded_value = decode($value);
				$xmlElem = simplexml_load_string($decoded_value);
				if($xmlElem != null){					
					$objects = $agent->getObjects(null, $xmlElem);
					if($objects != null){
						$objStr = array();
						foreach($objects as $obj){
							$objStr[] = $obj->toShortArray();
						}
						$results[$key] = $objStr;
					}
				}
			}
			if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: agent_process.php position: case search'."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var param: '.$param."\r\n"
															.'var hosts: '.serialize($hosts)."\r\n"
															.'var request: '.$request."\r\n"
															.'var decoded_response: '.$decoded_response."\r\n");
			echo json_encode2(object_to_array($results));
			error_reporting(5);
			break;
		case 'show':		
			if(isset($_POST['id'])){
				$parsedID = $agent->parseID($_POST['id']);
				
				if($parsedID != null){
					$host = $startupHosts[$parsedID['agentID']];
					$param = '<?xml version="1.0" encoding="utf-8" ?><request><id>'.$_POST['id'].'</id></request>';				
					$request = 'request='.urlencode(encode($param));
					$url = 'http://'.$host.'/read.php';
					$response = request($url, $request);
					$decoded_response = decode($response);					
					$xmlElem = simplexml_load_string($decoded_response);				
					if($xmlElem != null){
						$objects = $agent->getObjects(null, $xmlElem);
						if($objects != null){
							echo json_encode2(object_to_array($objects[0]));
							if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: agent_process.php position: case read'."\r\n"
															.'var POST: '.$_POST['id']."\r\n"
															.'var param: '.$param."\r\n"
															.'var url: '.$url."\r\n"
															.'var request: '.$request."\r\n"
															.'var decoded_response: '.$decoded_response."\r\n"
															.'var object serialize: '.serialize($object[0])."\r\n");
							}
					}						
				}
			}		
			break;
		case 'update':
			if(isset($_POST['request'])){
				$xmlStr = $_POST['request'];		
				$param = '<?xml version="1.0" encoding="utf-8" ?><request>'.$xmlStr.'</request>';
				$request = 'request='.urlencode(encode($param));				
				$parsedID = $agent->parseID($_POST['id']);
				$host = $startupHosts[$parsedID['agentID']];
				$url = 'http://'.$host.'/update.php';								
				$response = request($url, $request);
				$decoded_response = decode($response);
				$responseStatus = getResponseStatus($decoded_response);
				$status_message;
				switch($responseStatus){
					case 'OK': $status_message = 'Update Sucessful!'; break;
					case 'NAK': $status_message = 'Update failed!'; break;
					case 'ERROR': $status_message = 'Response error!'; break;
				}
				$message = array('message' => $status_message, 'status' => $responseStatus);
				echo json_encode2($message);
				if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: agent_process.php position: case update'."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var xmlStr: '.$xmlStr."\r\n"
															.'var url: '.$url."\r\n"
															.'var param: '.$param."\r\n"
															.'var request: '.$request."\r\n");
			}
			break;
		case 'create':
			// create only in local server!!!
			if(isset($_POST['request'])){
				//$xmlStr = rawurldecode($_POST['request']);
				/* $xmlStr = $_POST['request'];						
				$objectID = uniqid(constant('AGENTID').'@');
				$sxe = new SimpleXMLElement($xmlStr);
				$nodes = $sxe->xpath('//'.$_POST['typ']);
				$objectTyp = ucfirst($_POST['typ']);				
				$object = new $objectTyp($nodes[0]);
				$object->id = $objectID;
				$agent->create($object);
				$message = array('message' => 'Create Sucessful!', 'data' => $object->toShortArray()); */
				
				$xmlStr = $_POST['request'];
				$objectID = uniqid(constant('AGENTID').'@');
				$sxe = new SimpleXMLElement($xmlStr);
				$nodes = $sxe->xpath('//'.$_POST['typ']);
				$objectTyp = ucfirst($_POST['typ']);				
				$object = new $objectTyp($nodes[0]);
				$object->id = $objectID;
								
				$param = '<?xml version="1.0" encoding="utf-8" ?><request>'.$object->toXML().'</request>';
				$request = 'request='.urlencode(encode($param));
				$url = 'http://'.constant('MEIN_IP').'/create.php';								
				$response = request($url, $request);
				$decoded_response = decode($response);
				$responseStatus = getObjectID($decoded_response);				
				$status_message;
				if($responseStatus != null){
					$status_message = 'Create Sucessful!';
					$responseStatus = 'OK';
				}else{
					$status_message = 'Create failed!';
					$responseStatus = 'NAK';
				}
				$message = array('message' => $status_message, 'status' => $responseStatus, 'data' => $object->toShortArray());
				
				echo json_encode2($message);
				if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: agent_process.php position: case create'."\r\n"
															.'var POST: '.$_POST['request']."\r\n"
															.'var xmlStr: '.$xmlStr."\r\n"
															.'var url: '.$url."\r\n"
															.'var objectID: '.$objectID."\r\n"
															.'var objectTyp: '.$objectTyp."\r\n"
															.'var decoded_response: '.$decoded_response."\r\n");
				
			}
			break;
		case 'delete':
			if(isset($_POST['id'])){			
				$param = '<?xml version="1.0" encoding="utf-8" ?><request><id>'
							.$_POST['id']
							.'</id></request>';		
								
				$parsedID = $agent->parseID($_POST['id']);
				$host = $startupHosts[$parsedID['agentID']];
				$request = 'request='.urlencode(encode($param));
				$url = 'http://'.$host.'/delete.php';
				$response = request($url, $request);
				$decoded_response = decode($response);
				$responseStatus = getResponseStatus($decoded_response);
				$status_message;
				switch($responseStatus){
					case 'OK': $status_message = 'Delete Sucessful!'; break;
					case 'NAK': $status_message = 'Delete failed!'; break;
					case 'ERROR': $status_message = 'Response error!'; break;
				}
				$message = array('message' => $status_message, 'status' => $responseStatus, 'data' => array('id' => $_POST['id']));
				echo json_encode2($message);
				if($debug) fwrite(fopen("tmp.txt", 'a'), 'file: agent_process.php position: case delete'."\r\n"
															.'var POST: '.$_POST['id']."\r\n"
															.'var param: '.$param."\r\n"
															.'var url: '.$url."\r\n");
			}
			break;
	}
}
?>