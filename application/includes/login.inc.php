<?php
function check_login() {
	if (array_key_exists ('logged_in', $_SESSION)) {
		return true;
	}
	header ("location: /suite/login.php");
	exit;
}

function get_logged_in_user () {
	if (array_key_exists ('logged_in', $_SESSION)) {
		if (array_key_exists ('username', $_SESSION['logged_in'])) {
			return $_SESSION['logged_in']['username'];
		}
	}
	return false;
}

?>
