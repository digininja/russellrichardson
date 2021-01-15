<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/base_list.class.php");
require_once ("application/searches/cms.search.php");

class cms_list extends base_list {
	var $_id;
	var $_type_id;
	var $_thumb_image_id;
	var $_site_section_code;
	var $_status;
	var $_date;
	var $_title;
	var $_summary;
	var $_body;
	var $_keyword;
	var $_start_date;
	var $_end_date;
	var $_to_homepage;
	var $_weight;
	var $_meta_title;
	var $_meta_description;
	var $_meta_keywords;
	var $_url;
	var $_ignore_url;
	var $_url_starts;
	var $_sitemap_priority;
	var $_sitemap_change_freq;
	var $_last_modified;
	var $_order_by;
	var $_page_number;
	var $_page_size;

	function __construct () {
		$this->_id = null;
		$this->_type_id = null;
		$this->_thumb_image_id = null;
		$this->_site_section_code = null;
		$this->_status = null;
		$this->_date = null;
		$this->_title = null;
		$this->_summary = null;
		$this->_body = null;
		$this->_keyword = null;
		$this->_start_date = null;
		$this->_end_date = null;
		$this->_to_homepage = null;
		$this->_weight = null;
		$this->_meta_title = null;
		$this->_meta_description = null;
		$this->_meta_keywords = null;
		$this->_url = null;
		$this->_ignore_url = null;
		$this->_url_starts = null;
		$this->_sitemap_priority = null;
		$this->_sitemap_change_freq = null;
		$this->_last_modified = null;
		$this->_order_by = null;
		$this->_page_number = null;
		$this->_page_size = null;
	}

	function set_id ($val) {
		$this->_id = intval ($val);
	}
	function set_type_id ($val) {
		$this->_type_id = intval ($val);
	}
	function set_thumb_image_id ($val) {
		$this->_thumb_image_id = intval ($val);
	}
	function set_site_section_code ($val) {
		$this->_site_section_code = strval ($val);
	}
	function set_status ($val) {
		$this->_status = strval ($val);
	}
	function set_date ($val) {
		$this->_date = intval ($val);
	}
	function set_title ($val) {
		$this->_title = strval ($val);
	}
	function set_summary ($val) {
		$this->_summary = strval ($val);
	}
	function set_keyword ($val) {
		$this->_keyword = strval ($val);
	}
	function set_body ($val) {
		$this->_body = strval ($val);
	}
	function set_start_date ($val) {
		$this->_start_date = intval ($val);
	}
	function set_end_date ($val) {
		$this->_end_date = intval ($val);
	}
	function set_to_homepage ($val) {
		$this->_to_homepage = strval ($val);
	}
	function set_weight ($val) {
		$this->_weight = intval ($val);
	}
	function set_meta_title ($val) {
		$this->_meta_title = strval ($val);
	}
	function set_meta_description ($val) {
		$this->_meta_description = strval ($val);
	}
	function set_meta_keywords ($val) {
		$this->_meta_keywords = strval ($val);
	}
	function set_url_starts ($val) {
		$this->_url_starts = strval ($val);
	}
	function set_ignore_url ($val) {
		$this->_ignore_url = strval ($val);
	}
	function set_url ($val) {
		$this->_url = strval ($val);
	}
	function set_sitemap_priority ($val) {
		$this->_sitemap_priority = floatval ($val);
	}
	function set_sitemap_change_freq ($val) {
		$this->_sitemap_change_freq = strval ($val);
	}
	function set_last_modified ($val) {
		$this->_last_modified = intval ($val);
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
		if (!is_null ($this->_type_id)) {
			$options['type_id'] = $this->_type_id;
		}
		if (!is_null ($this->_thumb_image_id)) {
			$options['thumb_image_id'] = $this->_thumb_image_id;
		}
		if (!is_null ($this->_site_section_code)) {
			$options['site_section_code'] = $this->_site_section_code;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_date)) {
			$options['date'] = $this->_date;
		}
		if (!is_null ($this->_title)) {
			$options['title'] = $this->_title;
		}
		if (!is_null ($this->_summary)) {
			$options['summary'] = $this->_summary;
		}
		if (!is_null ($this->_keyword)) {
			$options['keyword'] = $this->_keyword;
		}
		if (!is_null ($this->_body)) {
			$options['body'] = $this->_body;
		}
		if (!is_null ($this->_start_date)) {
			$options['start_date'] = $this->_start_date;
		}
		if (!is_null ($this->_end_date)) {
			$options['end_date'] = $this->_end_date;
		}
		if (!is_null ($this->_to_homepage)) {
			$options['to_homepage'] = $this->_to_homepage;
		}
		if (!is_null ($this->_weight)) {
			$options['weight'] = $this->_weight;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_meta_keywords)) {
			$options['meta_keywords'] = $this->_meta_keywords;
		}
		if (!is_null ($this->_url_starts)) {
			$options['url_starts'] = $this->_url_starts;
		}
		if (!is_null ($this->_ignore_url)) {
			$options['ignore_url'] = $this->_ignore_url;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_sitemap_priority)) {
			$options['sitemap_priority'] = $this->_sitemap_priority;
		}
		if (!is_null ($this->_sitemap_change_freq)) {
			$options['sitemap_change_freq'] = $this->_sitemap_change_freq;
		}
		if (!is_null ($this->_last_modified)) {
			$options['last_modified'] = $this->_last_modified;
		}
		if (!is_null ($this->_page_number)) {
			$options['page_number'] = $this->_page_number;
		}
		if (!is_null ($this->_page_size)) {
			$options['page_size'] = $this->_page_size;
		}
	
		$count = get_cms_count ($options);

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
		if (!is_null ($this->_type_id)) {
			$options['type_id'] = $this->_type_id;
		}
		if (!is_null ($this->_thumb_image_id)) {
			$options['thumb_image_id'] = $this->_thumb_image_id;
		}
		if (!is_null ($this->_site_section_code)) {
			$options['site_section_code'] = $this->_site_section_code;
		}
		if (!is_null ($this->_status)) {
			$options['status'] = $this->_status;
		}
		if (!is_null ($this->_date)) {
			$options['date'] = $this->_date;
		}
		if (!is_null ($this->_title)) {
			$options['title'] = $this->_title;
		}
		if (!is_null ($this->_summary)) {
			$options['summary'] = $this->_summary;
		}
		if (!is_null ($this->_keyword)) {
			$options['keyword'] = $this->_keyword;
		}
		if (!is_null ($this->_body)) {
			$options['body'] = $this->_body;
		}
		if (!is_null ($this->_start_date)) {
			$options['start_date'] = $this->_start_date;
		}
		if (!is_null ($this->_end_date)) {
			$options['end_date'] = $this->_end_date;
		}
		if (!is_null ($this->_to_homepage)) {
			$options['to_homepage'] = $this->_to_homepage;
		}
		if (!is_null ($this->_weight)) {
			$options['weight'] = $this->_weight;
		}
		if (!is_null ($this->_meta_title)) {
			$options['meta_title'] = $this->_meta_title;
		}
		if (!is_null ($this->_meta_description)) {
			$options['meta_description'] = $this->_meta_description;
		}
		if (!is_null ($this->_meta_keywords)) {
			$options['meta_keywords'] = $this->_meta_keywords;
		}
		if (!is_null ($this->_ignore_url)) {
			$options['ignore_url'] = $this->_ignore_url;
		}
		if (!is_null ($this->_url_starts)) {
			$options['url_starts'] = $this->_url_starts;
		}
		if (!is_null ($this->_url)) {
			$options['url'] = $this->_url;
		}
		if (!is_null ($this->_sitemap_priority)) {
			$options['sitemap_priority'] = $this->_sitemap_priority;
		}
		if (!is_null ($this->_sitemap_change_freq)) {
			$options['sitemap_change_freq'] = $this->_sitemap_change_freq;
		}
		if (!is_null ($this->_last_modified)) {
			$options['last_modified'] = $this->_last_modified;
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
	
		$data = get_cmss ($options);

		return ($data);
	}
}
?>
