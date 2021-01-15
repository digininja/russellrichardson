<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/advice_l3.class.php");
require_once ("application/classes/advice_l2.class.php");
require_once ("application/classes/advice_l1.class.php");
require_once ("application/classes/advice_l3_list.class.php");
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

if (array_key_exists ("parent_id", $_GET) && is_numeric ($_GET['parent_id']) && $_GET['parent_id'] > 0) {
	$parent_id = intval ($_GET['parent_id']);
	$advice_l2 = new advice_l2($parent_id);
} else {
	header ("location: index.php");
	exit;
}

$advice_l3s = $advice_l2->get_children();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Advice Articles</title>
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
			<h1>Advice Articles</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item"><a href="/suite/advice_l1s.php">Advice Categories</a></li>
				<li class="breadcrumb-item"><a href="/suite/advice_l2s.php?parent_id=<?=clean_display_string ($advice_l2->get_advice_l1_id())?>">Advice Sub Categories</a></li>
				<li class="breadcrumb-item active" aria-current="page">Advice Articles</li>
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
					foreach ($advice_l3s as $advice_l3) {
						?>
						<tr>
							<td><?=clean_display_string ($advice_l3->get_name())?></td>
							<td class="edit"><a href="edit_advice_l3.php?id=<?=clean_display_string ($advice_l3->get_id())?>">Edit</a></td>
							<td class="delete"><a href="delete_advice_l3.php?id=<?=clean_display_string ($advice_l3->get_id())?>">Delete</a></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<div id="action_button">
				<a href="edit_advice_l3.php?parent_id=<?=clean_display_string ($parent_id)?>" class="add btn btn-primary">Add an article</a>
			</div>
		</div>
	</div>
	<?php
		include('parts/scripts.php');
	?>
	</body>
</html>
