<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth();

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
			<h1>Erreur 404 ='(</h1>
			<h3 style="text-align:justify;">Il semblerait que cette page n'existe pas ou que vous n'avez pas le droit de la visualiser.</h3>
			<div class="footer">
			<?php
			if($auth->isLogged())
				echo '<a href="index.php">Aller sur la page d\'accueil</a>';
			else
				echo '<a href="login.php">Connectez-vous</a>';
			?>
			</div>
		</div>
	</body>
</html>