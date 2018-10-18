<?php
	require_once 'inc/autoload.php';

    if (!empty($_POST)) {
		$errors = array();

		$db = App::getDatabase();
		$validator = new Validator($_POST);
		$validator->isAlpha('name', "Le nom n'est pas valide.");
		$validator->isTooLong('name', "Le nom renseigné est trop long.");
		$validator->isAlpha('forename', "Le prénom n'est pas valide.");	
		$validator->isTooLong('forename', "Le prenom renseigné est trop long.");
		$validator->isAlpha('login', "Le login n'est pas valide.");
		$validator->isTooLong('login', "Le login renseigné est trop long.");
		$validator->isUniq('login', $db, 'user', "Ce login est déjà utilisé.");
		$validator->isEmail('email', "L'email n'est pas valide.");
		$validator->isTooLong('email', "L'email renseigné est trop long.");
		$validator->isUniq('email', $db, 'user', "Cet email a déjà un compte attaché.");		
		$validator->isConfirmed('passwd', "Le mot de passe n'est pas valide (1 maj, 1 min, 1 chiffre, 8 caractères min).");
		$validator->isTooLong('passwd', "Le mot de passe renseigné est trop long.");
		$validator->isCaptcha('g-recaptcha-response', "Le captcha n'a pas fonctionné");

		if ($validator->isValid()) {
			App::getAuth()->register($db, $_POST['name'], $_POST['forename'], $_POST['login'], $_POST['email'], $_POST['passwd']);
			$session = new Session();
			Session::getInstance()->setFlash('success', "Un email de confirmation vous a ete envoyé pour valider votre compte.");
			App::redirect('login.php');
			die('Votre compte a bien été créé. Veuillez le valider en cliquant sur le lien reçu par email.');
		}else{
			$errors = $validator->getErrors();
		}
    }
?>

<?php require_once 'inc/header.php'; ?>


<h2>S'inscrire</h2>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
	<p>Le formulaire n'a pas été correctement rempli : </p>
		<ul>
		<?php foreach($errors as $error): ?>
			<li><?= $error; ?><br/><br/></li>
		<?php endforeach; ?>
		</ul></div>
<?php endif; ?>

<form action="" method="POST" id="login-form">
    <div class="form-group" id=mini-form>
        <label for="">Nom</label>
        <input type="text" name="name" value="<?php if (!empty($_POST)){echo $_POST['name'];}?>"/>

        <label for="">Prénom</label>
        <input type="text" name="forename" value="<?php if (!empty($_POST)){echo $_POST['forename'];}?>"/>

        <label for="">Login</label>
        <input type="text" name="login" value="<?php if (!empty($_POST)){echo $_POST['login'];}?>"/>

		<label for="">Email</label>
        <input type="email" name="email" required value="<?php if (!empty($_POST)){echo $_POST['email'];}?>"/>

        <label for="">Mot de passe</label>
        <input type="password" name="passwd" required/>

        <label for="">Confirmation du mot de passe</label>
        <input type="password" name="passwd_confirm" required/>
		
		<br/><div class="g-recaptcha" data-sitekey="6Lf0YnQUAAAAAF9qQcJjS-GtGEi8-08PEYwhcBKU"></div>
    </div>

    <button type="submit" class="submit">S'inscrire</button>

</form>


<?php require 'inc/footer.php'; ?>