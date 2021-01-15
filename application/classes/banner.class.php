<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/banner_db.class.php");

class banner {
	var $_banner_db;
	var $_dirty;

	function _make_clean() {
		$this->_dirty['banner'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_banner_db = new banner_db($id);
		} elseif (!is_null ($data)) {
			$banner_db = new banner_db();
			$banner_db->populate ($data);
			$this->_banner_db = $banner_db;
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_banner_db = new banner_db();
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
		if ($this->_banner_db->load_if_exists ($id)) {
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
		if ($this->_dirty['banner']) {
			$this->_banner_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_banner_db->get_id());
	}
	function get_title() {
		return ($this->_banner_db->get_title());
	}
	function get_headline() {
		return ($this->_banner_db->get_button());
	}
	function get_button() {
		return ($this->_banner_db->get_button());
	}
	function get_sub_headline() {
		return ($this->_banner_db->get_copy());
	}
	function get_copy() {
		return ($this->_banner_db->get_copy());
	}
	function get_homepage() {
		return ($this->_banner_db->get_homepage());
	}
	function get_url() {
		return ($this->_banner_db->get_url());
	}

	function set_id($val) {
		if ($val === $this->_banner_db->get_id()) {
			return true;
		}
		$this->_banner_db->set_id ($val);
		$this->_dirty['banner'] = true;
		return true;
	}
	function set_title($val) {
		if ($val === $this->_banner_db->get_title()) {
			return true;
		}
		$this->_banner_db->set_title ($val);
		$this->_dirty['banner'] = true;
		return true;
	}
	function set_headline($val) {
		return $this->set_button($val);
	}
	function set_button($val) {
		if ($val === $this->_banner_db->get_button()) {
			return true;
		}
		$this->_banner_db->set_button ($val);
		$this->_dirty['banner'] = true;
		return true;
	}
	function set_sub_headline ($val) {
		return $this->set_copy($val);
	}
	function set_copy($val) {
		if ($val === $this->_banner_db->get_copy()) {
			return true;
		}
		$this->_banner_db->set_copy ($val);
		$this->_dirty['banner'] = true;
		return true;
	}
	function set_homepage($val) {
		if ($val === $this->_banner_db->get_homepage()) {
			return true;
		}
		$this->_banner_db->set_homepage ($val);
		$this->_dirty['banner'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_banner_db->get_url()) {
			return true;
		}
		$this->_banner_db->set_url ($val);
		$this->_dirty['banner'] = true;
		return true;
	}

}

?>
