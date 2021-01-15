<?php
setlocale (LC_ALL, "en_GB");

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/includes/login.inc.php");

session_start();
check_login();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite</title>
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

					<div class="alert alert-secondary" role="alert">
					Please select an area from the menu to begin editing.
					</div>

				</div>

			</div>




	</div>

		<?php
			include('parts/scripts.php');
		?>

	</body>
</html>
