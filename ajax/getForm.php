<?php

require_once("../include/rules.inc.php");
require_once("../include/database.inc.php");
require_once("../include/auth.class.php");
require_once("../include/ajax.class.php");
require_once("../include/validation.class.php");


$auth = new Auth();

$ajax = new Ajax();

$correspondance = array("same" => 0, "middle" => 1, "zero" => 2, "all" => 3);
		
if($auth->isLogged()) {
	
	if(Validation::Query($_GET, array("questionnaire_id", "rule")) && isset($correspondance[$_GET["rule"]])) {
		
		// $q = 'UPDATE questionnaire SET questionnaire_notation_rule = '.$correspondance[$_GET["rule"]].' WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'"';
		// echo $q;
		
		$_MYSQLI->query('UPDATE questionnaire SET questionnaire_notation_rule = '.$correspondance[$_GET["rule"]].' WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'"');
		
		$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'" LIMIT 1');
		
		if($questionnaire_result->num_rows == 1)	{
			
			$questionnaire = $questionnaire_result->fetch_object();
			
			$ajax->data["questionnaire"] = $questionnaire;

			$ajax->data["questionnaire"]->own = $questionnaire->questionnaire_user_id == Auth::getUserId();
			
			$query = '	SELECT *
								FROM question q
								JOIN choice c ON c.choice_question_id = q.question_id
								LEFT JOIN answer a ON a.answer_choice_id = c.choice_id
								WHERE question_questionnaire_id = '.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'
								GROUP BY choice_id
								ORDER BY question_num ASC, question_id ASC
							';

			$ajax->data["questions"] = array();

			$questions_result = $_MYSQLI->query($query);


			while($question = $questions_result->fetch_object()) {
				
				if(!isset($ajax->data["questions"][$question->question_id])) {
					
					$ajax->data["questions"][$question->question_id] = array("num" => ($question->question_num), "content" => ($question->question_content), "type" => ($question->question_type), "hint" => ($question->question_hint), "choices" => array(), "total_correct" => 0);
					
				}
					
				$ajax->data["questions"][$question->question_id]["choices"][$question->choice_id] = array("content" => ($question->choice_content));

					
					if($question->choice_status == 1)
						$ajax->data["questions"][$question->question_id]["total_correct"]++;
					
					$ajax->data["questions"][$question->question_id]["choices"][$question->choice_id]["correct"] = $question->choice_status == 1;
					$ajax->data["questions"][$question->question_id]["weight"] = $question->question_weight;
			}
			

			
			$question_keys = array_fill_keys((array_keys($ajax->data["questions"])), array("correct" => 0, "uncorrect" => 0));
			
			$ajax->data["answers"] = array();
		
			$query = '	SELECT answer_student_user_id, CONCAT(user_firstname, " ", user_lastname) AS user_identity, question_id, choice_id
						FROM question q
						JOIN choice c ON c.choice_question_id = q.question_id
						INNER JOIN answer a ON a.answer_choice_id = c.choice_id
						INNER JOIN user u ON u.user_id = a.answer_student_user_id
						WHERE question_questionnaire_id = '.$_MYSQLI->real_escape_string($_GET["questionnaire_id"]).'
						GROUP BY answer_student_user_id, choice_id';
						
			$answer_result = $_MYSQLI->query($query);	

			while($answer = $answer_result->fetch_object()) {
				
				if(isset($ajax->data["questions"][$answer->question_id])) {
					
					if(!isset($ajax->data["answers"][$answer->answer_student_user_id]))
						$ajax->data["answers"][$answer->answer_student_user_id] = array("identity" => $answer->user_identity, "score" => $question_keys);
					
					if(isset($ajax->data["questions"][$answer->question_id]["choices"][$answer->choice_id])) {

						
						if($ajax->data["questions"][$answer->question_id]["choices"][$answer->choice_id]["correct"]) {
							$ajax->data["answers"][$answer->answer_student_user_id]["score"][$answer->question_id]["correct"]++;	
						}

						else {
							$ajax->data["answers"][$answer->answer_student_user_id]["score"][$answer->question_id]["uncorrect"]++;
						}
						
					}
					
					
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