<?php
require 'inc/autoload.php';
if(!empty($_POST) && !empty($_POST['email'])){
	$db = App::getDatabase();
	$auth = App::getAuth();
	$session = Session::getInstance();
	if ($auth->resetPassword($db, $_POST['email'])){
		$session->setFlash('success', "Les instructions du rappel de mot de passe vous ont été envoyées par email.");
		App::redirect('login.php');
	}else{
		$session->setFlash('danger', "Aucun compte ne correspond à cette adresse.");		
	}
}
?>

<?php require 'inc/header.php';?>

<h2>Mot de passe oublié</h2>
<form action="" method="POST" id="login-form">
    <div class="form-group" id=mini-form>
        <label for="">Email</label>
        <input type="email" name="email"/>
    </div>

    <button type="submit" class="submit">Réinitialiser le mot de passe</button> 

</form>

<?php require 'inc/footer.php';?>