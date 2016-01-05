<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth();

if($auth->isLogged()) {
	header("Location: index.php");
	exit;
	}

$error = false;

if(Validation::Query($_POST, array("email", "password"))) {

	if($auth->login($_POST['email'], $_POST['password'])) {
		header("Location: index.php");
		exit;
		}
	else {
		$error = true;
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
			<h1>Connectez-vous</h1>
			<?php if($error) echo '<div class="label">La connexion a échouée</div>'; ?>
			<div class="login-bottom">
				<form method="post">
					<input placeholder="Adresse e-mail" type="text" name="email" />					
					<input class="password" autofocus placeholder="Mot de passe" type="password" name="password" />						
					<input value="Connexion" type="submit">
				</form>
			</div>
			<div class="footer"><a href="./register.php">Vous n'avez pas de compte ? Inscrivez-vous !</a></div>
		</div>
	</body>
</html>