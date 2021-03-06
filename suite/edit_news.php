<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/image.class.php");
require_once ("application/classes/news_category_list.class.php");

session_start();

require_once ("application/includes/login.inc.php");

check_login();

$form_array = array (
						"id" => "",
						"name" => "",
						"summary" => "",
						"date" => "",
						"body" => "",
						"service_type" => "",
						"category_id" => "",
						"banner_id" => "",
						"url" => "",
						"quote" => "",
						"meta_title" => "",
						"meta_description" => "",
						"alt_text" => "",
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

	$news = new news();
	if ($form_array['id'] != "") {
		if (!$news->load_if_exists (intval ($form_array['id']))) {
			header ("location: news.php");
			exit;
		}
	}

	if ($form_array["name"] == "") {
		$validation_errors[] = "You must provide a name";
	}

	if (!is_numeric ($form_array["category_id"])) {
		$validation_errors[] = "You must select a category";
	}

	if (strtotime ($form_array["date"]) === false) {
		$validation_errors[] = "You must provide a valid date";
	}

	/*
	if ($form_array["url"] == "") {
		$validation_errors[] = "You must provide a URL";
	}
	if ($form_array["meta_title"] == "") {
		$validation_errors[] = "You must provide a meta title";
	}

	if ($form_array["meta_description"] == "") {
		$validation_errors[] = "You must provide a meta description";
	}
	*/

	$image = null;

	if ($form_array["alt_text"] == "") {
		$validation_errors[] = "You must provide a caption";
	} else {
		$image = $news->get_image();

		if (is_null ($news->get_id()) || is_null ($image)) {
			$image = new image();
		}

		$image_uploaded = false;
		if (array_key_exists ("image_upload", $_FILES) && $_FILES['image_upload']['error'] != UPLOAD_ERR_NO_FILE) {
			$image->set_status (STATUS_ACTIVE);

			if (!$image->create_from_upload ($_FILES['image_upload'])) {
				$validation_errors[] = "Invalid image uploaded";
			} else {
				$image_uploaded = true;
			}
		}
		$image->set_alt_text ($form_array['alt_text']);
		$image->set_description ($form_array['image_description']);

		$image->set_gallery_id (NEWS_GALLERY);

		if (!$image_uploaded && is_null ($image->get_id())) {
			$validation_errors[] = "No image uploaded";
		}
	}

	if (count ($validation_errors) == 0) {
		$news->set_summary ($form_array['summary']);
		$news->set_date (strtotime ($form_array['date']));
		$news->set_body ($form_array['body']);
		$news->set_name ($form_array['name']);
		$news->set_url ($form_array['url']);
		$news->set_quote ($form_array['quote']);
		$news->set_service_type ($form_array['service_type']);
		$news->set_meta_title ($form_array['meta_title']);
		$news->set_meta_description ($form_array['meta_description']);
		$news->set_category_id (intval($form_array['category_id']));
		$news->set_banner_id (intval($form_array['banner_id']));
		$news->set_status (STATUS_ACTIVE);

		if (!is_null ($image)) {
			$image->save();
			$news->set_image_id ($image->get_id());
		}

		$save_result = $news->save();

		$form_array['id'] = $news->get_id();
	} else {
	}
} else {
	$news = null;
	if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
		$id = intval ($_GET['id']);
		$news = new news();
		if (!$news->load_if_exists ($id)) {
			header ("location: news_cats.php");
			exit;
		}
	}
	if (!is_null ($news)) {
		$form_array['id'] = $news->get_id();
		$form_array['name'] = $news->get_name();
		$form_array['summary'] = $news->get_summary();
		$form_array['date'] = format_date($news->get_date());
		$form_array['body'] = $news->get_body();
		$form_array['meta_title'] = $news->get_meta_title();
		$form_array['url'] = $news->get_url();
		$form_array['meta_description'] = $news->get_meta_description();
		$form_array['category_id'] = $news->get_category_id();

		$image = $news->get_image();
		if (!is_null ($image)) {
			$form_array['alt_text'] = $image->get_alt_text();
			$form_array['image_description'] = $image->get_description();
		}
	} else {
		$image = null;
		$form_array['id'] = "";
		$form_array['name'] = "";
		$form_array['summary'] = "";
		$form_array['date'] = "";
		$form_array['body'] = "";
		$form_array['meta_title'] = "";
		$form_array['url'] = "";
		$form_array['meta_description'] = "";
		$form_array['category_id'] = "";
	}
}

$news_category_list = new news_category_list();
$news_category_list->set_order_by ("name");
$news_categories = $news_category_list->do_search();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit News Article</title>
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
		<h1>Add/Edit News Article</h1>
		</div>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/news.php">News Articles</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add/Edit News Article</li>
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
					<legend>Add/Edit News Article</legend>
						<hr>
					<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
					<div class="leftfloat"><label for="name">Title</label></div>
					<input class="w-100" type="text" name="name" id="name" value="<?=clean_display_string ($form_array['name'])?>" />
					<div class="clearfix"></div>
					<?php
					/*
					<div class="leftfloat"><label for="meta_title">Meta Title</label></div>
					<input type="text" name="meta_title" id="meta_title" value="<?=clean_display_string ($form_array['meta_title'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="meta_description">Meta Description</label></div>
					<textarea cols="50" rows="5" name="meta_description" id="meta_description"><?=clean_display_string ($form_array['meta_description'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="url">URL</label></div>
					<input type="text" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
					<div class="clearfix"></div>
					*/
					?>
					<div class="leftfloat mt-3"><label for="category_id">Category</label></div>
					<select id="category_id" name="category_id">
						<?php
						foreach ($news_categories as $news_category) {
							?>
							<option <?=select_selected ($news_category->get_id(), $form_array['category_id'])?> value="<?=clean_display_string ($news_category->get_id())?>"><?=clean_display_string ($news_category->get_name())?></option>
							<?php
						}
						?>
					</select>

					<div class="leftfloat mt-3"><label for="date">Date</label></div>
					<input class="w-100" type="text" name="date" id="date" value="<?=clean_display_string ($form_array['date'])?>" />
					<div class="clearfix"></div>

					<div class="leftfloat mt-3"><label for="summary">Summary</label></div>
					<textarea class="w-100" cols="50" rows="10" name="summary" id="summary"><?=clean_display_string ($form_array['summary'])?></textarea>
					<div class="clearfix"></div>

					<div class="leftfloat mt-3"><label for="body">Body</label></div>
					<textarea cols="50" rows="10" class="trumbowyg w-100" name="body" id="body"><?=clean_display_string ($form_array['body'])?></textarea>
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
					<textarea class="w-100" name="image_description" id="image_description"><?=clean_display_string ($form_array['image_description'])?></textarea>
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
