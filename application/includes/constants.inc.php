<?php
require_once ("database.inc.php");

/** who error emails go to */
define ("ERROR_MAIL_TO", "rr-error@freedomsoftware.co.uk");
/** subject line for error emails */
if (array_key_exists ("HTTP_HOST", $_SERVER)) {
	define ("ERROR_MAIL_SUBJECT", "error on " . $_SERVER['HTTP_HOST']);
} else {
	define ("ERROR_MAIL_SUBJECT", "error on RR website");
}

define ('PAGE_SIZE', 3);

define ("STATUS_ACTIVE", "active");
define ("STATUS_INACTIVE", "inactive");
define ("STATUS_DELETED", "deleted");

define ("YES", 'yes');
define ("NO", 'no');

define ("DATE_ONLY", 1);
define ("TIME_ONLY", 2);

define ("PDF_HREF_PREFIX", "/userfiles/pdfs/");
define ("PDF_FILE_PREFIX", "/userfiles/pdfs/");
define ("IMAGE_SRC_PREFIX", "/userfiles/images/");
define ("IMAGE_THUMBNAIL_SRC_PREFIX", "/userfiles/thumbnails/");
define ("IMAGE_FILE_PREFIX", "/userfiles/images/");
define ("FILE_FILE_PREFIX", "/userfiles/files/");

define ("SUPER_USER", 100); // For admin logins. DB default is 0

define ("CATEGORY_GALLERY", 1);
define ("NEWS_GALLERY", 2);
define ("CMS_GALLERY", 3);



?>
