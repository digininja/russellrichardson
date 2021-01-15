<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class cms_image_gallery_db {
	private $_id;
	private $_status;
	private $_image_id;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_status = null;
			$this->_image_id = null;
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
				cms_image_gallery.cms_image_gallery_id,
				cms_image_gallery.cms_image_gallery_status,
				cms_image_gallery.image_id
			FROM cms_image_gallery
			WHERE
				cms_image_gallery_id = " . Database::make_sql_value ($id) . " AND 
				cms_image_gallery.cms_image_gallery_status <> 'deleted' AND
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
			trigger_error ("cms_image_gallery_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO cms_image_gallery (
					cms_image_gallery_id,
					cms_image_gallery_status,
					image_id
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_status()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . "
				)";
		} else {
			$query = "UPDATE cms_image_gallery SET
								cms_image_gallery_status = " . Database::make_sql_value ($this->get_status()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . "
							WHERE cms_image_gallery_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['cms_image_gallery_id']);
		$this->_status = strval ($row['cms_image_gallery_status']);
		$this->_image_id = intval ($row['image_id']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_image_id() {
		return ($this->_image_id);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
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
	function set_image_id ($val) {
		if (is_int ($val)) {
			$this->_image_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_image_id", E_USER_ERROR);
			return false;
		}
	}
}

?>