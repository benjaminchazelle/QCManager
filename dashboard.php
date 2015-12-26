<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth(true);

/*
$error = true;

$data = array();

if(Validation::Query($_GET, array("id")) && is_numeric($_GET["id"])) {

	$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["id"]).'" LIMIT 1');
		
	if($questionnaire_result->num_rows == 1)	{
		
		$error = false;
		
		$questionnaire = $questionnaire_result->fetch_object();
		
		$data["questionnaire"] = $questionnaire;
		
		$own = $questionnaire->questionnaire_user_id == Auth::getUserId();

		$data["questionnaire"]->own = $own;
		
	}
	
	
}

if($error) {
	header("Location: 404.php");
	exit;
}
	*/		
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
							<li><a href=""><img src="media/user/mobi.png" />Mobi</a></li>
							<li><a href="">Tableau de bord</a></li>
							<li><a href="">Créer un QCM</a></li>
							<li><a href="">Déconnexion</a></li>
						</ul>


					</div>
				</div>
			</div>
		</div>

		<div id="questionscolumn">
			<div id="searchBar" style="background:#fff;">
				<div id="searchfield">
						<div id="minebtn" class="selected"><span>Mes QCMs</span></div>
						<div id="otherbtn"><span>QCMs répondu</span></div>
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