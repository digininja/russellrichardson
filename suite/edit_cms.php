<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/cms.class.php");
require_once ("application/includes/login.inc.php");
#require_once ("application/classes/cms_cms_image_gallery_list.class.php");
#require_once ("application/classes/cms_image_gallery_list.class.php");

session_start();

if (!check_login()) {
	header ("location: login.php");
	exit;
}

$form_array = array (
						"id" => "",
						"title" => "",
						"sitemap_change_freq" => "never",
						"weight" => "0",
						"url" => "",
						"meta_title" => "",
						"body" => "",
						"meta_description" => "",
						"meta_keywords" => "",
						"sitemap_priority" => "0",
						"summary" => "",
						"tagline" => "",
						"cms_gallery_id" => [],
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

	if ($form_array["sitemap_change_freq"] == "") {
		$validation_errors[] = "You must provide a sitemap_change_freq";
	}
	if ($form_array["title"] == "") {
		$validation_errors[] = "You must provide a title";
	}
	if ($form_array["body"] == "") {
		$validation_errors[] = "You must provide a body";
	}
	if (!is_numeric ($form_array["weight"])) {
		$validation_errors[] = "Please provide a numeric weight";
	}

	if (!is_numeric ($form_array["sitemap_priority"])) {
		$validation_errors[] = "Please provide a sitemap priority between 0 and 1";
	} elseif ($form_array["sitemap_priority"] < 0 || $form_array["sitemap_priority"] > 1) {
		$validation_errors[] = "Please provide a sitemap priority between 0 and 1";
	}

	$cms = new cms();

	if ($form_array['id'] != "") {
		$cms->load_if_exists (intval ($form_array['id']));
	}

	if (count ($validation_errors) == 0) {
		$cms->set_title ($form_array['title']);
		$cms->set_sitemap_change_freq ($form_array['sitemap_change_freq']);
		$cms->set_meta_title ($form_array['meta_title']);
		$cms->set_body ($form_array['body']);
		$cms->set_meta_description ($form_array['meta_description']);
		$cms->set_meta_keywords ($form_array['meta_keywords']);
		$cms->set_url ($form_array['url']);
		$cms->set_sitemap_priority (floatval ($form_array['sitemap_priority']));
		$cms->set_weight (intval ($form_array['weight']));
		$cms->set_summary ("unused");
		$cms->set_tagline ($form_array['tagline']);
		$cms->set_date (time());
		$cms->set_start_date (null);
		$cms->set_end_date (null);
		$cms->set_status (STATUS_ACTIVE);
		$cms->set_type_id (1);
		$cms->set_thumb_image_id (1);
		$cms->set_to_homepage ("no");

		$save_result = $cms->save();

		/*
		cms_cms_image_gallery::clear_for_cms($cms->get_id());

		foreach ($form_array['cms_gallery_id'] as $cms_image_gallery_id) {
			$cms_cms_image_gallery = new cms_cms_image_gallery();
			$cms_cms_image_gallery->set_cms_image_gallery_id (intval ($cms_image_gallery_id));
			$cms_cms_image_gallery->set_cms_id ($cms->get_id());
			$cms_cms_image_gallery->save();
		}
*/
		if (array_key_exists ("new", $_POST)) {
			// if they hit save and add new then reset the array so there is a blank page
			// for the next item
			$form_array = array (
									"id" => "",
									"title" => "",
									"sitemap_change_freq" => "never",
									"url" => "",
									"meta_title" => "",
									"body" => "",
									"meta_description" => "",
									"meta_keywords" => "",
									"sitemap_priority" => "0",
									"weight" => "0",
									"summary" => "",
									"tagline" => "",
								);
		} else {
			$form_array["id"] = $cms->get_id();
		}
	} else {
	}
} else {
	$cms = new cms();

	if (array_key_exists ("cms_id", $_GET) && is_numeric ($_GET["cms_id"])) {
		$id = intval ($_GET['cms_id']);
		if ($cms->load_if_exists ($id)) {
		} else {
			header ("location: index.php");
			exit;
		}
	} else {
	#	header ("location: index.php");
	#	exit;
	}

	$form_array["id"] = $cms->get_id();
	$form_array['title'] = $cms->get_title ();
	$form_array['sitemap_change_freq'] = $cms->get_sitemap_change_freq ();
	$url = $cms->get_url ();
	$form_array['url'] = $url;
	$form_array['meta_title'] = $cms->get_meta_title ();
	$form_array['body'] = $cms->get_body ();
	$form_array['meta_description'] = $cms->get_meta_description ();
	$form_array['meta_keywords'] = $cms->get_meta_keywords ();
	$form_array['sitemap_priority'] = $cms->get_sitemap_priority ();
	$form_array['weight'] = $cms->get_weight ();
	$form_array['summary'] = $cms->get_summary ();
	$form_array['tagline'] = $cms->get_tagline ();
}

/*
$cms_image_gallery_list = new cms_image_gallery_list();
$cms_image_gallery_list->set_order_by ("name");
$cms_image_galleries = $cms_image_gallery_list->do_search();
$form_array['cms_image_gallery_ids'] = $cms->get_image_gallery_ids();
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Add/Edit CMS Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Description" content="Administration area for RR" />
		<meta name="Author" content="Robin Wood - Freedom Software" />
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="ckfinder/ckfinder.js"></script>
		<style type="text/css" media="screen">
			@import "/suite/style.css";
		</style>		
<?php
		include('parts/styles.php');
		?>
        <link rel="stylesheet" href="/suite/trumbowyg/ui/trumbowyg.min.css">
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


		<h1>Add/Edit CMS Page</h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/cms_pages.php">CMS Pages</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit CMS Page</li>
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

				<form action="<?=clean_display_string ($_SERVER['PHP_SELF'])?>" method="post">
					<fieldset>
						<legend>Add/Edit CMS</legend>
						<hr>
						<input type="hidden" name="id" value="<?=clean_display_string ($form_array['id'])?>" />
						<div class="leftfloat"><label for="meta_title">Meta Title</label></div>
						<input class="w-100" type="text" name="meta_title" id="meta_title" value="<?=clean_display_string ($form_array['meta_title'])?>" />
						<div class="clearfix"></div>
						<div class="leftfloat mt-3"><label for="meta_description">Meta Description</label></div>
						<textarea class="w-100" rows="10" cols="27" name="meta_description" id="meta_description_nojs"><?=clean_display_string ($form_array['meta_description'])?></textarea>
						<div class="clearfix"></div>
						<div class="leftfloat mt-3"><label for="title">Title</label></div>
						<input class="w-100" type="text" name="title" id="title" value="<?=clean_display_string ($form_array['title'])?>" />
						<div class="clearfix mt-3"></div>
						<div class="leftfloat mt-3"><label for="url">URL</label></div>
							<?php
							if ($form_array['id'] == "" || $form_array['id'] > 5) {
								?>
								/cms/<input class="w-100" type="text" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
								<?php
							} else {
								?>
								<input disabled="disabled" class="w-100" type="text" name="urlx" id="urlx" value="<?=clean_display_string ($form_array['url'])?>" />
								<input type="hidden" name="url" id="url" value="<?=clean_display_string ($form_array['url'])?>" />
								<?php
							}
							?>

							<div class="clearfix mt-3"></div>
						<div class="leftfloat"><label for="body">Body</label></div>
						<textarea rows="10" cols="27" name="body" id="body" class="w-100 large trumbowyg"><?=clean_display_string ($form_array['body'])?></textarea>
						<div class="clearfix"></div>
					<?php
					/*
					<h3>Images</h3>
					<?php
					foreach ($cms_image_galleries as $cms_image_gallery) {
						$image = $cms_image_gallery->get_image();
						?>
						<input type="checkbox" <?=check_if_in_array ($form_array['cms_image_gallery_ids'], $cms_image_gallery->get_id())?> name="cms_gallery_id[]" id="cms_gallery_id_<?=clean_display_string ($cms_image_gallery->get_id())?>" value="<?=clean_display_string ($cms_image_gallery->get_id())?>"><label for="cms_gallery_id_<?=clean_display_string ($cms_image_gallery->get_id())?>"><?=clean_display_string ($image->get_alt_text())?></label>
						<?php
					}
					?>
					*/
					?>

						<div id="action_button" class="mt-4 mb-2"><input type="submit" name="submit" value="Save" /></div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.2.1.min.js"><\/script>')</script>

(??)

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
