<?php

require_once("session.inc.php");
require_once("database.inc.php");
require_once("security.class.php");

class Auth{
	
	static $REQUIRED = true;
	static $NOT_REQUIRED = false;
	
	public function __construct($requiredAuth = false) {
		
		global $_MYSQLI;
		
			
		if(isset($_SESSION["user_id"])) {
			
			$users_exists_result = $_MYSQLI->query("SELECT * FROM user WHERE user_id = ".$_SESSION["user_id"]);
						
			if($users_exists_result->num_rows == 1)	{
				
				$_SESSION["user_id"] = $users_exists_result->fetch_object()->user_id;

			}
			else {
				
				unset($_SESSION["user_id"]);
			}
		
		}

		else if($requiredAuth) {
			
			header("Location: login.php");
			exit;
			
		}
		
	}
	
	public function isLogged() {
		return isset($_SESSION["user_id"]);
	}
	
	static function user_exists($email, $password = null) {
		
		global $_MYSQLI;
		
		$query = 'SELECT * FROM user WHERE user_email = "'.$_MYSQLI->real_escape_string($email).'"';

		if(!is_null($password))
			$query .=' AND user_password = "'.Security::CryptPassword($password).'"';
		
		$users_matchs_result = $_MYSQLI->query($query);
		
		if($users_matchs_result->num_rows == 1)
			return $users_matchs_result->fetch_object()->user_id;
		else
			return 0;
	}
	
	static function login($email, $password) {
		
		global $_MYSQLI;
		
		$user_id = Auth::user_exists($email, $password);
		
		if($user_id > 0)	{
			
			$_SESSION["user_id"] = $user_id;
			
			return true;
			
		}
		else {
			
			return false;
		}
		
	}
	
	static function logout() {
		
		unset($_SESSION["user_id"]);
		
	}
	
	static function getUserId() {
		if(isset($_SESSION["user_id"]))
			return $_SESSION["user_id"];
		else
			return 0;
	}
	
	static function getUser() {
		
		global $_MYSQLI;
		
		$user_result = $_MYSQLI->query('SELECT * FROM user WHERE user_id = ' . Auth::getUserId());
		
		if($user_result->num_rows == 0) {
			header("Location: logout.php");
			exit;
		}
		
		return $user_result->fetch_object();
	}
	
};


?>