<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/case_study_category.class.php");
require_once ("application/classes/case_study_db.class.php");
require_once ("application/classes/case_study_list.class.php");
require_once ("application/classes/image.class.php");

class case_study {
	private $_case_study_db;
	private $_dirty;

	static function get_random() {
		$case_study_list = new case_study_list();
		$case_study_list->set_order_by ("random");
		$case_study_list->set_page_size (1);

		$case_studies = $case_study_list->do_search();
		if (count ($case_studies) > 0) {
			return $case_studies[0];
		}

		return null;
	}

	function _make_clean() {
		$this->_dirty['case_study'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_case_study_db = new case_study_db($id);
		} elseif (!is_null ($data)) {
			$this->_case_study_db = new case_study_db();
			$this->_case_study_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_case_study_db = new case_study_db();
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
		if ($this->_case_study_db->load_if_exists ($id)) {
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
		if ($this->_dirty['case_study']) {
			$this->_case_study_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_id() {
		return ($this->_case_study_db->get_id());
	}
	function get_status() {
		return ($this->_case_study_db->get_status());
	}
	function get_name() {
		return ($this->_case_study_db->get_name());
	}
	function get_category() {
		return (new case_study_category ($this->_case_study_db->get_category_id()));
	}
	function get_category_id() {
		return ($this->_case_study_db->get_category_id());
	}
	function get_service_type() {
		return ($this->_case_study_db->get_service_type());
	}
	function get_quote_name() {
		return ($this->_case_study_db->get_quote_name());
	}
	function get_body() {
		return ($this->_case_study_db->get_body());
	}
	function get_image_id() {
		return ($this->_case_study_db->get_image_id());
	}
	function get_image() {
		if (!is_null ($this->get_image_id()) && $this->get_image_id() != 0) {
			$image = new image($this->get_image_id());
			return $image;
		}
		return null;
	}
	function get_banner_id() {
		return ($this->_case_study_db->get_banner_id());
	}
	function get_logo() {
		if (!is_null ($this->get_logo_id()) && $this->get_logo_id() != 0) {
			$logo = new image($this->get_logo_id());
			return $logo;
		}
		return null;
	}
	function get_banner() {
		if (!is_null ($this->get_banner_id()) && $this->get_banner_id() != 0) {
			$banner = new image($this->get_banner_id());
			return $banner;
		}
		return null;
	}
	function get_logo_id() {
		return ($this->_case_study_db->get_logo_id());
	}
	function get_url() {
		return ($this->_case_study_db->get_url());
	}
	function get_quote() {
		return ($this->_case_study_db->get_quote());
	}
	function get_summary() {
		return ($this->_case_study_db->get_summary());
	}
	function get_meta_description() {
		return ($this->_case_study_db->get_meta_description());
	}
	function get_meta_title() {
		return ($this->_case_study_db->get_meta_title());
	}

	function set_id($val) {
		if ($val === $this->_case_study_db->get_id()) {
			return true;
		}
		$this->_case_study_db->set_id ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_case_study_db->get_status()) {
			return true;
		}
		$this->_case_study_db->set_status ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_case_study_db->get_name()) {
			return true;
		}
		$this->_case_study_db->set_name ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_category_id($val) {
		if ($val === $this->_case_study_db->get_category_id()) {
			return true;
		}
		$this->_case_study_db->set_category_id ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_service_type($val) {
		if ($val === $this->_case_study_db->get_service_type()) {
			return true;
		}
		$this->_case_study_db->set_service_type ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_quote_name($val) {
		if ($val === $this->_case_study_db->get_quote_name()) {
			return true;
		}
		$this->_case_study_db->set_quote_name ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_body($val) {
		if ($val === $this->_case_study_db->get_body()) {
			return true;
		}
		$this->_case_study_db->set_body ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_image_id($val) {
		if ($val === $this->_case_study_db->get_image_id()) {
			return true;
		}
		$this->_case_study_db->set_image_id ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_banner_id($val) {
		if ($val === $this->_case_study_db->get_banner_id()) {
			return true;
		}
		$this->_case_study_db->set_banner_id ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_logo_id($val) {
		if ($val === $this->_case_study_db->get_logo_id()) {
			return true;
		}
		$this->_case_study_db->set_logo_id ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_case_study_db->get_url()) {
			return true;
		}
		$this->_case_study_db->set_url ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_quote($val) {
		if ($val === $this->_case_study_db->get_quote()) {
			return true;
		}
		$this->_case_study_db->set_quote ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_summary($val) {
		if ($val === $this->_case_study_db->get_summary()) {
			return true;
		}
		$this->_case_study_db->set_summary ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_meta_description($val) {
		if ($val === $this->_case_study_db->get_meta_description()) {
			return true;
		}
		$this->_case_study_db->set_meta_description ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}
	function set_meta_title($val) {
		if ($val === $this->_case_study_db->get_meta_title()) {
			return true;
		}
		$this->_case_study_db->set_meta_title ($val);
		$this->_dirty['case_study'] = true;
		return true;
	}

}

?>
