<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");

class category_l3_db {
	private $_id;
	private $_category_l2_id;
	private $_name;
	private $_image_id;
	private $_banner_id;
	private $_summary;
	private $_body;
	private $_video_url;
	private $_url;
	private $_meta_title;
	private $_meta_description;
	private $_status;
	private $_offsite_shredding;
	private $_onsite_shredding;
	private $_containers_provided;
	private $_adhoc_collections;
	private $_regular_collections;

	function __construct ($id = null) {
		if (is_null ($id)) {
			$this->_id = null;
			$this->_category_l2_id = null;
			$this->_name = null;
			$this->_image_id = null;
			$this->_banner_id = null;
			$this->_summary = null;
			$this->_body = null;
			$this->_video_url = null;
			$this->_url = null;
			$this->_meta_title = null;
			$this->_meta_description = null;
			$this->_status = null;
			$this->_offsite_shredding = null;
			$this->_onsite_shredding = null;
			$this->_containers_provided = null;
			$this->_adhoc_collections = null;
			$this->_regular_collections = null;
		} elseif (is_int ($id)) {
			$this->load (intval ($id));
		} else {
			trigger_error ("Invalid type passed to constructor", E_USER_ERROR);
		}
	}

	function load_by_url ($url) {
		if ($url == "") {
			trigger_error ("Blank url passed to load_by_url function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				category_l3s.category_l3_id,
				category_l3s.category_l2_id,
				category_l3s.category_l3_name,
				category_l3s.image_id,
				category_l3s.banner_id,
				category_l3s.category_l3_summary,
				category_l3s.category_l3_body,
				category_l3s.category_l3_video_url,
				category_l3s.category_l3_url,
				category_l3s.category_l3_meta_title,
				category_l3s.category_l3_meta_description,
				category_l3s.category_l3_status,
				category_l3s.category_l3_offsite_shredding,
				category_l3s.category_l3_onsite_shredding,
				category_l3s.category_l3_containers_provided,
				category_l3s.category_l3_adhoc_collections,
				category_l3s.category_l3_regular_collections
			FROM category_l3s
			WHERE
				category_l3_url = " . Database::make_sql_value ($url) . " AND 
				category_l3s.category_l3_status <> 'deleted' AND
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

	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Non-integer value ($id) passed to load function", E_USER_ERROR);
			return false;
		}
		$query = "SELECT
				category_l3s.category_l3_id,
				category_l3s.category_l2_id,
				category_l3s.category_l3_name,
				category_l3s.image_id,
				category_l3s.banner_id,
				category_l3s.category_l3_summary,
				category_l3s.category_l3_body,
				category_l3s.category_l3_video_url,
				category_l3s.category_l3_url,
				category_l3s.category_l3_meta_title,
				category_l3s.category_l3_meta_description,
				category_l3s.category_l3_status,
				category_l3s.category_l3_offsite_shredding,
				category_l3s.category_l3_onsite_shredding,
				category_l3s.category_l3_containers_provided,
				category_l3s.category_l3_adhoc_collections,
				category_l3s.category_l3_regular_collections
			FROM category_l3s
			WHERE
				category_l3_id = " . Database::make_sql_value ($id) . " AND 
				category_l3s.category_l3_status <> 'deleted' AND
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
			trigger_error ("category_l3_db ($id) not found", E_USER_ERROR);
		}
	}
	function save() {
		if (is_null ($this->_id)) {
			$query = "INSERT INTO category_l3s (
					category_l3_id,
					category_l2_id,
					category_l3_name,
					image_id,
					banner_id,
					category_l3_summary,
					category_l3_body,
					category_l3_video_url,
					category_l3_url,
					category_l3_meta_title,
					category_l3_meta_description,
					category_l3_status,
					category_l3_offsite_shredding,
					category_l3_onsite_shredding,
					category_l3_containers_provided,
					category_l3_adhoc_collections,
					category_l3_regular_collections
				) VALUES (
					null,
					" . Database::make_sql_value ($this->get_category_l2_id()) . ",
					" . Database::make_sql_value ($this->get_name()) . ",
					" . Database::make_sql_value ($this->get_image_id()) . ",
					" . Database::make_sql_value ($this->get_banner_id()) . ",
					" . Database::make_sql_value ($this->get_summary()) . ",
					" . Database::make_sql_value ($this->get_body()) . ",
					" . Database::make_sql_value ($this->get_video_url()) . ",
					" . Database::make_sql_value ($this->get_url()) . ",
					" . Database::make_sql_value ($this->get_meta_title()) . ",
					" . Database::make_sql_value ($this->get_meta_description()) . ",
					" . Database::make_sql_value ($this->get_status()) . ",
					" . Database::make_sql_value ($this->get_offsite_shredding()?YES:NO) . ",
					" . Database::make_sql_value ($this->get_onsite_shredding()?YES:NO) . ",
					" . Database::make_sql_value ($this->get_containers_provided()?YES:NO) . ",
					" . Database::make_sql_value ($this->get_adhoc_collections()?YES:NO) . ",
					" . Database::make_sql_value ($this->get_regular_collections()?YES:NO) . "
				)";
		} else {
			$query = "UPDATE category_l3s SET
								category_l2_id = " . Database::make_sql_value ($this->get_category_l2_id()) . ",
								category_l3_name = " . Database::make_sql_value ($this->get_name()) . ",
								image_id = " . Database::make_sql_value ($this->get_image_id()) . ",
								banner_id = " . Database::make_sql_value ($this->get_banner_id()) . ",
								category_l3_summary = " . Database::make_sql_value ($this->get_summary()) . ",
								category_l3_body = " . Database::make_sql_value ($this->get_body()) . ",
								category_l3_video_url = " . Database::make_sql_value ($this->get_video_url()) . ",
								category_l3_url = " . Database::make_sql_value ($this->get_url()) . ",
								category_l3_meta_title = " . Database::make_sql_value ($this->get_meta_title()) . ",
								category_l3_meta_description = " . Database::make_sql_value ($this->get_meta_description()) . ",
								category_l3_status = " . Database::make_sql_value ($this->get_status()) . ",
								category_l3_offsite_shredding = " . Database::make_sql_value ($this->get_offsite_shredding()?YES:NO) . ",
								category_l3_onsite_shredding = " . Database::make_sql_value ($this->get_onsite_shredding()?YES:NO) . ",
								category_l3_containers_provided = " . Database::make_sql_value ($this->get_containers_provided()?YES:NO) . ",
								category_l3_adhoc_collections = " . Database::make_sql_value ($this->get_adhoc_collections()?YES:NO) . ",
								category_l3_regular_collections = " . Database::make_sql_value ($this->get_regular_collections()?YES:NO) . "
							WHERE category_l3_id = " . Database::make_sql_value ($this->get_id()) . "
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
		$this->_id = intval ($row['category_l3_id']);
		$this->_category_l2_id = intval ($row['category_l2_id']);
		$this->_name = strval ($row['category_l3_name']);
		$this->_image_id = intval ($row['image_id']);
		$this->_banner_id = intval ($row['banner_id']);
		$this->_summary = strval ($row['category_l3_summary']);
		$this->_body = strval ($row['category_l3_body']);
		$this->_video_url = strval ($row['category_l3_video_url']);
		$this->_url = strval ($row['category_l3_url']);
		$this->_meta_title = strval ($row['category_l3_meta_title']);
		$this->_meta_description = strval ($row['category_l3_meta_description']);
		$this->_status = strval ($row['category_l3_status']);
		$this->_offsite_shredding = ($row['category_l3_offsite_shredding'] == YES);
		$this->_onsite_shredding = ($row['category_l3_onsite_shredding'] == YES);
		$this->_containers_provided = ($row['category_l3_containers_provided'] == YES);
		$this->_adhoc_collections = ($row['category_l3_adhoc_collections'] == YES);
		$this->_regular_collections = ($row['category_l3_regular_collections'] == YES);
	}

	function get_id() {
		return ($this->_id);
	}
	function get_category_l2_id() {
		return ($this->_category_l2_id);
	}
	function get_name() {
		return ($this->_name);
	}
	function get_image_id() {
		return ($this->_image_id);
	}
	function get_banner_id() {
		return ($this->_banner_id);
	}
	function get_summary() {
		return ($this->_summary);
	}
	function get_video_url() {
		return ($this->_video_url);
	}
	function get_body() {
		return ($this->_body);
	}
	function get_url() {
		return ($this->_url);
	}
	function get_meta_title() {
		return ($this->_meta_title);
	}
	function get_meta_description() {
		return ($this->_meta_description);
	}
	function get_status() {
		return ($this->_status);
	}
	function get_offsite_shredding() {
		return ($this->_offsite_shredding);
	}
	function get_onsite_shredding() {
		return ($this->_onsite_shredding);
	}
	function get_adhoc_collections() {
		return ($this->_adhoc_collections);
	}
	function get_containers_provided() {
		return ($this->_containers_provided);
	}
	function get_regular_collections() {
		return ($this->_regular_collections);
	}

	function set_id ($val) {
		if (is_int ($val)) {
			$this->_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_id", E_USER_ERROR);
			return false;
		}
	}
	function set_category_l2_id ($val) {
		if (is_int ($val)) {
			$this->_category_l2_id = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_category_l2_id", E_USER_ERROR);
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
	function set_summary ($val) {
		if (is_string ($val)) {
			$this->_summary = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_summary", E_USER_ERROR);
			return false;
		}
	}
	function set_video_url ($val) {
		if (is_string ($val)) {
			$this->_video_url = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_video_url", E_USER_ERROR);
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
	function set_url ($val) {
		if (is_string ($val)) {
			$this->_url = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_url", E_USER_ERROR);
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
	function set_meta_description ($val) {
		if (is_string ($val)) {
			$this->_meta_description = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_meta_description", E_USER_ERROR);
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
	function set_offsite_shredding ($val) {
		if (is_bool ($val)) {
			$this->_offsite_shredding = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_offsite_shredding", E_USER_ERROR);
			return false;
		}
	}
	function set_onsite_shredding ($val) {
		if (is_bool ($val)) {
			$this->_onsite_shredding = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_onsite_shredding", E_USER_ERROR);
			return false;
		}
	}
	function set_adhoc_collections ($val) {
		if (is_bool ($val)) {
			$this->_adhoc_collections = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_adhoc_collections", E_USER_ERROR);
			return false;
		}
	}
	function set_containers_provided ($val) {
		if (is_bool ($val)) {
			$this->_containers_provided = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_containers_provided", E_USER_ERROR);
			return false;
		}
	}
	function set_regular_collections ($val) {
		if (is_bool ($val)) {
			$this->_regular_collections = $val;
		} else {
			trigger_error ("Invalid type (" . gettype($val) . "), ($val) passed to set_regular_collections", E_USER_ERROR);
			return false;
		}
	}
}

?>
