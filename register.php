<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/security.class.php");
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
$email_available = true;	
$error = "";
$repassword = true;

if($v->fieldsExists()) {

	$repassword = $_POST["user_password"] == $_POST["user_repassword"];
	
	$email_available = Auth::user_exists($_POST["user_email"]) == 0;

	if(!$email_available)
		$error = "E-Mail non disponible";
	else if(!$repassword)
		$error = "Les mots de passe ne correspondent pas";
	else
		$error = "Champ(s) invalide(s)";
	
	
	if($v->testAll() && $repassword && $email_available) {
				
		$statement = new SQLBuilder($_MYSQLI);
		
		$q = $statement->insertInto('user')
				->set($v->export($_MYSQLI, array("user_firstname", "user_lastname", "user_email", "user_schoolname"), array("user_photo_path" => "", "user_password" => Security::CryptPassword($_POST["user_password"]))))
				->build();
		
		$r = $_MYSQLI->query($q);
		
		Auth::login($_POST["user_email"], $_POST["user_password"]);
		
		header("Location: index.php");
		exit;
	}

	}

	
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/auth.css">
	</head>
	
	<body>
		<div class="login">
			<div class="login-top"><span>QCManager</span></div>
			<h1>Inscrivez-vous</h1>
			<div class="label"><?php echo $error; ?></div>
			<div class="login-bottom">
				<form method="post">
					<input autofocus name="user_firstname" <?php if($v->fail("user_firstname")) echo 'class="error"'; ?> placeholder="Nom" type="text">
					<input name="user_lastname" <?php if($v->fail("user_lastname")) echo 'class="error"'; ?> placeholder="Prénom" type="text">
					<input name="user_email" <?php if($v->fail("user_email") || !$email_available) echo 'class="error"'; ?> placeholder="Adresse e-mail" type="text">
					<input name="user_schoolname" <?php if($v->fail("user_schoolname")) echo 'class="error"'; ?> placeholder="Établissement" type="text">
					<input name="user_password" <?php if($v->fail("user_password")) echo 'class="error"'; ?> placeholder="Mot de passe" type="password">	
					<input name="user_repassword" <?php if($v->fail("user_repassword") || !$repassword) echo 'class="error"'; ?> placeholder="Veuillez retaper le mot de passe" type="password">	
					<input value="Inscription" type="submit">
				</form>
			</div>
			<div class="footer"><a href="./login.php">Vous avez déjà un compte ? Connectez-vous !</a></div>
		</div>
	</body>
</html>