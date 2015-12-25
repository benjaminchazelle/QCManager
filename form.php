<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth();

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
			
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
		<script>
		QUESTIONNAIRE_ID = <?php echo $_GET["id"]; ?>;
		</script>
		<script src="js/formControllers.js"></script>

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

						<hr />
						<div id="author">Par Mobi</div>

						<div id="description"><?php echo $questionnaire->questionnaire_description; ?></div>
						<!--<div id="progressinfo">Progression du QCM : 17%</div>
						<div id="progressbar"><img src="img/progress.png"></img></div>
						<div id="time">Temps restant : 6j 5h 42m </div>-->
						<?php
						if($own) { ?>
						<hr/>
						<ul>
							<li><a onclick="EditFormController()" href="#">Éditer les infos</a></li>
							<li><a onclick="AddQuestionController()" href="#">Nouvelle question</a></li>
							<li><a onclick="ViewStatController()"href="#">Statistiques</a></li>
						</ul>	
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<div id="questionscolumn">
			<div id="searchBar">
				<div id="searchfield">
					<input id="searchterm" type="text"  placeholder="Chercher une question..." />
				</div>
			</div>
			<div id="questions">
				<iframe id="questionsFrame" name="loaded" src="frame_form_questions.php?id=<?php echo $questionnaire->questionnaire_id; ?>"></iframe>
			</div>
		</div>
			
		<div id="answercolumn" class="content">
			<div id="title">
				<span><?php echo $questionnaire->questionnaire_title; ?></span>
				<div id="questionnumber">5/12&nbsp;</div>
			</div>
			<div id="answer" >
				<iframe id="answerFrame" src=""></iframe>
			</div>
		</div>
	
	</div>
	
	<script src="js/responsive.js"></script>

	</body>	
	
</html>