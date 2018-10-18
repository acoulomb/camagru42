<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
App::getAuth()->restrict();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	http_response_code(403);
	die();
}

$comm = new Comm();
$com_val = htmlentities($_POST['comment']);

$res = $comm->sendComment($db, $_GET['up_id'], $_SESSION['auth']->id, $_SESSION['auth']->login, $com_val);
if ($res){
	$comm->sendMailcomm($db, $_GET['up_id'], $com_val, $_SESSION['auth']->login);
}
App::redirect('index.php?page='.$_GET['page'].'#'.$_GET['up_id']);