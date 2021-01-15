<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/news_category_db.class.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/news_list.class.php");

class news_category {
	private $_news_category_db;
	private $_dirty;

	function _make_clean() {
		$this->_dirty['news_category'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_news_category_db = new news_category_db($id);
		} elseif (!is_null ($data)) {
			$this->_news_category_db = new news_category_db();
			$this->_news_category_db->populate ($data);
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_news_category_db = new news_category_db();
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
		if ($this->_news_category_db->load_if_exists ($id)) {
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
		if ($this->_dirty['news_category']) {
			$this->_news_category_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_children() {
		$news_list = new news_list();
		$news_list->set_order_by ("name");
		$news_list->set_category_id ($this->get_id());
		$news_articles = $news_list->do_search();

		return $news_articles;
	}

	function get_id() {
		return ($this->_news_category_db->get_id());
	}
	function get_status() {
		return ($this->_news_category_db->get_status());
	}
	function get_name() {
		return ($this->_news_category_db->get_name());
	}
	function get_url() {
		return ($this->_news_category_db->get_url());
	}
	function get_meta_description() {
		return ($this->_news_category_db->get_meta_description());
	}
	function get_meta_title() {
		return ($this->_news_category_db->get_meta_title());
	}
	function get_image_id() {
		return ($this->_news_category_db->get_image_id());
	}
	function get_image() {
		if (!is_null ($this->get_image_id()) && $this->get_image_id() != 0) {
			$image = new image($this->get_image_id());
			return $image;
		}
		return null;
	}

	function set_id($val) {
		if ($val === $this->_news_category_db->get_id()) {
			return true;
		}
		$this->_news_category_db->set_id ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_status($val) {
		if ($val === $this->_news_category_db->get_status()) {
			return true;
		}
		$this->_news_category_db->set_status ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_name($val) {
		if ($val === $this->_news_category_db->get_name()) {
			return true;
		}
		$this->_news_category_db->set_name ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_url($val) {
		if ($val === $this->_news_category_db->get_url()) {
			return true;
		}
		$this->_news_category_db->set_url ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_meta_description($val) {
		if ($val === $this->_news_category_db->get_meta_description()) {
			return true;
		}
		$this->_news_category_db->set_meta_description ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_meta_title($val) {
		if ($val === $this->_news_category_db->get_meta_title()) {
			return true;
		}
		$this->_news_category_db->set_meta_title ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}
	function set_image_id($val) {
		if ($val === $this->_news_category_db->get_image_id()) {
			return true;
		}
		$this->_news_category_db->set_image_id ($val);
		$this->_dirty['news_category'] = true;
		return true;
	}

}

?>
