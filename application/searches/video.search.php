<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/video.class.php");

function get_video_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " videos.video_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " videos.video_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " videos.video_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " videos.video_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM videos
			WHERE
				videos.video_status <> 'deleted' AND
				$id_where
				$url_where
				$title_where
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

function get_videos ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " videos.video_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " videos.video_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " videos.video_title = " . Database::make_sql_value ($options['title']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " videos.video_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY videos.video_id ";
				break;
			case "url":
				$order_by = " ORDER BY videos.video_url ";
				break;
			case "title":
				$order_by = " ORDER BY videos.video_title ";
				break;
			case "status":
				$order_by = " ORDER BY videos.video_status ";
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
				videos.video_id,
				videos.video_url,
				videos.video_title,
				videos.video_status
			FROM videos
			WHERE
				videos.video_status <> 'deleted' AND
				$id_where
				$url_where
				$title_where
				$status_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new video(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>