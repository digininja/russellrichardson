<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/case_study_category.class.php");
require_once ("application/classes/case_study.class.php");
require_once ("application/classes/image.class.php");

session_start();

require_once ("application/includes/login.inc.php");

check_login();

$form_array = array (
						"id" => "",
						"name" => "",
						"parent_id" => "",
						"service_type" => "",
						"body" => "",
						"summary" => "",
						"quote" => "",
						"url" => "",
						"quote_name" => "",
						"meta_title" => "",
						"meta_description" => "",
						"alt_text" => "",
						"banner_alt_text" => "",
						"logo_alt_text" => "",
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

	$case_study = new case_study();
	if ($form_array['id'] != "") {
		if (!$case_study->load_if_exists (intval ($form_array['id']))) {
			header ("location: case_study.php");
			exit;
		}
	}

	if ($form_array["name"] == "") {
		$validation_errors[] = "You must provide a name";
	}

	/*
	if ($form_array["meta_title"] == "") {
		$validation_errors[] = "You must provide a meta title";
	}
	if ($form_array["meta_description"] == "") {
		$validation_errors[] = "You must provide a meta description";
	}

	if ($form_array["url"] == "") {
		$validation_errors[] = "You must provide a URL";
	}
	*/
	if ($form_array["service_type"] == "") {
		$validation_errors[] = "You must provide a service type";
	}
	if ($form_array["quote"] == "") {
		$validation_errors[] = "You must provide a quote";
	}
	if ($form_array["quote_name"] == "") {
		$validation_errors[] = "You must provide a name for the quote";
	}

	$image = null;

/*
	if ($form_array["alt_text"] == "") {
		$validation_errors[] = "You must provide a caption";
	} else {
		$image = $case_study->get_image();

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
*/

	$logo = null;

	if ($form_array["logo_alt_text"] == "") {
		$validation_errors[] = "You must provide a caption for the logo";
	} else {
		$logo = $case_study->get_logo();

		if (is_null ($case_study->get_id()) || is_null ($logo)) {
			$logo = new image();
		}

		$logo_uploaded = false;
		if (array_key_exists ("logo_upload", $_FILES) && $_FILES['logo_upload']['error'] != UPLOAD_ERR_NO_FILE) {
			$logo->set_status (STATUS_ACTIVE);

			if (!$logo->create_from_upload ($_FILES['logo_upload'])) {
				$validation_errors[] = "Invalid logo uploaded";
			} else {
				$logo_uploaded = true;
			}
		}
		$logo->set_alt_text ($form_array['logo_alt_text']);
		$logo->set_description ($form_array['logo_alt_text']);
		$logo->set_gallery_id (CATEGORY_GALLERY);

		if (!$logo_uploaded && is_null ($logo->get_id())) {
			$validation_errors[] = "No logo uploaded";
		}
	}

	$banner = null;

/*
	if ($form_array["banner_alt_text"] == "") {
		$validation_errors[] = "You must provide a caption for the banner";
	} else {
		$banner = $case_study->get_banner();

		if (is_null ($case_study->get_id()) || is_null ($banner)) {
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
*/

	if (count ($validation_errors) == 0) {
		$case_study->set_summary ($form_array['summary']);
		$case_study->set_quote ($form_array['quote']);
		$case_study->set_name ($form_array['name']);
		$case_study->set_meta_title ($form_array['meta_title']);
		$case_study->set_url ($form_array['url']);
		$case_study->set_quote_name ($form_array['quote_name']);
		$case_study->set_service_type ($form_array['service_type']);
		$case_study->set_meta_description ($form_array['meta_description']);
		$case_study->set_body ($form_array['body']);
		$case_study->set_category_id (intval ($form_array['parent_id']));
		$case_study->set_status (STATUS_ACTIVE);

		if (!is_null ($logo)) {
			$logo->save();
			$case_study->set_logo_id ($logo->get_id());
		}

		if (!is_null ($banner)) {
			$banner->save();
			$case_study->set_banner_id ($banner->get_id());
		}

		if (!is_null ($image)) {
			$image->save();
			$case_study->set_image_id ($image->get_id());
		}

		$case_study->set_banner_id (-1);
		$case_study->set_image_id (-1);
		$save_result = $case_study->save();

		/*
		$case_study->clear_accreditations();

		foreach ($form_array['accreditation_ids'] as $accreditation_id) {
			$case_study->add_accreditation (intval ($accreditation_id));
		}

		case_study_image_gallery::clear_for_case_study($case_study->get_id());

		foreach ($form_array['image_gallery_id'] as $image_gallery_id) {
			$case_study_image_gallery = new case_study_image_gallery();
			$case_study_image_gallery->set_image_gallery_id (intval ($image_gallery_id));
			$case_study_image_gallery->set_case_study_id ($case_study->get_id());
			$case_study_image_gallery->save();
		}
		*/
		$form_array['id'] = $case_study->get_id();
	} else {
	}
} else {
	$case_study = null;
	if (array_key_exists ("id", $_GET) && is_numeric ($_GET['id'])) {
		$id = intval ($_GET['id']);
		$case_study = new case_study();
		if (!$case_study->load_if_exists ($id)) {
			header ("location: case_study_cats.php");
			exit;
		}
	} elseif (array_key_exists ("parent_id", $_GET) && is_numeric ($_GET['parent_id'])) {
		$parent_id = intval ($_GET['parent_id']);
		$case_study_category = new case_study_category();
		if (!$case_study_category->load_if_exists ($parent_id)) {
			header ("location: case_studies.php");
			exit;
		}
		$form_array['parent_id'] = $case_study_category->get_id();
	} else {
		header ("location: index.php");
		exit;
	}
	if (!is_null ($case_study)) {
		$form_array['id'] = $case_study->get_id();
		$form_array['name'] = $case_study->get_name();
		$form_array['summary'] = $case_study->get_summary();
		$form_array['quote'] = $case_study->get_quote();
		$form_array['meta_title'] = $case_study->get_meta_title();
		$form_array['url'] = $case_study->get_url();
		$form_array['quote_name'] = $case_study->get_quote_name();
		$form_array['service_type'] = $case_study->get_service_type();
		$form_array['meta_description'] = $case_study->get_meta_description();
		$form_array['body'] = $case_study->get_body();
		$form_array['parent_id'] = $case_study->get_category_id();

		$logo = $case_study->get_logo();
		if (!is_null ($logo)) {
			$form_array['logo_alt_text'] = $logo->get_alt_text();
		}

		$banner = $case_study->get_banner();
		if (!is_null ($banner)) {
			$form_array['banner_alt_text'] = $banner->get_alt_text();
		}

		$image = $case_study->get_image();
		if (!is_null ($image)) {
			$form_array['alt_text'] = $image->get_alt_text();
			$form_array['image_description'] = $image->get_description();
		}
	} else {
		$image = null;
		$form_array['id'] = "";
		$form_array['name'] = "";
		$form_array['quote'] = "";
		$form_array['meta_title'] = "";
		$form_array['url'] = "";
		$form_array['quote_name'] = "";
		$form_array['service_type'] = "";
		$form_array['order'] = "";
		$form_array['meta_description'] = "";
		$form_array['body'] = "";
		$banner = null;
		$logo = null;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit Case Study</title>
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
		<h1>Add/Edit Case Study</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/case_study_categories.php">Case Study Categories</a></li>
				<li class="breadcrumb-item"><a href="/suite/case_studies.php?parent_id=<?=clean_display_string ($form_array['parent_id'])?>">Case Study</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add/Edit Case Study</li>
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
					<legend>Add/Edit Category</legend>
					<hr>
					<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
					<input type="hidden" name="parent_id" value="<?=clean_display_string ($form_array['parent_id'])?>" />
					<div class="leftfloat"><label for="name">Title</label></div>
					<input class="w-100" type="text" name="name" id="name" value="<?=clean_display_string ($form_array['name'])?>" />
					<div class="clearfix"></div>
					<?php /*
					<div class="leftfloat"><label for="meta_title">Meta Title</label></div>
					<input type="text" name="meta_title" id="meta_title" value="<?=clean_display_string ($form_array['meta_title'])?>" />
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="meta_description">Meta Description</label></div>
					<textarea cols="50" rows="5" name="meta_description" id="meta_description"><?=clean_display_string ($form_array['meta_description'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat"><label for="url">URL</label></div>
					<input type="text" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
					<div class="clearfix"></div>
					*/ ?>

					<div class="leftfloat mt-3"><label for="summary">Summary</label></div>
					<textarea class="w-100" cols="50" rows="10" name="summary" id="summary"><?=clean_display_string ($form_array['summary'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="service_type">Service Type</label></div>
					<input class="w-100" type="text" name="service_type" id="service_type" value="<?=clean_display_string ($form_array['service_type'])?>" />
					<div class="clearfix"></div>

					<div class="leftfloat mt-3"><label for="body">Body</label></div>
					<textarea cols="50" rows="10" class="w-100 trumbowyg" name="body" id="body"><?=clean_display_string ($form_array['body'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="quote">Quote</label></div>
					<textarea class="w-100" cols="50" rows="10" name="quote" id="quote"><?=clean_display_string ($form_array['quote'])?></textarea>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="quote_name">Name for quote</label></div>
					<input class="w-100" type="text" name="quote_name" id="quote_name" value="<?=clean_display_string ($form_array['quote_name'])?>" />
					<div class="clearfix"></div>
					<hr>
					<h3>Logo</h3>
					<hr>
					<div class="leftfloat"><label for="logo_upload">Image File</label></div>
						<input type="file" name="logo_upload" id="logo_upload" />
						<div class="clearfix"></div>
					<?php
					if (!is_null ($logo)) {
						?>
						<p><img class="mt-3 w-100" src="<?=clean_display_string ($logo->get_src())?>" alt="<?=clean_display_string ($logo->get_alt_text())?>" /></p>
						<?php
					}
					?>
					<div class="clearfix"></div>
					<div class="leftfloat mt-3"><label for="logo_alt_text">Image Caption</label></div>
					<input class="w-100" type="text" name="logo_alt_text" id="logo_alt_text" style="width:500px" value="<?=clean_display_string ($form_array['logo_alt_text'])?>" />
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
