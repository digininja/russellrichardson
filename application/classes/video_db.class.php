<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class video_db {
	private $_id;
	private $_url;
	private $_title;
	private $_status;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_url = null;
			$this->_title = null;
			$this->_status = null;
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
				videos.video_id,
				videos.video_url,
				videos.video_title,
				videos.video_status
			FROM videos
			WHERE
				video_id = " . Database::make_sql_value ($id) . " AND 
				videos.video_status <> 'deleted' AND
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
			trigger_error ("video_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO videos (
					video_id,
					video_url,
					video_title,
					video_status
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_title()) . ",
					" . Database::make_sql_value ($this->get_status()) . "
				)";
		} else {
			$query = "UPDATE videos SET
								video_url = " . Database::make_sql_value ($this->get_url()) . ",
								video_title = " . Database::make_sql_value ($this->get_title()) . ",
								video_status = " . Database::make_sql_value ($this->get_status()) . "
							WHERE video_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['video_id']);
		$this->_url = strval ($row['video_url']);
		$this->_title = strval ($row['video_title']);
		if (is_null ($row['video_status'])) {
			$this->_status = null;
		} else {
			$this->_status = strval ($row['video_status']);
		}
	}

	function get_id() {
		return ($this->_id);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_title() {
		return ($this->_title);
	}
	function get_status() {
		return ($this->_status);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
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
	function set_title ($val) {
		if (is_string ($val)) {
			$this->_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_title", E_USER_ERROR);
			return false;
		}
	}
	function set_status ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_status = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_status", E_USER_ERROR);
			return false;
		}
	}
}

?>