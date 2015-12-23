<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
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

		<div id="questionscolumn">
			<div id="searchBar">
				<div id="searchfield">
					<input type="text"  placeholder="Chercher une question..." />
				</div>
			</div>
			<div id="questions">
				<iframe  src="frame_form_questions.php"></iframe>
			</div>
			
		</div>
			
		<div id="answercolumn" class="content">
			<div id="title">Titre du QCM - Par Mobi<div id="questionnumber">5/12&nbsp;</div></div>
			<div id="answer" >
				<iframe  src="frame_form_answer.php"></iframe>
				<!--<div class="padder">
					<div id="questiontitle">On considère les deux unités de pression : le Bar et le Pascal. Laquelle des propositions suivantes est correcte ?</div>
					<form action="" method="POST" id= "questionchoices" >
						<input type="radio" name="group1" value="Choice1"> 1 bar = 105 N.m².s² <br>
						<input type="radio" name="group1" value="Choice2" checked> 1 bar = 145 N.m².s² <br>
						<input type="radio" name="group1" value="Choice3"> 1 bar = 108 N.m².s² <br>
					</form>
					<div id="indice">► Indice</div>
					<div id="indice_content" style="">
					Coucou
					</div>
					<input type="submit" form="QuestionChoices" value="Sauvegarder" id="btn"></button>
				</div>-->
			</div>
		</div>
		<!--<div id="banner">
			<div id="logo"><span>QCManager</span></div>
			<div id="searchBar">
				<div id="searchfield">
					<input type="text"  placeholder="Chercher une question..." />
				</div>
			</div>
			<div id="title">Titre du QCM - Par Mobi<div id="questionnumber">5/12&nbsp;</div></div>
		</div>-->
			
		</div>

	
	</div>
	
	</body>
	

	<script src="js/responsive.js"></script>
	<script src="js/form.js"></script>
	
</html>