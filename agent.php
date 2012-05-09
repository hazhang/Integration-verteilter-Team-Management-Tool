<?php
session_start();
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$_SESSION['hosts'] = null;
$_SESSION['startup'] = false;

$agent = new Agent();
//$objects = $agent->getObjects();
//$list = $agent->objectsToList($objects);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/custom.js" type="text/javascript"></script>
<title>IGT WS2010 Gruppe 16 - Agent</title>
</head>
<body id="tab">
<div id="header">
<h2>Team Management Platform</h2>
<h4>- Agent: <?php echo constant('AGENTID') ?> -</h4>
<h5>IGT Integrationstechnologien WS2010/2011 - HS Mannheim</h5>
<div id="hosts">
<div id="systemControl">
<input id="startup" type="submit" class="button" value="Startup" />
<input id="shutdown" type="submit" class="button" value="Shutdown" />
</div>
<h6><div id="loading" style="display:none; text-align: center;"><img src="images/ajax-loader_bar.gif" /></div><div id="hostList"></div></h6>
</div>
</div>
<div id="mainContent">
<div id="leftColumn">
<div id="objectList">
<div id="tabHeader">
<ul id="tabnav"> 
	<li class="selectedTab" id="all">All</li> 
	<li id="contact">Contacts</li> 
	<li id="note">Notes</li> 
	<li id="task">Tasks</li> 
	<li id="appointment">Appointments</li>
	<li id="project">Projects</li>
	<li id="search">Search</li>
</ul>
</div>
<div id="searchContent">
<div id="inputArea">
<fieldset>
<div id="searchForm">
<input id='searchFilter' type='text' value='' /><input class='button' type='submit' id='search' value='Search' />
</div>
</fieldset>
</div>
<div id="loading" style="display:none; text-align: center; margin-top: 20px;"><img src="images/ajax-loader_circul.gif" /></div>
<div id="searchResultsContent"><ul></ul></div>
</div>
<div id="listContent"><ul></ul></div>
</div>
</div>

<div id="rightColumn">


<div id="messageContent">
<div id="message">
<div id="inputArea">
<label></label>
<br />
</div>
</div>
</div>

<div id="objectContent">
<div id="inputArea">
<!-- Contact Content -->
<fieldset id="objectTyp"><legend><strong></strong></legend>
<div id="objectForm">
<div id="formInputField" title="contact">
<?php
	$fooObject = new Contact();
	foreach($fooObject as $key => $value){
		$disabled = '';
		if($key == 'id'){
			$disabled = 'disabled';
		}
		echo("<label for='$key'>".ucfirst($key)."</label><input name='$key' type='text' id='$key' value='$value' ".$disabled." />");
	}
?>
</div>
<div id="formInputField" title="note">
<?php
	$fooObject = new Note();
	foreach($fooObject as $key => $value){
		$disabled = '';
		if($key == 'id'){
			$disabled = 'disabled';
		}
		echo("<label for='$key'>".ucfirst($key)."</label><input name='$key' type='text' id='$key' value='$value' ".$disabled." />");
	}
?>
</div>
<div id="formInputField" title="task">
<?php
	$fooObject = new Task();
	foreach($fooObject as $key => $value){
		$disabled = '';
		if($key == 'id'){
			$disabled = 'disabled';
		}
		echo("<label for='$key'>".ucfirst($key)."</label><input name='$key' type='text' id='$key' value='$value' ".$disabled." />");
	}
?>
</div>
<div id="formInputField" title="appointment">
<?php
	$fooObject = new Appointment();
	foreach($fooObject as $key => $value){
		$disabled = '';
		if($key == 'id'){
			$disabled = 'disabled';
		}
		echo("<label for='$key'>".ucfirst($key)."</label><input name='$key' type='text' id='$key' value='$value' ".$disabled." />");
	}
?>
</div>
<div id="formInputField" title="project">
<?php
	$fooObject = new Project();
	foreach($fooObject as $key => $value){
		$disabled = '';
		if($key == 'id'){
			$disabled = 'disabled';
		}
		echo("<label for='$key'>".ucfirst($key)."</label><input name='$key' type='text' id='$key' value='$value' ".$disabled." />");
	}
?>
</div>
<div id="formButtons">
<div id="newButton">
<input class="button" id="new" type="submit" value="New" />
</div>
<div id="updateDeleteButtons">
<input class="button" id="update" type="submit" value="Update" />
<input class="button" id="delete" type="reset" value="Delete" />
</div>
<input class="button" id="objectTyp" type="hidden" value="" />
<input class="button" id="objectID" type="hidden" value="" />
</div>
</div>
</fieldset>
<div id="linkedObjects">
<br />
<fieldset><legend>Linked Objects:</legend>
<div id="objectsList">
</div>
</fieldset>
</div>
</div>
</div> <!-- the objectContent end. -->
</div> <!-- the rightColumn end. -->

</div>

<div id="footer">
</div>
</body>
</html>