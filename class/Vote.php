<?php

class Vote {
	private function likeCheck($db, $us_id, $up_id){
		$req = $db->query("SELECT * FROM `likes` WHERE `up_id`=? AND `us_id` = ?", [
			$up_id,
			$us_id,
		])->fetch();
		return $req;
	}

	private function addLike($db, $up_id, $us_id, $val){
		$db->query("INSERT INTO `likes` SET `tab` = 'img', `up_id` = ?, `us_id` = ?, `like_val` = ?", [
			$up_id,
			$us_id,
			$val,
			]);
			if ($val > 0){
			$db->query("UPDATE `img` SET `up_likes` = `up_likes` + ? WHERE `up_id` = ?", [
				$val,				
				$up_id,
				]);
			}else{
				$db->query("UPDATE `img` SET `up_dislikes` = `up_dislikes` - ? WHERE `up_id` = ?", [
					$val,					
					$up_id,
					]);				
			}
		}

	private function updateLike($db, $up_id, $us_id, $val){
			$db->query("UPDATE `img` SET `up_likes` = `up_likes` + ?, `up_dislikes` = `up_dislikes` - ? WHERE `up_id` = ?", [
				$val,
				$val,
				$up_id,
				]);
			}

	private function cancelLike($db, $up_id, $us_id, $val){
		$db->query("UPDATE `likes` SET `like_val` = ?", [
			$val,
		]);
	}
		
	public function like($db, $tab, $up_id, $us_id, $val){
		$req = $db->query("SELECT * FROM $tab WHERE `up_id` = ?", [
			$up_id,
			]);
		if ($req->rowCount() > 0){
			$like_check = self::likeCheck($db, $us_id, $up_id);
			if ($like_check == false){
				self::addLike($db, $up_id, $us_id, $val);
				return true;
			}else if ($like_check->like_val != $val){
				self::cancelLike($db, $up_id, $us_id, $val);
				self::updateLike($db, $up_id, $us_id, $val);
				}
		}else {
			throw new Exception('Impossible de voter pour un enregistrement qui nexiste pas');
		}
	}

}