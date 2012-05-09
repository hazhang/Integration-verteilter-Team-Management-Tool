<?php
/* class Timestamp{
	public $year;
	public $month;
	public $day;
	public $hour;
	public $minutes;
	
	function __construct($year, $month, $day, $hour = null, $minutes = null){
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
		$this->hour = $hour;
		$this->minutes = $minutes;
	}
}

class TimestampUtils{
	static function toTimestamp($sxe){
		if($sxe instanceof SimpleXMLElement){
			$timestamp = new Timestamp();
			$timestamp->year = strval($sxe->year);
			$timestamp->month = strval($sxe->month);
			$timestamp->day = strval($sxe->day);
			$timestamp->hour = strval($sxe->hour);
			$timestamp->minutes = strval($sxe->minutes);
			return $timestamp;
		}
	}
} */

class Contact {
	public $id;
	public $firstname;
	public $lastname;
	public $street;
	public $town;
	public $zip;
	public $phone;
	public $mobile;
	public $email;
	public $links = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->firstname = strval($sxe->firstname);
			$this->lastname = strval($sxe->lastname);
			$this->street = strval($sxe->street);
			$this->town = strval($sxe->town);
			$this->zip = strval($sxe->zip);
			$this->phone = strval($sxe->phone);
			$this->mobile = strval($sxe->mobile);
			$this->email = strval($sxe->email);
			if(isset($sxe->links) && $sxe->links != null){
				foreach($sxe->links->id as $link){
					if($link != null)
						$this->links[] = strval($link);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->firstname.' '.$this->lastname, 
				'type' => get_class($this));
	}
	
	/* function toXML(){
		$sxe = $this::createContactXML();	
		return $sxe->asXML();
	} */
	
	/* function createContactXML(){
		$dom = new domDocument;
		$dom->formatOutput = true;
		$root = $dom->appendChild($dom->createElement('contacts'));		
		$sxe = simplexml_import_dom($dom);
		$node = $sxe->addChild("contact");
		$node->addChild("id", $this->id);
		$node->addChild("firstname", $this->firstname);
		$node->addChild("lastname", $this->lastname);
		$node->addChild("street", $this->street);
		$node->addChild("town", $this->town);
		$node->addChild("zip", $this->zip);
		$node->addChild("phone", $this->phone);
		$node->addChild("mobile", $this->mobile);
		$node->addChild("email", $this->email);
		$links = $node->addChild("links");
		foreach($this->links as $link){
			if($link != null)
				$links->addChild("id", $link);
		}
		return $sxe;
	} */

	function toXML(){
		$xmlStr = '<contact>';
		$xmlStr .= '<id>'.$this->id.'</id>';
		$xmlStr .= '<firstname>'.$this->firstname.'</firstname>';
		$xmlStr .= '<lastname>'.$this->lastname.'</lastname>';
		$xmlStr .= '<street>'.$this->street.'</street>';
		$xmlStr .= '<town>'.$this->town.'</town>';
		$xmlStr .= '<zip>'.$this->zip.'</zip>';
		$xmlStr .= '<phone>'.$this->phone.'</phone>';
		$xmlStr .= '<mobile>'.$this->mobile.'</mobile>';
		$xmlStr .= '<email>'.$this->email.'</email>';
		if($this->links == null){
			$xmlStr .= '<links/>';
		}else{
			$xmlStr .= '<links>';
			foreach($this->links as $link){
				if($link != null)
					$xmlStr .= '<id>'.$link.'</id>';
			}
			$xmlStr .= '</links>';
		}
		$xmlStr .= '</contact>';
		return $xmlStr;
	}
	
	function addToXML($xml){
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("contact");
		$node->addChild("id", $this->id);
		$node->addChild("firstname", $this->firstname);
		$node->addChild("lastname", $this->lastname);
		$node->addChild("street", $this->street);
		$node->addChild("town", $this->town);
		$node->addChild("zip", $this->zip);
		$node->addChild("phone", $this->phone);
		$node->addChild("mobile", $this->mobile);
		$node->addChild("email", $this->email);
		$node_links = $node->addChild("links");
		if($this->links != null){
			foreach($this->links as $link){
				if($link != null)
					$node_links->addChild("id", $link);
			}
		}		
		return $sxe;
	}
}

class Note {
	public $id;
	public $title;
	public $content;
	public $links = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->title = strval($sxe->title);
			$this->content = strval($sxe->content);
			if(isset($sxe->links) && $sxe->links != null){
				foreach($sxe->links->id as $link){
					if($link != null)
						$this->links[] = strval($link);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->title, 
				'type' => get_class($this));
	}
	
	function toXML(){
		$xmlStr = '<note>';
		$xmlStr .= '<id>'.$this->id.'</id>';
		$xmlStr .= '<title>'.$this->title.'</title>';
		$xmlStr .= '<content>'.$this->content.'</content>';
		if($this->links == null){
			$xmlStr .= '<links/>';
		}else{
			$xmlStr .= '<links>';
			foreach($this->links as $link){
				if($link != null)
					$xmlStr .= '<id>'.$link.'</id>';
			}
			$xmlStr .= '</links>';
		}
		$xmlStr .= '</note>';
		return $xmlStr;
	}
	
	function addToXML($xml){
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("note");
		$node->addChild("id", $this->id);
		$node->addChild("title", $this->title);
		$node->addChild("content", $this->content);
		$node_links = $node->addChild("links");
		if($this->links != null){
			foreach($this->links as $link){
				if($link != null)
					$node_links->addChild("id", $link);
			}
		}		
		return $sxe;
	}
}


class Task {
	public $id;
	public $title;
	public $content;
	public $date;
	public $deadline;
	public $links = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->title = strval($sxe->title);
			$this->content = strval($sxe->content);
			$this->date = strval($sxe->date);
			$this->deadline = strval($sxe->deadline);
			if(isset($sxe->links) && $sxe->links != null){
				foreach($sxe->links->id as $link){
					if($link != null)
						$this->links[] = strval($link);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->title, 
				'type' => get_class($this));
	}
	
	function toXML(){
		$xmlStr = '<task>';
		$xmlStr .= '<id>'.$this->id.'</id>';
		$xmlStr .= '<title>'.$this->title.'</title>';
		$xmlStr .= '<content>'.$this->content.'</content>';
		$xmlStr .= '<date>'.$this->date.'</date>';
		$xmlStr .= '<deadline>'.$this->deadline.'</deadline>';
		if($this->links == null){
			$xmlStr .= '<links/>';
		}else{
			$xmlStr .= '<links>';
			foreach($this->links as $link){
				if($link != null)
					$xmlStr .= '<id>'.$link.'</id>';
			}
			$xmlStr .= '</links>';
		}
		$xmlStr .= '</task>';
		return $xmlStr;
	}
	
	function addToXML($xml){
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("task");
		$node->addChild("id", $this->id);
		$node->addChild("title", $this->title);
		$node->addChild("content", $this->content);
		$node->addChild("date", $this->date);
		$node->addChild("deadline", $this->deadline);
		$node_links = $node->addChild("links");
		if($this->links != null){
			foreach($this->links as $link){
				if($link != null)
					$node_links->addChild("id", $link);
			}
		}		
		return $sxe;
	}
}

class Appointment {
	public $id;
	public $title;
	public $description;
	public $dateStart;
	public $dateEnd;
	public $timeStart;
	public $timeEnd;
	public $links = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->title = strval($sxe->title);
			$this->description = strval($sxe->description);
			$this->dateStart = strval($sxe->dateStart);
			$this->dateEnd = strval($sxe->dateEnd);
			$this->timeStart = strval($sxe->timeStart);
			$this->timeEnd = strval($sxe->timeEnd);
			if(isset($sxe->links) && $sxe->links != null){
				foreach($sxe->links->id as $link){
					if($link != null)
						$this->links[] = strval($link);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->title, 
				'type' => get_class($this));
	}
	
	function toXML(){
		$xmlStr = '<appointment>';
		$xmlStr .= '<id>'.$this->id.'</id>';
		$xmlStr .= '<title>'.$this->title.'</title>';
		$xmlStr .= '<description>'.$this->description.'</description>';
		$xmlStr .= '<dateStart>'.$this->dateStart.'</dateStart>';
		$xmlStr .= '<dateEnd>'.$this->dateEnd.'</dateEnd>';
		$xmlStr .= '<timeStart>'.$this->timeStart.'</timeStart>';
		$xmlStr .= '<timeEnd>'.$this->timeEnd.'</timeEnd>';
		if($this->links == null){
			$xmlStr .= '<links/>';
		}else{
			$xmlStr .= '<links>';
			foreach($this->links as $link){
				if($link != null)
					$xmlStr .= '<id>'.$link.'</id>';
			}
			$xmlStr .= '</links>';
		}
		$xmlStr .= '</appointment>';
		return $xmlStr;
	}
	
	function addToXML($xml){
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("appointment");
		$node->addChild("id", $this->id);
		$node->addChild("title", $this->title);
		$node->addChild("description", $this->description);
		$node->addChild("dateStart", $this->dateStart);
		$node->addChild("dateEnd", $this->dateEnd);
		$node->addChild("timeStart", $this->timeStart);
		$node->addChild("timeEnd", $this->timeEnd);
		$node_links = $node->addChild("links");
		if($this->links != null){
			foreach($this->links as $link){
				if($link != null)
					$node_links->addChild("id", $link);
			}
		}		
		return $sxe;
	}
}

class Project {
	public $id;
	public $title;
	public $description;
	public $startdate;
	public $enddate;
	public $starttime;
	public $endtime;
	public $links = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->title = strval($sxe->title);
			$this->description = strval($sxe->description);
			$this->startdate = strval($sxe->startdate);
			$this->enddate = strval($sxe->enddate);
			$this->starttime = strval($sxe->starttime);
			$this->endtime = strval($sxe->endtime);
			if(isset($sxe->links) && $sxe->links != null){
				foreach($sxe->links->id as $link){
					if($link != null)
						$this->links[] = strval($link);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->title, 
				'type' => get_class($this));
	}
	
	function toXML(){
		$xmlStr = '<project>';
		$xmlStr .= '<id>'.$this->id.'</id>';
		$xmlStr .= '<title>'.$this->title.'</title>';
		$xmlStr .= '<description>'.$this->description.'</description>';
		$xmlStr .= '<startdate>'.$this->startdate.'</startdate>';
		$xmlStr .= '<enddate>'.$this->enddate.'</enddate>';
		$xmlStr .= '<starttime>'.$this->starttime.'</starttime>';
		$xmlStr .= '<endtime>'.$this->endtime.'</endtime>';
		if($this->links == null){
			$xmlStr .= '<links/>';
		}else{
			$xmlStr .= '<links>';
			foreach($this->links as $link){
				if($link != null)
					$xmlStr .= '<id>'.$link.'</id>';
			}
			$xmlStr .= '</links>';
		}
		$xmlStr .= '</project>';
		return $xmlStr;
	}
	
	function addToXML($xml){
		$sxe = new SimpleXMLElement($xml->asXML());
		$node = $sxe->addChild("project");
		$node->addChild("id", $this->id);
		$node->addChild("title", $this->title);
		$node->addChild("description", $this->description);
		$node->addChild("startdate", $this->startdate);
		$node->addChild("enddate", $this->enddate);
		$node->addChild("starttime", $this->starttime);
		$node->addChild("endtime", $this->endtime);
		$node_links = $node->addChild("links");
		if($this->links != null){
			foreach($this->links as $link){
				if($link != null)
					$node_links->addChild("id", $link);
			}
		}		
		return $sxe;
	}
}

/* class Project {
	public $id;
	public $titel;
	public $description;
	public $begin;
	public $deadline;
	public $participants = array();
	
	function __construct($sxe = null){
		if($sxe instanceof SimpleXMLElement){
			$this->id = strval($sxe->id);
			$this->titel = strval($sxe->titel);
			if(isset($sxe->description)) $this->description = strval($sxe->description);
			if(isset($sxe->begin))$this->begin = TimestampUtils::toTimestamp($sxe->begin);
			if(isset($sxe->deadline))$this->deadline = TimestampUtils::toTimestamp($sxe->deadline);
			if(isset($sxe->participants)){
				foreach($sxe->participants->id as $participant){
					if($participant != null)
						$this->participants[] = strval($participant);
				}
			}
		}
	}
	
	function toShortArray(){
		return array('id' => $this->id, 
				'content' => $this->titel, 
				'type' => get_class($this));
	}
} */

?>