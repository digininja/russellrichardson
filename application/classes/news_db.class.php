<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class news_db {
	private $_id;
	private $_status;
	private $_name;
	private $_category_id;
	private $_service_type;
	private $_body;
	private $_image_id;
	private $_banner_id;
	private $_url;
	private $_quote;
	private $_summary;
	private $_meta_description;
	private $_meta_title;
	private $_date;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_status = null;
			$this->_name = null;
			$this->_category_id = null;
			$this->_service_type = null;
			$this->_body = null;
			$this->_image_id = null;
			$this->_banner_id = null;
			$this->_url = null;
			$this->_quote = null;
			$this->_summary = null;
			$this->_meta_description = null;
			$this->_meta_title = null;
			$this->_date = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				news.news_id,
				news.news_status,
				news.news_name,
				news.news_category_id,
				news.news_service_type,
				news.news_body,
				news.image_id,
				news.banner_id,
				news.news_url,
				news.news_quote,
				news.news_summary,
				news.news_meta_description,
				news.news_meta_title,
				news.news_date
			FROM news
			WHERE
				news_id = " . Database::make_sql_value ($id) . " AND 
				news.news_status <> 'deleted' AND
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
			trigger_error ("news_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO news (
					news_id,
					news_status,
					news_name,
					news_category_id,
					news_service_type,
					news_body,
					image_id,
					banner_id,
					news_url,
					news_quote,
					news_summary,
					news_meta_description,
					news_meta_title,
					news_date
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_status()) . ",
					" . Database::make_sql_value ($this->get_name()) . ",
					" . Database::make_sql_value ($this->get_category_id()) . ",
					" . Database::make_sql_value ($this->get_service_type()) . ",
					" . Database::make_sql_value ($this->get_body()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . ",
					" . Database::make_sql_value ($this->get_banner_id()) . ",
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_quote()) . ",
					" . Database::make_sql_value ($this->get_summary()) . ",
					" . Database::make_sql_value ($this->get_meta_description()) . ",
					" . Database::make_sql_value ($this->get_meta_title()) . ",
					" . Database::make_sql_value (Database::make_sql_date ($this->get_date())) . "
				)";
		} else {
			$query = "UPDATE news SET
								news_status = " . Database::make_sql_value ($this->get_status()) . ",
								news_name = " . Database::make_sql_value ($this->get_name()) . ",
								news_category_id = " . Database::make_sql_value ($this->get_category_id()) . ",
								news_service_type = " . Database::make_sql_value ($this->get_service_type()) . ",
								news_body = " . Database::make_sql_value ($this->get_body()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . ",
								banner_id = " . Database::make_sql_value ($this->get_banner_id()) . ",
								news_url = " . Database::make_sql_value ($this->get_url()) . ",
								news_quote = " . Database::make_sql_value ($this->get_quote()) . ",
								news_summary = " . Database::make_sql_value ($this->get_summary()) . ",
								news_meta_description = " . Database::make_sql_value ($this->get_meta_description()) . ",
								news_meta_title = " . Database::make_sql_value ($this->get_meta_title()) . ",
								news_date = " . Database::make_sql_value (Database::make_sql_date ($this->get_date())) . "
							WHERE news_id = " . Database::make_sql_value ($this->get_id()) . "
						";

		}
		#print $query;
		$res = database::execute ($query);
		if (is_null ($this->_id)) {
			$this->_id = database::get_insert_id();
		}
		return true;
	}

	function populate ($row) {
		$this->_id = intval ($row['news_id']);
		$this->_status = strval ($row['news_status']);
		$this->_name = strval ($row['news_name']);
		$this->_category_id = intval ($row['news_category_id']);
		$this->_service_type = strval ($row['news_service_type']);
		$this->_body = strval ($row['news_body']);
		$this->_image_id = intval ($row['image_id']);
		$this->_banner_id = intval ($row['banner_id']);
		$this->_url = strval ($row['news_url']);
		$this->_quote = strval ($row['news_quote']);
		$this->_summary = strval ($row['news_summary']);
		$this->_meta_description = strval ($row['news_meta_description']);
		$this->_meta_title = strval ($row['news_meta_title']);
		$this->_date = strtotime ($row['news_date']);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_name() {
		return ($this->_name);
	}
	function get_category_id() {
		return ($this->_category_id);
	}
	function get_service_type() {
		return ($this->_service_type);
	}
	function get_body() {
		return ($this->_body);
	}
	function get_image_id() {
		return ($this->_image_id);
	}
	function get_banner_id() {
		return ($this->_banner_id);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_quote() {
		return ($this->_quote);
	}
	function get_summary() {
		return ($this->_summary);
	}
	function get_meta_description() {
		return ($this->_meta_description);
	}
	function get_meta_title() {
		return ($this->_meta_title);
	}
	function get_date() {
		return ($this->_date);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
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
	function set_name ($val) {
		if (is_string ($val)) {
			$this->_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_name", E_USER_ERROR);
			return false;
		}
	}
	function set_category_id ($val) {
		if (is_int ($val)) {
			$this->_category_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_category_id", E_USER_ERROR);
			return false;
		}
	}
	function set_service_type ($val) {
		if (is_string ($val)) {
			$this->_service_type = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_service_type", E_USER_ERROR);
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
	function set_image_id ($val) {
		if (is_int ($val)) {
			$this->_image_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_image_id", E_USER_ERROR);
			return false;
		}
	}
	function set_banner_id ($val) {
		if (is_int ($val)) {
			$this->_banner_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_banner_id", E_USER_ERROR);
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
	function set_quote ($val) {
		if (is_string ($val)) {
			$this->_quote = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_quote", E_USER_ERROR);
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
	function set_meta_description ($val) {
		if (is_string ($val)) {
			$this->_meta_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_description", E_USER_ERROR);
			return false;
		}
	}
	function set_meta_title ($val) {
		if (is_string ($val)) {
			$this->_meta_title = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_title", E_USER_ERROR);
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
}

?>