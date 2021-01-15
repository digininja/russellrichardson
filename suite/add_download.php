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

$form_array = array (
						"id" => "",
						"title" => "",
						"summary" => "",
						"date" => format_date(time(), DATE_ONLY),
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

	$image_downloaded = false;
	if (array_key_exists ("file_download", $_FILES) && $_FILES['file_download']['error'] != UPLOAD_ERR_NO_FILE) {
		$filename = str_replace (" ", "-", $_FILES['file_download']['name']);

		if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/documents/" . $filename)) {
			$validation_errors[] = "File already exists";
		}

		if (count ($validation_errors) == 0) {
			$download = new download();
			$download->set_name($form_array['title']);
			$download->set_summary($form_array['summary']);
			$download->set_date(strtotime($form_array['date']));
			$download->set_filename($filename);
			$download->save();
			move_uploaded_file ($_FILES['file_download']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/userfiles/documents/" . $filename);
			header ("location: downloads.php");
			exit;
		}
	} else {
		$validation_errors[] = "Error downloading the file";
	}
} else {
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
		<h1>Add/Edit PDF</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/downloads.php">Downloads</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add/Edit PDF</li>
			</ol>
		</nav>

		<div id="main" class="main">

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
			<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="post">
				<fieldset>
					<legend>Upload File</legend>
					<hr>
					<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
					<div class="leftfloat"><label for="title">Title</label></div>
					<input class="w-100" type="text" name="title" id="title" value="<?=clean_display_string ($form_array['title'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="date">Date</label></div>
					<input class="w-100" type="text" name="date" id="date" value="<?=clean_display_string ($form_array['date'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="summary">Summary</label></div>
					<textarea class="w-100" name="summary" id="summary"><?=clean_display_string ($form_array['summary'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="file_download">File</label></div>
					<input type="file" name="file_download" id="file_download" />
					<div class="clearfix"></div>
					<div class="mt-4 mb-2"><input type="submit" name="submit" value="Save" /></div>
				</fieldset>
			</form>
			<div class="clearfix"></div>
		</div>

	</div>

	</div>
	<?php
	include('parts/scripts.php');
	?>
	</body>
</html>
