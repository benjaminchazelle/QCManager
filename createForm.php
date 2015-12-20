<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/database.inc.php");
require_once("include/sqlbuilder.class.php");

$auth = new Auth(true);

$_RULES = array(
				"questionnaire_title" => Validation::$f->notEmpty_String,
				"questionnaire_description" => Validation::$f->notEmpty_String,
				"questionnaire_start_date" => Validation::$f->datetime,
				"questionnaire_end_date" => Validation::$f->datetime
			);
			
$v = new Validation($_POST, array("questionnaire_title", "questionnaire_description", "questionnaire_start_date", "questionnaire_end_date"), $_RULES);

if($v->fieldsExists()) {
	
	$startdate_instance = DateTime::createFromFormat('Y/m/d H:i', $_POST["questionnaire_start_date"]);
	$enddate_instance = DateTime::createFromFormat('Y/m/d H:i', $_POST["questionnaire_end_date"]);

	$datetimes = false;
	
	if($startdate_instance instanceof DateTime && $enddate_instance instanceof DateTime) {
		$startdate = $startdate_instance->format('U');
		$enddate = $enddate_instance->format('U');		
		
		$datetimes = true;
		}


	if($v->testAll() && $datetimes) {
		
		$statement = new SQLBuilder($_MYSQLI);
		
		$q = $statement->insertInto('questionnaire')
				->set($v->export($_MYSQLI, array("questionnaire_title", "questionnaire_description"), array("questionnaire_user_id " => Auth::getUserId(), "questionnaire_start_date" => $startdate, "questionnaire_end_date" => $enddate)))
				->build();
		
		$r = $_MYSQLI->query($q);
		
		header("Location: form.php?id=".$_MYSQLI->insert_id);
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

<form action="" method="post">


	tt<input type="text" name="questionnaire_title" value="" />
	<br />
	dc<textarea name="questionnaire_description" ></textarea>
	<br />
	sd<input type="text" name="questionnaire_start_date" value="2014/03/15 05:06" />
	<br />
	ed<input type="text" name="questionnaire_end_date" value="2014/03/15 05:06" />

	
	<input type="submit" name="Envoyer" />

</form>