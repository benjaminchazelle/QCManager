<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/sqlbuilder.class.php");


$auth = new Auth();

$error = true;
$new = false;
$own = false;

$data = array();

	
if(Validation::Query($_GET, array("id", "qid")) && $_GET["id"] == -1) {

	$q = '	SELECT questionnaire.*, COUNT(question_id) AS questionnaire_total_questions
				FROM questionnaire 
				INNER JOIN question ON questionnaire_id = question_questionnaire_id
				WHERE questionnaire_id = '.$_MYSQLI->real_escape_string($_GET["qid"]).'
				LIMIT 1';
	
	$questionnaire_result = $_MYSQLI->query($q);

	if($questionnaire_result->num_rows > 0)	{
		
		$questionnaire = $questionnaire_result->fetch_object();
		
		if($questionnaire->questionnaire_user_id == Auth::getUserId()) {
			
			$error = false;
			$new = true;
			$own = true;
			
			$data["question"] = new stdClass();
			$data["question"]->question_content = "";
			$data["question"]->question_hint = "";
			$data["question"]->question_type = "checkbox";
			$data["question"]->question_weight = "1";
			$data["question"]->question_weight = "1";

			$data["choices"] = array();
			$data["choices"][0] = new stdClass();
			$data["choices"][0]->choice_status = 0;
			$data["choices"][0]->question_type = "checkbox";
			$data["choices"][0]->choice_content = "";
			$data["choices"][0]->choice_id = -1;

		}
		
		
	}
	
}
else if($auth->isLogged() && Validation::Query($_GET, array("id")) && is_numeric($_GET["id"])) {
	
	$question_result = $_MYSQLI->query('SELECT * FROM question INNER JOIN questionnaire ON questionnaire_id = question_questionnaire_id WHERE question_id = '.$_GET["id"]);
	
	if($question_result->num_rows > 0) {
		
		$error = false;
		
		
		
		$data["question"] = $question_result->fetch_object();
		$own = $data["question"]->questionnaire_user_id == Auth::getUserId();
		
		$choice_query = '	SELECT *, SUM(case when answer_student_user_id = '.Auth::getUserId().' then 1 else 0 end) as checked
							FROM question q
							JOIN choice c ON c.choice_question_id = q.question_id
							LEFT JOIN answer a ON a.answer_choice_id = c.choice_id
							WHERE question_id = '.$_GET["id"].'
							GROUP BY choice_id
							ORDER BY question_num ASC, question_id ASC';	
						
		$choice_result = $_MYSQLI->query($choice_query);
		
		$choice_ids = array();
		$data["choices"] = array();
		
		while($row = $choice_result->fetch_object()) {
			$data["choices"][$row->choice_id] = $row;
			
			if($row->checked > 0)
				$choice_ids[] = $row->choice_id;
		}
	}
	
	

	
}

if($error) {
	header("Location: 404.php");
	exit;
}

$_RULES = array (
				"question_content" => Validation::$f->notEmpty_String,
				"question_type" => function ($d) { return $d == "checkbox" || $d == "radio"; },
				"question_hint" => Validation::$f->String,
				"question_weight" => function ($d) { return is_numeric($d) && ($d%1 == 0) && $d >= 1 && $d <= 5; }
			);

$v = new Validation($_POST, array("question_content", "question_type", "question_hint", "question_weight"), $_RULES);

if($own && Validation::Query($_POST, array("indexes", "correct_indexes", "labels")) && $v->fieldsExists()) {

	if($v->testAll()) {
		
	$statement = new SQLBuilder($_MYSQLI);
		
	if($new) {

	$q = $statement->insertInto('question')
			->set($v->export(null, array("question_content", "question_type", "question_hint", "question_weight"), array("question_questionnaire_id" => $_GET["qid"], "question_num" => ($questionnaire->questionnaire_total_questions+1))))
			->build();
			
	$_MYSQLI->query($q);
	
	$_GET["id"] = $_MYSQLI->insert_id;
		
	}
	else {
		
	$q = $statement->update('question')
			->set($v->export(null, array("question_content", "question_type", "question_hint", "question_weight")))
			->where("question_id", "=", $_GET["id"])
			->build();
			
	$_MYSQLI->query($q);
	}
	

		
	
	
	$insertions = array();
	
	$correct = array();
	
	$one_correct = false;
	
	foreach($_POST["indexes"] as $k => $val) {
		$correct[$k] = in_array($val, $_POST["correct_indexes"]) ? 1 : 0;
	}
	
	foreach($_POST["labels"] as $k => $lbl) {
		if($lbl != "") {
			$insertions[] = '(NULL, '.$_GET["id"].', \''.$_MYSQLI->real_escape_string($lbl).'\', \''.$correct[$k].'\')';			
			if($correct[$k])
				$one_correct = true;
		}
		
	}
	
	if(count($insertions) > 0 && $one_correct) {

	$_MYSQLI->query('DELETE FROM choice WHERE choice_question_id = ' . $_GET["id"]);
	$_MYSQLI->query('INSERT INTO choice (choice_id, choice_question_id, choice_content, choice_status) VALUES ' . implode(", ", $insertions)); 
	
	}
	

	
	// echo $q;
	
	header("Location: frame_form_answer.php?refresh=true&id=".$_GET["id"]);
	exit;
	}

	
}

//$v = new Validation($_POST, array("user_firstname", "user_lastname", "user_email", "user_schoolname", "user_password", "user_repassword"), $_RULES);

else if(!$own && Validation::Query($_POST, array("post")) && $data["question"]->questionnaire_end_date > time()) {

	foreach($choice_ids as $cid) {
		$data["choices"][$cid]->checked = 0;
	}
	
	$delquery = '	DELETE FROM answer 
					WHERE answer_student_user_id = ' . Auth::getUserId() . ' AND answer_choice_id IN ('.implode(', ', $choice_ids).')';
	
	$_MYSQLI->query($delquery);
	
	if(isset($_POST["choices"])) {
	
		$insertion = array();
		
		foreach($_POST["choices"] as $cid) {
			$insertion[] = '(NULL, '.Auth::getUserId().', '.$cid.')';
			$data["choices"][$cid]->checked = 1;
		}
		
		$addquery = 'INSERT INTO answer (answer_id, answer_student_user_id, answer_choice_id) VALUES ' . join(', ', $insertion);

		$_MYSQLI->query($addquery);
	}


	 

}



		
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
	</head>
	
	<body style="background: #fff;">
			<div id="answer_framed" >
				<div class="padder">
					<img id="loader" src="media/static/loader.gif" alt="" style="display:none;margin-top:10px;" />
					<?php
					if($own) {
						



						?>
						
						<div id="answerForm">
						<form action="" method="post" id="ownquestionform">
						<fieldset>
							<legend>Question</legend>
							<input id="question_content" type="text" name="question_content" placeholder="Entrer une question" value="<?php echo ($data["question"]->question_content); ?>" /><br/>
							<div id="type">
								<label><input id="type_multi" type="radio" <?php echo ($data["question"]->question_type == "checkbox") ? "checked" : ""; ?>  name="question_type" value="checkbox" />Réponse multiple</label>
								<label><input id="type_one" type="radio"  <?php echo ($data["question"]->question_type == "radio") ? "checked" : ""; ?> name="question_type" value="radio" />Réponse unique</label>
							</div>
							<div id="weight">
								<label><input <?php echo ($data["question"]->question_weight == "1") ? "checked" : ""; ?> type="radio" name="question_weight" value="1" />1 point</label>
								<label><input <?php echo ($data["question"]->question_weight == "2") ? "checked" : ""; ?> type="radio" name="question_weight" value="2" />2 points</label>
								<label><input <?php echo ($data["question"]->question_weight == "3") ? "checked" : ""; ?> type="radio" name="question_weight" value="3" />3 points</label>
								<label><input <?php echo ($data["question"]->question_weight == "4") ? "checked" : ""; ?> type="radio" name="question_weight" value="4" />4 points</label>
								<label><input <?php echo ($data["question"]->question_weight == "5") ? "checked" : ""; ?> type="radio" name="question_weight" value="5" />5 points</label>
							</div>
						</fieldset>
						
						<fieldset id="choices">
							<legend>Choix de réponses</legend>
							
							<table>
							<?php
							$i=0;
							foreach($data["choices"] as $choice) {
								
								echo "<tr>";
									$checked = ($choice->choice_status == 1) ? "checked" : "";
									echo '<td><input '.$checked.' class="answerbox" type="'.$data["question"]->question_type.'" name="correct_indexes[]" value="'.$i.'"></td>';
									echo '<td><input class="choice" type="text" name="labels[]" value="'.$choice->choice_content.'" />';
									echo '<input type="hidden" name="keys[]" value="'.$choice->choice_id.'" /></td>';
									echo '<input type="hidden" name="indexes[]" value="'.$i++.'" /></td>';
									echo '<td><a class="del" href="#">Supprimer</a></td>';
									
									
								echo "</tr>";
								
							}
							
							?>
							</table>
							<a id="new" href="#">Ajouter une réponse</a>
							<hr/>
							<i id="check_indication">Cocher la ou les bonnes réponses</i>
						</fieldset>
						
						<fieldset>
							<legend>Indice</legend>
							<input name="question_hint" type="text" placeholder="Entrer un indice optionnel à la question" value="<?php echo ($data["question"]->question_hint); ?>" />
						</fieldset>
						</form>
						</div>
						
						<script>
						index = <?php echo $i; ?>;
						type = '<?php echo $data["question"]->question_type; ?>';
						$("#type_multi").click(function () { $(".answerbox").attr("type", "checkbox");type="checkbox"; $("#choices i").text("Cocher la ou les bonnes réponses"); });
						$("#type_one").click(function () { $(".answerbox").attr("type", "radio");type="radio"; $("#choices i").text("Cocher la bonne réponse"); });
						
						delfunc =  function() {
							
							$(this).parent().parent().remove();
							
						};
						
						$("#new").click(function () {
							
							if($(".choice").last().val() != "") {
								
								// var el = $("#choices table").append('<tr><td><input type="'+type+'" value="-1" name="corrects[]" class="answerbox"></td><td><input type="text" value="" name="labels[]" class="choice"></td><td><a class="del" href="#">Supprimer</a></td></tr>');
								
								var el = $("#choices table").append('<tr><td><input type="'+type+'" value="'+index+'" name="correct_indexes[]" class="answerbox"></td><td><input type="text" value="" name="labels[]" class="choice"><input type="hidden" value="-1" name="keys[]"></td><input type="hidden" value="'+index+'" name="indexes[]"><td><a href="#" class="del">Supprimer</a></td></tr>');
								
								index++;
								
								$(".del").click(".del", delfunc);	//	<input class="answerbox" type="checkbox" name="corrects_keys[]" value="'.$i++.'"><input  type="hidden" name="corrects[]" value="'.$choice->choice_id.'">
							}
							
						});
						
						$(".del").click(".del", delfunc);
						
						function validate() {
							
							back = true;


							if($("#question_content").val().length == 0) {
								back = false;
								$("#question_content").addClass("error");
								$("#question_content").change(function () {$("#question_content").removeClass("error");});
							}
						
						
							
							label_notempty_checked = false;
							
							$(".choice").each(function() {
								if($(this).val().length > 0) {
									if(this.parentElement.previousElementSibling.firstElementChild.checked)
										label_notempty_checked = true;
								}
							
							});
							
							if(!label_notempty_checked) {
								$("#check_indication").addClass("errortxt");
								$(".answerbox").change(function () {
									if(this.checked)
										$("#check_indication").removeClass("errortxt");									
								});
								
							}

							
							// alert(label_notempty_counter);
							back = back && label_notempty_checked;
							
							return back;
							
						}
						</script>
						
						<input onclick="if(!validate())return false;document.getElementById('loader').style.display='';" type="submit" form="ownquestionform" value="Sauvegarder" class="btn" />

						
						<?php
					}
					else {
						?>
					
					<div id="questiontitle"><?php echo ($data["question"]->question_content); ?></div>
					<form action="" method="post" id="questionchoices">
						<input type="hidden" name="post" value="post" />
						<?php
						
						foreach($data["choices"] as $choice) {
							
							if($choice->question_type == "checkbox") {
								
								// if($choice->answer_id != null)
									// echo "*";
								
								echo '<label><input '. (($choice->checked == 0)?"":"checked") .' class="answerbox" type="checkbox" name="choices[]" value="'.$choice->choice_id.'">'.$choice->choice_content.'</label><br>';
								
							}
							else {
								
								echo '<label><input '. (($choice->checked == 0)?"":"checked") .' class="answerbox" type="radio" name="choices[]" value="'.$choice->choice_id.'">'.$choice->choice_content.'</label><br>';
								
							}
							
						}
						
						?>

					</form>
					
					<?php
					
					if(strlen($data["question"]->question_hint) > 0) {
						
						echo '<div id="indice">► Indice</div>';
						echo '<div id="indice_content">'.$data["question"]->question_hint.'</div>';
						
					}
					

					if($data["question"]->questionnaire_end_date > time())
						echo '<input onclick="document.getElementById(\'loader\').style.display=\'\';" type="submit" form="questionchoices" value="Sauvegarder" class="btn" />';
					else
						echo 'Ce QCM est désormais terminé';
					
					} ?>
				</div>
			</div>
			
		<script>
		
		parent.InitAnswerFrameController(window);
		
		hint = false;

		$('#indice').click( function() {

			if(!hint) {
				$("#indice_content").animate({height: 'toggle'}, 300).animate({opacity: '1'}, 300);
				$('#indice').html("▼ Indice");				
			}
			else {
				$("#indice_content").animate({opacity: '0'}, 300).animate({height: 'toggle'}, 300);
				$('#indice').html("► Indice");				
			}

			hint = !hint;

		});
		
		<?php if(isset($_GET["refresh"])) echo 'parent.RefreshQuestionsFrameController();'; ?>
		</script>
	</body>
	
	
</html>