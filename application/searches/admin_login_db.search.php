<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/admin_login_db.class.php");

function get_admin_logins ($options) {
	$dbh = new database();

	$username_where = "";
	$password_where = "";
	$status_where = "";
	$order_by = "";

	if (array_key_exists ("username", $options)) {
		$username_where = " admin_logins.admin_login_username = " . $dbh->make_sql_value ($options['username']) . " AND ";
	}
	
	if (array_key_exists ("password", $options)) {
		$password_where = " admin_logins.admin_login_password = " . $dbh->make_sql_value ($options['password']) . " AND ";
	}
	
	if (array_key_exists ("status", $options)) {
		$status_where = " admin_logins.admin_login_status = " . $dbh->make_sql_value ($options['status']) . " AND ";
	}
	
	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "username":
				$order_by = " ORDER BY admin_logins.admin_login_username ";
				break;
			case "name":
				$order_by = " ORDER BY admin_logins.admin_login_name ";
				break;
		}
	}

	$query = "SELECT
				admin_login_username,
				admin_login_status,
				admin_login_password,
				admin_login_name,
				admin_login_description,
				admin_login_level
			FROM admin_logins
		WHERE 
			$status_where
			$username_where
			$password_where
			1
		$order_by
		";
		
	$result = $dbh->execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$admin_login_db = new admin_login_db();
		$admin_login_db->populate ($row);
		$data[] = $admin_login_db;
	}
	return $data;
}

?>
