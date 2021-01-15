<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/upload.class.php");
require_once ("application/classes/upload_list.class.php");
require_once ("application/classes/cms_image_gallery.class.php");
require_once ("application/classes/cms_image_gallery_list.class.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/login.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/includes/error.inc.php");

session_start();

if (!check_login()) {
	header ("location: login.php");
	exit;
}

$upload_list = new upload_list();
$upload_list->set_order_by ("name");
$uploads = $upload_list->do_search();

$cms_image_gallery_list = new cms_image_gallery_list();
$cms_image_gallery_list->set_order_by ("name");
$cms_image_gallerys = $cms_image_gallery_list->do_search();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Images & Media Uploads</title>
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
			<h1>Images & Media Uploads</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Images & Media Uploads</li>
			</ol>
		</nav>

		<div id="main">
			<h2>Images</h2>
			<table class="table">
				<thead>
					<tr>
						<th>File</th>
						<th>URL</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($cms_image_gallerys as $cms_image_gallery) {
						$image = $cms_image_gallery->get_image();
						?>
						<tr>
							<td><img style="max-width:300px; height:auto; width:auto;" src="<?=clean_display_string ($image->get_src())?>" alt="<?=clean_display_string ($image->get_alt_text())?>" /></td>
							<td><?=clean_display_string ($image->get_src())?></td>
							<td class="edit"><a href="edit_image_gallery.php?id=<?=clean_display_string ($cms_image_gallery->get_id())?>">Edit</a></td>
							<td class="delete"><a href="delete_image_gallery.php?id=<?=clean_display_string ($cms_image_gallery->get_id())?>">Delete</a></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table><br>
			<div id="action_button">
				<a href="edit_image_gallery.php" class="add btn btn-primary">Add Image</a>
			</div>
			<hr>
			<h2>PDFs</h2>
			<hr>
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>URL</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($uploads as $upload) {
						?>
						<tr>
							<td><?=clean_display_string ($upload->get_name())?></td>
							<td>/userfiles/files/<?=clean_display_string ($upload->get_filename())?></td>
							<td class="delete"><a href="delete_upload.php?id=<?=clean_display_string ($upload->get_id())?>">Delete</a></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table><br>
			<div id="action_button" class="py-5">
				<a href="add_upload.php" class="add btn btn-primary">Add PDF</a>
			</div>
		</div>
	</div>

	<?php
		include('parts/scripts.php');
	?>

</body>
</html>
