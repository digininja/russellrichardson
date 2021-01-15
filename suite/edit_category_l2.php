<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/category_l1.class.php");
require_once ("application/classes/category_l2.class.php");
require_once ("application/classes/image.class.php");

session_start();

require_once ("application/includes/login.inc.php");

check_login();

$form_array = array (
						"id" => "",
						"name" => "",
						"summary" => "",
						"body" => "",
						"parent_id" => "",
						"meta_title" => "",
						"url" => "",
						"order" => "",
						"meta_description" => "",
						"alt_text" => "",
						"banner_alt_text" => "",
						"image_description" => "",
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

	$category_l2 = new category_l2();
	if ($form_array['id'] != "") {
		if (!$category_l2->load_if_exists (intval ($form_array['id']))) {
			header ("location: category_l2.php");
			exit;
		}
	}

	if ($form_array["name"] == "") {
		$validation_errors[] = "You must provide a name";
	}

	if ($form_array["meta_title"] == "") {
		$validation_errors[] = "You must provide a meta title";
	}

	if ($form_array["url"] == "") {
		$validation_errors[] = "You must provide a URL";
	}
	
	if ($form_array["meta_description"] == "") {
		$validation_errors[] = "You must provide a meta description";
	}

	$image = null;

	if ($form_array["alt_text"] == "") {
		$validation_errors[] = "You must provide a caption";
	} else {
		$image = $category_l2->get_image();

		if (is_null ($image)) {
			$image = new image();
		}

		$image_uploaded = false;
		if (array_key_exists ("image_upload", $_FILES) && $_FILES['image_upload']['error'] == UPLOAD_ERR_OK) {
			$image->set_status (STATUS_ACTIVE);

			if (!$image->create_from_upload ($_FILES['image_upload'])) {
				$validation_errors[] = "Invalid image uploaded";
			} else {
				$image_uploaded = true;
			}
		}
		$image->set_alt_text ($form_array['alt_text']);
		$image->set_description ($form_array['image_description']);
		$image->set_gallery_id (CATEGORY_GALLERY);

		if (!$image_uploaded && is_null ($image->get_id())) {
			$validation_errors[] = "No image uploaded";
		}
	}

	$banner = null;

	if ($form_array["banner_alt_text"] == "") {
		$validation_errors[] = "You must provide a caption for the banner";
	} else {
		$banner = $category_l2->get_banner();

		if (is_null ($category_l2->get_id()) || is_null ($banner)) {
			$banner = new image();
		}

		$banner_uploaded = false;
		if (array_key_exists ("banner_upload", $_FILES) && $_FILES['banner_upload']['error'] != UPLOAD_ERR_NO_FILE) {
			$banner->set_status (STATUS_ACTIVE);

			if (!$banner->create_from_upload ($_FILES['banner_upload'])) {
				$validation_errors[] = "Invalid banner uploaded";
			} else {
				$banner_uploaded = true;
			}
		}
		$banner->set_alt_text ($form_array['banner_alt_text']);
		$banner->set_description ($form_array['banner_alt_text']);
		$banner->set_gallery_id (CATEGORY_GALLERY);

		if (!$banner_uploaded && is_null ($banner->get_id())) {
			$validation_errors[] = "No banner uploaded";
		}
	}

	if (count ($validation_errors) == 0) {
		$category_l2->set_summary ($form_array['summary']);
		$category_l2->set_name ($form_array['name']);
		$category_l2->set_meta_title ($form_array['meta_title']);
		$category_l2->set_url ($form_array['url']);
		$category_l2->set_meta_description ($form_array['meta_description']);
		$category_l2->set_body ($form_array['body']);
		$category_l2->set_category_l1_id (intval ($form_array['parent_id']));
		$category_l2->set_status (STATUS_ACTIVE);

		if (!is_null ($banner)) {
			$banner->save();
			$category_l2->set_banner_id ($banner->get_id());
		}

		if (!is_null ($image)) {
			$image->save();
			$category_l2->set_image_id ($image->get_id());
		}

		$category_l2->set_image_id ($image->get_id());
		$save_result = $category_l2->save();

		/*
		$category_l2->clear_accreditations();

		foreach ($form_array['accreditation_ids'] as $accreditation_id) {
			$category_l2->add_accreditation (intval ($accreditation_id));
		}

		category_l2_image_gallery::clear_for_category_l2($category_l2->get_id());

		foreach ($form_array['image_gallery_id'] as $image_gallery_id) {
			$category_l2_image_gallery = new category_l2_image_gallery();
			$category_l2_image_gallery->set_image_gallery_id (intval ($image_gallery_id));
			$category_l2_image_gallery->set_category_l2_id ($category_l2->get_id());
			$category_l2_image_gallery->save();
		}
		*/
		$form_array['id'] = $category_l2->get_id();
	} else {
	}
} else {
	$category_l2 = null;
	if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
		$id = intval ($_GET['id']);
		$category_l2 = new category_l2();
		if (!$category_l2->load_if_exists ($id)) {
			header ("location: category_l2_cats.php");
			exit;
		}
	} elseif (array_key_exists ("parent_id", $_GET) && is_numeric ($_GET['parent_id'])) {
		$parent_id = intval ($_GET['parent_id']);
		$parent_category_l1 = new category_l1();
		if (!$parent_category_l1->load_if_exists ($parent_id)) {
			header ("location: category_l2s.php");
			exit;
		}
		$form_array['parent_id'] = $parent_category_l1->get_id();
	} else {
		header ("location: index.php");
		exit;
	}
	if (!is_null ($category_l2)) {
		$form_array['id'] = $category_l2->get_id();
		$form_array['name'] = $category_l2->get_name();
		$form_array['summary'] = $category_l2->get_summary();
		$form_array['meta_title'] = $category_l2->get_meta_title();
		$form_array['url'] = $category_l2->get_url();
		$form_array['meta_description'] = $category_l2->get_meta_description();
		$form_array['body'] = $category_l2->get_body();
		$form_array['parent_id'] = $category_l2->get_category_l1_id();

		$banner = $category_l2->get_banner();
		if (!is_null ($banner)) {
			$form_array['banner_alt_text'] = $banner->get_alt_text();
		}

		$image = $category_l2->get_image();
		if (!is_null ($image)) {
			$form_array['alt_text'] = $image->get_alt_text();
			$form_array['image_description'] = $image->get_description();
		}
	} else {
		$image = null;
		$form_array['id'] = "";
		$form_array['name'] = "";
		$form_array['summary'] = "";
		$form_array['meta_title'] = "";
		$form_array['url'] = "";
		$form_array['order'] = "";
		$form_array['meta_description'] = "";
		$form_array['body'] = "";
		$banner = null;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit Level 2 Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Description" content="Administration area for RR" />
		<meta name="Author" content="Robin Wood - Freedom Software" />
		<style type="text/css" media="screen">
			@import "style.css";
		</style>
		<?php
			include('parts/styles.php');
		?>
		<link rel="stylesheet" href="/suite/trumbowyg/ui/trumbowyg.min.css">
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
		<h1>Add/Edit Level 2 Service</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/category_l1s.php">Services</a></li>
				<li class="breadcrumb-item"><a href="/suite/category_l2s.php?parent_id=<?=clean_display_string ($form_array['parent_id'])?>">Level 2 Services</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add/Edit Service</li>
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
							<p class="mb-0">Save Successful</p>
						</div>
						<?php
					} else {
						?>
						<div class="alert alert-danger" role="alert">
							<p class="mb-0">Save Failed</p>
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
			<form enctype="multipart/form-data" action="<?=clean_display_string ($_SERVER['PHP_SELF'])?>" method="post">
				<fieldset>
					<legend>Add/Edit Service</legend>
					<hr>
					<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
					<input type="hidden" name="parent_id" value="<?=clean_display_string ($form_array['parent_id'])?>" />
					<div class="leftfloat"><label for="name">Title</label></div>
					<input class="w-100" type="text" name="name" id="name" value="<?=clean_display_string ($form_array['name'])?>" />

					<div class="leftfloat mt-3"><label for="meta_title">Meta Title</label></div>
					<input class="w-100" type="text" name="meta_title" id="meta_title" value="<?=clean_display_string ($form_array['meta_title'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="meta_description">Meta Description</label></div>
					<textarea class="w-100" cols="50" rows="5" name="meta_description" id="meta_description"><?=clean_display_string ($form_array['meta_description'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="url">URL</label></div>
					/service/2/<input type="text" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="body">Body</label></div>
					<textarea cols="50" rows="10" class="trumbowyg" name="body" id="body"><?=clean_display_string ($form_array['body'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="summary">Summary</label></div>
					<textarea class="w-100" cols="50" rows="10" name="summary" id="summary"><?=clean_display_string ($form_array['summary'])?></textarea>
					<div class="clearfix"></div>
					<hr>
					<h3>Banner</h3>
					<hr>
					<div class="leftfloat"><label for="banner_upload">Image File</label></div>
						<input type="file" name="banner_upload" id="banner_upload" />
						<div class="clearfix"></div>
					<?php
					if (!is_null ($banner)) {
						?>
						<p><img class="w-100 mt-3" src="<?=clean_display_string ($banner->get_src())?>" width="<?=clean_display_string ($banner->get_width())?>" height="<?=clean_display_string ($banner->get_height())?>" alt="<?=clean_display_string ($banner->get_alt_text())?>" /></p>
						<?php
					}
					?>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="banner_alt_text">Image Caption</label></div>
					<input class="w-100" type="text" name="banner_alt_text" id="banner_alt_text" style="width:500px" value="<?=clean_display_string ($form_array['banner_alt_text'])?>" />
					<div class="clearfix"></div>
					<hr>
					<h3>Image</h3>
					<hr>
					<div class="leftfloat"><label for="image_upload">Image File</label></div>
						<input type="file" name="image_upload" id="image_upload" />
						<div class="clearfix"></div>
					<?php
					if (!is_null ($image)) {
						?>
						<p><img class="w-100 mt-3" src="<?=clean_display_string ($image->get_src())?>" width="<?=clean_display_string ($image->get_width())?>" height="<?=clean_display_string ($image->get_height())?>" alt="<?=clean_display_string ($image->get_alt_text())?>" /></p>
						<?php
					}
					?>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="alt_text">Image Caption</label></div>
					<input class="w-100" type="text" name="alt_text" id="alt_text" style="width:500px" value="<?=clean_display_string ($form_array['alt_text'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="image_description">Image Description</label></div>
					<textarea class="w-100" name="image_description" id="image_description"><?=clean_display_string ($form_array['image_description'])?></textarea>
					<div class="clearfix"></div>

					<div id="action_button" class="mt-4 mb-2"><input type="submit" name="add" value="Save" /></div>
				</fieldset>
			</form>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>

		<script src="/suite/trumbowyg/trumbowyg.js"></script>
		<script>
			/** Default editor configuration **/
			$('.trumbowyg').trumbowyg();
		</script>
		<?php
			include('parts/scripts.php');
		?>
	</body>
</html>
