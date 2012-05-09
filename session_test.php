<?php 
session_start();
echo 'startup';
var_dump($_SESSION['startup']);
echo 'hosts';
var_dump($_SESSION['hosts']);
?>