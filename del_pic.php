<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
App::getAuth()->restrict();

if(!empty($_POST)){
	$img = new Img();
	$img->del_pic($db, $_POST['hidden_pic'], $_SESSION['auth']->id);
}
App::redirect('upload.php');
