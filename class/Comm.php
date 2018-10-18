<?php

class Comm{
	public function sendComment($db, $up_id, $us_id, $login, $comm){
		$req = $db->query("INSERT INTO `comm` SET `up_id` = ?, `us_id` = ?, `comm_date` = NOW(), `login_us` = ?, `comm_val` = ?", [
			$up_id,
			$us_id,
			$login,
			$comm,
			]);
		if ($req == true){
			return true;
		}
	}

	public function getComment($db, $up_id){
		return $db->query("SELECT * FROM `comm` WHERE `up_id`= ? ORDER BY `comm_date` DESC", [$up_id])->fetchall();
	}

	public function sendMailcomm($db, $up_id, $com, $name){
		$user = $db->query("SELECT `up_usid` FROM `img` WHERE `up_id` = ?", [$up_id])->fetch();
		$email = $db->query("SELECT `email` FROM `user` WHERE `id` = ?", [(int)$user->up_usid])->fetch();
		$pref =  $db->query("SELECT `mail_com` FROM `user` WHERE `id` = ?", [(int)$user->up_usid])->fetch();

		if ((int)$pref->mail_com == 1){
			$to = $email->email;
			$subject = 'Nouveau commentaire sur votre photo !';
			$message = "Bonjour !\n\n$name a laissé un commentaire sur une de vos photos Camaguru! \n\n\"$com\"";
			$headers = 'From: webmaster@camagru.com' . "\r\n" .
			'Reply-To: webmaster@camagru.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
		}
	}
}

?>