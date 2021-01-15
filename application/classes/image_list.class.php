<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/image.search.php");

class image_list extends base_list {
	private $_id;
	private $_filename;
	private $_gallery_id;
	private $_width;
	private $_height;
	private $_alt_text;
	private $_inc_vips;
	private $_mime_type;
	private $_order_by;
	private $_page_number;
	private $_page_size;
	private $_category_id;
	private $_gallery_id;
	private $_service_id;
	private $_case_study_id;
	private $_tech_article_id;
	private $_vip_id;
	private $_join_table;
	private $_join_id;

	function image_list () {
		$this->_join_id = null;
		$this->_join_table = null;
		$this->_category_id = null;
		$this->_gallery_id = null;
		$this->_tech_article_id = null;
		$this->_vip_id = null;
		$this->_service_id = null;
		$this->_case_study_id = null;
		$this->_id = null;
		$this->_filename = null;
		$this->_gallery_id = null;
		$this->_width = null;
		$this->_height = null;
		$this->_alt_text = null;
		$this->_inc_vips = null;
		$this->_mime_type = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_tech_article_id ($val) {
		$this->_tech_article_id = intval ($val);
	}
	function set_case_study_id ($val) {
		$this->_case_study_id = intval ($val);
	}
	function set_service_id ($val) {
		$this->_service_id = intval ($val);
	}
	function set_vip_id ($val) {
		$this->_vip_id = intval ($val);
	}
	function set_gallery_id ($val) {
		$this->_gallery_id = intval ($val);
	}
	function set_category_id ($val) {
		$this->_category_id = intval ($val);
	}
	function set_join_table ($val) {
		$this->_join_table = strval ($val);
	}
	function set_join_id ($val) {
		$this->_join_id = intval ($val);
	}
	function set_id ($val) {
		$this->_id = intval ($val);
	}
	function set_gallery_id ($val) {
		$this->_gallery_id = strval ($val);
	}
	function set_filename ($val) {
		$this->_filename = strval ($val);
	}
	function set_width ($val) {
		$this->_width = intval ($val);
	}
	function set_height ($val) {
		$this->_height = intval ($val);
	}
	function set_inc_vips () {
		$this->_inc_vips = true;
	}
	function set_alt_text ($val) {
		$this->_alt_text = strval ($val);
	}
	function set_mime_type ($val) {
		$this->_mime_type = strval ($val);
	}
	function set_order_by ($val) {
		$this->_order_by = $val;
	}
	function set_page_size ($val) {
		$this->_page_size = $val;
	}
	function set_page_number ($val) {
		$this->_page_number = $val;
	}

	function get_page_details () {
		$options = array();
		
		if (!is_null ($this->_tech_article_id)) {
			$options['tech_article_id'] = $this->_tech_article_id;
		}
		if (!is_null ($this->_vip_id)) {
			$options['vip_id'] = $this->_vip_id;
		}
		if (!is_null ($this->_case_study_id)) {
			$options['case_study_id'] = $this->_case_study_id;
		}
		if (!is_null ($this->_service_id)) {
			$options['service_id'] = $this->_service_id;
		}
		if (!is_null ($this->_gallery_id)) {
			$options['gallery_id'] = $this->_gallery_id;
		}
		if (!is_null ($this->_category_id)) {
			$options['category_id'] = $this->_category_id;
		}
		if (!is_null ($this->_join_table)) {
			$options['join_table'] = $this->_join_table;
		}
		if (!is_null ($this->_join_id)) {
			$options['join_id'] = $this->_join_id;
		}
		if (!is_null ($this->_id)) {
			$options['id'] = $this->_id;
		}
		if (!is_null ($this->_gallery_id)) {
			$options['gallery_id'] = $this->_gallery_id;
		}
		if (!is_null ($this->_filename)) {
			$options['filename'] = $this->_filename;
		}
		if (!is_null ($this->_width)) {
			$options['width'] = $this->_width;
		}
		if (!is_null ($this->_height)) {
			$options['height'] = $this->_height;
		}
		if (!is_null ($this->_inc_vips)) {
			$options['inc_vips'] = $this->_inc_vips;
		}
		if (!is_null ($this->_alt_text)) {
			$options['alt_text'] = $this->_alt_text;
		}
		if (!is_null ($this->_mime_type)) {
			$options['mime_type'] = $this->_mime_type;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_image_count ($options);

		$details['count'] = $count;

		if (is_null ($this->_page_size)) {
			$details['pages'] = null;
		} else {
			$details['pages'] = ceil ($count/$this->_page_size);
		}

		return $details;
	}

	function do_search () {
		$options = array();
		
		if (!is_null ($this->_tech_article_id)) {
			$options['tech_article_id'] = $this->_tech_article_id;
		}
		if (!is_null ($this->_vip_id)) {
			$options['vip_id'] = $this->_vip_id;
		}
		if (!is_null ($this->_case_study_id)) {
			$options['case_study_id'] = $this->_case_study_id;
		}
		if (!is_null ($this->_service_id)) {
			$options['service_id'] = $this->_service_id;
		}
		if (!is_null ($this->_category_id)) {
			$options['category_id'] = $this->_category_id;
		}
		if (!is_null ($this->_join_table)) {
			$options['join_table'] = $this->_join_table;
		}
		if (!is_null ($this->_join_id)) {
			$options['join_id'] = $this->_join_id;
		}
		if (!is_null ($this->_id)) {
			$options['id'] = $this->_id;
		}
		if (!is_null ($this->_gallery_id)) {
			$options['gallery_id'] = $this->_gallery_id;
		}
		if (!is_null ($this->_filename)) {
			$options['filename'] = $this->_filename;
		}
		if (!is_null ($this->_width)) {
			$options['width'] = $this->_width;
		}
		if (!is_null ($this->_height)) {
			$options['height'] = $this->_height;
		}
		if (!is_null ($this->_gallery_id)) {
			$options['gallery_id'] = $this->_gallery_id;
		}
		if (!is_null ($this->_alt_text)) {
			$options['alt_text'] = $this->_alt_text;
		}
		if (!is_null ($this->_mime_type)) {
			$options['mime_type'] = $this->_mime_type;
		}
		if (!is_null ($this->_order_by)) {
			$options['order_by'] = $this->_order_by;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$data = get_images ($options);

		return ($data);
	}
}
?>
