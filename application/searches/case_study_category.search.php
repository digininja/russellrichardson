<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/case_study_category.class.php");

function get_case_study_category_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " case_study_categories.case_study_category_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " case_study_categories.case_study_category_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " case_study_categories.case_study_category_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " case_study_categories.case_study_category_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " case_study_categories.case_study_category_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " case_study_categories.case_study_category_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " case_study_categories.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM case_study_categories
			WHERE
				case_study_categories.case_study_category_status <> 'deleted' AND
				$id_where
				$status_where
				$name_where
				$url_where
				$meta_description_where
				$meta_title_where
				$image_id_where
				1=1
			";

	#print $query;
	$result = database::execute ($query);

	if (mysqli_num_rows ($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		$count = intval ($row['count']);
	} else {
		$count = 0;
	}

	return $count;
}

function get_case_study_categorys ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " case_study_categories.case_study_category_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " case_study_categories.case_study_category_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " case_study_categories.case_study_category_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " case_study_categories.case_study_category_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " case_study_categories.case_study_category_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " case_study_categories.case_study_category_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " case_study_categories.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY case_study_categories.case_study_category_id ";
				break;
			case "status":
				$order_by = " ORDER BY case_study_categories.case_study_category_status ";
				break;
			case "name":
				$order_by = " ORDER BY case_study_categories.case_study_category_name ";
				break;
			case "url":
				$order_by = " ORDER BY case_study_categories.case_study_category_url ";
				break;
			case "meta_description":
				$order_by = " ORDER BY case_study_categories.case_study_category_meta_description ";
				break;
			case "meta_title":
				$order_by = " ORDER BY case_study_categories.case_study_category_meta_title ";
				break;
			case "image_id":
				$order_by = " ORDER BY case_study_categories.image_id ";
				break;
		}
	}

	$limit = "";

	if (array_key_exists("page_size", $options) && is_int ($options['page_size'])) {
		$row_count = $options['page_size'];
		if (array_key_exists("page_number", $options) && is_int ($options['page_number'])) {
			$offset = ($options['page_number'] - 1) * $row_count;
			if ($offset < 0) {
				$offset = 0;
			}
		} else {
			$offset = 0;
		}

		$limit = " LIMIT " . Database::make_sql_value ($offset) . ", " . Database::make_sql_value ($row_count) . " ";
	}

	$query = "SELECT
				case_study_categories.case_study_category_id,
				case_study_categories.case_study_category_status,
				case_study_categories.case_study_category_name,
				case_study_categories.case_study_category_url,
				case_study_categories.case_study_category_meta_description,
				case_study_categories.case_study_category_meta_title,
				case_study_categories.image_id
			FROM case_study_categories
			WHERE
				case_study_categories.case_study_category_status <> 'deleted' AND
				$id_where
				$status_where
				$name_where
				$url_where
				$meta_description_where
				$meta_title_where
				$image_id_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new case_study_category(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>