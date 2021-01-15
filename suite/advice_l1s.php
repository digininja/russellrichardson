<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/advice_l1.class.php");
require_once ("application/classes/advice_l1_list.class.php");
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

$advice_l1_list = new advice_l1_list();
$advice_l1_list->set_order_by ("name");
$advice_l1s = $advice_l1_list->do_search();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>RR Admin Suite - Level 1 Advice</title>
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
			<h1>Advice Categories</h1>
		</div>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/suite/">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Advice Categories</li>
			</ol>
		</nav>


		<div id="main">
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Edit</th>
						<th>Delete</th>
						<th>Sub categories</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($advice_l1s as $advice_l1) {
						?>
						<tr>
							<td><?=clean_display_string ($advice_l1->get_name())?></td>
							<td class="edit"><a href="edit_advice_l1.php?id=<?=clean_display_string ($advice_l1->get_id())?>">Edit</a></td>
							<td class="delete"><a href="delete_advice_l1.php?id=<?=clean_display_string ($advice_l1->get_id())?>">Delete</a></td>
							<td class=""><a href="advice_l2s.php?parent_id=<?=clean_display_string ($advice_l1->get_id())?>">Level 2</a></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table><br>
			<div id="action_button">
				<a href="edit_advice_l1.php" class="add btn btn-primary">Add a category</a>
			</div>
		</div>
	</div>
	<?php
		include('parts/scripts.php');
	?>

</body>
</html>
