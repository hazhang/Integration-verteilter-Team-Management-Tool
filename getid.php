<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/literal.php");

$params = "<response><agent><id>Schwund15</id></agent></response>";
getAgentID("<request><agent><id>zhasta16</id></agent></request>");
var_dump($_SESSION['hosts']);
?>