<?php
require 'inc/autoload.php';
if(isset($_GET['id']) && isset($_GET['token'])){
	$auth = App::getAuth();
	$db = App::getDatabase();
	$user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
    if($user){
        if(!empty($_POST)){
			$validator = new Validator($_POST);
			$validator->isTooLong('passwd', "Le mot de passe renseigné est trop long.");
			$validator->isConfirmed('passwd', "Le mot de passe n'est pas valide (1 maj, 1 min, 1 chiffre, 8 caractères min).");
			if ($validator->isValid()&& $_POST['passwd'] == $_POST['passwd_confirm']){
				$password = $auth->hashPassword($_POST['passwd']);
				$db->query('UPDATE user SET `passwd` = ?, `reset_at` = NULL, `reset_token` = NULL WHERE id = ?', [$password, $_GET['id']]);
				$auth->connect($user);
				$session = Session::getInstance();
				$session->setFlash('success', "Votre mot de passe a bien été modifié.");
				App::redirect('account.php');
			}else {
				$session = Session::getInstance();
				$session->setFlash('danger', "Les mots de passe ne correspondent pas ou ne sont pas valides (1 maj, 1 min, 1 chiffre, 8 caractères min).");
			}
		}
	}else{
		$session = Session::getInstance();
		$session->setFlash('danger', "Ce token n'est pas valide.");
		App::redirect('login.php');
	}
}else{
	App::redirect('login.php');
}
?>

<?php require 'inc/header.php';?>

<h2>Réinitialiser le mot de passe</h2>
<form action="" method="POST" id="login-form">

    <div class="form-group" id="mini-form">
        <label for="">Mot de passe</a></label>
        <input type="password" name="passwd" required/>
    </div>

	<div class="form-group"id="mini-form">
        <label for="">Confirmation du mot de passe</label>
        <input type="password" name="passwd_confirm" required/>
    </div>

    <button type="submit" class="submit">Réinitialiser le mot de passe</button> 

</form>

<?php require 'inc/footer.php';?>


