<?php
require 'inc/autoload.php';

App::getAuth()->restrict();

	if(!empty($_POST)){
		$errors = array();
		$db = App::getDatabase();
		$validator = new Validator($_POST);

		if(!empty($_POST['login'])){
			$validator->isAlpha('login', "Le login n'est pas valide");		
			$validator->isUniq('login', $db, 'user', "Ce login est déjà utilisé");
			$validator->isTooLong('login', "Le login renseigné est trop long.");
		}
		if(!empty($_POST['email'])){
			$validator->isEmail('email', "L'email n'est pas valide");
			$validator->isUniq('email', $db, 'user', "Cet email a déjà un compte attaché");	
			$validator->isTooLong('email', "L'email renseigné est trop long.");	
		}
		if(!empty($_POST['passwd'])){
			$validator->isConfirmed('passwd', "Le mot de passe n'est pas valide (1 maj, 1 min, 1 chiffre, 8 caractères min)");
			$validator->isTooLong('passwd', "Le mot de passe renseigné est trop long.");
		}
		if ($validator->isValid()) {
			App::getAuth()->modify($db, $_POST['login'], $_POST['email'], $_POST['passwd'], $_POST['mail_com'], $_SESSION['auth']->id);
			Session::getInstance()->setFlash('success', "Les modifications ont bien été prises en compte");
			App::redirect('account.php');
		}else{
			$errors = $validator->getErrors();
		}
	}
			

require 'inc/header.php';?>

<h2>Bienvenue <?= $_SESSION['auth']->login; ?></h2>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
	<p>Le formlaire n'a pas été correctement rempli : </p>
		<ul>
		<?php foreach($errors as $error): ?>
			<li><?= $error; ?><br/><br/></li>
		<?php endforeach; ?>
		</ul></div>
<?php endif; ?>


<form action="" method="POST" id="login-form">
	<div class="form-group" id=mini-form>
        <label for="">Modifier mon login</label>
        <input type="text" name="login" placeholder="Nouveau login"/><br/>

        <label for="">Modifier mon email</label>
        <input type="text" name="email" placeholder="Nouvel email"/><br/><br/>

		<label for="">Modifier mon mot de passe</label><br/>
		<input type="password" name="passwd" placeholder="Nouveau mot de passe"/>
        <input type="password" name="passwd_confirm" placeholder="Confirmation du mot de passe"/>

	<label><input type="checkbox" checked="checked" name="mail_com" value="1"/>Recevoir un email à chaque nouveau commentaire sur une de mes photos.</label><br/>
	</div>
    <button type="submit" class="submit">Modifier mes informations</button>
</form>

<?php require 'inc/footer.php';?>