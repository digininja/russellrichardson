<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class admin_login_db {
	var $_dbh;
	var $_username;
	var $_status;
	var $_password;
	var $_name;
	var $_description;
	var $_level;

	function connect() {
		if (is_null ($this->_dbh)) {
			$this->_dbh = new database();
		}
	}

	function admin_login_db ($id = null) {
		$this->connect();
		if ($id == "") {
			$this->_username = null;
			$this->_status = null;
			$this->_password = null;
			$this->_name = null;
			$this->_description = null;
			$this->_level = null;
		} else {
			$this->load (strval ($id));
		}
	}

	function load ($id) {
		if ($id == "") {
			trigger_error ("Empty string passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				admin_login_username,
				admin_login_status,
				admin_login_password,
				admin_login_name,
				admin_login_description,
				admin_login_level
			FROM admin_logins
			WHERE admin_login_username = " . $this->_dbh->make_sql_value ($id) . "
			";
		$res = $this->_dbh->execute ($query);
		if (mysql_num_rows ($res) == 1) {
			$row = mysql_fetch_assoc ($res);
			$this->populate ($row);
		} else {
			trigger_error ("admin_login_db ($id) not found", E_USER_ERROR);
			return false;
		}
		return true;
	}

	function save() {
		// this is needed just in case the object was put in a session
		// in which case the db connection is lost and so needs re-establishing
		$this->connect();

		$query = "REPLACE INTO admin_logins (
				admin_login_username,
				admin_login_status,
				admin_login_password,
				admin_login_name,
				admin_login_description,
				admin_login_level
			) VALUES (
				" . $this->_dbh->make_sql_value ($this->get_username()) . ",
				" . $this->_dbh->make_sql_value ($this->get_status()) . ",
				" . $this->_dbh->make_sql_value ($this->get_password()) . ",
				" . $this->_dbh->make_sql_value ($this->get_name()) . ",
				" . $this->_dbh->make_sql_value ($this->get_description()) . ",
				" . $this->_dbh->make_sql_value ($this->get_level()) . "
			)";

	$res = $this->_dbh->execute ($query);
	if (is_null ($this->_username)) {
		$this->_username = $this->_dbh->get_insert_id();
	}
	return true;
											
	}

	function populate ($row) {
		$this->_username = strval ($row['admin_login_username']);
		$this->_status = strval ($row['admin_login_status']);
		$this->_password = strval ($row['admin_login_password']);
		if (is_null ($row['admin_login_name'])) {
			$this->_name = null;
		} else {
			$this->_name = strval ($row['admin_login_name']);
		}
		if (is_null ($row['admin_login_description'])) {
			$this->_description = null;
		} else {
			$this->_description = strval ($row['admin_login_description']);
		}
		$this->_level = intval ($row['admin_login_level']);
	}

	function get_username() {
		return ($this->_username);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_password() {
		return ($this->_password);
	}
	function get_name() {
		return ($this->_name);
	}
	function get_level() {
		return ($this->_level);
	}
	function get_description() {
		return ($this->_description);
	}

	function set_username ($val) {
		if (is_string ($val)) {
			$this->_username = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_username", E_USER_ERROR);
			return false;
		}
	}
	function set_status ($val) {
		if (is_string ($val)) {
			$this->_status = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_status", E_USER_ERROR);
			return false;
		}
	}
	function set_password ($val) {
		if (is_string ($val)) {
			$this->_password = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_password", E_USER_ERROR);
			return false;
		}
	}
	function set_name ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_name", E_USER_ERROR);
			return false;
		}
	}
	function set_level ($val) {
		if (is_int ($val)) {
			$this->_level = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_level", E_USER_ERROR);
			return false;
		}
	}
	function set_description ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_description", E_USER_ERROR);
			return false;
		}
	}
}

?>
