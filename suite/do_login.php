<?php
if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/includes/error.inc.php");
require_once ("application/includes/constants.inc.php");
require_once ("application/includes/useful.inc.php");
require_once ("application/includes/debug.inc.php");
require_once ("application/classes/admin_login.class.php");
require_once ("application/classes/PasswordHash.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (array_key_exists ("username", $_POST) && array_key_exists ("password", $_POST)) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		$admin_login = new admin_login();

		$valid = $admin_login->check_login ($username, $password);

		if ($valid) {
			$_SESSION['logged_in'] = array (
											"username" => $username,
										);
			header ("location: index.php");
			exit;
		}
	}
}
header ("location: login.php");
exit;
?>
