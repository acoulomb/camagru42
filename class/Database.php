<?php

class Database{
	
	private $pdo;

	public function __construct($login, $passwd, $database_name = NULL, $host='127.0.0.1'){
		if (isset($database_name) && !empty($database_name))
			$this->pdo = new PDO("mysql:host=$host;dbname=$database_name;charset=utf8", $login, $passwd);
		else
			$this->pdo = new PDO("mysql:host=$host;charset=utf8", $login, $passwd);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}

	public function query($query, $params = false){
		if($params){
			$req = $this->pdo->prepare($query);
			$req->execute($params);
		}else{
			$req = $this->pdo->query($query);
		}
		return $req;
	}

	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	
}

