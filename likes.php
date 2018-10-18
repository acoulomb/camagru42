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

$vote = new Vote();
if ($_GET['vote'] == 1){
	$vote->like($db, $_GET['tab'], $_GET['up_id'], $_SESSION['auth']->id, $_GET['vote']);
} else {
	$vote->like($db, $_GET['tab'], $_GET['up_id'], $_SESSION['auth']->id, $_GET['vote']);	
}

App::redirect('index.php?page='.$_GET['page'].'#'.$_GET['up_id']);