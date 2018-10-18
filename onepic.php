<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="gallery.css" type="text/css" rel="stylesheet" />

<?php require 'inc/header.php';

$gallery = $db->query("SELECT * FROM `img`")->fetchall();

if (!empty($_GET['pic'])){
	foreach ($gallery as $value){
		if ($_GET['pic'] == $value->up_id){
			$val = $value;
		}
	}
	if ($val == null){
		App::redirect('index.php');
	} else {?>
		<div class=gallery_pic id=gal-pic><img src='<?php print_r($val->up_path)?>' style="position: relative; size: 50vw;"/>
		<?php if (isset($_SESSION['auth'])){
			?>
<!-- likes -->
			<div class=like>
				<div class="vote">
					<div class="vote_progress" style="width:<?= ($val->up_likes + $val->up_dislikes) == 0 ? 100 : round(100*($val->up_likes / ($val->up_likes + $val->up_dislikes)))?>%;"></div>
				</div>
				<div class=vote_btns>
					<form action="likes.php?tab=img&up_id=<?php echo $val->up_id?>&vote=1" method="POST">
							<button type=submit class="vote_btn vote_like">
								<i class="fa fa-thumbs-up"></i>
							</button>
							<?php if ($val->up_likes == 0){
								print_r("0");
							}else{
								print_r($val->up_likes);}?>
					</form>
					<form action="likes.php?tab=img&up_id=<?php echo $val->up_id?>&vote=-1" method="POST">
							<button type=submit class="vote_btn vote_dislike">
								<i class="fa fa-thumbs-down"></i>
							</button>
							<?php if ($val->up_dislikes == 0){
								print_r("0");
							}else{
								print_r($val->up_dislikes);}?>
					</form>
				</div>
			</div>

<!-- comments -->
			<div>
				<div id=comments>
					<?php
						$comments = new Comm();
						$com = $comments->getComment($db, $val->up_id);
						if ($com){
							
							foreach ($com as $val){
								?>
							<div class=login><?php echo $val->login_us;?></div>
							<div class=comm_val><?php echo $val->comm_val;?></div>
							<div class=comm_date><?php echo $val->comm_date;?></div>
							<hr size=1 width=50% color=white>
							<?php
							}
						}
						
						?>
			</div>
			<form action="comments.php?up_id=<?php echo $val->up_id?>" method="POST">
					<textarea name="comment" placeholder="Votre commentaire..."></textarea>
					<button type=submit class="comm">Poster</button>
				</form>
				</div>

<!-- share -->
<div id=share>
	<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
	<a href="https://www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"></a>
	<iframe src="https://www.facebook.com/plugins/share_button.php?href=http%3A%2F%2F127.0.0.1%3A8100%2Fdemo%2Fcamagru%2Fonepic.php%3Fpic%3D<?php echo $val->up_id?>&layout=button&size=small&mobile_iframe=true&width=73&height=20&appId" width="73" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
	<a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="Camaguru">Tweet</a>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</div>

<?php }

}

}

?>

<?php require 'inc/footer.php';?>