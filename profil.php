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

		<script>
			function UpdateMenuController(data) {
				
				document.getElementById("profilitem").innerHTML = '<div id="profilimg">'+data.user_lastname[0]+'</div>'+data.user_firstname[0]+'. '+data.user_lastname;
				
			}
		</script>

	</head>
	
	<body>
	
	<div id="maincontainer" class="responsive">
	




		<div id="menucolumn">
			<div id="menu">
				<div id="logo"></div>
				<div id="menu_items">
					<div class="padder">
						<ul>
							<li><a id="profilitem" href="./profil.php"><div id="profilimg"><?php echo $user->user_lastname[0]; ?></div><?php echo $user->user_firstname[0] . ". " . $user->user_lastname; ?></a></li>
							<li><a href="./">Tableau de bord</a></li>
							<li><a href="./createForm.php">Créer un QCM</a></li>
							<li><a href="./logout.php">Déconnexion</a></li>
						</ul>


					</div>
				</div>
			</div>
		</div>


			
		<div id="onecolumn" class="content">
			<div id="title">
				<span>Éditer votre profil</span>

			</div>
			<div id="one" >
				<iframe id="oneFrame" src="frame_profil_edit.php"></iframe>
			</div>
		</div>
	
	</div>
	
	<script src="js/responsive.js"></script>

	</body>	
	
</html>