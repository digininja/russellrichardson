<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class image_db {
	var $_dbh;
	var $_id;
	var $_status;
	var $_filename;
	var $_gallery_id;
	var $_width;
	var $_height;
	var $_alt_text;
	var $_description;
	var $_mime_type;

	function connect() {
		if (is_null ($this->_dbh)) {
			$this->_dbh = new database();
		}
	}

	function __construct ($id = null) {
		
		if (is_null ($id)) {
			$this->_id = null;
			$this->_status = null;
			$this->_filename = null;
			$this->_gallery_id = null;
			$this->_width = null;
			$this->_height = null;
			$this->_alt_text = null;
			$this->_description = null;
			$this->_mime_type = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load ($id) {
		if (!$this->load_if_exists ($id)) {
			trigger_error ("image_db ($id) not found", E_USER_ERROR);
		}
		return true;
	}
	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				image_id,
				image_status,
				image_filename,
				image_gallery_id,
				image_width,
				image_height,
				image_alt_text,
				image_description,
				image_mime_type
			FROM images
			WHERE
				image_status <> 'deleted' AND
				image_id = " . database::make_sql_value ($id) . "
			";
				#image_status = 'active' AND
		$res = database::execute ($query);
		if (mysqli_num_rows ($res) == 1) {
			$row = mysqli_fetch_assoc ($res);
			$this->populate ($row);
		} else {
			return false;
		}
		return true;
	}

	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO images (
					image_id,
					image_status,
					image_filename,
					image_gallery_id,
					image_width,
					image_height,
					image_alt_text,
					image_description,
					image_mime_type
				) VALUES (
					" . database::make_sql_value ($this->get_id()) . ",
					" . database::make_sql_value ($this->get_status()) . ",
					" . database::make_sql_value ($this->get_filename()) . ",
					" . database::make_sql_value ($this->get_gallery_id()) . ",
					" . database::make_sql_value ($this->get_width()) . ",
					" . database::make_sql_value ($this->get_height()) . ",
					" . database::make_sql_value ($this->get_alt_text()) . ",
					" . database::make_sql_value ($this->get_description()) . ",
					" . database::make_sql_value ($this->get_mime_type()) . "
				)";
		} else {
			$query = "UPDATE images SET
					image_status = " . database::make_sql_value ($this->get_status()) . ",
					image_filename = " . database::make_sql_value ($this->get_filename()) . ",
					image_gallery_id = " . database::make_sql_value ($this->get_gallery_id()) . ",
					image_width = " . database::make_sql_value ($this->get_width()) . ",
					image_height = " . database::make_sql_value ($this->get_height()) . ",
					image_alt_text = " . database::make_sql_value ($this->get_alt_text()) . ",
					image_description = " . database::make_sql_value ($this->get_description()) . ",
					image_mime_type = " . database::make_sql_value ($this->get_mime_type()) . "
				WHERE image_id = " . database::make_sql_value ($this->get_id()) . "
			";
		}

		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
											
	}

	function populate ($row) {
		$this->_id = intval ($row['image_id']);
		$this->_status = strval ($row['image_status']);
		$this->_filename = strval ($row['image_filename']);
		$this->_gallery_id = strval ($row['image_gallery_id']);
		$this->_width = intval ($row['image_width']);
		$this->_height = intval ($row['image_height']);
		if (is_null ($row['image_alt_text'])) {
			$this->_alt_text = null;
		} else {
			$this->_alt_text = strval ($row['image_alt_text']);
		}
		$this->_description = strval ($row['image_description']);
		$this->_mime_type = strval ($row['image_mime_type']);
	}

	function get_status() {
		return ($this->_status);
	}
	function get_id() {
		return ($this->_id);
	}
	function get_gallery_id() {
		return ($this->_gallery_id);
	}
	function get_filename() {
		return ($this->_filename);
	}
	function get_width() {
		return ($this->_width);
	}
	function get_height() {
		return ($this->_height);
	}
	function get_description() {
		return ($this->_description);
	}
	function get_alt_text() {
		return ($this->_alt_text);
	}
	function get_mime_type() {
		return ($this->_mime_type);
	}

	function set_status ($val) {
		if ($val != "") {
			$this->_status = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_status", E_USER_ERROR);
			return false;
		}
	}
	function set_id ($val) {
		if (is_null ($val) || is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
			return false;
		}
	}
	function set_gallery_id ($val) {
		if (is_int ($val)) {
			$this->_gallery_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_gallery_id", E_USER_ERROR);
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
	function set_width ($val) {
		if (is_int ($val)) {
			$this->_width = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_width", E_USER_ERROR);
			return false;
		}
	}
	function set_height ($val) {
		if (is_int ($val)) {
			$this->_height = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_height", E_USER_ERROR);
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
	function set_alt_text ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_alt_text = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_alt_text", E_USER_ERROR);
			return false;
		}
	}
	function set_mime_type ($val) {
		if (is_string ($val)) {
			$this->_mime_type = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_mime_type", E_USER_ERROR);
			return false;
		}
	}
}

?>
