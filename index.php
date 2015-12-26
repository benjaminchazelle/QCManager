<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth(true);
$user = Auth::getUser();
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>

		<script src="js/dashboardControllers.js"></script>

	</head>
	
	<body>
	
	<div id="maincontainer" class="responsive">
	
		<div id="menucolumn">
			<div id="menu">
				<div id="logo"></div>
				<div id="menu_items">
					<div class="padder">
						<ul>
							<li><a href="./profil.php"><div id="profilimg"><?php echo $user->user_lastname[0]; ?></div><?php echo $user->user_firstname[0] . ". " . $user->user_lastname; ?></a></li>
							<li><a href="/">Tableau de bord</a></li>
							<li><a href="/createForm.php">Créer un QCM</a></li>
							<li><a href="/logout.php">Déconnexion</a></li>
						</ul>

					</div>
				</div>
			</div>
		</div>

		<div id="questionscolumn">
			<div id="searchBar" style="background:#fff;">
				<div id="searchfield">
						<div id="minebtn" class="selected"><span>Mes QCMs</span></div>
						<div id="otherbtn"><span>QCMs répondus</span></div>
				</div>
			</div>
			<div id="questions">
				<iframe id="questionsFrame" name="loaded" src="frame_dashboard_qcm.php"></iframe>
			</div>
		</div>
			
		<div id="answercolumn" style="visibility: hidden;" class="content">
			<div id="title">
				<span></span>
			</div>
			<div id="answer" >
				<iframe id="answerFrame" src=""></iframe>
			</div>
		</div>
	
	</div>
	
	<script src="js/responsive.js"></script>

	</body>	
	
</html>