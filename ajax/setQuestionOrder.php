<?php

require_once("../include/database.inc.php");
require_once("../include/auth.class.php");
require_once("../include/ajax.class.php");
require_once("../include/validation.class.php");

$auth = new Auth(true);

$ajax = new Ajax();

if($auth->isLogged()) {
	
	if(Validation::Query($_POST, array("questionnaire_id", "questions_order"))) {

		$questionnaire_id = $_POST["questionnaire_id"];

		$raw_orders = $_POST["questions_order"];

		$orders = explode("|", $raw_orders);

		$set = array();

		$break = false;

		foreach($orders as $val) {
			
			$set[(int)$val] = 1;
			
			if(!is_numeric($val))
				$break = true;
		}

		if(!$break && is_numeric($questionnaire_id)) {
			
			$questionnaire_result = $_MYSQLI->query('SELECT questionnaire_id, questionnaire_user_id FROM questionnaire WHERE questionnaire_id='.$questionnaire_id.' AND questionnaire_user_id='.$auth->getUserId());

			if($questionnaire_result->num_rows != 0) {		

				$questions_result = $_MYSQLI->query('SELECT question_id FROM question WHERE question_questionnaire_id='.$questionnaire_id);
				
				$questions_list = array();
				
				if($questions_result->num_rows == count($orders)) {
					
					$break = false;
					
					while($question = $questions_result->fetch_object()) {
						
						if(!isset($set[(int)$question->question_id])) {

							$break = true;
							break;
						}

					}
					
					if(!$break) {
						
						$i = 1;
						foreach($set as $k => $v) {
							
							// echo 'UPDATE question SET question_num='.$i.' WHERE question_id='.$k.' AND question_questionnaire_id='.$questionnaire_id;
							
							$_MYSQLI->query('UPDATE question SET question_num='.$i.' WHERE question_id='.$k.' AND question_questionnaire_id='.$questionnaire_id);
							$i++;
						}
						
						$ajax->data = $orders;
						
					}
					else {
						
						$ajax->setError("query_error: order argument fail, an id does not exist in database");
						
					}

				}
				else {
					
					$ajax->setError("query_error: order argument fail, not the same entry number that in database");
					
				}

			}
			else {
				
				$ajax->setError("query_error: questionnaire does not exist or you are not the owner");
				
			}
			
		}
		else {
			
			$ajax->setError("query_error: bad arguments");
			
		}
		
	}
	else {
		
		$ajax->setError("query_error: missing POST arguments");
		
	}
	
}
else {
	
	$ajax->setError("not_logged");
	
}

$ajax->out();

?>