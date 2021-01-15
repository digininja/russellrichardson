<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/category_l3_linked_category_l3.class.php");
require_once ("application/classes/category_l3.class.php");

function get_category_l3_linked_category_l3_count ($options) {
	$category_l3_id_where = "";

	if (array_key_exists ("category_l3_id", $options)) {
		$category_l3_id_where = " category_l3_linked_category_l3s.category_l3_id = " . Database::make_sql_value ($options['category_l3_id']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " category_l3_linked_category_l3s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM category_l3_linked_category_l3s
			WHERE
				$category_l3_id_where
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

function get_category_l3_linked_category_l3s ($options) {
	$category_l3_id_where = "";

	if (array_key_exists ("category_l3_id", $options)) {
		$category_l3_id_where = " category_l3_linked_category_l3s.category_l3_id = " . Database::make_sql_value ($options['category_l3_id']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " category_l3_linked_category_l3s.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "name":
				$order_by = " ORDER BY category_l3s.category_l3_name ";
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
				INNER JOIN category_l3_linked_category_l3s ON category_l3_linked_category_l3s.linked_category_l3_id = category_l3s.category_l3_id
			WHERE
				category_l3s.category_l3_status = 'active' AND
				$category_l3_id_where
				$image_id_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		if (array_key_exists ("id_only", $options)) {
			$data[] = $row['category_l3_id'];
		} else {
			$item = new category_l3(null, $row);
			$data[] = $item;
		}
	}
	#var_dump_pre ($data);

	return $data;
}

?>
