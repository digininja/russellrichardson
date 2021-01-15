<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/banner.search.php");

class banner_list extends base_list {
	var $_id;
	var $_title;
	var $_copy;
	var $_url;
	var $_service_id;
	var $_homepage;
	var $_order_by;
	var $_page_number;
	var $_page_size;

	function __construct () {
		$this->_id = null;
		$this->_title = null;
		$this->_copy = null;
		$this->_url = null;
		$this->_service_id = null;
		$this->_homepage = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_id ($val) {
		$this->_id = intval ($val);
	}
	function set_title ($val) {
		$this->_title = strval ($val);
	}
	function set_copy ($val) {
		$this->_copy = strval ($val);
	}
	function set_homepage () {
		$this->_homepage = true;
	}
	function set_service_id ($val) {
		$this->_service_id = strval ($val);
	}
	function set_url ($val) {
		$this->_url = strval ($val);
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
		if (!is_null ($this->_title)) {
			$options['title'] = $this->_title;
		}
		if (!is_null ($this->_copy)) {
			$options['copy'] = $this->_copy;
		}
		if (!is_null ($this->_homepage)) {
			$options['homepage'] = $this->_homepage;
		}
		if (!is_null ($this->_service_id)) {
			$options['service_id'] = $this->_service_id;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_banner_count ($options);

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
		if (!is_null ($this->_title)) {
			$options['title'] = $this->_title;
		}
		if (!is_null ($this->_copy)) {
			$options['copy'] = $this->_copy;
		}
		if (!is_null ($this->_homepage)) {
			$options['homepage'] = $this->_homepage;
		}
		if (!is_null ($this->_service_id)) {
			$options['service_id'] = $this->_service_id;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
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
	
		$data = get_banners ($options);

		return ($data);
	}
}
?>
