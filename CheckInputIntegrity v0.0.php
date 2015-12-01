<?php

/*

	USE :
	
		'(new CheckInputIntegrity("value_text_in_input"))->match("DATATYPES")'
			-> return a boolean : true if correct, false otherwise
			

	Summary of types availabes (see further if you need more informations) :
	
		- integer
		- double
		- number
		- positive
		- negative
		- username
		- password
		- email
		- telephone_fr
		- hexa
		- binary
		- date
		- abbreviated_date
		- year
		- day
		- month
		- timestamp
		- hour
		- minut
		- second
		- slug
		- url
		- html_tag
		- html_color
		- null
		- not_null
		- ip_adress
	
	
	Logicals operator
		- OR
		- AND : will come later
		- PARENTHESIS : will come later
		
	
	Types availabes by categories :
	
	Numbers :
		- integer : integer number
			ex : 123 ; -456
		- double : float number
			ex : 12.03 ; -456.6 ; 456,5
		- number : integer or float number
			ex : 456, +123, -1.3, 4.6
		- positive : positive number
			ex : 123, +456, 456.6, +8874,5
		- negative : negative number
			ex : -123, -879.6
			
	Utilities :
		
		- username : check if the username isn't too much complicated
			ex : toto, trol_91
		- password : check if the password don't got special chars, and is size from 6 to 18 characters
			ex : trol91, thisistheend
		- email : email adress
			ex : toto@example.com
		- telephone_fr : french phone number (first chiffer is 0, there are 10 total number)
			ex : 0614259299
		
	Numbers basis
		- hexa : check if the input is a number in hexa base
			ex : a45, F26b, FFA
		- binary : check if if the input is a number in binary base
			ex : 0, 0101, 1111111
	
	Date :
	
		- date : check if it's a correct date
			ex : "1956-11-20" ; "1980 03 31" 
			
		- abbreviated_date : check if it's an abbreviated date
			ex : "16-11-20" ; "95-00-12" 
	
		Unit :
			- year : check if it seems to be a year (positive)
				ex : 0000, 0123, 4568
			- day : check if it seems to be a day (01-31)
				ex : 02, 30
			- month : check if it seems to be a month (01-12)
				ex : 02, 10
			
	Time :
	
		- timestamp : check if it's a timestamp
			ex : "1448916753" for "2015-11-30 21:52:33"
	
		Unit :
			- hour : check if it seems to be a hour (00-23)
				ex : 00, 12, 22
			- minut : check if it seems to be a minut (00-59)
				ex : 00, 56
			- second : check if it seems to be a second (00-59)
				ex : 02, 26
	HTML :
		- slug : check if it's a good name for an url
			ex : "index-test" is ok, but not "test_failed"
		- url : check if it's seems to be a correct url (with or without http(s))
			ex : "example.com/test1/fds.php"
		- html_tag : check if it's a good html attribute tag name
			ex : "test" in index.php#test
		- html_color : check if it's an HTML color
			ex : #0000FF (blue)
			
	Others :
		- null : null string (that don't contains anything)
		- not null : not null string (need to contains at least one character)
		- ip_adress : check if it's a correct IPV4 ip adress ({0-255}^4)
			ex : 192.2.3.56, 0.0.0.0

*/


class CheckInputIntegrity
{
	
	private $input_text;
	
	private static $TYPES_AVAILABLES = array('integer','double','number','positive','negative','username','password','email','telephone_fr','hexa','date','abbreviated_date','year','day','month','timestamp','hour','minut','second','slug','url','html_tag','html_color','null','not_null','ip_adress');
	
	public function __construct($input_text)
	{
		$this->input_text = strval($input_text);
	}
	
	public function match($regex_str)
	{
		$result = str_replace("OR", "|", $regex_str);
		
		foreach(CheckInputIntegrity::$TYPES_AVAILABLES as $val) // to improve
		{
			$result = str_replace(" ".$val, CheckInputIntegrity::toRegex($val), $result);
			$result = str_replace($val." ", CheckInputIntegrity::toRegex($val), $result);
		}
		
		$result = str_replace(" ", "", $result);

		$result = CheckInputIntegrity::finalizeRegex($result);
		echo $result."<br>";
		
		$result = preg_match($result, $this->input_text, $matches, PREG_OFFSET_CAPTURE);
		
		return $result;
	}
	
	private static function finalizeRegex($regex_str)
	{
		$regex_str = "/".$regex_str."/";
		return $regex_str;
	}
	
	private static function toRegex($command)
	{ // time hour ? +refaire ++++++++++++++++++++++++ ALL DATES / phone ?
		switch($command)
		{
			case "integer":
				return "^([+-]?[0-9]+)$";
			case "double":
				return "^([+-]?[0-9]+[.|,][0-9]+)$";
			case "positive": // Not optimal
				return "^(\+?[^\-].*)";
			case "negative":
				return "^-.*";
			case "null":
				return "^-.*";
			case "not_null":
				return "^(.+)$";
			case "number":
				return CheckInputIntegrity::toRegex("integer")."|".CheckInputIntegrity::toRegex("double");
			case "email": // Better ?
				return "^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$";
			case "telephone_fr":
				return "^0[1-9][0-9]{8}$";
			case "html_color":
				return "^#([0-9a-fA-F]{6})$";
			case "hexa":
				return "^([a-fA-F0-9]+)$";
			case "binary":
				return "^([01]+)$";
			case "day":
				return "^(0[1-9]|[1-2][0-9]|3[0-1])$";
			case "month":
				return "^(0[1-9]|1[1-2])$";
			case "year":
				return "^([0-9]{4})$";
			case "hour":
				return "^([0-1][0-9]|2[0-3])$";
			case "minut":
				return "^([0-5][0-9])$";
			case "second":
				return "^([0-5][0-9])$";
			case "slug":
				return "^[a-z0-9-]+$";
			case "url": // Better ?
				return "^((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?)$";
			case "ip_adress":
				return "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$";
			case "html_tag":
				return "^<([a-z]+)([^<]+)*(?:>(.*)<\/\1>|\s+\/>)$";
			case "username":  // Lenght ?
				return "^<([a-z]+)([^<]+)*(?:>(.*)<\/\1>|\s+\/>)$";
			case "password": // Lenght ?
				return "^[a-z0-9_-]{6,18}$";
			case "date":
				return "^([0-9]{4}-(0[1-9]|1[0-2])-([0-2][1-9]|3[0-1])|[0-9]{4}(0[1-9]|1[0-2])([0-2][1-9]|3[0-1]))$";
			case "abbreviated_date":
				return "^([0-9]{2}-(0[1-9]|1[0-2])-([0-2][1-9]|3[0-1]))$";
			case "timestamp":
				return CheckInputIntegrity::toRegex("integer");
			default:
				return null;
		}
	}
	
	public function __destruct()
	{
	}
		
}

?>