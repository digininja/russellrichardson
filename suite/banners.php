<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/login.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/banner.class.php");

session_start();

check_login();

$form_array = array (
						'url' => '',
						'sub_headline' => '',
						'headline' => '',
						'url' => '',
						'homepage' => '',
						'title' => '',
						'position' => '',
					);

$validation_errors = array();

# null = no save
# true = save ok
# false = save failed
$save_result = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	foreach ($form_array as $name => $value) {
		if (array_key_exists ($name, $_POST)) {
			if (is_array ($_POST[$name])) {
				$form_array[$name] = $_POST[$name];
			} else {
				$form_array[$name] = trim ($_POST[$name]);
			}
		}
	}

	$form_array['position'] = intval ($form_array['position']);

	if (array_key_exists ("image_upload", $_FILES) && array_key_exists ("position", $_POST)) {
		$position = intval ($form_array['position']);
		if ($position > 0 && $position < 7) {
			if ($_FILES["image_upload"]['name'] != "") {
				$extension = pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION);
				if ($extension == "png" || $extension == "jpg") {
					# removing both so only the one definitive banner exists
					# print ("moving: " . $_FILES['image_upload']['tmp_name'] . " to " . $_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . "." . $extension);
					if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . ".jpg")){
						unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . ".jpg");
					}
					if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . ".png")){
						unlink($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . ".png");
					}
					move_uploaded_file ($_FILES['image_upload']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $position . "." . $extension);
				} else {
					$validation_errors[] = "Only png or jpg allowed";
				}
			}
		}
	}

	if ($form_array['headline'] != "" && $form_array['url'] != "") {
		$banner = new banner();
		$banner->load_if_exists ($form_array['position']);
		$banner->set_sub_headline ($form_array['sub_headline']);
		$banner->set_headline ($form_array['headline']);
		$banner->set_url ($form_array['url']);
		$banner->set_title ($form_array['title']);
		$banner->set_homepage ($form_array['homepage'] == YES);
		$banner->set_id ($form_array['position']);
		$banner->save();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Banners</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Description" content="Administration area for RR" />
		<meta name="Author" content="Robin Wood - Freedom Software" />
		<style type="text/css" media="screen">
			@import "style.css";
		</style>
		<?php
			include('parts/styles.php');
		?>
	</head>
	<body>
		<div class="container">


			<div class="row justify-content-center">
				<div class="col-8 py-5 text-center">
				 <img src="/assets/svgs/logos/main-logo.svg" width="320">
				</div>

			</div>

			<div class="row">

				<div class="col-4">

					<?php
						include('parts/sidenav.php');
					?>

				</div>

				<div class="col-8">

				<div class="header">
					<h1>Banners</h1>
				</div>


				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="/suite/">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">Banners</li>
				  </ol>
				</nav>

				<div id="main">
					
					<?php
					if (count ($validation_errors) > 0) {
						?>
						<div class="alert alert-danger" role="alert">
							<p>Errors:</p>
							<ul>
								<?php
								foreach ($validation_errors as $error) {
									?>
									<li><?=clean_display_string ($error)?></li>
									<?php
								}
								?>
							</li>
						</div>
						<?php
					}
					?>

					<?php
					for ($i = 1;$i < 7;$i++) {
						$banner = new banner();
						if ($banner->load_if_exists ($i)) {
						}
						?>
						<hr>
						<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="post">
							<fieldset>
								<?php
								if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $i . ".jpg")){
									?>
									<p class="image"><img class="w-100" src="/userfiles/banners/banner<?=$i?>.jpg" alt="banner" /></p>
									<?php
								}
								if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/banners/banner" . $i . ".png")){
									?>
									<p class="image"><img class="w-100" src="/userfiles/banners/banner<?=$i?>.png" alt="banner" /></p>
									<?php
								}
								?>
								<h2>Banner <?=$i?></h2>
								<hr>
								<input type="hidden" value="<?=clean_display_string ($i)?>" name="position" />
								<div class="w-100">
								<label class="w-100" for="image_upload">Replace Image</label>
								<input type="file" name="image_upload" />
								</div>
								<div class="w-100 mt-3">
								<label class="w-100" for="headline<?=$i?>">Headline</label>
								<input class="w-100" type="text" name="headline" id="headline<?=$i?>" value="<?=clean_display_string ($banner->get_headline())?>" />
								</div>
								<div class="w-100 mt-3">
								<label class="w-100" for="sub_headline<?=$i?>">Sub Headline</label>
								<input class="w-100" type="text" name="sub_headline" id="sub_headline<?=$i?>" value="<?=clean_display_string ($banner->get_sub_headline())?>" />
							</div>
							<div class="w-100 mt-3">
								<label class="w-100" for="url<?=$i?>">URL</label>
								<input class="w-100" type="text" value="<?=clean_display_string ($banner->get_url())?>" name="url" id="url<?=$i?>" />
							</div>
							<div class="w-100 mt-3">
								<label for="homepage<?=$i?>">Show on homepage</label>
								<input type="checkbox" value="<?=YES?>" <?=check_checked (true, $banner->get_homepage())?> name="homepage" id="homepage<?=$i?>" />
							</div>
							<div class="w-100 mt-4 mb-2">
								<input type="submit" name="upload" value="Update" />
							</fieldset>
						</form>
						<?php
					}
					?>
					<br />
					<div class="leftfloat"><a href="index.php">&laquo; Back</a></div>
					<div class="clearfix"></div>
					<br />
				</div>
			</div>

		</div>

		<?php
			include('parts/scripts.php');
		?>

	</body>
</html>
