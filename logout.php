<?php 
require 'inc/autoload.php';
App::getAuth()->logout();
Session::getInstance()->setFlash('success', "Vous êtes bien déconnecté");
App::redirect('login.php');