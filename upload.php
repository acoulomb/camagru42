<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
App::getAuth()->restrict();
?>

<?php require 'inc/header.php';?>

<h2>Studio photo</h2>

<menu id=menu_upload>
<a href="upload.php?tab=webcam">Depuis votre webcam</a>
<a href="upload.php?tab=file">Depuis vos fichiers</a></menu>

<?php
if (!isset($_GET['tab'])){
 	$_GET['tab'] = "webcam";
}
if ($_GET['tab'] != "webcam" && $_GET['tab'] != "file"){
	$_GET['tab'] = "webcam";
}
if ($_GET['tab'] == "webcam") {?>

<div id=workspace>

<div id=cam>
	<form action="upload_webcam.php" method="POST" enctype="multipart/form-data" id="webcamForm">

<?php require 'inc/frame.php';?>

		<div class="form-group" id="small-form">
			<div class=frame-overview>
				<video id="player" autoplay></video>
				<canvas id="canvas" width=640 height=480></canvas>
				<div id=frame-ajax>
				<img id=frame-over src="inc/filter/frame1.png" />
				</div>
			</div>

			<button id="capture" type="button">Capture</button>

		</div>

			<input type="hidden" name="hidden_data" id="hidden_data" value=""/>
			<input type="submit" name="submit" id="sendForm1" value="Valider" />
			<a href="upload.php" id="reload">Recommencer</a>
	</form>
</div>

<script src="./inc/js/webcam.js"></script>

<?php } else if($_GET['tab'] == "file") {
	if(!isset($_POST['submit'])){ ?>

	<form action="upload_file.php" method="POST" enctype="multipart/form-data" id="fileForm">

	<?php require 'inc/frame.php';?>
		<br/>

		<input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
		<label for="userfile">Téléchargez une photo (JPG ou PNG | max. 300 Ko) :</label><br/><br/>
		<input name="userfile" type="file" accept="image/*" capture="user"/><br/>
		<input type="hidden" name="preview_up" value="0"/><br/>
		<input type="submit" name="submit" id="sendForm2" value="Valider" />
		</form>
<?php }
} ?>

<div id=pics><?php require 'inc/gallery.php';?></div>
</div>
<?php require 'inc/footer.php';?>