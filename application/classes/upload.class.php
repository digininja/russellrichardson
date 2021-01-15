<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/upload_db.class.php");

class upload {
	private $_upload_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['upload'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_upload_db = new upload_db($id);
		} elseif (!is_null ($data)) {
			$this->_upload_db = new upload_db();
			$this->_upload_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_upload_db = new upload_db();
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
		if ($this->_upload_db->load_if_exists ($id)) {
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
		if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/uploads/files/" . $this->get_filename())) {
			unlink ($_SERVER['DOCUMENT_ROOT'] . "/uploads/files/" . $this->get_filename());
		}
		return $this->_upload_db->delete();
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['upload']) {
			$this->_upload_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_upload_db->get_id());
	}
	function get_filename() {
		return ($this->_upload_db->get_filename());
	}
	function get_name() {
		return ($this->_upload_db->get_name());
	}

	function set_id($val) {
		if ($val === $this->_upload_db->get_id()) {
			return true;
		}
		$this->_upload_db->set_id ($val);
		$this->_dirty['upload'] = true;
		return true;
	}
	function set_filename($val) {
		if ($val === $this->_upload_db->get_filename()) {
			return true;
		}
		$this->_upload_db->set_filename ($val);
		$this->_dirty['upload'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_upload_db->get_name()) {
			return true;
		}
		$this->_upload_db->set_name ($val);
		$this->_dirty['upload'] = true;
		return true;
	}

}

?>
