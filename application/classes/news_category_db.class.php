<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class news_category_db {
	private $_id;
	private $_status;
	private $_name;
	private $_url;
	private $_meta_description;
	private $_meta_title;
	private $_image_id;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_status = null;
			$this->_name = null;
			$this->_url = null;
			$this->_meta_description = null;
			$this->_meta_title = null;
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
				news_categories.news_category_id,
				news_categories.news_category_status,
				news_categories.news_category_name,
				news_categories.news_category_url,
				news_categories.news_category_meta_description,
				news_categories.news_category_meta_title,
				news_categories.image_id
			FROM news_categories
			WHERE
				news_category_id = " . Database::make_sql_value ($id) . " AND 
				news_categories.news_category_status <> 'deleted' AND
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
			trigger_error ("news_category_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO news_categories (
					news_category_id,
					news_category_status,
					news_category_name,
					news_category_url,
					news_category_meta_description,
					news_category_meta_title,
					image_id
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_status()) . ",
					" . Database::make_sql_value ($this->get_name()) . ",
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_meta_description()) . ",
					" . Database::make_sql_value ($this->get_meta_title()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . "
				)";
		} else {
			$query = "UPDATE news_categories SET
								news_category_status = " . Database::make_sql_value ($this->get_status()) . ",
								news_category_name = " . Database::make_sql_value ($this->get_name()) . ",
								news_category_url = " . Database::make_sql_value ($this->get_url()) . ",
								news_category_meta_description = " . Database::make_sql_value ($this->get_meta_description()) . ",
								news_category_meta_title = " . Database::make_sql_value ($this->get_meta_title()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . "
							WHERE news_category_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['news_category_id']);
		$this->_status = strval ($row['news_category_status']);
		$this->_name = strval ($row['news_category_name']);
		$this->_url = strval ($row['news_category_url']);
		$this->_meta_description = strval ($row['news_category_meta_description']);
		$this->_meta_title = strval ($row['news_category_meta_title']);
		$this->_image_id = intval ($row['image_id']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_name() {
		return ($this->_name);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_meta_description() {
		return ($this->_meta_description);
	}
	function get_meta_title() {
		return ($this->_meta_title);
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
	function set_name ($val) {
		if (is_string ($val)) {
			$this->_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_name", E_USER_ERROR);
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
	function set_meta_description ($val) {
		if (is_string ($val)) {
			$this->_meta_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_description", E_USER_ERROR);
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