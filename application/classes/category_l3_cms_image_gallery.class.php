<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/category_l3_cms_image_gallery_db.class.php");

class category_l3_cms_image_gallery {
	private $_category_l3_cms_image_gallery_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['category_l3_cms_image_gallery'] = false;
	}
	
	function __construct ($cms_image_gallery_id = null, $data = null) {
		if (!is_null ($cms_image_gallery_id) && (is_int ($cms_image_gallery_id))) {
			$this->_category_l3_cms_image_gallery_db = new category_l3_cms_image_gallery_db($cms_image_gallery_id);
		} elseif (!is_null ($data)) {
			$this->_category_l3_cms_image_gallery_db = new category_l3_cms_image_gallery_db();
			$this->_category_l3_cms_image_gallery_db->populate ($data);
		} elseif (is_null ($data) && is_null ($cms_image_gallery_id)) {
			$this->_category_l3_cms_image_gallery_db = new category_l3_cms_image_gallery_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
	}

	function load_if_exists ($cms_image_gallery_id, $category_l3_id) {
		if (is_null ($category_l3_id) || is_null ($cms_image_gallery_id)) {
			trigger_error ("No values passed to load function", E_USER_ERROR);
			return false;
		}
		if ($this->_category_l3_cms_image_gallery_db->load_if_exists ($cms_image_gallery_id, $category_l3_id)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function load ($cms_image_gallery_id, $category_l3_id) {
		return $this->load_if_exists ($cms_image_gallery_id, $category_l3_id);
	}

	function delete() {
		$this->set_status (STATUS_DELETED);
		return ($this->save());
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['category_l3_cms_image_gallery']) {
			$this->_category_l3_cms_image_gallery_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_category_l3_id() {
		return ($this->_category_l3_cms_image_gallery_db->get_category_l3_id());
	}
	function get_cms_image_gallery_id() {
		return ($this->_category_l3_cms_image_gallery_db->get_cms_image_gallery_id());
	}

	function set_category_l3_id($val) {
		if ($val === $this->_category_l3_cms_image_gallery_db->get_category_l3_id()) {
			return true;
		}
		$this->_category_l3_cms_image_gallery_db->set_category_l3_id ($val);
		$this->_dirty['category_l3_cms_image_gallery'] = true;
		return true;
	}
	function set_cms_image_gallery_id($val) {
		if ($val === $this->_category_l3_cms_image_gallery_db->get_cms_image_gallery_id()) {
			return true;
		}
		$this->_category_l3_cms_image_gallery_db->set_cms_image_gallery_id ($val);
		$this->_dirty['category_l3_cms_image_gallery'] = true;
		return true;
	}

}

?>
