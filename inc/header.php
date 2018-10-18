<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
		<link rel="icon" href="inc/img/guru.png" type="image/png"/>
		<script src="https://www.google.com/recaptcha/api.js"></script>
        <title>camaguru</title>
		<link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
		<?php if(Session::getInstance()->hasFlashes()): ?>
				<?php 
				foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
					<div id='alert' class="alert alert-<?= $type; ?>">
							<?= $message; ?>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
						<div class="container">
							<header>
								<div id="header">			
									<div id="logo">
										<a href="index.php"><img id="logo-img" src="inc/img/guru.png" title="Camaguru" alt="Camaguru"></a>
									</div>
									<a href="index.php"><h1 id="big-title">CAMAGURU</h1></a>
									<div class="navbar">
										<li><a href="index.php">Galerie</a></li>
										<?php if (isset($_SESSION['auth'])):?>
										<li><a href="upload.php?tab=webcam">Studio</a></li>
										<li><a href="account.php">Compte</a></li>
										<li><a href="logout.php">Déconnexion</a></li>
										<?php else: ?>
										<li><a href="register.php">S'inscrire</a></li>
										<li><a href="login.php">Se connecter</a></li>
										<?php endif; ?>
									</div>
								</div>
							</header>
							
							<div class="body">
								
<?php if(Session::getInstance()->hasFlashes()){ ?>
<script>
	window.onload = function()
	{
	setTimeout(function()
	{
		document.getElementById("alert").style.display = "none";
	}, 3000);
	}
</script>
<?php } ?>
