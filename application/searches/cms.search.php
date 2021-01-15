<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/cms.class.php");

function get_cms_count ($options) {
	$dbh = new database();

	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " cmss.cms_id = " . database::make_sql_value ($options['id']) . " AND ";
	}

	$type_id_where = "";

	if (array_key_exists ("type_id", $options)) {
		$type_id_where = " cmss.cms_type_id = " . database::make_sql_value ($options['type_id']) . " AND ";
	}

	$thumb_image_id_where = "";

	if (array_key_exists ("thumb_image_id", $options)) {
		$thumb_image_id_where = " cmss.thumb_image_id = " . database::make_sql_value ($options['thumb_image_id']) . " AND ";
	}

	$site_section_code_where = "";

	if (array_key_exists ("site_section_code", $options)) {
		$site_section_code_where = " cmss.site_section_code = " . database::make_sql_value ($options['site_section_code']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " cmss.cms_status = " . database::make_sql_value ($options['status']) . " AND ";
	}

	$date_where = "";

	if (array_key_exists ("date", $options)) {
		$date_where = " cmss.cms_date = " . database::make_sql_value ($options['date']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " cmss.cms_title = " . database::make_sql_value ($options['title']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " cmss.cms_summary = " . database::make_sql_value ($options['summary']) . " AND ";
	}

	$keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$keyword_where = " (cmss.cms_body LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$keyword_where .= " cmss.cms_title LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$keyword_where .= " cmss.cms_summary LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " cmss.cms_body = " . database::make_sql_value ($options['body']) . " AND ";
	}

	$start_date_where = "";

	if (array_key_exists ("start_date", $options)) {
		$start_date_where = " cmss.cms_start_date = " . database::make_sql_value ($options['start_date']) . " AND ";
	}

	$end_date_where = "";

	if (array_key_exists ("end_date", $options)) {
		$end_date_where = " cmss.cms_end_date = " . database::make_sql_value ($options['end_date']) . " AND ";
	}

	$to_homepage_where = "";

	if (array_key_exists ("to_homepage", $options)) {
		$to_homepage_where = " cmss.cms_to_homepage = " . database::make_sql_value ($options['to_homepage']) . " AND ";
	}

	$weight_where = "";

	if (array_key_exists ("weight", $options)) {
		$weight_where = " cmss.cms_weight = " . database::make_sql_value ($options['weight']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " cmss.cms_meta_title = " . database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " cmss.cms_meta_description = " . database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_keywords_where = "";

	if (array_key_exists ("meta_keywords", $options)) {
		$meta_keywords_where = " cmss.cms_meta_keywords = " . database::make_sql_value ($options['meta_keywords']) . " AND ";
	}

	$url_starts_where = "";

	if (array_key_exists ("url_starts", $options)) {
		$url_starts_where = " cmss.cms_url LIKE " . database::make_sql_value ($options['url_starts'] . "%") . " AND ";
	}

	$ignore_url_where = "";

	if (array_key_exists ("ignore_url", $options)) {
		$ignore_url_where = " cmss.cms_url NOT LIKE " . database::make_sql_value ($options['ignore_url'] . "%") . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " cmss.cms_url = " . database::make_sql_value ($options['url']) . " AND ";
	}

	$sitemap_priority_where = "";

	if (array_key_exists ("sitemap_priority", $options)) {
		$sitemap_priority_where = " cmss.cms_sitemap_priority = " . database::make_sql_value ($options['sitemap_priority']) . " AND ";
	}

	$sitemap_change_freq_where = "";

	if (array_key_exists ("sitemap_change_freq", $options)) {
		$sitemap_change_freq_where = " cmss.cms_sitemap_change_freq = " . database::make_sql_value ($options['sitemap_change_freq']) . " AND ";
	}

	$last_modified_where = "";

	if (array_key_exists ("last_modified", $options)) {
		$last_modified_where = " cmss.cms_last_modified = " . database::make_sql_value ($options['last_modified']) . " AND ";
	}

	$query = "SELECT
					COUNT(*) AS count
			FROM cmss
			WHERE
				cmss.cms_status <> 'deleted' AND
				$url_starts_where
				$id_where
				$type_id_where
				$thumb_image_id_where
				$site_section_code_where
				$status_where
				$date_where
				$title_where
				$summary_where
				$body_where
				$keyword_where
				$start_date_where
				$end_date_where
				$to_homepage_where
				$weight_where
				$meta_title_where
				$meta_description_where
				$meta_keywords_where
				$url_where
				$ignore_url_where
				$sitemap_priority_where
				$sitemap_change_freq_where
				$last_modified_where
				1=1
			";

	#print $query;
	$result = database::execute ($query);

	if (mysqli_num_rows ($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		$count = $row['count'];
	} else {
		$count = 0;
	}

	return $count;
}

function get_cmss ($options) {
	$dbh = new database();

	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " cmss.cms_id = " . database::make_sql_value ($options['id']) . " AND ";
	}

	$type_id_where = "";

	if (array_key_exists ("type_id", $options)) {
		$type_id_where = " cmss.cms_type_id = " . database::make_sql_value ($options['type_id']) . " AND ";
	}

	$thumb_image_id_where = "";

	if (array_key_exists ("thumb_image_id", $options)) {
		$thumb_image_id_where = " cmss.thumb_image_id = " . database::make_sql_value ($options['thumb_image_id']) . " AND ";
	}

	$site_section_code_where = "";

	if (array_key_exists ("site_section_code", $options)) {
		$site_section_code_where = " cmss.site_section_code = " . database::make_sql_value ($options['site_section_code']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " cmss.cms_status = " . database::make_sql_value ($options['status']) . " AND ";
	}

	$date_where = "";

	if (array_key_exists ("date", $options)) {
		$date_where = " cmss.cms_date = " . database::make_sql_value ($options['date']) . " AND ";
	}

	$title_where = "";

	if (array_key_exists ("title", $options)) {
		$title_where = " cmss.cms_title = " . database::make_sql_value ($options['title']) . " AND ";
	}

	$summary_where = "";

	if (array_key_exists ("summary", $options)) {
		$summary_where = " cmss.cms_summary = " . database::make_sql_value ($options['summary']) . " AND ";
	}

	$keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$keyword_where = " (cmss.cms_body LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$keyword_where .= " cmss.cms_title LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$keyword_where .= " cmss.cms_summary LIKE " . database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
	}

	$body_where = "";

	if (array_key_exists ("body", $options)) {
		$body_where = " cmss.cms_body = " . database::make_sql_value ($options['body']) . " AND ";
	}

	$start_date_where = "";

	if (array_key_exists ("start_date", $options)) {
		$start_date_where = " cmss.cms_start_date = " . database::make_sql_value ($options['start_date']) . " AND ";
	}

	$end_date_where = "";

	if (array_key_exists ("end_date", $options)) {
		$end_date_where = " cmss.cms_end_date = " . database::make_sql_value ($options['end_date']) . " AND ";
	}

	$to_homepage_where = "";

	if (array_key_exists ("to_homepage", $options)) {
		$to_homepage_where = " cmss.cms_to_homepage = " . database::make_sql_value ($options['to_homepage']) . " AND ";
	}

	$weight_where = "";

	if (array_key_exists ("weight", $options)) {
		$weight_where = " cmss.cms_weight = " . database::make_sql_value ($options['weight']) . " AND ";
	}

	$meta_title_where = "";

	if (array_key_exists ("meta_title", $options)) {
		$meta_title_where = " cmss.cms_meta_title = " . database::make_sql_value ($options['meta_title']) . " AND ";
	}

	$meta_description_where = "";

	if (array_key_exists ("meta_description", $options)) {
		$meta_description_where = " cmss.cms_meta_description = " . database::make_sql_value ($options['meta_description']) . " AND ";
	}

	$meta_keywords_where = "";

	if (array_key_exists ("meta_keywords", $options)) {
		$meta_keywords_where = " cmss.cms_meta_keywords = " . database::make_sql_value ($options['meta_keywords']) . " AND ";
	}

	$ignore_url_where = "";

	if (array_key_exists ("ignore_url", $options)) {
		$ignore_url_where = " cmss.cms_url NOT LIKE " . database::make_sql_value ($options['ignore_url'] . "%") . " AND ";
	}

	$url_starts_where = "";

	if (array_key_exists ("url_starts", $options)) {
		$url_starts_where = " cmss.cms_url LIKE " . database::make_sql_value ($options['url_starts'] . "%") . " AND ";
	}

	$url_where = "";

	if (array_key_exists ("url", $options)) {
		$url_where = " cmss.cms_url = " . database::make_sql_value ($options['url']) . " AND ";
	}

	$sitemap_priority_where = "";

	if (array_key_exists ("sitemap_priority", $options)) {
		$sitemap_priority_where = " cmss.cms_sitemap_priority = " . database::make_sql_value ($options['sitemap_priority']) . " AND ";
	}

	$sitemap_change_freq_where = "";

	if (array_key_exists ("sitemap_change_freq", $options)) {
		$sitemap_change_freq_where = " cmss.cms_sitemap_change_freq = " . database::make_sql_value ($options['sitemap_change_freq']) . " AND ";
	}

	$last_modified_where = "";

	if (array_key_exists ("last_modified", $options)) {
		$last_modified_where = " cmss.cms_last_modified = " . database::make_sql_value ($options['last_modified']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY cmss.cms_id ";
				break;
			case "type_id":
				$order_by = " ORDER BY cmss.cms_type_id ";
				break;
			case "thumb_image_id":
				$order_by = " ORDER BY cmss.thumb_image_id ";
				break;
			case "site_section_code":
				$order_by = " ORDER BY cmss.site_section_code ";
				break;
			case "status":
				$order_by = " ORDER BY cmss.cms_status ";
				break;
			case "date":
				$order_by = " ORDER BY cmss.cms_date ";
				break;
			case "title":
				$order_by = " ORDER BY cmss.cms_title ";
				break;
			case "summary":
				$order_by = " ORDER BY cmss.cms_summary ";
				break;
			case "body":
				$order_by = " ORDER BY cmss.cms_body ";
				break;
			case "start_date":
				$order_by = " ORDER BY cmss.cms_start_date ";
				break;
			case "end_date":
				$order_by = " ORDER BY cmss.cms_end_date ";
				break;
			case "to_homepage":
				$order_by = " ORDER BY cmss.cms_to_homepage ";
				break;
			case "weight name":
				$order_by = " ORDER BY cmss.cms_weight DESC, cmss.cms_title";
				break;
			case "weight":
				$order_by = " ORDER BY cmss.cms_weight ";
				break;
			case "meta_title":
				$order_by = " ORDER BY cmss.cms_meta_title ";
				break;
			case "meta_description":
				$order_by = " ORDER BY cmss.cms_meta_description ";
				break;
			case "meta_keywords":
				$order_by = " ORDER BY cmss.cms_meta_keywords ";
				break;
			case "url":
				$order_by = " ORDER BY cmss.cms_url ";
				break;
			case "sitemap_priority":
				$order_by = " ORDER BY cmss.cms_sitemap_priority ";
				break;
			case "sitemap_change_freq":
				$order_by = " ORDER BY cmss.cms_sitemap_change_freq ";
				break;
			case "last_modified":
				$order_by = " ORDER BY cmss.cms_last_modified ";
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

		$limit = " LIMIT " . database::make_sql_value ($offset) . ", " . $dbh->make_sql_value ($row_count) . " ";
	}

	$query = "SELECT
				cmss.cms_id,
				cmss.cms_type_id,
				cmss.thumb_image_id,
				cmss.site_section_code,
				cmss.cms_status,
				cmss.cms_date,
				cmss.cms_title,
				cmss.cms_summary,
				cmss.cms_tagline,
				cmss.cms_body,
				cmss.cms_start_date,
				cmss.cms_end_date,
				cmss.cms_to_homepage,
				cmss.cms_weight,
				cmss.cms_meta_title,
				cmss.cms_meta_description,
				cmss.cms_meta_keywords,
				cmss.cms_url,
				cmss.cms_sitemap_priority,
				cmss.cms_sitemap_change_freq,
				cmss.cms_last_modified
			FROM cmss
			WHERE
				cmss.cms_status <> 'deleted' AND
				$ignore_url_where
				$url_starts_where
				$id_where
				$type_id_where
				$thumb_image_id_where
				$site_section_code_where
				$status_where
				$date_where
				$title_where
				$summary_where
				$body_where
				$keyword_where
				$start_date_where
				$end_date_where
				$to_homepage_where
				$weight_where
				$meta_title_where
				$meta_description_where
				$meta_keywords_where
				$url_where
				$sitemap_priority_where
				$sitemap_change_freq_where
				$last_modified_where
				1=1
			$order_by	
			$limit	
			";

	#print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new cms(null, $row);
		$data[] = $item;
	}

	return $data;
}

?>
