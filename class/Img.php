<?php

class Img{
	
	static function saveImgDb($db, $path, $user_id){
		$db->query("INSERT INTO img SET `up_date` = NOW(), `up_usid` = ?, `up_path` = ?", [
			$user_id,
			$path
			]);
		}
	
	private function saveImgTmp($file, $bool){
		if (!file_exists('db/tmp')){
			mkdir('db/tmp');
		}
		$file_name = date("YmdHis") . "-". $_SESSION['auth']->id . ".png";
		$path = "./db/tmp/{$file_name}";
		if ($bool == 1){
			$resultat = rename($file,$path);
		}else{
			$resultat = file_put_contents("{$path}", $file);
		}
		return ($file_name);
	}

	function imageCopyMerge_Alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		$cut = imagecreatetruecolor($src_w, $src_h);
		imageCopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
		imageCopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
		imageCopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
	}

	function mergeImage($pic, $frame)
	{
		// COPY
		$frame = imageScale($frame, imagesx($pic));
		self::imageCopyMerge_Alpha($pic, $frame, imagesx($pic) / 2 - imagesx($frame) / 2, imagesy($pic) / 2 - imagesy($frame) / 2, 0, 0, imagesx($frame), imagesy($frame), 100);
		// SAVE TO $pic
		imageSaveAlpha($pic, true);
		// SAVE OUTPUT
		ob_start();
		imagepng($pic);
		$result =  ob_get_contents();
		ob_end_clean();
		// DESTROY AND RETURN
		imagedestroy($pic);
		imagedestroy($frame);
		return $result;
	}

	static function applyFrame($file, $frame, $type, $bool, $db, $user_id){
		$file_name = self::saveImgTmp($file, $bool);
		$path = "./db/tmp/{$file_name}";
		$frame = "./inc/filter/frame{$frame}.png";
		if ($type == "image/png"){
			$pic = ImageCreateFromPng($path);
		} else if ($type == "image/jpeg"){
			$pic = ImageCreateFromJpeg($path);
		}
		$frame = ImageCreateFromPng($frame);
		$data = self::mergeImage($pic, $frame);
		if ($bool == 2){
			file_put_contents("./db/img/{$file_name}", $data);
			unlink($path);
			self::saveImgDb($db, "./db/img/{$file_name}", $user_id);
		} else if ($bool == 1){
			file_put_contents($path, $data);
			return($file_name);
		}
	}

	static function saveFile($file_name, $db, $user_id){
		rename("./db/tmp/{$file_name}", "./db/img/{$file_name}");
		self::saveImgDb($db, "./db/img/{$file_name}", $user_id);
	}

	static function delFile($file_name){
		unlink("./db/tmp/{$file_name}");
	}

	static function del_pic($db, $pic, $user_id){
		$req = $db->query("SELECT * FROM img WHERE `up_path` = ? AND `up_usid` = ?", [
			$pic,
			$user_id
			]);
		if ($req->rowCount() > 0){
			$db->query("DELETE FROM img WHERE `up_path` = ? AND `up_usid` = ?", [
				$pic,
				$user_id
				]);
			unlink($pic);
		}
	}

	public function is_dir_empty($dir) {
		if (!is_readable($dir)) return NULL;
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
		  if ($entry != "." && $entry != "..") {
			return FALSE;
		  }
		}
		return TRUE;
	  }

}

?>