<?php
interface iAgent {
	function getObjects($filter, $xmlFile);
	function create($object, $xmlFile);
	function read($objectID, $xmlFile);
	function update($object, $xmlFile);
	function delete($objectID, $xmlFile);
	//function listLocal($xmlFile);
} 

class Agent implements iAgent {
	public $agentID;
	public $objectTyps = array('Contact' => 'contact',
								'Note' => 'note',
								'Task' => 'task',
								'Appointment' => 'appointment',
								'Project' => 'project');
	
	function __contruct($agentID = null){
		if($agentID != null)
			$this->agentID = $agentID;
		else
			$this->agentID = constant('AGENTID');
	}
	
	function buildID($objectID){
		return $agentID.'@'.$objectID;
	}
	
	function parseID($id){
		$ids = explode('@', $id);
		if(sizeof($ids) == 2)
			return array('agentID' => $ids[0],
						 'objectID' => $ids[1]
						 );
		else
			return null;
	}
	
	function getObjects($filterStr = null, $xmlElem = null){
		if($xmlElem == null){
			$xmlFile = constant("OBJECT_XML");
			$xmlElem = simplexml_load_file($xmlFile);
		}		
		$objects = array();
		$sxe = new SimpleXMLElement($xmlElem->asXML());
		$nodes = array();
		if($filterStr == null){
			$nodes = $sxe->children();			
		}else{
			$xpathArr = self::parseSearchFilterToXPath($filterStr);
			foreach($xpathArr as $xpathElem){
				$or_nodes = $sxe->xpath($xpathElem);
				//var_dump($or_nodes);
				if($or_nodes != null)
					$nodes = array_merge($nodes, $or_nodes);
			}
		}
		
		if($nodes != null){
			foreach($nodes as $node){
				$objectTyp = ucfirst(strtolower($node->getName()));
				if(in_array($objectTyp, array_keys($this->objectTyps))){
						$objects[] = new $objectTyp($node);
				}
			}
		}		
				
		return $objects;
	}
		
	function parseSearchFilterToXPath($filterStr){
		$xpathArr = array();
		$or_filter = explode('|', trim($filterStr));
		foreach($or_filter as $or_f){
			if(strpos($or_f, ':') === false)
				$xpathArr[] = "//*[contains(.,'".trim($or_f)."')]/parent::*";
			else{
				 $tag_filter = explode(':', trim($or_f));
				if(count($tag_filter) == 2){
					if($tag_filter[1] != null){
						$xpathArr[] = "//".trim(strtolower($tag_filter[0]))."[contains(.,'".trim($tag_filter[1])."')]/parent::*";
					}else{
						$xpathArr[] = "//".trim(strtolower($tag_filter[0]));
					}
				}
			}
		}
		//var_dump($xpathArr);
		return $xpathArr;
	}

	/**
	 * create a new object
	*/ 
	function create($object, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");		
		
		$xml = simplexml_load_file($xmlFile);
		$xml = $object->addToXML($xml);
		$xml->asXML($xmlFile);
	}
	
	function read($objectID, $xmlElem = null){
		if($xmlElem == null){
			$xmlFile = constant("OBJECT_XML");
			$xmlElem = simplexml_load_file($xmlFile);
		}		
		
		$sxe = new SimpleXMLElement($xmlElem->asXML());
		
		foreach($this->objectTyps as $typName => $typ){
			$nodes = $sxe->xpath("$typ/id[. = '$objectID']/parent::*");
			$node = $nodes[0];
			if($node != null)
				return new $typName($node);
		}
	}
	
	/**
	 * update a object
	*/ 
	function update($object, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		self::delete($object->id, $xmlFile);
		self::create($object, $xmlFile);
	}
	
	/**
	 * delete a object from xml file
	*/ 
	function delete($objectID, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		
		foreach($this->objectTyps as $typName => $typ){
			$nodes = $sxe->xpath("$typ/id[. = '$objectID']/parent::*");
			$node = $nodes[0];
			if($node != null){
				unset($nodes[0][0]);
				$sxe->saveXML($xmlFile);
				return true;
			}
		}
	}

}
?>