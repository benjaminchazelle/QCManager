<?php

	require_once("include/database.inc.php");
	require_once("include/auth.class.php");

	$auth = new Auth(true);

	if(!isset($_POST["id_questionnaire"]) || !isset($_POST["new_values"]))
		exit();
	
	$id_questionnaire = $_POST["id_questionnaire"];
	
	$test = $_POST["new_values"];
	
	$result = explode("|", $test);
	
	foreach($result as $val)
	{
		if(!is_numeric($val))
			$brk = true;
	}
	
	if(!isset($brk))
	{
		$questionnaire_result = $_MYSQLI->query('SELECT questionnaire_id FROM QUESTIONNAIRE WHERE questionnaire_id='.$id_questionnaire.' AND questionnaire_user_id='.$auth->getUserId());
		if($questionnaire_result->num_rows != 0)
		{
			// First tmp value
			$counter = 1;
			foreach($result as $val)
			{
				$operation = $_MYSQLI->query('UPDATE QUESTION SET question_num='.($counter*1000).' WHERE question_num='.$counter.' AND question_questionnaire_id='.$id_questionnaire);
				$counter = $counter + 1;
			}
			
			// Then reals
			$counter = 1;
			foreach($result as $val)
			{
				$operation = $_MYSQLI->query('UPDATE QUESTION SET question_num='.$val.' WHERE question_num='.($counter*1000).' AND question_questionnaire_id='.$id_questionnaire);
				$counter = $counter + 1;
			}
		}
	}
	
?>