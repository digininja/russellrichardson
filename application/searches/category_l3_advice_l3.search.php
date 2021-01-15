<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/category_l3_advice_l3.class.php");
require_once ("application/classes/advice_l3.class.php");
require_once ("application/classes/category_l3.class.php");

function get_category_l3_advice_l3s ($options) {
	$advice_l3_id_where = "";

	if (array_key_exists ("advice_l3_id", $options)) {
		$advice_l3_id_where = " category_l3_advice_l3s.advice_l3_id = " . Database::make_sql_value ($options['advice_l3_id']) . " AND ";
	}

	$category_l3_id_where = "";

	if (array_key_exists ("category_l3_id", $options)) {
		$category_l3_id_where = " category_l3_advice_l3s.category_l3_id = " . Database::make_sql_value ($options['category_l3_id']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "category_name":
				$order_by = " ORDER BY category_l3s.category_l3_name ";
				break;
			case "advice_name":
				$order_by = " ORDER BY advice_l3s.advice_l3_name ";
				break;
			case "category_l3_id":
				$order_by = " ORDER BY category_l3_advice_l3s.category_l3_id ";
				break;
			case "image_id":
				$order_by = " ORDER BY category_l3_advice_l3s.image_id ";
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

	if (array_key_exists ("advice_l3_id", $options)) {
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
				category_l3s.category_l3_onsite_shredding,
				category_l3s.category_l3_offsite_shredding,
				category_l3s.category_l3_containers_provided,
				category_l3s.category_l3_adhoc_collections,
				category_l3s.category_l3_regular_collections
			FROM category_l3s
					INNER JOIN category_l3_advice_l3s ON category_l3_advice_l3s.category_l3_id = category_l3s.category_l3_id
				WHERE
					category_l3s.category_l3_status = 'active' AND
					$advice_l3_id_where
					1=1
				$order_by	
				$limit	
				";
	} else {
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
					INNER JOIN category_l3_advice_l3s ON category_l3_advice_l3s.advice_l3_id = advice_l3s.advice_l3_id
				WHERE
					advice_l3s.advice_l3_status = 'active' AND
					$category_l3_id_where
					1=1
				$order_by	
				$limit	
				";
	}

	if (array_key_exists ("REMOTE_ADDR", $_SERVER) && $_SERVER['REMOTE_ADDR'] == "178.79.182.137") {
	# 	var_dump_pre ($query);
	}   

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		if (array_key_exists ("id_only", $options)) {
			$data[] = $row['advice_l3_id'];
		} else {
			if (array_key_exists ("advice_l3_id", $options)) {
				$item = new category_l3(null, $row);
				$data[] = $item;
			} else {
				$item = new advice_l3(null, $row);
				$data[] = $item;
			}
		}
	}
	#var_dump_pre ($data);

	return $data;
}

?>
