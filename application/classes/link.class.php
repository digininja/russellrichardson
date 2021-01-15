<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/link_db.class.php");

class link {
	private $_link_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['link'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_link_db = new link_db($id);
		} elseif (!is_null ($data)) {
			$this->_link_db = new link_db();
			$this->_link_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_link_db = new link_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
	}

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Blank id passed to load_if_exists function", E_USER_ERROR);
			return false;
		}
		if ($this->_link_db->load_if_exists ($id)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function load ($id) {
		return $this->load_if_exists ($id);
	}

	function delete() {
		$this->set_status (STATUS_DELETED);
		return ($this->save());
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['link']) {
			$this->_link_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_link_db->get_id());
	}
	function get_title() {
		return ($this->_link_db->get_title());
	}
	function get_url() {
		return ($this->_link_db->get_url());
	}

	function set_id($val) {
		if ($val === $this->_link_db->get_id()) {
			return true;
		}
		$this->_link_db->set_id ($val);
		$this->_dirty['link'] = true;
		return true;
	}
	function set_title($val) {
		if ($val === $this->_link_db->get_title()) {
			return true;
		}
		$this->_link_db->set_title ($val);
		$this->_dirty['link'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_link_db->get_url()) {
			return true;
		}
		$this->_link_db->set_url ($val);
		$this->_dirty['link'] = true;
		return true;
	}

}

?>