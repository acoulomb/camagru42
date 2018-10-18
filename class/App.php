<?php

class App{

	static $db = null;

	static function getDatabase(){
		if(!self::$db){
			self::$db = new Database('root2', 'root', 'camagru');
		}
		return self::$db;
	}

	static function getAuth(){
		return new Auth(Session::getInstance(), ['restriction_msg' => "Vous n'avez pas accès à cette page"]);
	}

	static function redirect($page){
		header("Location: $page");
		exit();
	}
}