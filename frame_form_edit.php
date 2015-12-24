<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/sqlbuilder.class.php");


		
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

$_RULES = array (
				"questionnaire_title" => Validation::$f->notEmpty_String,
				"questionnaire_description" => Validation::$f->notEmpty_String,
				"questionnaire_start_date" => Validation::$f->datetime,
				"questionnaire_end_date" => Validation::$f->datetime
			);

$v = new Validation($_POST, array("questionnaire_title", "questionnaire_description", "questionnaire_start_date", "questionnaire_end_date"), $_RULES);

if($v->fieldsExists()) {
	
	$startdate_instance = DateTime::createFromFormat('d/m/Y H:i', $_POST["questionnaire_start_date"]);
	$enddate_instance = DateTime::createFromFormat('d/m/Y H:i', $_POST["questionnaire_end_date"]);

	$datetimes = false;
	
	if($startdate_instance instanceof DateTime && $enddate_instance instanceof DateTime) {
		$startdate = $startdate_instance->format('U');
		$enddate = $enddate_instance->format('U');		
		
		$datetimes = $enddate > $startdate;
		}

	if($v->testAll() && $datetimes) {
		
	$statement = new SQLBuilder($_MYSQLI);
	
	$q = $statement->update('questionnaire')
			->set($v->export(null, array("questionnaire_title", "questionnaire_description"), array("questionnaire_start_date" => $startdate, "questionnaire_end_date" => $enddate)))
			->where("questionnaire_id", "=", $_GET["id"])
			->build();
			
	$_MYSQLI->query($q);

	header("Location: frame_form_edit.php?refresh=true&id=".$_GET["id"]);
	exit;
		
	}
	
	if($v->fail("questionnaire_title"))
		echo "questionnaire_title fail";

	if($v->fail("questionnaire_description"))
		echo "questionnaire_description fail";
	
	if(!$datetimes)
		echo "datetimes fail";

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

			
			<?php if(isset($_GET["refresh"])) echo 'parent.UpdateFormController('.json_encode($data["questionnaire"]).')'; ?>

		</script>
	</body>
	
</html>