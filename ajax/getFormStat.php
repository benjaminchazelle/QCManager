<?php

require("../include/auth.class.php");
require("../include/ajax.class.php");

$auth = new Auth();

$ajax = new Ajax();

if($auth->isLogged()) {
	
	
	
}
else {
	
	$ajax->setError("not_logged");
	
}

$ajax->out();

?>