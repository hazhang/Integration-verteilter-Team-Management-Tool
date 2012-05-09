<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$agent = new Agent();
$filter = constant('RESPONSE_NAK');
var_dump(getResponseStatus($filter));

?>