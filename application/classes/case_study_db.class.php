<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class case_study_db {
	private $_id;
	private $_status;
	private $_name;
	private $_category_id;
	private $_service_type;
	private $_body;
	private $_quote_name;
	private $_image_id;
	private $_banner_id;
	private $_logo_id;
	private $_url;
	private $_quote;
	private $_summary;
	private $_meta_description;
	private $_meta_title;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_status = null;
			$this->_name = null;
			$this->_category_id = null;
			$this->_service_type = null;
			$this->_body = null;
			$this->_quote_name = null;
			$this->_image_id = null;
			$this->_banner_id = null;
			$this->_logo_id = null;
			$this->_url = null;
			$this->_quote = null;
			$this->_summary = null;
			$this->_meta_description = null;
			$this->_meta_title = null;
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
				case_studies.case_study_id,
				case_studies.case_study_status,
				case_studies.case_study_name,
				case_studies.case_study_category_id,
				case_studies.case_study_service_type,
				case_studies.case_study_body,
				case_studies.case_study_quote_name,
				case_studies.image_id,
				case_studies.banner_id,
				case_studies.logo_id,
				case_studies.case_study_url,
				case_studies.case_study_quote,
				case_studies.case_study_summary,
				case_studies.case_study_meta_description,
				case_studies.case_study_meta_title
			FROM case_studies
			WHERE
				case_study_id = " . Database::make_sql_value ($id) . " AND 
				case_studies.case_study_status <> 'deleted' AND
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
			trigger_error ("case_study_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO case_studies (
					case_study_id,
					case_study_status,
					case_study_name,
					case_study_category_id,
					case_study_service_type,
					case_study_body,
					case_study_quote_name,
					image_id,
					banner_id,
					logo_id,
					case_study_url,
					case_study_quote,
					case_study_summary,
					case_study_meta_description,
					case_study_meta_title
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_status()) . ",
					" . Database::make_sql_value ($this->get_name()) . ",
					" . Database::make_sql_value ($this->get_category_id()) . ",
					" . Database::make_sql_value ($this->get_service_type()) . ",
					" . Database::make_sql_value ($this->get_body()) . ",
					" . Database::make_sql_value ($this->get_quote_name()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . ",
					" . Database::make_sql_value ($this->get_banner_id()) . ",
					" . Database::make_sql_value ($this->get_logo_id()) . ",
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_quote()) . ",
					" . Database::make_sql_value ($this->get_summary()) . ",
					" . Database::make_sql_value ($this->get_meta_description()) . ",
					" . Database::make_sql_value ($this->get_meta_title()) . "
				)";
		} else {
			$query = "UPDATE case_studies SET
								case_study_status = " . Database::make_sql_value ($this->get_status()) . ",
								case_study_name = " . Database::make_sql_value ($this->get_name()) . ",
								case_study_category_id = " . Database::make_sql_value ($this->get_category_id()) . ",
								case_study_service_type = " . Database::make_sql_value ($this->get_service_type()) . ",
								case_study_body = " . Database::make_sql_value ($this->get_body()) . ",
								case_study_quote_name = " . Database::make_sql_value ($this->get_quote_name()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . ",
								banner_id = " . Database::make_sql_value ($this->get_banner_id()) . ",
								logo_id = " . Database::make_sql_value ($this->get_logo_id()) . ",
								case_study_url = " . Database::make_sql_value ($this->get_url()) . ",
								case_study_quote = " . Database::make_sql_value ($this->get_quote()) . ",
								case_study_summary = " . Database::make_sql_value ($this->get_summary()) . ",
								case_study_meta_description = " . Database::make_sql_value ($this->get_meta_description()) . ",
								case_study_meta_title = " . Database::make_sql_value ($this->get_meta_title()) . "
							WHERE case_study_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['case_study_id']);
		$this->_status = strval ($row['case_study_status']);
		$this->_name = strval ($row['case_study_name']);
		$this->_category_id = intval ($row['case_study_category_id']);
		$this->_service_type = strval ($row['case_study_service_type']);
		$this->_body = strval ($row['case_study_body']);
		$this->_quote_name = strval ($row['case_study_quote_name']);
		$this->_image_id = intval ($row['image_id']);
		$this->_banner_id = intval ($row['banner_id']);
		$this->_logo_id = intval ($row['logo_id']);
		$this->_url = strval ($row['case_study_url']);
		$this->_quote = strval ($row['case_study_quote']);
		$this->_summary = strval ($row['case_study_summary']);
		$this->_meta_description = strval ($row['case_study_meta_description']);
		$this->_meta_title = strval ($row['case_study_meta_title']);
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
	function get_quote_name() {
		return ($this->_quote_name);
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
	function get_logo_id() {
		return ($this->_logo_id);
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
	function set_quote_name ($val) {
		if (is_string ($val)) {
			$this->_quote_name = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_quote_name", E_USER_ERROR);
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
	function set_logo_id ($val) {
		if (is_int ($val)) {
			$this->_logo_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_logo_id", E_USER_ERROR);
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
}

?>
