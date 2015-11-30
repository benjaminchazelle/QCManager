<?php

require("include/auth.class.php");
require("include/request.class.php");

$auth = new Auth();

if($auth->isLogged()) {
	header("Location: index.php");
	exit;
	}


if(Request::PostFields(array("email", "password"))) {
	
	if($auth->login($_POST['email'], $_POST['password'])) {
		header("Location: index.php");
		exit;
		}
}

?>

<form action="login.php" method="post">

	<div> <span>E-Mail</span> <input type="text" autofocus placeholder="" name="email" value="" /></div>
	<div> <span>Mot de passe</span> <input type="password" placeholder="" name="password" value="" /></div>
	
	<div> <span>&nbsp;</span> <input class="redbutton" type="submit" value="Connexion" /></div>
</form>