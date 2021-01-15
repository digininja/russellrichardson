<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/link.class.php");

function get_link_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " links.link_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " links.link_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " links.link_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM links
			WHERE
				$id_where
				$title_where
				$url_where
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

function get_links ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " links.link_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " links.link_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " links.link_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY links.link_id ";
				break;
			case "title":
				$order_by = " ORDER BY links.link_title ";
				break;
			case "url":
				$order_by = " ORDER BY links.link_url ";
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
				links.link_id,
				links.link_title,
				links.link_url
			FROM links
			WHERE
				$id_where
				$title_where
				$url_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new link(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>