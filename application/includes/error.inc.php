<?php
require_once ("constants.inc.php");

ob_start();
$old_error_handler = set_error_handler("mailing_error_handler");

function do_nothing_handler ($errno, $errstr, $errfile, $errline) {
	print "bailout error handler: " . $errstr;
	exit;
}

// error handler function
function mailing_error_handler ($err_no, $err_str, $err_file, $err_line) {
	$old_error_handler = set_error_handler("do_nothing_handler");

	$message = "Error on whitehouse (" . $_SERVER['HTTP_HOST'] . ") web site. The following state information may be useful:\r\n\r\n";

	if (array_key_exists ('HTTP_COOKIE', $_SERVER)) {
		$message .= "[HTTP_COOKIE] = " . $_SERVER['HTTP_COOKIE'] . "\r\n";
	}
	if (array_key_exists ('HTTP_USER_AGENT', $_SERVER)) {
		$message .= "[HTTP_USER_AGENT] = " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
	}
	$message .= "[REMOTE_ADDR] = " . $_SERVER['REMOTE_ADDR'] . "\r\n";
	$message .= "[REQUEST_METHOD] = " . $_SERVER['REQUEST_METHOD'] . "\r\n";
	$message .= "[REQUEST_URI] = " . $_SERVER['REQUEST_URI'] . "\r\n";

	$message .= '$_GET = ' . print_r ($_GET, true);

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$message .= '$_POST = ' . print_r ($_POST, true);
	}
	$message .= "\r\n";
	$message .= print_r (debug_backtrace(), true) . "\r\n";

	mail (ERROR_MAIL_TO, ERROR_MAIL_SUBJECT, $message, "From: webmaster@{$_SERVER['SERVER_NAME']}\r\n");
	ob_clean();
#	print "<pre>";
#	print $message;
#	print "</pre>";
	header ("Location: /index.php");
	#header ("Location: /fault.html");
	restore_error_handler();
	//exit;
}

?>
