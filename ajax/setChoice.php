<?php

require_once("../include/auth.class.php");
require_once("../include/ajax.class.php");

$auth = new Auth();

$ajax = new Ajax();

if($auth->isLogged()) {
	
	
	
}
else {
	
	$ajax->setError("not_logged");
	
}

$ajax->out();

?>