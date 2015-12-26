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
	
	if($v->fail("user_firstname"))
		echo "user_firstname fail";

	if($v->fail("user_lastname"))
		echo "user_lastname fail";
	
	if($v->fail("user_schoolname"))
		echo "user_schoolname fail";

	if($v->fail("user_email"))
		echo "user_email fail";
	
	if($setrepassword && !$repassword)
		echo "user_repassword fail";
	
	if(!$email_available)
		echo "email unavailable";

	}

$user = Auth::getUser();

	
?>
<form action="" method="post">


	fn<input type="text" name="user_firstname" value="<?php echo $user->user_firstname; ?>" />
	<br />
	ln<input type="text" name="user_lastname" value="<?php echo $user->user_lastname; ?>" />
	<br />
	em<input type="text" name="user_email" value="<?php echo $user->user_email; ?>" />
	<br />
	sn<input type="text" name="user_schoolname" value="<?php echo $user->user_schoolname; ?>" />
	<br />
	pw<input type="password" name="user_password" />
	<br />
	rpw<input type="password" name="user_repassword" />
	<br />
	
	<input type="submit" name="Envoyer" />

</form>

