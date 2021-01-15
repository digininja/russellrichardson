<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/category_l3_advice_l3.search.php");

class category_l3_advice_l3_list extends base_list {
	private $_category_l3_id;
	private $_image_id;
	private $_id_only;
	private $_order_by;
	private $_page_number;
	private $_page_size;

	function __construct () {
		$this->_category_l3_id = null;
		$this->_image_id = null;
		$this->_id_only = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_category_l3_id ($val) {
		$this->_category_l3_id = intval ($val);
	}
	function set_id_only () {
		$this->_id_only = true;
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
		
		if (!is_null ($this->_category_l3_id)) {
			$options['category_l3_id'] = $this->_category_l3_id;
		}
		if (!is_null ($this->_id_only)) {
			$options['id_only'] = $this->_id_only;
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
	
		$count = get_category_l3_advice_l3_count ($options);

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
		
		if (!is_null ($this->_category_l3_id)) {
			$options['category_l3_id'] = $this->_category_l3_id;
		}
		if (!is_null ($this->_id_only)) {
			$options['id_only'] = $this->_id_only;
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
	
		$data = get_category_l3_advice_l3s ($options);

		return ($data);
	}
}
?>
