<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class category_l3_advice_l3_db {
	private $_category_l3_id;
	private $_advice_l3_id;

	static function clear_for_category_l3($category_l3_id) {
		$query = "DELETE
			FROM category_l3_advice_l3s
			WHERE
				category_l3_advice_l3s.category_l3_id = " . Database::make_sql_value ($category_l3_id) . " AND 
				1=1
			";
		$res = database::execute ($query);
	}
	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_category_l3_id = null;
			$this->_advice_l3_id = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load_if_exists ($advice_l3_id, $category_l3_id) {
		if (is_null ($category_l3_id) || is_null ($advice_l3_id)) {
			trigger_error ("No values passed to load function", E_USER_ERROR);
			return false;
		}

		$query = "SELECT
				category_l3_advice_l3s.category_l3_id,
				category_l3_advice_l3s.advice_l3_id
			FROM category_l3_advice_l3s
			WHERE
				category_l3_advice_l3s.category_l3_id = " . Database::make_sql_value ($category_l3_id) . " AND 
				category_l3_advice_l3s.advice_l3_id = " . Database::make_sql_value ($id) . " AND 
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

	function load ($advice_l3_id, $category_l3_id) {
		if ($this->load_if_exists ($advice_l3_id, $category_l3_id)) {
			return true;
		} else {
			trigger_error ("category_l3_advice_l3_db ($advice_l3_id, $category_l3_id) not found", E_USER_ERROR);
		}
	}
	function save() {
		$query = "REPLACE INTO category_l3_advice_l3s (
				category_l3_id,
				advice_l3_id
			) VALUES (
				" . Database::make_sql_value ($this->get_category_l3_id()) . ",
				" . Database::make_sql_value ($this->get_advice_l3_id()) . "
			)";
		#print $query;
		$res = database::execute ($query);
		if (is_null ($this->_advice_l3_id)) {
			$this->_advice_l3_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_category_l3_id = intval ($row['category_l3_id']);
		$this->_advice_l3_id = intval ($row['advice_l3_id']);
	}

	function get_category_l3_id() {
		return ($this->_category_l3_id);
	}
	function get_advice_l3_id() {
		return ($this->_advice_l3_id);
	}

	function set_category_l3_id ($val) {
		if (is_int ($val)) {
			$this->_category_l3_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_category_l3_id", E_USER_ERROR);
			return false;
		}
	}
	function set_advice_l3_id ($val) {
		if (is_int ($val)) {
			$this->_advice_l3_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_advice_l3_id", E_USER_ERROR);
			return false;
		}
	}
}

?>
