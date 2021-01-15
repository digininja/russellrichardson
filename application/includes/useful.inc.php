<?php
function get_top_image_filename_vip ($position, $venue_id, $article_id, $vip_id) {
	if (!is_null ($article_id)) {
		if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/article_" . $article_id . "_top_" . $position . ".jpg")) {
			$image = "/userfiles/images/article_" . $article_id . "_top_" . $position . ".jpg";
		} else {
			$article = new article();
			$article->load ($article_id);
			$parent = $article->get_parent();
			if (!is_null ($parent) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/article_" . $parent->get_id() . "_top_" . $position . ".jpg")) {
				$image = "/userfiles/images/article_" . $parent->get_id() . "_top_" . $position . ".jpg";
			} elseif (!is_null ($vip_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/vip_" . $vip_id . "_top_" . $position . ".jpg")) {
				$image = "/userfiles/images/vip_" . $vip_id . "_top_" . $position . ".jpg";
			} elseif (!is_null ($venue_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg")) {
				$image = "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg";
			} else {
				$image = "/images/venue_detail_sample_img2.jpg";
			}
		}
	} elseif (!is_null ($vip_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/vip_" . $vip_id . "_top_" . $position . ".jpg")) {
		$image = "/userfiles/images/vip_" . $vip_id . "_top_" . $position . ".jpg";
	} elseif (!is_null ($venue_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg")) {
		$image = "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg";
	} else {
		$image = "/images/venue_detail_sample_img2.jpg";
	}
	return $image;
}
function get_top_image_filename ($position, $venue_id, $article_id) {
	if (!is_null ($article_id)) {
		if (file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/article_" . $article_id . "_top_" . $position . ".jpg")) {
			$image = "/userfiles/images/article_" . $article_id . "_top_" . $position . ".jpg";
		} else {
			$article = new article();
			$article->load ($article_id);
			$parent = $article->get_parent();
			if (!is_null ($parent) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/article_" . $parent->get_id() . "_top_" . $position . ".jpg")) {
				$image = "/userfiles/images/article_" . $parent->get_id() . "_top_" . $position . ".jpg";
			} elseif (!is_null ($venue_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg")) {
				$image = "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg";
			} else {
				$image = "/images/venue_detail_sample_img2.jpg";
			}
		}
	} elseif (!is_null ($venue_id) && file_exists ($_SERVER['DOCUMENT_ROOT'] . "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg")) {
		$image = "/userfiles/images/" . $venue_id . "_top_" . $position . ".jpg";
	} else {
		$image = "/images/venue_detail_sample_img2.jpg";
	}
	return $image;
}

if (function_exists ("date_default_timezone_set")) {
	date_default_timezone_set ('Europe/London');
}

function strip_paragraphs ($string) {
	$body = strip_tags ($string, "<img>,<a>");
#	if (strpos ($body, 'lang="popup"') !== false) {
#		$body = '<div style="background:none" class="gallery">' . $body . "</div>";
#	}
	if (strpos ($body, 'charset="popup"') !== false) {
		$body = '<div style="background:none" class="gallery">' . $body . "</div>";
	}
	return $body;
}

function style_first_paragraph ($string, $style = "") {
	$ret = preg_replace ("/<p>/", '<p class="' . $style . '">', $string, 1);
	$ret = preg_replace ("/<a([^>]*)href=\"([^\.]*\.pdf)\"([^>]*)>/i", "<a$1href=\"$2\"$3 onClick=\"javascript: pageTracker._trackPageview('$2');\">", $ret);

	# remove line breaks as they can be in the middle of an anchor and image group so that breaks the regex
	$ret = preg_replace ("/\n/", "", $ret);
	$ret = preg_replace ("/\r/", "", $ret);

	#Put each anchor tag on its own line
	$ret = preg_replace ("/<\/a>/", "</a>\n\r", $ret);
	$ret = preg_replace ("/<a/", "\n\r<a", $ret);
	if (preg_match ("/<a[^>]*charset=\"popup\"[^>]*>.*<img[^>]*>.*/i", $ret, $matches)) {
		$ret = preg_replace ("/(<a[^>]*charset=\"popup\"[^>]*>.*<img[^>]*>.*<\/a>)/i", '<div style="background:none" class="gallery">$1</div>', $ret);
	}
#	if (preg_match ("/<a[^>]*charset=\"popup\"[^>]*>/i", $ret, $matches)) {
#		$ret = '<div style="background:none" class="gallery">' . $ret . '</div>';
#	}
#	if (preg_match ("/<a[^>]*charset=\"popup\"[^>]*>/i", $ret, $matches)) {
#		$ret = preg_replace ("/(<a[^>]*>)(.*<\/a>)/i", '<div style="background:none" class="gallery">$1$2</div>', $ret);
#	}

#	if (strpos ($ret, 'lang="popup"') !== false) {
#		$ret = '<div style="background:none" class="gallery">' . $ret . "</div>";
#	}
#	if (strpos ($ret, 'charset="popup"') !== false) {
#		$ret = '<div style="background:none" class="gallery">' . $ret . "</div>";
#	}
	return $ret;
}

function parse_date ($date_str) {
	if (preg_match ("/(\d+)\/(\d+)\/(\d+)/", $date_str, $matches)) {
		$date = mktime (0,0,0,$matches[2], $matches[1], $matches[3]);
	} else {
		$date = strtotime ($date_str);
	}
	return $date;
}
function get_return_url ($return) {
	switch ($return) {
		case "index":
			$return_url = "index.php";
			break;
		case "company_list":
			$return_url = "company_list.php";
			break;
		case "division_list":
			$return_url = "division_list.php";
			break;
		case "contact_list":
			$return_url = "contact_list.php";
			break;
		case "communication_add":
			$return_url = "communication_add.php";
			break;
		default:
			$return_url = "index.php";
	}
	return ($return_url);
}

function format_date ($date, $options = null) {
	if (is_null ($date) || (!is_numeric ($date)) || ($date < 0)) {
		return $date;
	}
	if ($options == DATE_ONLY) {
		return date ("d M Y", $date);
	} elseif ($options == TIME_ONLY) {
		return date ("H:i", $date);
	} else {
		return date ("d M Y H:i", $date);
	}
}

function format_quantity ($value) {
	if (is_null ($value) || !is_numeric ($value)) {
		return $value;
	}
	return round ($value, 2);
}
function format_money ($value) {
	if (is_null ($value) || !is_numeric ($value)) {
		return $value;
	}
	return number_format ($value, 2);
}
function get_and_inc_tabindex() {
	global $tabindex;
	$current = $tabindex;
	$tabindex++;
	return $current;
}
function select_selected ($a, $b) {
	if ($a == $b) {
		return ' selected="selected" ';
	} else {
		return "";
	}
}
function select_if_in_array ($array, $b) {
	#print "checking $b in " . print_r ($array, true) ;
	if (in_array ($b, $array)){
		return ' selected="selected" ';
	} else {
		return "";
	}
}
function check_if_in_array ($array, $b) {
	if (in_array ($b, $array)){
		return ' checked="checked" ';
	} else {
		return "";
	}
}
function check_checked ($a, $b) {
	#print "comparing '$a' with '$b'<br />";
	if ($a == $b) {
		return ' checked="checked" ';
	} else {
		return "";
	}
}
function list_to_array($list) {
	return split (",", $list);
}
function clean_display_string ($value) {
	$a =  htmlentities ($value);
	$a = str_replace(chr(194), "", $a);
	$b = str_replace("&Acirc;", "", $a);
	return $b;
}	
function nl2p($text, $cssClass=''){
	// Return if there are no line breaks.
	if (!strstr($text, "\n")) {
		return $text;
	}

	// Add Optional css class
	if (!empty($cssClass)) {
		$cssClass = ' class="' . $cssClass . '" ';
	}

	// put all text into <p> tags
	$text = '<p' . $cssClass . '>' . $text . '</p>';

	// replace all newline characters with paragraph
	// ending and starting tags
	$text = str_replace("\n", "</p>\n<p" . $cssClass . '>', $text);

	// remove empty paragraph tags & any cariage return characters
	$text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "\r"), '', $text);

	return $text;
}

if (!function_exists ("fputcsv")) {
	function fputcsv ($fp, $array, $deliminator=",") {
		$line = "";
		foreach($array as $val) {
			# remove any windows new lines,
			# as they interfere with the parsing at the other end
			$val = str_replace("\r\n", "\n", $val);

			# if a deliminator char, a double quote char or a newline
			# are in the field, add quotes
			if(ereg("[$deliminator\"\n\r]", $val)) {
				$val = '"'.str_replace('"', '""', $val).'"';
			}

			$line .= $val.$deliminator;
		}

		# strip the last deliminator
		$line = substr($line, 0, (strlen($deliminator) * -1));
		# add the newline
		$line .= "\n";

		# we don't care if the file pointer is invalid,
		# let fputs take care of it
		return fputs($fp, $line);
	}
}

function validate_email($address) {
	$result = (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@.*$/i" , $address));
	return $result;
}   

function validate_password ($password) {
	return (strlen ($password) > 7);
}
	
if ( ! function_exists ( 'mime_content_type')) {
	function mime_content_type ($f) {
		return trim ( exec ('file -bi ' . escapeshellarg ( $f ) ) ) ;
    }
}
?>
