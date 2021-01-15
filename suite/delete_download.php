<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/download.class.php");

session_start();

require_once ("application/includes/login.inc.php");

check_login();

# null = no save
# true = save ok
# false = save failed
$save_result = null;

$form_array = array (
						"id" => "",
						"title" => "",
					);

$validation_errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	foreach ($form_array as $name => $value) {
		if (array_key_exists ($name, $_POST)) {
			$form_array[$name] = trim ($_POST[$name]);
		}
	}

	if (array_key_exists ('submit', $_POST)) {
		$download = new download();
		if ($download->load_if_exists (intval ($form_array['id']))) {
			$form_array["id"] = $download->get_id();
			$form_array["title"] = $download->get_filename();
			$save_result = $download->delete(intval ($form_array['id']));
		} else {
			header ("location: /suite");
			exit;
		}
	}
} else {
	if (array_key_exists ("id", $_GET)) {
		$id = intval ($_GET['id']);
		$download = new download  ();
		$download->load ($id);
	} else {
		header ("location: menu.php");
		exit;
	}
	$form_array["id"] = $download->get_id();
	$form_array["title"] = $download->get_filename();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit PDF</title>
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
		<h1>Delete Document</h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="downloads.php">Download Library</a></li>
				<li class="breadcrumb-item active" aria-current="page">Delete Document</li>
			</ol>
		</nav>

		<div id="main" class="main">
			<?php
			if (!is_null ($save_result)) {
				?>
				<div id="saveResult">
					<?php
					if ($save_result) {
						?>
						<div class="alert alert-success" role="alert">
						<p class="mb-0">Delete Successful</p>
						</div>
						<?php
					} else {
						?>
						<div class="alert alert-danger" role="alert">
							<p class="mb-0">
						Delete Failed
					</p>
				</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<?php
			if (count ($validation_errors) > 0) {
				?>
				<div id="errors">
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
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
				<fieldset>

					<?php
					if (!is_null ($save_result)) {
						if ($save_result) {
							?>
							<div class="alert alert-success" role="alert">
								<p class="mb-0">The entry was deleted.</p>
							</div>
							<?php
						} else {
							?>
							<div class="alert alert-danger" role="alert">
							<p class="mb-0">There was a problem deleting the entry.</p>
							</div>
							<?php
						}
					} else {
						?>
						<div class="alert alert-danger" role="alert">
						<p>
							Are you sure you want to delete the following download?
						</p>
						<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
						<div class="leftfloat"><label for="title">Filename</label></div>
						<?=clean_display_string ($form_array['title'])?>
						<div class="clearfix"><br /></div>
						<div class="rightfloat">
							<p><input type="submit" name="submit" value="Delete" class="btn btn-danger" /></p>
						</div>
					</div>
						<?php
					}
					?>
				</fieldset>
			</form>
		</div>
	</div>
	</div>
		<?php
			include('parts/scripts.php');
		?>
	</body>
</html>
