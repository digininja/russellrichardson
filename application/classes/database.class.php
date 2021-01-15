<?php

class database {
	private static $link;

	private static function connect() {
	/* Connecting, selecting database */
		self::$link = mysqli_connect(DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD)
			or trigger_error ("Could not connect : " . mysqli_error(self::$link), E_USER_ERROR);
		mysqli_select_db(self::$link, DATABASE_NAME) or trigger_error ("Could not select database", E_USER_ERROR);
	}
	
	public static function execute ($query) {
		/*
		$disp_query = preg_replace ("/SELECT/", "<strong>SELECT</strong>", $query);
		print $disp_query . "<br>";
		*/
		if (is_null (self::$link)) {
			self::connect();
		}
		$result = mysqli_query(self::$link, $query) or trigger_error ("Query failed : " . mysqli_error(database::$link), E_USER_ERROR);
		return $result;
	}

	public static function get_insert_id() {
		return mysqli_insert_id(self::$link);
	}

	/* Closing connection */
	public static function close() {
		mysqli_close(self::$link);
	}

	public static function make_sql_date ($date, $options = null) {
		if (is_null ($date)) {
			return null;
		}
		if (!is_int ($date)) {
			trigger_error ("Invalid date ($date) passed to make_sql_date", E_USER_ERROR);
			return false;
		}
		if (is_null ($options)) {
			return date ("Y-m-d H:i:s", $date);
		} elseif ($options == DATE_ONLY) {
			return date ("Y-m-d", $date);
		} elseif ($options == TIME_ONLY) {
			return date ("H:i:s", $date);
		}
	}
	
	public static function make_sql_value ($value) {
		switch (gettype ($value)) {
			case "boolean":
				if ($value) {
					return 1;
				} else {
					return 0;
				}
				break;
			case "integer":
			case "double":
				return floatval ($value);
				break;
			case "string":
				return "'" . addslashes ($value) . "'";
				break;
			case "NULL":
				return "null";
				break;
			default:
				trigger_error ("make_sql_value doesn't know how to handle type " . gettype ($value), E_USER_ERROR);
		}
		return $value;
	}
}
?>
