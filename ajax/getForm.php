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
			
			$own = $questionnaire->questionnaire_user_id != Auth::getUserId();

			$ajax->data["questionnaire"]->own = $own;
			
			$query = $own ?	'	SELECT *
								FROM question q
								JOIN choice c ON c.choice_question_id = q.question_id
								LEFT JOIN answer a ON a.answer_choice_id = c.choice_id
								WHERE question_questionnaire_id = '.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'
								ORDER BY question_num ASC, question_id ASC
							' :
							
							'	SELECT *, SUM(case when answer_student_user_id = '.Auth::getUserId().' then 1 else 0 end) as checked
								FROM question q
								JOIN choice c ON c.choice_question_id = q.question_id
								LEFT JOIN answer a ON a.answer_choice_id = c.choice_id
								WHERE question_questionnaire_id = '.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).' GROUP BY choice_id
								ORDER BY question_num ASC, question_id ASC
							';

			
			$ajax->data["questions"] = array();

			$questions_result = $_MYSQLI->query($query);


			while($question = $questions_result->fetch_object()) {
				
				if(!isset($ajax->data["questions"][$question->question_id])) {
					
					$ajax->data["questions"][$question->question_id] = array("num" => utf8_encode($question->question_num), "content" => utf8_encode($question->question_content), "type" => utf8_encode($question->question_type), "hint" => utf8_encode($question->question_hint), "choices" => array());
					
				}
					
				$ajax->data["questions"][$question->question_id]["choices"][$question->choice_id] = array("content" => utf8_encode($question->choice_content));
				
				if($own) {
					$ajax->data["questions"][$question->question_id]["choices"][$question->choice_id]["correct"] = $question->choice_status == 1;
					$ajax->data["questions"][$question->question_id]["weight"] = $question->question_weight;
				}
				else {
					$ajax->data["questions"][$question->question_id]["choices"][$question->choice_id]["checked"] = $question->checked == 1;
				}
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