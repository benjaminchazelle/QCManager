<?php

header("Content-type: text/plain");

class Ajax {
	
	private $time;
	private $error;
	public $data;
	
	public function __construct() {
		
		$this->time = time();
		$this->error = false;
		$this->errorFlag = "";
		
		$this->data = array();
		
	}
	
	public function setError($value) {
		
		if($value == null) {
			
			$this->error = false;
			$this->errorFlag = "";
			
		}
		else {
			
			$this->error = true;
			$this->errorFlag = $value;
		
		}
		
	}
	
	public function out() {
		
		$obj = array(
						"time" => $this->time,
						"error" => $this->error,
						"errorFlag" => $this->errorFlag,
						"data" => $this->data
		);
		
		echo json_encode($obj, JSON_PRETTY_PRINT);
		
	}
	
	
	
};

?>