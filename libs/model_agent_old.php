<?php
interface iAgent {
	function listObjects($objectTyp, $xmlFile);

	function createContact($contact, $xmlFile);
	function readContact($contactID, $xmlFile);
	function updateContact($contact, $xmlFile);
	function deleteContact($contactID, $xmlFile);
	function listContacts($xmlFile);
	
	// function createNotiz($notiz, $xmlFile);
	// function readNotiz($notizID, $xmlFile);
	// function updateNotiz($notiz, $xmlFile);
	// function deleteNotiz($notizID, $xmlFile);
	// function listNotiz($xmlFile);
	
	function createProject($project, $xmlFile);
	function readProject($projectID, $xmlFile);
	function updateProject($project, $xmlFile);
	function deleteProject($projectID, $xmlFile);
	function listProjects($xmlFile);
	
} 

class Agent implements iAgent {
	public $agentID;
	
	function __contruct($agentID){
		$this->agentID = $agentID;
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
	
	function getObjects($objectTyp = null, $xmlElem = null){
		if($xmlElem == null){
			$xmlFile = constant("OBJECT_XML");
			$xmlElem = simplexml_load_file($xmlFile);
		}
		
		$objects = array();
		switch($objectTyp) {
			case "Contact":
				return getAllContactsToList();
				break;
			case "Note":
				return getAllNotesToList();
				break;
			case "Task":
				return getAllTasksToList();
				break;
			case "Time":
				return getAllTerminsToList();
				break;
			case "Project":
				return getAllProjectsToList();
				break;
			default:
				{					
					$sxe = new SimpleXMLElement($xmlElem->asXML());
					$nodes = $sxe->children();
					if($nodes != null){
						foreach($nodes as $node){
							switch(strtolower($node->getName())){
								case "contact":
									$objects[] = new Contact($node); break;
								case "project":
									$objects[] = new Project($node); break;
							}
						}
					}
					break;
				}
		}
		
		return $objects;
	}
	
	function listObjects($objectTyp = null, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
		
		$list = array();
		switch($objectTyp) {
			case "Contact":
				return getAllContactsToList();
				break;
			case "Note":
				return getAllNotesToList();
				break;
			case "Task":
				return getAllTasksToList();
				break;
			case "Time":
				return getAllTerminsToList();
				break;
			case "Project":
				return getAllProjectsToList();
				break;
			default:
				{
					$xml = simplexml_load_file($xmlFile);
					$sxe = new SimpleXMLElement($xml->asXML());
					$nodes = $sxe->children();
					if($nodes != null){
						foreach($nodes as $node){
							switch(strtolower($node->getName())){
								case "contact":
									$object = new Contact($node); $list[] = $object->toShortArray(); break;
								case "project":
									$object = new Project($node); $list[] = $object->toShortArray(); break;
							}
						}
					}
					break;
				}
		}
		
		return $list;
	}
	
	function objectsToList($objects){
		$list = array();
		foreach($objects as $object){
			switch(get_class($object)) {
				case "Contact":
					$list[] = array('id' => $object->id, 
								'content' => $object->firstname.' '.$object->lastname, 
								'type' => get_class($object));
					break;
				case "Note":
					break;
				case "Task":
					break;
				case "Time":
					break;
				case "Project":
					$list[] = array('id' => $object->id, 
								'content' => $object->titel, 
								'type' => get_class($object));
					break;
				}
		}
		return $list;
	}

	/**
	 * create a new contact
	*/ 
	function createContact($contact, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("contact");
		$node->addChild("id", $contact->id);
		$node->addChild("firstname", $contact->firstname);
		$node->addChild("lastname", $contact->lastname);
		$node->addChild("street", $contact->street);
		$node->addChild("town", $contact->town);
		$node->addChild("zip", $contact->zip);
		$node->addChild("phone", $contact->phone);
		$node->addChild("mobile", $contact->mobile);
		$node->addChild("email", $contact->email);
		$links = $node->addChild("links");
		foreach($contact->links as $link){
			if($link != null)
				$links->addChild("id", $link);
		}
		$sxe->asXML($xmlFile);
	}
	
	
	
	/**
	 * read a contact
	*/ 
	function readContact($contactID, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->xpath("contact/id[. = $contactID]/parent::*");
		$node = $nodes[0];
		if($node != null)
			return new Contact($node);
		else
			return null;
	}
	
	/**
	 * update a contact
	*/ 
	function updateContact($contact, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		self::deleteContact($contact->id, $xmlFile);
		self::createContact($contact, $xmlFile);
	}
	
	/**
	 * delete a contact
	*/ 
	function deleteContact($contactID, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->xpath("contact/id[. = $contactID]/parent::*");
		unset($nodes[0][0]);
		$sxe->saveXML($xmlFile);
	}
	
	/**
	 * list all contacts
	*/ 
	function listContacts($filter = null, $xmlFile = null){
		if($xmlFile == null)
			$xmlFile = constant("OBJECT_XML");
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->children();
		if($nodes != null){
			$contacts = array();
			foreach($nodes as $node){
				$contacts[] = new Contact($node);
			}
			return $contacts;
		}else{
			return null;
		}
	}
	
	function createProject($project, $xmlFile){
		if($xmlFile == null)
			$xmlFile = constant("PROJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("project");
		$node->addChild("id", $project->id);
		$node->addChild("titel", $project->firstname);
		$node->addChild("begin", $project->lastname);
		$node->addChild("deadline", $project->street);
		$childnode = $node->addChild("links");
		foreach($childnode->links as $link){
			if($link != null)
				$links->addChild("id", $link);
		}
		$sxe->asXML($xmlFile);
	}
	
	function readProject($projectID, $xmlFile){
		if($xmlFile == null)
			$xmlFile = constant("PROJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->xpath("project/id[. = $contactID]/parent::*");
		$node = $nodes[0];
		if($node != null)
			return new Contact($node);
		else
			return null;
	}
	
	function updateProject($project, $xmlFile){
		if($xmlFile == null)
			$xmlFile = constant("PROJECT_XML");
			
		self::deleteProject($project->id, $xmlFile);
		self::createProject($project, $xmlFile);
	}
	
	function deleteProject($projectID, $xmlFile){
		if($xmlFile == null)
			$xmlFile = constant("PROJECT_XML");
			
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->xpath("project/id[. = $projectID]/parent::*");
		unset($nodes[0][0]);
		$sxe->saveXML($xmlFile);
	}
	
	function listProjects($xmlFile){
		if($xmlFile == null)
			$xmlFile = constant("PROJECT_XML");
		$xml = simplexml_load_file($xmlFile);
		$sxe = new SimpleXMLElement($xml->asXML());
		$nodes = $sxe->children();
		if($nodes != null){
			$projects = array();
			foreach($nodes as $node){
				$projects[] = new Project($node);
			}
			return $projects;
		}else{
			return null;
		}
	}
}

?>