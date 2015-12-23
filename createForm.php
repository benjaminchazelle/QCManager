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
	
	$startdate_instance = DateTime::createFromFormat('d/m/Y H:i', $_POST["questionnaire_start_date"]);
	$enddate_instance = DateTime::createFromFormat('d/m/Y H:i', $_POST["questionnaire_end_date"]);

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
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="js/datetimepicker-master/jquery.datetimepicker.css"/ >
		<script src="js/jquery.min.js"></script>
		<script src="js/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
		
	</head>
	
	
	
	<body>
	
	<div id="maincontainer" class="aresponsive">
	
		
		<div id="menucolumn">
			<div id="menu">
			<div id="logo"></div>
			<div id="menu_items">
			<div class="padder">
				<ul>
					<li><a href=""><img src="media/user/mobi.png" />Mobi</a></li>
					<li><a href="">Créer</a></li>
					<li><a href="">Mes QCM</a></li>
					<li><a href="">Mes Réponses</a></li>
					<li><a href="">Déconnexion</a></li>
				</ul>
				<hr/>
				<div id="author">Par Mobi</div>
				<div id="description">QCM sur le cours vu depuis le début de l'année. Utile pour s'entrainer avant un DS ou un concours.</div>
				<!--<div id="progressinfo">Progression du QCM : 17%</div>
				<div id="progressbar"><img src="img/progress.png"></img></div>
				<div id="time">Temps restant : 6j 5h 42m </div>-->
			</div>
			</div>
			</div>
		</div>
		


		<div id="createformcolumn">
		
			<div id="banner">
				<div id="title">Nouveau QCM</div>
			</div>
		
		
		
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div id="cf_text">					
						<form action="" method="post">
							<TABLE id="cf_table">
								<TR>
									<TD colspan=2>
										<div id="cf_label">Nom du QCM</div>
										<input name="questionnaire_title" id="cf_nom" type="text">
									</TD>
								</TR>

								<TR>
									<TD colspan=2>
										<div id="cf_label">Description du QCM</div>
										<textarea name="questionnaire_description" id="cf_description"></textarea>
									</TD>
								</TR>

								<TR>
									<TD width=33%>
										<div id="cf_label">Date de début</div>
										<input name="questionnaire_start_date" id="datetimepicker1" type="text" value="<?php echo date('d/m/Y H:i', time()); ?>" >
									</TD>
									
									<TD width=33%>
										<div id="cf_label">Date de fin</div>
										<input name="questionnaire_end_date" id="datetimepicker2" type="text" value="<?php echo date('d/m/Y H:i', time()+60*60); ?>" >
									</TD>
								</TR>
								
								<TR>
									<TD colspan=2>
										<input id="button" value="Inscription" type="submit">
									</TD>
								</TR>
							</TABLE>
						</form>
					</div>
				</div>
			</div>					
		</div>		
	</div>
	
	</body>
	

	<script src="js/responsive.js"></script>
	<script>
		jQuery('#datetimepicker1').datetimepicker({
			format:'d/m/Y H:i',
			inline:true,
			lang:'fr'
		});
		jQuery('#datetimepicker2').datetimepicker({
			format:'d/m/Y H:i',
			inline:true,
			lang:'fr'
		});
	</script>
	
</html>