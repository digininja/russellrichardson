<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/cms_db.class.php");
#require_once ("application/classes/cms_cms_image_gallery_list.class.php");

function get_navigation($start_path = null) {
	return get_navigation_db($start_path);
}

class cms {
	var $_cms_db;
	var $_dirty;
	var $_images;

	function _make_clean() {
		$this->_dirty['cms'] = false;
		$this->_dirty['images'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_cms_db = new cms_db($id);
		} elseif (!is_null ($data)) {
			$cms_db = new cms_db();
			$cms_db->populate ($data);
			$this->_cms_db = $cms_db;
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_cms_db = new cms_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
		$this->_images = null;
	}

	function get_parent() {
		$parent_url = substr ($this->get_url(), 0, strrpos ($this->get_url(), "/"));
		if ($parent_url != "") {
			$parent = new cms();
			if ($parent->load_by_url ($parent_url)) {
				return $parent;
			}
		}

		return null;
	}

	function load_by_url ($url) {
		if ($url == "") {
			trigger_error ("Blank url passed to load_by_url function", E_USER_ERROR);
			return false;
		}
		if ($this->_cms_db->load_by_url ($url)) {
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
		if ($this->_cms_db->load_if_exists ($id)) {
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
		if ($this->_dirty['cms']) {
			$this->_cms_db->save();
		}

		if ($this->_dirty['images']) {
			if (is_array ($this->_images)) {
				$this->_cms_db->clear_all_images();
				foreach ($this->_images as $image_data) {
					$position = $image_data['position'];
					$image = $image_data['image'];
					$image->save();
					$cms_image = new cmss_image();
					$cms_image->set_cms_id ($this->get_id());
					$cms_image->set_image_id ($image->get_id());
					$cms_image->set_position ($position);
					$cms_image->save();
				}
			}
		}

		$this->_make_clean();
		return true;
	}

	function get_image_gallery_ids() {
		$cms_cms_image_gallery_list = new cms_cms_image_gallery_list();
		$cms_cms_image_gallery_list->set_cms_id($this->get_id());
		$cms_cms_image_gallery_list->set_just_ids();
		$list = $cms_cms_image_gallery_list->do_search();

		return $list;
	}
	function get_image_galleries() {
		$cms_cms_image_gallery_list = new cms_cms_image_gallery_list();
		$cms_cms_image_gallery_list->set_advice_id($this->get_id());
		$list = $cms_cms_image_gallery_list->do_search();

		return $list;
	}
	function get_id() {
		return ($this->_cms_db->get_id());
	}
	function get_type_id() {
		return ($this->_cms_db->get_type_id());
	}
	function get_thumb_image_id() {
		return ($this->_cms_db->get_thumb_image_id());
	}
	function get_site_section_code() {
		return ($this->_cms_db->get_site_section_code());
	}
	function get_status() {
		return ($this->_cms_db->get_status());
	}
	function get_date() {
		return ($this->_cms_db->get_date());
	}
	function get_title() {
		return ($this->_cms_db->get_title());
	}
	function get_tagline() {
		return ($this->_cms_db->get_tagline());
	}
	function get_summary() {
		return ($this->_cms_db->get_summary());
	}
	function get_body() {
		return ($this->_cms_db->get_body());
	}
	function get_start_date() {
		return ($this->_cms_db->get_start_date());
	}
	function get_end_date() {
		return ($this->_cms_db->get_end_date());
	}
	function get_to_homepage() {
		return ($this->_cms_db->get_to_homepage());
	}
	function get_weight() {
		return ($this->_cms_db->get_weight());
	}
	function get_meta_title() {
		return ($this->_cms_db->get_meta_title());
	}
	function get_meta_description() {
		return ($this->_cms_db->get_meta_description());
	}
	function get_meta_keywords() {
		return ($this->_cms_db->get_meta_keywords());
	}
	function get_url() {
		return ($this->_cms_db->get_url());
	}
	function get_sitemap_priority() {
		return ($this->_cms_db->get_sitemap_priority());
	}
	function get_sitemap_change_freq() {
		return ($this->_cms_db->get_sitemap_change_freq());
	}
	function get_last_modified() {
		return ($this->_cms_db->get_last_modified());
	}

	function set_id($val) {
		if ($val === $this->_cms_db->get_id()) {
			return true;
		}
		$this->_cms_db->set_id ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_type_id($val) {
		if ($val === $this->_cms_db->get_type_id()) {
			return true;
		}
		$this->_cms_db->set_type_id ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_thumb_image_id($val) {
		if ($val === $this->_cms_db->get_thumb_image_id()) {
			return true;
		}
		$this->_cms_db->set_thumb_image_id ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_site_section_code($val) {
		if ($val === $this->_cms_db->get_site_section_code()) {
			return true;
		}
		$this->_cms_db->set_site_section_code ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_cms_db->get_status()) {
			return true;
		}
		$this->_cms_db->set_status ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_date($val) {
		if ($val === $this->_cms_db->get_date()) {
			return true;
		}
		$this->_cms_db->set_date ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_title($val) {
		if ($val === $this->_cms_db->get_title()) {
			return true;
		}
		$this->_cms_db->set_title ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_tagline($val) {
		if ($val === $this->_cms_db->get_tagline()) {
			return true;
		}
		$this->_cms_db->set_tagline ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_summary($val) {
		if ($val === $this->_cms_db->get_summary()) {
			return true;
		}
		$this->_cms_db->set_summary ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_body($val) {
		if ($val === $this->_cms_db->get_body()) {
			return true;
		}
		$this->_cms_db->set_body ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_start_date($val) {
		if ($val === $this->_cms_db->get_start_date()) {
			return true;
		}
		$this->_cms_db->set_start_date ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_end_date($val) {
		if ($val === $this->_cms_db->get_end_date()) {
			return true;
		}
		$this->_cms_db->set_end_date ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_to_homepage($val) {
		if ($val === $this->_cms_db->get_to_homepage()) {
			return true;
		}
		$this->_cms_db->set_to_homepage ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_weight($val) {
		if ($val === $this->_cms_db->get_weight()) {
			return true;
		}
		$this->_cms_db->set_weight ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_meta_title($val) {
		if ($val === $this->_cms_db->get_meta_title()) {
			return true;
		}
		$this->_cms_db->set_meta_title ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_meta_description($val) {
		if ($val === $this->_cms_db->get_meta_description()) {
			return true;
		}
		$this->_cms_db->set_meta_description ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_meta_keywords($val) {
		if ($val === $this->_cms_db->get_meta_keywords()) {
			return true;
		}
		$this->_cms_db->set_meta_keywords ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_cms_db->get_url()) {
			return true;
		}
		$this->_cms_db->set_url ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_sitemap_priority($val) {
		if ($val === $this->_cms_db->get_sitemap_priority()) {
			return true;
		}
		$this->_cms_db->set_sitemap_priority ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_sitemap_change_freq($val) {
		if ($val === $this->_cms_db->get_sitemap_change_freq()) {
			return true;
		}
		$this->_cms_db->set_sitemap_change_freq ($val);
		$this->_dirty['cms'] = true;
		return true;
	}
	function set_last_modified($val) {
		if ($val === $this->_cms_db->get_last_modified()) {
			return true;
		}
		$this->_cms_db->set_last_modified ($val);
		$this->_dirty['cms'] = true;
		return true;
	}

	// Images
	function get_images() {
		if (is_null ($this->_images)) {
			$this->load_images();
		}
		return $this->_images;
	}
	function load_images() {
		$image_list = new image_list();
		$image_list->set_order_by ("id");
		$image_list->set_cms_id ($this->get_id());
		$this->_images = $image_list->do_search();
	}
	function add_image($image, $position) {
		if (is_null ($this->_images)) {
			$this->load_images();
		}
		$data = array (
						"image" => $image,
						"position" => $position,
					);
		$this->_images[] = $data;
		$this->_dirty['images'] = true;
	}
	function clear_images() {
		$this->_images = array();
		$this->_dirty['images'] = true;
	}
}

?>
