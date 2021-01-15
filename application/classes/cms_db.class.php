<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

function get_navigation_db($start_path) {
	$dbh = new database();

	$start_path_where = "";
	if (!is_null ($start_path)) {
		$start_path_where = " cmss.cms_url LIKE " . $dbh->make_sql_value ($start_path . "%") . " AND ";
	}

	$query = "SELECT
				cmss.cms_id,
				cmss.cms_title,
				cmss.cms_tagline,
				cmss.cms_url
			FROM cmss
			WHERE
				$start_path_where
				cmss.cms_status <> 'deleted' AND
				1=1
			ORDER BY cmss.cms_weight DESC, cmss.cms_title
			";
	#print $query;
	$result = $dbh->execute ($query);

	#$depth_of_this_cms = count (explode ("/", $this->get_url()));
	#print ("Depth = " . $depth_of_this_cms);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
	#	if (count (explode ("/", $row['cms_url'])) == $depth_of_this_cms + 1) {
			$item = array (
							"id" => $row['cms_id'],
							"title" => $row['cms_title'],
							"url" => $row['cms_url'],
							"tagline" => $row['cms_tagline'],
							"depth" => count (explode ("/", $row['cms_url'])),
						);
			$parent_url = substr ($row['cms_url'], 0, strrpos ($row['cms_url'], "/"));
			if ($parent_url == "") {
				$parent_url = "/";
			}
			if (!array_key_exists ($parent_url, $data)) {
				$data[$parent_url] = array();
			}
			$data[$parent_url][] = $item;
	#	}
	}

#	$data = $data[""];
	#var_dump_pre ($data);
	return $data;
}

class cms_db {
	var $_dbh;
	var $_id;
	var $_type_id;
	var $_thumb_image_id;
	var $_site_section_code;
	var $_status;
	var $_date;
	var $_title;
	var $_summary;
	var $_tagline;
	var $_body;
	var $_start_date;
	var $_end_date;
	var $_to_homepage;
	var $_weight;
	var $_meta_title;
	var $_meta_description;
	var $_meta_keywords;
	var $_url;
	var $_sitemap_priority;
	var $_sitemap_change_freq;
	var $_last_modified;

	function connect() {
		if (is_null ($this->_dbh) || database::link == 0) {
			$this->_dbh = new database();
		}
	}

	function __construct ($id = null) {
		
		if (is_null ($id)) {
			$this->_id = null;
			$this->_type_id = null;
			$this->_thumb_image_id = null;
			$this->_site_section_code = null;
			$this->_status = null;
			$this->_date = null;
			$this->_title = null;
			$this->_summary = null;
			$this->_tagline = null;
			$this->_body = null;
			$this->_start_date = null;
			$this->_end_date = null;
			$this->_to_homepage = null;
			$this->_weight = null;
			$this->_meta_title = null;
			$this->_meta_description = null;
			$this->_meta_keywords = null;
			$this->_url = null;
			$this->_sitemap_priority = null;
			$this->_sitemap_change_freq = null;
			$this->_last_modified = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function clear_all_promos($location) {
		// this is needed just in case the object was put in a session
		// in which case the db connection is lost and so needs re-establishing
		

		$query = "DELETE
					cmss_promos
					FROM cmss_promos
					INNER JOIN promos ON promos.promo_id = cmss_promos.promo_id
					WHERE
						cmss_promos.cms_id = " . database::make_sql_value ($this->get_id()) . " AND
						promos.promo_location = " . database::make_sql_value ($location) . "
			";

		$res = database::execute ($query);
		return true;
	}

	function load_by_url ($url) {
		if ($url == "") {
			trigger_error ("Blank url passed to load_by_url function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				cmss.cms_id,
				cmss.cms_type_id,
				cmss.thumb_image_id,
				cmss.site_section_code,
				cmss.cms_status,
				cmss.cms_date,
				cmss.cms_title,
				cmss.cms_summary,
				cmss.cms_tagline,
				cmss.cms_body,
				cmss.cms_start_date,
				cmss.cms_end_date,
				cmss.cms_to_homepage,
				cmss.cms_weight,
				cmss.cms_meta_title,
				cmss.cms_meta_description,
				cmss.cms_meta_keywords,
				cmss.cms_url,
				cmss.cms_sitemap_priority,
				cmss.cms_sitemap_change_freq,
				cmss.cms_last_modified
			FROM cmss
			WHERE
				cmss.cms_url = " . database::make_sql_value ($url) . " AND 
				cmss.cms_status <> 'deleted' AND
				1=1
			";
		//print $query;
		$res = database::execute ($query);
		if (mysqli_num_rows ($res) == 1) {
			$row = mysqli_fetch_assoc ($res);
			$this->populate ($row);
		} else {
			return false;
		}
		return true;
	}

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				cmss.cms_id,
				cmss.cms_type_id,
				cmss.thumb_image_id,
				cmss.site_section_code,
				cmss.cms_status,
				cmss.cms_date,
				cmss.cms_title,
				cmss.cms_summary,
				cmss.cms_tagline,
				cmss.cms_body,
				cmss.cms_start_date,
				cmss.cms_end_date,
				cmss.cms_to_homepage,
				cmss.cms_weight,
				cmss.cms_meta_title,
				cmss.cms_meta_description,
				cmss.cms_meta_keywords,
				cmss.cms_url,
				cmss.cms_sitemap_priority,
				cmss.cms_sitemap_change_freq,
				cmss.cms_last_modified
			FROM cmss
			WHERE
				cms_id = " . database::make_sql_value ($id) . " AND 
				cmss.cms_status <> 'deleted' AND
				1=1
			";
		$res = database::execute ($query);
		if (mysqli_num_rows ($res) == 1) {
			$row = mysqli_fetch_assoc ($res);
			$this->populate ($row);
		} else {
			return false;
		}
		return true;
	}

	function load ($id) {
		if ($this->load_if_exists ($id)) {
			return true;
		} else {
			trigger_error ("cms_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		// this is needed just in case the object was put in a session
		// in which case the db connection is lost and so needs re-establishing
		

		$query = "REPLACE INTO cmss (
				cms_id,
				cms_type_id,
				thumb_image_id,
				site_section_code,
				cms_status,
				cms_date,
				cms_title,
				cms_summary,
				cms_tagline,
				cms_body,
				cms_start_date,
				cms_end_date,
				cms_to_homepage,
				cms_weight,
				cms_meta_title,
				cms_meta_description,
				cms_meta_keywords,
				cms_url,
				cms_sitemap_priority,
				cms_sitemap_change_freq,
				cms_last_modified
			) VALUES (
				" . database::make_sql_value ($this->get_id()) . ",
				" . database::make_sql_value ($this->get_type_id()) . ",
				" . database::make_sql_value ($this->get_thumb_image_id()) . ",
				" . database::make_sql_value ($this->get_site_section_code()) . ",
				" . database::make_sql_value ($this->get_status()) . ",
				" . database::make_sql_value (database::make_sql_date ($this->get_date())) . ",
				" . database::make_sql_value ($this->get_title()) . ",
				" . database::make_sql_value ($this->get_summary()) . ",
				" . database::make_sql_value ($this->get_tagline()) . ",
				" . database::make_sql_value ($this->get_body()) . ",
				" . database::make_sql_value (database::make_sql_date ($this->get_start_date())) . ",
				" . database::make_sql_value (database::make_sql_date ($this->get_end_date())) . ",
				" . database::make_sql_value ($this->get_to_homepage()) . ",
				" . database::make_sql_value ($this->get_weight()) . ",
				" . database::make_sql_value ($this->get_meta_title()) . ",
				" . database::make_sql_value ($this->get_meta_description()) . ",
				" . database::make_sql_value ($this->get_meta_keywords()) . ",
				" . database::make_sql_value ($this->get_url()) . ",
				" . database::make_sql_value ($this->get_sitemap_priority()) . ",
				" . database::make_sql_value ($this->get_sitemap_change_freq()) . ",
				" . database::make_sql_value (database::make_sql_date (time())) . "
			)";

		#print $query;exit;
		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_id = intval ($row['cms_id']);
		$this->_type_id = intval ($row['cms_type_id']);
		$this->_thumb_image_id = intval ($row['thumb_image_id']);
		if (is_null ($row['site_section_code'])) {
			$this->_site_section_code = null;
		} else {
			$this->_site_section_code = strval ($row['site_section_code']);
		}
		$this->_status = strval ($row['cms_status']);
		$this->_date = strtotime ($row['cms_date']);
		$this->_title = strval ($row['cms_title']);
		$this->_summary = strval ($row['cms_summary']);
		$this->_tagline = strval ($row['cms_tagline']);
		$this->_body = strval ($row['cms_body']);
		if (is_null ($row['cms_start_date'])) {
			$this->_start_date = null;
		} else {
			$this->_start_date = strtotime ($row['cms_start_date']);
		}
		if (is_null ($row['cms_end_date'])) {
			$this->_end_date = null;
		} else {
			$this->_end_date = strtotime ($row['cms_end_date']);
		}
		$this->_to_homepage = strval ($row['cms_to_homepage']);
		$this->_weight = intval ($row['cms_weight']);
		if (is_null ($row['cms_meta_title'])) {
			$this->_meta_title = null;
		} else {
			$this->_meta_title = strval ($row['cms_meta_title']);
		}
		if (is_null ($row['cms_meta_description'])) {
			$this->_meta_description = null;
		} else {
			$this->_meta_description = strval ($row['cms_meta_description']);
		}
		if (is_null ($row['cms_meta_keywords'])) {
			$this->_meta_keywords = null;
		} else {
			$this->_meta_keywords = strval ($row['cms_meta_keywords']);
		}
		$this->_url = strval ($row['cms_url']);
		$this->_sitemap_priority = floatval ($row['cms_sitemap_priority']);
		$this->_sitemap_change_freq = strval ($row['cms_sitemap_change_freq']);
		$this->_last_modified = strtotime ($row['cms_last_modified']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_type_id() {
		return ($this->_type_id);
	}
	function get_thumb_image_id() {
		return ($this->_thumb_image_id);
	}
	function get_site_section_code() {
		return ($this->_site_section_code);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_date() {
		return ($this->_date);
	}
	function get_title() {
		return ($this->_title);
	}
	function get_tagline() {
		return ($this->_tagline);
	}
	function get_summary() {
		return ($this->_summary);
	}
	function get_body() {
		return ($this->_body);
	}
	function get_start_date() {
		return ($this->_start_date);
	}
	function get_end_date() {
		return ($this->_end_date);
	}
	function get_to_homepage() {
		return ($this->_to_homepage);
	}
	function get_weight() {
		return ($this->_weight);
	}
	function get_meta_title() {
		return ($this->_meta_title);
	}
	function get_meta_description() {
		return ($this->_meta_description);
	}
	function get_meta_keywords() {
		return ($this->_meta_keywords);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_sitemap_priority() {
		return ($this->_sitemap_priority);
	}
	function get_sitemap_change_freq() {
		return ($this->_sitemap_change_freq);
	}
	function get_last_modified() {
		return ($this->_last_modified);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
			return false;
		}
	}
	function set_type_id ($val) {
		if (is_int ($val)) {
			$this->_type_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_type_id", E_USER_ERROR);
			return false;
		}
	}
	function set_thumb_image_id ($val) {
		if (is_int ($val)) {
			$this->_thumb_image_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_thumb_image_id", E_USER_ERROR);
			return false;
		}
	}
	function set_site_section_code ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_site_section_code = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_site_section_code", E_USER_ERROR);
			return false;
		}
	}
	function set_status ($val) {
		if (is_string ($val)) {
			$this->_status = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_status", E_USER_ERROR);
			return false;
		}
	}
	function set_date ($val) {
		if (is_int ($val)) {
			$this->_date = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_date", E_USER_ERROR);
			return false;
		}
	}
	function set_title ($val) {
		if (is_string ($val)) {
			$this->_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_title", E_USER_ERROR);
			return false;
		}
	}
	function set_tagline ($val) {
		if (is_string ($val)) {
			$this->_tagline = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_tagline", E_USER_ERROR);
			return false;
		}
	}
	function set_summary ($val) {
		if (is_string ($val)) {
			$this->_summary = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_summary", E_USER_ERROR);
			return false;
		}
	}
	function set_body ($val) {
		if (is_string ($val)) {
			$this->_body = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_body", E_USER_ERROR);
			return false;
		}
	}
	function set_start_date ($val) {
		if (is_int ($val) || is_null ($val)) {
			$this->_start_date = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_start_date", E_USER_ERROR);
			return false;
		}
	}
	function set_end_date ($val) {
		if (is_int ($val) || is_null ($val)) {
			$this->_end_date = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_end_date", E_USER_ERROR);
			return false;
		}
	}
	function set_to_homepage ($val) {
		if (is_string ($val)) {
			$this->_to_homepage = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_to_homepage", E_USER_ERROR);
			return false;
		}
	}
	function set_weight ($val) {
		if (is_int ($val)) {
			$this->_weight = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_weight", E_USER_ERROR);
			return false;
		}
	}
	function set_meta_title ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_meta_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_title", E_USER_ERROR);
			return false;
		}
	}
	function set_meta_description ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_meta_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_description", E_USER_ERROR);
			return false;
		}
	}
	function set_meta_keywords ($val) {
		if (is_string ($val) || is_null ($val)) {
			$this->_meta_keywords = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_keywords", E_USER_ERROR);
			return false;
		}
	}
	function set_url ($val) {
		if (is_string ($val)) {
			$this->_url = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_url", E_USER_ERROR);
			return false;
		}
	}
	function set_sitemap_priority ($val) {
		if (is_numeric ($val)) {
			$this->_sitemap_priority = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_sitemap_priority", E_USER_ERROR);
			return false;
		}
	}
	function set_sitemap_change_freq ($val) {
		if (is_string ($val)) {
			$this->_sitemap_change_freq = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_sitemap_change_freq", E_USER_ERROR);
			return false;
		}
	}
	function set_last_modified ($val) {
		if (is_int ($val)) {
			$this->_last_modified = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_last_modified", E_USER_ERROR);
			return false;
		}
	}
}

?>
