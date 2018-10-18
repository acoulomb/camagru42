<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
App::getAuth()->restrict();
?>

<link href="gallery.css" type="text/css" rel="stylesheet" />

<div class=us_pic>
<br/>Vos photos<br/><br/>
<?php
	$pics = $db->query("SELECT * FROM `img` WHERE `up_usid` = ? ORDER BY `up_path` DESC", [$_SESSION['auth']->id]);
	foreach ($pics as $val){
		?>
		<img class=gallery_us src='<?php print_r($val->up_path)?>'/>
		<form action="del_pic.php" method="POST">
				<input type="hidden" name="hidden_pic" value=<?php echo $val->up_path?> />
				<input type=submit value=Supprimer />
				</form>
	<?php }
	?>

</div>