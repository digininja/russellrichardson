<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/cms.class.php");
require_once ("application/classes/cms_list.class.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/login.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/includes/useful.inc.php");

session_start();

if (!check_login()) {
	header ("location: login.php");
	exit;
}

$cms_page_list = new cms_list();
$cms_page_list->set_order_by ("title");
$cms_pages = $cms_page_list->do_search();

//var_dump_pre ($navigation['/about']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Add/Edit Advice</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Description" content="Administration area for RR" />
		<meta name="Author" content="Robin Wood - Freedom Software" />
		<style type="text/css" media="screen">
			@import "/suite/style.css";
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
		<h1>CMS Pages</h1>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">CMS Pages</li>
			</ol>
		</nav>

		<div id="main">
			<table class="table">
				<thead>
					<tr>
						<th>Title</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($cms_pages as $cms_page) {
					?>
					<tr>
						<td><?=clean_display_string ($cms_page->get_title())?></td>
						<td><a href="edit_cms.php?cms_id=<?=clean_display_string ($cms_page->get_id())?>" class="edit">Edit</a></td>
						<?php
						if ($cms_page->get_id() > 5) {
							?>
							<td><a href="delete_cms.php?cms_id=<?=clean_display_string ($cms_page->get_id())?>" class="edit">Delete</a></td>
							<?php
						} else {
							?>
							<td>&nbsp;</td>
							<?php
						}
						?>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table><br>
			<a href="edit_cms.php" class="edit">Add</a>
		</div>
	</div>
</div>
<?php
	include('parts/scripts.php');
?>
</body>
</html>
