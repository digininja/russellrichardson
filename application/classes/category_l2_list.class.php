<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/category_l2.search.php");

class category_l2_list extends base_list {
	private $_id;
	private $_category_l1_id;
	private $_name;
	private $_image_id;
	private $_banner_id;
	private $_summary;
	private $_body;
	private $_url;
	private $_meta_title;
	private $_meta_description;
	private $_status;
	private $_order_by;
	private $_page_number;
	private $_page_size;

	function __construct () {
		$this->_id = null;
		$this->_category_l1_id = null;
		$this->_name = null;
		$this->_image_id = null;
		$this->_banner_id = null;
		$this->_summary = null;
		$this->_body = null;
		$this->_url = null;
		$this->_meta_title = null;
		$this->_meta_description = null;
		$this->_status = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_id ($val) {
		$this->_id = intval ($val);
	}
	function set_category_l1_id ($val) {
		$this->_category_l1_id = intval ($val);
	}
	function set_name ($val) {
		$this->_name = strval ($val);
	}
	function set_image_id ($val) {
		$this->_image_id = intval ($val);
	}
	function set_banner_id ($val) {
		$this->_banner_id = intval ($val);
	}
	function set_summary ($val) {
		$this->_summary = strval ($val);
	}
	function set_body ($val) {
		$this->_body = strval ($val);
	}
	function set_url ($val) {
		$this->_url = strval ($val);
	}
	function set_meta_title ($val) {
		$this->_meta_title = strval ($val);
	}
	function set_meta_description ($val) {
		$this->_meta_description = strval ($val);
	}
	function set_status ($val) {
		$this->_status = strval ($val);
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
		
		if (!is_null ($this->_id)) {
			$options['id'] = $this->_id;
		}
		if (!is_null ($this->_category_l1_id)) {
			$options['category_l1_id'] = $this->_category_l1_id;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_image_id)) {
			$options['image_id'] = $this->_image_id;
		}
		if (!is_null ($this->_banner_id)) {
			$options['banner_id'] = $this->_banner_id;
		}
		if (!is_null ($this->_summary)) {
			$options['summary'] = $this->_summary;
		}
		if (!is_null ($this->_body)) {
			$options['body'] = $this->_body;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_category_l2_count ($options);

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
		
		if (!is_null ($this->_id)) {
			$options['id'] = $this->_id;
		}
		if (!is_null ($this->_category_l1_id)) {
			$options['category_l1_id'] = $this->_category_l1_id;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_image_id)) {
			$options['image_id'] = $this->_image_id;
		}
		if (!is_null ($this->_banner_id)) {
			$options['banner_id'] = $this->_banner_id;
		}
		if (!is_null ($this->_summary)) {
			$options['summary'] = $this->_summary;
		}
		if (!is_null ($this->_body)) {
			$options['body'] = $this->_body;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
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
	
		$data = get_category_l2s ($options);

		return ($data);
	}
}
?>