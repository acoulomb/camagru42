<?php

class Validator{

    private $data;
    private $errors = [];

    public function __construct($data){
        $this->data = $data;
	}
	
	private function getField($field){
		if (!isset($this->data[$field])){
			return null;
		}
		return $this->data[$field];
	}

	public function isAlpha($field, $errorMsg = ''){
		if(!preg_match('/^[a-zA-Z0-9_-]+$/', $this->getField($field))){
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isUniq($field, $db, $table, $errorMsg = ''){
		$record = $db->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
		if($record) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isEmail($field, $errorMsg = ''){
		if(!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isConfirmed($field, $errorMsg = ''){
		if(empty($this->getField($field)) || strlen($this->getField($field)) < 8 || !preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $this->getField($field)) || $this->getField($field) != $this->getField($field . '_confirm')){
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isCaptcha($field, $errorMsg = ''){
		$secret = "6Lf0YnQUAAAAADz5C6K_wybuV8vILba4IVS-oOh_";
		$response = $this->getField($field);
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$api_url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$remoteip ;

		$decode = json_decode(file_get_contents($api_url), true);
		if ($decode['success'] != true) {
			$this->errors[$field] = $errorMsg;
		}
	}
	
	public function isTooLong($field, $errorMsg = ''){
		if (strlen($this->getField($field)) > 250){
			$this->errors[$field] = $errorMsg;
		}
	}

	public function isValid(){
		return empty($this->errors);
	}

	public function getErrors(){
		return $this->errors;
	}
}