<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/image_gallery.class.php");

function get_image_gallery_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " image_gallery.image_gallery_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " image_gallery.image_gallery_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " image_gallery.image_gallery_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM image_gallery
			WHERE
				image_gallery.image_gallery_status <> 'deleted' AND
				$id_where
				$name_where
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

function get_image_gallerys ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " image_gallery.image_gallery_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " image_gallery.image_gallery_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " image_gallery.image_gallery_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY image_gallery.image_gallery_id ";
				break;
			case "name":
				$order_by = " ORDER BY image_gallery.image_gallery_name ";
				break;
			case "status":
				$order_by = " ORDER BY image_gallery.image_gallery_status ";
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
				image_gallery.image_gallery_id,
				image_gallery.image_gallery_name,
				image_gallery.image_gallery_status
			FROM image_gallery
			WHERE
				image_gallery.image_gallery_status <> 'deleted' AND
				$id_where
				$name_where
				$status_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new image_gallery(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>