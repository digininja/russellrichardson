<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/image.class.php");
require_once ("application/classes/advice_l1.class.php");
require_once ("application/classes/advice_l2_db.class.php");
require_once ("application/classes/advice_l3_list.class.php");

class advice_l2 {
	private $_advice_l2_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['advice_l2'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_advice_l2_db = new advice_l2_db($id);
		} elseif (!is_null ($data)) {
			$this->_advice_l2_db = new advice_l2_db();
			$this->_advice_l2_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_advice_l2_db = new advice_l2_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
	}

	function load_by_url ($url) {
		if ($url == "") {
			trigger_error ("Blank url passed to load_by_url function", E_USER_ERROR);
			return false;
		}
		if ($this->_advice_l2_db->load_by_url ($url)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Blank id passed to load_if_exists function", E_USER_ERROR);
			return false;
		}
		if ($this->_advice_l2_db->load_if_exists ($id)) {
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
		if ($this->_dirty['advice_l2']) {
			$this->_advice_l2_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_parent() {
		$advice_l1 = new advice_l1($this->get_advice_l1_id());
		return $advice_l1;
	}
	function get_children() {
		$advice_l3_list = new advice_l3_list();
		$advice_l3_list->set_order_by ("name");
		$advice_l3_list->set_advice_l2_id ($this->get_id());
		$advice_l3s = $advice_l3_list->do_search();

		return $advice_l3s;
	}

	function get_id() {
		return ($this->_advice_l2_db->get_id());
	}
	function get_advice_l1_id() {
		return ($this->_advice_l2_db->get_advice_l1_id());
	}
	function get_name() {
		return ($this->_advice_l2_db->get_name());
	}
	function get_image_id() {
		return ($this->_advice_l2_db->get_image_id());
	}
	function get_image() {
		if (!is_null ($this->get_image_id()) && $this->get_image_id() != 0) {
			$image = new image($this->get_image_id());
			return $image;
		}
		return null;
	}
	function get_banner_id() {
		return ($this->_advice_l2_db->get_banner_id());
	}
	function get_banner() {
		if (!is_null ($this->get_banner_id()) && $this->get_banner_id() != 0) {
			$banner = new image($this->get_banner_id());
			return $banner;
		}
		return null;
	}
	function get_summary() {
		return ($this->_advice_l2_db->get_summary());
	}
	function get_body() {
		return ($this->_advice_l2_db->get_body());
	}
	function get_url() {
		return ($this->_advice_l2_db->get_url());
	}
	function get_meta_title() {
		return ($this->_advice_l2_db->get_meta_title());
	}
	function get_meta_description() {
		return ($this->_advice_l2_db->get_meta_description());
	}
	function get_status() {
		return ($this->_advice_l2_db->get_status());
	}

	function set_id($val) {
		if ($val === $this->_advice_l2_db->get_id()) {
			return true;
		}
		$this->_advice_l2_db->set_id ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_advice_l1_id($val) {
		if ($val === $this->_advice_l2_db->get_advice_l1_id()) {
			return true;
		}
		$this->_advice_l2_db->set_advice_l1_id ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_advice_l2_db->get_name()) {
			return true;
		}
		$this->_advice_l2_db->set_name ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_image_id($val) {
		if ($val === $this->_advice_l2_db->get_image_id()) {
			return true;
		}
		$this->_advice_l2_db->set_image_id ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_banner_id($val) {
		if ($val === $this->_advice_l2_db->get_banner_id()) {
			return true;
		}
		$this->_advice_l2_db->set_banner_id ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_summary($val) {
		if ($val === $this->_advice_l2_db->get_summary()) {
			return true;
		}
		$this->_advice_l2_db->set_summary ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_body($val) {
		if ($val === $this->_advice_l2_db->get_body()) {
			return true;
		}
		$this->_advice_l2_db->set_body ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_advice_l2_db->get_url()) {
			return true;
		}
		$this->_advice_l2_db->set_url ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_meta_title($val) {
		if ($val === $this->_advice_l2_db->get_meta_title()) {
			return true;
		}
		$this->_advice_l2_db->set_meta_title ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_meta_description($val) {
		if ($val === $this->_advice_l2_db->get_meta_description()) {
			return true;
		}
		$this->_advice_l2_db->set_meta_description ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_advice_l2_db->get_status()) {
			return true;
		}
		$this->_advice_l2_db->set_status ($val);
		$this->_dirty['advice_l2'] = true;
		return true;
	}

}

?>
