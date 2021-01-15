<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}

class base_list {

	private $_page_size = null;
	private $_page_number = null;

	function set_page_size($val) {
		if (is_int ($val)) {
			$this->_page_size = $val;
		} else {
			$this->_page_size = PAGE_SIZE;
		}
	}
	function set_page_number ($val) {
		if (is_int ($val)) {
			$this->_page_number = $val;
		} else {
			$this->_page_number = 1;
		}
	}
}
?>
