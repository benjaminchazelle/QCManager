<?php

require_once("../include/rules.inc.php");
require_once("../include/database.inc.php");
require_once("../include/auth.class.php");
require_once("../include/ajax.class.php");
require_once("../include/validation.class.php");


$auth = new Auth();

$ajax = new Ajax();

if($auth->isLogged()) {
	
	if(Validation::Query($_GET, array("questionnaire_id"))) {
		
		$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'"');
		
		if($questionnaire_result->num_rows == 1)	{
			
			$questionnaire = $questionnaire_result->fetch_object();
			
			$ajax->data["questionnaire"] = $questionnaire;
			
			$ajax->data["questions"] = array();
			
			$questions_result = $_MYSQLI->query('
				SELECT *
				FROM question q
				JOIN choice c ON c.choice_question_id = q.question_id
				WHERE questionnaire_id = "'.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'"');

				while($question = $questions_result->fetch_object()) {
					
					if(!isset($ajax->data["questions"][$question->question_id])) {
						
						$ajax->data["questions"][$question->question_id] = array("content" => utf8_encode($question->question_content), "type" => utf8_encode($question->question_type), "choices" => array());
						
					}
						
					$ajax->data["questions"][$question->question_id]["choices"][$question->choice_question_id] = array("content" => utf8_encode($question->choice_content), "choice_hint" => utf8_encode($question->choice_hint));
					
				}

		}
		else {

			$ajax->setError("questionnaire not exists");

		}
		
	}
	else {
		
		$ajax->setError("query_error: questionnaire_id missing");
		
	}
	
}
else {
	
	$ajax->setError("not_logged");
	
}

$ajax->out();

?>