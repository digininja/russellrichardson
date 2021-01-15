<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/download_db.class.php");

class download {
	private $_download_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['download'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_download_db = new download_db($id);
		} elseif (!is_null ($data)) {
			$this->_download_db = new download_db();
			$this->_download_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_download_db = new download_db();
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
		if ($this->_download_db->load_if_exists ($id)) {
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
		if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/documents/" . $this->get_filename())) {
			unlink ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/documents/" . $this->get_filename());
		}
		return $this->_download_db->delete();
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['download']) {
			$this->_download_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_download_db->get_id());
	}
	function get_date() {
		return ($this->_download_db->get_date());
	}
	function get_summary() {
		return ($this->_download_db->get_summary());
	}
	function get_filename() {
		return ($this->_download_db->get_filename());
	}
	function get_name() {
		return ($this->_download_db->get_name());
	}

	function set_id($val) {
		if ($val === $this->_download_db->get_id()) {
			return true;
		}
		$this->_download_db->set_id ($val);
		$this->_dirty['download'] = true;
		return true;
	}
	function set_date($val) {
		if ($val === $this->_download_db->get_date()) {
			return true;
		}
		$this->_download_db->set_date ($val);
		$this->_dirty['download'] = true;
		return true;
	}
	function set_summary($val) {
		if ($val === $this->_download_db->get_summary()) {
			return true;
		}
		$this->_download_db->set_summary ($val);
		$this->_dirty['download'] = true;
		return true;
	}
	function set_filename($val) {
		if ($val === $this->_download_db->get_filename()) {
			return true;
		}
		$this->_download_db->set_filename ($val);
		$this->_dirty['download'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_download_db->get_name()) {
			return true;
		}
		$this->_download_db->set_name ($val);
		$this->_dirty['download'] = true;
		return true;
	}

}

?>
