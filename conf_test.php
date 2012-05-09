<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");
/* 
var_dump(isStartup());
setStartup(0);
var_dump(isStartup());
setStartup(1);
var_dump(isStartup());
setStartup(0);

setHosts();
var_dump(getHosts());
setHosts(array('host2'=>'234.3.234.9'));
var_dump(getHosts());
setHosts(array('host3'=>'234.3.234.10', 'host4'=>'234.3.234.10', 'host5'=>'234.3.234.103'));
var_dump(getHosts());
setHosts(array('host5'=>null));
var_dump(getHosts());
setHosts(); */

var_dump(isStartup());
var_dump(getHosts());

?>