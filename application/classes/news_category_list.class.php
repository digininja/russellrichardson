<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/news_category.search.php");

class news_category_list extends base_list {
	private $_id;
	private $_status;
	private $_name;
	private $_url;
	private $_meta_description;
	private $_meta_title;
	private $_image_id;
	private $_order_by;
	private $_page_number;
	private $_page_size;

	function __construct () {
		$this->_id = null;
		$this->_status = null;
		$this->_name = null;
		$this->_url = null;
		$this->_meta_description = null;
		$this->_meta_title = null;
		$this->_image_id = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_id ($val) {
		$this->_id = intval ($val);
	}
	function set_status ($val) {
		$this->_status = strval ($val);
	}
	function set_name ($val) {
		$this->_name = strval ($val);
	}
	function set_url ($val) {
		$this->_url = strval ($val);
	}
	function set_meta_description ($val) {
		$this->_meta_description = strval ($val);
	}
	function set_meta_title ($val) {
		$this->_meta_title = strval ($val);
	}
	function set_image_id ($val) {
		$this->_image_id = intval ($val);
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
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_image_id)) {
			$options['image_id'] = $this->_image_id;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_news_category_count ($options);

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
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_image_id)) {
			$options['image_id'] = $this->_image_id;
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
	
		$data = get_news_categorys ($options);

		return ($data);
	}
}
?>