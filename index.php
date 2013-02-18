<!DOCTYPE html>
<html>
<head>
	<title>Rectangle Packing</title>
	<meta charset="utf-8">
	<style>
		img {
			position: absolute;
			margin: 0;
			padding: 0;
		}

		#holder {
			position: relative;
		}
	</style>
</head>
<body>
	<h1>Rectangle Packing</h1>
	<?php
		// let's just define an array of images - I'm using a few images
		// randomly collected from deviantART - kudos to each author
		$images = array(
			array('id' => 'http://th09.deviantart.net/fs70/200H/i/2013/048/4/0/bmo_mini_book___adventure_time_by_myfebronia-d5v5pit.jpg', 'width' => 161, 'height' => 199),
			array('id' => 'http://th07.deviantart.net/fs71/200H/f/2013/048/c/d/on_a_monday_morning_by_vidom-d5v8k1j.jpg', 'width' => 299, 'height' => 197),
			array('id' => 'http://th02.deviantart.net/fs71/200H/f/2013/048/6/3/seconds_ticking_away_by_hawkeyedelite-d5v6x60.png', 'width' => 147, 'height' => 200),
			array('id' => 'http://th02.deviantart.net/fs70/200H/f/2013/048/1/a/josefinejonsson_120904_0055_by_bloddroppe-d5v8f6r.jpg', 'width' => 133, 'height' => 200),
			array('id' => 'http://th05.deviantart.net/fs70/200H/f/2013/047/d/3/sv__vixiebee_by_kaze_hime-d5v7kwk.jpg', 'width' => 128, 'height' => 200),
			array('id' => 'http://th01.deviantart.net/fs71/200H/f/2013/048/4/7/god_of_evanescence_by_apofiss-d5v8s3p.jpg', 'width' => 130, 'height' => 200),
			array('id' => 'http://th02.deviantart.net/fs71/200H/f/2013/048/4/c/pokemon_white___hilda___touko_by_beethy-d5v8rzz.jpg', 'width' => 300, 'height' => 200),
			array('id' => 'http://th00.deviantart.net/fs70/200H/f/2013/047/c/b/canine_base_psd_and__png_by_snow_body-d5v5qyq.png', 'width' => 182, 'height' => 200),
			array('id' => 'http://th00.deviantart.net/fs70/200H/i/2013/047/f/2/its_so_fluffy_by_suikuzu-d5v6qoj.png', 'width' => 300, 'height' => 188),
			array('id' => 'http://th00.deviantart.net/fs70/200H/f/2013/048/4/0/my_wall_only_for_me_to_adore_by_chriscold-d5v8q11.jpg', 'width' => 299, 'height' => 112),
			array('id' => 'http://fc04.deviantart.net/fs70/f/2013/047/8/1/81c5d4df933ab79a71853dc535da2db1-d5v7hgu.png', 'width' => 210, 'height' => 282)
		);

		require_once("RectanglePacker.class.php");
		$rp = new PowerOf2RectanglePacker($images);
		$imgs = $rp->pack();
		$dimensions = $rp->getPackingDimensions();
	?>
	<h2>Packing Dimensions</h2>
	<p><?php echo $dimensions['width']; ?> x <?php echo $dimensions['height']; ?></p>
	<h2>Result</h2>
	<div id="holder">
		<?php foreach ($imgs as $img): ?><img width="<?php echo $img['width']; ?>" height="<?php echo $img['height']; ?>" style="<?php echo "left: " . $img['posX'] . "px; top: " . $img['posY'] . "px;"; ?>" alt="<?php echo $img['id']; ?>" src="<?php echo $img['id'] ?>" /><?php endforeach; ?>
	</div>
</body>
</html>