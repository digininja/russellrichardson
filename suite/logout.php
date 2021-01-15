<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}

session_start();

session_destroy();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>RR Admin Suite - Logout</title>
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

			<div class="row justify-content-center">
			<div class="col-4">


				<div id="main" class="main">
						<div class="alert alert-success" role="alert">
					<h2>Logged Out</h2>
					<p>
					You have been logged out.
					</p>
					<p>
					<a href="index.php" class="btn btn-primary">Login again</a>
				</div>

				</div>
			</div>
		</div>

		<?php
			include('parts/scripts.php');
		?>

	</body>
</html>
