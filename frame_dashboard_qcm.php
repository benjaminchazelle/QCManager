<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

// $_SESSION["user_id"] = 5;

	
$auth = new Auth(true);

$mine_query = '	SELECT * 
				FROM questionnaire
				WHERE questionnaire_user_id = ' . Auth::getUserId();

$mine_result = $_MYSQLI->query($mine_query);

$mine_collection = array();

while($row = $mine_result->fetch_object()) {
	
	$mine_collection[] = array("id" => $row->questionnaire_id, "title" => $row->questionnaire_title, "finished" => (time() > $row->questionnaire_end_date));
	
}

$other_query = 'SELECT questionnaire.*
				FROM answer
				INNER JOIN choice ON choice_id = answer_choice_id
				INNER JOIN question ON question_id = choice_question_id
				INNER JOIN questionnaire ON questionnaire_id = question_questionnaire_id
				WHERE answer_student_user_id = '.Auth::getUserId().'
				GROUP BY questionnaire_id
';


$other_result = $_MYSQLI->query($other_query);

$other_collection = array();


while($row = $other_result->fetch_object()) {
	
	$other_collection[] = array("id" => $row->questionnaire_id, "title" => $row->questionnaire_title, "finished" => (time() > $row->questionnaire_end_date));
	
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
	
	<body style="background:#fff;	text-align:left;">

		<div id="qcm_framed" >
				<div class="padder">
				
					<div id="mine">
						
						<fieldset>
							<legend>En cours</legend>
						<ul>
							<?php
								$hay = false;
								foreach($mine_collection as $m) {
									$hay = true;
									if(!$m["finished"])
										echo '<li><a onclick="parent.ViewFormController('.$m["id"].');" href="#">'.$m["title"].'</a></li>';
								}
								
								if(!$hay)
									echo '<li>Aucun questionnaire à afficher</li>';								
							?>
						</ul>
						</fieldset>
						
						<fieldset>
							<legend>Terminés</legend>
						<ul>
							<?php
								$hay = false;
								foreach($mine_collection as $m) {
									$hay = true;
									if($m["finished"])
										echo '<li><a onclick="parent.ViewStatController('.$m["id"].', \''.addslashes($m["title"]).'\');" href="#">'.$m["title"].'</a></li>';
								}
								
								if(!$hay)
									echo '<li>Aucun questionnaire à afficher</li>';
							?>
						</ul>
						</fieldset>
					</div>
					<div id="other" style="display:none;">
						
						<fieldset>
							<legend>En cours</legend>
						<ul>
							<?php
								$hay = false;
								foreach($other_collection as $m) {
									$hay = true;
									if(!$m["finished"])
										echo '<li><a onclick="parent.ViewFormController('.$m["id"].');" href="#">'.$m["title"].'</a></li>';
								}
								
								if(!$hay)
									echo '<li>Aucun questionnaire à afficher</li>';								
							?>
						</ul>
						</fieldset>
						
						<fieldset>
							<legend>Terminés</legend>
						<ul>
							<?php
								$hay = false;
								foreach($other_collection as $m) {
									$hay = true;
									if($m["finished"])
										echo '<li><a onclick="parent.ViewFormController('.$m["id"].');" href="#">'.$m["title"].'</a></li>';
								}
								
								if(!$hay)
									echo '<li>Aucun questionnaire à afficher</li>';								
							?>
						</ul>
						</fieldset>
					</div>

				
				</div>
			</div>
		<script>
		parent.InitQuestionsFrameController(window);
		</script>
	</body>
	
</html>