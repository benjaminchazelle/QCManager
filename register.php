<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/database.inc.php");
require_once("include/sqlbuilder.class.php");

$auth = new Auth();

if($auth->isLogged()) {
	header("Location: index.php");
	exit;
	}



$_RULES = array(
				"user_firstname" => Validation::$f->notEmpty_String,
				"user_lastname" => Validation::$f->notEmpty_String,
				"user_email" => Validation::$f->Email,
				"user_schoolname" => Validation::$f->notEmpty_String,
				"user_password" => Validation::$f->notEmpty_String,
				"user_repassword" => Validation::$f->notEmpty_String
			);
			
$v = new Validation($_POST, array("user_firstname", "user_lastname", "user_email", "user_schoolname", "user_password", "user_repassword"), $_RULES);
			
if($v->fieldsExists()) {

	$repassword = $_POST["user_password"] == $_POST["user_repassword"];
	
	$email_available = Auth::user_exists($_POST["user_email"]) == 0;

	
	
	if($v->testAll() && $repassword && $email_available) {
		
		// $users_matchs_result = $_MYSQLI->query('SELECT * FROM user WHERE user_email = "'.$_MYSQLI->real_escape_string($_POST["user_email"]).'"');
		
		$statement = new SQLBuilder($_MYSQLI);
		
		$q = $statement->insertInto('user')
				->set($v->export(array("user_firstname", "user_lastname", "user_email", "user_schoolname", "user_password"), array("user_photo_path" => "")))
				->build();
		
		$r = $_MYSQLI->query($q);
		var_dump($_MYSQLI);
		
		print_r($r);
	}
	
	if($v->fail("user_firstname"))
		echo "user_firstname fail";

	if($v->fail("user_lastname"))
		echo "user_lastname fail";
	
	if($v->fail("user_schoolname"))
		echo "user_schoolname fail";

	if($v->fail("user_email"))
		echo "user_email fail";

	if($v->fail("user_password"))
		echo "user_password fail";
	
	if($v->fail("user_repassword") && $repassword)
		echo "user_repassword fail";
	
	if(!$email_available)
		echo "email unavailable";

	}

	
?>
<form action="" method="post">


	fn<input type="text" name="user_firstname" />
	<br />
	ln<input type="text" name="user_lastname" />
	<br />
	em<input type="text" name="user_email" />
	<br />
	sn<input type="text" name="user_schoolname" />
	<br />
	pw<input type="password" name="user_password" />
	<br />
	rpw<input type="password" name="user_repassword" />
	<br />
	
	<input type="submit" name="Envoyer" />

</form>

