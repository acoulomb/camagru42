<?php

class Auth{

	private $options = [
		'restriction_msg' => "Vous n'avez pas le droit d'accéder à cette page"
	];

	private $session;

	public function __construct($session, $options= []){
		$this->options = array_merge($this->options, $options);
		$this->session = $session;
	}

	public function hashPassword($passwd){
		return password_hash($passwd, PASSWORD_BCRYPT);
	}

	public function register($db, $name, $forename, $login, $email, $passwd){
		$password = $this->hashPassword($passwd);
		$token = Str::random(60);
		$db->query("INSERT INTO user SET `name` = ?, forename = ?, `login` = ?, email = ?, passwd = ?, `check` = ?, mail_com = ?, `token` = ?", [
			$name,
			$forename,
			$login,
			$email,
			$password,
			0,
			1,
			$token
		]);
		
		$user_id = $db->lastInsertId();

		$to      = $email;
		$subject = 'Confirmation de votre compte Camaguru';
		$message = "Bonjour !\n\nAfin de valider votre compte, merci de cliquer sur le lien suivant\n\nhttp://127.0.0.1:8100/demo/camagru/confirm.php?id=$user_id&token=$token";
		$headers = 'From: webmaster@camagru.com' . "\r\n" .
		'Reply-To: webmaster@camagru.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $headers);
	}

	public function modify($db, $login = '', $email = '', $passwd = '', $mail_com = '', $user_id){	
		if ($login != null){
			$db->query('UPDATE user SET `login` = ? WHERE id = ?', [$login, $user_id]);
		}
		if ($email != null){
			$db->query('UPDATE user SET `email` = ? WHERE id = ?', [$email, $user_id]);
		}
		if ($passwd != null){
			$password = $this->hashPassword($passwd);			
			$db->query('UPDATE user SET `passwd` = ? WHERE id = ?', [$password, $user_id]);
		}
		if ($mail_com == null){
			$db->query('UPDATE user SET `mail_com` = ? WHERE id = ?', [0, $user_id]);
		}else if ($mail_com == 1){
			$db->query('UPDATE user SET `mail_com` = ? WHERE id = ?', [$mail_com, $user_id]);
		}
		$user = $db->query('SELECT * FROM user WHERE id = ?', [$user_id])->fetch();		
		$this->session->write('auth', $user);					
	}


	public function confirm($db, $user_id, $token){
		$user = $db->query('SELECT * FROM user WHERE id = ?', [$user_id])->fetch();
		
		if ($user && $user->token == $token) {
			$db->query('UPDATE user SET `token` = NULL, `check` = 1 WHERE id = ?', [$user_id]);
			$this->session->write('auth', $user);
			return true;
		} else {
			return false;
		}
	}

	public function restrict(){
		if(!$this->session->read('auth')){
			$this->session->setFlash('danger', $this->options['restriction_msg']);
			header('Location: login.php');
			exit();
		}
	}

	public function user(){
		if(!$this->session->read('auth')){
			return false;
		}
		return $this->session->read('auth');			
	}

	public function connect($user){
		$this->session->write('auth', $user);
	}

	public function connectFromCookie($db){
		if(isset($_COOKIE['remember']) && !$this->user() ){
			$remember_token = $_COOKIE['remember'];
			$parts = explode('==', $remember_token);
			$user_id = $parts[0];
			$user = $db->query('SELECT * FROM user WHERE id = ?', [$user_id])->fetch();
			if($user){
				$expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'securite_supp');
				if($expected == $remember_token){
					$this->connect($user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
				} else{
					setcookie('remember', null, -1);
				}
			}else{
				setcookie('remember', null, -1);
			}
		}
	}
	
	public function login($db, $login, $passwd, $remember = false){
		$user = $db->query('SELECT * FROM user WHERE (`login` = :login OR `email` = :login) AND `check` = 1', ['login' => $login])->fetch();
		if ($user && password_verify($passwd, $user->passwd)){
			$this->connect($user);
			if($remember){
				$this->remember($db, $user->id);
			}
			return $user;
		}else if ($user == NULL || $login == NULL){
			return false;
		}
	}
	
	public function remember($db, $user_id){

		$remember_token = Str::random(250);
		$db->query('UPDATE user SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);
		setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
	}

	public function logout(){
		$this->cleanTmp();
		setcookie('remember', NULL, -1);
		$this->session->delete('auth');
	}

	public function resetPassword($db, $email){

		$user = $db->query('SELECT * FROM user WHERE `email` = ? AND `check` = 1', [$email])->fetch();
		if($user){
			$reset_token = Str::random(60);
			$db->query('UPDATE user SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
	
			$to      = $email;
			$subject = 'Réinitiatilisation de votre mot de passe Camaguru';
			$message = "Bonjour !\n\nAfin de réinitialiser votre mot de passe, merci de cliquer sur le lien suivant\n\nhttp://127.0.0.1:8100/demo/camagru/reset.php?id={$user->id}&token=$reset_token";
			$headers = 'From: webmaster@camagru.com' . "\r\n" .
			'Reply-To: webmaster@camagru.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
			return $user;
		}else{
			return false;
		}
	}

	public function checkResetToken($db, $user_id, $token){
		return $db->query('SELECT * FROM user WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();
	}

	public function cleanTmp(){
		$tmp = "db/tmp";
		if (is_dir($tmp)) {
			$files = scandir($tmp);
			foreach ($files as $file) {
				if ($file != "." && $file != "..") {
					if (strstr($file, "-".strval($_SESSION['auth']->id)) !== false){
						unlink($tmp."/".$file);
					}
				}
			}
		}
		reset($files);
	}
	
}