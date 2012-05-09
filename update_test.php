<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$serial_obj = 'O:11:"Appointment":8:{s:2:"id";s:22:"zhasta16@4d26eb9b40da1";s:5:"title";s:8:"termin 0";s:11:"description";s:3:"123";s:9:"dateStart";s:0:"";s:7:"dateEnd";s:0:"";s:9:"timeStart";s:0:"";s:7:"timeEnd";s:0:"";s:5:"links";a:0:{}}';
$obj = unserialize($serial_obj);
var_dump($obj);
$agent = new Agent();
$object = $agent->update($obj);
?>