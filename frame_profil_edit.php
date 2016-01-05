<?php

require_once("include/auth.class.php");
require_once("include/validation.class.php");
require_once("include/database.inc.php");
require_once("include/sqlbuilder.class.php");

$auth = new Auth(true);
$user = Auth::getUser();


$_RULES = array(
				"user_firstname" => Validation::$f->notEmpty_String,
				"user_lastname" => Validation::$f->notEmpty_String,
				"user_email" => Validation::$f->Email,
				"user_schoolname" => Validation::$f->notEmpty_String
			);
			
$v = new Validation($_POST, array("user_firstname", "user_lastname", "user_email", "user_schoolname", "user_password", "user_repassword"), $_RULES);
			
if($v->fieldsExists()) {

	
	$setrepassword = Validation::Query($_POST, array("user_password", "user_repassword"));
	$repassword = $setrepassword ? ($_POST["user_password"] == $_POST["user_repassword"]) : false;
	
	
	$email_available = Auth::user_exists($_POST["user_email"]) == 0 || $_POST["user_email"] == $user->user_email;

	
	
	if($v->testAll() && $email_available) {
				
		$set = $v->export($_MYSQLI, array("user_firstname", "user_lastname", "user_email", "user_schoolname", "user_password"));		

		if(false)
			$set["user_photo_path"] = "";
		
		if($repassword)
			$set["user_password"] = Security::CryptPassword($_POST["user_password"]);
		
		$statement = new SQLBuilder($_MYSQLI);
		
		$q = $statement->update('user')
				->set($set)
				->where("user_id", "=", Auth::getUserId())
				->build();
		
		$r = $_MYSQLI->query($q);

	}

	}

$user = Auth::getUser();

	
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="js/datetimepicker-master/jquery.datetimepicker.css"/ >
		<script src="js/jquery.min.js"></script>
		<script src="js/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
		
	</head>
	
	<body style="background:#fff;">
			<div id="answer_framed" >
				<div class="padder">

						<img id="loader" src="media/static/loader.gif" alt="" style="display:none;margin-top:10px;" />
						
						<div id="answerForm">
							<form action="frame_profil_edit.php?refresh=true" method="post" id="ownquestionform">
							
								<table class="doublefieldset">
								<tr><td>
								<fieldset class="w50">
									<legend>Nom</legend>
									<input  id="startdate" type="text" name="user_lastname" value="<?php echo $user->user_lastname; ?>" /><br/>
								</fieldset>
								</td><td>
								<fieldset class="w50">
									<legend>Prénom</legend>
									<input   id="enddate" type="text" name="user_firstname" value="<?php echo $user->user_firstname; ?>" /><br/>
								</fieldset>
								</td>
								</tr>
								</table>							

								<table class="doublefieldset">
								<tr><td>
								<fieldset class="w50">
									<legend>E-Mail</legend>
									<input  id="startdate" type="text" name="user_email" value="<?php echo $user->user_email; ?>" /><br/>
								</fieldset>
								</td><td>
								<fieldset class="w50">
									<legend>Établissement</legend>
									<input id="enddate" type="text" name="user_schoolname" value="<?php echo $user->user_schoolname; ?>" /><br/>
								</fieldset>
								</td>
								</tr>
								</table>
								
								<table class="doublefieldset">
								<tr><td>
								<fieldset class="w50">
									<legend>Mot de passe</legend>
									<input  id="user_password" type="password" name="user_password" /><br/>
								</fieldset>
								</td><td>
								<fieldset class="w50">
									<legend>Veuillez retaper le mot de passe</legend>
									<input id="user_repassword" type="password" name="user_repassword"  /><br/>
								</fieldset>
								</td>
								</tr>
								</table>

		
							</form>
						</div>
						<script>
						
						function validate() {
							
							back = true;
							
							$("#ownquestionform input[type=text]").each(function() {
							
								if($(this).val().length == 0) {
									back = false;
									$(this).addClass("error");
									$(this).change(function () {$(this).removeClass("error");});
								}
							
							});
							
							passwordempty = 0;
							
							$("#ownquestionform input[type=password]").each(function() {
							
								if($(this).val().length == 0) {
									passwordempty++;
								}
							
							});	

							if(passwordempty == 1 || $("#user_password").val() != $("#user_repassword").val() ) {
								$("#ownquestionform input[type=password]").addClass("error");
								$("#ownquestionform input[type=password]").change(function () {$("#ownquestionform input[type=password]").removeClass("error");});
								return false;
							}
								
							
							
							
							return back;
							
						}
						
						<?php if(isset($_GET["refresh"])) echo 'parent.UpdateMenuController('.json_encode($user).')'; ?>
						
						</script>
						<input onclick="if(!validate())return false;document.getElementById('loader').style.display='';" type="submit" form="ownquestionform" value="Sauvegarder" class="btn" />
				</div>
				</div>
			</body>
		</html>