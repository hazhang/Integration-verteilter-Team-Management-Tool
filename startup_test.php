<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/literal.php");

$params = constant("STARTUP_SHUTDOWN");
$data = startup($params);
//$data['141.19.143.136'] = 'zhasta16';
$_SESSION['hosts'] = $data;
var_dump($data);
?>