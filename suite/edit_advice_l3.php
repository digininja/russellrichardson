<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/advice_l2.class.php");
require_once ("application/classes/advice_l2.class.php");
require_once ("application/classes/advice_l3.class.php");
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
						"content_type" => "",
						"order" => "",
						"meta_description" => "",
						"alt_text" => "",
						"banner_alt_text" => "",
						"linked_advice_l3_ids" => [],
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

	$advice_l3 = new advice_l3();
	if ($form_array['id'] != "") {
		if (!$advice_l3->load_if_exists (intval ($form_array['id']))) {
			header ("location: advice_l3.php");
			exit;
		}
	}

	if ($form_array["name"] == "") {
		$validation_errors[] = "You must provide a name";
	}

	if ($form_array["meta_title"] == "") {
		$validation_errors[] = "You must provide a meta title";
	}
	if ($form_array["content_type"] == "") {
		$validation_errors[] = "You must provide a content type";
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
		$image = $advice_l3->get_image();

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
		$banner = $advice_l3->get_banner();

		if (is_null ($advice_l3->get_id()) || is_null ($banner)) {
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
		$advice_l3->set_summary ($form_array['summary']);
		$advice_l3->set_name ($form_array['name']);
		$advice_l3->set_meta_title ($form_array['meta_title']);
		$advice_l3->set_url ($form_array['url']);
		$advice_l3->set_content_type ($form_array['content_type']);
		$advice_l3->set_meta_description ($form_array['meta_description']);
		$advice_l3->set_body ($form_array['body']);
		$advice_l3->set_advice_l2_id (intval ($form_array['parent_id']));
		$advice_l3->set_status (STATUS_ACTIVE);

		if (!is_null ($banner)) {
			$banner->save();
			$advice_l3->set_banner_id ($banner->get_id());
		}

		if (!is_null ($image)) {
			$image->save();
			$advice_l3->set_image_id ($image->get_id());
		}

		$advice_l3->set_image_id ($image->get_id());
		$save_result = $advice_l3->save();

		$advice_l3->clear_linked_advice_l3s();

		foreach ($form_array['linked_advice_l3_ids'] as $linked_advice_l3_id) {
			$advice_l3->add_linked_advice_l3 (intval ($linked_advice_l3_id));
		}

		$form_array['id'] = $advice_l3->get_id();
	} else {
	}
} else {
	$advice_l3 = null;
	if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
		$id = intval ($_GET['id']);
		$advice_l3 = new advice_l3();
		if (!$advice_l3->load_if_exists ($id)) {
			header ("location: advice_l3_cats.php");
			exit;
		}
	} elseif (array_key_exists ("parent_id", $_GET) && is_numeric ($_GET['parent_id'])) {
		$parent_id = intval ($_GET['parent_id']);
		$parent_advice_l2 = new advice_l2();
		if (!$parent_advice_l2->load_if_exists ($parent_id)) {
			header ("location: advice_l3s.php");
			exit;
		}
		$form_array['parent_id'] = $parent_advice_l2->get_id();
	} else {
		header ("location: index.php");
		exit;
	}
	if (!is_null ($advice_l3)) {
		$form_array['id'] = $advice_l3->get_id();
		$form_array['name'] = $advice_l3->get_name();
		$form_array['summary'] = $advice_l3->get_summary();
		$form_array['meta_title'] = $advice_l3->get_meta_title();
		$form_array['url'] = $advice_l3->get_url();
		$form_array['content_type'] = $advice_l3->get_content_type();
		$form_array['meta_description'] = $advice_l3->get_meta_description();
		$form_array['body'] = $advice_l3->get_body();
		$form_array['parent_id'] = $advice_l3->get_advice_l2_id();
		$form_array['linked_advice_l3_ids'] = $advice_l3->get_linked_advice_l3_ids();

		$banner = $advice_l3->get_banner();
		if (!is_null ($banner)) {
			$form_array['banner_alt_text'] = $banner->get_alt_text();
		}

		$image = $advice_l3->get_image();
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
		$form_array['content_type'] = "";
		$form_array['order'] = "";
		$form_array['meta_description'] = "";
		$form_array['body'] = "";
		$banner = null;
	}
}


$linked_advice_l3_list = new advice_l3_list();
$linked_advice_l3_list->set_order_by ("name");
$linked_advice_l3s = $linked_advice_l3_list->do_search();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml2-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit Advice Article</title>
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
		<h1>Add/Edit Advice Article</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/advice_l1s.php">Advice Categories</a></li>
				<li class="breadcrumb-item"><a href="/suite/advice_l3s.php?parent_id=<?=clean_display_string ($form_array['parent_id'])?>">Advice Articles</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add/Edit Advice Article</li>
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
					<legend>Add/Edit Advice Article</legend>
					<hr>
					<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
					<input type="hidden" name="parent_id" value="<?=clean_display_string ($form_array['parent_id'])?>" />
					<div class="leftfloat"><label for="name">Title</label></div>
					<input class="w-100" type="text" name="name" id="name" value="<?=clean_display_string ($form_array['name'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="meta_title">Meta Title</label></div>
					<input class="w-100" type="text" name="meta_title" id="meta_title" value="<?=clean_display_string ($form_array['meta_title'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="meta_description">Meta Description</label></div>
					<textarea class="w-100" cols="50" rows="5" name="meta_description" id="meta_description"><?=clean_display_string ($form_array['meta_description'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="content_type">Content Type</label></div>
					<select name="content_type" id="content_type">
						<option value="guide" <?= select_selected ("guide", $form_array['content_type'])?>>Guide</option>
						<option value="faq" <?= select_selected ("faq", $form_array['content_type'])?>>FAQ</option>
						<option value="research" <?= select_selected ("research", $form_array['content_type'])?>>Research</option>
						<option value="other" <?= select_selected ("other", $form_array['content_type'])?>>Other</option>
					</select>
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="url">URL</label></div>
					/advice/3/<input type="text" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="body">Body</label></div>
					<textarea cols="50" rows="10" class="trumbowyg w-100" name="body" id="body"><?=clean_display_string ($form_array['body'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="summary">Summary</label></div>
					<textarea class="w-100" cols="50" rows="10" name="summary" id="summary"><?=clean_display_string ($form_array['summary'])?></textarea>
					<div class="clearfix"></div>
					<hr>
					<h3>Linked Articles</h3>
					<hr>
					<ul class="list-group">
						<?php
						foreach ($linked_advice_l3s as $linked_advice_l3) {
							if ($linked_advice_l3->get_id() == $form_array['id']) {
								continue;
							}
							?>
							<li class="list-group-item"><input name="linked_advice_l3_ids[]" id="linked_advice_l3_id_<?=clean_display_string ($linked_advice_l3->get_id())?>" type="checkbox" <?=check_if_in_array ($form_array['linked_advice_l3_ids'], $linked_advice_l3->get_id())?> value="<?=clean_display_string ($linked_advice_l3->get_id())?>"><label class="mb-0" for="linked_advice_l3_id_<?=clean_display_string ($linked_advice_l3->get_id())?>"> &nbsp; <?= clean_display_string ($linked_advice_l3->get_name())?></label></li>
							<?php
						}
						?>
					</ul>
					<h3>Banner</h3>
					<hr>
					<div class="leftfloat"><label for="banner_upload">Image File</label></div>
						<input type="file" name="banner_upload" id="banner_upload" />
						<div class="clearfix"></div>
					<?php
					if (!is_null ($banner)) {
						?>
						<p><img class="w-100 mt-3" src="<?=clean_display_string ($banner->get_src())?>" alt="<?=clean_display_string ($banner->get_alt_text())?>" /></p>
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
						<p><img class="w-100 mt-3" src="<?=clean_display_string ($image->get_src())?>" alt="<?=clean_display_string ($image->get_alt_text())?>" /></p>
						<?php
					}
					?>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="alt_text">Image Caption</label></div>
					<input class="w-100" type="text" name="alt_text" id="alt_text" style="width:500px" value="<?=clean_display_string ($form_array['alt_text'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="image_description">Image Description</label></div>
					<textarea  class="w-100"name="image_description" id="image_description"><?=clean_display_string ($form_array['image_description'])?></textarea>
					<div class="clearfix"></div>

					<div id="action_button" class="mt-4 mb-2"><input type="submit" name="add" value="Save" /></div>
				</fieldset>
			</form>
			<div class="clearfix"></div>
		</div>
	</div>
	<?php
		include('parts/scripts.php');
	?>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>

		<script src="/suite/trumbowyg/trumbowyg.js"></script>
		<script>
			/** Default editor configuration **/
			$('.trumbowyg').trumbowyg();
		</script>
	</body>
</html>
