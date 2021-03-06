<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/image_gallery_db.class.php");

class image_gallery {
	private $_image_gallery_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['image_gallery'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_image_gallery_db = new image_gallery_db($id);
		} elseif (!is_null ($data)) {
			$this->_image_gallery_db = new image_gallery_db();
			$this->_image_gallery_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_image_gallery_db = new image_gallery_db();
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
		if ($this->_image_gallery_db->load_if_exists ($id)) {
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
		if ($this->_dirty['image_gallery']) {
			$this->_image_gallery_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_image_gallery_db->get_id());
	}
	function get_name() {
		return ($this->_image_gallery_db->get_name());
	}
	function get_status() {
		return ($this->_image_gallery_db->get_status());
	}

	function set_id($val) {
		if ($val === $this->_image_gallery_db->get_id()) {
			return true;
		}
		$this->_image_gallery_db->set_id ($val);
		$this->_dirty['image_gallery'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_image_gallery_db->get_name()) {
			return true;
		}
		$this->_image_gallery_db->set_name ($val);
		$this->_dirty['image_gallery'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_image_gallery_db->get_status()) {
			return true;
		}
		$this->_image_gallery_db->set_status ($val);
		$this->_dirty['image_gallery'] = true;
		return true;
	}

}

?>