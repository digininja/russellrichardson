<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class link_db {
	private $_id;
	private $_title;
	private $_url;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_title = null;
			$this->_url = null;
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
				links.link_id,
				links.link_title,
				links.link_url
			FROM links
			WHERE
				link_id = " . Database::make_sql_value ($id) . " AND 
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
			trigger_error ("link_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		$query = "REPLACE INTO links (
				link_id,
				link_title,
				link_url
			) VALUES (
				" . Database::make_sql_value ($this->get_id()) . ",
				" . Database::make_sql_value ($this->get_title()) . ",
				" . Database::make_sql_value ($this->get_url()) . "
			)";
		# print $query;
		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_id = intval ($row['link_id']);
		$this->_title = strval ($row['link_title']);
		$this->_url = strval ($row['link_url']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_title() {
		return ($this->_title);
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
