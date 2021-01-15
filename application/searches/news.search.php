<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/news.class.php");

function get_news_count ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " news.news_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " news.news_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " news.news_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$category_id_where = "";

	if (array_key_exists ("category_id", $options)) {
		$category_id_where = " news.news_category_id = " . Database::make_sql_value ($options['category_id']) . " AND ";
	}

	$service_type_where = "";

	if (array_key_exists ("service_type", $options)) {
		$service_type_where = " news.news_service_type = " . Database::make_sql_value ($options['service_type']) . " AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " news.news_body = " . Database::make_sql_value ($options['body']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " news.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$banner_id_where = "";

	if (array_key_exists ("banner_id", $options)) {
		$banner_id_where = " news.banner_id = " . Database::make_sql_value ($options['banner_id']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " news.news_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$quote_where = "";

	if (array_key_exists ("quote", $options)) {
		$quote_where = " news.news_quote = " . Database::make_sql_value ($options['quote']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " news.news_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " news.news_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " news.news_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$date_where = "";

	if (array_key_exists ("date", $options)) {
		$date_where = " news.news_date = " . Database::make_sql_value ($options['date']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM news
			WHERE
				news.news_status <> 'deleted' AND
				$id_where
				$status_where
				$name_where
				$category_id_where
				$service_type_where
				$body_where
				$image_id_where
				$banner_id_where
				$url_where
				$quote_where
				$summary_where
				$meta_description_where
				$meta_title_where
				$date_where
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

function get_newss ($options) {
	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " news.news_id = " . Database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " news.news_status = " . Database::make_sql_value ($options['status']) . " AND ";
	}

	$name_where = "";

	if (array_key_exists ("name", $options)) {
		$name_where = " news.news_name = " . Database::make_sql_value ($options['name']) . " AND ";
	}

	$category_id_where = "";

	if (array_key_exists ("category_id", $options)) {
		$category_id_where = " news.news_category_id = " . Database::make_sql_value ($options['category_id']) . " AND ";
	}

	$service_type_where = "";

	if (array_key_exists ("service_type", $options)) {
		$service_type_where = " news.news_service_type = " . Database::make_sql_value ($options['service_type']) . " AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " news.news_body = " . Database::make_sql_value ($options['body']) . " AND ";
	}

	$image_id_where = "";

	if (array_key_exists ("image_id", $options)) {
		$image_id_where = " news.image_id = " . Database::make_sql_value ($options['image_id']) . " AND ";
	}

	$banner_id_where = "";

	if (array_key_exists ("banner_id", $options)) {
		$banner_id_where = " news.banner_id = " . Database::make_sql_value ($options['banner_id']) . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " news.news_url = " . Database::make_sql_value ($options['url']) . " AND ";
	}

	$quote_where = "";

	if (array_key_exists ("quote", $options)) {
		$quote_where = " news.news_quote = " . Database::make_sql_value ($options['quote']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " news.news_summary = " . Database::make_sql_value ($options['summary']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " news.news_meta_description = " . Database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " news.news_meta_title = " . Database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$date_where = "";

	if (array_key_exists ("date", $options)) {
		$date_where = " news.news_date = " . Database::make_sql_value ($options['date']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY news.news_id ";
				break;
			case "status":
				$order_by = " ORDER BY news.news_status ";
				break;
			case "name":
				$order_by = " ORDER BY news.news_name ";
				break;
			case "category_id":
				$order_by = " ORDER BY news.news_category_id ";
				break;
			case "service_type":
				$order_by = " ORDER BY news.news_service_type ";
				break;
			case "body":
				$order_by = " ORDER BY news.news_body ";
				break;
			case "image_id":
				$order_by = " ORDER BY news.image_id ";
				break;
			case "banner_id":
				$order_by = " ORDER BY news.banner_id ";
				break;
			case "url":
				$order_by = " ORDER BY news.news_url ";
				break;
			case "quote":
				$order_by = " ORDER BY news.news_quote ";
				break;
			case "summary":
				$order_by = " ORDER BY news.news_summary ";
				break;
			case "meta_description":
				$order_by = " ORDER BY news.news_meta_description ";
				break;
			case "meta_title":
				$order_by = " ORDER BY news.news_meta_title ";
				break;
			case "date":
				$order_by = " ORDER BY news.news_date ";
				break;
			case "date_desc":
				$order_by = " ORDER BY news.news_date DESC ";
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
				news.news_id,
				news.news_status,
				news.news_name,
				news.news_category_id,
				news.news_service_type,
				news.news_body,
				news.image_id,
				news.banner_id,
				news.news_url,
				news.news_quote,
				news.news_summary,
				news.news_meta_description,
				news.news_meta_title,
				news.news_date
			FROM news
			WHERE
				news.news_status <> 'deleted' AND
				$id_where
				$status_where
				$name_where
				$category_id_where
				$service_type_where
				$body_where
				$image_id_where
				$banner_id_where
				$url_where
				$quote_where
				$summary_where
				$meta_description_where
				$meta_title_where
				$date_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new news(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
