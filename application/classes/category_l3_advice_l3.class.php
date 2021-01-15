<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/category_l3_advice_l3_db.class.php");

class category_l3_advice_l3 {
	private $_category_l3_advice_l3_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['category_l3_advice_l3'] = false;
	}
	
	function __construct ($advice_l3_id = null, $data = null) {
		if (!is_null ($advice_l3_id) && (is_int ($advice_l3_id))) {
			$this->_category_l3_advice_l3_db = new category_l3_advice_l3_db($advice_l3_id);
		} elseif (!is_null ($data)) {
			$this->_category_l3_advice_l3_db = new category_l3_advice_l3_db();
			$this->_category_l3_advice_l3_db->populate ($data);
		} elseif (is_null ($data) && is_null ($advice_l3_id)) {
			$this->_category_l3_advice_l3_db = new category_l3_advice_l3_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
	}

	function load_if_exists ($advice_l3_id, $category_l3_id) {
		if (is_null ($category_l3_id) || is_null ($advice_l3_id)) {
			trigger_error ("No values passed to load function", E_USER_ERROR);
			return false;
		}
		if ($this->_category_l3_advice_l3_db->load_if_exists ($advice_l3_id, $category_l3_id)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function load ($advice_l3_id, $category_l3_id) {
		return $this->load_if_exists ($advice_l3_id, $category_l3_id);
	}

	function delete() {
		$this->set_status (STATUS_DELETED);
		return ($this->save());
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['category_l3_advice_l3']) {
			$this->_category_l3_advice_l3_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_category_l3_id() {
		return ($this->_category_l3_advice_l3_db->get_category_l3_id());
	}
	function get_advice_l3_id() {
		return ($this->_category_l3_advice_l3_db->get_advice_l3_id());
	}

	function set_category_l3_id($val) {
		if ($val === $this->_category_l3_advice_l3_db->get_category_l3_id()) {
			return true;
		}
		$this->_category_l3_advice_l3_db->set_category_l3_id ($val);
		$this->_dirty['category_l3_advice_l3'] = true;
		return true;
	}
	function set_advice_l3_id($val) {
		if ($val === $this->_category_l3_advice_l3_db->get_advice_l3_id()) {
			return true;
		}
		$this->_category_l3_advice_l3_db->set_advice_l3_id ($val);
		$this->_dirty['category_l3_advice_l3'] = true;
		return true;
	}

}

?>
