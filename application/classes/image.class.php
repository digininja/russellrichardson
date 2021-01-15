<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/image_db.class.php");

class image {
	var $_image_db;
	var $_dirty;
	var $_tmp_name;
	var $_extension;

	function _make_clean() {
		$this->_dirty['image'] = false;
	}
	
	function __construct ($id = null, $data = null) {
		if (!is_null ($id) && (is_int ($id))) {
			$this->_image_db = new image_db($id);
		} elseif (!is_null ($data)) {
			$image_db = new image_db();
			$image_db->populate ($data);
			$this->_image_db = $image_db;
		} elseif (is_null ($data) && is_null ($id)) {
			$this->_image_db = new image_db();
		} else {
			trigger_error ("Invalid values passed to object constructor", E_USER_ERROR);
		}
		$this->_make_clean();
		$this->_tmp_name = null;
		$this->_extension = null;
	}

	function load ($id) {
		return ($this->_load());
	}
	function load_if_exists ($id) {
		if (!is_int ($id)) {
			trigger_error ("Blank id passed to load function", E_USER_ERROR);
			return false;
		}
		if ($this->_image_db->load_if_exists ($id)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function bulk_import ($filename) {
		$image_info = getimagesize($filename);

		if (!preg_match ("/^image\//", $image_info['mime'])) {
			return false;
		}

		$this->set_width ($image_info[0]);
		$this->set_height ($image_info[1]);
		$this->set_mime_type ($image_info['mime']);
		
		$path_parts = pathinfo($filename);
		$this->set_filename ($path_parts['basename']);
		$this->_extension = $path_parts['extension'];

		return true;
	}

	function create_from_upload ($file_array) {
		if (!is_array ($file_array)) {
			trigger_error ("Array not passed to create_from_upload", E_USER_ERROR);
		}

		if (array_key_exists ("error", $file_array)) {
			if ($file_array['error'] != UPLOAD_ERR_OK) {
				return false;
			}

			if ($file_array['size'] == 0) {
				return false;
			}

			if (array_key_exists ("tmp_name", $file_array)) {
				$image_info = getimagesize($file_array["tmp_name"]);

				if (!preg_match ("/^image\//", $image_info['mime'])) {
					return false;
				}

				$this->set_width ($image_info[0]);
				$this->set_height ($image_info[1]);
				$this->set_mime_type ($image_info['mime']);
			} else {
				return false;
			}
		} else {
			return false;
		}
		$this->_tmp_name = $file_array["tmp_name"];
		$path_parts = pathinfo($file_array["name"]);
		$this->_extension = $path_parts['extension'];

		$this->_dirty['image'] = true;

		return true;
	}

	function delete() {
		$this->set_status (STATUS_DELETED);
		return ($this->save());
	}

	function my_clone() {
		$this->set_id (null);
		$this->_tmp_name = null;

		$path_parts = pathinfo($this->get_filename());
		if (array_key_exists ("extension", $path_parts)) {
			$extension = $path_parts['extension'];
		} else {
			$extension = "";
		}

		$new_filename = md5(time() . mt_rand()) . "." . $extension;
		copy ($_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $this->get_filename(), $_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $new_filename);
	#	print "copying from " . $_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $this->get_filename() . " to " . $_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $new_filename . "<br />";
		$this->set_filename ($new_filename);
		$this->_dirty['image'] = true;
	}

	function save() {
		// Only save if necessary
		if ($this->_dirty['image']) {
			if (!is_null ($this->_tmp_name)) {
				$filename = md5(time() . mt_rand()) . "." . $this->_extension;
				$this->set_filename ($filename);
				#print  ("Moving from: " . $this->_tmp_name . " to " . $_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $filename);
				move_uploaded_file ($this->_tmp_name, $_SERVER['DOCUMENT_ROOT'] . IMAGE_FILE_PREFIX . $filename);
			}
			$this->_image_db->save();
		}
		$this->_make_clean();
		return true;
	}
	
	function get_thumbnail_src() {
		return IMAGE_THUMBNAIL_SRC_PREFIX . $this->get_filename();
	}
	function get_src() {
		return IMAGE_SRC_PREFIX . $this->get_filename();
	}

	function get_status() {
		return ($this->_image_db->get_status());
	}
	function get_id() {
		return ($this->_image_db->get_id());
	}
	function get_gallery_id() {
		return ($this->_image_db->get_gallery_id());
	}
	function get_filename() {
		return ($this->_image_db->get_filename());
	}
	function get_width() {
		return ($this->_image_db->get_width());
	}
	function get_height() {
		return ($this->_image_db->get_height());
	}
	function get_description() {
		return ($this->_image_db->get_description());
	}
	function get_alt_text() {
		return ($this->_image_db->get_alt_text());
	}
	function get_mime_type() {
		return ($this->_image_db->get_mime_type());
	}

	function set_status($val) {
		if ($val === $this->_image_db->get_status()) {
			return true;
		}
		$this->_image_db->set_status ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_id($val) {
		if ($val === $this->_image_db->get_id()) {
			return true;
		}
		$this->_image_db->set_id ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_gallery_id($val) {
		if ($val === $this->_image_db->get_gallery_id()) {
			return true;
		}
		$this->_image_db->set_gallery_id ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_filename($val) {
		if ($val === $this->_image_db->get_filename()) {
			return true;
		}
		$this->_image_db->set_filename ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_width($val) {
		if ($val === $this->_image_db->get_width()) {
			return true;
		}
		$this->_image_db->set_width ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_height($val) {
		if ($val === $this->_image_db->get_height()) {
			return true;
		}
		$this->_image_db->set_height ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_description($val) {
		if ($val === $this->_image_db->get_description()) {
			return true;
		}
		$this->_image_db->set_description ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_alt_text($val) {
		if ($val === $this->_image_db->get_alt_text()) {
			return true;
		}
		$this->_image_db->set_alt_text ($val);
		$this->_dirty['image'] = true;
		return true;
	}
	function set_mime_type($val) {
		if ($val === $this->_image_db->get_mime_type()) {
			return true;
		}
		$this->_image_db->set_mime_type ($val);
		$this->_dirty['image'] = true;
		return true;
	}
}

?>
