<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/admin_login.search.php");

class admin_login_list extends base_list {
	var $_username;
	var $_status;
	var $_password;
	var $_name;
	var $_description;
	var $_order_by;
	var $_page_number;
	var $_page_size;

	function admin_login_list () {
		$this->_username = null;
		$this->_status = null;
		$this->_password = null;
		$this->_name = null;
		$this->_description = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_username ($val) {
		$this->_username = strval ($val);
	}
	function set_status ($val) {
		$this->_status = strval ($val);
	}
	function set_password ($val) {
		$this->_password = strval ($val);
	}
	function set_name ($val) {
		$this->_name = strval ($val);
	}
	function set_description ($val) {
		$this->_description = strval ($val);
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
		
		if (!is_null ($this->_username)) {
			$options['username'] = $this->_username;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_password)) {
			$options['password'] = $this->_password;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_description)) {
			$options['description'] = $this->_description;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_admin_login_count ($options);

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
		
		if (!is_null ($this->_username)) {
			$options['username'] = $this->_username;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_password)) {
			$options['password'] = $this->_password;
		}
		if (!is_null ($this->_name)) {
			$options['name'] = $this->_name;
		}
		if (!is_null ($this->_description)) {
			$options['description'] = $this->_description;
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
	
		$data = get_admin_logins ($options);

		return ($data);
	}
}
?>
