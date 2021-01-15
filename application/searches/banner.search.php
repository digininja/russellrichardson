<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/banner.class.php");

function get_banner_count ($options) {
	$dbh = new database();

	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " banners.banner_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " banners.banner_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$copy_where = "";

	if (array_key_exists ("copy", $options)) {
		$copy_where = " banners.banner_copy = " . Database::make_sql_value ($options['copy']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " banners.banner_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM banners
			WHERE
				$id_where
				$title_where
				$copy_where
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

function get_banners ($options) {
	$dbh = new database();

	$service_id_join = "";
	$service_id_where = "";

	if (array_key_exists ("service_id", $options)) {
		$service_id_join = " INNER JOIN banner_services ON banner_services.banner_id = banners.banner_id ";
		$service_id_where = " banner_services.service_id = " . Database::make_sql_value ($options['service_id']) . " AND ";
	}

	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " banners.banner_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " banners.banner_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$copy_where = "";

	if (array_key_exists ("copy", $options)) {
		$copy_where = " banners.banner_copy = " . Database::make_sql_value ($options['copy']) . " AND ";
	}

	$homepage_where = "";

	if (array_key_exists ("homepage", $options)) {
		$homepage_where = " banners.banner_homepage = " . Database::make_sql_value (YES) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " banners.banner_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY banners.banner_id ";
				break;
			case "title":
				$order_by = " ORDER BY banners.banner_title ";
				break;
			case "copy":
				$order_by = " ORDER BY banners.banner_copy ";
				break;
			case "url":
				$order_by = " ORDER BY banners.banner_url ";
				break;
			case "random":
				$order_by = " ORDER BY RAND() ";
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
				banners.banner_id,
				banners.banner_title,
				banners.banner_copy,
				banners.banner_button,
				banners.banner_url,
				banners.banner_homepage
			FROM banners
				$service_id_join
			WHERE
				$id_where
				$service_id_where
				$title_where
				$copy_where
				$url_where
				$homepage_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new banner(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
