<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class category_l1_db {
	private $_id;
	private $_name;
	private $_image_id;
	private $_summary;
	private $_url;
	private $_order;
	private $_meta_title;
	private $_meta_description;
	private $_status;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_name = null;
			$this->_image_id = null;
			$this->_summary = null;
			$this->_url = null;
			$this->_order = null;
			$this->_meta_title = null;
			$this->_meta_description = null;
			$this->_status = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load_by_url ($url) {
		if ($url == "") {
			trigger_error ("Blank url passed to load_by_url function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				category_l1s.category_l1_id,
				category_l1s.category_l1_name,
				category_l1s.image_id,
				category_l1s.category_l1_summary,
				category_l1s.category_l1_url,
				category_l1s.category_l1_order,
				category_l1s.category_l1_meta_title,
				category_l1s.category_l1_meta_description,
				category_l1s.category_l1_status
			FROM category_l1s
			WHERE
				category_l1_url = " . Database::make_sql_value ($url) . " AND 
				category_l1s.category_l1_status <> 'deleted' AND
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

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				category_l1s.category_l1_id,
				category_l1s.category_l1_name,
				category_l1s.image_id,
				category_l1s.category_l1_summary,
				category_l1s.category_l1_url,
				category_l1s.category_l1_order,
				category_l1s.category_l1_meta_title,
				category_l1s.category_l1_meta_description,
				category_l1s.category_l1_status
			FROM category_l1s
			WHERE
				category_l1_id = " . Database::make_sql_value ($id) . " AND 
				category_l1s.category_l1_status <> 'deleted' AND
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
			trigger_error ("category_l1_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO category_l1s (
					category_l1_id,
					category_l1_name,
					image_id,
					category_l1_summary,
					category_l1_url,
					category_l1_order,
					category_l1_meta_title,
					category_l1_meta_description,
					category_l1_status
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_name()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . ",
					" . Database::make_sql_value ($this->get_summary()) . ",
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_order()) . ",
					" . Database::make_sql_value ($this->get_meta_title()) . ",
					" . Database::make_sql_value ($this->get_meta_description()) . ",
					" . Database::make_sql_value ($this->get_status()) . "
				)";
		} else {
			$query = "UPDATE category_l1s SET
								category_l1_name = " . Database::make_sql_value ($this->get_name()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . ",
								category_l1_summary = " . Database::make_sql_value ($this->get_summary()) . ",
								category_l1_url = " . Database::make_sql_value ($this->get_url()) . ",
								category_l1_order = " . Database::make_sql_value ($this->get_order()) . ",
								category_l1_meta_title = " . Database::make_sql_value ($this->get_meta_title()) . ",
								category_l1_meta_description = " . Database::make_sql_value ($this->get_meta_description()) . ",
								category_l1_status = " . Database::make_sql_value ($this->get_status()) . "
							WHERE category_l1_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['category_l1_id']);
		$this->_name = strval ($row['category_l1_name']);
		$this->_image_id = intval ($row['image_id']);
		$this->_summary = strval ($row['category_l1_summary']);
		$this->_url = strval ($row['category_l1_url']);
		$this->_order = intval ($row['category_l1_order']);
		$this->_meta_title = strval ($row['category_l1_meta_title']);
		$this->_meta_description = strval ($row['category_l1_meta_description']);
		$this->_status = strval ($row['category_l1_status']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_name() {
		return ($this->_name);
	}
	function get_image_id() {
		return ($this->_image_id);
	}
	function get_summary() {
		return ($this->_summary);
	}
	function get_order() {
		return ($this->_order);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_meta_title() {
		return ($this->_meta_title);
	}
	function get_meta_description() {
		return ($this->_meta_description);
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
	function set_name ($val) {
		if (is_string ($val)) {
			$this->_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_name", E_USER_ERROR);
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
	function set_summary ($val) {
		if (is_string ($val)) {
			$this->_summary = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_summary", E_USER_ERROR);
			return false;
		}
	}
	function set_order ($val) {
		if (is_int ($val)) {
			$this->_order = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_order", E_USER_ERROR);
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
	function set_meta_title ($val) {
		if (is_string ($val)) {
			$this->_meta_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_title", E_USER_ERROR);
			return false;
		}
	}
	function set_meta_description ($val) {
		if (is_string ($val)) {
			$this->_meta_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_description", E_USER_ERROR);
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
}

?>
