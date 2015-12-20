<?php

require_once("../include/auth.class.php");
require_once("../include/ajax.class.php");
require_once("../include/validation.class.php");

$auth = new Auth();

$ajax = new Ajax();

if($auth->isLogged()) {
	
	if(Validation::Query($_GET, array("choice_id", "value")) && is_numeric($_GET["choice_id"]) && in_array($_GET["value"], array(0, 1)) ) {
		
		$_MYSQLI->query('DELETE FROM answer WHERE answer_choice_id = '.$_MYSQLI->real_escape_string($_GET["choice_id"]).' AND answer_student_user_id = '.Auth::getUserId().' LIMIT 1');
		
		if($_GET["value"] == "1")
			$_MYSQLI->query('INSERT INTO answer (answer_id, answer_student_user_id, answer_choice_id) VALUES(NULL, '.Auth::getUserId().', '.$_MYSQLI->real_escape_string($_GET["choice_id"]).')');

		$ajax->data["notification"] = "OK";
		
	}
	else {
		
		$ajax->setError("arguments choice_id or value not valid");
	
	}
	
}
else {
	
	$ajax->setError("not_logged");
	
}

$ajax->out();

?>