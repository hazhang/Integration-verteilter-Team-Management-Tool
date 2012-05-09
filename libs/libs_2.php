<?php
include_once("libs/literal.php");

/**
 * server startup and requests to all hosts in the same net.
 *
 * @param	$param	the data to send
 * @return	a array, that saves the started remote hosts' URL-adresses.
*/
function startup($param){
	$request = 'request='.urlencode(encode($param));
	//$request = 'request='.encode($param);
	//var_dump($request);
	$hosts = array();
	$startedHosts = array();
	$i = 121;
	while($i <= 136){
		$hosts[] = "141.19.123.".$i."/startup.php";
		$i++;
	}
	var_dump($hosts);

	/* $data = request("141.19.143.133/startup.php", $request);
	return $data; */

	$results = multi_request($hosts, $request);
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
        CURLOPT_CONNECTTIMEOUT => 5,          // timeout on connect 
        CURLOPT_TIMEOUT        => 5,          // timeout on response 
        CURLOPT_MAXREDIRS      => 3,           // stop after 10 redirects
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
    if ($content)   
        return decode(base64_decode($content));  
    else
        return false;
}

/**
 * request with POST method to remote hosts.
 *
 * @param	$urls	the URLs adresses of remote hosts
 * @param	$vars	the request data, that will send to all hosts
 * @return	a array, that the key is the host URL adress 
 * 			and the value is the recepted content from remote hosts.
*/
function multi_request($urls, $vars){
	$options = array();
	foreach($urls as $url){
		$options[$url] =  array(
			CURLOPT_URL => $url,					// the URL adress			
			CURLOPT_RETURNTRANSFER => true,         // return web page 
			CURLOPT_HEADER         => false,        // don't return headers 
			CURLOPT_FOLLOWLOCATION => false,        // follow redirects 
			CURLOPT_ENCODING       => "",           // handle all encodings 
			CURLOPT_USERAGENT      => "zhasta16",    // who am i 
			CURLOPT_AUTOREFERER    => true,         // set referer on redirect 
			CURLOPT_CONNECTTIMEOUT => 5,          	// timeout on connect 
			CURLOPT_TIMEOUT        => 5,          	// timeout on response 
			CURLOPT_MAXREDIRS      => 3,          	// stop after 3 redirects
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

function decode($chiffre, $key = null){
	return encode($chiffre, $key);
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

function getAgentID($param){
	if($param != null){
		$xml = simplexml_load_string($param);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->xpath('agent/id');
		if(count($nodes) == 1)
			return $nodes[0];
	}
}
?>