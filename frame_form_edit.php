<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");


		
$auth = new Auth();

$error = true;

$data = array();

if(Validation::Query($_GET, array("id")) && is_numeric($_GET["id"])) {

	$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["id"]).'" LIMIT 1');
		
	if($questionnaire_result->num_rows > 0)	{
		
		$questionnaire = $questionnaire_result->fetch_object();
		
		if($questionnaire->questionnaire_user_id == Auth::getUserId()) {
			
			$error = false;
			$data["questionnaire"] = $questionnaire;
		}
		
		
	}
}


if($error) {
	header("Location: 404.php");
	exit;
}
			
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="js/datetimepicker-master/jquery.datetimepicker.css"/ >
		<script src="js/jquery.min.js"></script>
		<script src="js/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
		
	</head>
	
	<body style="background:#fff;">
			<div id="answer_framed" >
				<div class="padder">

						<img id="loader" src="media/static/loader.gif" alt="" style="display:none;margin-top:10px;" />
						<div id="answerForm">
						<form action="" method="post" id="ownquestionform">
						<fieldset>
							<legend>Titre</legend>
							<input type="text" name="questionnaire_title" placeholder="Entrer le titre du questionnaire" value="<?php echo ($data["questionnaire"]->questionnaire_title); ?>" /><br/>
						</fieldset>
						
						<fieldset>
							<legend>Description</legend>
							<textarea placeholder="Entrer une description" name="questionnaire_description"><?php echo ($data["questionnaire"]->questionnaire_description); ?></textarea>
						</fieldset>
						<table class="doublefieldset">
						<tr><td>
						<fieldset class="w50">
							<legend>Date de début</legend>
							<input  placeholder="JJ/MM/AA hh:mm" id="startdate" type="text" name="questionnaire_start_date" placeholder="Entrer le titre du questionnaire" value="<?php echo date("d/m/Y H:i", $data["questionnaire"]->questionnaire_start_date); ?>" /><br/>
						</fieldset>
						</td><td>
						<fieldset class="w50">
							<legend>Date de fin</legend>
							<input  placeholder="JJ/MM/AA hh:mm" id="enddate" type="text" name="questionnaire_end_date" placeholder="Entrer le titre du questionnaire" value="<?php echo date("d/m/Y H:i", $data["questionnaire"]->questionnaire_end_date); ?>" /><br/>
						</fieldset>
						</td>
						</tr>
						</table>
	
						</form>
						</div>
						
						<script>
						jQuery('#startdate').datetimepicker({
						  format:'d/m/Y H:i'
						});
						jQuery('#enddate').datetimepicker({
						  format:'d/m/Y H:i'
						});
						
						function validate() {
							
							back = true;
							
							$("#answerForm input, #answerForm textarea").each(function() {
								// alert($(this).val());
								if($(this).val().length == 0) {
									back = false;
									$(this).addClass("error");
									$(this).change(function () {$(this).removeClass("error");});
								}
							
							});
							
							return back;
							
						}
						</script>
						
						<input onclick="if(!validate())return false;document.getElementById('loader').style.display='';" type="submit" form="ownquestionform" value="Sauvegarder" class="btn" />

						
				</div>
		
		<script>
			/*parent.InitQuestionsFrameController(window);
			
			if(window.location.search.indexOf("noRefresh") == -1)
				parent.QuestionSelectQuestionController(document.getElementById(<?php echo $first; ?>));*/
		</script>
	</body>
	
</html>