<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class banner_db {
	var $_id;
	var $_title;
	var $_copy;
	var $_button;
	var $_url;
	var $_homepage;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_title = null;
			$this->_copy = null;
			$this->_button = null;
			$this->_url = null;
			$this->_homepage = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				banners.banner_id,
				banners.banner_title,
				banners.banner_copy,
				banners.banner_button,
				banners.banner_url,
				banners.banner_homepage
			FROM banners
			WHERE
				banner_id = " . Database::make_sql_value ($id) . " AND 
				1=1
			";
		$res = database::execute ($query);
		if (mysqli_num_rows ($res) == 1) {
			$row = mysqli_fetch_assoc ($res);
			$this->populate ($row);
		} else {
			return false;
		}
		return true;
	}

	function load ($id) {
		if ($this->load_if_exists ($id)) {
			return true;
		} else {
			trigger_error ("banner_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		$query = "REPLACE INTO banners (
				banner_id,
				banner_title,
				banner_copy,
				banner_button,
				banner_url,
				banner_homepage
			) VALUES (
				" . Database::make_sql_value ($this->get_id()) . ",
				" . Database::make_sql_value ($this->get_title()) . ",
				" . Database::make_sql_value ($this->get_copy()) . ",
				" . Database::make_sql_value ($this->get_button()) . ",
				" . Database::make_sql_value ($this->get_url()) . ",
				" . Database::make_sql_value ($this->get_homepage()?YES:NO) . "
			)";
		# print $query;
		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_id = intval ($row['banner_id']);
		$this->_title = strval ($row['banner_title']);
		$this->_copy = strval ($row['banner_copy']);
		$this->_button = strval ($row['banner_button']);
		$this->_url = strval ($row['banner_url']);
		$this->_homepage = ($row['banner_homepage'] == YES);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_title() {
		return ($this->_title);
	}
	function get_copy() {
		return ($this->_copy);
	}
	function get_button() {
		return ($this->_button);
	}
	function get_homepage() {
		return ($this->_homepage);
	}
	function get_url() {
		return ($this->_url);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
			return false;
		}
	}
	function set_title ($val) {
		if (is_string ($val)) {
			$this->_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_title", E_USER_ERROR);
			return false;
		}
	}
	function set_button ($val) {
		if (is_string ($val)) {
			$this->_button = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_button", E_USER_ERROR);
			return false;
		}
	}
	function set_copy ($val) {
		if (is_string ($val)) {
			$this->_copy = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_copy", E_USER_ERROR);
			return false;
		}
	}
	function set_homepage ($val) {
		if (is_bool ($val)) {
			$this->_homepage = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_homepage", E_USER_ERROR);
			return false;
		}
	}
	function set_url ($val) {
		if (is_string ($val)) {
			$this->_url = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_url", E_USER_ERROR);
			return false;
		}
	}
}

?>
