<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/category_l1.class.php");
require_once ("application/classes/category_l2.class.php");
require_once ("application/classes/category_l3.class.php");
require_once ("application/classes/news.class.php");
require_once ("application/classes/case_study.class.php");

function site_search ($options) {

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

	// service category level 1

	$category_l1_keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$category_l1_keyword_where = " ( category_l1s.category_l1_name LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$category_l1_keyword_where .= "  category_l1s.category_l1_summary LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
	}

	$query = "SELECT
				category_l1s.category_l1_id,
				category_l1s.category_l1_name,
				category_l1s.image_id,
				category_l1s.category_l1_summary,
				category_l1s.category_l1_url,
				category_l1s.category_l1_order,
				category_l1s.category_l1_meta_title,
				category_l1s.category_l1_meta_description,
				category_l1s.category_l1_status
			FROM category_l1s
			WHERE
				category_l1s.category_l1_status <> 'deleted' AND
				$category_l1_keyword_where
				1=1
			ORDER BY category_l1s.category_l1_name
			$limit	
			";

	# print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new category_l1(null, $row);
		$data[] = $item;
	}

	$search_results['category_l1'] = $data;

	// service category level 2

	$category_l2_keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$category_l2_keyword_where = " ( category_l2s.category_l2_name LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$category_l2_keyword_where .= "  category_l2s.category_l2_summary LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
	}

	$query = "SELECT
				category_l2s.category_l2_id,
				category_l2s.category_l1_id,
				category_l2s.category_l2_name,
				category_l2s.image_id,
				category_l2s.banner_id,
				category_l2s.category_l2_summary,
				category_l2s.category_l2_body,
				category_l2s.category_l2_url,
				category_l2s.category_l2_meta_title,
				category_l2s.category_l2_meta_description,
				category_l2s.category_l2_status
			FROM category_l2s
			WHERE
				category_l2s.category_l2_status <> 'deleted' AND
				$category_l2_keyword_where
				1=1
			ORDER BY category_l2s.category_l2_name
			$limit	
			";

	# print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new category_l2(null, $row);
		$data[] = $item;
	}

	$search_results['category_l2'] = $data;

	// service category level 3

	$category_l3_keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$category_l3_keyword_where = " ( category_l3s.category_l3_name LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$category_l3_keyword_where .= "  category_l3s.category_l3_summary LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
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
			WHERE
				category_l3s.category_l3_status <> 'deleted' AND
				$category_l3_keyword_where
				1=1
			ORDER BY category_l3s.category_l3_name
			$limit	
			";

	# print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new category_l3(null, $row);
		$data[] = $item;
	}

	$search_results['category_l3'] = $data;

	// news

	$news_keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$news_keyword_where = " ( news.news_name LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . " OR ";
		$news_keyword_where .= "  news.news_body LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
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
				$news_keyword_where
				1=1
			ORDER BY news.news_name
			$limit	
			";

	# print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new news(null, $row);
		$data[] = $item;
	}

	$search_results['news'] = $data;

	// case studies

	$case_study_keyword_where = "";

	if (array_key_exists ("keyword", $options)) {
		$case_study_keyword_where .= "  ( case_studies.case_study_body LIKE " . Database::make_sql_value ("%" . $options['keyword'] . "%") . ") AND ";
	}

	$query = "SELECT
				case_studies.case_study_id,
				case_studies.case_study_status,
				case_studies.case_study_name,
				case_studies.case_study_category_id,
				case_studies.case_study_service_type,
				case_studies.case_study_body,
				case_studies.case_study_quote_name,
				case_studies.image_id,
				case_studies.banner_id,
				case_studies.logo_id,
				case_studies.case_study_url,
				case_studies.case_study_quote,
				case_studies.case_study_summary,
				case_studies.case_study_meta_description,
				case_studies.case_study_meta_title
			FROM case_studies
			WHERE
				case_studies.case_study_status <> 'deleted' AND
				$case_study_keyword_where
				1=1
			ORDER BY case_studies.case_study_name
			$limit	
			";

	# print $query;
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$item = new case_study(null, $row);
		$data[] = $item;
	}

	$search_results['case_studies'] = $data;


	return $search_results;

}

