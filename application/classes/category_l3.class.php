<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/category_l3_db.class.php");
require_once ("application/classes/category_l2.class.php");
require_once ("application/classes/category_l3_cms_image_gallery.class.php");
require_once ("application/classes/category_l3_cms_image_gallery_list.class.php");
require_once ("application/classes/cms_image_gallery.class.php");
require_once ("application/classes/category_l3_linked_category_l3.class.php");
require_once ("application/classes/category_l3_linked_category_l3_list.class.php");
require_once ("application/classes/category_l3_advice_l3.class.php");
require_once ("application/classes/category_l3_advice_l3_list.class.php");
require_once ("application/classes/advice_l3.class.php");

class category_l3 {
	private $_category_l3_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['category_l3'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_category_l3_db = new category_l3_db($id);
		} elseif (!is_null ($data)) {
			$this->_category_l3_db = new category_l3_db();
			$this->_category_l3_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_category_l3_db = new category_l3_db();
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
		if ($this->_category_l3_db->load_by_url ($url)) {
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
		if ($this->_category_l3_db->load_if_exists ($id)) {
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
		if ($this->_dirty['category_l3']) {
			$this->_category_l3_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function add_cms_image_gallery ($cms_image_gallery_id) {
		$category_l3_cms_image_gallery = new category_l3_cms_image_gallery();
		$category_l3_cms_image_gallery->set_category_l3_id ($this->get_id());
		$category_l3_cms_image_gallery->set_cms_image_gallery_id ($cms_image_gallery_id);
		$category_l3_cms_image_gallery->save();
	}

	function clear_cms_image_gallerys() {
		category_l3_cms_image_gallery_db::clear_for_category_l3($this->get_id());
	}

	function get_cms_image_gallery_ids() {
		$category_l3_cms_image_gallery_list = new category_l3_cms_image_gallery_list();
		$category_l3_cms_image_gallery_list->set_category_l3_id ($this->get_id());
		$category_l3_cms_image_gallery_list->set_id_only ();
		$cms_image_gallery_ids = $category_l3_cms_image_gallery_list->do_search();

		return $cms_image_gallery_ids;
	}

	function get_gallery_images() {
		$category_l3_cms_image_gallery_list = new category_l3_cms_image_gallery_list();
		$category_l3_cms_image_gallery_list->set_category_l3_id ($this->get_id());
		$category_l3_cms_image_gallery_list->set_order_by ("name");
		$cms_image_gallerys = $category_l3_cms_image_gallery_list->do_search();

		$images = array();
		foreach ($cms_image_gallerys as $gallery) {
			$image = $gallery->get_image();
			$images[] = $image;
		}

		return $images;
	}

	function get_cms_image_gallerys() {
		$category_l3_cms_image_gallery_list = new category_l3_cms_image_gallery_list();
		$category_l3_cms_image_gallery_list->set_category_l3_id ($this->get_id());
		$category_l3_cms_image_gallery_list->set_order_by ("name");
		$cms_image_gallerys = $category_l3_cms_image_gallery_list->do_search();

		return $cms_image_gallerys;
	}

	function add_advice_l3 ($advice_l3_id) {
		$category_l3_advice_l3 = new category_l3_advice_l3();
		$category_l3_advice_l3->set_category_l3_id ($this->get_id());
		$category_l3_advice_l3->set_advice_l3_id ($advice_l3_id);
		$category_l3_advice_l3->save();
	}

	function clear_advice_l3s() {
		category_l3_advice_l3_db::clear_for_category_l3($this->get_id());
	}

	function get_advice_l3_ids() {
		$category_l3_advice_l3_list = new category_l3_advice_l3_list();
		$category_l3_advice_l3_list->set_category_l3_id ($this->get_id());
		$category_l3_advice_l3_list->set_id_only ();
		$advice_l3_ids = $category_l3_advice_l3_list->do_search();

		return $advice_l3_ids;
	}

	function get_advice_l3s() {
		$category_l3_advice_l3_list = new category_l3_advice_l3_list();
		$category_l3_advice_l3_list->set_category_l3_id ($this->get_id());
		$category_l3_advice_l3_list->set_order_by ("name");
		$advice_l3s = $category_l3_advice_l3_list->do_search();

		return $advice_l3s;
	}


	function add_linked_category_l3 ($linked_category_l3_id) {
		$category_l3_linked_category_l3 = new category_l3_linked_category_l3();
		$category_l3_linked_category_l3->set_category_l3_id ($this->get_id());
		$category_l3_linked_category_l3->set_linked_category_l3_id ($linked_category_l3_id);
		$category_l3_linked_category_l3->save();
	}

	function clear_linked_category_l3s() {
		category_l3_linked_category_l3_db::clear_for_category_l3($this->get_id());
	}

	function get_linked_category_l3_ids() {
		$category_l3_linked_category_l3_list = new category_l3_linked_category_l3_list();
		$category_l3_linked_category_l3_list->set_category_l3_id ($this->get_id());
		$category_l3_linked_category_l3_list->set_id_only ();
		$linked_category_l3_ids = $category_l3_linked_category_l3_list->do_search();

		return $linked_category_l3_ids;
	}

	function get_linked_category_l3s() {
		$category_l3_linked_category_l3_list = new category_l3_linked_category_l3_list();
		$category_l3_linked_category_l3_list->set_category_l3_id ($this->get_id());
		$category_l3_linked_category_l3_list->set_order_by ("name");
		$linked_category_l3s = $category_l3_linked_category_l3_list->do_search();

		return $linked_category_l3s;
	}

	function get_parent() {
		$category_l2 = new category_l2($this->get_category_l2_id());
		return $category_l2;
	}
	function get_id() {
		return ($this->_category_l3_db->get_id());
	}
	function get_category_l2_id() {
		return ($this->_category_l3_db->get_category_l2_id());
	}
	function get_name() {
		return ($this->_category_l3_db->get_name());
	}
	function get_image_id() {
		return ($this->_category_l3_db->get_image_id());
	}
	function get_image() {
		if (!is_null ($this->get_image_id()) && $this->get_image_id() != 0) {
			$image = new image($this->get_image_id());
			return $image;
		}
		return null;
	}
	function get_banner_id() {
		return ($this->_category_l3_db->get_banner_id());
	}
	function get_banner() {
		if (!is_null ($this->get_banner_id()) && $this->get_banner_id() != 0) {
			$banner = new image($this->get_banner_id());
			return $banner;
		}
		return null;
	}
	function get_summary() {
		return ($this->_category_l3_db->get_summary());
	}
	function get_body() {
		return ($this->_category_l3_db->get_body());
	}
	function get_video_url() {
		return ($this->_category_l3_db->get_video_url());
	}
	function get_url() {
		return ($this->_category_l3_db->get_url());
	}
	function get_meta_title() {
		return ($this->_category_l3_db->get_meta_title());
	}
	function get_meta_description() {
		return ($this->_category_l3_db->get_meta_description());
	}
	function get_status() {
		return ($this->_category_l3_db->get_status());
	}
	function get_onsite_shredding() {
		return ($this->_category_l3_db->get_onsite_shredding());
	}
	function get_offsite_shredding() {
		return ($this->_category_l3_db->get_offsite_shredding());
	}
	function get_adhoc_collections() {
		return ($this->_category_l3_db->get_adhoc_collections());
	}
	function get_containers_provided() {
		return ($this->_category_l3_db->get_containers_provided());
	}
	function get_regular_collections() {
		return ($this->_category_l3_db->get_regular_collections());
	}

	function set_id($val) {
		if ($val === $this->_category_l3_db->get_id()) {
			return true;
		}
		$this->_category_l3_db->set_id ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_category_l2_id($val) {
		if ($val === $this->_category_l3_db->get_category_l2_id()) {
			return true;
		}
		$this->_category_l3_db->set_category_l2_id ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_category_l3_db->get_name()) {
			return true;
		}
		$this->_category_l3_db->set_name ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_image_id($val) {
		if ($val === $this->_category_l3_db->get_image_id()) {
			return true;
		}
		$this->_category_l3_db->set_image_id ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_banner_id($val) {
		if ($val === $this->_category_l3_db->get_banner_id()) {
			return true;
		}
		$this->_category_l3_db->set_banner_id ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_summary($val) {
		if ($val === $this->_category_l3_db->get_summary()) {
			return true;
		}
		$this->_category_l3_db->set_summary ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_body($val) {
		if ($val === $this->_category_l3_db->get_body()) {
			return true;
		}
		$this->_category_l3_db->set_body ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_video_url($val) {
		if ($val === $this->_category_l3_db->get_video_url()) {
			return true;
		}
		$this->_category_l3_db->set_video_url ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_category_l3_db->get_url()) {
			return true;
		}
		$this->_category_l3_db->set_url ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_meta_title($val) {
		if ($val === $this->_category_l3_db->get_meta_title()) {
			return true;
		}
		$this->_category_l3_db->set_meta_title ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_meta_description($val) {
		if ($val === $this->_category_l3_db->get_meta_description()) {
			return true;
		}
		$this->_category_l3_db->set_meta_description ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_category_l3_db->get_status()) {
			return true;
		}
		$this->_category_l3_db->set_status ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_offsite_shredding($val) {
		if ($val === $this->_category_l3_db->get_offsite_shredding()) {
			return true;
		}
		$this->_category_l3_db->set_offsite_shredding ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_onsite_shredding($val) {
		if ($val === $this->_category_l3_db->get_onsite_shredding()) {
			return true;
		}
		$this->_category_l3_db->set_onsite_shredding ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_adhoc_collections($val) {
		if ($val === $this->_category_l3_db->get_adhoc_collections()) {
			return true;
		}
		$this->_category_l3_db->set_adhoc_collections ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_containers_provided($val) {
		if ($val === $this->_category_l3_db->get_containers_provided()) {
			return true;
		}
		$this->_category_l3_db->set_containers_provided ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}
	function set_regular_collections($val) {
		if ($val === $this->_category_l3_db->get_regular_collections()) {
			return true;
		}
		$this->_category_l3_db->set_regular_collections ($val);
		$this->_dirty['category_l3'] = true;
		return true;
	}

}

?>
