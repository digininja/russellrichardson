<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/news_category.class.php");
require_once ("application/classes/news_category_list.class.php");
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

$news_category_list = new news_category_list();
$news_category_list->set_order_by ("name");
$news_categories = $news_category_list->do_search();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - News Categories</title>
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
			<h1>News Categories</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">News Categories</li>
			</ol>
		</nav>

		<div id="main">
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($news_categories as $news_category) {
						?>
						<tr>
							<td><?=clean_display_string ($news_category->get_name())?></td>
							<td class="edit"><a href="edit_news_category.php?id=<?=clean_display_string ($news_category->get_id())?>">Edit</a></td>
							<td class="delete"><a href="delete_news_category.php?id=<?=clean_display_string ($news_category->get_id())?>">Delete</a></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table><br>
			<div id="action_button">
				<a href="edit_news_category.php" class="add btn btn-primary">Add News Category</a>
			</div>
		</div>
	</div>
	<?php
		include('parts/scripts.php');
	?>
	</body>
</html>
