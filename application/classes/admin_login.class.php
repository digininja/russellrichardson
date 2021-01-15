<?php

if (strpos (ini_get("include_path"), $_SERVER['DOCUMENT_ROOT']) === false) {
	ini_set ("include_path",  ini_get("include_path") .":" . $_SERVER['DOCUMENT_ROOT']);
}
require_once ("application/classes/admin_login_db.class.php");
require_once ("application/classes/PasswordHash.php");
require_once ("application/searches/admin_login_db.search.php");

class admin_login {
	var $_dirty;
	var $_admin_login_db;

	function _make_clean() {
		$this->_dirty['admin_login'] = false;
	}

	function __construct ($username = null, $admin_login_db = null) {
		if (!is_null($username ) && ($username != "") ) {
			$this->_admin_login_db = new admin_login_db($username);
		} elseif (!is_null ($admin_login_db)) {
			$this->_admin_login_db = $admin_login_db;
		} else {
			$this->_admin_login_db = new admin_login_db();
		}
		$this->_make_clean();
	}

	function check_login ($username, $password) {
		$options = array (
							"username" => $username,
							"status" => STATUS_ACTIVE,
						);
		$admin_login_dbs = get_admin_logins ($options);

		if (count ($admin_login_dbs) == 1) {
			$admin_login_db = array_pop ($admin_login_dbs);

			$t_hasher = new PasswordHash(8, FALSE);
			$check = $t_hasher->CheckPassword($password, $admin_login_db->get_password());

			if ($check) {
				$this->_admin_login_db = ($admin_login_db);
				return true;
			}
		}

		$this->_admin_login_db = null;
		return false;
	}

	function load ($username) {
		if (is_null ($username) || ($username == "")) {
			trigger_error ("Blank username passed to load function", E_USER_ERROR);
			return false;
		}
		if ($this->_admin_login_db->load ($username)) {
			$this->_make_clean();
			return true;
		} else {
			return false;
		}
	}

	function delete() {
		$this->set_status (STATUS_DELETED);
		return ($this->save());
	}

	function save() {
		// Save the admin_login if necessary
		if ($this->_dirty['admin_login']) {
			$this->_admin_login_db->save();
		}

		$this->_make_clean();
		return true;
	}

	function get_username() {
		return ($this->_admin_login_db->get_username());
	}
	function get_status() {
		return ($this->_admin_login_db->get_status());
	}
	function get_password() {
		return ($this->_admin_login_db->get_password());
	}
	function get_name() {
		return ($this->_admin_login_db->get_name());
	}
	function get_level() {
		return ($this->_admin_login_db->get_level());
	}
	function get_description() {
		return ($this->_admin_login_db->get_description());
	}

	function set_username ($val) {
		if ($val == $this->_admin_login_db->get_username()) {
			return true;
		}
		$this->_admin_login_db->set_username ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
	function set_status ($val) {
		if ($val == $this->_admin_login_db->get_status()) {
			return true;
		}
		$this->_admin_login_db->set_status ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
	function set_password ($val) {
		if ($val == $this->_admin_login_db->get_password()) {
			return true;
		}
		$this->_admin_login_db->set_password ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
	function set_name ($val) {
		if ($val == $this->_admin_login_db->get_name()) {
			return true;
		}
		$this->_admin_login_db->set_name ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
	function set_level ($val) {
		if ($val == $this->_admin_login_db->get_level()) {
			return true;
		}
		$this->_admin_login_db->set_level ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
	function set_description ($val) {
		if ($val == $this->_admin_login_db->get_description()) {
			return true;
		}
		$this->_admin_login_db->set_description ($val);
		$this->_dirty['admin_login'] = true;
		return true;
	}
}

?>
