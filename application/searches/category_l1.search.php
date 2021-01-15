<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/category_l1.class.php");

function get_category_l1_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " category_l1s.category_l1_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " category_l1s.category_l1_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " category_l1s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " category_l1s.category_l1_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " category_l1s.category_l1_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " category_l1s.category_l1_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " category_l1s.category_l1_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " category_l1s.category_l1_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM category_l1s
			WHERE
				category_l1s.category_l1_status <> 'deleted' AND
				$id_where
				$name_where
				$image_id_where
				$summary_where
				$url_where
				$meta_title_where
				$meta_description_where
				$status_where
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

function get_category_l1s ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " category_l1s.category_l1_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " category_l1s.category_l1_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " category_l1s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " category_l1s.category_l1_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " category_l1s.category_l1_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " category_l1s.category_l1_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " category_l1s.category_l1_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " category_l1s.category_l1_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY category_l1s.category_l1_id ";
				break;
			case "name":
				$order_by = " ORDER BY category_l1s.category_l1_name ";
				break;
			case "image_id":
				$order_by = " ORDER BY category_l1s.image_id ";
				break;
			case "summary":
				$order_by = " ORDER BY category_l1s.category_l1_summary ";
				break;
			case "order":
				$order_by = " ORDER BY category_l1s.category_l1_order ";
				break;
			case "url":
				$order_by = " ORDER BY category_l1s.category_l1_url ";
				break;
			case "meta_title":
				$order_by = " ORDER BY category_l1s.category_l1_meta_title ";
				break;
			case "meta_description":
				$order_by = " ORDER BY category_l1s.category_l1_meta_description ";
				break;
			case "status":
				$order_by = " ORDER BY category_l1s.category_l1_status ";
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
				category_l1s.category_l1_id,
				category_l1s.category_l1_name,
				category_l1s.image_id,
				category_l1s.category_l1_summary,
				category_l1s.category_l1_url,
				category_l1s.category_l1_order,
				category_l1s.category_l1_meta_title,
				category_l1s.category_l1_meta_description,
				category_l1s.category_l1_status
			FROM category_l1s
			WHERE
				category_l1s.category_l1_status <> 'deleted' AND
				$id_where
				$name_where
				$image_id_where
				$summary_where
				$url_where
				$meta_title_where
				$meta_description_where
				$status_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new category_l1(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
