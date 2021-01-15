<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class download_db {
	private $_id;
	private $_filename;
	private $_summary;
	private $_date;
	private $_name;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_filename = null;
			$this->_summary = null;
			$this->_date = null;
			$this->_name = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function delete() {
		$query = "DELETE
			FROM downloads
			WHERE
				download_id = " . Database::make_sql_value ($this->get_id()) . " AND 
				1=1
			";
		$res = database::execute ($query);
		return true;
	}
	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				downloads.download_id,
				downloads.download_filename,
				downloads.download_summary,
				downloads.download_date,
				downloads.download_name
			FROM downloads
			WHERE
				download_id = " . Database::make_sql_value ($id) . " AND 
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
			trigger_error ("download_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO downloads (
					download_id,
					download_filename,
					download_summary,
					download_date,
					download_name
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_filename()) . ",
					" . Database::make_sql_value ($this->get_summary()) . ",
					" . Database::make_sql_value (Database::make_sql_date ($this->get_date())) . ",
					" . Database::make_sql_value ($this->get_name()) . "
				)";
		} else {
			$query = "UPDATE downloads SET
								download_filename = " . Database::make_sql_value ($this->get_filename()) . ",
								download_summary = " . Database::make_sql_value ($this->get_summary()) . ",
								download_date = " . Database::make_sql_value (Database::make_sql_date ($this->get_date())) . ",
								download_name = " . Database::make_sql_value ($this->get_name()) . "
							WHERE download_id = " . Database::make_sql_value ($this->get_id()) . "
						";

		}
		#print $query;
		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_id = intval ($row['download_id']);
		$this->_filename = strval ($row['download_filename']);
		$this->_summary = strval ($row['download_summary']);
		$this->_date = strtotime ($row['download_date']);
		$this->_name = strval ($row['download_name']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_date() {
		return ($this->_date);
	}
	function get_summary() {
		return ($this->_summary);
	}
	function get_filename() {
		return ($this->_filename);
	}
	function get_name() {
		return ($this->_name);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
			return false;
		}
	}
	function set_date ($val) {
		if (is_int ($val)) {
			$this->_date = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_date", E_USER_ERROR);
			return false;
		}
	}
	function set_summary ($val) {
		if (is_string ($val)) {
			$this->_summary = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_summary", E_USER_ERROR);
			return false;
		}
	}
	function set_filename ($val) {
		if (is_string ($val)) {
			$this->_filename = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_filename", E_USER_ERROR);
			return false;
		}
	}
	function set_name ($val) {
		if (is_string ($val)) {
			$this->_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_name", E_USER_ERROR);
			return false;
		}
	}
}

?>
