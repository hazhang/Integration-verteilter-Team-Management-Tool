<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$agent = new Agent();
$objs = $agent->getObjects();
var_dump($objs);

$newContact = new Contact();
$newContact->id = "5";
$newContact->street = "mannheim";
$newContact->links = array(
							'1324',
							'456',
							'678'
							);
//var_dump($newContact->toXML());
// $xmlfile = "../data/contacts.xml";
// ewContact->create($xmlfile);
// $contact2 = new Contact();
// $contact2->read("5", $xmlfile);
// var_dump($contact2);
// ontact2->delete("2", $xmlfile);
// $contact2->firstname = "Liu";
// $contact2->update($xmlfile);

//$agent = new Agent("IGT_G16");
//var_dump($agent->listContacts());

?>