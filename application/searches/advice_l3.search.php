<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/advice_l3.class.php");

function get_advice_l3_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " advice_l3s.advice_l3_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$advice_l2_id_where = "";

	if (array_key_exists ("advice_l2_id", $options)) {
		$advice_l2_id_where = " advice_l3s.advice_l2_id = " . Database::make_sql_value ($options['advice_l2_id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " advice_l3s.advice_l3_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " advice_l3s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$banner_id_where = "";

	if (array_key_exists ("banner_id", $options)) {
		$banner_id_where = " advice_l3s.banner_id = " . Database::make_sql_value ($options['banner_id']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " advice_l3s.advice_l3_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " advice_l3s.advice_l3_body = " . Database::make_sql_value ($options['body']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " advice_l3s.advice_l3_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " advice_l3s.advice_l3_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " advice_l3s.advice_l3_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " advice_l3s.advice_l3_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM advice_l3s
			WHERE
				advice_l3s.advice_l3_status <> 'deleted' AND
				$id_where
				$advice_l2_id_where
				$name_where
				$image_id_where
				$banner_id_where
				$summary_where
				$body_where
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

function get_advice_l3s ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " advice_l3s.advice_l3_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$advice_l2_id_where = "";

	if (array_key_exists ("advice_l2_id", $options)) {
		$advice_l2_id_where = " advice_l3s.advice_l2_id = " . Database::make_sql_value ($options['advice_l2_id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " advice_l3s.advice_l3_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " advice_l3s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$banner_id_where = "";

	if (array_key_exists ("banner_id", $options)) {
		$banner_id_where = " advice_l3s.banner_id = " . Database::make_sql_value ($options['banner_id']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " advice_l3s.advice_l3_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " advice_l3s.advice_l3_body = " . Database::make_sql_value ($options['body']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " advice_l3s.advice_l3_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " advice_l3s.advice_l3_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " advice_l3s.advice_l3_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " advice_l3s.advice_l3_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY advice_l3s.advice_l3_id ";
				break;
			case "advice_l2_id":
				$order_by = " ORDER BY advice_l3s.advice_l2_id ";
				break;
			case "name":
				$order_by = " ORDER BY advice_l3s.advice_l3_name ";
				break;
			case "image_id":
				$order_by = " ORDER BY advice_l3s.image_id ";
				break;
			case "banner_id":
				$order_by = " ORDER BY advice_l3s.banner_id ";
				break;
			case "summary":
				$order_by = " ORDER BY advice_l3s.advice_l3_summary ";
				break;
			case "body":
				$order_by = " ORDER BY advice_l3s.advice_l3_body ";
				break;
			case "url":
				$order_by = " ORDER BY advice_l3s.advice_l3_url ";
				break;
			case "meta_title":
				$order_by = " ORDER BY advice_l3s.advice_l3_meta_title ";
				break;
			case "meta_description":
				$order_by = " ORDER BY advice_l3s.advice_l3_meta_description ";
				break;
			case "status":
				$order_by = " ORDER BY advice_l3s.advice_l3_status ";
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
				advice_l3s.advice_l3_id,
				advice_l3s.advice_l2_id,
				advice_l3s.advice_l3_name,
				advice_l3s.image_id,
				advice_l3s.banner_id,
				advice_l3s.advice_l3_summary,
				advice_l3s.advice_l3_body,
				advice_l3s.advice_l3_content_type,
				advice_l3s.advice_l3_url,
				advice_l3s.advice_l3_meta_title,
				advice_l3s.advice_l3_meta_description,
				advice_l3s.advice_l3_status
			FROM advice_l3s
			WHERE
				advice_l3s.advice_l3_status <> 'deleted' AND
				$id_where
				$advice_l2_id_where
				$name_where
				$image_id_where
				$banner_id_where
				$summary_where
				$body_where
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
		$item = new advice_l3(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
