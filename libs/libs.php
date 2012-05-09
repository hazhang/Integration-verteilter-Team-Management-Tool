<?php
include_once("libs/literal.php");

/**
 * server startup and requests to all hosts in the same net.
 *
 * @param	$param	the data to send
 * @return	a array, that saves the started remote hosts' URL-adresses.
*/
function startup($param){
	$request = 'request='.encode($param);
	//var_dump($request);
	$hosts = array();	
	$responses = array();
	$i = 121;
	while($i <= 136){
		$hosts["141.19.143.".$i] = "http://141.19.143.".$i."/startup.php";
		$i++;
	}

	$results = array();
	//var_dump($hosts);
	$responses = multi_request($hosts, $request);
	foreach($responses as $key => $value){
		$decoded_value = decode($value);
		$agentID = getAgentID($decoded_value);
		if($agentID != null)
			//$results[$key] = $agentID;
			$results[$agentID] = $key;
	}
    return $results;	
}

function shutdown($param){
	$request = 'request='.encode($param);
	$hosts = array();
	$i = 121;
	$responses = array();
	
	while($i <= 136){
		$hosts["141.19.143.".$i] = "http://141.19.143.".$i."/shutdown.php";
		$i++;
	}

	$results = array();
	$responses = multi_request($hosts, $request);
	foreach($responses as $key => $value){
		$decoded_value = decode($value);
		$agentID = getAgentID($decoded_value);
		if($agentID != null)
			$results[$agentID] = $key;
	}
    return $results;
}

/**
 * request with POST method to a remote host.
 *
 * @param	$url	the URL adress of remote host
 * @param	$vars	the request data, that will send to host
 * @return	the response from remote host
*/
function request($url, $vars){
	$options = array(
		CURLOPT_URL => $url,					// the URL adress
        CURLOPT_RETURNTRANSFER => true,         // return web page 
        CURLOPT_HEADER         => false,        // don't return headers 
        CURLOPT_FOLLOWLOCATION => false,         // follow redirects 
        CURLOPT_ENCODING       => "",           // handle all encodings 
        CURLOPT_USERAGENT      => "zhasta16",    // who am i 
        CURLOPT_AUTOREFERER    => true,         // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 3,          // timeout on connect 
        CURLOPT_TIMEOUT        => 3,          // timeout on response 
        CURLOPT_MAXREDIRS      => 2,           // stop after 10 redirects
		CURLOPT_HTTPHEADER, array('Content-Type: text/xml'),	// post as xml file format
        CURLOPT_POST           => 1,            // sending POST data 
        CURLOPT_POSTFIELDS     => $vars,	    // POST vars 
        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl 
        CURLOPT_SSL_VERIFYPEER => false,        // 
        CURLOPT_VERBOSE        => 1             // 
    ); 
	$ch = curl_init();	     
    curl_setopt_array($ch, $options); 
    $content = curl_exec($ch); 
    $err     = curl_errno($ch); 
    $errmsg  = curl_error($ch); 
    $header  = curl_getinfo($ch); 
    curl_close($ch);      
    if ($content){
       return $content;
		}
    else
        return false;
}

function multi_request($urls, $vars){
	$results = array();
	foreach($urls as $key => $value){
		$response = request($value, $vars);
		$parsed_url = parse_url($value);
		if($response != null)
			$results[$parsed_url['host']] = $response;
		if($parsed_url == '141.19.143.136')
			fwrite(fopen("tmp.txt", 'w'), 'get from host');
	}
	return $results;
}

/**
 * request with POST method to remote hosts.
 *
 * @param	$urls	the URLs adresses of remote hosts
 * @param	$vars	the request data, that will send to all hosts
 * @return	a array, that the key is the host URL adress 
 * 			and the value is the recepted content from remote hosts.
*/
function multi_request_org($urls, $vars){
	$options = array();
	foreach($urls as $url){
		$parsed_url = parse_url($url);
		$options[$parsed_url[host]] =  array(
			CURLOPT_URL => $url,					// the URL adress			
			CURLOPT_RETURNTRANSFER => true,         // return web page 
			CURLOPT_HEADER         => false,        // don't return headers 
			CURLOPT_FOLLOWLOCATION => false,        // follow redirects 
			CURLOPT_ENCODING       => "",           // handle all encodings 
			CURLOPT_USERAGENT      => "zhasta16",    // who am i 
			CURLOPT_AUTOREFERER    => true,         // set referer on redirect 
			CURLOPT_CONNECTTIMEOUT => 2,          	// timeout on connect 
			CURLOPT_TIMEOUT        => 2,          	// timeout on response 
			CURLOPT_MAXREDIRS      => 1,          	// stop after 3 redirects
			CURLOPT_HTTPHEADER, array('Content-Type: text/xml'),	// post as xml file format
			CURLOPT_POST           => 1,            // sending POST data
			CURLOPT_POSTFIELDS     => $vars,	    // POST vars 
			CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl 
			CURLOPT_SSL_VERIFYPEER => false,        // 
			CURLOPT_VERBOSE        => 1             // 
		);
	}
	
	$ch_multi = curl_multi_init();
	$chs = array();
	foreach($options as $key => $option){
		$chs[$key] = curl_init();
		curl_setopt_array($chs[$key], $option);
		curl_multi_add_handle($ch_multi, $chs[$key]);
	}
	
	$active = 1;
	while($active) {
		$mrc = curl_multi_exec($ch_multi, $active);
	}
	
	$results = array();
	foreach($options as $key => $option){
		$content = curl_multi_getcontent($chs[$key]);
		
		if($content != null){
			$results[$key] = $content;
		}
		curl_multi_remove_handle($ch_multi, $chs[$key]);
	}
	curl_multi_close($ch_multi);
	
	return $results;
}

function encode($text, $key = null){
	if($key == null) $key = constant("CRYPT_PW");
	return base64_encode(xor_encode($text, $key));
}

// The superglobals $_GET and $_REQUEST are already url_decoded. 
// Using urldecode() on an element in $_GET or $_REQUEST could 
// have unexpected and dangerous results.
function decode($text, $key = null){
	if($key == null) $key = constant("CRYPT_PW");
	return xor_decode(base64_decode($text), $key);
}

function xor_encode($text, $key){
	$l_k = strlen($key);
	$l_t = strlen($text);

	if($l_k == 0) return $text; // Ohne Key keine Verschlüsselung!!!

	$encoded = "";
	$k = 0; // Position im Key
	for($i=0; $i<$l_t; $i++)
	{
		if($k >= $l_k) $k = 0; // Wenn ende des keys, dann wieder von vorne
		$encoded .= chr(ord($text[$i]) ^ ord($key[$k])); // Verschlüsselung
		$k++;
	}
	return $encoded;
}

function xor_decode($chiffre, $key){
	return xor_encode($chiffre, $key);
}

function object_to_array($var) {
    $result = array();
    $references = array();

    // loop over elements/properties
    foreach ($var as $key => $value) {
        // recursively convert objects
        if (is_object($value) || is_array($value)) {
            // but prevent cycles
            if (!in_array($value, $references)) {
                $result[$key] = object_to_array($value);
                $references[] = $value;
            }
        } else {
            // simple values are untouched
            $result[$key] = $value;
        }
    }
    return $result;
}

function json_encode2($param) {
    if (is_object($param) || is_array($param)) {
        $param = object_to_array($param);
    }
    return json_encode($param);
}

function getAgentID($xmlStr){
	error_reporting(1); // close all warning
	if($xmlStr != null){
		$xml = simplexml_load_string($xmlStr);
		if($xml === false)
			return null;
		else{
			$sxe = new SimpleXMLElement($xml->asXML());
			$nodes = $sxe->xpath('agent/id');
			if(count($nodes) == 1)
				return (string)$nodes[0];
		}
	}
	return null;
}

function getObjectID($xmlStr){
	error_reporting(1); // close all warning
	if($xmlStr != null){
		$xml = simplexml_load_string($xmlStr);
		if($xml === false)
			return null;
		else{
			$sxe = new SimpleXMLElement($xml->asXML());
			$nodes = $sxe->xpath('//id');
			if(count($nodes) == 1)
				return (string)$nodes[0];
		}
	}
	return null;
}

function getFilter($xmlStr){
	error_reporting(1); // close all warning
	if($xmlStr != null){
		$xml = simplexml_load_string($xmlStr);
		if($xml === false)
			return null;
		else{
			$sxe = new SimpleXMLElement($xml->asXML());
			$nodes = $sxe->xpath('filter');
			if(count($nodes) == 1)
				return (string)$nodes[0];
		}
	}
	return null;
}

function getResponseStatus($xmlStr){
	if($xmlStr != null){
		$xml = simplexml_load_string($xmlStr);
		if($xml === false)
			return 'ERROR';	// xml format error.
		else{
			$sxe = new SimpleXMLElement($xml->asXML());
			if(count($sxe->xpath('/response/OK')) == 1)
				return 'OK';	// positive response
			if(count($sxe->xpath('/response/NAK')) == 1)
				return 'NAK';	// negative response
		}
	}
	
	return 'ERROR';		// no response error or other errors
}

function isStartup(){
	$xml = simplexml_load_file(constant('CONFIGURATION'));	
	$sxe = new SimpleXMLElement($xml->asXML());
	$nodes = $sxe->xpath('//startup');
	if($nodes[0] == '1')
		return true;
	else
		return false;
}

function setStartup($status){
	$xml = simplexml_load_file(constant('CONFIGURATION'));	
	$sxe = new SimpleXMLElement($xml->asXML());
	$nodes = $sxe->xpath('//startup');
	$nodes[0][0] = $status;
	$sxe->asXML(constant('CONFIGURATION'));
}

function getHosts(){
	$xml = simplexml_load_file(constant('CONFIGURATION'));	
	$sxe = new SimpleXMLElement($xml->asXML());
	$nodes = $sxe->xpath('//host');
	$hosts = array();
	foreach($nodes as $host){
		$hosts[strval($host->id)] = strval($host->ip);
	}
	return $hosts;
}

function setHosts($hosts = null){
	$xml = simplexml_load_file(constant('CONFIGURATION'));	
	$sxe = new SimpleXMLElement($xml->asXML());
	if($hosts == null){
		$nodes = $sxe->xpath("//host");
		foreach($nodes as $node) unset($node[0]);
	}else{
		foreach($hosts as $id => $ip){
			if($ip == null){
				$nodes = $sxe->xpath("//host/id[. = '$id']/parent::*");
				if($nodes != null)
					unset($nodes[0][0]);
			}else{
				$nodes = $sxe->xpath("//host/id[. = '$id']/parent::*");
				if($nodes != null)
					$nodes[0]->ip = $ip;
				else{
					$child = $sxe->addChild('host');
					$child->addChild('id', $id);
					$child->addChild('ip', $ip);
				}
			}
		}
	}
	$sxe->asXML(constant('CONFIGURATION'));
}

?>