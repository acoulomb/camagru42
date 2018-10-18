<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);

if($auth->user()){
	App::redirect('account.php');
}

if (!empty($_POST) && !empty($_POST['passwd'])){
	$user = $auth->login($db, $_POST['login'], $_POST['passwd'], isset($_POST['remember']));
	$session = Session::getInstance();
	if ($user){
		$session->setFlash('success', "Vous êtes connecté.");
		App::redirect('index.php');
	}else{
		$session->setFlash('danger', "Connexion impossible.");		
	}
}

?>

<?php require 'inc/header.php';?>

<h2>Se connecter</h2>
<form action="" method="POST" id="login-form">
    <div class="form-group" id=mini-form>
        <label for="">Login ou email</label>
        <input type="text" name="login"/>

        <label for="">Mot de passe</label>
        <input type="password" name="passwd" required/>
		
		<a id=passwd-forgot href="forget.php" style="text-align: center">Mot de passe oublié ?</a>
    </div>

    <button type="submit" class="submit">Se connecter</button>

</form>

<?php require 'inc/footer.php';?>


