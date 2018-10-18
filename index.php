<?php
require_once 'inc/autoload.php';
$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="gallery.css" type="text/css" rel="stylesheet" />

<?php require 'inc/header.php';?>

<h2>Galerie</h2>

<div class=gallery>
	<?php
	$resppage = 5;
	$total = $db->query("SELECT * FROM `img` ORDER BY `up_date` DESC");
	$total = $total->rowCount();
	$last = (int)$total/$resppage;
	
	if (!empty($_GET['page'])){
		if ((int)$_GET['page'] > 0 && (int)$_GET['page'] < $last){
			$page = (int)$_GET['page'];
		}else{
			App::redirect('index.php');
		}
	}else{
		$page = 0;
	}
	$offset = $page*$resppage;
	$img = $db->query("SELECT * FROM `img` ORDER BY `up_date` DESC LIMIT $offset,$resppage")->fetchall();

	foreach($img as $val){
		?>
		<a href="onepic.php?pic=<?php print_r($val->up_id)?>"><div class=gallery_pic><img id="<?php print_r($val->up_id)?>" src='<?php print_r($val->up_path)?>' style="position: relative;"/></a>
		<?php if (isset($_SESSION['auth'])){
			?>
			<div class=like>
				<div class="vote">
					<div class="vote_progress" style="width:<?= ($val->up_likes + $val->up_dislikes) == 0 ? 100 : round(100*($val->up_likes / ($val->up_likes + $val->up_dislikes)))?>%;"></div>
				</div>
				<div class=vote_btns>
					<form action="likes.php?tab=img&up_id=<?php echo $val->up_id?>&vote=1&page=<?php echo $page?>" method="POST">
							<button type=submit class="vote_btn vote_like">
								<i class="fa fa-thumbs-up"></i>
							</button>
							<?php if ($val->up_likes == 0){
								print_r("0");
							}else{
								print_r($val->up_likes);}?>
					</form>
					<form action="likes.php?tab=img&up_id=<?php echo $val->up_id?>&vote=-1&page=<?php echo $page?>" method="POST">
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
			<div id=comm>
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
			<form id=form-comment action="comments.php?up_id=<?php echo $val->up_id?>&page=<?php echo $page?>" method="POST">
					<textarea name="comment" placeholder="Votre commentaire..."></textarea>
					<button type=submit class="comm">Poster</button>
				</form>
			</div>
			<?php } ?>
		
		</div>
		
		<?php
		}
		?>
	
	<?php
		if ($page > 0){?>
			<a href="index.php?page=<?php echo $page-1;?>"><button><</button></a>
			<?php
		}
		
		if ($page < (int)$last){
			?>
			<a href="index.php?page=<?php echo $page+1;?>"><button>></button></a>
			<?php
	}?>

	</div>
<?php require 'inc/footer.php';?>
