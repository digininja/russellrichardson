<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/database.class.php");
require_once ("application/classes/image.class.php");

function get_image_count ($options) {
	$dbh = new database();

	$join_id_where = "";
	$join_id_inner = "";

	if (array_key_exists ("join_id", $options) && array_key_exists ("join_table", $options)) {
		$join_id_inner = " INNER JOIN images_" .  $options['join_table'] . "s ON images_" .  $options['join_table'] . "s.image_id = images.image_id ";
		$join_id_where = " images_" . $options['join_table'] . "s." .$options['join_table'] . "_id = " . database::make_sql_value ($options['join_id']) . " AND ";
	}

	$image_gallery_inner = "";
	$gallery_id_where = "";

	if (array_key_exists ("gallery_id", $options)) {
		$image_gallery_inner = " INNER JOIN galleries_images ON galleries_images.image_id = images.image_id ";
		$gallery_id_where = " galleries_images.gallery_id = " . database::make_sql_value ($options['gallery_id']) . " AND ";
	}

	$tech_article_id_where = "";
	$tech_article_id_inner = "";

	if (array_key_exists ("tech_article_id", $options)) {
		$tech_article_id_inner = " INNER JOIN tech_articles_images ON tech_articles_images.image_id = images.image_id ";
		$tech_article_id_where = " tech_articles_images.tech_article_id = " . database::make_sql_value ($options['tech_article_id']) . " AND ";
	}

	$case_study_id_where = "";
	$case_study_id_inner = "";

	if (array_key_exists ("case_study_id", $options)) {
		$case_study_id_inner = " INNER JOIN case_studies_images ON case_studies_images.image_id = images.image_id ";
		$case_study_id_where = " case_studies_images.case_study_id = " . database::make_sql_value ($options['case_study_id']) . " AND ";
	}

	$service_id_where = "";
	$service_id_inner = "";

	if (array_key_exists ("service_id", $options)) {
		$service_id_inner = " INNER JOIN services_images ON services_images.image_id = images.image_id ";
		$service_id_where = " services_images.service_id = " . database::make_sql_value ($options['service_id']) . " AND ";
	}

	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " images.image_id = " . database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " images.image_status = " . database::make_sql_value ($options['status']) . " AND ";
	}

	$gallery_id_where = "";

	if (array_key_exists ("gallery_id", $options)) {
		$gallery_id_where = " images.image_gallery_id = " . database::make_sql_value ($options['gallery_id']) . " AND ";
	}

	$filename_where = "";

	if (array_key_exists ("filename", $options)) {
		$filename_where = " images.image_filename = " . database::make_sql_value ($options['filename']) . " AND ";
	}

	$width_where = "";

	if (array_key_exists ("width", $options)) {
		$width_where = " images.image_width = " . database::make_sql_value ($options['width']) . " AND ";
	}

	$height_where = "";

	if (array_key_exists ("height", $options)) {
		$height_where = " images.image_height = " . database::make_sql_value ($options['height']) . " AND ";
	}

	$alt_text_where = "";

	if (array_key_exists ("alt_text", $options)) {
		$alt_text_where = " images.image_alt_text = " . database::make_sql_value ($options['alt_text']) . " AND ";
	}

	$mime_type_where = "";

	if (array_key_exists ("mime_type", $options)) {
		$mime_type_where = " images.image_mime_type = " . database::make_sql_value ($options['mime_type']) . " AND ";
	}


	$query = "SELECT
					COUNT(DISTINCT (images.image_id)) AS count
				FROM images
					$join_id_inner
					$image_gallery_inner
					$service_id_inner
					$case_study_id_inner
					$tech_article_id_inner
				WHERE
					image_status <> 'deleted' AND
					$service_id_where
					$case_study_id_where
					$tech_article_id_where
					$gallery_id_where
					$join_id_where
					$id_where
					$status_where
					$filename_where
					$gallery_id_where
					$width_where
					$height_where
					$alt_text_where
					$mime_type_where
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

function get_images ($options) {
	$dbh = new database();

	$join_id_where = "";
	$join_id_inner = "";

	if (array_key_exists ("join_id", $options) && array_key_exists ("join_table", $options)) {
		$join_id_inner = " INNER JOIN images_" .  $options['join_table'] . "s ON images_" .  $options['join_table'] . "s.image_id = images.image_id ";
		$join_id_where = " images_" . $options['join_table'] . "s." .$options['join_table'] . "_id = " . database::make_sql_value ($options['join_id']) . " AND ";
	}

	$image_gallery_inner = "";
	$gallery_id_where = "";

	if (array_key_exists ("gallery_id", $options)) {
		$image_gallery_inner = " INNER JOIN galleries_images ON galleries_images.image_id = images.image_id ";
		$gallery_id_where = " galleries_images.gallery_id = " . database::make_sql_value ($options['gallery_id']) . " AND ";
	}

	$tech_article_id_where = "";
	$tech_article_id_inner = "";

	if (array_key_exists ("tech_article_id", $options)) {
		$tech_article_id_inner = " INNER JOIN tech_articles_images ON tech_articles_images.image_id = images.image_id ";
		$tech_article_id_where = " tech_articles_images.tech_article_id = " . database::make_sql_value ($options['tech_article_id']) . " AND ";
	}

	$case_study_id_where = "";
	$case_study_id_inner = "";

	if (array_key_exists ("case_study_id", $options)) {
		$case_study_id_inner = " INNER JOIN case_studies_images ON case_studies_images.image_id = images.image_id ";
		$case_study_id_where = " case_studies_images.case_study_id = " . database::make_sql_value ($options['case_study_id']) . " AND ";
	}

	$service_id_where = "";
	$service_id_inner = "";

	if (array_key_exists ("service_id", $options)) {
		$service_id_inner = " INNER JOIN services_images ON services_images.image_id = images.image_id ";
		$service_id_where = " services_images.service_id = " . database::make_sql_value ($options['service_id']) . " AND ";
	}


	$id_where = "";

	if (array_key_exists ("id", $options)) {
		$id_where = " images.image_id = " . database::make_sql_value ($options['id']) . " AND ";
	}

	$status_where = "";

	if (array_key_exists ("status", $options)) {
		$status_where = " images.image_status = " . database::make_sql_value ($options['status']) . " AND ";
	}


	$gallery_id_where = "";

	if (array_key_exists ("gallery_id", $options)) {
		$gallery_id_where = " images.image_gallery_id = " . database::make_sql_value ($options['gallery_id']) . " AND ";
	}

	$filename_where = "";

	if (array_key_exists ("filename", $options)) {
		$filename_where = " images.image_filename = " . database::make_sql_value ($options['filename']) . " AND ";
	}

	$width_where = "";

	if (array_key_exists ("width", $options)) {
		$width_where = " images.image_width = " . database::make_sql_value ($options['width']) . " AND ";
	}

	$height_where = "";

	if (array_key_exists ("height", $options)) {
		$height_where = " images.image_height = " . database::make_sql_value ($options['height']) . " AND ";
	}

	$alt_text_where = "";

	if (array_key_exists ("alt_text", $options)) {
		$alt_text_where = " images.image_alt_text = " . database::make_sql_value ($options['alt_text']) . " AND ";
	}

	$mime_type_where = "";

	if (array_key_exists ("mime_type", $options)) {
		$mime_type_where = " images.image_mime_type = " . database::make_sql_value ($options['mime_type']) . " AND ";
	}

	$order_by = "";

	if (array_key_exists ("order_by", $options)) {
		switch ($options['order_by']) {
			case "id":
				$order_by = " ORDER BY images.image_id ";
				break;
			case "status":
				$order_by = " ORDER BY images.image_status ";
				break;
			case "filename":
				$order_by = " ORDER BY images.image_filename ";
				break;
			case "width":
				$order_by = " ORDER BY images.image_width ";
				break;
			case "height":
				$order_by = " ORDER BY images.image_height ";
				break;
			case "alt_text":
				$order_by = " ORDER BY images.image_alt_text ";
				break;
			case "mime_type":
				$order_by = " ORDER BY images.image_mime_type ";
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
				DISTINCT 
				images.image_id,
				images.image_status,
				images.image_filename,
				images.image_gallery_id,
				images.image_width,
				images.image_height,
				images.image_alt_text,
				images.image_description,
				images.image_mime_type
			FROM images
				$join_id_inner
				$image_gallery_inner
				$service_id_inner
				$case_study_id_inner
				$tech_article_id_inner
			WHERE
				image_status <> 'deleted' AND
				$service_id_where
				$case_study_id_where
				$tech_article_id_where
				$gallery_id_where
				$join_id_where
				$id_where
				$status_where
				$filename_where
				$gallery_id_where
				$width_where
				$height_where
				$alt_text_where
				$mime_type_where
				1 = 1
			$order_by	
			$limit	
			";

	#print "<!--" . $query . "-->";
	$result = database::execute ($query);

	$data = array();
	while ($row = mysqli_fetch_assoc ($result)) {
		$image = new image(null, $row);
		$data[$row['image_id']] = $image;
	}

	return $data;
}

?>
