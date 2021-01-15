<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/download.class.php");

function get_download_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " downloads.download_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$filename_where = "";

	if (array_key_exists ("filename", $options)) {
		$filename_where = " downloads.download_filename = " . Database::make_sql_value ($options['filename']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " downloads.download_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM downloads
			WHERE
				$id_where
				$filename_where
				$name_where
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

function get_downloads ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " downloads.download_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$filename_where = "";

	if (array_key_exists ("filename", $options)) {
		$filename_where = " downloads.download_filename = " . Database::make_sql_value ($options['filename']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " downloads.download_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY downloads.download_id ";
				break;
			case "filename":
				$order_by = " ORDER BY downloads.download_filename ";
				break;
			case "name":
				$order_by = " ORDER BY downloads.download_name ";
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
				downloads.download_id,
				downloads.download_filename,
				downloads.download_summary,
				downloads.download_date,
				downloads.download_name
			FROM downloads
			WHERE
				$id_where
				$filename_where
				$name_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new download(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
