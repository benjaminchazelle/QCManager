<?php

class Validation {
	
	static $f;
	
	static function init () {
		
		Validation::$f = new stdClass();
		
		Validation::$f->{"bool_Int"} = function ($d) { return $d == 0 || $d == 1; };	
		Validation::$f->{"not_Null"} = function ($d) { return !is_null($d); };	
		Validation::$f->{"unsigned_Int"} = function ($d) { return is_numeric($d) && ($d%1 == 0) && $d >= 0; };	
		Validation::$f->{"Int"} = function ($d) { return is_numeric($d) && ($d%1 == 0); };	
		Validation::$f->{"Number"} = function ($d) { return is_numeric($d); };	
		Validation::$f->{"notEmpty_String"} = function ($d) { return strlen($d) > 0; };	
		Validation::$f->{"datetime"} = function ($d) { DateTime::createFromFormat('Y-m-d H:i:s', $d) != false; };	
		Validation::$f->{"Email"} = function ($d) { return filter_var($d, FILTER_VALIDATE_EMAIL); };	
		Validation::$f->{"Timestamp"} = Validation::$f->Int;	
		Validation::$f->{"anterior_Timestamp"} = function ($d) { return $d < time(); };	
		Validation::$f->{"posterior_Timestamp"} = function ($d) { return $d > time(); };	
		
	}
	
	private $validity;
	private $fieldsValidity;
	
	private $fieldExists;
	
	private $die;
	
	private $fields;
	
	public function __construct($source, $requiredFields, $rules=array(), $die=false) {
		
		$this->fields = array();
		
		if(count($rules) == 0)
			$die = false;
		
		$this->die = $die;
		
		$this->validity = true;
		
		$this->fieldsExists = true;
		$this->fieldsValidity = array();
		
		foreach($requiredFields as $k) {
			
			if(isset($source[$k])) {
				if(isset($rules[$k])){
					if($rules[$k]($source[$k])) {
						$this->fieldsValidity[$k] = 1;
						$this->fields[$k] = $source[$k];
						
					}
					else {
						$this->fieldsValidity[$k] = 0;
						$this->validity = false;
					}
				}
				else {
					if($this->die) die("Validation : $k rules not exists");
				}
			}
			else {
				if($this->die) die("Validation : $k field missing");
				$this->fieldsValidity[$k] = -1;
				$this->fieldsExists = false;
			}
			
		}
		
	}
	
	public function test($fieldName) {
		
		if(!isset($this->fieldsValidity[$fieldName])) {
			if($this->die) die("Validation validation : $fieldName fieldname not exists");
			return -2;			
		}
		else {
			return (bool) $this->fieldsValidity[$fieldName];			
		}

	}
	
	public function fail($fieldName) {
		
		return $this->test($fieldName) != 1;
	}
	
	public function testAll() {
		return $this->validity;
	}
	
	public function fieldsExists() {
		return $this->fieldsExists;
	}
	
	public function export($includeFields, $otherFields = array()) {
		
		$fields =  array();
		
		foreach($includeFields as $f) {

			if(isset($this->fields[$f]))
				$fields[$f] = $this->fields[$f];
		}
		
		foreach($otherFields as $f => $v) {

			$fields[$f] = $v;
		}
		
		
		
		return $fields;
	}
	
	
	static function Query($source, $fieldnames) {
		
		foreach($fieldnames as $k) {

			if(!isset($source[$k]))
				return false;
		
		}
		
		return true;
		
	}


};

Validation::init();





?>