<?php

class Security {

	static function CryptPassword($password) {
		
		return sha1($password . md5(sha1($password)));
		
	}
	
};


?>