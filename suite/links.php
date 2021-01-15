<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/login.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/link.class.php");

session_start();

check_login();

$form_array = array (
						'position' => '',
						'name' => '',
						'url' => '',
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

	if ($form_array['name'] != "" && $form_array['url'] != "") {
		$link = new link();
		$link->load_if_exists ($form_array['position']);
		$link->set_title ($form_array['name']);
		$link->set_url ($form_array['url']);
		$link->set_id ($form_array['position']);
		$link->save();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Links</title>
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
	<body><div class="container">


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
			<h1>Links</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Links</li>
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
			for ($i = 1;$i < 6;$i++) {
				$link = new link();
				if ($link->load_if_exists ($i)) {
				}
				?>
				<hr>
				<form class="mb-5" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="post">
					<fieldset>
						<h2>Link <?=$i?></h2>
						<hr>
						<input type="hidden" value="<?=clean_display_string ($i)?>" name="position" />
						<label for="name<?=$i?>">Title</label>
						<input  class="w-100"type="text" name="name" id="name<?=$i?>" value="<?=clean_display_string ($link->get_title())?>" />
						<label class="mt-3" for="url<?=$i?>">URL</label>
						<input class="w-100" type="text" value="<?=clean_display_string ($link->get_url())?>" name="url" id="url<?=$i?>" />
						<input class="mt-3" type="submit" name="upload" value="Update" />
					</fieldset>
				</form>
				<?php
			}
			?>
		</div>
		</div>
		<?php
			include('parts/scripts.php');
		?>
	</body>
</html>
